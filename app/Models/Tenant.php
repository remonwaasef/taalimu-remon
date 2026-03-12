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

        // High-Scale: Cache Table Schema
        if (app()->environment('production') && extension_loaded('redis')) {
            static::$appColumns = \Illuminate\Support\Facades\Cache::remember(
                'schema_columns_tenants', 
                86400, 
                fn() => \Illuminate\Support\Facades\Schema::getColumnListing('tenants')
            );
        }

        static::saved(function ($tenant) {
            try {
                if (extension_loaded('redis')) {
                    \Illuminate\Support\Facades\Cache::forget("taalimu:tenancy:domain:{$tenant->domain}");
                }
            } catch (\Throwable $e) {
                // Fail silently if Redis is down or extension missing
            }
            // Keep old cache clearing for safety during transition
            \Illuminate\Support\Facades\Cache::forget("tenant_lookup_{$tenant->domain}");
        });

        static::deleted(function ($tenant) {
            try {
                if (extension_loaded('redis')) {
                    \Illuminate\Support\Facades\Cache::forget("taalimu:tenancy:domain:{$tenant->domain}");
                }
            } catch (\Throwable $e) {
                // Fail silently
            }
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
                'settings',
                'timezone'
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
        'type',
        'status',
        'settings',
        'timezone',
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
    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected static $appColumns = [];
    public function getTableColumns() { return static::$appColumns ?: parent::getTableColumns(); }

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
        // Check if relation is already loaded (from Cache Eager Loading)
        if ($this->relationLoaded('currentSubscription')) {
            return $this->currentSubscription;
        }

        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Relationship for Eager Loading in Cache
     */
    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    /**
     * Get the sales for the tenant.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the count of unique students with overdue payments.
     */
    public function getOverdueStudentsCount(): int
    {
        return $this->sales()
            ->whereRaw('paid_amount < total_amount')
            ->distinct('student_id')
            ->count('student_id');
    }

    /**
     * Check if tenant has access to a feature.
     */
    public function hasFeature(string $featureCode): bool
    {
        return app(\App\Services\SubscriptionService::class)->checkLimit($this, $featureCode);
    }

    /**
     * Get the value of a feature (ignoring usage).
     */
    public function getFeatureValue(string $featureCode)
    {
        return app(\App\Services\SubscriptionService::class)->getFeatureValue($this, $featureCode);
    }
}
