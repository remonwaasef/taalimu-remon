@extends('campus::layouts.master')

@section('content')
<div class="row g-4 mb-5">
    <!-- Welcome and Stats -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 position-relative welcome-card">
            <div class="card-body p-4 p-lg-5 position-relative" style="z-index: 2;">
                <div class="d-flex flex-column justify-content-center h-100">
                    <h1 class="display-5 fw-bold text-white mb-2">
                        أهلاً بك، <span class="text-warning">{{ auth()->user()->name }}</span>! 👋
                    </h1>
                    <p class="text-white-50 lead mb-4" style="max-width: 600px;">
                        سعداء برؤيتك مجدداً. واصل رحلة التعلم وحقق أهدافك اليوم.
                    </p>
                    
                    @if($lastEnrollment && $nextLesson)
                    <div class="glass-action-card p-3 rounded-4 mt-auto border border-white border-opacity-10 d-flex align-items-center gap-3 transition-hover">
                        <div class="play-icon-wrapper shadow-lg">
                            <i class="fas fa-play fa-lg text-primary ms-1"></i>
                        </div>
                        <div class="flex-grow-1 text-white">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-warning text-dark rounded-pill px-2">استكمال التعلم</span>
                                <small class="opacity-75">من حيث توقفت</small>
                            </div>
                            <h6 class="fw-bold mb-0 text-truncate">{{ $nextLesson->title }}</h6>
                        </div>
                        <a href="{{ route('center.courses.player', ['course' => $lastEnrollment->course_id, 'lesson' => $nextLesson->id]) }}" 
                           class="btn btn-white rounded-pill px-4 py-2 fw-bold shadow-sm d-none d-sm-block">
                            استمرار <i class="fas fa-arrow-left ms-2"></i>
                        </a>
                        <!-- Mobile only button -->
                        <a href="{{ route('center.courses.player', ['course' => $lastEnrollment->course_id, 'lesson' => $nextLesson->id]) }}" 
                           class="btn btn-white btn-sm rounded-circle d-sm-none shadow-sm" style="width: 40px; height: 40px; display: grid; place-items: center;">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Abstract Shapes Background -->
            <div class="position-absolute top-0 end-0 h-100 w-100 bg-overlay-pattern"></div>
            <div class="position-absolute bottom-0 end-0 mb-n5 me-n5 opacity-10">
                <i class="fas fa-graduation-cap" style="font-size: 300px; transform: rotate(-15deg);"></i>
            </div>
        </div>
    </div>

    <!-- Gamification Cards -->
    <div class="col-lg-4">
        <div class="row g-3 h-100">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 stat-card bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle">
                            <i class="fas fa-star fa-lg"></i>
                        </div>
                        <span class="badge bg-light text-muted rounded-pill">المجموع الكلي</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($points) }}</h3>
                    <p class="text-muted small mb-0">نقطة مكتسبة</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 stat-card bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="fas fa-trophy fa-lg"></i>
                        </div>
                        <span class="badge bg-light text-muted rounded-pill">الترتيب العام</span>
                    </div>
                    <h3 class="fw-bold mb-1">#{{ $rank }}</h3>
                    <p class="text-muted small mb-0">على مستوى الدفعة</p>
                    <a href="{{ route('center.leaderboard.index') }}" class="btn btn-link text-primary p-0 mt-2 small text-decoration-none">
                        عرض لوحة الصدارة <i class="fas fa-arrow-left ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="fas fa-book-reader text-primary me-2"></i> دوراتي المسجلة
        </h3>
        <p class="text-muted mb-0">لديك <span class="fw-bold text-dark">{{ $enrollments->count() }}</span> دورات قيد التنفيذ حالياً</p>
    </div>
    <a href="{{ route('campus.courses.index') }}" class="btn btn-light rounded-pill px-4 border shadow-sm transition-hover">
        تصفح الكل <i class="fas fa-th-large ms-2"></i>
    </a>
</div>

