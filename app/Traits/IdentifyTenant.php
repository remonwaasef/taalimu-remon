<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait IdentifyTenant
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootIdentifyTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (app()->bound('tenant')) {
                $model->tenant_id = app('tenant')->id;
            }
        });
    }
}
