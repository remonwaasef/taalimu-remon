<?php

namespace App\Jobs;

use App\Models\ClassRecording;
use App\Models\OnlineClass;
use App\Services\VideoStorage\VideoStorageManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Archives a finished Zoom cloud recording into our own storage.
 *
 * Idempotent: the (provider, external_recording_id) unique index plus a
 * ready-check means duplicate webhooks never create double recordings.
 */
class ProcessClassRecordingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(
        public int $onlineClassId,
        public ?string $meetingUuid = null,
        public ?string $hostId = null
    ) {
    }

    public function handle(): void
    {
        $class = OnlineClass::withoutGlobalScopes()->find($this->onlineClassId);

        if (! $class) {
            return;
        }

        $zoom = app(\App\Services\ZoomService::class);

        if (! $zoom->isConfigured()) {
            $class->forceFill(['recording_status' => ClassRecording::STATUS_FAILED])->save();
            Log::error('Recording processing skipped: Zoom not configured', ['class_id' => $class->id]);

            return;
        }

        try {
            $meta = $zoom->fetchRecording((string) $class->meeting_id);
        } catch (\Throwable $e) {
            // Recording may not be processed by Zoom yet — retry handles it.
            $class->forceFill(['recording_status' => ClassRecording::STATUS_PROCESSING])->save();
            Log::warning('Zoom recording metadata unavailable yet', [
                'class_id' => $class->id,
                'error' => $e->getMessage(),
            ]);

            throw $e; // trigger backoff retry
        }

        /** @var ClassRecording|null $recording */
        $recording = ClassRecording::withoutGlobalScopes()
            ->firstOrCreate(
                ['provider' => 'zoom', 'external_recording_id' => $meta['external_id']],
                ['tenant_id' => $class->tenant_id, 'online_class_id' => $class->id, 'status' => ClassRecording::STATUS_PROCESSING]
            );

        if ($recording->status === ClassRecording::STATUS_READY) {
            return; // already archived — webhook duplicates stop here
        }

        $storage = VideoStorageManager::default();

        try {
            // Zoom download URLs require the OAuth token (header auth works
            // for Server-to-Server OAuth tokens).
            $stored = $storage->storeFromUrl(
                $meta['download_url'],
                ['Authorization' => 'Bearer '.$zoom->accessToken()],
                "recordings/tenant-{$recording->tenant_id}/class-{$class->uuid}.mp4"
            );
        } catch (\Throwable $e) {
            $recording->forceFill(['status' => ClassRecording::STATUS_FAILED])->save();
            $class->forceFill(['recording_status' => ClassRecording::STATUS_FAILED])->save();
            Log::error('Recording archive failed', ['recording_id' => $recording->id, 'error' => $e->getMessage()]);

            throw $e; // retry
        }

        $recording->forceFill([
            'tenant_id' => $class->tenant_id,
            'online_class_id' => $class->id,
            'storage_provider' => $stored['storage_provider'],
            'storage_key' => $stored['storage_key'],
            'file_size' => $stored['file_size'],
            'duration_seconds' => $meta['duration_seconds'],
            'status' => ClassRecording::STATUS_READY,
            'available_at' => now(),
        ])->save();

        $class->forceFill(['recording_status' => ClassRecording::STATUS_AVAILABLE])->save();

        Log::info('Recording archived and available', ['recording_id' => $recording->id]);

        NotifyRecordingAvailableJob::dispatch($recording->id)->onQueue('default');
    }
}
