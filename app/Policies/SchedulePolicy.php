<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function view(User $user, Schedule $schedule): bool
    {
        return (int)$user->tenant_id === (int)$schedule->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin']);
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return (int)$user->tenant_id === (int)$schedule->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin']);
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        return (int)$user->tenant_id === (int)$schedule->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin']);
    }
}
