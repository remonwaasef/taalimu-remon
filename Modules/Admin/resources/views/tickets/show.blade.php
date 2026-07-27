@extends('admin::layouts.app-next')

@section('title', 'Ticket #' . $ticket->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
<style>
    /* Inline safety overrides */
    .chat-container { display: flex !important; flex-direction: column !important; }
    .message { display: flex !important; margin-bottom: 12px !important; }
</style>
@endpush

@section('panel-content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-headset mr-2 text-primary"></i>
            Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge badge-pill px-3 py-2 badge-{{ $ticket->status == 'open' ? 'success' : 'secondary' }} mr-3">
                {{ strtoupper($ticket->status) }}
            </span>
            @if($ticket->status !== 'closed')
                <form action="{{ route('admin.tickets.close', $ticket->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                        <i class="fas fa-times-circle mr-1"></i> Close Ticket
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="chat-wrapper mb-4">
                @foreach($ticket->messages as $message)
                    @php 
                        $isMe = $message->user_id == Auth::id(); 
                        $senderName = $message->user->name;
                        $initials = mb_substr($senderName, 0, 1, 'UTF-8');
                        if (str_contains($senderName, ' ')) {
                            $parts = explode(' ', $senderName);
                            if (isset($parts[1])) {
                                $initials .= mb_substr($parts[1], 0, 1, 'UTF-8');
                            }
                        }
                    @endphp
                    <div class="chat-item {{ $isMe ? 'sent' : 'received' }}">
                        <div class="chat-avatar">{{ $initials }}</div>
                        <div class="chat-bubble">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                        <div class="chat-meta">
                            @if($message->user->role == 'super_admin')
                                <span class="support-label">Support</span>
                            @else
                                <span class="badge bg-light text-dark border me-1" style="font-size: 0.65rem;">{{ $message->user->role }}</span>
                            @endif
                            {{ $senderName }} • {{ $message->created_at->format('h:i A') }}
                        </div>
                    </div>
                @endforeach
            </div>

            @if($ticket->status !== 'closed')
            <div class="reply-well mb-4">
                <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <textarea name="message" rows="2" required placeholder="Type your message..."></textarea>
                    <div class="reply-btn-container">
                        <button type="submit" class="btn btn-primary px-4">
                            Send Reply <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Context Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold">Center / Tenant</small>
                        <div class="h6 font-weight-bold text-gray-800">{{ $ticket->tenant->name }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold">Requester</small>
                        <div class="text-gray-700">{{ $ticket->user->name }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold">Category</small>
                        <div class="text-gray-700">{{ ucfirst($ticket->category) }}</div>
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
