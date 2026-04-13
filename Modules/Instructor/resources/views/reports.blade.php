@extends('instructor::components.layouts.hope-master')

@section('title', __('instructor::sidebar.reports'))
@section('page-title', __('instructor::sidebar.reports'))
@section('page-subtitle', __('instructor::reports.subtitle'))

@section('content')
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{ __('instructor::reports.revenue_performance') }}</h5>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ __('instructor::reports.last_6_months') }}</span>
                </div>
            </div>
            <div class="card-body px-4">
                <canvas id="revenueChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Course Distribution (Bar) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">{{ __('instructor::reports.course_enrollments') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="iq-details-list mt-4">
                    @foreach($courseStats as $course)
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $course->title }}</h6>
                                    <small class="text-muted">{{ $course->enrollments_count }} {{ __('instructor::reports.students') }}</small>
                                </div>
                            </div>
                            <div class="fw-bold text-primary">{{ round(($course->enrollments_count / max(1, $courseStats->sum('enrollments_count'))) * 100) }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Trends Chart -->
    <div class="col-lg-12 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{ __('instructor::reports.attendance_trends') }}</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ __('instructor::reports.last_7_days') }}</span>
                </div>
            </div>
            <div class="card-body px-4">
                <canvas id="attendanceChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenueData);
    
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.month),
            datasets: [{
                label: '{{ __('instructor::reports.revenue') }}',
                data: revenueData.map(d => d.amount),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Attendance Chart
    const attCtx = document.getElementById('attendanceChart').getContext('2d');
    const attData = @json($attendanceTrends);
    
    new Chart(attCtx, {
        type: 'bar',
        data: {
            labels: attData.map(d => d.date),
            datasets: [
                {
                    label: '{{ __('instructor::reports.present') }}',
                    data: attData.map(d => d.present),
                    backgroundColor: '#10b981',
                    borderRadius: 10
                },
                {
                    label: '{{ __('instructor::reports.absent') }}',
                    data: attData.map(d => d.absent),
                    backgroundColor: '#ef4444',
                    borderRadius: 10
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { beginAtZero: true, stacked: false },
                x: { stacked: false, grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
