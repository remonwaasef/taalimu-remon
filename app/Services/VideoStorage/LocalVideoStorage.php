<?php

namespace App\Services\VideoStorage;

use App\Interfaces\VideoStorageInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Local-disk implementation for development / trial environments.
 * Playback is streamed by VideoStreamController (Range requests supported);
 * no public URL of the file is ever exposed.
 */
class LocalVideoStorage implements VideoStorageInterface
{
    public function storeFromUrl(string $remoteUrl, array $headers = [], ?string $suggestedKey = null): array
    {
        $key = $suggestedKey ?? ('recordings/'.bin2hex(random_bytes(16)).'.mp4');

        $response = Http::withHeaders($headers)->timeout(3600)->sink(
            Storage::disk('local')->path($key.'.tmp')
        )->get($remoteUrl);

        if ($response->failed()) {
            Storage::disk('local')->delete($key.'.tmp');
            throw new \RuntimeException("Failed to download recording: HTTP {$response->status()}");
        }

        Storage::disk('local')->move($key.'.tmp', $key);

        return [
            'storage_provider' => $this->getName(),
            'storage_key' => $key,
            'file_size' => (int) Storage::disk('local')->size($key),
        ];
    }

    public function temporaryPlaybackUrl(string $storageKey, int $ttlSeconds = 300): ?string
    {
        return null; // streamed through the authorized controller instead
    }

    public function delete(string $storageKey): bool
    {
        return Storage::disk('local')->delete($storageKey);
    }

    public function getName(): string
    {
        return 'local';
    }
}
