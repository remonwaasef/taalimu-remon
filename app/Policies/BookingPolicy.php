<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->tenant_id === $booking->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->tenant_id === $booking->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin', 'instructor']);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->tenant_id === $booking->tenant_id && 
               $user->hasAnyRole(['center_admin', 'admin']);
    }
}
