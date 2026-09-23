<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoWebhookEvent;
use App\Services\BunnyStreamProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BunnyWebhookController extends Controller
{
    public function __construct(protected BunnyStreamProvider $bunny)
    {
        // No auth middleware - webhook is called by Bunny Stream
    }

    /**
     * Handle Bunny Stream webhook events.
     * Endpoint: POST /webhooks/bunny/video
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['EventType'] ?? $payload['eventType'] ?? 'unknown';

        Log::info('Bunny webhook received', [
            'event_type' => $eventType,
            'payload' => $payload,
        ]);

        // Store webhook event for idempotency
        $providerVideoId = $payload['VideoGuid'] ?? $payload['videoGuid'] ?? null;

        if (! $providerVideoId) {
            return response()->json(['status' => 'ignored', 'reason' => 'missing_video_guid'], 200);
        }

        // Check if already processed
        $existing = VideoWebhookEvent::where('provider_video_id', $providerVideoId)
            ->where('event_type', $eventType)
            ->first();

        if ($existing) {
            if ($existing->status === VideoWebhookEvent::STATUS_PROCESSED) {
                return response()->json(['status' => 'already_processed'], 200);
            }
        } else {
            $existing = VideoWebhookEvent::create([
                'provider' => 'bunny',
                'event_type' => $eventType,
                'provider_video_id' => $providerVideoId,
                'payload' => $payload,
                'status' => VideoWebhookEvent::STATUS_PENDING,
            ]);
        }

        // Dispatch job for async processing
        \App\Jobs\ProcessBunnyWebhook::dispatch($existing->id)->onQueue('video');

        return response()->json(['status' => 'accepted'], 200);
    }
}