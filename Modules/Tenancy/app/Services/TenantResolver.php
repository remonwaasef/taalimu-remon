<?php

namespace Modules\Tenancy\app\Services;

use App\Models\Tenant;

class TenantResolver
{
    /**
     * Get the currently active tenant ID safely.
     *
     * @return string|null
     */
    public static function id()
    {
        return self::get()?->id;
    }

    /**
     * Get the currently active tenant instance safely.
     *
     * @return Tenant|null
     */
    public static function get()
    {
        if (app()->bound('tenant')) {
            return app('tenant');
        }
        
        return null;
    }

    /**
     * Set the current tenant manually (Crucial for Queues, Jobs, and Commands).
     *
     * @param Tenant $tenant
     */
    public static function set(Tenant $tenant)
    {
        app()->instance('tenant', $tenant);
    }
}
