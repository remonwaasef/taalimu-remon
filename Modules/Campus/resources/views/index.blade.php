@extends('campus::layouts.master')

@section('content')
<!-- Welcome Hero Section -->
<div class="welcome-hero rounded-4 shadow-soft overflow-hidden mb-0 position-relative animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); min-height: 250px;">
    <div class="hero-bg-accent" style="position: absolute; top:0; right:0; width:100%; height:100%; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.1; pointer-events: none;"></div>
    <div class="container-fluid py-5 px-4 px-md-5 position-relative z-target">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-2 mb-3 extra-small fw-bold">تحسين مستمر 🚀</span>
                <h1 class="display-6 fw-bold text-white mb-2">أهلاً بك، {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                <p class="text-white text-opacity-75 mb-4">واصل رحلة التميز وحقق أهدافك التعليمية اليوم.</p>
                
                @if($nextLesson)
                    <div class="d-inline-flex align-items-center gap-3 p-2 bg-white bg-opacity-10 backdrop-blur rounded-pill border border-white border-opacity-10 animate__animated animate__pulse animate__infinite">
                        <div class="stat-icon-sm bg-white rounded-circle text-primary shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                            <i class="fas fa-play small ms-1"></i>
                        </div>
                        <div class="me-3">
                            <div class="extra-small text-white text-opacity-75">استكمال التعلم</div>
                            <a href="#" class="text-white fw-bold text-decoration-none small stretched-link">{{ $nextLesson->title }}</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-4 d-none d-lg-block text-center">
                <i class="fas fa-graduation-cap display-1 text-white opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div class="stats-bar-container animate__animated animate__fadeInUp" style="margin-top: -30px; position: relative; z-index: 10;">
    <div class="stats-floating-bar mx-auto bg-white rounded-pill p-2 shadow-sm d-flex align-items-center justify-content-around px-3 mx-lg-5 border" style="max-width: 800px;">
        <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-sm bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <div class="extra-small text-muted">النقاط</div>
                <div class="fw-bold small">{{ $points ?? 0 }}</div>
            </div>
        </div>
        <div class="vr opacity-10"></div>
        <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-sm bg-warning bg-opacity-10 text-warning rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-trophy"></i>
            </div>
            <div>
                <div class="extra-small text-muted">الترتيب</div>
                <div class="fw-bold small">#{{ $rank ?? '-' }}</div>
            </div>
        </div>
        <div class="vr opacity-10"></div>
        <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-sm bg-success bg-opacity-10 text-success rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <div class="extra-small text-muted">الدورات</div>
                <div class="fw-bold small">{{ $enrollments->count() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Services Portal Grid -->
<div class="mt-5 pt-3">
    <h5 class="fw-bold text-dark mb-4 px-2 border-end border-primary border-4 animate__animated animate__fadeInRight">أين تريد الذهاب اليوم؟ 👋</h5>
    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-6 col-md-3">
            <a href="{{ route('campus.courses.index') }}" class="service-card text-decoration-none color-inherit">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 transition-all">
                    <div class="service-icon bg-primary bg-opacity-10 text-primary mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book-open fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">تصفح الدورات</h6>
                    <p class="extra-small text-muted mb-0">مكتبة شاملة</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('campus.schedule') }}" class="service-card text-decoration-none color-inherit">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 transition-all">
                    <div class="service-icon bg-info bg-opacity-10 text-info mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">الجدول الدراسي</h6>
                    <p class="extra-small text-muted mb-0">مواعيد دروسك</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('campus.attendance') }}" class="service-card text-decoration-none color-inherit">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 transition-all">
                    <div class="service-icon bg-success bg-opacity-10 text-success mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-check fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">سجل الحضور</h6>
                    <p class="extra-small text-muted mb-0">متابعة دقيقة</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('campus.finances') }}" class="service-card text-decoration-none color-inherit">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 transition-all">
                    <div class="service-icon bg-warning bg-opacity-10 text-warning mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-wallet fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">المالية والديون</h6>
                    <p class="extra-small text-muted mb-0">إدارة ميزانيتك</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- My Courses Section -->
<div class="mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <h5 class="fw-bold text-dark mb-0 border-end border-primary border-4 animate__animated animate__fadeInRight">دوراتي المسجلة 📚</h5>
        <a href="{{ route('campus.courses.index') }}" class="text-primary text-decoration-none small fw-bold">عرض الكل <i class="fas fa-arrow-left ms-1 small"></i></a>
    </div>

    <div class="row g-4 animate__animated animate__fadeInUp">
        @forelse($enrollments as $enrollment)
            @php $course = $enrollment->course; @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 course-card-premium overflow-hidden border">
                    <div class="course-cover-mini position-relative" style="height: 140px; overflow: hidden;">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary">
                                <i class="fas fa-graduation-cap fs-1 opacity-25"></i>
                            </div>
                        @endif
                        <div class="course-badge-mini" style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; color: var(--bs-primary);">
                            {{ $enrollment->status_label ?? 'نشط' }}
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-2 line-clamp-1" title="{{ $course->title }}">{{ $course->title }}</h6>
                        
                        <!-- Progress -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between extra-small mb-1">
                                <span class="text-muted small">الإنجاز</span>
                                <span class="fw-bold text-primary small">{{ $enrollment->progress_percent ?? 0 }}%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 6px;">
                                <div class="progress-bar bg-primary rounded-pill" style="width: {{ $enrollment->progress_percent ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <a href="#" class="btn btn-primary-soft rounded-pill py-2 fw-bold transition-all" style="background: rgba(79,70,229,0.08); color: #4f46e5; border:none;">
                                متابعة التعلم
                                <i class="fas fa-play ms-2 extra-small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 rounded-4 border bg-white shadow-sm">
                    <div class="mb-4 fs-1 opacity-25">📚</div>
                    <h5 class="fw-bold text-dark mb-2">لا توجد دورات مسجلة</h5>
                    <p class="text-muted mb-0 small">ابدأ برحلة العلم اليوم وتصفح الدورات المتاحة.</p>
                    <a href="{{ route('campus.courses.index') }}" class="btn btn-primary rounded-pill px-5 py-2 mt-4 fw-bold shadow-sm">تصفح الدورات</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .service-card:hover .card { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .service-card:hover .service-icon { transform: scale(1.1); }
    .color-inherit { color: inherit; }
    .course-card-premium:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1) !important; }
</style>
@endsection

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


