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

        // 1. Subscriptions and Trials expiring in 3 days
        $expiringSubscriptions = \App\Models\Subscription::whereDate('ends_at', $threeDaysFromNow)
            ->where(function ($q) {
                $q->whereIn('stripe_status', ['active', 'trialing'])
                    ->orWhereIn('status', ['active', 'trialing']);
            })
            ->with('tenant')
            ->get();

        foreach ($expiringSubscriptions as $sub) {
            $tenant = $sub->tenant;
            if (! $tenant) {
                continue;
            }

            $isTrial = ($sub->status === 'trialing' || $sub->stripe_status === 'trialing');
            $statusLabel = $isTrial ? 'فترة تجريبية' : 'اشتراك نشط';

            // Notify Admin via Telegram
            $message = "<b>⏳ تنبيه: اقتراب انتهاء {$statusLabel} (بعد 3 أيام)</b>\n\n";
            $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
            $message .= "<b>📦 الباقة:</b> {$sub->type_label}\n";
            $message .= '<b>📅 تاريخ الانتهاء:</b> '.$sub->ends_at->format('Y-m-d')."\n";
            $message .= '<b>💰 قيمة التجديد:</b> '.$sub->total_amount.' '.\App\Models\SiteSetting::get('currency_symbol', 'جنيه')."\n\n";
            $message .= 'يرجى التواصل مع العميل للتأكد من الرغبة في التجديد.';

            try {
                app(\App\Services\TelegramService::class)->sendAdminNotification($message);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Telegram notification failed: '.$e->getMessage());
            }

            // Notify Center Admin via Email & Database (Dashboard Bell)
            $admin = \App\Models\User::where('tenant_id', $tenant->id)
                ->where('role', 'center_admin')
                ->first();

            if ($admin) {
                $admin->notify(new \App\Notifications\SubscriptionReminderNotification($sub));
            }
        }

        // 2. Subscriptions / Trials that have expired (ends_at <= now())
        $expiredSubscriptions = \App\Models\Subscription::where('ends_at', '<=', now())
            ->where(function ($q) {
                $q->where('status', 'trialing')
                    ->orWhere('stripe_status', 'trialing')
                    ->orWhere('status', 'active');
            })
            ->with('tenant')
            ->get();

        $expiredNotifiedCount = 0;
        foreach ($expiredSubscriptions as $sub) {
            $tenant = $sub->tenant;
            if (! $tenant) {
                continue;
            }

            $admin = \App\Models\User::where('tenant_id', $tenant->id)
                ->where('role', 'center_admin')
                ->first();

            if (! $admin) {
                continue;
            }

            // Guard against duplicate notification within 24 hours
            $alreadyNotifiedRecently = $admin->notifications()
                ->where('type', \App\Notifications\TrialExpiredNotification::class)
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (! $alreadyNotifiedRecently) {
                $admin->notify(new \App\Notifications\TrialExpiredNotification($sub));
                $expiredNotifiedCount++;

                // Notify Platform Super Admins via Telegram
                $telegramMsg = "<b>⚠️ إشعار: انتهاء الفترة المجانية/الاشتراك</b>\n\n";
                $telegramMsg .= "<b>🏢 المركز:</b> {$tenant->name}\n";
                $telegramMsg .= "<b>📦 الباقة:</b> {$sub->type_label}\n";
                $telegramMsg .= '<b>📅 تاريخ الانتهاء:</b> '.$sub->ends_at->format('Y-m-d')."\n";
                $telegramMsg .= "يرجى متابعة العميل لتحصيل الاشتراك.\n";

                try {
                    app(\App\Services\TelegramService::class)->sendAdminNotification($telegramMsg);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Telegram notification failed: '.$e->getMessage());
                }
            }
        }

        $this->info("Sent {$expiringSubscriptions->count()} expiring reminders and {$expiredNotifiedCount} expired notifications.");
    }
}
