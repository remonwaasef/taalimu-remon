@extends('admin::layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">{{ __('admin.sidebar.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.operation-issues.index') }}" class="text-decoration-none text-muted">{{ __('admin.operation_issues.title') }}</a></li>
            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ substr($issue->uuid, 0, 8) }}</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <span class="badge {{ $issue->severity == 'critical' ? 'bg-danger' : ($issue->severity == 'high' ? 'bg-warning text-dark' : 'bg-info text-white') }} rounded-pill px-3 py-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ __('admin.operation_issues.severities.' . $issue->severity) }}
                </span>
                <span class="badge bg-light text-secondary border rounded-pill px-3 py-2">
                    <i class="fas fa-clock me-1"></i> {{ $issue->created_at->diffForHumans() }}
                </span>
            </div>
            <h1 class="h3 fw-bold text-dark mt-3 mb-1">
                {{ __('admin.operation_issues.messages.' . $issue->exception_class) != 'admin.operation_issues.messages.' . $issue->exception_class 
                    ? __('admin.operation_issues.messages.' . $issue->exception_class) 
                    : ($issue->exception_class ?: __('admin.operation_issues.history.system')) }}
            </h1>
            <p class="text-muted mb-0 font-monospace small"><i class="fas fa-code me-1"></i> {{ $issue->message }}</p>
        </div>
        
        <!-- Status Actions -->
        <div class="col-md-4 text-end">
            <div class="dropdown d-inline-block">
                <button class="btn btn-{{ $issue->status == 'resolved' ? 'success' : 'secondary' }} dropdown-toggle btn-lg shadow-sm" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-tasks me-2"></i>
                    {{ __('admin.operation_issues.statuses.' . $issue->status) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="statusDropdown">
                    <li><h6 class="dropdown-header">{{ __('admin.operation_issues.filters.status') }}</h6></li>
                    <form action="{{ route('admin.operation-issues.update-status', $issue->uuid) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <li><button class="dropdown-item d-flex align-items-center gap-2" type="submit" name="status" value="in_progress"><span class="badge bg-warning p-1 rounded-circle"> </span> {{ __('admin.operation_issues.statuses.in_progress') }}</button></li>
                        <li><button class="dropdown-item d-flex align-items-center gap-2" type="submit" name="status" value="resolved"><span class="badge bg-success p-1 rounded-circle"> </span> {{ __('admin.operation_issues.statuses.resolved') }}</button></li>
                        <li><button class="dropdown-item d-flex align-items-center gap-2" type="submit" name="status" value="closed"><span class="badge bg-secondary p-1 rounded-circle"> </span> {{ __('admin.operation_issues.statuses.closed') }}</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button class="dropdown-item d-flex align-items-center gap-2 text-muted" type="submit" name="status" value="wont_fix"><i class="fas fa-ban small"></i> {{ __('admin.operation_issues.statuses.wont_fix') }}</button></li>
                    </form>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Issue Context Card -->
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i> {{ __('admin.operation_issues.details.title') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <th width="200" class="bg-light ps-4 text-secondary">{{ __('admin.operation_issues.details.action') }}</th>
                                    <td class="pe-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-bold text-dark">
                                                {{ __('admin.operation_issues.actions_map.' . $issue->action) != 'admin.operation_issues.actions_map.' . $issue->action 
                                                    ? __('admin.operation_issues.actions_map.' . $issue->action) 
                                                    : $issue->action }}
                                            </span>
                                            <span class="badge bg-light text-muted border font-monospace">{{ $issue->method }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light ps-4 text-secondary">{{ __('admin.operation_issues.details.url') }}</th>
                                    <td class="pe-4"><a href="{{ $issue->url }}" target="_blank" class="text-decoration-none text-break">{{ $issue->url }} <i class="fas fa-external-link-alt small ms-1"></i></a></td>
                                </tr>
                                <tr>
                                    <th class="bg-light ps-4 text-secondary">{{ __('admin.operation_issues.details.user') }}</th>
                                    <td class="pe-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $issue->user->name ?? __('admin.operation_issues.details.guest') }}</div>
                                                <small class="text-muted">{{ $issue->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light ps-4 text-secondary">{{ __('admin.operation_issues.details.ip_agent') }}</th>
                                    <td class="pe-4">
                                        <div class="font-monospace mb-1">{{ $issue->ip_address }}</div>
                                        <small class="text-muted d-block text-truncate" style="max-width: 400px;" title="{{ $issue->user_agent }}">{{ $issue->user_agent }}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Technical Details Accordion -->
            <div class="accordion mb-4 shadow-sm rounded-4 overflow-hidden border-0" id="technicalAccordion">
                <!-- Stack Trace -->
                @if($issue->stack_trace)
                <div class="accordion-item border-0">
                    <h2 class="accordion-header" id="headingTrace">
                        <button class="accordion-button collapsed bg-white fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrace" aria-expanded="false" aria-controls="collapseTrace">
                            <i class="fas fa-bug me-2 text-danger"></i> {{ __('admin.operation_issues.details.stack_trace') }}
                        </button>
                    </h2>
                    <div id="collapseTrace" class="accordion-collapse collapse" aria-labelledby="headingTrace" data-bs-parent="#technicalAccordion">
                        <div class="accordion-body bg-dark p-0">
                            <div class="position-relative">
                                <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2" onclick="navigator.clipboard.writeText(this.nextElementSibling.innerText); this.innerHTML='Converted!'; setTimeout(() => this.innerHTML='Copy', 1000);">
                                    Copy
                                </button>
                                <pre class="text-white small m-0 p-3" style="max-height: 400px; overflow-y: auto;"><code>{{ $issue->stack_trace }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Payload -->
                @if($issue->payload)
                <div class="accordion-item border-0 border-top">
                    <h2 class="accordion-header" id="headingPayload">
                        <button class="accordion-button collapsed bg-white fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayload" aria-expanded="false" aria-controls="collapsePayload">
                            <i class="fas fa-database me-2 text-info"></i> {{ __('admin.operation_issues.details.payload') }}
                        </button>
                    </h2>
                    <div id="collapsePayload" class="accordion-collapse collapse" aria-labelledby="headingPayload" data-bs-parent="#technicalAccordion">
                        <div class="accordion-body bg-light p-3">
                            <pre class="m-0 border rounded p-3 bg-white"><code>{{ json_encode($issue->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Timeline & Comments -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-history me-2"></i> {{ __('admin.operation_issues.history.title') }}</h5>
                </div>
                <div class="card-body">
                    <div class="timeline position-relative ps-3 border-start ms-2">
                        @foreach($issue->timeline as $activity)
                        <div class="mb-4 position-relative ps-4">
                            <div class="position-absolute start-0 top-0 translate-middle-x bg-white p-1">
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    <small>{{ $activity->type_icon }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="fw-bold">{{ $activity->user->name ?? __('admin.operation_issues.history.system') }}</span>
                                    <span class="text-muted mx-1">{{ $activity->description }}</span>
                                </div>
                                <small class="text-muted" style="white-space: nowrap;">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            @if($activity->comment && $activity->type == 'commented')
                            <div class="mt-2 bg-light p-3 rounded-3 border">
                                {{ $activity->comment }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Add Comment -->
                    <form action="{{ route('admin.operation-issues.comments', $issue->uuid) }}" method="POST" class="mt-4 bg-light p-3 rounded-3">
                        @csrf
                        <div class="form-group mb-2">
                            <textarea name="comment" class="form-control border-0 bg-white" rows="2" placeholder="{{ __('admin.operation_issues.history.placeholder') }}"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('admin.operation_issues.history.button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Assignment & SLA Helper Card -->
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-gradient-primary text-white py-3 border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold"><i class="fas fa-shield-alt me-2"></i> {{ __('admin.operation_issues.assignment.title') }}</h6>
                </div>
                <div class="card-body">
                    <!-- Current Assignee -->
                    <div class="text-center mb-4">
                        @if($issue->assignee)
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm" style="width: 64px; height: 64px; font-size: 1.5rem;">
                                {{ substr($issue->assignee->name, 0, 1) }}
                            </div>
                            <h5 class="fw-bold mb-0">{{ $issue->assignee->name }}</h5>
                            <small class="text-muted">{{ __('admin.operation_issues.assignment.assigned') }}</small>
                        @else
                            <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 border border-dashed" style="width: 64px; height: 64px; font-size: 1.5rem;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="badge bg-secondary mb-2">{{ __('admin.operation_issues.assignment.unassigned') }}</div>
                        @endif
                    </div>

                    <!-- Assignment Form -->
                    <form action="{{ route('admin.operation-issues.assign', $issue->uuid) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <div class="input-group">
                            <select name="assigned_to" class="form-select bg-light border-0">
                                <option value="">{{ __('admin.operation_issues.assignment.select_admin') }}</option>
                                @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $issue->assigned_to == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">{{ __('admin.operation_issues.assignment.button') }}</button>
                        </div>
                    </form>

                    <hr class="my-4 op-2">

                    <!-- SLA Info -->
                    <div>
                        <h6 class="fw-bold text-muted text-uppercase small mb-3">{{ __('admin.operation_issues.sla.title') }}</h6>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">{{ __('admin.operation_issues.sla.time_open') }}</span>
                            <span class="fw-bold font-monospace">{{ $issue->created_at->diffForHumans(null, true) }}</span>
                        </div>

                        @if($issue->resolved_at)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">{{ __('admin.operation_issues.sla.resolution_time') }}</span>
                            <span class="fw-bold text-success">{{ $issue->resolution_time_minutes }} {{ __('admin.operation_issues.sla.mins') }}</span>
                        </div>
                        @endif

                        <div class="mt-3">
                            @if($issue->isOverdue())
                            <div class="alert alert-danger d-flex align-items-center m-0 p-2 rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-fire me-2"></i> 
                                <span class="small fw-bold">{{ __('admin.operation_issues.sla.breached') }}</span>
                            </div>
                            @else
                            <div class="alert alert-success d-flex align-items-center m-0 p-2 rounded-3 border-0 bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-shield me-2"></i> 
                                <span class="small fw-bold">{{ __('admin.operation_issues.sla.within') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Issue Summary Mini-Card -->
            <div class="card shadow-sm border-0 rounded-4 bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">{{ __('admin.operation_issues.list.tenant') }}</span>
                        <span class="fw-bold small">{{ $issue->tenant->name ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">{{ __('admin.operation_issues.list.occurrences') }}</span>
                        <span class="badge bg-secondary rounded-pill">{{ $issue->occurrence_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard-specific Styles */
.card { transition: all 0.2s; }
.accordion-button:not(.collapsed) { 
    background-color: var(--bs-light); 
    color: var(--bs-primary);
    box-shadow: none;
}
.accordion-button:focus { box-shadow: none; border-color: rgba(0,0,0,.125); }
.timeline:before {
    display: none; /* Disable default timeline line if any */
}
</style>
@endsection
