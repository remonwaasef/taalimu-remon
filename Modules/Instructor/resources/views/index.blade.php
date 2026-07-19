@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::dashboard.title'))

@push('styles')
<style>
    .stats-card {
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    .hover-lift {
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.1);
    }
    .action-card {
        background: #ffffff !important;
        border: 1px solid #f1f5f9 !important;
    }
    .action-card:hover {
        border-color: rgba(5, 150, 105, 0.2) !important;
    }
    .hover-lift i {
        background: #f8fafc;
        padding: 12px;
        border-radius: 12px;
    }
    .empty-state-container {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 1rem;
        border: 1px dashed #cbd5e1;
    }
</style>
@endpush

@section('content')
    @if($totalCourses == 0 || $totalStudents == 0)
    <div class="card border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-2">{{ __('instructor::dashboard.getting_started_title') }}</h3>
                    <p class="mb-4 opacity-75">{{ __('instructor::dashboard.getting_started_desc') }}</p>
                    
                    @php
                        $completedSteps = 0;
                        if($totalCourses > 0) $completedSteps++;
                        if($totalStudents > 0) $completedSteps++;
                        $progress = ($completedSteps / 2) * 100;
                    @endphp
                    
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-bold me-3">{{ $completedSteps }} / 2</span>
                        <div class="progress flex-grow-1" style="height: 8px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $progress }}%; border-radius: 10px;"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('instructor.groups.create') }}" class="btn {{ $totalCourses > 0 ? 'btn-success bg-opacity-25 border-0 disabled text-white' : 'btn-light text-success fw-bold' }} rounded-pill px-4">
                            @if($totalCourses > 0) <i class="fas fa-check-circle me-2"></i> @else <i class="fas fa-circle me-2 opacity-50"></i> @endif
                            {{ __('instructor::dashboard.step_create_group') }}
                        </a>
                        <a href="{{ route('instructor.students.create') }}" class="btn {{ $totalStudents > 0 ? 'btn-success bg-opacity-25 border-0 disabled text-white' : 'btn-light text-success fw-bold' }} rounded-pill px-4">
                            @if($totalStudents > 0) <i class="fas fa-check-circle me-2"></i> @else <i class="fas fa-circle me-2 opacity-50"></i> @endif
                            {{ __('instructor::dashboard.step_add_student') }}
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Get Started" class="img-fluid" style="max-height: 180px; opacity: 0.9; filter: drop-shadow(0px 10px 10px rgba(0,0,0,0.1));">
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-md-4" id="tour-stats-students">
            <div class="stats-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1">
                            {{ __('instructor::dashboard.total_students') }}
                            <i class="fas fa-info-circle ms-1 opacity-50" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('instructor::dashboard.tour.stats_desc') }}"></i>
                        </h6>
                        <h2 class="fw-bold mb-0 count-up text-primary">{{ number_format($totalStudents) }}</h2>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(5, 150, 105, 0.08);">
                        <i class="fas fa-user-graduate text-primary fs-4"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(5, 150, 105, 0.05);">
                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="tour-stats-groups">
            <div class="stats-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1">
                            {{ __('instructor::dashboard.active_groups') }}
                            <i class="fas fa-info-circle ms-1 opacity-50" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('instructor::dashboard.tour.groups_desc') }}"></i>
                        </h6>
                        <h2 class="fw-bold mb-0 count-up text-success">{{ number_format($totalCourses) }}</h2>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(34, 197, 94, 0.08);">
                        <i class="fas fa-users text-success fs-4"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(34, 197, 94, 0.05);">
                    <div class="progress-bar bg-success" style="width: 45%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1">
                            {{ __('instructor::dashboard.monthly_revenue') }}
                            <i class="fas fa-info-circle ms-1 opacity-50" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('instructor::dashboard.tour.stats_desc') }}"></i>
                        </h6>
                        <h2 class="fw-bold mb-0">
                            <span class="count-up text-info">{{ number_format($monthlyRevenue) }}</span>
                            <small class="fs-6 fw-normal text-muted">{{ app('tenant')->settings['currency'] ?? 'EGP' }}</small>
                        </h2>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(13, 202, 240, 0.08);">
                        <i class="fas fa-wallet text-info fs-4"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(13, 202, 240, 0.05);">
                    <div class="progress-bar bg-info" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="stats-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">{{ __('instructor::dashboard.attendance_analytics') }}</h5>
                    <span class="badge bg-light text-primary rounded-pill px-3">{{ __('instructor::dashboard.last_7_days') }}</span>
                </div>
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4" id="tour-quick-links">
            <div class="stats-card p-4 h-100">
                <h5 class="fw-bold mb-4">{{ __('instructor::dashboard.quick_links') }}</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('instructor.students.create') }}" class="btn btn-light action-card text-start p-3 rounded-4 border-0 hover-lift">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-plus-circle text-primary me-3 fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark">{{ __('instructor::dashboard.add_new_student') }}</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('instructor.groups.create') }}" class="btn btn-light action-card text-start p-3 rounded-4 border-0 hover-lift">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-folder-plus text-success me-3 fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark">{{ __('instructor::dashboard.create_new_group') }}</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('instructor.attendance.index') }}" class="btn btn-light action-card text-start p-3 rounded-4 border-0 hover-lift">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-qrcode text-info me-3 fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark">{{ __('instructor::dashboard.smart_attendance') }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Section -->
    <div class="stats-card p-4 mb-5" id="tour-groups-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">{{ __('instructor::dashboard.groups_and_registration') }}</h5>
        </div>
        
        @if($courses->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start-3 px-4">{{ __('instructor::dashboard.group') }}</th>
                        <th class="border-0">{{ __('instructor::dashboard.students') }}</th>
                        <th class="border-0">{{ __('instructor::dashboard.registration_link') }}</th>
                        <th class="border-0 rounded-end-3 text-end px-4">{{ __('instructor::dashboard.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold text-dark">{{ $course->title }}</div>
                            <small class="text-muted">{{ $course->schedules->count() }} {{ __('instructor::dashboard.attendees_count') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                {{ $course->enrollments_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            @if($course->registration_token)
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <input type="text" class="form-control bg-white" value="{{ route('group.register', ['token' => $course->registration_token]) }}" id="link-{{ $course->id }}" readonly>
                                <button class="btn btn-primary px-3" onclick="copyLink('link-{{ $course->id }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            @else
                            <span class="text-muted small">{{ __('instructor::dashboard.no_link') }}</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                    <li><a class="dropdown-item py-2" href="{{ route('instructor.scanner', $course->id) }}"><i class="fas fa-qrcode me-2 text-primary"></i> {{ __('instructor::dashboard.qr_scanner') }}</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('instructor.groups.edit', $course->id) }}"><i class="fas fa-edit me-2 text-success"></i> {{ __('instructor::dashboard.edit_data') }}</a></li>
                                    <li>
                                        <form action="{{ route('instructor.groups.duplicate', $course->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2"><i class="fas fa-copy me-2 text-info"></i> {{ __('instructor::dashboard.duplicate_group') }}</button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('instructor.groups.rotate-link', $course->id) }}" method="POST" onsubmit="return confirm('{{ __('instructor::dashboard.confirm_rotate_link') }}')">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-warning"><i class="fas fa-sync-alt me-2"></i> {{ __('instructor::dashboard.generate_new_link') }}</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('instructor.groups.destroy', $course->id) }}" method="POST" onsubmit="return confirm('{{ __('instructor::dashboard.confirm_delete_group') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i> {{ __('instructor::dashboard.delete_group') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row align-items-center bg-white p-4 p-md-5 rounded-4 border" style="border-style: dashed !important; border-color: #cbd5e1 !important;">
            <div class="col-md-7 text-center text-md-start mb-4 mb-md-0">
                <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(5, 150, 105, 0.08);">
                    <i class="fas fa-layer-group text-success fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">{{ __('instructor::dashboard.no_groups_title') }}</h3>
                <p class="text-muted mb-4 fs-6 pe-md-4">
                    {{ __('instructor::dashboard.no_groups_desc_extended') }}
                </p>
                <ul class="list-unstyled text-muted mb-4 text-start d-inline-block">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ __('instructor::dashboard.benefit_1') }}</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ __('instructor::dashboard.benefit_2') }}</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> {{ __('instructor::dashboard.benefit_3') }}</li>
                </ul>
                <div class="d-block mt-2">
                    <a href="{{ route('instructor.groups.create') }}" class="btn btn-success px-4 py-3 rounded-pill hover-lift fw-bold shadow-sm">
                        <i class="fas fa-plus me-2"></i> {{ __('instructor::dashboard.create_first_group') }}
                    </a>
                </div>
            </div>
            <div class="col-md-5 text-center d-none d-md-block">
                <img src="https://cdn-icons-png.flaticon.com/512/4185/4185796.png" class="img-fluid opacity-75" style="max-width: 220px;" alt="Groups">
            </div>
        </div>
        @endif
    </div>
@push('scripts')
<!-- Onboarding Tour removed in favor of Getting Started Checklist -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Change button icon to checkmark temporarily
    var btn = copyText.nextElementSibling;
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-primary');
    
    setTimeout(function() {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    }, 2000);
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($days) !!},
                datasets: [{
                    label: '{{ __('instructor::dashboard.attendees_count') }}',
                    data: {!! json_encode($attendanceData) !!},
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#059669',
                    borderWidth: 3,
                    pointHoverRadius: 7,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { family: 'Cairo', size: 14 },
                        bodyFont: { family: 'Cairo', size: 13 },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: { 
                            stepSize: 1,
                            font: { family: 'Cairo' }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Cairo' } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
