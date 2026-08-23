<?php

namespace App\Jobs;

use App\Models\ClassRecording;
use App\Models\OnlineClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies allowed students that the class recording is now watchable.
 */
class NotifyRecordingAvailableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $recordingId)
    {
    }

    public function handle(): void
    {
        $recording = ClassRecording::withoutGlobalScopes()->find($this->recordingId);

        if (! $recording) {
            return;
        }

        $class = OnlineClass::withoutGlobalScopes()->find($recording->online_class_id);

        if (! $class) {
            return;
        }

        $url = tenant_route('campus.recordings.show', ['tenant' => $class->tenant_id ?? null, 'recording' => $recording->uuid]);

        foreach ($class->allowedUserIds() as $userId) {
            $user = \App\Models\User::find($userId);

            if (! $user) {
                continue;
            }

            $user->notify(new \App\Notifications\OnlineClassNotification(
                title: __('online_classes::notifications.recording_title', ['title' => $class->title]),
                message: __('online_classes::notifications.recording_body'),
                url: $url,
                icon: 'fas fa-clapperboard text-primary',
            ));
        }
    }
}
