<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['center_admin', 'admin', 'instructor', 'student']);
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->tenant_id === $ticket->tenant_id &&
               ($user->id === $ticket->user_id || $user->hasAnyRole(['center_admin', 'admin']));
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user in the tenant can create a ticket
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->tenant_id === $ticket->tenant_id &&
               ($user->id === $ticket->user_id || $user->hasAnyRole(['center_admin', 'admin']));
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->tenant_id === $ticket->tenant_id &&
               $user->hasAnyRole(['center_admin', 'admin']);
    }
}
