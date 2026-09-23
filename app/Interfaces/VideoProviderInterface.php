<?php

namespace App\Interfaces;

use App\Models\OnlineClass;
use App\Models\User;
use App\Models\Video;

/**
 * Contract for video providers (Zoom for live, Bunny Stream for recorded).
 * Implementations must keep all credentials server-side and never expose
 * secrets to the frontend.
 */
interface VideoProviderInterface
{
    /**
     * Create a live session for the class on the provider.
     *
     * @return array{external_id: string, external_uuid: ?string, join_url: string, password: ?string, account_id: ?string}
     */
    public function createSession(OnlineClass $class): array;

    /**
     * Update the scheduled session (time / duration / topic).
     */
    public function updateSession(OnlineClass $class): void;

    /**
     * Delete/cancel the session on the provider. Best-effort.
     */
    public function deleteSession(OnlineClass $class): void;

    /**
     * Build the SDK join context for a participant. The signature/token is
     * generated server-side and short-lived.
     *
     * @return array{signature: string, meeting_number: string, role: int, sdk_key: string, expires_at: int}
     */
    public function getJoinContext(OnlineClass $class, User $user, bool $isHost): array;

    /**
     * Fetch recording metadata from the provider after a cloud recording
     * completes.
     *
     * @param  string  $meetingId  Provider-side meeting id stored on the class
     * @return array{external_id: string, download_url: string, file_size: ?int, duration_seconds: ?int, recording_type: string}
     */
    public function fetchRecording(string $meetingId): array;

    /**
     * Create a direct upload authorization for recorded videos.
     * Used for Bunny Stream TUS direct upload from browser.
     *
     * @param  Video  $video  The video record to authorize upload for
     * @return array{upload_url: string, token: string, video_id: string, library_id: string}
     */
    public function createDirectUpload(Video $video): array;

    /**
     * Whether the provider integration is fully configured.
     */
    public function isConfigured(): bool;

    public function getName(): string;
}