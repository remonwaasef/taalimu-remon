<?php

namespace App\Interfaces;

/**
 * Contract for recording storage backends (R2 / S3 / local).
 * Business logic must never depend on a concrete provider.
 */
interface VideoStorageInterface
{
    /**
     * Download a remote file and persist it. Implementations should stream to
     * avoid loading whole videos into memory.
     *
     * @param  array<string, string>  $headers  e.g. Authorization header for provider download URLs
     * @return array{storage_provider: string, storage_key: string, file_size: int}
     */
    public function storeFromUrl(string $remoteUrl, array $headers = [], ?string $suggestedKey = null): array;

    /**
     * A short-lived, read-only playback URL for the stored object.
     *
     * Return null when the backend cannot serve direct URLs (e.g. local disk);
     * in that case the caller streams the bytes itself through an authorized,
     * token-bound endpoint.
     */
    public function temporaryPlaybackUrl(string $storageKey, int $ttlSeconds = 300): ?string;

    /**
     * Permanently delete the stored object.
     */
    public function delete(string $storageKey): bool;

    /**
     * Provider identifier persisted on the recording row (r2, s3, local...).
     */
    public function getName(): string;
}
