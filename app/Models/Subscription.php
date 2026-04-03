<?php

namespace App\Models;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    use \App\Traits\IdentifyTenant;

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($subscription) {
            if ($subscription->tenant) {
                // Clear tenant cache both from default and potential redis if exists
                try {
                    \Illuminate\Support\Facades\Cache::forget("taalimu:tenancy:domain:{$subscription->tenant->domain}");
                    \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$subscription->tenant->domain}");
                } catch (\Throwable $e) {}
            }
        });

        static::deleted(function ($subscription) {
            if ($subscription->tenant) {
                try {
                    \Illuminate\Support\Facades\Cache::forget("taalimu:tenancy:domain:{$subscription->tenant->domain}");
                    \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$subscription->tenant->domain}");
                } catch (\Throwable $e) {}
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'package_id',
        'name',
        'stripe_id',
        'paypal_id',
        'stripe_status',
        'paypal_status',
        'stripe_price',
        'paypal_plan_id',
        'quantity',
        'gateway',
        'trial_ends_at',
        'ends_at',
        'status', // Custom field
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'total_amount',
        'billing_cycle',
        'base_price',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the package associated with the subscription.
     */
    public function package()
    {
        // Branch based on gateway
        if ($this->gateway === 'paypal') {
            return $this->belongsTo(Package::class, 'paypal_plan_id', 'paypal_plan_id');
        }
        
        $relation = $this->belongsTo(Package::class, 'stripe_price', 'stripe_price_id');
        
        // If we're eager loading, we can't easily fallback here, 
        // but we'll handle it in the SubscriptionService or via an attribute.
        return $relation;
    }

    /**
     * Get the package with a fallback if the direct relationship fails.
     */
    public function getResolvedPackageAttribute()
    {
        $package = $this->package;
        if ($package) return $package;

        // Fallback for demo price IDs: price_demo_{slug}
        if ($this->gateway === 'stripe' && str_starts_with($this->stripe_price, 'price_demo_')) {
            $slug = str_replace('price_demo_', '', $this->stripe_price);
            return Package::where('slug', $slug)->first();
        }

        // Fallback for free trial: price_free
        if ($this->stripe_price === 'price_free') {
            return Package::where('slug', 'free-trial')->first();
        }

        // Robust Fallback for configuration mismatches or old data
        // price_starter -> basic
        // price_growth -> pro
        // price_enterprise -> enterprise
        $mappings = [
            'price_starter' => 'basic',
            'price_growth' => 'pro',
            'price_enterprise' => 'enterprise',
            'price_paymob_basic' => 'basic',
            'price_paymob_pro' => 'pro',
            'price_paymob_enterprise' => 'enterprise',
            'starter' => 'basic', // some old data might use plain slug
            'growth' => 'pro',
        ];

        if (isset($mappings[$this->stripe_price])) {
            return Package::where('slug', $mappings[$this->stripe_price])->first();
        }

        // Final attempt: check if stripe_price itself is a slug
        return Package::where('slug', $this->stripe_price)->first();
    }

    /**
     * Get the human-readable label for the subscription type.
     */
    public function getTypeLabelAttribute(): string
    {
        $package = $this->resolved_package;
        if ($package) {
            return app()->getLocale() == 'ar' ? $package->name : ($package->name_en ?: $package->name);
        }

        return 'مخصص';
    }

    /**
     * Get the tenant that owns the subscription.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
