<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['center_admin', 'admin', 'instructor']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->tenant_id === $attendance->tenant_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['center_admin', 'admin', 'instructor']);
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->tenant_id === $attendance->tenant_id && 
               in_array($user->role, ['center_admin', 'admin', 'instructor']);
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->tenant_id === $attendance->tenant_id && 
               in_array($user->role, ['center_admin', 'admin']);
    }
}
