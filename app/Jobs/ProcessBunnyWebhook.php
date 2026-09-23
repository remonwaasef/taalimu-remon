<?php

namespace App\Jobs;

use App\Models\Video;
use App\Models\VideoWebhookEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessBunnyWebhook implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected int $webhookEventId
    ) {}

    public function handle(): void
    {
        $webhookEvent = VideoWebhookEvent::find($this->webhookEventId);

        if (! $webhookEvent) {
            Log::warning('ProcessBunnyWebhook: Webhook event not found', [
                'webhook_event_id' => $this->webhookEventId,
            ]);

            return;
        }

        if ($webhookEvent->status === VideoWebhookEvent::STATUS_PROCESSED) {
            return;
        }

        $payload = $webhookEvent->payload;
        $eventType = $webhookEvent->event_type;
        $providerVideoId = $webhookEvent->provider_video_id;

        try {
            $video = Video::where('provider_video_id', $providerVideoId)->first();

            if (! $video) {
                // Video might not exist locally yet (created directly in Bunny)
                // Try to find by lesson if metadata includes it
                $lessonId = $payload['lessonId'] ?? $payload['metadata']['lessonId'] ?? null;
                if ($lessonId) {
                    $video = \App\Models\Video::where('lesson_id', $lessonId)->first();
                    if ($video) {
                        $video->update([
                            'provider_video_id' => $providerVideoId,
                            'provider_library_id' => $payload['LibraryId'] ?? $payload['libraryId'] ?? null,
                        ]);
                    }
                }
            }

            if (! $video) {
                $webhookEvent->markFailed('Video not found locally');
                Log::warning('Bunny webhook: Video not found locally', [
                    'provider_video_id' => $providerVideoId,
                    'event_type' => $webhookEvent->event_type,
                ]);
                return;
            }

            // Process based on event type
            match ($eventType) {
                'video.created' => $this->handleCreated($video, $payload),
                'video.encoded' => $this->handleEncoded($video, $payload),
                'video.encoding.failed', 'video.failed' => $this->handleFailed($video, $payload),
                'video.deleted' => $this->handleDeleted($video),
                default => $this->handleUnknown($video, $payload),
            };

            $webhookEvent->markProcessed();

        } catch (\Throwable $e) {
            $webhookEvent->markFailed($e->getMessage());
            Log::error('ProcessBunnyWebhook failed', [
                'webhook_event_id' => $this->webhookEventId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    protected function handleCreated(Video $video, array $payload): void
    {
        // Video created in Bunny library, awaiting upload
        $video->markUploading();
    }

    protected function handleEncoded(Video $video, array $payload): void
    {
        $video->markReady([
            'duration' => $payload['Length'] ?? $payload['length'] ?? null,
            'width' => $payload['Width'] ?? $payload['width'] ?? null,
            'height' => $payload['Height'] ?? $payload['height'] ?? null,
            'size' => $payload['FileSize'] ?? $payload['fileSize'] ?? null,
            'thumbnail' => $payload['ThumbnailUrl'] ?? $payload['thumbnailUrl'] ?? null,
        ]);
    }

    protected function handleFailed(Video $video, array $payload): void
    {
        $error = $payload['ErrorMessage'] ?? $payload['errorMessage'] ?? 'Encoding failed';
        $video->markFailed($error);
    }

    protected function handleDeleted(Video $video): void
    {
        $video->delete();
    }

    protected function handleUnknown(Video $video, array $payload): void
    {
        // Log unknown event types but don't fail
    }
}