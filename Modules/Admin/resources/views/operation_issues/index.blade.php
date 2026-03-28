@extends('admin::layouts.hope-master')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('admin.operation_issues.title') }}</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">{{ __('admin.operation_issues.stats.critical') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['critical'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('admin.operation_issues.stats.open') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('admin.operation_issues.stats.resolved_today') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved_today'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">{{ __('admin.operation_issues.stats.sla_breached') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sla_breached'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operation_issues.filters.title') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.operation-issues.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('admin.operation_issues.filters.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ __('admin.operation_issues.filters.all_statuses') }}</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>{{ __('admin.operation_issues.statuses.new') }}</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('admin.operation_issues.statuses.in_progress') }}</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>{{ __('admin.operation_issues.statuses.resolved') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ __('admin.operation_issues.filters.severity') }}</label>
                            <select name="severity" class="form-control">
                                <option value="">{{ __('admin.operation_issues.filters.all_severities') }}</option>
                                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>{{ __('admin.operation_issues.severities.critical') }}</option>
                                <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>{{ __('admin.operation_issues.severities.high') }}</option>
                                <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>{{ __('admin.operation_issues.severities.medium') }}</option>
                                <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>{{ __('admin.operation_issues.severities.low') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('admin.operation_issues.filters.search') }}</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('admin.operation_issues.filters.search_placeholder') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary w-100">{{ __('admin.operation_issues.filters.button') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operation_issues.list.title') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.operation_issues.list.id') }}</th>
                            <th>{{ __('admin.operation_issues.list.severity') }}</th>
                            <th>{{ __('admin.operation_issues.list.issue_title') }}</th>
                            <th>{{ __('admin.operation_issues.list.tenant') }}</th>
                            <th>{{ __('admin.operation_issues.list.status') }}</th>
                            <th>{{ __('admin.operation_issues.list.occurrences') }}</th>
                            <th>{{ __('admin.operation_issues.list.created') }}</th>
                            <th>{{ __('admin.operation_issues.list.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $issue)
                        <tr>
                            <td>{{ substr($issue->uuid, 0, 8) }}...</td>
                            <td>
                                <span class="badge {{ $issue->severity == 'critical' ? 'bg-danger text-white' : ($issue->severity == 'high' ? 'bg-warning text-dark' : 'bg-info text-white') }}">
                                    {{ __('admin.operation_issues.severities.' . $issue->severity) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold" style="font-size: 0.9rem;">
                                    {{ __('admin.operation_issues.messages.' . $issue->exception_class) != 'admin.operation_issues.messages.' . $issue->exception_class 
                                        ? __('admin.operation_issues.messages.' . $issue->exception_class) 
                                        : ($issue->exception_class ?: __('admin.operation_issues.history.system')) }}
                                </div>
                                <div class="text-muted small text-truncate" style="max-width: 350px;" title="{{ $issue->message }}">
                                    {{ $issue->message }}
                                </div>
                                <small class="badge badge-light border text-muted mt-1">
                                    {{ __('admin.operation_issues.actions_map.' . $issue->action) != 'admin.operation_issues.actions_map.' . $issue->action 
                                        ? __('admin.operation_issues.actions_map.' . $issue->action) 
                                        : $issue->action }}
                                </small>
                            </td>
                            <td>{{ $issue->tenant->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $issue->status == 'resolved' ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                    {{ __('admin.operation_issues.statuses.' . $issue->status) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $issue->occurrence_count }}</td>
                            <td>{{ $issue->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.operation-issues.show', $issue->uuid) }}" class="btn btn-sm btn-primary">
                                    {{ __('admin.operation_issues.list.view') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">{{ __('admin.operation_issues.list.no_issues') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $issues->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
