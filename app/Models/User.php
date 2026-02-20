<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\ManagesTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity, HasApiTokens, ManagesTokens, \App\Traits\IdentifyTenant;

    protected static function boot()
    {
        parent::boot();

        // High-Scale: Cache Table Schema to prevent DESCRIBE queries
        if (app()->environment('production') && extension_loaded('redis')) {
            static::$appColumns = \Illuminate\Support\Facades\Cache::store('redis')->remember(
                'schema_columns_users', 
                86400, 
                fn() => \Illuminate\Support\Facades\Schema::getColumnListing('users')
            );
        }

        static::created(function ($user) {
            if ($user->role === 'student' && $user->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($user->tenant_id);
                if ($tenant && $tenant->id == $user->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->incrementUsage($tenant, 'max_students');
                }
            }
        });

        static::saved(function ($user) {
            \Illuminate\Support\Facades\Cache::forget("user_cache_{$user->id}");
            
            // Professional Sync: Ensure the 'role' column matches the primary Spatie role
            // This is a safety fallback for code that checks $user->role directly.
            $primaryRole = $user->getRoleNames()->first();
            if ($primaryRole && $user->role !== $primaryRole) {
                // Update without triggering events to prevent loops
                $user->newQuery()->where('id', $user->id)->update(['role' => $primaryRole]);
            }
        });

        static::deleted(function ($user) {
            \Illuminate\Support\Facades\Cache::forget("user_cache_{$user->id}");
            if ($user->role === 'student' && $user->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($user->tenant_id);
                if ($tenant && $tenant->id == $user->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->decrementUsage($tenant, 'max_students');
                }
            }
        });
    }

    protected static $appColumns = [];
    public function getTableColumns() { return static::$appColumns ?: parent::getTableColumns(); }

    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'role', 'tenant_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();

        // Performance Mode: Disable heavy logging if configured via ENV
        if (config('app.performance_mode')) {
            $options->disableLogging();
        }

        return $options;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'points',
        'tenant_id',
        'instructor_id',
        'must_change_password',
        'locale',
        'google_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
