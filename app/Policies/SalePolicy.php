<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

use App\Traits\HasRoleCheck;

class SalePolicy
{
    use HasRoleCheck;

    public function viewAny(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('view sales');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->tenant_id === $sale->tenant_id && ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('view sales'));
    }

    public function create(User $user): bool
    {
        return $this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('create sales');
    }

    public function update(User $user, Sale $sale): bool
    {
        return $user->tenant_id === $sale->tenant_id && 
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('edit sales'));
    }

    public function delete(User $user, Sale $sale): bool
    {
        return $user->tenant_id === $sale->tenant_id && 
               ($this->hasAnyRole($user, ['center_admin', 'admin']) || $user->checkPermissionTo('delete sales'));
    }
}
