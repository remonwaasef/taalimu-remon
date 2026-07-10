<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class SendDailyTelegramReport extends Command
{
    protected $signature = 'app:send-daily-telegram-report';

    protected $description = 'Send a daily summary of revenue and new signups to Telegram';

    public function handle(TelegramService $telegram)
    {
        $today = now()->startOfDay();

        $newTenants = \App\Models\Tenant::where('created_at', '>=', $today)->count();
        $newSubs = \App\Models\Subscription::where('created_at', '>=', $today)->where('stripe_status', 'active')->count();
        $dailyRevenue = \App\Models\Subscription::where('created_at', '>=', $today)->where('stripe_status', 'active')->sum('total_amount');

        $data = [
            'مراكز جديدة (اليوم)' => $newTenants,
            'اشتراكات جديدة (اليوم)' => $newSubs,
            'إيرادات اليوم' => number_format($dailyRevenue, 0).' '.SiteSetting::get('currency_symbol', 'جنيه'),
        ];

        $telegram->sendSummaryReport('التقرير اليومي للمنصة 💰', $data);

        $this->info('Daily report sent to Telegram.');
    }
}
