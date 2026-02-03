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

        // Zero DB Hits: Check if package and features are already loaded from Cache
        if ($subscription->relationLoaded('package') && $subscription->package && $subscription->package->relationLoaded('features')) {
            $packageFeature = $subscription->package->features->firstWhere('code', $featureCode);
        } else {
            // Fallback to DB if not loaded for some reason
            $package = $subscription->package;
            if (!$package) return false;
            $packageFeature = $package->features()->where('code', $featureCode)->first();
        }

        if (!$packageFeature) {
            return false; // Feature not included in package
        }

        // Handle both loaded collection and pivot object
        $limit = $packageFeature->pivot ? $packageFeature->pivot->value : $packageFeature->value;

        if ($packageFeature->type === 'boolean') {
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
     * Get current usage for a feature (Atomic Optimization).
     */
    protected function getUsage(Tenant $tenant, string $featureCode): int
    {
        $cacheKey = "tenant_{$tenant->id}_usage_{$featureCode}";

        // Use Atomic Counter if enabled, otherwise fallback to heavy count
        if (extension_loaded('redis')) {
            $usage = \Illuminate\Support\Facades\Cache::store('redis')->get($cacheKey);
            if ($usage !== null) {
                return (int) $usage;
            }
        }

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

    /**
     * Increment usage counter atomically.
     */
    public function incrementUsage(Tenant $tenant, string $featureCode)
    {
        $cacheKey = "tenant_{$tenant->id}_usage_{$featureCode}";
        try {
            if (extension_loaded('redis')) {
                \Illuminate\Support\Facades\Cache::store('redis')->increment($cacheKey);
            } else {
                \Illuminate\Support\Facades\Cache::forget($cacheKey);
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Decrement usage counter atomically.
     */
    public function decrementUsage(Tenant $tenant, string $featureCode)
    {
        $cacheKey = "tenant_{$tenant->id}_usage_{$featureCode}";
        try {
            if (extension_loaded('redis')) {
                $current = \Illuminate\Support\Facades\Cache::store('redis')->get($cacheKey);
                if ($current && (int)$current > 0) {
                    \Illuminate\Support\Facades\Cache::store('redis')->decrement($cacheKey);
                }
            } else {
                \Illuminate\Support\Facades\Cache::forget($cacheKey);
            }
        } catch (\Throwable $e) {}
    }
}
