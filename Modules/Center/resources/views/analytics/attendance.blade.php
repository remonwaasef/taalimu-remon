@extends('center::layouts.hope-master')

@section('title', __('center::analytics.attendance_reports'))

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('center::analytics.attendance_reports') }}</h1>
        <a href="{{ route('center.analytics.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-right"></i>{{ __('center::messages.back') }}</a>
    </div>

    <!-- Attendance Summary -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('center::analytics.attendance_summary') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i>{{ __('center::analytics.present') }}</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i>{{ __('center::analytics.late') }}</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i>{{ __('center::analytics.absent') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Log -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('center::analytics.recent_attendance_log') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('center::analytics.student') }}</th>
                            <th>{{ __('center::sidebar.courses') }}</th>
                            <th>{{ __('center::analytics.session_date') }}</th>
                            <th>{{ __('center::analytics.status') }}</th>
                            <th>{{ __('center::analytics.check_in_time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAttendance as $record)
                            <tr>
                                <td>{{ $record->student->name }}</td>
                                <td>{{ $record->course->title }}</td>
                                <td>{{ $record->session_date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge badge-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger') }}">
                                        {{ __('center::analytics.' . $record->status) }}
                                    </span>
                                </td>
                                <td>{{ $record->check_in_time ? $record->check_in_time->format('h:i A') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $recentAttendance->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxAtt = document.getElementById("attendanceChart");
    var attChart = new Chart(ctxAtt, {
        type: 'doughnut',
        data: {
            labels: ["{{ __('center::analytics.present') }}", "{{ __('center::analytics.late') }}", "{{ __('center::analytics.absent') }}"],
            datasets: [{
                data: [{{ $attendanceStats['present'] ?? 0 }}, {{ $attendanceStats['late'] ?? 0 }}, {{ $attendanceStats['absent'] ?? 0 }}],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: { backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, caretPadding: 10 },
            legend: { display: false },
            cutoutPercentage: 80,
        },
    });
</script>
@endpush
@endsection
