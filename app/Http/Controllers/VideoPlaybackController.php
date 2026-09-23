<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\VideoPlaybackSession;
use App\Services\PlaybackTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoPlaybackController extends Controller
{
    public function __construct(protected PlaybackTokenService $tokenService)
    {
        $this->middleware('auth');
        $this->middleware('tenant');
    }

    /**
     * Authorize playback and issue a short-lived token.
     * Returns token for direct use with VideoStreamController.
     */
    public function issueToken(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $tenant = app('tenant');

        // Load video
        $video = $lesson->video()->first();

        if (! $video) {
            return response()->json([
                'message' => 'No video available for this lesson.',
            ], 404);
        }

        if (! $video->isReady()) {
            return response()->json([
                'message' => 'Video is not ready for playback.',
                'status' => $video->status,
            ], 403);
        }

        // Check enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->course_id)
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->first();

        if (! $enrollment) {
            // Check if lesson is preview
            if ($lesson->is_preview) {
                // Allow preview without enrollment
            } else {
                return response()->json([
                    'message' => 'You are not enrolled in this course.',
                ], 403);
            }
        }

        // Check subscription/entitlement if needed
        if (! $this->checkEntitlement($tenant, $lesson->course)) {
            return response()->json([
                'message' => 'Your subscription does not include access to this content.',
            ], 403);
        }

        // Issue playback token
        $token = $this->tokenService->issue($video, $user);

        // Create session record
        $session = VideoPlaybackSession::create([
            'video_id' => $video->id,
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'session_token_hash' => VideoPlaybackSession::hashToken($token),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'expires_at' => now()->addSeconds(VideoPlaybackSession::TTL_SECONDS),
        ]);

        return response()->json([
            'token' => $token,
            'expires_at' => $session->expires_at,
            'video' => [
                'id' => $video->id,
                'title' => $video->title,
                'duration_seconds' => $video->duration_seconds,
                'width' => $video->width,
                'height' => $video->height,
                'thumbnail_url' => $video->thumbnail_url,
            ],
            'stream_url' => route('video.stream', ['token' => $token]),
        ]);
    }

    /**
     * Get current playback position for resume.
     */
    public function progress(Lesson $lesson)
    {
        $user = auth()->user();
        $video = $lesson->video()->first();

        if (! $video) {
            return response()->json(['position' => 0]);
        }

        $progress = \App\Models\VideoProgress::where('video_id', $video->id)
            ->where('user_id', auth()->id())
            ->first();

        return response()->json([
            'position_seconds' => $progress->last_position_seconds ?? 0,
            'watched_seconds' => $progress->watched_seconds ?? 0,
            'completion_percentage' => $progress->completion_percentage ?? 0,
            'completed_at' => $progress->completed_at,
        ]);
    }

    protected function checkEntitlement($tenant, $course): bool
    {
        // Check if tenant has video access feature
        if (! $tenant->hasFeature('video_courses')) {
            return false;
        }

        // Check subscription status
        $subscription = $tenant->active_subscription;
        if (! $subscription || ! $subscription->is_active) {
            return false;
        }

        // Could add course-specific entitlement checks here
        return true;
    }
}