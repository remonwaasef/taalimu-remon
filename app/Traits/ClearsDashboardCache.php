<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsDashboardCache
{
    /**
     * Boot the trait and register model events.
     */
    protected static function bootClearsDashboardCache(): void
    {
        static::saved(fn() => static::clearDashboardCache());
        static::deleted(fn() => static::clearDashboardCache());
    }

    /**
     * Clear the dashboard-related caches for the current tenant.
     */
    public static function clearDashboardCache(): void
    {
        \App\Support\TenantCache::forget("dashboard_stats_v3");
        \App\Support\TenantCache::forget("recent_activities");
    }
}
