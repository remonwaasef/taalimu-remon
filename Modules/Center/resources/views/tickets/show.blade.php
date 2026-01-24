@extends('center::layouts.master')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h1>
        <span class="badge badge-{{ $ticket->status == 'open' ? 'success' : 'secondary' }}">{{ ucfirst($ticket->status) }}</span>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Messages -->
            @foreach($ticket->messages as $message)
                <div class="card shadow mb-4 {{ $message->user_id == Auth::id() ? 'border-left-primary' : 'border-left-warning' }}">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold {{ $message->user_id == Auth::id() ? 'text-primary' : 'text-warning' }}">
                            {{ $message->user->name }}
                        </h6>
                        <small class="text-muted">{{ $message->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                    <div class="card-body">
                        {{ $message->message }}
                    </div>
                </div>
            @endforeach

            <!-- Reply Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reply</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('center.tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="message" class="form-control" rows="3" required placeholder="Type your reply..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Category:</strong> {{ ucfirst($ticket->category) }}</p>
                    <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                    <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
