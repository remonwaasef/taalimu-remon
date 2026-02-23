@extends('campus::layouts.master')

@section('content')
<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-premium rounded-5 overflow-hidden welcome-hero position-relative animate__animated animate__fadeInDown">
            <div class="card-body p-4 p-lg-5 position-relative z-target">
                <div class="row align-items-center">
                    <div class="col-lg-7 text-white">
                        <h1 class="display-5 fw-bold mb-3">
                            أهلاً بك، <span class="text-warning">{{ auth()->user()->name }}</span>! 👋
                        </h1>
                        <p class="lead opacity-75 mb-4">
                            واصل رحلة التميز وحقق أهدافك التعليمية اليوم. نحن هنا لندعمك في كل خطوة.
                        </p>
                        
                        @if($lastEnrollment && $nextLesson)
                        <div class="d-inline-flex align-items-center gap-3 bg-white bg-opacity-10 p-2 pe-4 rounded-pill backdrop-blur border border-white border-opacity-10 transition-hover">
                            <a href="{{ route('center.courses.player', ['course' => $lastEnrollment->course_id, 'lesson' => $nextLesson->id]) }}" 
                               class="btn btn-white rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="fas fa-play text-primary ms-1"></i>
                            </a>
                            <div class="flex-grow-1">
                                <span class="d-block extra-small text-white-50">استكمال التعلم</span>
                                <span class="fw-bold d-block line-clamp-1 small">{{ $nextLesson->title }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Decorative BG -->
            <div class="hero-bg-accent"></div>
        </div>

        <!-- Floating Stats Bar (Glassmorphism) -->
        <div class="container stats-bar-container">
            <div class="card border-0 shadow-lg rounded-pill bg-glass p-2 mx-auto stats-floating-bar animate__animated animate__fadeInUp animate__delay-1s">
                <div class="row g-0 align-items-center text-center">
                    <div class="col-4 border-end border-white border-opacity-20 px-3">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <div class="stat-icon-sm bg-warning text-white rounded-circle"><i class="fas fa-star"></i></div>
                            <div class="text-start">
                                <span class="fw-bold d-block lh-1">{{ number_format($points) }}</span>
                                <small class="text-muted extra-small">نقطة</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 border-end border-white border-opacity-20 px-3">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <div class="stat-icon-sm bg-primary text-white rounded-circle"><i class="fas fa-trophy"></i></div>
                            <div class="text-start">
                                <span class="fw-bold d-block lh-1">#{{ $rank }}</span>
                                <small class="text-muted extra-small">الترتيب</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 px-3">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <div class="stat-icon-sm bg-success text-white rounded-circle"><i class="fas fa-check-circle"></i></div>
                            <div class="text-start">
                                <span class="fw-bold d-block lh-1">{{ $enrollments->count() }}</span>
                                <small class="text-muted extra-small">دورة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Services Grid -->
<div class="row g-4 mb-5 animate__animated animate__fadeInUp animate__delay-1s">
    <div class="col-6 col-md-3">
        <a href="{{ route('campus.courses.index') }}" class="service-card transition-all">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="service-icon mb-3 bg-primary bg-opacity-10 text-primary mx-auto">
                    <i class="fas fa-book-open fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0">تصفح الدورات</h6>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('campus.schedule') }}" class="service-card transition-all">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="service-icon mb-3 bg-info bg-opacity-10 text-info mx-auto">
                    <i class="fas fa-calendar-alt fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0">الجدول الدراسي</h6>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('campus.attendance') }}" class="service-card transition-all">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="service-icon mb-3 bg-success bg-opacity-10 text-success mx-auto">
                    <i class="fas fa-user-check fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0">سجل الحضور</h6>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('campus.finances') }}" class="service-card transition-all">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                <div class="service-icon mb-3 bg-warning bg-opacity-10 text-warning mx-auto">
                    <i class="fas fa-wallet fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0">المالية والديون</h6>
            </div>
        </a>
    </div>
