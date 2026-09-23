<?php

namespace App\Services;

use App\Models\ClassRecording;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccessLog;
use App\Models\VideoPlaybackSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Issues and verifies short-lived, user-bound playback tokens.
 *
 * A token is an opaque random string cached server-side with:
 *  - user_id binding (a copied URL is useless to anyone else)
 *  - tenant + recording/video binding
 *  - short TTL (default 5 minutes)
 */
class PlaybackTokenService
{
    public const TTL_SECONDS = 300;

    // ─────────────────────────────────────────────────────────────
    // ClassRecording (Live Classes) - Original implementation
    // ─────────────────────────────────────────────────────────────

    /**
     * Issue a playback token for a user/recording pair after authorization
     * has been performed by the caller (policy).
     *
     * @throws \App\Exceptions\BusinessException on concurrent-session block
     */
    public function issue(ClassRecording $recording, User $user): string
    {
        $this->enforceConcurrentSessions($recording, $user);

        $token = Str::random(64);

        Cache::put($this->key($token), [
            'user_id' => $user->id,
            'tenant_id' => $recording->tenant_id,
            'recording_id' => $recording->id,
            'type' => 'recording',
        ], now()->addSeconds(self::TTL_SECONDS));

        VideoAccessLog::record($recording, $user->id, 'token_issued');

        return $token;
    }

    /**
     * Resolve a token to its recording if valid AND bound to this user.
     */
    public function resolve(string $token, User $user): ?ClassRecording
    {
        $payload = Cache::get($this->key($token));

        if (! is_array($payload)) {
            return null; // expired or unknown
        }

        if ($payload['type'] !== 'recording') {
            return null;
        }

        if ((int) $payload['user_id'] !== (int) $user->id) {
            // Token theft / sharing attempt — log it.
            $recording = ClassRecording::find($payload['recording_id'] ?? 0);
            if ($recording) {
                VideoAccessLog::record($recording, $user->id, 'playback_denied', ['reason' => 'token_user_mismatch']);
            }

            return null;
        }

        return ClassRecording::find($payload['recording_id']);
    }

    /**
     * Consume a single-use stream request (keeps the token valid for the rest
     * of its TTL but prevents mass parallel downloads).
     */
    public function touch(ClassRecording $recording, User $user): void
    {
        // Heartbeat keeps the concurrent-session marker alive.
        Cache::put(
            $this->sessionKey($recording->id, $user->id),
            $this->deviceFingerprint(),
            now()->addSeconds(90)
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Video (Recorded Courses) - New implementation
    // ─────────────────────────────────────────────────────────────

    /**
     * Issue a playback token for a user/video pair after authorization
     * has been performed by the caller (policy).
     */
    public function issueVideo(Video $video, User $user): string
    {
        $this->enforceConcurrentSessionsVideo($video, $user);

        $token = Str::random(64);

        Cache::put($this->key($token), [
            'user_id' => $user->id,
            'tenant_id' => $video->tenant_id,
            'video_id' => $video->id,
            'type' => 'video',
        ], now()->addSeconds(self::TTL_SECONDS));

        // Log access for Video (similar to VideoAccessLog)
        \App\Models\VideoAccessLog::record($video, $user->id, 'token_issued');

        return $token;
    }

    /**
     * Resolve a video playback token to its session if valid AND bound to this user.
     */
    public function resolveVideo(string $token, User $user): ?VideoPlaybackSession
    {
        $payload = Cache::get($this->key($token));

        if (! is_array($payload)) {
            return null; // expired or unknown
        }

        if ($payload['type'] !== 'video') {
            return null;
        }

        if ((int) $payload['user_id'] !== (int) $user->id) {
            // Token theft / sharing attempt — log it.
            $video = Video::find($payload['video_id'] ?? 0);
            if ($video) {
                \App\Models\VideoAccessLog::record($video, $user->id, 'playback_denied', ['reason' => 'token_user_mismatch']);
            }

            return null;
        }

        return VideoPlaybackSession::find($payload['video_id']);
    }

    /**
     * Consume a single-use stream request for video (keeps the token valid for the rest
     * of its TTL but prevents mass parallel downloads).
     */
    public function touchVideo(VideoPlaybackSession $session, User $user): void
    {
        // Heartbeat keeps the concurrent-session marker alive.
        Cache::put(
            $this->videoSessionKey($session->video_id, $user->id),
            $this->deviceFingerprint(),
            now()->addSeconds(90)
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Concurrent Session Enforcement
    // ─────────────────────────────────────────────────────────────

    /**
     * Account-sharing guard for ClassRecording.
     */
    protected function enforceConcurrentSessions(ClassRecording $recording, User $user): void
    {
        $mode = $this->concurrentMode();
        $fingerprint = $this->deviceFingerprint();
        $sessionKey = $this->sessionKey($recording->id, $user->id);

        $existing = Cache::get($sessionKey);

        if (! $existing || $existing === $fingerprint) {
            return; // first device or same session — fine
        }

        if ($mode === 'block') {
            VideoAccessLog::record($recording, $user->id, 'session_conflict', ['mode' => 'block']);

            throw new \App\Exceptions\BusinessException(__('online_classes::messages.concurrent_blocked'));
        }

        VideoAccessLog::record($recording, $user->id, 'session_conflict', ['mode' => $mode]);
    }

    /**
     * Account-sharing guard for Video.
     */
    protected function enforceConcurrentSessionsVideo(Video $video, User $user): void
    {
        $mode = $this->concurrentMode();
        $fingerprint = $this->deviceFingerprint();
        $sessionKey = $this->videoSessionKey($video->id, $user->id);

        $existing = Cache::get($sessionKey);

        if (! $existing || $existing === $fingerprint) {
            return; // first device or same session — fine
        }

        if ($mode === 'block') {
            \App\Models\VideoAccessLog::record($video, $user->id, 'session_conflict', ['mode' => 'block']);

            throw new \App\Exceptions\BusinessException(__('online_classes::messages.concurrent_blocked'));
        }

        \App\Models\VideoAccessLog::record($video, $user->id, 'session_conflict', ['mode' => $mode]);
    }

    // ─────────────────────────────────────────────────────────────
    // Helper Methods
    // ─────────────────────────────────────────────────────────────

    protected function concurrentMode(): string
    {
        $tenant = app()->bound('tenant') ? app('tenant') : null;

        return data_get($tenant?->settings, 'online_classes.concurrent_mode', config('services.recordings.concurrent_mode', 'block'));
    }

    /**
     * Stable per-browser identifier for the playback session.
     */
    protected function deviceFingerprint(): string
    {
        return hash('sha256', auth()->id().'|'.request()?->header('User-Agent').'|'.request()?->ip());
    }

    protected function key(string $token): string
    {
        return "playback_token:{$token}";
    }

    protected function sessionKey(int $recordingId, int $userId): string
    {
        return "video_session:{$userId}:{$recordingId}";
    }

    protected function videoSessionKey(int $videoId, int $userId): string
    {
        return "video_video_session:{$userId}:{$videoId}";
    }
}
