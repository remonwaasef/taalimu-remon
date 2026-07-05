<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticketService;

    protected $telegram;

    public function __construct(\App\Services\TicketService $ticketService, \App\Services\TelegramService $telegram)
    {
        $this->ticketService = $ticketService;
        $this->telegram = $telegram;
    }

    public function index()
    {
        $this->authorize('viewAny', Ticket::class);
        $tenant = app('tenant');
        $tickets = Ticket::with('user')
            ->latest()
            ->paginate(10);

        return view('center::tickets.index', compact('tickets', 'tenant'));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);
        $tenant = app('tenant');

        return view('center::tickets.create', compact('tenant'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|string',
            'message' => 'required|string',
        ]);

        $tenant = app('tenant');

        $ticket = $this->ticketService->createTicket(array_merge($validated, [
            'tenant_id' => $tenant->id,
        ]));

        // Notify Admin
        $this->telegram->sendTicketAlert($tenant, $ticket, true);

        return redirect()->route('center.tickets.show', ['tenant' => $tenant->domain, 'ticket' => $ticket->id])
            ->with('success', __('center::messages.msg_089'));
    }

    public function show($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $this->authorize('view', $ticket);

        $tenant = app('tenant');
        $ticket->load('messages.user');

        return view('center::tickets.show', compact('ticket', 'tenant'));
    }

    public function reply(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $this->authorize('update', $ticket);

        $request->validate([
            'message' => 'required|string',
        ]);

        $this->ticketService->reply($ticket, $request->message);

        // Notify Admin
        $tenant = app('tenant');
        $this->telegram->sendTicketAlert($tenant, $ticket, false);

        return back()->with('success', __('center::messages.msg_090'));
    }
}
