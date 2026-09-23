<?php

namespace Modules\Instructor\Http\Controllers;

use App\Models\Lesson;
use App\Models\Video;
use App\Services\BunnyStreamProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends \App\Http\Controllers\Controller
{
    public function __construct(protected BunnyStreamProvider $bunny)
    {
        $this->middleware('auth');
        $this->middleware('tenant');
    }

    /**
     * Initialize direct upload for a lesson video.
     * Returns TUS upload URL and token for direct browser upload to Bunny Stream.
     */
    public function initUpload(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $video = $lesson->video()->first();

        if (! $video) {
            $video = Video::create([
                'tenant_id' => $lesson->tenant_id,
                'lesson_id' => $lesson->id,
                'provider' => 'bunny',
                'title' => $lesson->title,
                'status' => Video::STATUS_PENDING,
            ]);
        } elseif ($video->isReady()) {
            return response()->json([
                'message' => 'Video already exists and is ready.',
                'video' => $video,
            ], 409);
        } elseif ($video->isProcessing()) {
            return response()->json([
                'message' => 'Video is already being processed.',
                'video' => $video,
            ], 409);
        }

        try {
            $upload = $this->bunny->createDirectUpload($video);

            return response()->json([
                'upload_url' => $upload['upload_url'],
                'token' => $upload['token'],
                'video_id' => $upload['video_id'],
                'library_id' => $upload['library_id'],
                'video' => $video->fresh(),
            ]);
        } catch (\Throwable $e) {
            $video->markFailed($e->getMessage());

            return response()->json([
                'message' => 'Failed to initialize upload.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check upload/processing status.
     */
    public function status(Lesson $lesson)
    {
        $this->authorize('view', $lesson->course);

        $video = $lesson->video()->first();

        if (! $video) {
            return response()->json(['status' => 'none']);
        }

        return response()->json([
            'status' => $video->status,
            'progress' => $video->encode_progress,
            'video' => $video,
        ]);
    }

    /**
     * Delete video and remove from Bunny Stream.
     */
    public function destroy(Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $video = $lesson->video()->first();

        if (! $video) {
            return response()->json(['message' => 'No video to delete.'], 404);
        }

        DB::transaction(function () use ($video) {
            // Delete from Bunny Stream
            try {
                $this->bunny->deleteVideo($video->provider_video_id, $video->provider_library_id);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Bunny video delete failed', [
                    'video_id' => $video->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Delete local record
            $video->delete();
        });

        return response()->json(['message' => 'Video deleted.']);
    }
}