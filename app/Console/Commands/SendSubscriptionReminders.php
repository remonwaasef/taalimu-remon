<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-subscription-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Telegram alerts for subscriptions expiring in 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeDaysFromNow = now()->addDays(3)->toDateString();
        
        $subscriptions = \App\Models\Subscription::whereDate('ends_at', $threeDaysFromNow)
            ->where('stripe_status', 'active')
            ->with('tenant')
            ->get();

        foreach ($subscriptions as $sub) {
            $tenant = $sub->tenant;
            if (!$tenant) continue;

            $message = "<b>⏳ تنبيه: اقتراب انتهاء اشتراك (بعد 3 أيام)</b>\n\n";
            $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
            $message .= "<b>📦 الباقة:</b> {$sub->type_label}\n";
            $message .= "<b>📅 تاريخ الانتهاء:</b> " . $sub->ends_at->format('Y-m-d') . "\n";
            $message .= "<b>💰 قيمة التجديد:</b> " . $sub->total_amount . " " . \App\Models\SiteSetting::get('currency_symbol', 'جنيه') . "\n\n";
            $message .= "يرجى التواصل مع العميل للتأكد من الرغبة في التجديد.";

            app(\App\Services\TelegramService::class)->sendAdminNotification($message);
        }

        $this->info('Sent ' . $subscriptions->count() . ' subscription reminders.');
    }
}
