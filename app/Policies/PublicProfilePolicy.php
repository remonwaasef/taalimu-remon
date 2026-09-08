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
        return $user->tenant_id === $profile->tenant_id &&
               $this->hasAnyRole($user, ['center_admin', 'admin', 'instructor']);
    }

    /**
     * Determine if the user can update the growth profile.
     */
    public function update(User $user, PublicProfile $profile): bool
    {
        return $user->tenant_id === $profile->tenant_id &&
               $this->hasAnyRole($user, ['center_admin', 'admin', 'instructor']);
    }

    /**
     * Determine if the user can publish/unpublish the growth profile.
     */
    public function publish(User $user, PublicProfile $profile): bool
    {
        return $user->tenant_id === $profile->tenant_id &&
               $this->hasAnyRole($user, ['center_admin', 'admin']);
    }
}
