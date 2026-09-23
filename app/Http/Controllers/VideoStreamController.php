<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoPlaybackSession;
use App\Services\PlaybackTokenService;
use App\Services\VideoStorage\VideoStorageManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The ONLY endpoint through which video bytes ever flow.
 *
 * - Resolves a short-lived, user-bound playback token.
 * - Cloud storage (Bunny Stream): redirects to a presigned read-only URL (~5 min).
 * - Local storage: streams bytes directly with Range support.
 *
 * The permanent object key is never exposed to the client.
 * Hotlink protection: validates Referer/Origin headers.
 * No permanent MP4 URLs ever exposed.
 * No Direct Play (permanent URLs) supported.
 */
class VideoStreamController extends Controller
{
    public function __invoke(string $token, PlaybackTokenService $tokens): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(401);
        }

        $session = $tokens->resolveVideo($token, $user);

        if (! $session) {
            // Expired, invalid, or bound to another user.
            Log::channel('security')->warning('Playback denied: bad token', ['user_id' => $user->id]);

            return response('Invalid or expired playback token.', 403)
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Cache-Control', 'private, no-store');
        }

        $video = $session->video;

        if ($video->status !== Video::STATUS_READY || ! $video->storage_key) {
            abort(404);
        }

        // Hotlink protection: validate Referer/Origin
        $this->validateReferer($session);

        // Refresh the concurrent-session heartbeat marker.
        $tokens->touchVideo($session, $user);

        if ($video->storage_provider === 'local') {
            return $this->streamLocal($video);
        }

        $url = VideoStorageManager::forRecording($video->storage_provider)
            ->temporaryPlaybackUrl($video->storage_key, config('services.recordings.url_ttl', 300));

        if (! $url) {
            abort(500, 'Playback unavailable');
        }

        return redirect()->away($url)
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Frame-Options', 'DENY')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Validate Referer/Origin headers to prevent hotlinking.
     */
    protected function validateReferer(VideoPlaybackSession $session): void
    {
        $referer = request()->headers->get('referer');
        $origin = request()->headers->get('origin');

        // Allow requests with no referer (direct access, some mobile apps)
        if (! $referer && ! $origin) {
            return;
        }

        $allowedHosts = $this->getAllowedHosts();

        $isValid = false;

        if ($referer) {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            $isValid = $isValid || in_array($refererHost, $allowedHosts, true);
        }

        if ($origin) {
            $originHost = parse_url($origin, PHP_URL_HOST);
            $isValid = $isValid || in_array($originHost, $allowedHosts, true);
        }

        if (! $isValid) {
            \Illuminate\Support\Facades\Log::channel('security')->warning('Hotlink attempt blocked', [
                'user_id' => auth()->id(),
                'video_id' => $session->video_id,
                'referer' => $referer,
                'origin' => $origin,
            ]);

            abort(403, 'Hotlinking not allowed.');
        }
    }

    /**
     * Get list of allowed hosts for referrer/origin validation.
     */
    protected function getAllowedHosts(): array
    {
        $tenant = app('tenant');
        $hosts = [
            config('app.tenant_domain'),
            parse_url(config('app.url'), PHP_URL_HOST),
            'localhost',
            '127.0.0.1',
        ];

        if ($tenant) {
            $hosts[] = $tenant->domain . '.' . config('app.tenant_domain');
        }

        return array_filter(array_unique($hosts));
    }

    /**
     * Stream a locally stored recording with HTTP Range support so seeking
     * works in <video> players without exposing any filesystem path.
     */
    protected function streamLocal(Video $video): StreamedResponse
    {
        $disk = Storage::disk('local');

        if (! $disk->exists($video->storage_key)) {
            abort(404);
        }

        $fullPath = $disk->path($video->storage_key);
        $size = filesize($fullPath);

        $start = 0;
        $end = $size - 1;

        $range = request()->header('Range');

        if ($range && preg_match('/bytes=(\d*)-(\d*)/', $range, $m)) {
            if ($m[1] !== '') {
                $start = (int) $m[1];
            }
            if ($m[2] !== '') {
                $end = min((int) $m[2], $size - 1);
            }
            if ($start > $end || $start >= $size) {
                return response('', 416)->withHeaders(['Content-Range' => "bytes */{$size}"]);
            }
        }

        $headers = [
            'Content-Type' => 'video/mp4',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
        ];

        return response()->stream(function () use ($fullPath, $start, $end) {
            $handle = fopen($fullPath, 'rb');
            try {
                fseek($handle, $start);
                $remaining = $end - $start + 1;

                while ($remaining > 0 && ! feof($handle)) {
                    $chunk = fread($handle, min(8192, $remaining));
                    echo $chunk;
                    $remaining -= strlen($chunk);
                    flush();
                }
            } finally {
                fclose($handle);
            }
        }, $range ? 206 : 200, $headers + ($range
            ? ['Content-Range' => "bytes {$start}-{$end}/{$size}", 'Content-Length' => $end - $start + 1]
            : ['Content-Length' => $size]));
    }
}
