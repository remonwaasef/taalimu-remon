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
    <div class="row mb-4 g-4" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
        <div class="col-6 col-md-3">
            <a href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? 'center']) }}" class="quick-action-btn shadow-sm">
                <i class="fas fa-user-plus text-primary"></i>
                <span class="fw-bold text-dark small">{{ __('center::sidebar.add_student') ?? 'إضافة طالب' }}</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? 'center']) }}" class="quick-action-btn shadow-sm">
                <i class="fas fa-calendar-check text-success"></i>
                <span class="fw-bold text-dark small">{{ __('center::sidebar.attendance') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('center.sales.account', ['tenant' => $tenant->domain ?? 'center']) }}" class="quick-action-btn shadow-sm">
                <i class="fas fa-file-invoice-dollar text-warning"></i>
                <span class="fw-bold text-dark small">{{ __('center::sidebar.student_accounts') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <div class="quick-action-btn shadow-sm bg-primary bg-opacity-10 border-primary border-opacity-25 h-100 d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-map-marker-alt text-primary mb-2"></i>
                <span class="fw-bold text-dark small text-center">{{ $tenant->name }}<br><small class="text-muted">({{ __('center::dashboard.main_branch') ?? 'الفرع الرئيسي' }})</small></span>
            </div>
        </div>
    </div>

    <!-- KPI Section -->
    <div class="row mb-4 g-4" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <div class="kpi-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="fw-extrabold mb-1">{{ number_format($activeStudents) }}</h3>
                    <p class="text-muted small fw-bold mb-0">{{ __('center::dashboard.models.Student') }}</p>
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">+12%</span>
                        <small class="text-muted" style="font-size: 0.7rem;">{{ __('center::dashboard.since_last_month') ?? 'منذ الشهر الماضي' }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <div class="kpi-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="fw-extrabold mb-1">{{ $sessionsToday }}</h3>
                    <p class="text-muted small fw-bold mb-0">{{ __('center::dashboard.sessions_today') }}</p>
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size: 0.7rem;">Active</span>
                        <small class="text-muted" style="font-size: 0.7rem;">{{ __('center::dashboard.running_now') ?? 'جاري الآن' }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body p-4 text-white">
                    <div class="kpi-icon glass-badge">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="fw-extrabold mb-1 text-white">{{ number_format($monthlyRevenue, 0) }}</h3>
                    <p class="text-white-50 small fw-bold mb-0">{{ __('center::dashboard.monthly_revenue_curr') }}</p>
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <span class="badge glass-badge rounded-pill px-2 py-1" style="font-size: 0.7rem;">+{{ get_currency_symbol() }}</span>
                        <small class="text-white-50" style="font-size: 0.7rem;">{{ __('center::dashboard.target_reached') ?? 'تم تحقيق الهدف' }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card dashboard-card h-100">
                <div class="card-body p-4">
                    <div class="kpi-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="fw-extrabold mb-1 text-danger">{{ number_format($overdueAmount, 0) }}</h3>
                    <p class="text-muted small fw-bold mb-0">{{ __('center::dashboard.overdue_amount') ?? 'متأخرات مالية' }}</p>
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <a href="{{ route('center.sales.overdue', ['tenant' => $tenant->domain ?? 'center']) }}" class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 text-decoration-none transition-all hover-shadow-sm">
                            متابعة التحصيل <i class="fas fa-arrow-left ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
        <!-- Main Chart Section -->
        <div class="col-lg-8">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">{{ __('center::dashboard.revenue_overview') }}</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light rounded-pill px-3" type="button" data-bs-toggle="dropdown">{{ __('center::dashboard.this_week') ?? 'هذا الأسبوع' }} <i class="fas fa-chevron-down ms-1 fa-xs"></i></button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="height: 300px; width: 100%; position: relative;">
                        <!-- Enhanced Mock Chart -->
                        <svg viewBox="0 0 400 150" class="w-100 h-100" style="overflow: visible;" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="premiumGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:rgba(16, 185, 129, 0.2);stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:rgba(16, 185, 129, 0);stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path d="M 0,110 Q 50,120 100,90 T 200,80 T 300,40 T 400,20 L 400,150 L 0,150 Z" fill="url(#premiumGrad)" />
                            <path d="M 0,110 Q 50,120 100,90 T 200,80 T 300,40 T 400,20" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" />
                            <circle cx="400" cy="20" r="5" fill="#10b981" stroke="#fff" stroke-width="2" />
                        </svg>
                    </div>
                    <div class="d-flex justify-content-between mt-3 text-muted small fw-bold">
                        <span>{{ __('center::dashboard.sunday') }}</span>
                        <span>{{ __('center::dashboard.monday') }}</span>
                        <span>{{ __('center::dashboard.tuesday') }}</span>
                        <span>{{ __('center::dashboard.wednesday') }}</span>
                        <span>{{ __('center::dashboard.thursday') }}</span>
                        <span>{{ __('center::dashboard.friday') }}</span>
                        <span>{{ __('center::dashboard.saturday') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities & Suggestions -->
        <div class="col-lg-4">
            <!-- AI Suggestions Bubble -->
            <div class="ai-bubble shadow-lg mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center me-2 ms-2" style="width: 32px; height: 32px;">
                        <i class="fas fa-robot text-white small"></i>
                    </div>
                    <h6 class="mb-0 text-white fw-bold">{{ __('center::dashboard.smart_suggestions') }}</h6>
                </div>
                <p class="text-white-50 small mb-2 fw-medium">
                    {{ __('center::dashboard.insights.low_engagement') }}
                </p>
                <div class="text-end">
                    <button class="btn btn-sm glass-badge rounded-pill px-3" style="font-size: 0.7rem;">{{ __('center::dashboard.details') ?? 'التفاصيل' }}</button>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card dashboard-card">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">{{ __('center::dashboard.recent_activities') }}</h6>
                    <a href="#" class="text-muted small text-decoration-none fw-bold">{{ __('center::dashboard.view_all') ?? 'الكل' }}</a>
                </div>
                <div class="card-body p-2">
                    @forelse(isset($recentActivities) ? $recentActivities->take(4) : collect([]) as $activity)
                    <div class="activity-item d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                            <i class="fas fa-bolt text-warning small"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="mb-0 text-dark small fw-bold text-truncate">{{ __('center::dashboard.actions.' . $activity->description) }}</h6>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 px-3">
                        <div class="mb-3">
                            <img src="{{ asset('assets/images/empty-state.svg') }}" alt="No activities" style="width: 80px; opacity: 0.6;">
                        </div>
                        <h6 class="text-dark fw-bold">{{ __('center::dashboard.no_activities') }}</h6>
                        <p class="text-muted small px-3">{{ __('center::dashboard.no_activities_desc') ?? 'لا توجد أنشطة مسجلة حالياً في هذا المركز. بمجرد قيام الموظفين أو المعلمين بأي إجراء، ستظهر التفاصيل هنا.' }}</p>
                    </div>
                    @endforelse
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
