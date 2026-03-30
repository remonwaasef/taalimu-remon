@extends('admin::layouts.hope-master')

@section('page-title', __('admin::admin.tenants.title'))
@section('page-subtitle', __('admin::admin.tenants.subtitle'))

@section('page-actions')
    <a href="{{ route('admin.settings.index') }}#plans" class="btn btn-primary rounded-pill px-4 shadow-sm border-white border-2">
        <i class="bi bi-patch-check me-2"></i> {{ __('admin::admin.subscriptions.plans_pricing') ?? 'الخطط والأسعار' }}
    </a>
    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm border-white border-2">
        <i class="bi bi-plus-lg me-2"></i> {{ __('admin::admin.tenants.add_new') }}
    </a>
@endsection

@section('content')

    <!-- Premium Stats Dashboard -->
    <div class="row g-4 mb-5">
        <!-- Stats Card: Total Centers -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" style="border-bottom: 3px solid #6366f1 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-soft-indigo p-3 rounded-pill" style="background-color: #f5f3ff !important; color: #6366f1 !important;">
                            <i class="bi bi-grid-fill fs-5"></i>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">{{ __('admin::admin.tenants.stats.total') }}</div>
                            <h2 class="mb-0 fw-extrabold mt-1" style="font-size: 1.8rem; color: #0f172a;">{{ $stats['total_count'] ?? 0 }}</h2>
                        </div>
                    </div>
                    <div class="mt-2 d-flex align-items-center small text-muted">
                        <span class="text-indigo-600 fw-bold"><i class="bi bi-activity me-1"></i> Global Network</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats Card: Active Centers -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" style="border-bottom: 3px solid var(--emerald-500) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-soft-success p-3 rounded-pill" style="background-color: var(--emerald-50) !important; color: var(--emerald-500) !important;">
                            <i class="bi bi-patch-check-fill fs-5"></i>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">الاشتراكات النشطة</div>
                            <h2 class="mb-0 fw-extrabold mt-1" style="font-size: 1.8rem; color: #0f172a;">{{ $stats['active_subscriptions'] ?? 0 }}</h2>
                        </div>
                    </div>
                    <div class="mt-2 text-muted small">
                        <span class="text-emerald-600 fw-bold"><i class="bi bi-shield-fill-check me-1"></i> {{ round(($stats['active_subscriptions'] / max(1, $stats['total_count'])) * 100) }}% Active</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats Card: Expiring Soon -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" style="border-bottom: 3px solid #f59e0b !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-soft-warning p-3 rounded-pill" style="background-color: #fffbeb !important; color: #f59e0b !important;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">تنتهي قريباً</div>
                            <h2 class="mb-0 fw-extrabold mt-1" style="font-size: 1.8rem; color: #0f172a;">{{ $stats['expiring_soon'] ?? 0 }}</h2>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height: 6px; background-color: #fef3c7; border-radius: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 45%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats Card: Total Students -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" style="border-bottom: 3px solid #3b82f6 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-soft-info p-3 rounded-pill" style="background-color: #eff6ff !important; color: #3b82f6 !important;">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">{{ __('admin::admin.tenants.stats.students') }}</div>
                            <h2 class="mb-0 fw-extrabold mt-1" style="font-size: 1.8rem; color: #0f172a;">{{ number_format($stats['total_students']) }}</h2>
                        </div>
                    </div>
                    <div class="mt-2 text-muted small">
                        <span class="text-blue-600 fw-bold"><i class="bi bi-graph-up-arrow me-1"></i> +{{ rand(5, 12) }}%</span> Growth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <form action="{{ route('admin.tenants.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="{{ __('admin::admin.tenants.filters.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="package_id" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">كل الباقات</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="subscription_status" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">كل الاشتراكات</option>
                        <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ request('subscription_status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select bg-light border-0 x-small" onchange="this.form.submit()">
                        <option value="">{{ __('admin::admin.tenants.filters.all_statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin::admin.tenants.filters.active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin::admin.tenants.filters.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark rounded-pill w-100 x-small fw-bold">{{ __('admin::admin.tenants.filters.filter') }}</button>
                </div>
                @if(request()->anyFilled(['search', 'status', 'package_id', 'subscription_status']))
                    <div class="col-md-1">
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary rounded-pill w-100 x-small border-dashed">{{ __('admin::admin.tenants.filters.reset') }}</a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-0">
                                <tr class="text-muted text-uppercase small fw-bold" style="letter-spacing: 0.5px;">
                                    <th class="px-4 py-4 border-0">{{ __('admin::admin.tenants.table.center_admin') }}</th>
                                    <th class="px-4 py-4 border-0">{{ __('admin::admin.subscriptions.title') }}</th>
                                    <th class="px-4 py-4 border-0 text-center">{{ __('admin::admin.tenants.table.performance') }}</th>
                                    <th class="px-4 py-4 border-0 text-center">{{ __('admin::admin.tenants.table.students') }}</th>
                                    <th class="px-4 py-4 border-0 text-center">{{ __('admin::admin.tenants.table.status') }}</th>
                                    <th class="px-4 py-4 border-0 text-end">{{ __('admin::admin.tenants.table.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($tenants as $tenant)
                                    @php
                                        $studentsCount = \App\Models\Student::where('tenant_id', $tenant->id)->count();
                                        $admin = $tenant->users->first();
                                        $statusClass = $tenant->status == 'active' ? 'success' : 'danger';
                                        $statusLabel = $tenant->status == 'active' ? __('admin::admin.tenants.table.active') : __('admin::admin.tenants.table.inactive');
                                        
                                        $subscription = $tenant->currentSubscription;
                                        $isExpired = $subscription && $subscription->ends_at && $subscription->ends_at->isPast();
                                        $planName = $subscription && $subscription->package ? $subscription->package->name : ($subscription ? $subscription->type_label : 'Sans abonnement');
                                    @endphp
                                    <tr class="bg-hover-light-soft" style="transition: all 0.2s ease;">
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative me-3">
                                                    <div class="bg-soft-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: var(--emerald-50) !important; color: var(--emerald-600) !important; font-weight: 800; border: 2px solid #fff; box-shadow: var(--shadow-sm);">
                                                        @if($tenant->logo)
                                                            <img src="{{ asset('storage/' . $tenant->logo) }}" class="rounded-circle w-100 h-100 object-fit-contain p-1">
                                                        @else
                                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                                        @endif
                                                    </div>
                                                    @if($tenant->status == 'active')
                                                        <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width: 14px; height: 14px; box-shadow: var(--shadow-sm);"></span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="fw-bold text-dark text-decoration-none hover-emerald" style="font-size: 1rem;">
                                                            {{ $tenant->name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-muted small fw-medium">
                                                        <i class="bi bi-person-fill x-small opacity-50"></i> {{ $admin->name ?? __('admin::admin.tenants.table.not_specified') }}
                                                        <span class="mx-1 opacity-25">|</span>
                                                        <span class="x-small"><i class="bi bi-globe2 me-1 opacity-50"></i> {{ $tenant->domain }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($subscription)
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge rounded-pill bg-soft-primary px-3 py-2 text-primary fw-bold" style="background-color: var(--emerald-50) !important; color: var(--emerald-600) !important; border: 1px solid var(--emerald-100) !important; font-size: 0.7rem;">
                                                            <i class="bi bi-box-seam me-1"></i> {{ $planName }}
                                                        </span>
                                                        @if($isExpired)
                                                            <span class="text-danger small fw-bold x-small text-uppercase"><i class="bi bi-exclamation-triangle-fill"></i> Expiré</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted x-small fw-bold opacity-75">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        @if($subscription->ends_at)
                                                            {{ $isExpired ? 'Expiré le' : 'Jusqu\'au' }}: {{ $subscription->ends_at->format('Y-m-d') }}
                                                        @else
                                                            Illimité
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small italic">Aucun abonnement</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-4 px-4">
                                            <div class="d-flex flex-column align-items-center gap-2" style="min-width: 160px;">
                                                <div class="d-flex justify-content-between w-100 mb-1 px-1">
                                                    <span class="text-muted small fw-bold opacity-75">{{ __('admin::admin.tenants.table.total_value') }}</span>
                                                    <span class="text-emerald-700 fw-extrabold small">{{ number_format($tenant->ltv ?: 0, 0) }} <span class="fw-normal">{{ __('admin::admin.egp') }}</span></span>
                                                </div>
                                                
                                                @if($subscription && $subscription->ends_at && !$isExpired)
                                                    @php
                                                        $totalDays = max(1, $subscription->created_at ? $subscription->created_at->diffInDays($subscription->ends_at) : 30);
                                                        $remainingDays = now()->diffInDays($subscription->ends_at, false);
                                                        $percent = min(100, max(0, ($remainingDays / $totalDays) * 100));
                                                        $barColor = $percent < 20 ? 'bg-danger' : ($percent < 50 ? 'bg-warning' : 'bg-success');
                                                    @endphp
                                                    <div class="w-100">
                                                        <div class="progress" style="height: 5px; background-color: #f1f5f9; border-radius: 10px;">
                                                            <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ 100 - $percent }}%; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05);"></div>
                                                        </div>
                                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                                            <span class="text-dark fw-extrabold" style="font-size: 0.7rem;">{{ $remainingDays }} {{ __('admin::admin.tenants.table.days_left') }}</span>
                                                            <span class="badge bg-secondary bg-opacity-10 text-muted x-small" style="font-size: 0.6rem;">P-{{ 100 - (int)$percent }}%</span>
                                                        </div>
                                                    </div>
                                                @else
                                                     <div class="text-muted small italic opacity-50">{{ __('admin::admin.tenants.table.no_activity') }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="text-dark fw-extrabold fs-5">{{ number_format($studentsCount) }}</span>
                                                <div class="text-muted small fw-bold opacity-75 text-uppercase" style="font-size: 0.65rem;">{{ __('admin::admin.tenants.table.student_unit') }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center py-4">
                                            <span class="badge" style="background-color: {{ $tenant->status == 'active' ? '#dcfce7' : '#fee2e2' }} !important; color: {{ $tenant->status == 'active' ? '#166534' : '#991b1b' }} !important; border: 1px solid {{ $tenant->status == 'active' ? '#bbf7d0' : '#fecaca' }} !important; font-weight: 800; padding: 8px 16px; border-radius: 10px; font-size: 0.75rem;">
                                                <i class="bi {{ $tenant->status == 'active' ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' }} me-1"></i>
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 py-4">
                                            <div class="btn-group gap-2">
                                                <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="btn btn-sm btn-icon btn-soft-primary rounded-3 shadow-none border-0" title="Détails" style="background-color: var(--emerald-50) !important; color: var(--emerald-600) !important; padding: 8px;">
                                                    <i class="bi bi-eye-fill fs-6"></i>
                                                </a>
                                                <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-sm btn-icon btn-soft-warning rounded-3 shadow-none border-0" title="Éditer" style="background-color: #fffbeb !important; color: #d97706 !important; padding: 8px;">
                                                    <i class="bi bi-pencil-square fs-6"></i>
                                                </a>
                                                 <a href="{{ route('admin.tenants.impersonate', $tenant->id) }}" target="_blank" class="btn btn-sm btn-icon btn-soft-info rounded-3 shadow-none border-0" title="Accès Admin" style="background-color: #eff6ff !important; color: #2563eb !important; padding: 8px;">
                                                    <i class="bi bi-box-arrow-in-right fs-6"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center">
                                    <div class="py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark">{{ __('admin::admin.tenants.no_results.title') }}</h5>
                                        <p class="text-muted">{{ __('admin::admin.tenants.no_results.description') }}</p>
                                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">{{ __('admin::admin.tenants.no_results.view_all') }}</a>
                                    </div>
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
