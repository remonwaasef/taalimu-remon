<?php

namespace App\Traits;

use App\Models\User;

trait HasRoleCheck
{
    /**
     * Check if the user has any of the given roles, checking both Spatie roles and the simple role column.
     *
     * @param User $user
     * @param array|string $roles
     * @return bool
     */
    protected function hasAnyRole(User $user, array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        // 1. Check Spatie Roles (Standard)
        if ($user->hasRole($roles)) {
            return true;
        }

        // 2. Fallback: Check simple 'role' column (Legacy/Simple)
        return in_array($user->role, $roles);
    }
}
