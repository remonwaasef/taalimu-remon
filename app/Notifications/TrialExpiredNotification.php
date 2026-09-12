<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialExpiredNotification extends Notification implements ShouldQueue
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
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $packageName = $this->subscription->type_label;
        $currency = \App\Models\SiteSetting::get('currency_symbol', 'جنيه');
        $checkoutUrl = route('center.subscription.index', ['tenant' => $this->tenant->domain]);

        return (new MailMessage)
            ->subject(__('center::subscription.trial_expired_mail_subject', ['app' => config('app.name')]))
            ->greeting(__('center::subscription.greeting_name', ['name' => $notifiable->name]))
            ->line(__('center::subscription.trial_expired_mail_body', [
                'center' => $this->tenant->name,
                'package' => $packageName,
            ]))
            ->line(__('center::subscription.renewal_amount', [
                'amount' => number_format($this->subscription->total_amount ?? 0, 0).' '.$currency,
            ]))
            ->action(__('center::subscription.pay_and_activate_now'), $checkoutUrl)
            ->line(__('center::subscription.thank_you_note'));
    }

    /**
     * Get the array representation of the notification for database storage (bell icon).
     */
    public function toArray($notifiable): array
    {
        $packageName = $this->subscription->type_label;
        $checkoutUrl = route('center.subscription.index', ['tenant' => $this->tenant->domain]);

        return [
            'title' => __('center::subscription.trial_expired_title'),
            'message' => __('center::subscription.trial_expired_message', ['package' => $packageName]),
            'url' => $checkoutUrl,
            'icon' => 'fas fa-exclamation-triangle',
            'type' => 'trial_expired',
        ];
    }
}
