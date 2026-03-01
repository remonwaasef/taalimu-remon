<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailyTelegramReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-telegram-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily summary of revenue and new signups to Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();
        
        $newTenants = \App\Models\Tenant::where('created_at', '>=', $today)->count();
        $newSubs = \App\Models\Subscription::where('created_at', '>=', $today)->where('stripe_status', 'active')->count();
        $dailyRevenue = \App\Models\Subscription::where('created_at', '>=', $today)->where('stripe_status', 'active')->sum('total_amount');
        
        $data = [
            'مراكز جديدة (اليوم)' => $newTenants,
            'اشتراكات جديدة (اليوم)' => $newSubs,
            'إيرادات اليوم' => number_format($dailyRevenue, 0) . ' ' . \App\Models\SiteSetting::get('currency_symbol', 'جنيه'),
        ];

        app(\App\Services\TelegramService::class)->sendSummaryReport('التقرير اليومي للمنصة 💰', $data);

        $this->info('Daily report sent to Telegram.');
    }
}
