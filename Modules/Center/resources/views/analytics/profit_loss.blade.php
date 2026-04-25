@extends('center::layouts.hope-master')

@section('page-title', __('center::analytics.profit_loss_report'))

@section('page-actions')
    <div class="d-flex gap-2">
        <form action="{{ route('center.analytics.profit_loss') }}" method="GET" class="d-flex gap-2 align-items-center">
            <select name="year" class="form-select form-select-sm rounded-pill px-3 shadow-sm" onchange="this.form.submit()">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
        <a href="{{ route('center.analytics.finance') }}" class="btn btn-glass shadow-sm btn-sm">
            <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
        </a>
    </div>
@endsection

@section('content')

    <!-- Yearly Summary Cards -->
    <div class="row g-4 mb-5 animate__animated animate__fadeIn">
        <div class="col-md-4">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fas fa-hand-holding-usd fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">{{ __('center::analytics.revenue') }} ({{ $year }})</p>
                            <h2 class="fw-bold text-dark mb-0">{{ format_price($totalYearlyRevenue) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-success opacity-50" style="height: 4px;"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">{{ __('center::analytics.total_expenses') }} ({{ $year }})</p>
                            <h2 class="fw-bold text-dark mb-0">{{ format_price($totalYearlyExpenses) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-danger opacity-50" style="height: 4px;"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-{{ $totalYearlyProfit >= 0 ? 'primary' : 'warning' }} bg-opacity-10 text-{{ $totalYearlyProfit >= 0 ? 'primary' : 'warning' }} rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">{{ __('center::analytics.net_result') }} ({{ $year }})</p>
                            <h2 class="fw-bold text-dark mb-0">{{ format_price($totalYearlyProfit) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-{{ $totalYearlyProfit >= 0 ? 'primary' : 'warning' }} opacity-50" style="height: 4px;"></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Monthly Breakdown Table -->
        <div class="col-lg-8 animate__animated animate__fadeInLeft">
            <div class="card border-0 shadow-sm rounded-5 overflow-hidden h-100 bg-white">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-calendar-alt text-primary me-2"></i>{{ __('center::analytics.monthly_breakdown') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">{{ __('center::analytics.month') }}</th>
                                    <th class="border-0">{{ __('center::analytics.revenue') }}</th>
                                    <th class="border-0">{{ __('center::analytics.total_expenses') }}</th>
                                    <th class="border-0 px-4 text-end">{{ __('center::analytics.net_result') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $data)
                                    <tr>
                                        <td class="px-4 fw-bold text-dark">{{ $data['month_name'] }}</td>
                                        <td class="text-success fw-bold">{{ format_price($data['revenue']) }}</td>
                                        <td class="text-danger small">{{ format_price($data['total_expenses']) }}</td>
                                        <td class="px-4 text-end">
                                            <span class="badge {{ $data['profit'] >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $data['profit'] >= 0 ? 'success' : 'danger' }} rounded-pill px-3 py-2 fw-bold">
                                                {{ format_price($data['profit']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Categories -->
        <div class="col-lg-4 animate__animated animate__fadeInRight">
            <div class="card border-0 shadow-sm rounded-5 h-100 bg-white">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-pie-chart text-danger me-2"></i>{{ __('center::analytics.operating_expenses') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        @forelse($expenseCategories as $cat)
                            <div class="p-3 rounded-4 bg-light border border-white hover-lift shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark">{{ $cat->category ?: __('center::analytics.undefined') }}</span>
                                    <span class="fw-bold text-danger">{{ format_price($cat->total) }}</span>
                                </div>
                                <div class="progress rounded-pill bg-white" style="height: 6px;">
                                    @php
                                        $pct = $totalYearlyExpenses > 0 ? ($cat->total / $totalYearlyExpenses) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-danger rounded-pill" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle mb-2 d-block fs-3"></i>
                                {{ __('center::analytics.no_data_available') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .shadow-elite {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .btn-glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
</style>
@endpush
