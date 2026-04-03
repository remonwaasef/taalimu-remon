@extends('center::layouts.hope-master')

@section('content')
<style>
    :root {
        --dash-primary: #059669;
        --dash-primary-light: #ecfdf5;
        --dash-success: #059669;
        --dash-success-light: #ecfdf5;
        --dash-warning: #d97706;
        --dash-warning-light: #fffbeb;
        --dash-danger: #dc2626;
        --dash-danger-light: #fef2f2;
        --dash-info: #0284c7;
        --dash-info-light: #f0f9ff;
        --dash-gradient: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
        --dash-gradient-success: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --dash-card-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
        --dash-card-shadow-hover: 0 10px 25px rgba(0,0,0,0.08);
    }

    /* Welcome Banner - Premium Pastel */
    .welcome-banner {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 1.25rem;
        padding: 2rem 2.5rem;
        color: #0f172a;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0) 100%);
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0) 100%);
    }
    .welcome-banner h2 {
        color: #064e3b !important;
        font-weight: 800;
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .welcome-banner p {
        color: #065f46 !important;
        opacity: 0.8;
        font-size: 0.9rem;
        margin: 0;
    }
    .welcome-banner .welcome-stat {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(16,185,129,0.2);
        border-radius: 0.875rem;
        padding: 0.75rem 1.25rem;
        text-align: center;
        min-width: 120px;
    }
    .welcome-banner .welcome-stat .stat-num {
        font-size: 1.5rem;
        font-weight: 800;
        color: #064e3b;
        line-height: 1.2;
    }
    .welcome-banner .welcome-stat .stat-label {
        font-size: 0.7rem;
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #f1f5f9;
        box-shadow: var(--dash-card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--dash-card-shadow-hover);
    }
    .stat-card .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Quick Action Cards */
    .quick-action-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        border-radius: 1rem;
        background: white;
        border: 1px solid #f1f5f9;
        box-shadow: var(--dash-card-shadow);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        color: #0f172a !important;
        height: 100%;
    }
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--dash-card-shadow-hover);
        border-color: transparent;
    }
    .quick-action-card .action-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        transition: transform 0.3s;
    }
    .quick-action-card:hover .action-icon {
        transform: scale(1.1) rotate(-3deg);
    }
    .quick-action-card .action-text {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1e293b;
    }
    .quick-action-card .action-desc {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* Activity Feed */
    .activity-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #f1f5f9;
        box-shadow: var(--dash-card-shadow);
        overflow: hidden;
    }
    .activity-card .card-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .activity-card .card-header-custom h5 {
        font-weight: 800;
        font-size: 1rem;
        margin: 0;
        color: #0f172a;
    }
    .activity-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        transition: background 0.2s;
    }
    .activity-item:hover {
        background: #f8fafc;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-item .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    .activity-item .activity-content {
        flex: 1;
        min-width: 0;
    }
    .activity-item .activity-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: #1e293b;
        margin: 0;
    }
    .activity-item .activity-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0;
    }
    .activity-item .activity-time {
        font-size: 0.7rem;
        color: #cbd5e1;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .section-header h5 {
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin: 0;
    }
    .section-header .section-line {
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    /* Dashboard Tabs */
    .dashboard-tabs .nav-link {
        border-radius: 50rem;
        padding: 0.6rem 1.25rem;
        font-weight: 700;
        font-size: 0.8rem;
        color: #64748b;
        border: none;
        background: transparent;
        transition: all 0.25s;
    }
    .dashboard-tabs .nav-link.active {
        background: white;
        color: var(--dash-primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .dashboard-tabs .nav-link i {
        margin-inline-end: 0.375rem;
    }

    /* Status Pulse */
    .pulse-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 rgba(34, 197, 94, 0.4);
        animation: pulse-anim 2s infinite;
    }
    @keyframes pulse-anim {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    .empty-state .empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #cbd5e1;
        margin: 0 auto 1rem;
    }
    .empty-state p {
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Risk Badges */
    .risk-high { color: #dc2626; background: #fef2f2; }
    .risk-medium { color: #d97706; background: #fffbeb; }
    .risk-low { color: #22c55e; background: #ecfdf5; }

    /* Performance Bar */
    .perf-bar {
        height: 8px;
        border-radius: 4px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .perf-bar .perf-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 1s ease-out;
    }
</style>

<div class="container-fluid py-4">

    {{-- Welcome Banner --}}
    <div class="welcome-banner mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 position-relative" style="z-index: 1;">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="pulse-dot"></span>
                    <span style="font-size: 0.75rem; color: #065f46; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                        {{ __('center::dashboard.center_status') }}: {{ __('center::dashboard.healthy') }}
                    </span>
                </div>
                <h2>
                    {{ app()->isLocale('ar') ? 'مرحباً' : 'Welcome' }}, {{ auth()->user()->name }} 👋
                </h2>
                <p>{{ app()->isLocale('ar') ? 'إليك ملخص نشاط مركزك التعليمي اليوم' : 'Here is your center activity summary for today' }}</p>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div class="welcome-stat">
                    <div class="stat-num">{{ number_format($activeStudents) }}</div>
                    <div class="stat-label">{{ __('center::dashboard.active_students') }}</div>
                </div>
                <div class="welcome-stat">
                    <div class="stat-num">{{ number_format($monthlyRevenue, 0) }}</div>
                    <div class="stat-label">{{ __('center::dashboard.monthly_revenue') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <ul class="nav nav-pills dashboard-tabs bg-light p-1 rounded-pill mb-4 d-inline-flex" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                <i class="fas fa-home"></i> {{ __('center::dashboard.overview') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                <i class="fas fa-chart-pie"></i> {{ __('center::dashboard.advanced_analytics') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="dashboardTabsContent">

        {{-- ============= Tab 1: Overview ============= --}}
        <div class="tab-pane fade show active" id="overview" role="tabpanel">

            {{-- Quick Actions --}}
            <div class="section-header">
                <h5>{{ __('center::dashboard.quick_actions') }}</h5>
                <div class="section-line"></div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <a href="{{ route('center.students.create') }}" class="quick-action-card">
                        <div class="action-icon" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <div class="action-text">{{ __('center::dashboard.add_student') }}</div>
                            <div class="action-desc">{{ app()->isLocale('ar') ? 'طالب جديد' : 'New student' }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3">
                    <a href="{{ route('center.attendance.index') }}" class="quick-action-card">
                        <div class="action-icon" style="background: var(--dash-success-light); color: var(--dash-success);">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <div class="action-text">{{ __('center::dashboard.record_attendance') }}</div>
                            <div class="action-desc">{{ app()->isLocale('ar') ? 'تسجيل يومي' : 'Daily record' }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3">
                    <a href="{{ route('center.sales.create') }}" class="quick-action-card">
                        <div class="action-icon" style="background: var(--dash-warning-light); color: var(--dash-warning);">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <div class="action-text">{{ __('center::dashboard.collect_fees') }}</div>
                            <div class="action-desc">{{ app()->isLocale('ar') ? 'تحصيل رسوم' : 'Collect fees' }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-lg-3">
                    <a href="{{ route('center.students.index') }}" class="quick-action-card">
                        <div class="action-icon" style="background: var(--dash-info-light); color: var(--dash-info);">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <div class="action-text">{{ __('center::dashboard.search_student') }}</div>
                            <div class="action-desc">{{ app()->isLocale('ar') ? 'بحث سريع' : 'Quick search' }}</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="section-header">
                <h5>{{ app()->isLocale('ar') ? 'نظرة سريعة' : 'At a Glance' }}</h5>
                <div class="section-line"></div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($activeStudents) }}</div>
                        <div class="stat-label">{{ __('center::dashboard.active_students') }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: var(--dash-success-light); color: var(--dash-success);">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($monthlyRevenue, 0) }}</div>
                        <div class="stat-label">{{ __('center::dashboard.monthly_revenue') }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: var(--dash-info-light); color: var(--dash-info);">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="stat-value">--</div>
                        <div class="stat-label">{{ __('center::dashboard.todays_sessions') }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: var(--dash-warning-light); color: var(--dash-warning);">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="stat-value">{{ number_format($netProfit, 0) }}</div>
                        <div class="stat-label">{{ __('center::dashboard.net_profit') }}</div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="section-header">
                <h5>{{ __('center::dashboard.recent_activities') }}</h5>
                <div class="section-line"></div>
            </div>
            <div class="activity-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-bolt text-warning me-2"></i>{{ __('center::dashboard.recent_activities') }}</h5>
                </div>
                @forelse($recentActivities->take(7) as $activity)
                    <div class="activity-item">
                        <div class="activity-icon"
                             style="background: {{ $loop->index % 4 == 0 ? 'var(--dash-primary-light)' : ($loop->index % 4 == 1 ? 'var(--dash-success-light)' : ($loop->index % 4 == 2 ? 'var(--dash-warning-light)' : 'var(--dash-info-light)')) }};
                                    color: {{ $loop->index % 4 == 0 ? 'var(--dash-primary)' : ($loop->index % 4 == 1 ? 'var(--dash-success)' : ($loop->index % 4 == 2 ? 'var(--dash-warning)' : 'var(--dash-info)')) }};">
                            <i class="fas fa-{{ $loop->index % 4 == 0 ? 'bolt' : ($loop->index % 4 == 1 ? 'check' : ($loop->index % 4 == 2 ? 'edit' : 'info')) }}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">{{ __('center::dashboard.actions.' . $activity->description) }}</p>
                            <p class="activity-meta">
                                {{ $activity->causer ? $activity->causer->name : __('center::dashboard.system') }}
                                • <span class="badge bg-light text-muted fw-normal" style="font-size:0.65rem;">{{ __('center::dashboard.models.' . class_basename($activity->subject_type)) }}</span>
                            </p>
                        </div>
                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                        <p>{{ __('center::dashboard.no_activities') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ============= Tab 2: Analytics ============= --}}
        <div class="tab-pane fade" id="analytics" role="tabpanel">

            <div class="alert border-0 rounded-4 d-flex align-items-center mb-4" style="background: var(--dash-primary-light); color: var(--dash-primary);">
                <i class="fas fa-robot fa-2x me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1" style="color: var(--dash-primary);">{{ __('center::dashboard.smart_analytics_center') }}</h6>
                    <p class="mb-0 small" style="color: #64748b;">{{ __('center::dashboard.financial_reports_ai') }}</p>
                </div>
            </div>

            {{-- Financial Metrics --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-4 col-md-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: var(--dash-success-light); color: var(--dash-success);">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="stat-label mb-1">{{ __('center::dashboard.monthly_revenue') }}</div>
                        <div class="stat-value">{{ number_format($monthlyRevenue, 2) }}</div>
                        <div class="mt-2 small">
                            <span class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i> 8.4%</span>
                            <span class="text-muted ms-1">{{ __('center::dashboard.currency') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="stat-card" style="background: var(--dash-gradient); border-color: transparent;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: rgba(255,255,255,0.15); color: white;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="stat-label mb-1" style="color: rgba(255,255,255,0.7);">{{ __('center::dashboard.net_profit') }}</div>
                        <div class="stat-value" style="color: white;">{{ number_format($netProfit, 2) }}</div>
                        <div class="mt-2 small">
                            <span style="color: rgba(255,255,255,0.8);"><i class="fas fa-piggy-bank me-1"></i> {{ __('center::dashboard.projected') }}: +5%</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon-wrapper" style="background: var(--dash-danger-light); color: var(--dash-danger);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stat-label mb-1">{{ app()->isLocale('ar') ? 'طلاب معرّضون للخطر' : 'At-Risk Students' }}</div>
                        <div class="stat-value">{{ count($atRiskStudents) }}</div>
                        <div class="mt-2 small">
                            <span class="text-danger fw-semibold"><i class="fas fa-arrow-up me-1"></i> 2.1%</span>
                            <span class="text-muted ms-1">{{ __('center::dashboard.currency') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                {{-- AI Early Warning --}}
                <div class="col-xl-7">
                    <div class="activity-card h-100">
                        <div class="card-header-custom">
                            <h5><i class="fas fa-bolt text-warning me-2"></i> {{ __('center::dashboard.ai_early_warning') }}</h5>
                            <a href="#" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3" style="font-size:0.75rem;">
                                {{ __('center::dashboard.view_all_risks') }}
                            </a>
                        </div>
                        <div class="p-3">
                            <p class="text-muted small mb-3 px-2">{{ __('center::dashboard.at_risk_students') }}</p>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted small text-uppercase" style="font-size:0.7rem;">
                                            <th>{{ __('center::dashboard.student') }}</th>
                                            <th>{{ __('center::dashboard.risk_level') }}</th>
                                            <th>{{ __('center::dashboard.observed_pattern') }}</th>
                                            <th>{{ __('center::dashboard.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($atRiskStudents as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex align-items-center justify-content-center fw-bold rounded-circle me-2"
                                                             style="width:32px; height:32px; font-size:10px; background: var(--dash-primary-light); color: var(--dash-primary);">
                                                            {{ mb_substr($student['name'], 0, 2) }}
                                                        </div>
                                                        <span class="fw-semibold" style="font-size:0.85rem;">{{ $student['name'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge risk-{{ $student['risk_level'] }} rounded-pill px-3" style="font-size:0.7rem;">
                                                        {{ __('center::dashboard.risk_levels.' . $student['risk_level']) }}
                                                    </span>
                                                </td>
                                                <td><small class="text-muted">{{ $student['reason'] }}</small></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size:0.75rem;">{{ __('center::dashboard.contact') }}</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr class="my-3 opacity-10">

                            <h6 class="fw-bold mb-3 px-2" style="font-size:0.85rem;"><i class="fas fa-lightbulb text-info me-2"></i> {{ __('center::dashboard.ai_insights') }}</h6>
                            @foreach($aiInsights as $insight)
                                <div class="alert alert-{{ $insight['type'] }} border-0 rounded-3 mb-2 py-2 px-3">
                                    <i class="fas fa-{{ $insight['type'] == 'warning' ? 'exclamation-triangle' : ($insight['type'] == 'success' ? 'check-circle' : 'info-circle') }} me-2"></i>
                                    <small class="fw-medium">{{ $insight['text'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Performance Trend --}}
                <div class="col-xl-5">
                    <div class="activity-card h-100">
                        <div class="card-header-custom">
                            <h5><i class="fas fa-chart-area text-info me-2"></i> {{ __('center::dashboard.performance_trend') }}</h5>
                        </div>
                        <div class="p-4 d-flex flex-column justify-content-center">
                            <div class="py-3">
                                <svg viewBox="0 0 400 150" class="w-100 h-auto">
                                    <defs>
                                        <linearGradient id="chartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:rgba(5, 150, 105, 0.3);stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:rgba(5, 150, 105, 0);stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    @php
                                        $trendsData = $performanceTrends['data'];
                                        $isDemo = $performanceTrends['is_demo'] ?? false;
                                        $step = 400 / max(count($trendsData) - 1, 1);
                                        $opacity = $isDemo ? 0.3 : 1;
                                        $strokeColor = $isDemo ? '#94a3b8' : '#059669';

                                        $pathD = "M 0,100";
                                        foreach($trendsData as $i => $val) {
                                            $y = 150 - ($val * 1.5);
                                            $x = $i * $step;
                                            if ($i == 0) continue;
                                            $pathD .= " L $x,$y";
                                        }
                                    @endphp

                                    @if($isDemo)
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#94a3b8" font-size="14" font-weight="bold" opacity="0.8">
                                            {{ __('center::dashboard.no_activities') }} ({{ __('center::dashboard.projected') }})
                                        </text>
                                    @endif

                                    <path d="{{ $pathD }}" fill="none" stroke="{{ $strokeColor }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="{{ $isDemo ? '5,5' : '0' }}" opacity="{{ $opacity }}" />
                                    <path d="{{ $pathD }} L 400,150 L 0,150 Z" fill="url(#chartGrad)" opacity="{{ $isDemo ? 0.1 : 1 }}" />

                                    @if(!$isDemo)
                                        @foreach($trendsData as $i => $val)
                                            <circle cx="{{ $i * $step }}" cy="{{ 150 - ($val * 1.5) }}" r="4" fill="{{ $strokeColor }}" />
                                        @endforeach
                                    @endif
                                </svg>
                            </div>
                            <div class="d-flex justify-content-between mt-2 text-muted small fw-semibold">
                                <span>{{ app()->isLocale('ar') ? 'أحد' : 'Mon' }}</span>
                                <span>{{ app()->isLocale('ar') ? 'إثنين' : 'Tue' }}</span>
                                <span>{{ app()->isLocale('ar') ? 'ثلاثاء' : 'Wed' }}</span>
                                <span>{{ app()->isLocale('ar') ? 'أربعاء' : 'Thu' }}</span>
                                <span>{{ app()->isLocale('ar') ? 'خميس' : 'Fri' }}</span>
                                <span>{{ app()->isLocale('ar') ? 'جمعة' : 'Sat' }}</span>
                                <span>{{ app()->isLocale('ar') ? 'سبت' : 'Sun' }}</span>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-muted">{{ __('center::dashboard.course_completion_rate') }}</span>
                                    <span class="small fw-bold" style="color: var(--dash-primary);">78%</span>
                                </div>
                                <div class="perf-bar">
                                    <div class="perf-fill" style="width: 78%; background: var(--dash-gradient);"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
