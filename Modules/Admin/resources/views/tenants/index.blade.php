@extends('admin::layouts.master')

@section('page-title', __('admin::admin.tenants.title'))

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.settings.index') }}#plans" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-patch-check me-1"></i> {{ __('admin::admin.subscriptions.plans_pricing') }}
        </a>
        <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-plus-lg me-1"></i> {{ __('admin::admin.tenants.add_new') }}
        </a>
    </div>
@endsection

@section('content')

    <!-- Integrated Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-extrabold mb-1" style="letter-spacing: -0.5px; color: var(--text-main);">{{ __('admin::admin.tenants.title') }}</h3>
            <p class="text-muted small mb-0">{{ __('admin::admin.tenants.subtitle') ?? 'Manage your educational network and subscriptions.' }}</p>
        </div>
        @yield('page-actions')
    </div>

    <!-- Bento Grid Row -->
    <div class="row g-3 mb-4">
        <!-- Wide Bento: Network Summary -->
        <div class="col-md-6 col-lg-8">
            <div class="card h-100 border-0 p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-primary bg-opacity-20 text-primary mb-3 px-3 py-2 rounded-pill small fw-bold" style="font-size: 0.65rem;">NETWORK STATUS</span>
                        <h4 class="text-white fw-bold mb-1">{{ __('admin::admin.dashboard.stats.total_students') }}</h4>
                        <div class="d-flex align-items-baseline gap-2">
                            <h1 class="text-white display-5 fw-extrabold mb-0">{{ number_format($stats['total_students']) }}</h1>
                            <span class="text-success small fw-bold"><i class="bi bi-arrow-up"></i> 12%</span>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-mortarboard fs-3 text-white"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-top border-white border-opacity-10 d-flex gap-4">
                    <div>
                        <div class="text-white text-opacity-50 x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.tenants.stats.active') }}</div>
                        <div class="text-white fw-bold">{{ $stats['active_subscriptions'] }}</div>
                    </div>
                    <div>
                        <div class="text-white text-opacity-50 x-small fw-bold text-uppercase mb-1">Growth Index</div>
                        <div class="text-white fw-bold">+2.4x</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Small Bento: Expiring Soon -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 p-4 border-0" style="background-color: #fef3c7 !important; border: 1px solid #fde68a !important;">
                <div class="mb-auto">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                         <div class="bg-warning bg-opacity-20 p-2 rounded-3">
                            <i class="bi bi-hourglass-split text-warning"></i>
                        </div>
                        <span class="text-warning-emphasis small fw-bold text-uppercase" style="font-size: 0.6rem;">{{ __('admin::admin.dashboard.stats.expiring_soon') }}</span>
                    </div>
                    <h2 class="fw-extrabold mb-0" style="color: #92400e;">{{ $stats['expiring_soon'] }}</h2>
                    <p class="text-warning-emphasis small mt-1 opacity-75">Centers need renewal action</p>
                </div>
                <a href="?subscription_status=expired" class="btn btn-warning btn-sm w-100 rounded-pill mt-3 py-2 fw-bold" style="background-color: #f59e0b; color: white; border: none;">{{ __('admin::admin.tenants.filters.filter') }}</a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="col-md-3">
            <div class="card p-4">
                <div class="text-muted x-small fw-bold text-uppercase mb-2">{{ __('admin::admin.tenants.stats.total') }}</div>
                <h3 class="fw-extrabold mb-0">{{ $stats['total_count'] }}</h3>
                <div class="small text-success fw-bold mt-1"><i class="bi bi-check-circle"></i> Live</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4">
                <div class="text-muted x-small fw-bold text-uppercase mb-2">Service Health</div>
                <h3 class="fw-extrabold mb-0">99.9%</h3>
                <div class="small text-muted mt-1">Uptime Optimized</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 bg-light border-dashed">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">System Activity</h6>
                        <p class="text-muted small mb-0">Review real-time operation logs</p>
                    </div>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-dark btn-sm rounded-pill px-3">View Logs</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bento Table View -->
    <div class="card border-0 p-0 overflow-hidden shadow-sm">
        <div class="card-header bg-white border-0 p-4 pb-0">
            <form action="{{ route('admin.tenants.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="{{ __('admin::admin.tenants.filters.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm bg-light border-0" onchange="this.form.submit()">
                        <option value="">{{ __('admin::admin.tenants.filters.all_statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin::admin.tenants.filters.active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin::admin.tenants.filters.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">{{ __('admin::admin.tenants.filters.filter') }}</button>
                </div>
            </form>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">{{ __('admin::admin.tenants.table.center_admin') }}</th>
                            <th class="py-3">{{ __('admin::admin.subscriptions.title') }}</th>
                            <th class="py-3 text-center">{{ __('admin::admin.tenants.table.performance') }} / LTV</th>
                            <th class="py-3 text-center">{{ __('admin::admin.tenants.table.students') }}</th>
                            <th class="py-3 text-center">{{ __('admin::admin.tenants.table.status') }}</th>
                            <th class="py-3 text-end pe-4">{{ __('admin::admin.tenants.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                            @php
                                $studentsCount = $tenant->students_count;
                                $admin = $tenant->users->first();
                                $statusClass = $tenant->status == 'active' ? 'success' : 'danger';
                                $statusLabel = $tenant->status == 'active' ? __('admin::admin.tenants.table.active') : __('admin::admin.tenants.table.inactive');
                                
                                $subscription = $tenant->currentSubscription;
                                $isExpired = $subscription && $subscription->ends_at && $subscription->ends_at->isPast();
                                $planName = $subscription && $subscription->package ? $subscription->package->name : ($subscription ? $subscription->type_label : 'بدون اشتراك');
                            @endphp
                            <tr class="bg-hover-light-soft">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 42px; height: 42px; font-size: 0.95rem;">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="fw-bold text-dark text-decoration-none small" style="font-size: 0.9rem;">{{ $tenant->name }}</a>
                                            <div class="text-muted x-small font-monospace opacity-75">{{ $tenant->domain }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="fw-bold text-dark small" style="font-size: 0.8rem;">{{ $planName }}</span>
                                        @if($subscription && $subscription->ends_at)
                                            <span class="text-muted x-small">{{ $subscription->ends_at->format('Y-m-d') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="fw-extrabold text-success small">{{ number_format($tenant->ltv ?: 0, 0) }} <span class="fw-normal">ج.م</span></div>
                                    <div class="text-muted x-small opacity-50">LTV Optimized</div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark">{{ number_format($studentsCount) }}</span>
                                    <div class="text-muted x-small text-uppercase opacity-50" style="font-size: 0.6rem;">{{ __('admin::admin.tenants.table.student_unit') }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} x-small px-3 py-1" style="border: 1px solid currentColor; font-weight: 700;">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group gap-2">
                                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="btn btn-icon btn-sm btn-light border shadow-sm" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-icon btn-sm btn-light border shadow-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    {{ __('admin::admin.tenants.no_results.title') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tenants->hasPages())
            <div class="card-footer bg-white border-0 p-4 pt-0">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
@endsection
