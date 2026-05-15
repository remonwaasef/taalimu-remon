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

    /**
     * Map packages into formatted display data for views.
     */
    public static function getDisplayData($packages, string $currency)
    {
        return $packages->map(function($p) use ($currency) {
            $discountPercent = 0;
            $savingsAmount = 0;
            if ($p->old_price > 0 && $p->old_price > $p->price) {
                $discountPercent = round((($p->old_price - $p->price) / $p->old_price) * 100);
                $savingsAmount = $p->old_price - $p->price;
            }

            return [
                'slug' => $p->slug,
                'name' => match(app()->getLocale()) {
                    'ar' => $p->name,
                    'fr' => $p->name_fr ?: ($p->name_en ?: $p->name),
                    default => $p->name_en ?: $p->name,
                },
                'price' => number_format($p->price, 0) . ' ' . $currency,
                'price_value' => number_format($p->price, 0),
                'price_raw' => (float)$p->price,
                'old_price' => $p->old_price > 0 ? number_format($p->old_price, 0) . ' ' . $currency : null,
                'old_price_value' => $p->old_price > 0 ? number_format($p->old_price, 0) : null,
                'old_price_raw' => (float)$p->old_price,
                'currency' => $currency,
                'discount_percent' => $discountPercent > 0 ? $discountPercent : null,
                'savings_amount' => $savingsAmount > 0 ? number_format($savingsAmount, 0) : null,
                'discount_label' => $p->discount_label,
                'term_price' => $p->term_price ? number_format($p->term_price, 0) . ' ' . $currency : number_format($p->price * 4, 0) . ' ' . $currency,
                'term_price_value' => $p->term_price ? number_format($p->term_price, 0) : number_format($p->price * 4, 0),
                'term_price_raw' => $p->term_price ?: ($p->price * 4),
                'yearly_price' => $p->yearly_price ? number_format($p->yearly_price, 0) . ' ' . $currency : number_format($p->price * 10, 0) . ' ' . $currency,
                'yearly_price_value' => $p->yearly_price ? number_format($p->yearly_price, 0) : number_format($p->price * 10, 0),
                'yearly_price_raw' => $p->yearly_price ?: ($p->price * 10),
                'regional_prices' => $p->regional_prices ?? [],
                'trial_days' => (int)$p->trial_days,
                'features' => ($p->display_features && is_array($p->display_features) && count($p->display_features) > 0) 
                    ? $p->display_features 
                    : $p->features->map(function($f) {
                        $name = app()->getLocale() == 'ar' ? $f->name : ($f->name_en ?: $f->name);
                        $value = $f->pivot->value;
                        if ($value && !in_array(strtolower($value), ['true', '1', 'yes'])) {
                            return $name . ': ' . $value;
                        }
                        return $name;
                    })->toArray(),
            ];
        })->values();
    }
}
