<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class SendWeeklyTelegramReport extends Command
{
    protected $signature = 'app:send-weekly-telegram-report';

    protected $description = 'Send a weekly summary report of platform performance to Telegram';

    public function handle(TelegramService $telegram)
    {
        $lastWeek = now()->subDays(7);

        $newTenants = Tenant::where('created_at', '>=', $lastWeek)->count();
        $totalTenants = Tenant::count();

        $activeSubs = Subscription::where('stripe_status', 'active')->count();
        $totalRevenue = Subscription::where('stripe_status', 'active')->sum('total_amount');

        $totalStudents = User::where('role', 'student')->count();
        $newStudents = User::where('role', 'student')->where('created_at', '>=', $lastWeek)->count();

        $data = [
            'مراكز جديدة (هذا الأسبوع)' => $newTenants,
            'إجمالي المراكز المسجلة' => $totalTenants,
            'اشتراكات نشطة حالياً' => $activeSubs,
            'إجمالي الإيرادات المتكررة' => number_format($totalRevenue, 0).' '.SiteSetting::get('currency_symbol', 'جنيه'),
            'طلاب جدد (هذا الأسبوع)' => $newStudents,
            'إجمالي الطلاب في المنصة' => $totalStudents,
        ];

        $telegram->sendSummaryReport('التقرير الأسبوعي للمنصة 📈', $data);

        $this->info('Weekly report sent to Telegram.');
    }
}
