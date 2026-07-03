<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']) || $user->checkPermissionTo('view schedule');
    }

    public function view(User $user, Schedule $schedule): bool
    {
        if ((int)$user->tenant_id !== (int)$schedule->tenant_id) {
            return false;
        }

        if ($user->hasAnyRole(['center_admin', 'admin']) || $user->checkPermissionTo('view schedule')) {
            return true;
        }

        return $user->hasRole('instructor') && (int)$user->instructor?->id === (int)$schedule->instructor_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']) || $user->checkPermissionTo('manage schedule');
    }

    public function update(User $user, Schedule $schedule): bool
    {
        if ((int)$user->tenant_id !== (int)$schedule->tenant_id) {
            return false;
        }

        if ($user->hasAnyRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage schedule')) {
            return true;
        }

        return $user->hasRole('instructor') && (int)$user->instructor?->id === (int)$schedule->instructor_id;
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        if ((int)$user->tenant_id !== (int)$schedule->tenant_id) {
            return false;
        }

        if ($user->hasAnyRole(['center_admin', 'admin']) || $user->checkPermissionTo('manage schedule')) {
            return true;
        }

        return $user->hasRole('instructor') && (int)$user->instructor?->id === (int)$schedule->instructor_id;
    }
}
