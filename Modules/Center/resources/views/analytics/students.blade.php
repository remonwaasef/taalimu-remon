@extends('center::layouts.master')

@section('title', __('center::messages.blade_0098'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">{{ __('center::messages.blade_0079') }}</h1>
            <p class="text-muted mb-0">{{ __('center::messages.blade_0080') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-white border shadow-sm rounded-pill px-3" onclick="window.print()">
                <i class="fas fa-print me-2"></i>{{ __('center::messages.blade_0081') }}</button>
            <a href="{{ route('center.analytics.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="fas fa-arrow-left me-2"></i>{{ __('center::messages.blade_0082') }}</a>
        </div>
    </div>

    <!-- 1. Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">{{ __('center::messages.blade_0083') }}</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">{{ __('center::messages.blade_0084') }}</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $activeStudents }}</div>
                            <small class="text-muted">الذين لديهم حالة "نشط"</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success bg-opacity-10 text-success">
                                <i class="fas fa-user-check fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 py-2 rounded-4 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">{{ __('center::messages.blade_0085') }}</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $inactiveStudents }}</div>
                            <small class="text-muted">{{ __('center::messages.blade_0086') }}</small>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-user-slash fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Growth Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">{{ __('center::messages.blade_0087') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="studentGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demographics / Status Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-primary">{{ __('center::messages.blade_0088') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2 pb-2" style="height: 250px;">
                        <canvas id="gradeDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small text-muted">{{ __('center::messages.blade_0089') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Tables Row -->
    <div class="row g-4">
        <!-- Top Spenders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-success">
                        <i class="fas fa-crown me-2"></i>{{ __('center::messages.blade_0090') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3">{{ __('center::messages.blade_0091') }}</th>
                                    <th class="border-0 p-3 text-end">{{ __('center::messages.blade_0092') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topStudents as $student)
                                    <tr>
                                        <td class="p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-2">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('center.students.show', $student->id) }}" class="fw-bold text-decoration-none text-gray-800 hover-primary">
                                                        {{ $student->name }}
                                                    </a>
                                                    <small class="text-muted d-block">{{ $student->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-end fw-bold text-success">
                                            {{ format_price($student->sales_count > 0 ? $student->sales->sum('paid_amount') : 0) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4 text-muted">{{ __('center::messages.blade_0093') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debtors -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 bg-white border-0 rounded-top-4">
                    <h6 class="m-0 fw-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ __('center::messages.blade_0094') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3">{{ __('center::messages.blade_0095') }}</th>
                                    <th class="border-0 p-3 text-end">{{ __('center::messages.blade_0096') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($debtorStudents as $student)
                                    <tr>
                                        <td class="p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-danger text-white me-2">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('center.students.show', $student->id) }}" class="fw-bold text-decoration-none text-gray-800 hover-primary">
                                                        {{ $student->name }}
                                                    </a>
                                                    <small class="text-muted d-block">{{ $student->phone }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-3 text-end fw-bold text-danger">
                                            {{ format_price($student->total_debt) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4 text-muted">{{ __('center::messages.blade_0097') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .icon-circle {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .avatar-circle {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: bold;
    }
    .card-header { border-bottom: 1px solid rgba(0,0,0,0.05) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Cairo', 'Nunito', sans-serif";
    
    // 1. Growth Chart
    var ctxGrowth = document.getElementById("studentGrowthChart");
    new Chart(ctxGrowth, {
        type: 'bar',
        data: {
            labels: @json($studentGrowth->pluck('months')),
            datasets: [{
                label: "طلاب جدد",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderRadius: 5,
                data: @json($studentGrowth->pluck('count')),
                barThickness: 30,
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2], drawBorder: false } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // 2. Grade Distribution Chart
    var ctxGrade = document.getElementById("gradeDistributionChart");
    
    // Prepare data
    var gradeLabels = [];
    var gradeData = [];
    
    @foreach($studentsByGrade as $grade)
        // Simple mapping for demonstration, ideally done in backend or with a robust JS map
        var label = "{{ $grade->grade_level }}"; 
        // Try to map numeric to text if simple 1-12
        const gradesMap = {
            '1': 'أولى ابتدائي', '2': 'ثانية ابتدائي', '3': 'ثالثة ابتدائي',
            '4': 'رابعة ابتدائي', '5': 'خامسة ابتدائي', '6': 'سادسة ابتدائي',
            '7': 'أولى إعدادي', '8': 'ثانية إعدادي', '9': 'ثالثة إعدادي',
            '10': 'أولى ثانوي', '11': 'ثانية ثانوي', '12': 'ثالثة ثانوي'
        };
        if(gradesMap[label]) label = gradesMap[label];
        
        gradeLabels.push(label);
        gradeData.push({{ $grade->count }});
    @endforeach

    new Chart(ctxGrade, {
        type: 'doughnut',
        data: {
            labels: gradeLabels,
            datasets: [{
                data: gradeData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#2c9faf', '#17a673'],
                borderWidth: 0
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
            }
        }
    });
</script>
@endpush
@endsection
