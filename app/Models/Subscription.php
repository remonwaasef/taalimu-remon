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
                        \Illuminate\Support\Facades\Cache::store('redis')->forget("tenancy:domain:{$subscription->tenant->domain}");
                    }
                } catch (\Throwable $e) {}
                \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$subscription->tenant->domain}");
            }
        });

        static::deleted(function ($subscription) {
            if ($subscription->tenant) {
                try {
                    if (extension_loaded('redis')) {
                        \Illuminate\Support\Facades\Cache::store('redis')->forget("tenancy:domain:{$subscription->tenant->domain}");
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
        return $this->belongsTo(Package::class, 'stripe_price', 'stripe_price_id');
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
