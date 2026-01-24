<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Feature;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Subscribe a tenant to a package.
     */
    public function subscribe(Tenant $tenant, Package $package): Subscription
    {
        // Cancel existing active subscriptions
        $tenant->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        return Subscription::create([
            'tenant_id' => $tenant->id,
            'name' => 'default',
            'stripe_id' => 'sub_demo_' . time(),
            'stripe_status' => 'active',
            'stripe_price' => $package->stripe_price_id,
            'ends_at' => Carbon::now()->addDays($package->duration_in_days),
            'status' => 'active',
        ]);
    }

    /**
     * Check if a tenant has access to a feature and is within limits.
     */
    public function checkLimit(Tenant $tenant, string $featureCode): bool
    {
        $subscription = $tenant->activeSubscription();

        if (!$subscription) {
            return false;
        }

        $package = $subscription->package;
        
        if (!$package) {
            return false; // No package linked to subscription
        }

        $feature = Feature::where('code', $featureCode)->first();

        if (!$feature) {
            return false; // Feature doesn't exist
        }

        // Find the limit for this package
        $packageFeature = $package->features()->where('feature_id', $feature->id)->first();

        if (!$packageFeature) {
            return false; // Feature not included in package
        }

        $limit = $packageFeature->pivot->value;

        if ($feature->type === 'boolean') {
            return filter_var($limit, FILTER_VALIDATE_BOOLEAN);
        }

        if ($limit == -1) {
            return true; // Unlimited
        }

        // Calculate current usage
        $usage = $this->getUsage($tenant, $featureCode);

        return $usage < (int) $limit;
    }

    /**
     * Get current usage for a feature.
     */
    protected function getUsage(Tenant $tenant, string $featureCode): int
    {
        $cacheKey = "tenant_{$tenant->id}_usage_{$featureCode}";

        return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, function () use ($tenant, $featureCode) {
            switch ($featureCode) {
                case 'max_students':
                    return $tenant->users()->where('role', 'student')->count();
                case 'max_instructors':
                    return \App\Models\Instructor::where('tenant_id', $tenant->id)->count();
                case 'max_courses':
                    return \App\Models\Course::where('tenant_id', $tenant->id)->count();
                case 'max_classrooms':
                    return \App\Models\Classroom::where('tenant_id', $tenant->id)->count();
                case 'max_branches':
                    return \Modules\Center\Models\Branch::where('tenant_id', $tenant->id)->count();
                default:
                    return 0;
            }
        });
    }
}
