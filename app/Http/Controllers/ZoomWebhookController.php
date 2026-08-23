<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessClassRecordingJob;
use App\Models\OnlineClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Zoom webhook receiver.
 *
 * Security:
 *  1. Endpoint validation challenge answered with the plain token.
 *  2. Every event verified via X-Zm-Signature (HMAC-SHA256 of v0:{ts}:{body}).
 *  3. Replay protection: timestamp tolerance + event-id dedupe cache.
 *  4. Processing is queued and idempotent (unique provider recording ids).
 */
class ZoomWebhookController extends Controller
{
    private const REPLAY_TOLERANCE_SECONDS = 300;

    public function handle(Request $request): JsonResponse
    {
        $raw = $request->getContent();
        $payload = json_decode($raw, true);

        if (! is_array($payload) || empty($payload['event'])) {
            Log::channel('security')->warning('Zoom webhook rejected: malformed payload');

            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 1) Endpoint URL validation handshake.
        if ($payload['event'] === 'endpoint.url.validation') {
            return response()->json([
                'plainToken' => $payload['payload']['plainToken'] ?? '',
            ]);
        }

        // 2) Signature verification.
        if (! $this->signatureValid($request, $raw)) {
            Log::channel('security')->warning('Zoom webhook rejected: invalid signature', ['event' => $payload['event']]);

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $eventId = $payload['event_ts'].'.'.$payload['payload']['object']['id'] ?? ($payload['event'].'.'.($payload['event_ts'] ?? ''));

        // 3) Replay / duplicate guard.
        if (! Cache::add("zoom:event:{$eventId}", true, now()->addDays(3))) {
            return response()->json(['message' => 'Duplicate event ignored']); // idempotent no-op
        }

        try {
            match ($payload['event']) {
                'meeting.started' => $this->onMeetingStarted($payload),
                'meeting.ended' => $this->onMeetingEnded($payload),
                'recording.completed' => $this->onRecordingCompleted($payload),
                default => null,
            };
        } catch (\Throwable $e) {
            // Never fail the webhook; log and acknowledge.
            Log::error('Zoom webhook processing error', ['event' => $payload['event'], 'error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'ok']);
    }

    protected function signatureValid(Request $request, string $raw): bool
    {
        $secret = (string) config('services.zoom.webhook_secret_token');

        if ($secret === '') {
            Log::channel('security')->warning('Zoom webhook secret not configured');

            return false;
        }

        $header = (string) $request->header('x-zm-signature');
        $timestamp = (string) $request->header('x-zm-request-timestamp');

        if (! str_starts_with($header, 'v0=') || ! ctype_digit($timestamp)) {
            return false;
        }

        if (abs(time() - (int) $timestamp) > self::REPLAY_TOLERANCE_SECONDS) {
            Log::channel('security')->warning('Zoom webhook rejected: replay suspected', ['timestamp' => $timestamp]);

            return false;
        }

        $expected = 'v0='.hash_hmac('sha256', "v0:{$timestamp}:{$raw}", $secret);

        return hash_equals($expected, $header);
    }

    /**
     * Find the Taalimu class behind a Zoom object id.
     */
    protected function findClass(array $payload): ?OnlineClass
    {
        $meetingId = (string) data_get($payload, 'payload.object.id');
        $meetingUuid = (string) data_get($payload, 'payload.object.uuid');

        return OnlineClass::withoutGlobalScopes()
            ->where(function ($q) use ($meetingId, $meetingUuid) {
                $q->where('meeting_id', $meetingId)
                    ->orWhere('zoom_meeting_uuid', $meetingUuid);
            })
            ->first();
    }

    protected function onMeetingStarted(array $payload): void
    {
        $class = $this->findClass($payload);

        if (! $class || $class->status === OnlineClass::STATUS_COMPLETED) {
            return;
        }

        $class->forceFill([
            'status' => OnlineClass::STATUS_LIVE,
            'started_at' => now(),
        ])->save();

        \App\Jobs\NotifyClassStartedJob::dispatch($class->id);
    }

    protected function onMeetingEnded(array $payload): void
    {
        $class = $this->findClass($payload);

        if (! $class) {
            return;
        }

        app(\App\Services\OnlineClassService::class)->endClass($class);
    }

    protected function onRecordingCompleted(array $payload): void
    {
        $class = $this->findClass($payload);

        if (! $class) {
            Log::warning('Zoom recording.completed for unknown meeting', [
                'meeting_id' => data_get($payload, 'payload.object.id'),
            ]);

            return;
        }

        ProcessClassRecordingJob::dispatch(
            $class->id,
            data_get($payload, 'payload.object.uuid'),
            data_get($payload, 'payload.object.host_id')
        )->onQueue('default');
    }
}
