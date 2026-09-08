<?php

namespace App\Policies;

use App\Models\PublicProfile;
use App\Models\User;
use App\Traits\HasRoleCheck;

class PublicProfilePolicy
{
    use HasRoleCheck;

    /**
     * Determine if the user can view the growth profile settings.
     */
    public function viewSettings(User $user, PublicProfile $profile): bool
    {
        if ($this->hasAnyRole($user, 'super_admin')) {
            return true;
        }

        if ((int) $user->tenant_id !== (int) $profile->tenant_id) {
            return false;
        }

        return $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor']);
    }

    /**
     * Determine if the user can update the growth profile.
     */
    public function update(User $user, PublicProfile $profile): bool
    {
        if ($this->hasAnyRole($user, 'super_admin')) {
            return true;
        }

        if ((int) $user->tenant_id !== (int) $profile->tenant_id) {
            return false;
        }

        return $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor']);
    }

    /**
     * Determine if the user can publish/unpublish the growth profile.
     */
    public function publish(User $user, PublicProfile $profile): bool
    {
        if ($this->hasAnyRole($user, 'super_admin')) {
            return true;
        }

        if ((int) $user->tenant_id !== (int) $profile->tenant_id) {
            return false;
        }

        return $this->hasAnyRole($user, ['center_admin', 'admin', 'center_owner', 'instructor']);
    }
}
