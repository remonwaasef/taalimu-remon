@extends('center::layouts.hope-master')
@section('page-title', auth()->user()->name . ' 👋')
@section('page-subtitle', app()->isLocale('ar') ? 'إدارة مركزك بشكل أسهل من أي وقت مضى' : 'Manage your center easier than ever')

@section('content')
<style>
/* AI Insights Block */
.ai-insights-block {
    background: #f8fafc;
    border-radius: 1.25rem;
    padding: 1.5rem;
    border: 1px solid #f1f5f9;
}
.stat-box { 
    background: white; 
    border: 1px solid #f1f5f9; 
    border-radius: 1rem; 
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
}
</style>

<div class="container-fluid">
    <!-- Top Greeting Container Removed as it moved to Banner -->


    <!-- Green Health Card + 3 Stats -->
    <div class="card shadow-sm mb-4" style="background-color: #f8fafc; border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 1.5rem;">
        <div class="card-body p-4">
            <div class="row align-items-center" dir="rtl">
                <!-- Validation & Title (Right side in RTL) -->
                <div class="col-lg-5 text-start order-1 order-lg-2 ms-auto d-flex justify-content-end align-items-center gap-4 mb-4 mb-lg-0">
                    <div class="text-end">
                        <h4 class="fw-bold" style="color: #064e3b;">إجابة المركز ممتازة</h4>
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">حالة المركز جيدة، لا توجد مدفوعات متأخرة</p>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; background-color: #10b981; color: white; box-shadow: 0 4px 10px rgba(16,185,129,0.3);">
                         <i class="fas fa-check fa-xl"></i>
                    </div>
                </div>

                <!-- 3 Stats (Left side in RTL) -->
                <div class="col-lg-7 order-2 order-lg-1">
                    <div class="d-flex justify-content-lg-start justify-content-center gap-3 w-100 flex-wrap flex-md-nowrap">
                        <div class="stat-box flex-grow-1 text-center p-3">
                            <h3 class="fw-bold text-dark mb-2">{{ number_format($activeStudents) }} <i class="fas fa-arrow-up text-success fs-6 align-middle ms-1"></i></h3>
                            <span class="text-muted small fw-semibold" style="background: #ecfdf5; padding: 4px 12px; border-radius: 6px;"><i class="fas fa-user-graduate me-1 text-success"></i> الطلاب</span>
                        </div>
                        <div class="stat-box flex-grow-1 text-center p-3">
                            <h3 class="fw-bold text-dark mb-2">-- <i class="fas fa-arrow-up text-warning fs-6 align-middle ms-1"></i></h3>
                            <span class="text-muted small fw-semibold" style="background: #f0f9ff; padding: 4px 12px; border-radius: 6px;"><i class="fas fa-calendar-alt me-1 text-info"></i> جلسات اليوم</span>
                        </div>
                        <div class="stat-box flex-grow-1 text-center p-3">
                            <h3 class="fw-bold text-dark mb-2">{{ number_format($monthlyRevenue, 0) }}</h3>
                            <span class="text-muted small fw-semibold" style="background: #fef2f2; padding: 4px 12px; border-radius: 6px;"><i class="fas fa-wallet me-1 text-danger"></i> إيرادات الشهر</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Array -->
    <div class="row g-4" dir="rtl">
        
        <!-- Right Column (In RTL this defaults to Right): Activities & Smart insights -->
        <div class="col-lg-5">
            
            <!-- Activities Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1.5rem;">
                <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <button class="btn btn-sm btn-light rounded-3 px-3"><i class="fas fa-sliders-h text-muted"></i></button>
                    <h5 class="fw-bold mb-0 text-dark">أحدث النشاطات</h5>
                </div>
                <div class="card-body px-4 py-3">
                    @forelse(isset($recentActivities) ? $recentActivities->take(3) : collect([]) as $activity)
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-light pb-3">
                        <div class="d-flex align-items-center gap-3 w-100 justify-content-between">
                            <!-- Time -->
                            <div class="text-start" style="width: 60px;">
                                <span class="text-muted small" dir="ltr"><i class="fas fa-arrow-left fa-sm text-secondary me-1"></i> {{ $activity->created_at->diffInHours() }}h</span>
                            </div>
                            <!-- Detail -->
                            <div class="text-end flex-grow-1 px-3">
                                <h6 class="mb-1 fw-bold text-dark">{{ __('center::dashboard.actions.' . $activity->description) }}</h6>
                                <small class="text-muted fw-medium">{{ $activity->causer ? $activity->causer->name : 'النظام' }}</small>
                            </div>
                            <!-- Avatar placeholder -->
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 45px; height: 45px; background: #f0f9ff; color: #0284c7;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted fw-medium">لا توجد نشاطات مسجلة مؤخراً</div>
                    @endforelse
                    
                    <div class="text-center mt-3 mb-1">
                        <a href="#" class="text-muted text-decoration-none small fw-bold"><i class="fas fa-search me-1"></i> عرض جميع النشاطات</a>
                    </div>
                </div>
            </div>

            <!-- Overview Input box -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1.5rem;">
                <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                    <button class="btn btn-sm btn-light rounded-3 px-3"><i class="fas fa-expand text-secondary"></i></button>
                    <h5 class="fw-bold mb-0 text-dark">نظرة عامة</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-1">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 py-3 rounded-end-pill fw-medium" placeholder="ابحث السجل..." dir="rtl">
                        <span class="input-group-text bg-light border-0 rounded-start-pill text-success px-4"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>

            <!-- AI Insights Box -->
            <div class="ai-insights-block text-end">
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <h5 class="fw-bold mb-0 me-3" style="color: #0f172a;">اقتراحات ذكية</h5>
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-robot text-success"></i>
                    </div>
                </div>
                
                <div class="mb-4">
                    @forelse(isset($aiInsights) ? $aiInsights : [] as $insight)
                        <p class="small text-muted mb-3 d-flex justify-content-end align-items-start gap-2 fw-medium">
                            <span class="text-end">{{ $insight['text'] }}</span>
                            <span style="color: #cbd5e1; flex-shrink: 0;">••</span>
                        </p>
                    @empty
                        <p class="small text-muted mb-3 d-flex justify-content-end align-items-start gap-2 fw-medium">
                            <span class="text-end">يوجد 8 طلاب انقطعوا منذ 7 أيام ولابد من التواصل معهم</span> <span style="color: #cbd5e1; flex-shrink: 0;">••</span>
                        </p>
                        <p class="small text-muted mb-0 d-flex justify-content-end align-items-start gap-2 fw-medium">
                            <span class="text-end">معدل الحضور انخفض هذا الأسبوع بنسبة 4%</span> <span style="color: #cbd5e1; flex-shrink: 0;">••</span>
                        </p>
                    @endforelse
                </div>
                
                <div class="text-end">
                   <a href="#" class="text-muted text-decoration-none small fw-bold"><i class="fas fa-chevron-left fa-xs me-1"></i> عرض جميع الاقتراحات</a>
                </div>
            </div>

        </div>

        <!-- Left Column (In RTL Left): Chart and mini stats -->
        <div class="col-lg-7">
            
            <!-- Revenue Chart Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1.5rem; height: calc(100% - 240px); min-height: 380px;">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                       <i class="fas fa-chart-area text-success bg-success bg-opacity-10 p-2 rounded"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">الإيرادات</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-2 position-relative d-flex flex-column justify-content-end">
                    
                    <!-- Revenue Overlay text block inside chart -->
                    <div class="position-absolute text-end" style="bottom: 70px; right: 30px; z-index: 10;">
                        <span class="badge bg-white text-dark shadow-sm px-3 py-2 border border-light rounded-pill">
                            <h4 class="fw-bold mb-0 d-inline">{{ number_format($monthlyRevenue, 0) }}</h4>
                            <small class="text-muted d-inline ms-1" style="font-size: 0.7rem;">ر.س</small>
                        </span>
                    </div>

                    <div style="height: 250px; width: 100%; position: relative;">
                        <!-- Mock Area Chart -->
                        <svg viewBox="0 0 400 150" class="w-100 h-100" style="overflow: visible;" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chartGradNew" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:rgba(16, 185, 129, 0.15);stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:rgba(16, 185, 129, 0);stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <!-- Baseline Grid Lines -->
                            <line x1="0" y1="120" x2="400" y2="120" stroke="#f8fafc" stroke-width="1"></line>
                            <line x1="0" y1="80" x2="400" y2="80" stroke="#f8fafc" stroke-width="1"></line>
                            <line x1="0" y1="40" x2="400" y2="40" stroke="#f8fafc" stroke-width="1"></line>

                            <!-- Smooth Curve Path -->
                            @php
                                $d = "M 0,100 T 50,90 T 100,100 T 150,80 T 200,90 T 250,70 T 300,80 T 350,50 T 380,20 L 400,15";
                            @endphp
                            <path d="{{ $d }} L 400,150 L 0,150 Z" fill="url(#chartGradNew)" />
                            <path d="{{ $d }}" fill="none" stroke="#10b981" stroke-width="2.5" />
                            <circle cx="380" cy="20" r="4.5" fill="#10b981" stroke="#fff" stroke-width="2" />
                        </svg>
                    </div>

                    <!-- X Axis Labels -->
                    <div class="d-flex justify-content-between mt-3 px-2 text-muted fw-semibold" style="font-size: 0.75rem;" dir="rtl">
                        <span>الأحد</span>
                        <span>الإثنين</span>
                        <span>الثلاثاء</span>
                        <span>الأربعاء</span>
                        <span>الخميس</span>
                        <span>الجمعة</span>
                        <span>السبت</span>
                    </div>
                </div>
            </div>

            <!-- Two bottom stat cards -->
            <div class="row g-4" style="height: 200px;">
                <!-- Total Box (Right mini card) -->
                <div class="col-md-6 h-100">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 1.5rem;">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                            <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="fas fa-user text-info"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">الطلاب</h5>
                        </div>
                        <div class="card-body px-4 pb-4 text-end d-flex flex-column justify-content-end">
                            <h2 class="fw-bolder text-dark mb-1" style="font-size: 2.2rem;">{{ number_format($activeStudents) }}</h2>
                            <p class="text-muted small fw-medium mb-0">إجمالي عدد الطلاب النشطين بالمؤسسة</p>
                        </div>
                    </div>
                </div>
                
                <!-- Line Chart Students (Left mini card) -->
                <div class="col-md-6 h-100">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 1.5rem;">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-between align-items-center">
                            <h2 class="fw-bolder text-dark mb-0 align-self-end mt-2" style="font-size: 2rem;">142</h2>
                            
                            <!-- Little zig-zag line -->
                            <div class="w-100 px-3 mt-3 mb-2">
                                <svg viewBox="0 0 100 35" class="w-100 h-100" preserveAspectRatio="none">
                                    <path d="M 0,25 L 15,20 L 30,28 L 45,15 L 60,22 L 75,5 L 100,10" fill="none" stroke="#93c5fd" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <!-- Light shadow under -->
                                    <path d="M 0,25 L 15,20 L 30,28 L 45,15 L 60,22 L 75,5 L 100,10 L 100,35 L 0,35 Z" fill="#eff6ff" />
                                </svg>
                            </div>

                            <p class="text-muted small fw-medium mb-0 align-self-end">معدل الطلاب آخر 6 شهور</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
