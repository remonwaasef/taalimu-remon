<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class Classroom extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($classroom) {
            if (!$classroom->invite_uuid) {
                $classroom->invite_uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        static::created(function ($classroom) {
            if ($classroom->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($classroom->tenant_id);
                if ($tenant && $tenant->id == $classroom->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->incrementUsage($tenant, 'max_classrooms');
                }
            }
        });

        static::deleted(function ($classroom) {
            if ($classroom->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($classroom->tenant_id);
                if ($tenant && $tenant->id == $classroom->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->decrementUsage($tenant, 'max_classrooms');
                }
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'capacity',
        'type',
        'color',
        'is_active',
        'facilities_summary',
        'invite_uuid',
        'is_registration_open',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'facilities_summary' => 'array',
    ];

    public function assets()
    {
        return $this->hasMany(Modules\Center\Models\Asset::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'capacity'])
            ->logOnlyDirty();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
