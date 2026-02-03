<?php

namespace Modules\Center\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant;

class Branch extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($branch) {
            if ($branch->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($branch->tenant_id);
                if ($tenant && $tenant->id == $branch->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->incrementUsage($tenant, 'max_branches');
                }
            }
        });

        static::deleted(function ($branch) {
            if ($branch->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($branch->tenant_id);
                if ($tenant && $tenant->id == $branch->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->decrementUsage($tenant, 'max_branches');
                }
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'phone',
        'manager_id', // Optional: link to a user who is the manager
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
