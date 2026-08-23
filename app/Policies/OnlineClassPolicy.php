<?php

namespace App\Policies;

use App\Models\OnlineClass;
use App\Models\User;

class OnlineClassPolicy
{
    /**
     * Who can manage classes at all (listing / creation).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['instructor', 'center_admin', 'admin']);
    }

    public function view(User $user, OnlineClass $class): bool
    {
        return $this->manage($user, $class) || $class->hasAccess($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['instructor', 'center_admin']);
    }

    public function update(User $user, OnlineClass $class): bool
    {
        return $this->manage($user, $class);
    }

    public function delete(User $user, OnlineClass $class): bool
    {
        return $this->manage($user, $class);
    }

    public function start(User $user, OnlineClass $class): bool
    {
        return $this->manage($user, $class)
            && ! in_array($class->status, [OnlineClass::STATUS_COMPLETED, OnlineClass::STATUS_CANCELLED], true);
    }

    public function end(User $user, OnlineClass $class): bool
    {
        return $this->manage($user, $class) && $class->status === OnlineClass::STATUS_LIVE;
    }

    public function join(User $user, OnlineClass $class): bool
    {
        // Cross-tenant access is already impossible: TenantScope filters the
        // class lookup itself; this guards role/ownership/enrollment.
        if ($class->status !== OnlineClass::STATUS_LIVE && ! $class->isJoinableNow()) {
            return false;
        }

        return $this->manage($user, $class) || $class->hasAccess($user);
    }

    public function viewAttendance(User $user, OnlineClass $class): bool
    {
        return $this->manage($user, $class);
    }

    /**
     * Owning instructor (by instructor profile id) or center admins.
     */
    protected function manage(User $user, OnlineClass $class): bool
    {
        if ((int) $user->tenant_id !== (int) $class->tenant_id) {
            return false;
        }

        if ($user->hasAnyRole(['center_admin', 'admin'])) {
            return true;
        }

        return $user->instructor !== null
            && (int) $user->instructor->id === (int) $class->instructor_id;
    }
}
