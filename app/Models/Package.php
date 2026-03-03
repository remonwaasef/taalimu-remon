<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'price',
        'yearly_price',
        'old_price',
        'discount_label',
        'duration_in_days',
        'description',
        'description_en',
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
}
