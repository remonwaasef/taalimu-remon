@extends('center::layouts.app-next')
@section('page-title', __('center::analytics.discounts_title'))
@section('page-subtitle', __('center::analytics.discounts_desc'))

@section('page-actions')
    <a href="{{ route('center.analytics.finance') }}" class="btn btn-glass shadow-sm">
        <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
    </a>
@endsection

@section('panel-content')
<div class="container-fluid">

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-percentage fa-lg"></i>
                        </div>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">{{ __('center::analytics.discounts_granted') }}</span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_discounts_amount') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalDiscounts) }}</h3>
                </div>
                <div class="bg-secondary" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4 px-4">
            <h5 class="fw-bold mb-0 text-dark">{{ __('center::analytics.discounts_log') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" data-mobile-cards>
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">{{ __('center::sales.invoice_number') }}</th>
                            <th>{{ __('center::analytics.discount_value') }}</th>
                            <th>{{ __('center::analytics.subtotal') }}</th>
                            <th>{{ __('center::analytics.final_after_discount') }}</th>
                            <th class="px-4">{{ __('center::analytics.invoice_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $sale)
                            <tr>
                                <td class="px-4">
                                    <a href="{{ route('center.sales.show', $sale->id) }}" class="fw-bold text-primary text-decoration-none">#{{ $sale->id }}</a>
                                    <small class="d-block text-muted">{{ $sale->student->name ?? __('center::analytics.undefined') }}</small>
                                </td>
                                <td class="fw-bold text-danger">- {{ format_price($sale->discount_amount) }}</td>
                                <td class="text-muted">{{ format_price($sale->subtotal_amount > 0 ? $sale->subtotal_amount : $sale->total_amount + $sale->discount_amount) }}</td>
                                <td class="fw-bold text-success">{{ format_price($sale->total_amount) }}</td>
                                <td class="px-4 small text-muted">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-search-dollar fa-3x text-light"></i>
                                    </div>
                                    <p class="mb-0">{{ __('center::analytics.no_discounts_recorded') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($discounts->hasPages())
            <div class="p-4 border-top">
                {{ $discounts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
