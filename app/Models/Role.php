<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends SpatieRole
{
    use \App\Traits\IsImmutable;
    use \Spatie\Activitylog\Traits\LogsActivity;
    use \App\Traits\IdentifyTenant;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'guard_name', 'tenant_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs(); 
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::saved(function ($role) {
            if ($role->tenant_id) {
                app(\App\Repositories\RoleRepository::class)->clearCache($role->tenant_id);
            }
        });

        static::deleted(function ($role) {
            if ($role->tenant_id) {
                app(\App\Repositories\RoleRepository::class)->clearCache($role->tenant_id);
            }
        });
    }
}
