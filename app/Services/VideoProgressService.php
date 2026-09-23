<?php

namespace App\Services;

use App\Models\Video;
use App\Models\VideoPlaybackSession;
use App\Models\VideoWatchEvent;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VideoProgressService
{
    public const HEARTBEAT_INTERVAL = 30; // seconds
    public const COMPLETION_THRESHOLD = 95.0; // percentage

    /**
     * Process a heartbeat from the video player.
     * Stores in Redis for real-time, persists to DB periodically.
     */
    public function heartbeat(VideoPlaybackSession $session, int $positionSeconds): array
    {
        $video = $session->video;
        $user = $session->user;
        $tenant = $session->tenant;

        // Update session last_seen
        $session->touch();

        // Calculate completion
        $duration = max(1, $video->duration_seconds ?? 1);
        $completion = min(100.0, round(($positionSeconds / $duration) * 100, 2));
        $completedAt = $completion >= self::COMPLETION_THRESHOLD ? now() : null;

        // Update progress in Redis (atomic, fast)
        $cacheKey = "video_progress:{$video->id}:{$session->user_id}";
        $progressData = [
            'last_position_seconds' => max(0, $positionSeconds),
            'completion_percentage' => $completion,
            'completed_at' => $completedAt?->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        Cache::put($cacheKey, $progressData, now()->addMinutes(30));

        // Log watch event (async via queue for high volume)
        VideoWatchEvent::log(VideoWatchEvent::EVENT_HEARTBEAT, [
            'video_id' => $video->id,
            'user_id' => $session->user_id,
            'tenant_id' => $session->tenant_id,
            'session_id' => $session->id,
            'position_seconds' => $positionSeconds,
            'duration_seconds' => self::HEARTBEAT_INTERVAL,
        ]);

        // Persist to DB periodically (every 5 heartbeats = ~2.5 min)
        $heartbeatCount = Cache::increment("video_heartbeat_count:{$cacheKey}");
        if ($heartbeatCount % 5 === 0) {
            $this->persistProgress($video, $session->user_id, $cacheKey, $progressData);
        }

        // Check for completion
        $isCompleted = $completedAt !== null;

        return [
            'position_seconds' => max(0, $positionSeconds),
            'completion_percentage' => $completion,
            'completed' => $isCompleted,
            'completed_at' => $completedAt?->toISOString(),
        ];
    }

    /**
     * Record a discrete watch event (play, pause, seek, complete).
     */
    public function recordEvent(VideoPlaybackSession $session, string $event, int $positionSeconds, int $durationSeconds = null): void
    {
        $video = $session->video;

        VideoWatchEvent::log($event, [
            'video_id' => $video->id,
            'user_id' => $session->user_id,
            'tenant_id' => $session->tenant_id,
            'session_id' => $session->id,
            'position_seconds' => $positionSeconds,
            'duration_seconds' => $durationSeconds ?? 0,
        ]);

        // Update progress for seek/play events
        if (in_array($event, [VideoWatchEvent::EVENT_PLAY, VideoWatchEvent::EVENT_SEEK])) {
            $this->heartbeat($session, $positionSeconds);
        }

        // Check completion on 'complete' event
        if ($event === VideoWatchEvent::EVENT_COMPLETE) {
            $this->markCompleted($session->video, $session->user_id);
        }
    }

    /**
     * Persist progress from Redis to database.
     */
    public function persistProgress(Video $video, int $userId, string $cacheKey, array $data): void
    {
        try {
            DB::transaction(function () use ($video, $userId, $data) {
                $progress = VideoProgress::where('video_id', $video->id)
                    ->where('user_id', $userId)
                    ->first();

                $duration = max(1, $video->duration_seconds ?? 1);
                $position = max(0, $data['last_position_seconds'] ?? 0);
                $completion = min(100.0, round(($position / $duration) * 100, 2));
                $completedAt = ($data['completion_percentage'] ?? 0) >= self::COMPLETION_THRESHOLD ? now() : null;

                if (! $progress) {
                    VideoProgress::create([
                        'tenant_id' => $video->tenant_id,
                        'video_id' => $video->id,
                        'user_id' => $userId,
                        'last_position_seconds' => $data['last_position_seconds'] ?? 0,
                        'watched_seconds' => 0,
                        'completion_percentage' => $data['completion_percentage'] ?? 0,
                        'completed_at' => $completedAt,
                    ]);
                } else {
                    $progress->fill([
                        'last_position_seconds' => $data['last_position_seconds'] ?? 0,
                        'completion_percentage' => $data['completion_percentage'] ?? 0,
                        'completed_at' => $data['completed_at'] ?? $progress->completed_at,
                    ]);
                    $progress->save();
                }
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('VideoProgressService: Failed to persist progress', [
                'video_id' => $video->id,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Mark video as completed for user.
     */
    public function markCompleted(Video $video, int $userId): void
    {
        $progress = VideoProgress::where('video_id', $video->id)
            ->where('user_id', $userId)
            ->first();

        if ($progress) {
            $progress->update([
                'completion_percentage' => 100,
                'completed_at' => now(),
                'last_position_seconds' => $video->duration_seconds ?? 0,
            ]);
        } else {
            VideoProgress::create([
                'tenant_id' => $video->tenant_id,
                'video_id' => $video->id,
                'user_id' => $userId,
                'last_position_seconds' => $video->duration_seconds ?? 0,
                'watched_seconds' => $video->duration_seconds ?? 0,
                'completion_percentage' => 100,
                'completed_at' => now(),
            ]);
        }

        // Clear cache
        Cache::forget("video_progress:{$video->id}:{$userId}");
    }

    /**
     * Get progress for a user/video (reads from cache first, then DB).
     */
    public function getProgress(Video $video, int $userId): ?array
    {
        $cacheKey = "video_progress:{$video->id}:{$userId}";

        $cached = Cache::get($cacheKey);
        if ($cached) {
            return [
                'last_position_seconds' => $cached['last_position_seconds'] ?? 0,
                'completion_percentage' => $cached['completion_percentage'] ?? 0,
                'completed_at' => $cached['completed_at'] ?? null,
                'source' => 'cache',
            ];
        }

        $progress = VideoProgress::where('video_id', $video->id)
            ->where('user_id', $userId)
            ->first();

        if (! $progress) {
            return null;
        }

        return [
            'last_position_seconds' => $progress->last_position_seconds ?? 0,
            'completion_percentage' => $progress->completion_percentage ?? 0,
            'completed_at' => $progress->completed_at?->toISOString(),
            'source' => 'database',
        ];
    }

    /**
     * Cleanup stale session data (run via scheduler).
     */
    public function cleanupStaleSessions(): int
    {
        // Clean expired playback sessions
        VideoPlaybackSession::cleanupExpired();

        // Clean old watch events (keep 30 days)
        $deleted = VideoWatchEvent::where('created_at', '<', now()->subDays(30))->delete();

        \Illuminate\Support\Facades\Log::info('VideoProgressService: Cleanup completed', [
            'deleted_watch_events' => $deleted,
        ]);

        return $deleted;
    }
}