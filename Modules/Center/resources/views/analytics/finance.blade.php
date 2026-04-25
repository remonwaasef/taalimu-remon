@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0027'))

@section('page-actions')
    <a href="{{ route('center.analytics.index') }}" class="btn btn-glass shadow-sm">
        <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
    </a>
@endsection

@section('content')

    <!-- Financial Summary Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('center::analytics.revenue') }}</span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::messages.blade_0029') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalRevenue) }}</h3>
                </div>
                <div class="bg-success" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ __('center::analytics.net_profit') }}</span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.net_profit') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($netProfit) }}</h3>
                </div>
                <div class="bg-primary" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-receipt fa-lg"></i>
                        </div>
                        <a href="{{ route('center.expenses.index') }}" class="btn btn-sm btn-light rounded-pill px-3">{{ __('center::analytics.view_details') }}</a>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_expenses') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalExpenses) }}</h3>
                </div>
                <div class="bg-danger" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>

        <!-- Instructor Commissions -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <a href="{{ route('center.analytics.commissions') }}" class="btn btn-sm btn-light rounded-pill px-3">{{ __('center::analytics.view_details') }}</a>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.teacher_commissions') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalCommissions) }}</h3>
                </div>
                <div class="bg-info" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>

        <!-- Total Outstanding Due -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">{{ __('center::messages.blade_0030') }}</span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::messages.blade_0030') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalDue) }}</h3>
                </div>
                <div class="bg-warning" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>

        <!-- Total Discounts -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-tags fa-lg"></i>
                        </div>
                        <a href="{{ route('center.analytics.discounts') }}" class="btn btn-sm btn-light rounded-pill px-3">{{ __('center::analytics.view_details') }}</a>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.discounts_granted') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalDiscounts) }}</h3>
                </div>
                <div class="bg-secondary" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>

        <!-- Total Tax -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                        </div>
                        <a href="{{ route('center.analytics.taxes') }}" class="btn btn-sm btn-light rounded-pill px-3">{{ __('center::analytics.view_details') }}</a>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_taxes') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalTaxes) }}</h3>
                </div>
                <div class="bg-dark" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-0 py-4 px-4">
            <h5 class="fw-bold mb-0 text-dark">{{ __('center::messages.blade_0031') }}</h5>
        </div>
        <div class="card-body p-4">
            <div class="chart-area" style="height: 350px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">{{ __('center::messages.blade_0032') }}</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ __('center::analytics.recent_transactions') }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">{{ __('center::analytics.transaction_number') }}</th>
                            <th>{{ __('center::analytics.invoice_student') }}</th>
                            <th>{{ __('center::messages.blade_0035') }}</th>
                            <th>{{ __('center::messages.blade_0036') }}</th>
                            <th>{{ __('center::messages.blade_0037') }}</th>
                            <th>{{ __('center::analytics.status') }}</th>
                            <th class="px-4">{{ __('center::messages.blade_0039') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td class="px-4 fw-bold">#{{ $sale->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $sale->student->name }}</div>
                                    <small class="text-muted">{{ $sale->student->phone ?? '' }}</small>
                                </td>
                                <td>{{ number_format($sale->total_amount) }}</td>
                                <td class="text-success fw-medium">{{ number_format($sale->paid_amount) }}</td>
                                <td class="text-danger fw-medium">{{ number_format($sale->total_amount - $sale->paid_amount) }}</td>
                                <td>
                                    @php
                                        $statusClass = $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger');
                                        $statusLabel = $sale->status == 'paid' ? __('center::messages.blade_0041') : ($sale->status == 'partial' ? __('center::messages.blade_0042') : __('center::messages.blade_0043'));
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} rounded-pill px-3">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-4 small text-muted">{{ $sale->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
            <div class="p-4 border-top">
                {{ $sales->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxRev = document.getElementById("revenueChart");
    var revChart = new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: @json($monthlyRevenue->pluck('months')),
            datasets: [{
                label: "{{ __('center::analytics.revenue') }}",
                lineTension: 0.3,
                backgroundColor: "rgba(28, 200, 138, 0.05)",
                borderColor: "rgba(28, 200, 138, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(28, 200, 138, 1)",
                pointBorderColor: "rgba(28, 200, 138, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: @json($monthlyRevenue->pluck('sums')),
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                xAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } }],
                yAxes: [{ ticks: { maxTicksLimit: 5, padding: 10, callback: function(value) { return '{{ get_currency_symbol() }} ' + value; } }, gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }],
            },
            legend: { display: false },
        }
    });
</script>
@endpush
@endsection
