<?php

namespace App\Services;

use App\Interfaces\VideoProviderInterface;
use App\Models\Video;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Bunny Stream integration for recorded video courses.
 *
 * Security model:
 *  - API key stays server-side; never exposed to frontend.
 *  - Creates direct upload authorization for TUS upload from browser.
 *  - Webhook endpoint validates signatures and updates video status.
 */
class BunnyStreamProvider implements VideoProviderInterface
{
    private const API_BASE = 'https://video.bunnycdn.com/library';
    private const CDN_BASE = 'https://video.bunnycdn.com/library';

    public function getName(): string
    {
        return 'bunny';
    }

    public function isConfigured(): bool
    {
        return ! empty(config('services.bunny.library_id'))
            && ! empty(config('services.bunny.api_key'));
    }

    // ─────────────────────────────────────────────────────────────
    // Live session methods (not used for recorded videos)
    // ─────────────────────────────────────────────────────────────

    public function createSession($class): array
    {
        throw new \BadMethodCallException('Bunny Stream does not support live sessions.');
    }

    public function updateSession($class): void
    {
        // Not applicable for recorded videos
    }

    public function deleteSession($class): void
    {
        // Not applicable for recorded videos
    }

    public function getJoinContext($class, $user, bool $isHost): array
    {
        throw new \BadMethodCallException('Bunny Stream does not support live sessions.');
    }

    public function fetchRecording(string $meetingId): array
    {
        throw new \BadMethodCallException('Bunny Stream does not fetch live recordings.');
    }

    // ─────────────────────────────────────────────────────────────
    // Direct Upload for Recorded Videos
    // ─────────────────────────────────────────────────────────────

    /**
     * Create a direct upload authorization for a video.
     * Returns TUS upload URL and authorization token for direct browser upload.
     *
     * @param  Video  $video  The video record to authorize upload for
     * @return array{upload_url: string, token: string, video_id: string, library_id: string}
     */
    public function createDirectUpload(Video $video): array
    {
        $libraryId = config('services.bunny.library_id');
        $apiKey = config('services.bunny.api_key');

        if (! $libraryId || ! $apiKey) {
            throw new \RuntimeException('Bunny Stream not configured.');
        }

        // Create video in Bunny library
        $response = Http::withHeaders([
            'AccessKey' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->getBaseUrl()}/{$libraryId}/videos", [
            'title' => $video->title ?? 'Untitled',
            'collection' => config('services.bunny.collection_id') ?? '',
        ]);

        if ($response->failed()) {
            Log::error('Bunny video creation failed', [
                'video_id' => $video->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Failed to create video in Bunny Stream: ' . $response->body());
        }

        $data = $response->json();

        // Store Bunny identifiers on video
        $video->update([
            'provider_video_id' => (string) $data['guid'],
            'provider_library_id' => $libraryId,
            'status' => Video::STATUS_UPLOADING,
        ]);

        // Get TUS upload URL and token
        $tusUrl = "{$this->getBaseUrl()}/{$libraryId}/videos/{$data['guid']}";
        $token = $this->generateAccessToken($libraryId, $data['guid']);

        return [
            'upload_url' => $tusUrl,
            'token' => $token,
            'video_id' => (string) $data['guid'],
            'library_id' => $libraryId,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Helper methods
    // ─────────────────────────────────────────────────────────────

    private function getBaseUrl(): string
    {
        return self::API_BASE;
    }

    private function generateAccessToken(string $libraryId, string $videoGuid): string
    {
        $apiKey = config('services.bunny.api_key');
        $payload = [
            'libraryId' => (int) $libraryId,
            'videoId' => $videoGuid,
            'exp' => time() + 3600, // 1 hour TTL for upload
        ];

        // Simple JWT-like token (Bunny uses JWT for TUS auth)
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", $apiKey, true);
        $signature = base64_encode($signature);

        return "$header.$payload.$signature";
    }

    // ─────────────────────────────────────────────────────────────
    // Video Management
    // ─────────────────────────────────────────────────────────────

    /**
     * Delete a video from Bunny Stream library.
     */
    public function deleteVideo(string $videoGuid, string $libraryId): void
    {
        $apiKey = config('services.bunny.api_key');

        if (! $apiKey) {
            return;
        }

        try {
            Http::withHeaders([
                'AccessKey' => $apiKey,
            ])->delete("{$this->getBaseUrl()}/{$libraryId}/videos/{$videoGuid}")->throw();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Bunny video delete failed', [
                'video_guid' => $videoGuid,
                'library_id' => $libraryId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Not implemented for Bunny Stream (live-focused methods)
    // ─────────────────────────────────────────────────────────────

    public function createSession($class): array
    {
        throw new \BadMethodCallException('Bunny Stream does not support live sessions.');
    }

    public function updateSession($class): void
    {
    }

    public function deleteSession($class): void
    {
    }

    public function getJoinContext($class, $user, bool $isHost): array
    {
        throw new \BadMethodCallException('Bunny Stream does not support live sessions.');
    }

    public function fetchRecording(string $meetingId): array
    {
        throw new \BadMethodCallException('Bunny Stream does not fetch live recordings.');
    }

    public function isConfigured(): bool
    {
        return ! empty(config('services.bunny.library_id'))
            && ! empty(config('services.bunny.api_key'));
    }

    public function getName(): string
    {
        return 'bunny';
    }
}