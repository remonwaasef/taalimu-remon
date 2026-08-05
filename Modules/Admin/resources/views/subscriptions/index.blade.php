@extends('admin::layouts.app-next')

@section('page-title', __('admin::admin.subscriptions.title'))

@section('panel-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ __('admin::admin.subscriptions.title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin::admin.subscriptions.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.settings.index') }}#plans" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-patch-check me-2"></i>
            {{ __('admin::admin.subscriptions.plans_pricing') ?? 'الخطط والأسعار' }}
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #4361EE !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.subscriptions.stats.total') }}</div>
                            <div class="h3 fw-bold mb-0">{{ $stats['total_count'] }}</div>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #10b981 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.subscriptions.stats.active') }}</div>
                            <div class="h3 fw-bold mb-0 text-success">{{ $stats['active_count'] }}</div>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-right: 4px solid #f59e0b !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.subscriptions.stats.expiring_soon') }}</div>
                            <div class="h3 fw-bold mb-0 text-warning">{{ $stats['expiring_soon'] }}</div>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-right: 4px solid #2E8B83 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted x-small fw-bold text-uppercase mb-1">{{ __('admin::admin.subscriptions.stats.revenue') }}</div>
                            <div class="h3 fw-bold mb-0 text-dark">{{ number_format($stats['total_revenue'], 2) }} <small class="text-muted small">{{ __('admin::admin.egp') }}</small></div>
                        </div>
                        <div class="icon-box bg-success text-white rounded-3 shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #2E8B83 0%, #34d399 100%);">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="card border-0 shadow-sm rounded-4" style="overflow: visible;">
        <div class="card-header bg-white border-0 p-4">
            <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="{{ __('admin::admin.subscriptions.filters.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-3 align-items-center h-100">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="status" value="active" class="form-check-input" id="activeOnly" {{ request('status') == 'active' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="activeOnly">{{ __('admin::admin.subscriptions.filters.active_only') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="status" value="expired" class="form-check-input" id="expiredOnly" {{ request('status') == 'expired' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="expiredOnly">{{ __('admin::admin.subscriptions.filters.expired_only') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100 x-small fw-bold">{{ __('admin::admin.tenants.filters.filter') }}</button>
                </div>
                @if(request()->anyFilled(['search', 'status']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary rounded-pill w-100 x-small border-dashed">{{ __('admin::admin.tenants.filters.reset') }}</a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body p-0" style="overflow: visible !important;">
            <div class="table-responsive" style="overflow: visible !important;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small text-uppercase">
                             <th class="px-4 py-3 border-0">{{ __('admin::admin.subscriptions.table.center_contact') }}</th>
                            <th class="px-4 py-3 border-0">{{ __('admin::admin.subscriptions.table.plan_billing') }}</th>
                            <th class="px-4 py-3 border-0 text-center">{{ __('admin::admin.subscriptions.table.start_renewal') }}</th>
                            <th class="px-4 py-3 border-0 text-center">{{ __('admin::admin.subscriptions.table.status.active') }}</th>
                            <th class="px-4 py-3 border-0 text-end">{{ __('admin::admin.subscriptions.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($subscriptions as $subscription)
                            @php
                                $isExpired = $subscription->ends_at && $subscription->ends_at->isPast();
                                $isLifetime = !$subscription->ends_at;
                                $type = $subscription->package ? $subscription->package->name : $subscription->type_label;
                                $price = $subscription->package ? number_format($subscription->package->price, 0) . ' ' . (__('admin::admin.egp') ?? 'ج.م') : '0 ' . (__('admin::admin.egp') ?? 'ج.م');
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">
                                            {{ substr($subscription->tenant->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.tenants.show', $subscription->tenant->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                {{ $subscription->tenant->name }}
                                            </a>
                                            <div class="x-small text-muted mt-1">
                                                <div class="d-flex align-items-center gap-1"><i class="bi bi-person"></i> {{ $admin->name ?? '---' }}</div>
                                                <div class="d-flex align-items-center gap-1"><i class="bi bi-envelope"></i> {{ $subscription->tenant->email ?: ($admin->email ?? '---') }}</div>
                                                <div class="d-flex align-items-center gap-1"><i class="bi bi-telephone"></i> {{ $subscription->tenant->phone ?: ($admin->phone ?? '---') }}</div>
                                                <div class="d-flex align-items-center gap-1 text-primary"><i class="bi bi-link-45deg"></i> {{ $subscription->tenant->domain }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-dark small">{{ $type }}</span>
                                            @if($subscription->billing_cycle)
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary x-small rounded-pill">
                                                <div class="text-muted x-small">
                                                    @if($subscription->billing_cycle == 'yearly')
                                                        {{ __('admin::admin.subscriptions.table.yearly') ?? 'سنوي' }}
                                                    @elseif($subscription->billing_cycle == 'term')
                                                        {{ __('admin::admin.subscriptions.table.term') ?? 'ترم' }}
                                                    @else
                                                        {{ __('admin::admin.subscriptions.table.monthly') ?? 'شهري' }}
                                                    @endif
                                                </div>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="x-small text-muted fw-medium">{{ number_format($subscription->base_price ?: ($subscription->package->price ?? 0), 0) }} {{ __('admin::admin.egp') ?? 'ج.م' }}</span>
                                            @if($subscription->coupon_code && $subscription->discount_amount > 0)
                                                <span class="badge bg-info bg-opacity-10 text-info x-small" title="كوبون: {{ $subscription->coupon_code }}">
                                                    <i class="bi bi-ticket-perforated"></i> %{{ (int)$subscription->discount_amount }}
                                                </span>
                                            @endif
                                            @php
                                                $isFreeTrial = ($subscription->package && $subscription->package->slug === 'free-trial') || ($subscription->stripe_price === 'price_free');
                                                $displayPrice = $subscription->total_amount ?: ($subscription->base_price ?: ($subscription->package->price ?? 0));
                                            @endphp
                                            @if($isFreeTrial)
                                                <span class="x-small text-primary fw-bold"><i class="bi bi-gift"></i> {{ __('admin::admin.subscriptions.table.free_trial') }}</span>
                                            @elseif($subscription->stripe_status == 'active')
                                                <span class="x-small text-success fw-bold"><i class="bi bi-shield-check"></i> {{ number_format($displayPrice, 0) }} {{ __('admin::admin.egp') ?? 'ج.م' }}</span>
                                            @else
                                                <span class="x-small text-warning"><i class="bi bi-clock"></i> {{ __('admin::admin.subscriptions.table.pending') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($isLifetime)
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">{{ __('admin::admin.subscriptions.table.lifetime') }}</span>
                                    @else
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="x-small text-muted italic mb-1">بدأ: {{ $subscription->created_at->format('Y-m-d') }}</span>
                                            <span class="fw-bold small {{ $isExpired ? 'text-danger' : 'text-dark' }}">
                                                تجديد: {{ $subscription->ends_at->format('Y-m-d') }}
                                            </span>
                                            @php $daysRem = (int)now()->diffInDays($subscription->ends_at, false); @endphp
                                            <span class="x-small text-muted mt-1">
                                                @if($daysRem > 0)
                                                  <div class="x-small text-muted">{{ __('admin::admin.subscriptions.table.days_remaining', ['days' => $daysRem]) }}</div>
                                                @else
                                                  <div class="x-small text-danger">{{ __('admin::admin.subscriptions.table.expired_since', ['days' => abs($daysRem)]) }}</div>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success') }} bg-opacity-10 text-{{ $isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success') }} rounded-pill px-3 py-2 border border-{{ $isExpired ? 'danger' : ($isLifetime ? 'primary' : 'success') }} border-opacity-10">
                                        <i class="bi bi-{{ $isExpired ? 'exclamation-circle' : 'circle-fill' }} me-1" style="{{ !$isExpired ? 'font-size: 8px;' : '' }}"></i>
                                        {{ $isExpired ? __('admin::admin.subscriptions.table.status.expired') : ($isLifetime ? __('admin::admin.subscriptions.table.status.continuous') : __('admin::admin.subscriptions.table.status.active')) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4" style="position: relative; pointer-events: auto;">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-none border dropdown-toggle-custom" type="button" onclick="toggleCustomDropdown(event, this)">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.tenants.show', $subscription->tenant_id) }}"><i class="bi bi-building me-2"></i> {{ __('admin::admin.subscriptions.actions.view_center') }}</a></li>
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.subscriptions.edit', $subscription->id) }}"><i class="bi bi-pencil me-2"></i> {{ __('admin::admin.subscriptions.actions.edit_subscription') }}</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="{{ route('admin.tenants.impersonate', $subscription->tenant_id) }}"><i class="bi bi-box-arrow-in-right me-2"></i> {{ __('admin::admin.tenants.actions.impersonate') }}</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" onsubmit="return confirm('{{ __('admin::admin.subscriptions.actions.delete_confirm') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger">
                                                        <i class="bi bi-trash me-2"></i> {{ __('admin::admin.subscriptions.actions.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <div class="py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <h4 class="fw-bold mb-2">{{ __('admin::admin.tenants.no_results.title') }}</h4>
                                        <p class="text-muted mb-4 px-5">{{ __('admin::admin.tenants.no_results.description') }}</p>
                                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-primary rounded-pill px-4">{{ __('admin::admin.tenants.no_results.view_all') }}</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($subscriptions->hasPages())
            <div class="card-footer bg-white border-0 p-4 pt-0">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
@push('scripts')
<style>
    /* Ensure dropdowns are always on top and visible */
    .dropdown-menu {
        z-index: 99999 !important;
        display: none; /* Default hidden */
        position: absolute;
        inset: auto 0 auto auto;
        margin: 0;
        transform: translate(0, 10px);
    }
    .dropdown-menu.show {
        display: block !important; /* Force show */
    }
    .table-responsive {
        overflow: visible !important;
    }
    /* Hide the default Bootstrap caret */
    .dropdown-toggle-custom::after {
        display: none !important;
    }
</style>
@endpush
@endsection
