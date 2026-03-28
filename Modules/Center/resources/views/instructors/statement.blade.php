@extends('center::layouts.hope-master')

@section('title', __('center::instructors.account_statement') . ': ' . $instructor->name)

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between no-print">
    <div class="d-flex align-items-center">
        <a href="{{ route('center.instructors.show', $instructor->id) }}" class="btn btn-light rounded-circle me-3">
            <i class="fas fa-arrow-right"></i>
        </a>
        <h2 class="fw-bold text-dark mb-0">{{ __('center::instructors.account_statement_ledger') }}</h2>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-print me-2"></i> {{ __('center::instructors.print_statement') }}
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4 invoice-card">
    <div class="card-body p-5">
        <!-- Header -->
        <div class="row mb-5 border-bottom pb-4">
            <div class="col-sm-6 text-start">
                <h4 class="fw-bold text-primary mb-3">{{ $tenant->name }}</h4>
                <div class="text-muted small">
                    <p class="mb-1">{{ __('center::instructors.statement_date') }}: {{ now()->format('Y/m/d H:i') }}</p>
                </div>
            </div>
            <div class="col-sm-6 text-end">
                <h5 class="fw-bold text-dark mb-1">{{ $instructor->name }}</h5>
                <div class="text-muted small">
                    <p class="mb-1">{{ $instructor->phone }}</p>
                    <p class="mb-0">{{ $instructor->email }}</p>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="row g-3 mb-5 px-3">
            <div class="col-md-4">
                <div class="bg-light rounded-3 p-3 text-center border">
                    <small class="text-muted d-block mb-1">{{ __('center::instructors.total_commissions_credit') }}</small>
                    <h5 class="fw-bold text-success mb-0">{{ format_price($ledger->where('is_credit', true)->sum('amount')) }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded-3 p-3 text-center border">
                    <small class="text-muted d-block mb-1">{{ __('center::instructors.total_payouts_debit') }}</small>
                    <h5 class="fw-bold text-danger mb-0">{{ format_price($ledger->where('is_credit', false)->sum('amount')) }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center border border-primary border-opacity-25">
                    <small class="text-primary fw-bold d-block mb-1">{{ __('center::instructors.available_balance') }}</small>
                    <h4 class="fw-bold text-primary mb-0">{{ format_price($instructor->total_earned) }}</h4>
                </div>
            </div>
        </div>

        <!-- Ledger Table -->
        <h6 class="fw-bold mb-3 text-muted text-uppercase">{{ __('center::instructors.ledger_details') }}</h6>
        <div class="table-responsive">
            <table class="table align-middle table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>{{ __('center::instructors.table_date') }}</th>
                        <th>{{ __('center::instructors.table_description') }}</th>
                        <th class="text-center text-success">{{ __('center::instructors.table_credit') }}</th>
                        <th class="text-center text-danger">{{ __('center::instructors.table_debit') }}</th>
                        <th class="text-center bg-light fw-bold">{{ __('center::instructors.table_balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse($ledger as $item)
                        <tr>
                            <td class="text-center text-muted">{{ $i++ }}</td>
                            <td class="small">{{ $item['date']->format('Y/m/d H:i') }}</td>
                            <td>
                                <span class="fw-bold d-block gap-2">
                                    @if($item['type'] == 'commission')
                                        <i class="fas fa-plus-circle text-success small"></i>
                                    @else
                                        <i class="fas fa-minus-circle text-danger small"></i>
                                    @endif
                                    {{ $item['description'] }}
                                </span>
                            </td>
                            <td class="text-center fw-bold text-success">
                                {{ $item['is_credit'] ? format_price($item['amount']) : '-' }}
                            </td>
                            <td class="text-center fw-bold text-danger">
                                {{ !$item['is_credit'] ? format_price($item['amount']) : '-' }}
                            </td>
                            <td class="text-center fw-bold bg-light">
                                {{ format_price($item['balance']) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">{{ __('center::instructors.no_financial_movements') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background-color: white !important; }
        .invoice-card { box-shadow: none !important; border: 0 !important; }
        .card-body { padding: 0 !important; }
        .invoice-card .p-5 { padding: 0 !important; }
        .sidebar, .navbar { display: none !important; }
        .main-content { margin-right: 0 !important; padding: 0 !important; width: 100% !important; }
    }
</style>
@endsection
