@extends('admin::layouts.master')

@section('title', __('admin.sidebar.cookie_reports'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📊 {{ __('admin.consent_report.title') }}</h2>
        <a href="{{ route('consent.export') }}" class="btn btn-primary">
            <i class="bi bi-download"></i> {{ __('admin.consent_report.export_csv') }}
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">{{ __('admin.consent_report.stats.total') }}</h6>
                    <h2 class="fw-bold text-primary mb-0">{{ $stats['total_consents'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">{{ __('admin.consent_report.stats.analytics') }}</h6>
                    <h2 class="fw-bold text-success mb-0">{{ $stats['analytics_accepted'] }}</h2>
                    <small class="text-muted">
                        {{ __('admin.consent_report.stats.of_total', ['percent' => $stats['total_consents'] > 0 ? round(($stats['analytics_accepted'] / $stats['total_consents']) * 100) : 0]) }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">{{ __('admin.consent_report.stats.marketing') }}</h6>
                    <h2 class="fw-bold text-info mb-0">{{ $stats['marketing_accepted'] }}</h2>
                    <small class="text-muted">
                        {{ __('admin.consent_report.stats.of_total', ['percent' => $stats['total_consents'] > 0 ? round(($stats['marketing_accepted'] / $stats['total_consents']) * 100) : 0]) }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">{{ __('admin.consent_report.stats.today') }}</h6>
                    <h2 class="fw-bold text-warning mb-0">{{ $stats['today_consents'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Consents Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">{{ __('admin.consent_report.recent_title') }}</h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-end">ID</th>
                            <th class="text-end">{{ __('admin.consent_report.table.user') }}</th>
                            <th class="text-end">{{ __('admin.consent_report.table.ip') }}</th>
                            <th class="text-center">{{ __('admin.consent_report.table.analytics') }}</th>
                            <th class="text-center">{{ __('admin.consent_report.table.marketing') }}</th>
                            <th class="text-end">{{ __('admin.consent_report.table.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_consents as $consent)
                        <tr>
                            <td class="text-end text-muted">#{{ $consent->id }}</td>
                            <td class="text-end">
                                @if($consent->user_id)
                                    <span class="badge bg-primary">{{ __('admin.consent_report.table.user_unit', ['id' => $consent->user_id]) }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('admin.consent_report.table.guest') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <code class="text-muted">{{ $consent->ip_address }}</code>
                            </td>
                            <td class="text-center">
                                @if($consent->analytics_consent)
                                    <span class="badge bg-success">✓ {{ __('admin.consent_report.table.yes') }}</span>
                                @else
                                    <span class="badge bg-danger">✗ {{ __('admin.consent_report.table.no') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($consent->marketing_consent)
                                    <span class="badge bg-success">✓ {{ __('admin.consent_report.table.yes') }}</span>
                                @else
                                    <span class="badge bg-danger">✗ {{ __('admin.consent_report.table.no') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div>{{ \Carbon\Carbon::parse($consent->created_at)->format('Y-m-d H:i') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($consent->created_at)->diffForHumans() }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h5>{{ __('admin.consent_report.no_results.title') }}</h5>
                                    <p class="mb-0">{{ __('admin.consent_report.no_results.description') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
