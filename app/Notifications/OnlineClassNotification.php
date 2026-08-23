<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Database (+ optional mail) notification for online class lifecycle events.
 * Follows the GeneralNotification convention used across the platform.
 */
class OnlineClassNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $title,
        protected string $message,
        protected string $url,
        protected string $icon = 'fas fa-video'
    ) {
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        $tenantId = app()->bound('tenant') ? app('tenant')->id : null;

        if (\App\Models\SiteSetting::get('enable_email_notifications', false, $tenantId)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action(__('online_classes::notifications.open'), $this->url);
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'icon' => $this->icon,
        ];
    }
}
