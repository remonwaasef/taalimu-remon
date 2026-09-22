<?php

namespace App\Traits;

use App\Scopes\TenantScope;

/**
 * Trait BelongsToTenant
 *
 * يُضاف لأي Model يخضع لعزل بيانات المستأجرين (Tenant Isolation).
 * يقوم تلقائياً بإضافة TenantScope وضبط tenant_id عند الإنشاء.
 *
 * ملاحظة: تمت إعادة تسمية هذا الـ Trait من IdentifyTenant إلى BelongsToTenant
 * لتجنب التعارض مع Middleware IdentifyTenant (أُزيل الاسم القديم نهائياً).
 *
 * Security: tenant_id is mass-assignable for internal use (tests, services, commands).
 * Controllers MUST NOT accept tenant_id from request input - always use TenantResolver::get()
 * or app('tenant') to set it explicitly.
 */
trait BelongsToTenant
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            // Only auto-set tenant_id if not already set (e.g., from mass assignment in tests/services)
            // and if tenant context is bound.
            if (empty($model->tenant_id) && app()->bound('tenant')) {
                $model->tenant_id = app('tenant')->id;
            }
        });
    }

    /**
     * Scope a query to only include records of a given tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $tenantId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTenant($query, $tenantId = null)
    {
        $tenantId = $tenantId ?? (app()->bound('tenant') ? app('tenant')->id : null);
        if ($tenantId) {
            return $query->where($this->getTable().'.tenant_id', $tenantId);
        }

        return $query;
    }
}
