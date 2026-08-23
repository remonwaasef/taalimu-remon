<?php

namespace App\Console\Commands;

use App\Models\OnlineClass;
use App\Notifications\OnlineClassNotification;
use Illuminate\Console\Command;

class SendOnlineClassRemindersCommand extends Command
{
    protected $signature = 'online-classes:send-reminders';

    protected $description = 'Notify students 15 minutes before their online classes start';

    public function handle(): int
    {
        $classes = OnlineClass::withoutGlobalScopes()
            ->where('status', OnlineClass::STATUS_SCHEDULED)
            ->whereNull('reminder_sent_at')
            ->whereBetween('start_time', [now(), now()->addMinutes(15)])
            ->get();

        foreach ($classes as $class) {
            $url = \tenant_route('campus.classes.index', $class->tenant_id);

            foreach ($class->allowedUserIds() as $userId) {
                $user = \App\Models\User::find($userId);

                if (! $user) {
                    continue;
                }

                $user->notify(new OnlineClassNotification(
                    title: __('online_classes::notifications.reminder_title', ['title' => $class->title]),
                    message: __('online_classes::notifications.reminder_body'),
                    url: $url,
                    icon: 'fas fa-bell text-amber-500',
                ));
            }

            // Mark even when zero recipients so we never re-send.
            $class->forceFill(['reminder_sent_at' => now()])->save();

            $this->info("Reminder processed for class #{$class->id} — {$class->title}");
        }

        if ($classes->isEmpty()) {
            $this->info('No upcoming classes need reminders.');
        }

        return self::SUCCESS;
    }
}
