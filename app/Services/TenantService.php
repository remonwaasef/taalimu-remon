<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Sale;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use App\Scopes\TenantScope;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class TenantService
{
    /**
     * Get statistics for a specific tenant.
     */
    public function getTenantStats(Tenant $tenant)
    {
        $studentsCount = Student::where('tenant_id', $tenant->id)->count();
        $coursesCount = Course::withoutGlobalScope(TenantScope::class)->where('tenant_id', $tenant->id)->count();
        $totalRevenue = Sale::where('tenant_id', $tenant->id)->where('status', 'paid')->sum('paid_amount');

        $lastSubscription = $tenant->subscriptions()->latest()->first();

        $limits = [
            'students' => [
                'used' => $studentsCount,
                'total' => $lastSubscription?->plan?->students_limit ?? 100,
            ],
            'courses' => [
                'used' => $coursesCount,
                'total' => $lastSubscription?->plan?->courses_limit ?? 10,
            ],
        ];

        return [
            'studentsCount' => $studentsCount,
            'coursesCount' => $coursesCount,
            'totalRevenue' => $totalRevenue,
            'limits' => $limits,
        ];
    }

    /**
     * Get recent activity for a tenant.
     */
    public function getRecentActivity(Tenant $tenant, int $limit = 5)
    {
        $tenantId = $tenant->id;
        return Activity::with(['subject', 'causer'])
            ->where('causer_type', User::class)
            ->whereExists(function ($q) use ($tenantId) {
                $q->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.id', 'activity_log.causer_id')
                    ->where('users.tenant_id', $tenantId);
            })
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Logic for impersonating a tenant admin.
     */
    public function getImpersonationUser(Tenant $tenant)
    {
        return $tenant->users()
            ->whereIn('role', ['center_admin', 'admin', 'instructor'])
            ->orderByRaw("FIELD(role, 'center_admin', 'admin', 'instructor')")
            ->first();
    }

    /**
     * Get staff/instructors for a tenant.
     */
    public function getStaff(Tenant $tenant)
    {
        return User::where('tenant_id', $tenant->id)
            ->whereIn('role', ['instructor', 'staff'])
            ->get();
    }

    /**
     * Get all subscriptions for a tenant.
     */
    public function getSubscriptionHistory(Tenant $tenant)
    {
        // Use subscription_logs table if available
        $logs = $tenant->subscriptionLogs()
            ->orderBy('created_at', 'desc')
            ->get();

        // Fallback: if no logs exist yet, create an entry from the current active subscription
        if ($logs->isEmpty()) {
            $currentSub = $tenant->activeSubscription();
            if ($currentSub) {
                $package = $currentSub->resolved_package;
                try {
                    $log = \App\Models\SubscriptionLog::logOperation(
                        $tenant->id,
                        'subscription',
                        $package->slug ?? 'unknown',
                        $package->name ?? 'غير محدد',
                        $currentSub->billing_cycle ?? 'monthly',
                        $currentSub->gateway ?? 'unknown',
                        $currentSub->total_amount ?? 0,
                        $currentSub->stripe_id ?? null,
                        $currentSub->created_at,
                        $currentSub->ends_at
                    );
                    $logs = collect([$log]);
                } catch (\Throwable $e) {
                    // If subscription_logs table doesn't exist yet, fall back to old logic
                    $logs = $tenant->subscriptions()
                        ->with('package')
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function ($sub) {
                            $sub->operation_type = 'subscription';
                            $sub->package_name = $sub->package->name ?? 'غير محدد';
                            $sub->package_slug = $sub->package->slug ?? 'unknown';
                            $sub->amount = $sub->total_amount ?? 0;
                            $sub->transaction_id = $sub->stripe_id;

                            return $sub;
                        });
                }
            }
        }

        return $logs;
    }

    /**
     * Get student growth data for the last 30 days.
     */
    public function getGrowthData(Tenant $tenant)
    {
        return Student::where('tenant_id', $tenant->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('cast(created_at as date) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('cast(created_at as date)'))
            ->orderBy('date', 'asc')
            ->get();
    }
}
