<?php

namespace App\Services\VideoStorage;

use App\Interfaces\VideoStorageInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * S3-compatible object storage implementation (AWS S3 and Cloudflare R2 —
 * both speak the S3 API; R2 just uses a custom endpoint in the disk config).
 * Playback URLs are presigned, short-lived and never permanent.
 */
class CloudVideoStorage implements VideoStorageInterface
{
    public function __construct(protected string $diskName = 'r2')
    {
    }

    public function storeFromUrl(string $remoteUrl, array $headers = [], ?string $suggestedKey = null): array
    {
        $key = $suggestedKey ?? ('recordings/'.bin2hex(random_bytes(16)).'.mp4');

        // Stream directly to the object storage without buffering in memory.
        $tmp = tempnam(sys_get_temp_dir(), 'rec');
        try {
            $response = Http::withHeaders($headers)->timeout(3600)->sink($tmp)->get($remoteUrl);

            if ($response->failed()) {
                throw new \RuntimeException("Failed to download recording: HTTP {$response->status()}");
            }

            $stream = fopen($tmp, 'rb');
            try {
                Storage::disk($this->diskName)->writeStream($key, $stream);
            } finally {
                fclose($stream);
            }
        } finally {
            @unlink($tmp);
        }

        return [
            'storage_provider' => $this->getName(),
            'storage_key' => $key,
            'file_size' => (int) Storage::disk($this->diskName)->size($key),
        ];
    }

    public function temporaryPlaybackUrl(string $storageKey, int $ttlSeconds = 300): ?string
    {
        return Storage::disk($this->diskName)->temporaryUrl(
            $storageKey,
            now()->addSeconds($ttlSeconds),
            ['ResponseContentDisposition' => 'inline']
        );
    }

    public function delete(string $storageKey): bool
    {
        return Storage::disk($this->diskName)->delete($storageKey);
    }

    public function getName(): string
    {
        // Persisted on recordings so playback resolves to the right backend.
        return $this->diskName === 's3' ? 's3' : 'r2';
    }
}
