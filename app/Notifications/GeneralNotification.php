<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $title;

    protected $message;

    protected $url;

    protected $created_by;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $url = '#', $icon = 'fas fa-bell', $created_by = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->icon = $icon;
        $this->created_by = $created_by;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        $channels = ['database'];
        
        $tenantId = app()->bound('tenant') ? app('tenant')->id : null;
        if (\App\Models\SiteSetting::get('enable_email_notifications', false, $tenantId)) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($this->title)
            ->line($this->message)
            ->action('عرض التفاصيل', $this->url)
            ->line('شكراً لاستخدامكم منصتنا التعليمية!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'icon' => $this->icon,
            'created_by' => $this->created_by,
        ];
    }
}