</div>

<!-- My Enrolled Courses -->
<div class="d-flex justify-content-between align-items-end mb-4 animate__animated animate__fadeIn">
    <div>
        <h4 class="fw-bold text-dark mb-1">دوراتي المسجلة</h4>
        <p class="text-muted small mb-0">تابع تقدمك في الدورات التي اشتركت بها</p>
    </div>
</div>

<div class="row g-4 mb-5 animate__animated animate__fadeInUp">
    @forelse($enrollments as $enrollment)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 course-card-premium overflow-hidden transition-all">
                <div class="position-relative">
                    <div class="course-cover-mini position-relative" style="height: 160px;">
                        @if($enrollment->course->image)
                            <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-premium">
                                <i class="fas fa-graduation-cap display-4 text-white opacity-25"></i>
                            </div>
                        @endif
                        <div class="course-badge-mini">
                            {{ $enrollment->course->grade?->name ?? 'عامة' }}
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 line-clamp-1" title="{{ $enrollment->course->title }}">
                        {{ $enrollment->course->title }}
                    </h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">مستوى التقدم</small>
                        <small class="fw-bold text-primary">{{ $enrollment->progress }}%</small>
                    </div>
                    <div class="progress rounded-pill mb-4" style="height: 6px; background: #f1f5f9;">
                        <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: {{ $enrollment->progress }}%"></div>
                    </div>

                    <a href="{{ route('center.courses.player', ['course' => $enrollment->course_id]) }}" 
                       class="btn btn-primary-soft w-100 rounded-pill py-2 fw-bold transition-all">
                        {{ $enrollment->progress > 0 ? 'استكمال' : 'بدء التعلم' }} 
                        <i class="fas fa-chevron-left ms-2 small"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="p-5 rounded-5 border-dashed-premium bg-white shadow-sm">
                <div class="mb-4 fs-1 opacity-25">📚</div>
                <h5 class="fw-bold text-dark mb-2">لا توجد دورات مسجلة</h5>
                <p class="text-muted mb-4 opacity-75">ابدأ رحلتك التعليمية الآن واشترك في دورتك الأولى</p>
                <a href="{{ route('campus.courses.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                    تصفح الدورات المتوفرة
                </a>
            </div>
        </div>
    @endforelse
</div>

<style>
    /* Premium Dashboard Styles */
    
    .welcome-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        min-height: 280px;
        display: flex;
        align-items: center;
    }
    
    .hero-bg-accent {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.1;
        pointer-events: none;
    }

    .stats-bar-container {
        margin-top: -35px;
        position: relative;
        z-index: 10;
    }

    .stats-floating-bar {
        max-width: 800px;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .stat-icon-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .service-card { text-decoration: none; color: inherit; display: block; }
    .service-card:hover .card { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1) !important; }
    .service-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .service-card:hover .service-icon { transform: scale(1.1); }

    .course-card-premium {
        border: 1px solid rgba(0,0,0,0.03);
    }
    .course-card-premium:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1) !important;
    }

    .course-cover-mini {
        overflow: hidden;
    }
    .course-badge-mini {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--bs-primary);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .bg-gradient-premium {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }

    .btn-primary-soft {
        background: rgba(99, 102, 241, 0.08);
        color: var(--bs-primary);
        border: none;
    }
    .btn-primary-soft:hover {
        background: var(--bs-primary);
        color: white;
    }

    .border-dashed-premium {
        border: 2px dashed #e2e8f0;
    }

    .backdrop-blur { backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    .extra-small { font-size: 0.7rem; }
    .z-target { z-index: 5; }

    @media (max-width: 768px) {
        .welcome-hero { min-height: 200px; text-align: center; }
        .stats-floating-bar { border-radius: 20px !important; }
        .stats-bar-container { margin-top: -20px; }
    }
</style>
@endsection


