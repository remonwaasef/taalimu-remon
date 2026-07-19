@extends('instructor::components.layouts.hope-master')

@section('page-title', __('instructor::dashboard.title'))

@push('styles')
<style>
    /* ═══════════ Premium Design System ═══════════ */

    /* Stats Cards — Glassmorphism */
    .stats-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 8px 24px rgba(0, 0, 0, 0.03);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.7);
        position: relative;
    }
    .stats-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1.25rem;
        padding: 1px;
        background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(255,255,255,0));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }
    .stats-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 20px 40px rgba(0, 0, 0, 0.06);
    }

    /* Hover Lift — Universal */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06) !important;
    }

    /* Gradient Icon Pills */
    .icon-pill {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .icon-pill-indigo { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; }
    .icon-pill-emerald { background: linear-gradient(135deg, #059669, #34d399); color: #fff; }
    .icon-pill-amber { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff; }

    /* Quick Links — Premium Action Cards */
    .action-card {
        background: #ffffff !important;
        border: 1px solid #f1f5f9 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .action-card:hover {
        border-color: transparent !important;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
    }
    .action-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    .action-card:hover .action-icon { transform: scale(1.1); }
    .action-icon-indigo { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .action-icon-emerald { background: rgba(5, 150, 105, 0.1); color: #059669; }
    .action-icon-cyan { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }

    /* Premium Progress Bars */
    .progress-premium {
        height: 4px;
        border-radius: 100px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .progress-premium .progress-bar {
        border-radius: 100px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .progress-indigo { background: linear-gradient(90deg, #6366f1, #818cf8); }
    .progress-emerald { background: linear-gradient(90deg, #059669, #34d399); }
    .progress-amber { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    /* Accent Color Text */
    .text-indigo { color: #6366f1 !important; }
    .text-emerald { color: #059669 !important; }
    .text-amber { color: #f59e0b !important; }

    /* Getting Started Widget — Left Accent Stripe */
    .getting-started-card {
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 10px 30px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
    }
    .getting-started-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(180deg, #6366f1, #06b6d4);
        border-radius: 1.25rem 0 0 1.25rem;
    }
    html[dir="rtl"] .getting-started-card::before {
        left: auto;
        right: 0;
        border-radius: 0 1.25rem 1.25rem 0;
    }

    /* Badge Chip */
    .badge-chip {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 100px;
        letter-spacing: 0.02em;
    }

    /* Empty State CTA */
    .btn-indigo {
        background: linear-gradient(135deg, #6366f1, #818cf8) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-indigo:hover {
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.35) !important;
        transform: translateY(-2px);
    }

    /* Table Row Transitions */
    .table-hover tbody tr {
        transition: background-color 0.2s ease;
    }

    /* Stat Number Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .count-up {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    /* Section Heading */
    .section-heading {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    @media (max-width: 768px) {
        .icon-pill { width: 44px; height: 44px; border-radius: 12px; font-size: 1rem; }
    }
</style>
@endpush

@section('content')
    @if($totalCourses == 0 || $totalStudents == 0)
    <div class="getting-started-card mb-4">
        <div class="p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge-chip">{{ __('instructor::dashboard.getting_started_title') }}</span>
                    </div>
                    <h3 class="fw-bold mb-2" style="color: #0f172a; font-size: 1.5rem; letter-spacing: -0.02em;">{{ __('instructor::dashboard.getting_started_desc') }}</h3>
                    
                    @php
                        $completedSteps = 0;
                        if($totalCourses > 0) $completedSteps++;
                        if($totalStudents > 0) $completedSteps++;
                        $progress = ($completedSteps / 2) * 100;
                    @endphp
                    
                    <div class="d-flex align-items-center mb-3 mt-3">
                        <span class="fw-bold me-3" style="color: #6366f1; font-size: 0.85rem;">{{ $completedSteps }} / 2 {{ __('instructor::dashboard.steps_completed') }}</span>
                        <div class="progress-premium flex-grow-1">
                            <div class="progress-bar progress-indigo" role="progressbar" style="width: {{ $progress }}%;"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        @if($totalCourses > 0)
                            <span class="btn btn-light text-muted fw-bold border-0 rounded-pill px-4 pe-none" style="opacity: 0.7;">
                                <i class="fas fa-check-circle text-emerald me-2"></i>
                                {{ __('instructor::dashboard.step_create_group') }}
                            </span>
                        @else
                            <a href="{{ route('instructor.groups.create') }}" class="btn btn-indigo rounded-pill px-4 py-2 fw-bold hover-lift">
                                <i class="fas fa-plus me-2"></i>
                                {{ __('instructor::dashboard.step_create_group') }}
                            </a>
                        @endif
                        @if($totalStudents > 0)
                            <span class="btn btn-light text-muted fw-bold border-0 rounded-pill px-4 pe-none" style="opacity: 0.7;">
                                <i class="fas fa-check-circle text-emerald me-2"></i>
                                {{ __('instructor::dashboard.step_add_student') }}
                            </span>
                        @else
                            <a href="{{ route('instructor.students.create') }}" class="btn btn-indigo rounded-pill px-4 py-2 fw-bold hover-lift">
                                <i class="fas fa-plus me-2"></i>
                                {{ __('instructor::dashboard.step_add_student') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <div style="width: 140px; height: 140px; margin: 0 auto; background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(6,182,212,0.08)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-rocket text-indigo" style="font-size: 3rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-md-4" id="tour-stats-students">
            <div class="stats-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                            {{ __('instructor::dashboard.total_students') }}
                        </h6>
                        <h2 class="fw-bold mb-0 count-up text-indigo" style="font-size: 2rem; letter-spacing: -0.02em;">{{ number_format($totalStudents) }}</h2>
                    </div>
                    <div class="icon-pill icon-pill-indigo">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="progress-premium mt-3">
                    <div class="progress-bar progress-indigo" style="width: 70%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4" id="tour-stats-groups">
            <div class="stats-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                            {{ __('instructor::dashboard.active_groups') }}
                        </h6>
                        <h2 class="fw-bold mb-0 count-up text-emerald" style="font-size: 2rem; letter-spacing: -0.02em;">{{ number_format($totalCourses) }}</h2>
                    </div>
                    <div class="icon-pill icon-pill-emerald">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="progress-premium mt-3">
                    <div class="progress-bar progress-emerald" style="width: 45%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                            {{ __('instructor::dashboard.monthly_revenue') }}
                        </h6>
                        <h2 class="fw-bold mb-0" style="font-size: 2rem; letter-spacing: -0.02em;">
                            <span class="count-up text-amber">{{ number_format($monthlyRevenue) }}</span>
                            <small class="fs-6 fw-normal text-muted">{{ app('tenant')->settings['currency'] ?? 'EGP' }}</small>
                        </h2>
                    </div>
                    <div class="icon-pill icon-pill-amber">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="progress-premium mt-3">
                    <div class="progress-bar progress-amber" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="stats-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-heading mb-0">{{ __('instructor::dashboard.attendance_analytics') }}</h5>
                    <span class="badge-chip">{{ __('instructor::dashboard.last_7_days') }}</span>
                </div>
                <div style="position: relative; height: 280px; width: 100%;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4" id="tour-quick-links">
            <div class="stats-card p-4 h-100">
                <h5 class="section-heading mb-4">{{ __('instructor::dashboard.quick_links') }}</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('instructor.students.create') }}" class="btn btn-light action-card text-start p-3 rounded-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="action-icon action-icon-indigo me-3">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ __('instructor::dashboard.add_new_student') }}</div>
                                <small class="text-muted">{{ __('instructor::dashboard.add_student_hint') }}</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('instructor.groups.create') }}" class="btn btn-light action-card text-start p-3 rounded-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="action-icon action-icon-emerald me-3">
                                <i class="fas fa-folder-plus"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ __('instructor::dashboard.create_new_group') }}</div>
                                <small class="text-muted">{{ __('instructor::dashboard.create_group_hint') }}</small>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('instructor.attendance.index') }}" class="btn btn-light action-card text-start p-3 rounded-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="action-icon action-icon-cyan me-3">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ __('instructor::dashboard.smart_attendance') }}</div>
                                <small class="text-muted">{{ __('instructor::dashboard.attendance_hint') }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-card p-4 mb-5" id="tour-groups-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="section-heading mb-0">{{ __('instructor::dashboard.groups_and_registration') }}</h5>
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
        @else
        <div class="row align-items-center bg-white p-4 p-md-5 rounded-4 border" style="border-style: dashed !important; border-color: #cbd5e1 !important;">
            <div class="col-md-7 text-center text-md-start mb-4 mb-md-0">
                <div class="d-inline-flex p-3 rounded-circle mb-3 bg-indigo-soft">
                    <i class="fas fa-layer-group text-indigo fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">{{ __('instructor::dashboard.no_groups_title') }}</h3>
                <p class="text-muted mb-4 fs-6 pe-md-4">
                    {{ __('instructor::dashboard.no_groups_desc_extended') }}
                </p>
                <ul class="list-unstyled text-muted mb-4 text-start d-inline-block">
                    <li class="mb-2"><i class="fas fa-check-circle text-indigo me-2"></i> {{ __('instructor::dashboard.benefit_1') }}</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-indigo me-2"></i> {{ __('instructor::dashboard.benefit_2') }}</li>
                    <li><i class="fas fa-check-circle text-indigo me-2"></i> {{ __('instructor::dashboard.benefit_3') }}</li>
                </ul>
                <div class="d-block mt-2">
                    <a href="{{ route('instructor.groups.create') }}" class="btn btn-indigo px-4 py-3 rounded-pill fw-bold">
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
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#6366f1',
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
