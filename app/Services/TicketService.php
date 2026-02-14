<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    /**
     * Reply to a ticket.
     */
    public function reply(Ticket $ticket, string $message)
    {
        $ticketMessage = TicketMessage::create([
            'tenant_id' => $ticket->tenant_id,
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $message,
        ]);

        // Re-open or update status based on who replied
        $status = Auth::user()->hasRole('super_admin') ? 'answered' : 'open';
        $ticket->update(['status' => $status]);

        return $ticketMessage;
    }

    /**
     * Close a ticket.
     */
    public function close(Ticket $ticket)
    {
        return $ticket->update(['status' => 'closed']);
    }

    /**
     * Create a new ticket.
     */
    public function createTicket(array $data)
    {
        $ticket = Ticket::create([
            'tenant_id' => $data['tenant_id'],
            'user_id' => Auth::id(),
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'status' => 'open',
        ]);

        TicketMessage::create([
            'tenant_id' => $ticket->tenant_id,
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $data['message'],
        ]);

        return $ticket;
    }
}
