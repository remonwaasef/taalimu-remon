@extends('admin::layouts.master')

@section('page-title', __('admin::admin.dashboard.title'))
@section('page-subtitle', date('Y-m-d'))

@section('content')

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.total_centers') }}</h6>
                         <h3 class="fw-bold mb-0">{{ $totalTenants }}</h3>
                    </div>
                    <a href="{{ route('admin.tenants.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.active_centers') }}</h6>
                         <h3 class="fw-bold mb-0">{{ $activeTenants }}</h3>
                    </div>
                    <a href="{{ route('admin.tenants.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.expiring_soon') }}</h6>
                         <h3 class="fw-bold mb-0">{{ $expiringSoon }}</h3>
                    </div>
                    <a href="{{ route('admin.subscriptions.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.total_students') }}</h6>
                         <h3 class="fw-bold mb-0">{{ $totalStudents }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue and Support Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.total_revenue') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalRevenue, 2) }} <small class="fs-6 text-muted">{{ __('admin::admin.egp') ?? 'ج.م' }}</small></h3>
                    </div>
                    <!-- Assuming revenue details might be in subscriptions for now -->
                     <a href="{{ route('admin.subscriptions.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.this_month_revenue') }}</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($thisMonthRevenue, 2) }} <small class="fs-6 text-muted">{{ __('admin::admin.egp') ?? 'ج.م' }}</small></h3>
                    </div>
                     <a href="{{ route('admin.subscriptions.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning me-3">
                        <i class="bi bi-ticket-perforated fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.open_tickets') }}</h6>
                         <h3 class="fw-bold mb-0">{{ $openTickets }}</h3>
                    </div>
                    <a href="{{ route('admin.tickets.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-circle text-secondary me-3">
                        <i class="bi bi-life-preserver fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-2">{{ __('admin::admin.dashboard.stats.total_tickets') }}</h6>
                         <h3 class="fw-bold mb-0">{{ $totalTickets }}</h3>
                    </div>
                    <a href="{{ route('admin.tickets.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscription Analytics -->
    <div class="mb-5">
        <h5 class="fw-bold mb-4 text-dark d-flex align-items-center">
            <i class="bi bi-pie-chart-fill me-2 text-primary"></i>
            {{ __('admin::admin.dashboard.subscription_analytics') ?? 'تحليل باقات الاشتراك' }}
        </h5>
        <div class="row g-4">
            @foreach($planAnalytics as $plan)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                    {{ $plan['name'] }}
                                </span>
                                @if($plan['badge'])
                                    <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size: 0.7rem;">
                                        {{ $plan['badge'] }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="row g-0 align-items-center">
                                <div class="col-6 border-end">
                                    <div class="px-2">
                                        <div class="text-muted small mb-1">{{ __('admin::admin.dashboard.centers') ?? 'المراكز' }}</div>
                                        <div class="h4 fw-bold mb-0 text-dark">{{ $plan['centers_count'] }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="px-2 text-end">
                                        <div class="text-muted small mb-1">{{ __('admin::admin.dashboard.net_profits') ?? 'صافي الأرباح' }}</div>
                                        <div class="h4 fw-bold mb-0 text-success">
                                            {{ number_format($plan['total_profits'], 0) }}
                                            <span class="small fw-normal text-muted" style="font-size: 0.7rem;">{{ __('admin::admin.egp') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top">
                                <div class="progress" style="height: 6px;">
                                    @php
                                        $percentage = $totalTenants > 0 ? ($plan['centers_count'] / $totalTenants) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">{{ __('admin::admin.dashboard.acquisition_rate') ?? 'نسبة الاستحواذ' }}</small>
                                    <small class="fw-bold">{{ number_format($percentage, 1) }}%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Tenants -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">{{ __('admin::admin.dashboard.recent_tenants') }}</h5>
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-link">{{ __('admin::admin.view_all') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tenants.table.name') ?? 'اسم المركز' }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tenants.table.domain') ?? 'النطاق' }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tenants.table.joined_on') ?? 'تاريخ الانضمام' }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tenants.table.status') ?? 'الحالة' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTenants as $tenant)
                        <tr>
                            <td class="px-4 position-relative">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="fw-bold text-decoration-none text-dark stretched-link">{{ $tenant->name }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 text-muted">{{ $tenant->domain }}</td>
                            <td class="px-4 text-muted">{{ $tenant->created_at->format('Y-m-d') }}</td>
                            <td class="px-4">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $tenant->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                             <td colspan="4" class="text-center py-5 text-muted">{{ __('admin::admin.dashboard.no_tenants') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Support Tickets -->
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">{{ __('admin::admin.dashboard.recent_tickets') }}</h5>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-link">{{ __('admin::admin.view_all') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tickets.subject') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tickets.user') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tickets.center') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tickets.date') }}</th>
                        <th class="px-4 py-3 border-0">{{ __('admin::admin.tickets.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTickets as $ticket)
                        <tr>
                            <td class="px-4 fw-bold position-relative">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-decoration-none text-dark stretched-link">{{ $ticket->subject }}</a>
                            </td>
                            <td class="px-4">{{ $ticket->user ? $ticket->user->name : __('admin::admin.tenants.table.not_specified') }}</td>
                            <td class="px-4 text-muted">{{ $ticket->tenant ? $ticket->tenant->name : __('admin::admin.sidebar.admin') }}</td>
                            <td class="px-4 text-muted">{{ $ticket->created_at->format('Y-m-d') }}</td>
                            <td class="px-4">
                                @if($ticket->status == 'open')
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('admin::admin.dashboard.ticket_open') ?? 'مفتوحة' }}</span>
                                @elseif($ticket->status == 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">{{ __('admin::admin.dashboard.ticket_pending') ?? 'قيد الانتظار' }}</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('admin::admin.dashboard.ticket_closed') ?? 'مغلقة' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                             <td colspan="5" class="text-center py-5 text-muted">{{ __('admin::admin.dashboard.no_tickets') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
