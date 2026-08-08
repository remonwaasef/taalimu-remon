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
            // Global users (tenant_id = null, e.g. global super-admins) must stay
            // accessible even when a tenant is bound to the request — otherwise
            // they can never sign in on tenant-bound hosts (u.taalimu.com).
            if ($model instanceof \App\Models\User) {
                $builder->where(function (Builder $q) use ($model) {
                    $q->where($model->getTable().'.tenant_id', app('tenant')->id)
                        ->orWhereNull($model->getTable().'.tenant_id');
                });

                return;
            }

            $builder->where($model->getTable().'.tenant_id', app('tenant')->id);
        }
    }
}