<div class="row g-4">
    @forelse($enrollments as $enrollment)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 course-card overflow-hidden">
                <div class="position-relative">
                    <div class="course-cover-wrapper bg-dark position-relative" style="height: 200px;">
                        @if($enrollment->course->image)
                            <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="w-100 h-100 object-fit-cover transition-zoom">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-primary">
                                <i class="fas {{ $loop->even ? 'fa-flask' : 'fa-calculator' }} display-1 text-white opacity-25"></i>
                            </div>
                        @endif
                        
                        <!-- Overlay Gradient -->
                        <div class="course-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                        
                        <!-- Badges -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                            <span class="badge glass-badge backdrop-blur">
                                {{ $enrollment->course->grade?->name ?? 'دورة عامة' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="progress-floating shadow-sm">
                         <div class="progress rounded-pill bg-light" style="height: 8px;">
                            <div class="progress-bar rounded-pill bg-gradient-success" role="progressbar" style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 pt-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold text-dark mb-0 line-clamp-2 w-75" title="{{ $enrollment->course->title }}">
                            {{ $enrollment->course->title }}
                        </h5>
                        <div class="circular-progress" data-percent="{{ $enrollment->progress }}" style="--percent: {{ $enrollment->progress }};">
                            <span class="small fw-bold">{{ $enrollment->progress }}%</span>
                        </div>
                    </div>

                    <div class="text-muted small mb-4 d-flex align-items-center gap-3">
                        <span><i class="far fa-clock me-1"></i> {{ $enrollment->course->sections->sum(fn($s) => $s->lessons->count()) }} درس</span>
                        <span><i class="far fa-calendar me-1"></i> {{ $enrollment->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('center.courses.player', ['course' => $enrollment->course_id]) }}" 
                           class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm btn-hover-effect">
                             {{ $enrollment->progress > 0 ? 'استكمال التعلم' : 'بدء التعلم' }} 
                             <i class="fas fa-play-circle ms-2"></i>
                        </a>
                        
                        @if($enrollment->progress >= 100 && $enrollment->certificate)
                            <a href="{{ route('campus.certificates.download', $enrollment->certificate->id) }}" class="btn btn-outline-success rounded-pill py-2 fw-bold border-2">
                                <i class="fas fa-certificate me-2"></i> الشهادة
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="empty-state p-5 rounded-4 border border-dashed bg-light">
                <div class="mb-4 display-1 text-primary opacity-25">🎓</div>
                <h3 class="fw-bold text-dark mb-2">لا توجد دورات مسجلة</h3>
                <p class="text-muted mb-4 lead">لم تقم بالتسجيل في أي دورة بعد. تصفح الدورات وابدأ رحلتك التعليمية الآن.</p>
                <a href="{{ route('campus.courses.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg">
                    <i class="fas fa-search me-2"></i> تصفح الدورات المتاحة
                </a>
            </div>
        </div>
    @endforelse
</div>

<style>
    /* Custom Styling for Dashboard */
    
    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.25) !important;
    }
    
    .glass-action-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .glass-action-card:hover {
        background: rgba(255, 255, 255, 0.15);
    }
    
    .play-icon-wrapper {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-white {
        background: white;
        color: #4f46e5;
        border: none;
        transition: all 0.2s;
    }
    
    .btn-white:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Stat Cards */
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
    
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Course Cards */
    .course-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: transparent;
    }
    
    .course-cover-wrapper {
        overflow: hidden;
    }
    
    .transition-zoom {
        transition: transform 0.5s ease;
    }
    
    .course-card:hover .transition-zoom {
        transform: scale(1.05);
    }
    
    .course-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.4) 0%, transparent 100%);
        opacity: 0.6;
    }
    
    .glass-badge {
        background: rgba(255, 255, 255, 0.9);
        color: #1e293b;
        border: 1px solid rgba(255,255,255,0.5);
        font-weight: 600;
        padding: 0.5em 1em;
        border-radius: 50px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    }
    
    .progress-floating {
        position: absolute;
        bottom: -4px;
        left: 20px;
        right: 20px;
        z-index: 2;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Circular Progress */
    .circular-progress {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: conic-gradient(var(--bs-primary) calc(var(--percent) * 1%), #e2e8f0 0);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .circular-progress::before {
        content: '';
        position: absolute;
        width: 34px;
        height: 34px;
        background: white;
        border-radius: 50%;
    }
    
    .circular-progress span {
        position: relative;
        z-index: 1;
        font-size: 10px;
        color: var(--bs-primary);
    }

    .btn-hover-effect:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .bg-overlay-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>
@endsection


