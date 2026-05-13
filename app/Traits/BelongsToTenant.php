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
 * لتجنب التعارض مع Middleware IdentifyTenant.
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
            if (app()->bound('tenant')) {
                $model->tenant_id = app('tenant')->id;
            }
        });
    }
}
