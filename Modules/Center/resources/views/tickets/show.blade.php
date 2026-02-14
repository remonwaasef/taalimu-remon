@extends('center::layouts.master')

@section('title', 'Ticket #' . $ticket->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
<style>
    /* Inline safety overrides */
    .chat-container { display: flex !important; flex-direction: column !important; }
    .message { display: flex !important; margin-bottom: 12px !important; }
</style>
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
            <div class="chat-container mb-4">
                @foreach($ticket->messages as $message)
                    @php 
                        $isMe = $message->user_id == Auth::id(); 
                        $initials = strtoupper(substr($message->user->name, 0, 1) . substr(explode(' ', $message->user->name)[1] ?? '', 0, 1));
                    @endphp
                    <div class="message-wrapper {{ $isMe ? 'sent' : 'received' }}">
                        <div class="chat-avatar">{{ $initials }}</div>
                        <div class="message-content">
                            <div class="message-bubble">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                            <div class="message-meta">
                                @if(!$isMe && $message->user->role == 'super_admin')
                                    <span class="badge-support">Support</span>
                                @endif
                                {{ $message->user->name }} • {{ $message->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($ticket->status !== 'closed')
            <div class="chat-reply-area mb-4 shadow-sm">
                <form action="{{ route('center.tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <textarea name="message" rows="2" required placeholder="Type your message..."></textarea>
                    <div class="reply-footer">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            Send Message <i class="fas fa-paper-plane ms-1"></i>
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
