<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope automatically filters all queries by the current tenant.
 * 
 * Usage: Add `use HasTenantScope;` to any model that has a `tenant_id` column.
 * This prevents accidental cross-tenant data access.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->bound('tenant')) {
            $builder->where($model->getTable() . '.tenant_id', app('tenant')->id);
        }
    }
}
