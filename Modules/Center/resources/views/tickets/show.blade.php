@extends('center::layouts.master')

@section('title', 'Ticket #' . $ticket->id)

@push('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-ticket-alt mr-2 text-primary"></i>
            Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
        </h1>
        <span class="badge badge-pill px-3 py-2 badge-{{ $ticket->status == 'open' ? 'success' : 'secondary' }}">
            {{ strtoupper($ticket->status) }}
        </span>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="chat-container mb-4 shadow-sm">
                @foreach($ticket->messages as $message)
                    @php $isMe = $message->user_id == Auth::id(); @endphp
                    <div class="message {{ $isMe ? 'sent' : 'received' }}">
                        <div class="message-info">
                            {{ $message->user->name }}
                            @if($message->user->role == 'super_admin')
                                <span class="badge badge-danger badge-role">Support</span>
                            @endif
                        </div>
                        <div class="message-bubble shadow-sm">
                            {!! nl2br(e($message->message)) !!}
                            <div class="message-time">
                                {{ $message->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($ticket->status !== 'closed')
            <div class="chat-reply-area shadow-sm mb-4">
                <form action="{{ route('center.tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <textarea name="message" class="form-control" rows="3" required placeholder="Describe your issue or follow up..."></textarea>
                    <div class="chat-reply-footer">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-paper-plane mr-1"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold">Category</small>
                        <div class="h6 font-weight-bold text-gray-800">{{ ucfirst($ticket->category) }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold">Priority</small>
                        <div>
                            <span class="badge badge-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'info') }}">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-0">
                        <small class="text-uppercase text-muted font-weight-bold">Created On</small>
                        <div class="text-gray-700">{{ $ticket->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
