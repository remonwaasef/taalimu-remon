<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(\App\Services\TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        // Authorization: Only super admins can manage tickets
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $tickets = Ticket::with(['tenant', 'user'])
            ->latest()
            ->paginate(15);
            
        return view('admin::tickets.index', compact('tickets'));
    }

    public function show($ticketId)
    {
        // Authorization
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $ticket = Ticket::with(['tenant', 'messages.user'])->findOrFail($ticketId);
        
        return view('admin::tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $ticketId)
    {
        // Authorization
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($ticketId);
        $this->ticketService->reply($ticket, $request->message);

        return back()->with('success', 'Reply sent.');
    }

    public function close($ticketId)
    {
        // Authorization
        if (!auth()->user()->hasRole('super_admin')) {
             abort(403, 'Unauthorized action.');
        }

        $ticket = Ticket::findOrFail($ticketId);
        $this->ticketService->close($ticket);

        return back()->with('success', 'Ticket closed.');
    }
}
