<?php

namespace Modules\Center\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use \App\Traits\IdentifyTenant, HasFactory;

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
