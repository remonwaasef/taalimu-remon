<?php

namespace App\Http\Controllers;

use App\Services\PlaybackTokenService;
use App\Services\VideoStorage\VideoStorageManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The ONLY endpoint through which recording bytes ever flow.
 *
 * - Resolves a short-lived, user-bound playback token.
 * - Cloud storage (R2/S3): redirects to a presigned read-only URL (~5 min).
 * - Local storage: streams bytes directly with Range support.
 *
 * The permanent object key is never exposed to the client.
 */
class VideoStreamController extends Controller
{
    public function __invoke(string $token, PlaybackTokenService $tokens): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(401);
        }

        $recording = $tokens->resolve($token, $user);

        if (! $recording) {
            // Expired, invalid, or bound to another user.
            Log::channel('security')->warning('Playback denied: bad token', ['user_id' => $user->id]);

            return response(__('online_classes::messages.token_invalid'), 403)
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Cache-Control', 'private, no-store');
        }

        if ($recording->status !== \App\Models\ClassRecording::STATUS_READY || ! $recording->storage_key) {
            abort(404);
        }

        // Refresh the concurrent-session heartbeat marker.
        $tokens->touch($recording, $user);

        if ($recording->storage_provider === 'local') {
            return $this->streamLocal($recording->storage_key);
        }

        $url = VideoStorageManager::forRecording($recording->storage_provider)
            ->temporaryPlaybackUrl($recording->storage_key, config('services.recordings.url_ttl', 300));

        if (! $url) {
            abort(500, 'Playback unavailable');
        }

        return redirect()->away($url)->header('Cache-Control', 'private, no-store');
    }

    /**
     * Stream a locally stored recording with HTTP Range support so seeking
     * works in <video> players without exposing any filesystem path.
     */
    protected function streamLocal(string $key): StreamedResponse
    {
        $disk = Storage::disk('local');

        if (! $disk->exists($key)) {
            abort(404);
        }

        $fullPath = $disk->path($key);
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
