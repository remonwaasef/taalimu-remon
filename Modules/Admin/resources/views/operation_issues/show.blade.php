@extends('admin::layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.sidebar.dashboard') ?? 'Dashboard' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.operation-issues.index') }}">{{ __('admin.operation_issues.title') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ substr($issue->uuid, 0, 8) }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-xs text-primary font-weight-bold text-uppercase mb-1">
                {{ __('admin.operation_issues.messages.' . $issue->exception_class) != 'admin.operation_issues.messages.' . $issue->exception_class 
                    ? __('admin.operation_issues.messages.' . $issue->exception_class) 
                    : ($issue->exception_class ?: __('admin.operation_issues.history.system')) }}
            </div>
            <h1 class="h3 text-gray-800 mb-2">{{ $issue->message }}</h1>
            <div class="d-flex align-items-center">
                <span class="badge badge-{{ $issue->severity == 'critical' ? 'danger' : ($issue->severity == 'high' ? 'warning' : 'info') }} mr-2">
                    {{ __('admin.operation_issues.severities.' . $issue->severity) }}
                </span>
                <span class="text-muted mr-3"><i class="fas fa-clock mr-1"></i> {{ $issue->created_at->format('Y-m-d H:i:s') }}</span>
                <span class="text-muted"><i class="fas fa-building mr-1"></i> {{ $issue->tenant->name ?? 'N/A' }}</span>
            </div>
        </div>
        
        <!-- Status Actions -->
        <div>
            <div class="dropdown d-inline-block">
                <button class="btn btn-{{ $issue->status == 'resolved' ? 'success' : 'secondary' }} dropdown-toggle" type="button" id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('admin.operation_issues.statuses.' . $issue->status) }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="statusDropdown">
                    <form action="{{ route('admin.operation-issues.update-status', $issue->uuid) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="dropdown-item" type="submit" name="status" value="in_progress">{{ __('admin.operation_issues.statuses.in_progress') }}</button>
                        <button class="dropdown-item" type="submit" name="status" value="resolved">{{ __('admin.operation_issues.statuses.resolved') }}</button>
                        <button class="dropdown-item" type="submit" name="status" value="closed">{{ __('admin.operation_issues.statuses.closed') }}</button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" type="submit" name="status" value="wont_fix">{{ __('admin.operation_issues.statuses.wont_fix') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Issue Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operation_issues.details.title') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <th width="150" class="bg-light">{{ __('admin.operation_issues.details.action') }}</th>
                                    <td>
                                        <p class="mb-0">
                                            {{ __('admin.operation_issues.actions_map.' . $issue->action) != 'admin.operation_issues.actions_map.' . $issue->action 
                                                ? __('admin.operation_issues.actions_map.' . $issue->action) 
                                                : $issue->action }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('admin.operation_issues.details.url') }}</th>
                                    <td><a href="{{ $issue->url }}" target="_blank">{{ $issue->url }}</a></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('admin.operation_issues.details.method') }}</th>
                                    <td><span class="badge badge-secondary">{{ $issue->method }}</span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('admin.operation_issues.details.user') }}</th>
                                    <td>{{ $issue->user->name ?? __('admin.operation_issues.details.guest') }} ({{ $issue->user->email ?? '-' }})</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">{{ __('admin.operation_issues.details.ip_agent') }}</th>
                                    <td>
                                        <div>{{ $issue->ip_address }}</div>
                                        <small class="text-muted">{{ $issue->user_agent }}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6 class="font-weight-bold">{{ __('admin.operation_issues.details.error_message') }}</h6>
                        <div class="alert alert-danger font-monospace">
                            {{ $issue->message }}
                        </div>
                    </div>

                    @if($issue->stack_trace)
                    <div class="mt-4">
                        <h6 class="font-weight-bold">{{ __('admin.operation_issues.details.stack_trace') }}</h6>
                        <div class="card bg-dark text-white">
                            <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                                <pre class="text-white small m-0"><code>{{ $issue->stack_trace }}</code></pre>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($issue->payload)
                    <div class="mt-4">
                        <h6 class="font-weight-bold">{{ __('admin.operation_issues.details.payload') }}</h6>
                        <pre class="bg-light p-3 border rounded"><code>{{ json_encode($issue->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operation_issues.history.title') }}</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($issue->timeline as $activity)
                        <div class="pb-3 mb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="mr-2 h5">{{ $activity->type_icon }}</span>
                                    <span class="font-weight-bold">{{ $activity->user->name ?? __('admin.operation_issues.history.system') }}</span>
                                    <span class="text-muted px-1">{{ $activity->description }}</span>
                                </div>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            @if($activity->comment && $activity->type == 'commented')
                            <div class="mt-2 bg-light p-2 rounded ml-4">
                                {{ $activity->comment }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Add Comment -->
                    <form action="{{ route('admin.operation-issues.comments', $issue->uuid) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" class="form-control" rows="3" placeholder="{{ __('admin.operation_issues.history.placeholder') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('admin.operation_issues.history.button') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Assignment Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operation_issues.assignment.title') }}</h6>
                </div>
                <div class="card-body">
                    @if($issue->assignee)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                            {{ substr($issue->assignee->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-weight-bold">{{ $issue->assignee->name }}</div>
                            <small class="text-muted">{{ __('admin.operation_issues.assignment.assigned') }}</small>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-light text-center">{{ __('admin.operation_issues.assignment.unassigned') }}</div>
                    @endif

                    <form action="{{ route('admin.operation-issues.assign', $issue->uuid) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <select name="assigned_to" class="form-control">
                                <option value="">{{ __('admin.operation_issues.assignment.select_admin') }}</option>
                                @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $issue->assigned_to == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-block btn-sm">{{ __('admin.operation_issues.assignment.button') }}</button>
                    </form>
                </div>
            </div>

            <!-- SLA Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operation_issues.sla.title') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('admin.operation_issues.sla.time_open') }}</small>
                        <strong class="h5">{{ $issue->created_at->diffForHumans(null, true) }}</strong>
                    </div>
                    
                    @if($issue->resolved_at)
                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('admin.operation_issues.sla.resolution_time') }}</small>
                        <strong>{{ $issue->resolution_time_minutes }} {{ __('admin.operation_issues.sla.mins') }}</strong>
                    </div>
                    @endif

                    @if($issue->isOverdue())
                    <div class="alert alert-danger m-0 p-2 text-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ __('admin.operation_issues.sla.breached') }}
                    </div>
                    @else
                    <div class="alert alert-success m-0 p-2 text-center">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('admin.operation_issues.sla.within') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
