<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;
use Spatie\Activitylog\Traits\LogsActivity; // Added direct import for LogsActivity
use Spatie\Activitylog\LogOptions; // Added direct import for LogOptions

class Tenant extends Model
{
    use HasFactory, Billable, LogsActivity;
    
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($tenant) {
            \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$tenant->domain}");
        });

        static::deleted(function ($tenant) {
            \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$tenant->domain}");
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 
                'email', 
                'phone', 
                'address', 
                'logo', 
                'favicon', 
                'description', 
                'facebook_url', 
                'instagram_url', 
                'twitter_url', 
                'youtube_url', 
                'linkedin_url', 
                'domain', 
                'status',
                'settings'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'favicon',
        'description',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'linkedin_url',
        'domain',
        'database_name',
        'status',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'database_name',
        'settings',
        'stripe_id',
        'pm_type',
        'pm_last_four',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the users for the tenant.
     */
    /**
     * Get the users for the tenant.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the subscriptions for the tenant.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription for the tenant.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Check if tenant has access to a feature.
     */
    public function hasFeature(string $featureCode): bool
    {
        return app(\App\Services\SubscriptionService::class)->checkLimit($this, $featureCode);
    }
}
