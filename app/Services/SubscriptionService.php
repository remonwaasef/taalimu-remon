<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Tenant;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Flag to silence resource limit warnings temporarily.
     */
    public static $isSilenced = false;

    /**
     * Silences or unsilences resource limit warnings.
     */
    public static function silence(bool $value = true)
    {
        self::$isSilenced = $value;
    }

    /**
     * Subscribe a tenant to a package.
     */
    public function subscribe(Tenant $tenant, Package $package): Subscription
    {
        // Cancel existing active subscriptions
        $tenant->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        return Subscription::forceCreate([
            'tenant_id' => $tenant->id,
            'name' => 'default',
            'stripe_id' => 'sub_demo_'.time(),
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
        $subscription = $tenant->active_subscription;

        if (! $subscription) {
            return false;
        }

        // Zero DB Hits: Check if package and features are already loaded from Cache
        $package = $subscription->relationLoaded('package') ? $subscription->package : null;

        if ($package && $package->relationLoaded('features')) {
            $packageFeature = $package->features->firstWhere('code', $featureCode);
        } else {
            // Use resolved_package attribute which has fallbacks for demo/mismatched price IDs
            $package = $subscription->resolved_package;
            if (! $package) {
                return false;
            }
            $packageFeature = $package->features()->where('code', $featureCode)->first();
        }

        if (! $packageFeature) {
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
     * Get the raw value of a feature from the package (ignoring current usage).
     * Useful for UI visibility logic.
     */
    public function getFeatureValue(Tenant $tenant, string $featureCode)
    {
        $subscription = $tenant->active_subscription;
        if (! $subscription) {
            return false;
        }

        $package = $subscription->resolved_package;
        if (! $package) {
            return false;
        }

        $packageFeature = $package->features()->where('code', $featureCode)->first();
        if (! $packageFeature) {
            return false;
        }

        $limit = $packageFeature->pivot ? $packageFeature->pivot->value : $packageFeature->value;

        if ($packageFeature->type === 'boolean') {
            return filter_var($limit, FILTER_VALIDATE_BOOLEAN);
        }

        return $limit;
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

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($tenant, $featureCode) {
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

            // check for 90% limit warning
            $this->checkThresholdWarning($tenant, $featureCode);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("SubscriptionService: Failed to increment usage for tenant {$tenant->id}, feature {$featureCode}. Error: ".$e->getMessage());
        }
    }

    /**
     * Check if usage reached 90% threshold and notify admin.
     */
    protected function checkThresholdWarning(Tenant $tenant, string $featureCode)
    {
        if (self::$isSilenced) {
            return;
        }

        $subscription = $tenant->active_subscription;
        if (! $subscription) {
            return;
        }

        $package = $subscription->resolved_package;
        if (! $package) {
            return;
        }

        $packageFeature = $package->features()->where('code', $featureCode)->first();
        if (! $packageFeature) {
            return;
        }

        $limit = (int) ($packageFeature->pivot ? $packageFeature->pivot->value : $packageFeature->value);
        if ($limit <= 0) {
            return;
        } // Unlimited or invalid

        $usage = $this->getUsage($tenant, $featureCode);

        if ($usage >= ($limit * 0.9)) {
            // Use cache to prevent spamming (once per day per resource)
            $alertKey = "tenant_{$tenant->id}_alert_sent_{$featureCode}_".now()->format('Y-m-d');
            if (! \Illuminate\Support\Facades\Cache::has($alertKey)) {
                app(\App\Services\TelegramService::class)->sendResourceLimitWarning($tenant, $featureCode, $usage, $limit);
                \Illuminate\Support\Facades\Cache::put($alertKey, true, now()->addDay());
            }
        }
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
                if ($current && (int) $current > 0) {
                    \Illuminate\Support\Facades\Cache::store('redis')->decrement($cacheKey);
                }
            } else {
                \Illuminate\Support\Facades\Cache::forget($cacheKey);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("SubscriptionService: Failed to decrement usage for tenant {$tenant->id}, feature {$featureCode}. Error: ".$e->getMessage());
        }
    }
}
