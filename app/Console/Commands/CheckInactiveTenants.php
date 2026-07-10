<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Tenant;
use App\Services\TelegramService;
use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class CheckInactiveTenants extends Command
{
    protected $signature = 'app:check-inactive-tenants';

    protected $description = 'Check for tenants that have been inactive (no courses or logins) for 14 days';

    public function handle(TelegramService $telegram)
    {
        $fourteenDaysAgo = now()->subDays(14);

        $tenants = Tenant::where('created_at', '<=', $fourteenDaysAgo)->get();

        $inactiveCount = 0;
        foreach ($tenants as $tenant) {
            $courseCount = 0;
            if (class_exists(Course::class)) {
                $courseCount = Course::where('tenant_id', $tenant->id)->count();
            }

            $lastLogin = Activity::where('subject_id', $tenant->id)
                ->where('subject_type', 'App\Models\Tenant')
                ->where('description', 'Successful Login')
                ->latest()
                ->first();

            if ($courseCount === 0 && (! $lastLogin || $lastLogin->created_at->lt($fourteenDaysAgo))) {
                $telegram->sendChurnWarning($tenant, 'لا يوجد كورسات محملة + لا يوجد تسجيل دخول منذ 14 يوم');
                $inactiveCount++;
            }
        }

        $this->info('Checked '.$tenants->count()." tenants. Found {$inactiveCount} inactive.");
    }
}
