<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckInactiveTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-inactive-tenants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for tenants that have been inactive (no courses or logins) for 14 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fourteenDaysAgo = now()->subDays(14);

        // Find tenants registered more than 14 days ago
        $tenants = \App\Models\Tenant::where('created_at', '<=', $fourteenDaysAgo)->get();

        $inactiveCount = 0;
        foreach ($tenants as $tenant) {
            // Check for courses (assuming Modules\Center\Models\Course)
            // If the module isn't loaded or model doesn't exist, we fallback
            $courseCount = 0;
            if (class_exists('\App\Models\Course')) {
                $courseCount = \App\Models\Course::where('tenant_id', $tenant->id)->count();
            }

            // Check for last login (assuming activity log or a field)
            $lastLogin = \Spatie\Activitylog\Models\Activity::where('subject_id', $tenant->id)
                ->where('subject_type', 'App\Models\Tenant')
                ->where('description', 'Successful Login')
                ->latest()
                ->first();

            if ($courseCount === 0 && (! $lastLogin || $lastLogin->created_at->lt($fourteenDaysAgo))) {
                app(\App\Services\TelegramService::class)->sendChurnWarning($tenant, 'لا يوجد كورسات محملة + لا يوجد تسجيل دخول منذ 14 يوم');
                $inactiveCount++;
            }
        }

        $this->info('Checked '.$tenants->count()." tenants. Found {$inactiveCount} inactive.");
    }
}
