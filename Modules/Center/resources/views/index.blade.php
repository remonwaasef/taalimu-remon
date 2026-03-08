@extends('center::layouts.master')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .ai-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .risk-high { color: #ff4757; background: rgba(255, 71, 87, 0.1); }
    .risk-medium { color: #ffa502; background: rgba(255, 165, 2, 0.1); }
    .risk-low { color: #2ed573; background: rgba(46, 213, 115, 0.1); }

    .performance-bar {
        height: 8px;
        border-radius: 4px;
        background: #f1f2f6;
        overflow: hidden;
    }

    .pulse {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2ed573;
        box-shadow: 0 0 0 rgba(46, 213, 115, 0.4);
        animation: pulse-animation 2s infinite;
        margin-right: 5px;
    }
    
    /* Quick Action Buttons */
    .btn-quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        border: 0;
        border-radius: 1rem;
        background: white;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
        height: 100%;
        width: 100%;
        color: var(--text-dark);
        text-decoration: none;
    }
    .btn-quick-action:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        color: var(--primary-color);
    }
    .btn-quick-action i {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }
    
    /* Tabs Styling */
    .dashboard-tabs .nav-link {
        border-radius: 50rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: var(--text-muted);
        border: none;
        background: transparent;
        transition: all 0.2s;
    }
    .dashboard-tabs .nav-link.active {
        background: var(--bg-white);
        color: var(--primary-color);
        box-shadow: var(--shadow-sm);
    }

    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0 rgba(46, 213, 115, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(46, 213, 115, 0); }
        100% { box-shadow: 0 0 0 0 rgba(46, 213, 115, 0); }
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                {{ auth()->user()->tenant && auth()->user()->tenant->type === 'instructor' ? __('center::dashboard.tutor_dashboard') : __('center::dashboard.center_dashboard') }} 
            </h3>
            <p class="text-muted small mb-0">
                <span class="pulse"></span> 
                {{ auth()->user()->tenant && auth()->user()->tenant->type === 'instructor' ? __('center::dashboard.tutor_status') : __('center::dashboard.center_status') }}: <span class="text-success fw-semibold">{{ __('center::dashboard.healthy') }}</span>
            </p>
        </div>
        
        <!-- Tabs Navigation -->
        <ul class="nav nav-pills dashboard-tabs bg-light p-1 rounded-pill" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-home me-2"></i> {{ __('center::dashboard.overview') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                    <i class="fas fa-chart-pie me-2"></i> {{ __('center::dashboard.advanced_analytics') }}
                </button>
            </li>
        </ul>
    </div>

    <!-- Launchpad Widget (Only if setup incomplete) -->
    @if($launchpadProgress < 100)
        @include('center::partials.launchpad')
    @endif

    <div class="tab-content" id="dashboardTabsContent">
        
        <!-- Tab 1: Daily Overview (Simplified) -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            
            <!-- Quick Actions -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3 small text-uppercase text-muted">{{ __('center::dashboard.quick_actions') }}</h5>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('center.students.create') }}" class="btn-quick-action">
                        <i class="fas fa-user-plus text-primary"></i>
                        <span class="fw-bold">{{ __('center::dashboard.add_student') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('center.attendance.index') }}" class="btn-quick-action">
                        <i class="fas fa-clipboard-check text-success"></i>
                        <span class="fw-bold">{{ __('center::dashboard.record_attendance') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('center.sales.create') }}" class="btn-quick-action">
                        <i class="fas fa-receipt text-warning"></i>
                        <span class="fw-bold">{{ __('center::dashboard.collect_fees') }}</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('center.students.index') }}" class="btn-quick-action">
                        <i class="fas fa-search text-info"></i>
                        <span class="fw-bold">{{ __('center::dashboard.search_student') }}</span>
                    </a>
                </div>
            </div>

            <!-- Key Daily Metrics -->
            <div class="row g-4 mb-4">
                <div class="col-xl-6">
                    <div class="card glass-card border-0 rounded-4 h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-opacity-10 bg-primary text-primary mb-0 me-3">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">{{ __('center::dashboard.active_students') }}</h6>
                                <h2 class="fw-bold mb-0">{{ number_format($activeStudents) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card glass-card border-0 rounded-4 h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-opacity-10 bg-success text-success mb-0 me-3">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">{{ __('center::dashboard.todays_sessions') }}</h6>
                                <h2 class="fw-bold mb-0">--</h2> <!-- Placeholder for Today's sessions count layout -->
                                <small class="text-muted">{{ __('center::dashboard.scheduled_session') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity List (Simplified) -->
            <div class="row">
                <div class="col-12">
                    <div class="card glass-card border-0 rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">{{ __('center::dashboard.recent_activities') }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush border-0">
                                @forelse($recentActivities->take(5) as $activity)
                                    <div class="list-group-item bg-transparent border-0 px-4 py-3 border-bottom border-light">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-sm bg-light-primary rounded-circle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-bolt"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-0 fw-semibold text-dark">{{ __('center::dashboard.actions.' . $activity->description) }}</h6>
                                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $activity->causer ? $activity->causer->name : __('center::dashboard.system') }} 
                                                    • <span class="badge bg-light text-muted fw-normal">{{ __('center::dashboard.models.' . class_basename($activity->subject_type)) }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <p class="text-muted">{{ __('center::dashboard.no_activities') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Analytics & AI (Advanced - Original Content) -->
        <div class="tab-pane fade" id="analytics" role="tabpanel">
            
            <div class="alert alert-soft-primary border-0 rounded-4 d-flex align-items-center mb-4">
                <i class="fas fa-robot fa-2x me-3 text-primary"></i>
                <div>
                    <h6 class="fw-bold mb-1">{{ __('center::dashboard.smart_analytics_center') }}</h6>
                    <p class="mb-0 small text-muted">{{ __('center::dashboard.financial_reports_ai') }}</p>
                </div>
            </div>

            <!-- Core Metrics (Financial) -->
            <div class="row g-4 mb-4">
                <div class="col-xl-4 col-md-6">
                    <div class="card glass-card border-0 rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="stat-icon" style="background: rgba(67, 233, 123, 0.1); color: #43e97b;">
                                <i class="fas fa-coins fa-lg"></i>
                            </div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">{{ __('center::dashboard.monthly_revenue') }}</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($monthlyRevenue, 2) }}</h2>
                            <div class="mt-2 small">
                                <span class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i> 8.4%</span> 
                                <span class="text-muted ms-1">{{ __('center::dashboard.currency') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card glass-card border-0 rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="stat-icon" style="background: rgba(240, 147, 251, 0.1); color: #f093fb;">
                                <i class="fas fa-wallet fa-lg"></i>
                            </div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">{{ __('center::dashboard.monthly_expenses') }}</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($monthlyExpenses, 2) }}</h2>
                            <div class="mt-2 small">
                                <span class="text-danger fw-semibold"><i class="fas fa-arrow-up me-1"></i> 2.1%</span> 
                                <span class="text-muted ms-1">{{ __('center::dashboard.currency') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card glass-card border-0 rounded-4 h-100 bg-primary text-white">
                        <div class="card-body p-4">
                            <div class="stat-icon bg-white bg-opacity-20 text-white">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                            <h6 class="text-white text-opacity-75 small fw-bold text-uppercase mb-1">{{ __('center::dashboard.net_profit') }}</h6>
                            <h2 class="fw-bold mb-0 text-white">{{ number_format($netProfit, 2) }}</h2>
                            <div class="mt-2 small">
                                <span class="text-white text-opacity-90"><i class="fas fa-piggy-bank me-1"></i> {{ __('center::dashboard.projected') }}: +5%</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- AI Early Warning -->
                <div class="col-xl-7">
                    <div class="card glass-card border-0 rounded-4 h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-bolt text-warning me-2"></i> {{ __('center::dashboard.ai_early_warning') }}
                            </h5>
                            <a href="#" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">
                                {{ __('center::dashboard.view_all_risks') }}
                            </a>
                        </div>
                        <div class="card-body px-4">
                            <p class="text-muted small mb-4">{{ __('center::dashboard.at_risk_students') }}</p>
                            
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <thead>
                                        <tr class="text-muted small text-uppercase">
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
                                                        <div class="avatar avatar-sm bg-light rounded-circle text-primary me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 10px;">
                                                            {{ substr($student['name'], 0, 2) }}
                                                        </div>
                                                        <span class="fw-semibold">{{ $student['name'] }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge risk-{{ $student['risk_level'] }} rounded-pill px-3">
                                                        {{ __('center::dashboard.risk_levels.' . $student['risk_level']) }}
                                                    </span>
                                                </td>
                                                <td><small class="text-muted">{{ $student['reason'] }}</small></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3">{{ __('center::dashboard.contact') }}</button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr class="my-4 opacity-10">
                            
                            <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb text-info me-2"></i> {{ __('center::dashboard.ai_insights') }}</h6>
                            @foreach($aiInsights as $insight)
                                <div class="alert alert-{{ $insight['type'] }} border-0 rounded-3 mb-2 py-2 px-3">
                                    <i class="fas fa-{{ $insight['type'] == 'warning' ? 'exclamation-triangle' : ($insight['type'] == 'success' ? 'check-circle' : 'info-circle') }} me-2"></i>
                                    <small class="fw-medium">{{ $insight['text'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Performance Trend -->
                <div class="col-xl-5">
                    <div class="card glass-card border-0 rounded-4 h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">{{ __('center::dashboard.performance_trend') }}</h5>
                        </div>
                        <div class="card-body px-4 d-flex flex-column justify-content-center">
                            <!-- Simple SVG Chart Prototype -->
                            <div class="py-4">
                                <svg viewBox="0 0 400 150" class="w-100 h-auto">
                                    <defs>
                                        <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:rgba(102, 126, 234, 0.4);stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:rgba(102, 126, 234, 0);stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    @php 
                                        $trendsData = $performanceTrends['data'];
                                        $isDemo = $performanceTrends['is_demo'] ?? false;
                                        $step = 400 / (count($trendsData) - 1); 
                                        $opacity = $isDemo ? 0.3 : 1;
                                        $strokeColor = $isDemo ? '#94a3b8' : '#667eea';
                                        
                                        // Calculate path d attribute
                                        $pathD = "M 0,100";
                                        foreach($trendsData as $i => $val) {
                                            $y = 150 - ($val * 1.5);
                                            $x = $i * $step;
                                            if ($i == 0) continue; // Start point already defined
                                            
                                            // Simple curve approximation (bezier could be better but this works for demo)
                                            $pathD .= " L $x,$y";
                                        }
                                    @endphp

                                    @if($isDemo)
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#94a3b8" font-size="14" font-weight="bold" opacity="0.8">
                                            {{ __('center::dashboard.no_activities') }} ({{ __('center::dashboard.projected') }})
                                        </text>
                                    @endif

                                    <path d="{{ $pathD }}" fill="none" stroke="{{ $strokeColor }}" stroke-width="3" stroke-dasharray="{{ $isDemo ? '5,5' : '0' }}" opacity="{{ $opacity }}" />
                                    <path d="{{ $pathD }} L 400,150 L 0,150 Z" fill="url(#chartGradient)" opacity="{{ $isDemo ? 0.1 : 1 }}" />
                                    
                                    @if(!$isDemo)
                                        @foreach($trendsData as $i => $val)
                                            <circle cx="{{ $i * $step }}" cy="{{ 150 - ($val * 1.5) }}" r="4" fill="#667eea" />
                                        @endforeach
                                    @endif
                                </svg>
                            </div>
                            <div class="d-flex justify-content-between mt-3 text-muted small">
                                <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                            </div>
                            
                            <div class="mt-auto pt-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold text-muted">{{ __('center::dashboard.course_completion_rate') }}</span>
                                    <span class="small fw-bold">78%</span>
                                </div>

                                <div class="performance-bar">
                                    <div class="progress-bar bg-primary h-100" style="width: 78%"></div>
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
