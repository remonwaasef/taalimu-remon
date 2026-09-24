<?php

namespace App\Services;

use App\Interfaces\VideoProviderInterface;
use App\Models\OnlineClass;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Zoom integration via the Meetings API + Web Meeting SDK.
 *
 * Security model:
 *  - Server-to-Server OAuth for all management calls (token cached ~55 min).
 *  - Meeting SDK signatures are generated here and are short-lived; SDK
 *    secrets never reach the browser.
 */
class ZoomService implements VideoProviderInterface
{
    private const API_BASE = 'https://api.zoom.us/v2';

    private const OAUTH_URL = 'https://zoom.us/oauth/token';

    public function isConfigured(): bool
    {
        return ! empty(config('services.zoom.account_id'))
            && ! empty(config('services.zoom.client_id'))
            && ! empty(config('services.zoom.client_secret'))
            && ! empty(config('services.zoom.sdk_key'))
            && ! empty(config('services.zoom.sdk_secret'));
    }

    public function getName(): string
    {
        return 'zoom';
    }

    /**
     * Create the scheduled meeting on Zoom and return its identifiers.
     */
    public function createSession(OnlineClass $class): array
    {
        $settings = [
            'auto_recording' => $class->auto_recording ? 'cloud' : 'none',
            'join_before_host' => false,
            'waiting_room' => true,
            'mute_upon_entry' => true,
            'approval_type' => 2, // no registration required — access is enforced by Taalimu
            'meeting_invitees' => [],
        ];

        $response = $this->post('/users/me/meetings', [
            'topic' => mb_substr($class->title, 0, 200),
            'type' => 2, // scheduled meeting
            'start_time' => $class->start_time->copy()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z'),
            'duration' => max(1, (int) $class->duration_minutes),
            'timezone' => 'UTC',
            'default_password' => true,
            'settings' => $settings,
        ])->throw()->json();

        return [
            'external_id' => (string) $response['id'],
            'external_uuid' => $response['uuid'] ?? null,
            'join_url' => $response['join_url'] ?? '',
            'password' => $response['password'] ?? null,
            'account_id' => config('services.zoom.account_id'),
        ];
    }

    public function updateSession(OnlineClass $class): void
    {
        if (! $class->meeting_id || str_starts_with((string) $class->meeting_id, 'manual-')) {
            return;
        }

        $this->patch("/meetings/{$class->meeting_id}", [
            'topic' => mb_substr($class->title, 0, 200),
            'start_time' => $class->start_time->copy()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z'),
            'duration' => max(1, (int) $class->duration_minutes),
            'settings' => [
                'auto_recording' => $class->auto_recording ? 'cloud' : 'none',
            ],
        ])->throw();
    }

    public function deleteSession(OnlineClass $class): void
    {
        if (! $class->meeting_id || str_starts_with((string) $class->meeting_id, 'manual-')) {
            return;
        }

        try {
            $this->delete("/meetings/{$class->meeting_id}");
        } catch (\Throwable $e) {
            // Best-effort: class deletion must not fail because Zoom is down.
            Log::warning('Zoom deleteSession failed', ['meeting_id' => $class->meeting_id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Meeting SDK signature (JWT-style HMAC) for embedding the client inside
     * Taalimu pages. Short-lived and role-bound (host vs attendee).
     *
     * @return array{signature: string, meeting_number: string, role: int, sdk_key: string, expires_at: int}
     */
    public function getJoinContext(OnlineClass $class, User $user, bool $isHost): array
    {
        $sdkKey = (string) config('services.zoom.sdk_key');
        $sdkSecret = (string) config('services.zoom.sdk_secret');

        $iat = time() - 30;
        $exp = $iat + 60 * 120; // 2 hours — covers the whole session window
        $role = $isHost ? 1 : 0;

        $b64 = fn (string $value): string => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');

        $header = $b64(json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_UNESCAPED_SLASHES));
        $payload = $b64((string) json_encode([
            'appKey' => $sdkKey,
            'sdkKey' => $sdkKey,
            'mn' => $class->meeting_id,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $exp,
        ], JSON_UNESCAPED_SLASHES));

        $signature = $b64(hash_hmac('sha256', "{$header}.{$payload}", $sdkSecret, true));

        return [
            'signature' => "{$header}.{$payload}.{$signature}",
            'meeting_number' => (string) $class->meeting_id,
            'role' => $role,
            'sdk_key' => $sdkKey,
            'expires_at' => $exp,
        ];
    }

    /**
     * Fetch the completed cloud recording metadata for a meeting.
     * Prefers the combined MP4 playback file.
     */
    public function fetchRecording(string $meetingId): array
    {
        $data = $this->get("/meetings/{$meetingId}/recordings")->throw()->json();

        $file = collect($data['recording_files'] ?? [])
            ->filter(fn ($f) => ($f['file_type'] ?? '') === 'MP4')
            ->sortByDesc(fn ($f) => ($f['recording_type'] ?? '') === 'shared_screen_with_speaker_view' ? 1 : 0)
            ->first();

        if (! $file) {
            throw new \RuntimeException('No MP4 recording file found for meeting '.$meetingId);
        }

        return [
            'external_id' => (string) ($file['id'] ?? $data['uuid']),
            'download_url' => $file['download_url'],
            'file_size' => isset($file['file_size']) ? (int) $file['file_size'] : null,
            'duration_seconds' => isset($data['duration']) ? ((int) $data['duration'] * 60) : null,
            'recording_type' => $file['recording_type'] ?? 'unknown',
        ];
    }

    /**
     * Direct browser uploads are handled by Bunny Stream for recorded video courses.
     */
    public function createDirectUpload(Video $video): array
    {
        throw new \BadMethodCallException('Zoom does not support direct video upload.');
    }

    /*
    |----------------------------------------------------------------
    | Low-level authenticated HTTP helpers
    |----------------------------------------------------------------
    */

    public function get(string $uri): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->accessToken())
            ->acceptJson()
            ->retry(2, 300, throw: false)
            ->get(self::API_BASE.$uri);
    }

    public function post(string $uri, array $body): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->accessToken())
            ->acceptJson()
            ->asJson()
            ->retry(1, 300, throw: false)
            ->post(self::API_BASE.$uri, $body);
    }

    public function patch(string $uri, array $body): \Illuminate\Http\Client\Response
    {
        return Http::withToken($this->accessToken())
            ->acceptJson()
            ->asJson()
            ->patch(self::API_BASE.$uri, $body);
    }

    public function delete(string $uri): void
    {
        Http::withToken($this->accessToken())->delete(self::API_BASE.$uri)->throw();
    }

    /**
     * Server-to-Server OAuth access token, cached until shortly before expiry.
     */
    public function accessToken(): string
    {
        return Cache::remember('zoom:oauth:access_token', now()->addMinutes(55), function () {
            $accountId = (string) config('services.zoom.account_id');
            $clientId = (string) config('services.zoom.client_id');
            $clientSecret = (string) config('services.zoom.client_secret');

            $response = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->post(self::OAUTH_URL, [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ]);

            if ($response->failed()) {
                Log::error('Zoom OAuth token request failed', ['status' => $response->status()]);
                throw new ConnectionException('Unable to authenticate with Zoom.');
            }

            return (string) $response->json('access_token');
        });
    }
}
