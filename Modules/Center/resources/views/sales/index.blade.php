@extends('center::layouts.hope-master')

@section('title', __('center::sales.title'))

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <h2 class="fw-bold text-dark mb-0">{{ __('center::sales.title') }}</h2>
    <a href="{{ route('center.sales.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i> {{ __('center::sales.new_sale') }}
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4 py-3">{{ __('center::sales.invoice_number') }}</th>
                        <th class="border-0">{{ __('center::sales.student') }}</th>
                        <th class="border-0">{{ __('center::sales.amount') }}</th>
                        <th class="border-0">{{ __('center::sales.paid') }}</th>
                        <th class="border-0">{{ __('center::sales.status') }}</th>
                        <th class="border-0">{{ __('center::sales.date') }}</th>
                        <th class="border-0 rounded-end px-4">{{ __('center::sales.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr class="hover-bg">
                        <td class="ps-4 fw-bold">#{{ $sale->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user-graduate small"></i>
                                </div>
                                <span class="fw-bold">{{ $sale->student->name }}</span>
                            </div>
                        </td>
                        <td class="fw-bold text-dark">{{ number_format($sale->total_amount, 2) }} {{ __('center::sales.currency') }}</td>
                        <td class="text-success fw-bold">{{ number_format($sale->paid_amount, 2) }} {{ __('center::sales.currency') }}</td>
                        <td>
                            <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} rounded-pill px-3">
                                {{ __('center::sales.status_' . ($sale->status == 'pending' ? 'unpaid' : $sale->status)) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $sale->created_at->format('Y-m-d') }}</td>
                        <td class="px-4">
                            <a href="{{ route('center.sales.show', $sale->id) }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-none border">
                                <i class="fas fa-eye me-1"></i> {{ __('center::sales.view_invoice') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($sales->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $sales->links() }}
    </div>
    @endif
</div>

<style>
    .hover-bg:hover {
        background-color: rgba(67, 97, 238, 0.02);
    }
</style>
@endsection
