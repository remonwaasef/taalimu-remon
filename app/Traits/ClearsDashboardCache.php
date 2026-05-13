<?php

namespace App\Traits;

use App\Queries\CenterAnalyticsQuery;

/**
 * Trait ClearsDashboardCache
 * 
 * يُمسح كاش لوحة التحكم تلقائياً عند إنشاء/تحديث/حذف نموذج.
 * يُستخدم في Models التي تؤثر على بيانات لوحة التحكم (Sale, Student, Attendance).
 * 
 * Usage: use ClearsDashboardCache; في الـ Model المطلوب.
 */
trait ClearsDashboardCache
{
    public static function bootClearsDashboardCache(): void
    {
        static::created(function ($model) {
            static::clearAnalyticsCache($model);
        });

        static::updated(function ($model) {
            static::clearAnalyticsCache($model);
        });

        static::deleted(function ($model) {
            static::clearAnalyticsCache($model);
        });
    }

    /**
     * مسح كاش التحليلات للمستأجر المرتبط بالنموذج.
     */
    protected static function clearAnalyticsCache($model): void
    {
        $tenantId = $model->tenant_id ?? (app()->bound('tenant') ? app('tenant')->id : null);
        
        if ($tenantId) {
            CenterAnalyticsQuery::clearCacheForTenant($tenantId);
        }
    }
}
