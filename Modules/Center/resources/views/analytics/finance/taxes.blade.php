@extends('center::layouts.hope-master')
@section('page-title', __('center::analytics.total_taxes'))
@section('page-subtitle', __('center::analytics.tax_invoices_log'))

@section('page-actions')
    <a href="{{ route('center.analytics.finance') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
    </a>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border-left-dark">
                <div class="card-body p-4 position-relative">
                    <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-coins fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_taxes') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalTaxes) }}</h3>
                </div>
                <div class="bg-dark" style="height: 4px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::analytics.tax_invoices_log') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('center::sales.invoice_number') }}</th>
                            <th>{{ __('center::sales.student') }}</th>
                            <th>{{ __('center::analytics.tax_value') }}</th>
                            <th>{{ __('center::analytics.subtotal') }}</th>
                            <th>{{ __('center::analytics.total_with_tax') }}</th>
                            <th>{{ __('center::sales.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxes as $sale)
                            <tr>
                                <td><a href="{{ route('center.sales.show', $sale->id) }}" class="fw-bold">#{{ $sale->id }}</a></td>
                                <td>{{ $sale->student->name ?? __('center::analytics.undefined') }}</td>
                                <td class="fw-bold">{{ format_price($sale->tax_amount) }}</td>
                                <td class="text-muted">{{ format_price($sale->subtotal_amount > 0 ? $sale->subtotal_amount : $sale->total_amount - $sale->tax_amount) }}</td>
                                <td class="fw-bold text-success">{{ format_price($sale->total_amount) }}</td>
                                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">{{ __('center::analytics.no_taxes_applied') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $taxes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
