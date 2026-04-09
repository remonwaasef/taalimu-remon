<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'name_fr',
        'slug',
        'price',
        'yearly_price',
        'term_price',
        'old_price',
        'discount_label',
        'duration_in_days',
        'description',
        'description_en',
        'description_fr',
        'stripe_price_id',
        'paypal_plan_id',
        'display_features',
        'badge',
        'is_active',
        'is_featured',
        'is_default',
        'sort_order',
        'trial_days',
        'custom_cta_link',
        'custom_cta_text',
        'regional_prices',
    ];

    protected $casts = [
        'display_features' => 'array',
        'regional_prices' => 'array',
        'price' => 'decimal:2',
        'term_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'package_features')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get pricing for a specific currency.
     */
    public function getRegionalPrice(string $currency = 'EGP'): array
    {
        $prices = $this->regional_prices ?? [];

        // Map currency code to the regional_prices key (country code)
        $currencyToRegionKey = [
            'EGP' => 'EG',
            'EUR' => 'FR',
            'USD' => 'default',
            'SAR' => 'SA',
            'AED' => 'AE',
        ];

        $regionKey = $currencyToRegionKey[$currency] ?? 'default';
        
        if (isset($prices[$regionKey])) {
            return $prices[$regionKey];
        }

        // Fallback to default if the region key wasn't found
        if (isset($prices['default'])) {
            return $prices['default'];
        }

        // Ultimate fallback to main price columns
        return [
            'amount' => (float)$this->price,
            'currency' => 'EGP',
            'yearly_price' => (float)$this->yearly_price,
            'term_price' => (float)$this->term_price,
        ];
    }
}
