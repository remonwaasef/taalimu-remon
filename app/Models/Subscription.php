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
                try {
                    if (extension_loaded('redis')) {
                        \Illuminate\Support\Facades\Cache::store('redis')->forget("taalimu:tenancy:domain:{$subscription->tenant->domain}");
                    }
                } catch (\Throwable $e) {}
                \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$subscription->tenant->domain}");
            }
        });

        static::deleted(function ($subscription) {
            if ($subscription->tenant) {
                try {
                    if (extension_loaded('redis')) {
                        \Illuminate\Support\Facades\Cache::store('redis')->forget("taalimu:tenancy:domain:{$subscription->tenant->domain}");
                    }
                } catch (\Throwable $e) {}
                \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$subscription->tenant->domain}");
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
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
        // Custom logic to handle demo price IDs and fallbacks
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
        if (str_starts_with($this->stripe_price, 'price_demo_')) {
            $slug = str_replace('price_demo_', '', $this->stripe_price);
            return Package::where('slug', $slug)->first();
        }

        // Fallback for free trial: price_free
        if ($this->stripe_price === 'price_free') {
            return Package::where('slug', 'free-trial')->first();
        }

        // Fallback for configuration mismatches: price_starter -> basic
        if ($this->stripe_price === 'price_starter') {
            return Package::where('slug', 'basic')->first();
        }
        if ($this->stripe_price === 'price_growth') {
            return Package::where('slug', 'pro')->first();
        }

        return null;
    }

    /**
     * Get the human-readable label for the subscription type.
     */
    public function getTypeLabelAttribute(): string
    {
        // Try to find the package by stripe_price_id
        $package = Package::where('stripe_price_id', $this->stripe_price)->first();
        
        if ($package) {
            return $package->name;
        }

        // Fallback for demo/special prices
        $price = $this->stripe_price;

        // Free Plan
        if ($price === 'price_free') {
            return 'مجاني';
        }

        // Basic Plan (Real or Demo)
        if ($price === config('services.stripe.price_basic') || $price === 'price_demo_basic') {
            return 'أساسي';
        }

        // Pro Plan (Real or Demo)
        if ($price === config('services.stripe.price_pro') || $price === 'price_demo_pro') {
            return 'احترافي';
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
