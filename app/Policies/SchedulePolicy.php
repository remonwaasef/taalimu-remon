<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['center_admin', 'admin', 'instructor']);
    }

    public function view(User $user, Schedule $schedule): bool
    {
        return $user->tenant_id === $schedule->tenant_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['center_admin', 'admin']);
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return $user->tenant_id === $schedule->tenant_id && 
               in_array($user->role, ['center_admin', 'admin']);
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->tenant_id === $schedule->tenant_id && 
               in_array($user->role, ['center_admin', 'admin']);
    }
}
