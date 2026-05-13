@extends('center::layouts.hope-master')
@section('page-title', auth()->user()->name . ' 👋')
@section('page-subtitle', app()->isLocale('ar') ? 'إدارة مركزك بشكل أسهل من أي وقت مضى' : 'Manage your center easier than ever')

@section('content')
<style>
    /* Premium Dashboard Styles */
    .dashboard-card {
        border-radius: 20px !important;
        border: 1px solid rgba(0,0,0,0.04) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.05) !important;
    }
    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }
    .quick-action-btn {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s ease;
        text-decoration: none !important;
        display: block;
    }
    .quick-action-btn:hover {
        background: #f8fafc;
        border-color: #10b981;
        transform: scale(1.02);
    }
    .quick-action-btn i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    .activity-item {
        padding: 1rem;
        border-radius: 12px;
        transition: background 0.2s ease;
    }
    .activity-item:hover {
        background: #f8fafc;
    }
    .glass-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }
    .ai-bubble {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 20px 20px 0 20px;
        padding: 1.25rem;
        position: relative;
    }
    [dir="ltr"] .ai-bubble {
        border-radius: 20px 20px 20px 0;
    }
    .skeleton { background: #e2e8f0; border-radius: 8px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
    .skeleton-stat { background: #fff; padding: 1.5rem; border-radius: 20px; }
    .skeleton-icon { width: 48px; height: 48px; margin-bottom: 1rem; }
    .skeleton-number { width: 80%; height: 30px; margin-bottom: 10px; }
    .skeleton-label { width: 60%; height: 15px; }
</style>

<div id="dashboard-skeleton" class="container-fluid py-4">
    <div class="row g-4 mb-4">
        @for($i=0; $i<4; $i++)
        <div class="col-md-6 col-xl-3">
            <div class="skeleton-stat">
                <div class="skeleton skeleton-icon"></div>
                <div class="skeleton skeleton-number"></div>
                <div class="skeleton skeleton-label"></div>
            </div>
        </div>
        @endfor
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="skeleton" style="height: 400px;"></div>
        </div>
        <div class="col-lg-4">
            <div class="skeleton mb-4" style="height: 180px;"></div>
            <div class="skeleton" style="height: 200px;"></div>
        </div>
    </div>
</div>

<div class="container-fluid py-4" id="dashboard-main-content" style="display: none; opacity: 0; transition: opacity 0.5s ease;">
    {{-- Premium Hero Section --}}
    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden position-relative" style="background: #ffffff;">
        <div class="position-absolute top-0 end-0 h-100 w-50" style="background: linear-gradient(90deg, rgba(16,185,129,0) 0%, rgba(16,185,129,0.05) 100%); pointer-events: none;"></div>
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px;">
                    <i class="fas fa-hand-sparkles fa-lg"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">مرحباً بك، {{ auth()->user()->name ?? 'مدير المركز' }} 👋</h4>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                        <i class="fas fa-building ms-1 text-black-50"></i> <span class="fw-medium text-dark">{{ $tenant->name }}</span>
                        <span class="mx-2 text-black-50">|</span> 
                        <i class="far fa-calendar-alt ms-1 text-black-50"></i> {{ now()->translatedFormat('l، d F Y') }}
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-success rounded-pill px-4 py-2 shadow-sm fw-bold d-inline-flex align-items-center transition-all hover-shadow">
                    <i class="fas fa-plus me-2"></i> {{ __('center::sidebar.add_student') ?? 'طالب جديد' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="row mb-4 g-4" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-2 text-uppercase tracking-wider">{{ __('center::dashboard.models.Student') }}</p>
                            <h2 class="fw-extrabold mb-0">{{ number_format($activeStudents) }}</h2>
                        </div>
                        <div class="kpi-icon bg-success bg-opacity-10 text-success m-0">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-2 text-uppercase tracking-wider">{{ __('center::dashboard.sessions_today') }}</p>
                            <h2 class="fw-extrabold mb-0">{{ $sessionsToday }}</h2>
                        </div>
                        <div class="kpi-icon bg-primary bg-opacity-10 text-primary m-0">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-2 text-uppercase tracking-wider">{{ __('center::dashboard.monthly_revenue_curr') }}</p>
                            <h2 class="fw-extrabold mb-0 text-success">{{ number_format($monthlyRevenue, 0) }}</h2>
                        </div>
                        <div class="kpi-icon bg-success bg-opacity-10 text-success m-0">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold mb-2 text-uppercase tracking-wider">{{ __('center::dashboard.overdue_amount') ?? 'متأخرات' }}</p>
                            <h2 class="fw-extrabold mb-0 text-danger">{{ number_format($overdueAmount, 0) }}</h2>
                        </div>
                        <div class="kpi-icon bg-danger bg-opacity-10 text-danger m-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
        {{-- Quick Actions --}}
        <div class="col-lg-12">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0 text-dark">{{ __('center::dashboard.quick_actions') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-outline-light text-dark w-100 py-3 border rounded-4 shadow-none">
                                <i class="fas fa-calendar-check text-success mb-2 d-block fa-lg"></i>
                                <span class="fw-bold small">{{ __('center::sidebar.attendance') }}</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('center.sales.account', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-outline-light text-dark w-100 py-3 border rounded-4 shadow-none">
                                <i class="fas fa-file-invoice-dollar text-warning mb-2 d-block fa-lg"></i>
                                <span class="fw-bold small">{{ __('center::sidebar.student_accounts') }}</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('center.expenses.index', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-outline-light text-dark w-100 py-3 border rounded-4 shadow-none">
                                <i class="fas fa-calculator text-danger mb-2 d-block fa-lg"></i>
                                <span class="fw-bold small">{{ __('center::sidebar.expenses') }}</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('center.settings.index', ['tenant' => $tenant->domain ?? 'center']) }}" class="btn btn-outline-light text-dark w-100 py-3 border rounded-4 shadow-none">
                                <i class="fas fa-cog text-secondary mb-2 d-block fa-lg"></i>
                                <span class="fw-bold small">{{ __('center::sidebar.settings') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="col-lg-12">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">{{ __('center::dashboard.recent_activities') }}</h6>
                    <a href="#" class="text-muted small text-decoration-none fw-bold">{{ __('center::dashboard.view_all') ?? 'الكل' }}</a>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @forelse(isset($recentActivities) ? $recentActivities->take(6) : collect([]) as $activity)
                        <div class="col-md-6 mb-3">
                            <div class="activity-item d-flex align-items-center gap-3 border rounded-3 p-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                    <i class="fas fa-bolt text-warning small"></i>
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <h6 class="mb-0 text-dark small fw-bold text-truncate">{{ __('center::dashboard.actions.' . $activity->description) }}</h6>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted small">{{ __('center::dashboard.no_activities') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simulate a small delay for smooth skeleton transition
        setTimeout(() => {
            const skeleton = document.getElementById('dashboard-skeleton');
            const content = document.getElementById('dashboard-main-content');
            
            if (skeleton && content) {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                setTimeout(() => {
                    content.style.opacity = '1';
                }, 50);
            }
        }, 800);
    });
</script>
@endpush
@endsection
