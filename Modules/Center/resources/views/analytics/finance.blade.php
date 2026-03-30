@extends('center::layouts.hope-master')

@section('page-title', __('center::messages.blade_0027'))

@section('page-actions')
    <a href="{{ route('center.analytics.index') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-arrow-right me-2"></i>{{ __('center::analytics.back_to_finance') }}
    </a>
@endsection

@section('content')

    <!-- Financial Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-dollar-sign fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::messages.blade_0029') }}</p>
                    <h3 class="fw-bold text-success mb-0">{{ format_price($totalRevenue) }}</h3>
                </div>
                <div class="bg-success" style="height: 4px; width: 100%;"></div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.net_profit') }}</p>
                    <h3 class="fw-bold text-primary mb-0">{{ format_price($netProfit) }}</h3>
                </div>
                <div class="bg-primary" style="height: 4px; width: 100%;"></div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-receipt fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_expenses') }}</p>
                    <h3 class="fw-bold text-danger mb-0">{{ format_price($totalExpenses) }}</h3>
                    <a href="{{ route('center.expenses.index') }}" class="btn btn-link btn-sm text-danger p-0 mt-2">{{ __('center::analytics.view_details') }} <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
                <div class="bg-danger" style="height: 4px; width: 100%;"></div>
            </div>
        </div>

        <!-- Instructor Commissions -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.teacher_commissions') }}</p>
                    <h3 class="fw-bold text-info mb-0">{{ format_price($totalCommissions) }}</h3>
                    <a href="{{ route('center.analytics.commissions') }}" class="btn btn-link btn-sm text-info p-0 mt-2">{{ __('center::analytics.view_details') }} <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
                <div class="bg-info" style="height: 4px; width: 100%;"></div>
            </div>
        </div>

        <!-- Total Outstanding Due -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::messages.blade_0030') }}</p>
                    <h3 class="fw-bold text-warning mb-0">{{ format_price($totalDue) }}</h3>
                </div>
                <div class="bg-warning" style="height: 4px; width: 100%;"></div>
            </div>
        </div>

        <!-- Total Discounts -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-tags fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.discounts_granted') }}</p>
                    <h3 class="fw-bold text-secondary mb-0">{{ format_price($totalDiscounts) }}</h3>
                    <a href="{{ route('center.analytics.discounts') }}" class="btn btn-link btn-sm text-secondary p-0 mt-2">{{ __('center::analytics.view_details') }} <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
                <div class="bg-secondary" style="height: 4px; width: 100%;"></div>
            </div>
        </div>

        <!-- Total Tax -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-4" style="width: 48px; height: 48px;">
                        <i class="fas fa-file-invoice-dollar fa-lg"></i>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1">{{ __('center::analytics.total_taxes') }}</p>
                    <h3 class="fw-bold text-dark mb-0">{{ format_price($totalTaxes) }}</h3>
                    <a href="{{ route('center.analytics.taxes') }}" class="btn btn-link btn-sm text-dark p-0 mt-2">{{ __('center::analytics.view_details') }} <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
                <div class="bg-dark" style="height: 4px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::messages.blade_0031') }}</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::messages.blade_0032') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('center::analytics.transaction_number') }}</th>
                            <th>{{ __('center::analytics.invoice_student') }}</th>
                            <th>{{ __('center::messages.blade_0035') }}</th>
                            <th>{{ __('center::messages.blade_0036') }}</th>
                            <th>{{ __('center::messages.blade_0037') }}</th>
                            <th>{{ __('center::analytics.status') }}</th>
                            <th>{{ __('center::messages.blade_0039') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>{{ $sale->student->name }}</td>
                                <td>{{ number_format($sale->total_amount) }}</td>
                                <td>{{ number_format($sale->paid_amount) }}</td>
                                <td>{{ number_format($sale->total_amount - $sale->paid_amount) }}</td>
                                <td>
                                    <span class="badge badge-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }}">
                                        {{ $sale->status == 'paid' ? __('center::messages.blade_0041') : ($sale->status == 'partial' ? __('center::messages.blade_0042') : __('center::messages.blade_0043')) }}
                                    </span>
                                </td>
                                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $sales->links() }}
            </div>
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
