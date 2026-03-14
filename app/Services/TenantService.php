<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Student;
use App\Models\Course;
use App\Models\Sale;
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

        $lastSubscription = $tenant->subscriptions->last();
        
        $limits = [
            'students' => [
                'used' => $studentsCount,
                'total' => $lastSubscription?->plan?->students_limit ?? 100,
            ],
            'courses' => [
                'used' => $coursesCount,
                'total' => $lastSubscription?->plan?->courses_limit ?? 10,
            ]
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
        $tenantUserIds = $tenant->users->pluck('id');
        
        return Activity::with(['subject', 'causer'])
            ->whereIn('causer_id', $tenantUserIds)
            ->where('causer_type', User::class)
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Logic for impersonating a tenant admin.
     */
    public function getImpersonationUser(Tenant $tenant)
    {
        // Prioritize center_admin (Owner)
        $admin = $tenant->users()->where('role', 'center_admin')->orderBy('id', 'asc')->first();
        
        // Fallback to regular admin
        if (!$admin) {
            $admin = $tenant->users()->where('role', 'admin')->first();
        }

        return $admin;
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
        $history = $tenant->subscriptions()
            ->with('package')
            ->orderBy('created_at', 'asc') // Get in chronological order to calculate types
            ->get();
            
        $processedHistory = collect();
        $previousPackageId = null;
        
        foreach ($history as $index => $sub) {
            $currentPackageId = $sub->stripe_price ?? ($sub->paypal_plan_id ?? 'unknown');
            
            if ($index === 0) {
                $sub->operation_type = 'subscription'; // First one is always initial subscription
            } else {
                // If package changed, it's an upgrade/change. If same, it's a renewal.
                if ($currentPackageId !== $previousPackageId) {
                    $sub->operation_type = 'upgrade';
                } else {
                    $sub->operation_type = 'renewal';
                }
            }
            
            $previousPackageId = $currentPackageId;
            $processedHistory->push($sub);
        }
        
        return $processedHistory->reverse(); // Return latest first for the UI
    }

    /**
     * Get student growth data for the last 30 days.
     */
    public function getGrowthData(Tenant $tenant)
    {
        return Student::where('tenant_id', $tenant->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }
}
