<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant, \Spatie\Activitylog\Traits\LogsActivity, \App\Traits\ClearsDashboardCache;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($instructor) {
            if ($instructor->tenant_id) {
                // High-Scale: Use the already resolved tenant from the app instance
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($instructor->tenant_id);
                if ($tenant && $tenant->id == $instructor->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->incrementUsage($tenant, 'max_instructors');
                }
            }
        });

        static::deleted(function ($instructor) {
            if ($instructor->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($instructor->tenant_id);
                if ($tenant && $tenant->id == $instructor->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->decrementUsage($tenant, 'max_instructors');
                }
            }
        });
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        $options = \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'email', 'specialization'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();

        if (config('app.performance_mode')) {
            $options->disableLogging();
        }

        return $options;
    }

    protected $guarded = ['id'];

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'email',
        'phone',
        'specialization',
        'status',
        'commission_rate',
        'commission_type',
        'national_id',
        'gender',
        'hiring_date',
        'bio',
        'is_co_instructor',
        'image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commission_rate' => 'decimal:2',
        'hiring_date' => 'date',
        'is_co_instructor' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'tenant_id',
        'phone',
    ];


    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, Course::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function getTotalEarnedAttribute()
    {
        return $this->commissions()->where('status', '!=', 'pending')->sum('amount');
    }

    public function getPendingEarningsAttribute()
    {
        return $this->commissions()->where('status', 'pending')->sum('amount');
    }

    public function getOutstandingBalanceAttribute()
    {
        $earned = $this->commissions()->where('status', '!=', 'pending')->sum('amount');
        $payouts = $this->payouts()->sum('amount');
        return $earned - $payouts;
    }
}
