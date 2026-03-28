@extends('admin::layouts.hope-master')

@section('title', __('admin.tickets.title'))

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('admin.tickets.title') }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.tickets.id') }}</th>
                            <th>{{ __('admin.tickets.tenant') }}</th>
                            <th>{{ __('admin.tickets.subject') }}</th>
                            <th>{{ __('admin.tickets.category') }}</th>
                            <th>{{ __('admin.tickets.priority') }}</th>
                            <th>{{ __('admin.tickets.status') }}</th>
                            <th>{{ __('admin.tickets.last_updated') }}</th>
                            <th>{{ __('admin.tickets.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->tenant->name }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ ucfirst($ticket->category) }}</td>
                            <td>
                                <span class="badge badge-{{ $ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'info') }}">
                                    {{ __('admin.tickets.priorities.' . $ticket->priority) ?? ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $ticket->status == 'open' ? 'success' : ($ticket->status == 'closed' ? 'secondary' : 'primary') }}">
                                    {{ __('admin.tickets.statuses.' . $ticket->status) ?? ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">{{ __('admin.tickets.view') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
