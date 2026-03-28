@extends('center::layouts.hope-master')
@section('page-title', __('center::analytics.discounts_title'))

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-tags text-primary me-2"></i>{{ __('center::analytics.discounts_title') }}</h1>
            <p class="text-muted mb-0 mt-1">{{ __('center::analytics.discounts_desc') }}</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('center.analytics.finance') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border-left-secondary">
                <div class="card-body p-4 position-relative">
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-percentage fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_discounts_amount') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalDiscounts) }}</h3>
                </div>
                <div class="bg-secondary" style="height: 4px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::analytics.discounts_log') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('center::sales.invoice_number') }}</th>
                            <th>{{ __('center::analytics.discount_value') }}</th>
                            <th>{{ __('center::analytics.subtotal') }}</th>
                            <th>{{ __('center::analytics.final_after_discount') }}</th>
                            <th>{{ __('center::analytics.invoice_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('center.sales.show', $sale->id) }}" class="fw-bold">#{{ $sale->id }}</a>
                                    <small class="d-block text-muted">{{ $sale->student->name ?? __('center::analytics.undefined') }}</small>
                                </td>
                                <td class="fw-bold text-danger">- {{ format_price($sale->discount_amount) }}</td>
                                <td class="text-muted">{{ format_price($sale->subtotal_amount > 0 ? $sale->subtotal_amount : $sale->total_amount + $sale->discount_amount) }}</td>
                                <td class="fw-bold text-success">{{ format_price($sale->total_amount) }}</td>
                                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-search-dollar fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">{{ __('center::analytics.no_discounts_recorded') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $discounts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
