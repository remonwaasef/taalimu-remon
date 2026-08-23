<?php

namespace App\Services\VideoStorage;

use App\Interfaces\VideoStorageInterface;
use InvalidArgumentException;

/**
 * Resolves the configured recording storage backend. Adding a new provider
 * (e.g. Bunny Stream) means adding one class here — no business logic changes.
 */
class VideoStorageManager
{
    /**
     * Resolve the storage backend for a persisted recording row.
     */
    public static function forRecording(string $storageProvider): VideoStorageInterface
    {
        return match ($storageProvider) {
            'local' => new LocalVideoStorage,
            'r2' => new CloudVideoStorage('r2'),
            's3' => new CloudVideoStorage('s3'),
            default => throw new InvalidArgumentException("Unknown storage provider [{$storageProvider}]"),
        };
    }

    /**
     * The backend used when archiving new recordings.
     */
    public static function default(): VideoStorageInterface
    {
        $driver = config('services.recordings.driver', 'auto');

        // auto: use whatever cloud disk is fully configured, else local.
        if ($driver === 'auto') {
            if (config('services.r2.key') && config('services.r2.secret')) {
                return new CloudVideoStorage('r2');
            }

            if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret')) {
                return new CloudVideoStorage('s3');
            }

            return new LocalVideoStorage;
        }

        return self::forRecording($driver);
    }
}
