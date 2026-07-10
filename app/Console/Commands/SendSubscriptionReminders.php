<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'app:send-subscription-reminders';

    protected $description = 'Send Telegram alerts for subscriptions expiring in 3 days';

    public function handle(TelegramService $telegram)
    {
        $threeDaysFromNow = now()->addDays(3)->toDateString();

        $subscriptions = Subscription::whereDate('ends_at', $threeDaysFromNow)
            ->where('stripe_status', 'active')
            ->with('tenant')
            ->get();

        foreach ($subscriptions as $sub) {
            $tenant = $sub->tenant;
            if (! $tenant) {
                continue;
            }

            $message = "<b>⏳ تنبيه: اقتراب انتهاء اشتراك (بعد 3 أيام)</b>\n\n";
            $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
            $message .= "<b>📦 الباقة:</b> {$sub->type_label}\n";
            $message .= '<b>📅 تاريخ الانتهاء:</b> '.$sub->ends_at->format('Y-m-d')."\n";
            $message .= '<b>💰 قيمة التجديد:</b> '.$sub->total_amount.' '.SiteSetting::get('currency_symbol', 'جنيه')."\n\n";
            $message .= 'يرجى التواصل مع العميل للتأكد من الرغبة في التجديد.';

            $telegram->sendAdminNotification($message);

            $admin = User::where('tenant_id', $tenant->id)
                ->where('role', 'center_admin')
                ->first();

            if ($admin) {
                $admin->notify(new \App\Notifications\GeneralNotification(
                    'subscription_expiring',
                    'باقي 3 أيام على انتهاء اشتراكك في '.($sub->type_label ?? 'الباقة الحالية'),
                    route('center.subscription'),
                    'fas fa-clock',
                    'System'
                ));
            }
        }

        $this->info('Sent '.$subscriptions->count().' subscription reminders.');
    }
}
