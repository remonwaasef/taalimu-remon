@extends('admin::layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Operation Issues Tracking</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Critical Issues</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Open Issues</div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Resolved Today</div>
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
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">SLA Breached</div>
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
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.operation-issues.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Severity</label>
                            <select name="severity" class="form-control">
                                <option value="">All Severities</option>
                                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title, message, ID...">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Issues List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Severity</th>
                            <th>Title</th>
                            <th>Tenant</th>
                            <th>Status</th>
                            <th>Occurrences</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $issue)
                        <tr>
                            <td>{{ substr($issue->uuid, 0, 8) }}...</td>
                            <td>
                                <span class="badge badge-pill badge-{{ $issue->severity == 'critical' ? 'danger' : ($issue->severity == 'high' ? 'warning' : 'info') }}">
                                    {{ ucfirst($issue->severity) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold text-truncate" style="max-width: 300px;">{{ $issue->title }}</div>
                                <small class="text-muted">{{ $issue->action }}</small>
                            </td>
                            <td>{{ $issue->tenant->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $issue->status == 'resolved' ? 'success' : 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $issue->occurrence_count }}</td>
                            <td>{{ $issue->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.operation-issues.show', $issue->uuid) }}" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No issues found.</td>
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
