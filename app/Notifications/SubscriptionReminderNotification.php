<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    protected $tenant;

    /**
     * Create a new notification instance.
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
        $this->tenant = $subscription->tenant;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $packageName = $this->subscription->type_label;
        $endsAt = $this->subscription->ends_at->format('Y-m-d');
        $currency = \App\Models\SiteSetting::get('currency_symbol', 'جنيه');

        return (new MailMessage)
            ->subject('تذكير: اقتراب انتهاء اشتراك منصة '.config('app.name'))
            ->greeting('مرحباً '.$notifiable->name)
            ->line('نود تذكيركم بأن اشتراك مركزكم ('.$this->tenant->name.') في باقة '.$packageName.' سينتهي قريباً.')
            ->line('تاريخ الانتهاء: '.$endsAt)
            ->line('قيمة التجديد المتوقعة: '.$this->subscription->total_amount.' '.$currency)
            ->action('تجديد الاشتراك الآن', route('center.subscription.index', ['tenant' => $this->tenant->domain]))
            ->line('شكراً لاستخدامكم منصتنا التعليمية!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'تذكير بانتهاء الاشتراك',
            'message' => 'اشتراك باقة '.$this->subscription->type_label.' سينتهي في '.$this->subscription->ends_at->format('Y-m-d'),
            'url' => route('center.subscription.index', ['tenant' => $this->tenant->domain]),
            'icon' => 'fas fa-clock',
            'type' => 'subscription_reminder',
        ];
    }
}
