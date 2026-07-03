<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendWeeklyTelegramReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-weekly-telegram-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a weekly summary report of platform performance to Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastWeek = now()->subDays(7);

        $newTenants = \App\Models\Tenant::where('created_at', '>=', $lastWeek)->count();
        $totalTenants = \App\Models\Tenant::count();

        $activeSubs = \App\Models\Subscription::where('stripe_status', 'active')->count();
        $totalRevenue = \App\Models\Subscription::where('stripe_status', 'active')->sum('total_amount');

        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $newStudents = \App\Models\User::where('role', 'student')->where('created_at', '>=', $lastWeek)->count();

        $data = [
            'مراكز جديدة (هذا الأسبوع)' => $newTenants,
            'إجمالي المراكز المسجلة' => $totalTenants,
            'اشتراكات نشطة حالياً' => $activeSubs,
            'إجمالي الإيرادات المتكررة' => number_format($totalRevenue, 0).' '.\App\Models\SiteSetting::get('currency_symbol', 'جنيه'),
            'طلاب جدد (هذا الأسبوع)' => $newStudents,
            'إجمالي الطلاب في المنصة' => $totalStudents,
        ];

        app(\App\Services\TelegramService::class)->sendSummaryReport('التقرير الأسبوعي للمنصة 📈', $data);

        $this->info('Weekly report sent to Telegram.');
    }
}
