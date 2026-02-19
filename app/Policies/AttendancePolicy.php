<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return (int)$user->tenant_id === (int)$attendance->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return (int)$user->tenant_id === (int)$attendance->tenant_id && 
                $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return (int)$user->tenant_id === (int)$attendance->tenant_id && 
                $user->hasAnyRole(['center_admin', 'admin']);
    }
}
