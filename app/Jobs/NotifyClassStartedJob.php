<?php

namespace App\Jobs;

use App\Models\OnlineClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies allowed students that the live class has just started.
 */
class NotifyClassStartedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $onlineClassId)
    {
    }

    public function handle(): void
    {
        $class = OnlineClass::withoutGlobalScopes()->find($this->onlineClassId);

        if (! $class) {
            return;
        }

        $url = tenant_route('campus.classes.index', $class->tenant_id ?? null);

        foreach ($class->allowedUserIds() as $userId) {
            $user = \App\Models\User::find($userId);

            if (! $user) {
                continue;
            }

            $user->notify(new \App\Notifications\OnlineClassNotification(
                title: __('online_classes::notifications.started_title', ['title' => $class->title]),
                message: __('online_classes::notifications.started_body'),
                url: $url,
                icon: 'fas fa-circle-play text-danger',
            ));
        }
    }
}
