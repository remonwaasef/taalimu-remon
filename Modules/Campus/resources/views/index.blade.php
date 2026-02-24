@extends('campus::layouts.master')

@section('content')
<!-- Welcome Hero Section -->
<div class="welcome-hero rounded-4 shadow-soft overflow-hidden mb-0 position-relative animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); min-height: 250px;">
    <div class="hero-bg-accent" style="position: absolute; top:0; right:0; width:100%; height:100%; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.1; pointer-events: none;"></div>
    <div class="container-fluid py-5 px-4 px-md-5 position-relative z-target text-end">
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
                            <a href="{{ route('center.courses.player', ['course' => $lastEnrollment->course_id, 'lesson' => $nextLesson->id]) }}" class="text-white fw-bold text-decoration-none small stretched-link">{{ $nextLesson->title }}</a>
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
<div class="mt-5">
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <h5 class="fw-bold text-dark mb-0 border-end border-primary border-4 animate__animated animate__fadeInRight">&nbsp; دوراتي المسجلة 📚</h5>
        <a href="{{ route('campus.courses.index') }}" class="text-primary text-decoration-none extra-small fw-bold">استكشف المزيد <i class="fas fa-arrow-left ms-1 small"></i></a>
    </div>

    <div class="row g-4 animate__animated animate__fadeInUp">
        @forelse($enrollments as $enrollment)
            @php $course = $enrollment->course; @endphp
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('center.courses.player', ['course' => $course->id]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-soft rounded-5 h-100 premium-course-card overflow-hidden">
                        <div class="position-relative overflow-hidden" style="height: 160px;">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" class="w-100 h-100 object-fit-cover card-img-scale">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center premium-gradient-bg">
                                    <i class="fas fa-book-open fs-1 text-white opacity-20"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge glass-badge rounded-pill px-3 py-2 text-white fw-bold shadow-sm">
                                    {{ $enrollment->status_label ?? 'نشط' }}
                                </span>
                            </div>

                            <!-- Floating Play Button on Hover -->
                            <div class="play-overlay d-flex align-items-center justify-content-center">
                                <div class="play-btn-circle">
                                    <i class="fas fa-play text-white ms-1"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3 line-clamp-1" title="{{ $course->title }}">{{ $course->title }}</h6>
                            
                            <!-- Premium Progress Bar -->
                            <div class="mb-2">
                                <div class="d-flex justify-content-between extra-small mb-2">
                                    <span class="text-muted fw-bold">مستوى الإنجاز</span>
                                    <span class="text-primary fw-bold">{{ $enrollment->progress_percent ?? 0 }}%</span>
                                </div>
                                <div class="progress-premium-container">
                                    <div class="progress-premium-bar" style="width: {{ $enrollment->progress_percent ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state-card p-5 rounded-5 bg-white shadow-sm border border-dashed text-center">
                    <div class="empty-icon-wrapper mx-auto mb-4">
                        <i class="fas fa-book-reader display-3 text-primary opacity-20"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">لا توجد دورات مسجلة</h5>
                    <p class="text-muted small mb-0">ابدأ برحلة العلم اليوم وتصفح الدورات المتاحة.</p>
                    <a href="{{ route('campus.courses.index') }}" class="btn btn-primary rounded-pill px-5 py-2 mt-4 fw-bold shadow-sm">تصفح الدورات</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    :root {
        --premium-primary: #4f46e5;
        --premium-secondary: #7c3aed;
    }

    .premium-course-card {
        background: #fff;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0,0,0,0.03) !important;
    }

    .premium-course-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1) !important;
    }

    .card-img-scale {
        transition: transform 0.8s ease;
    }

    .premium-course-card:hover .card-img-scale {
        transform: scale(1.1);
    }

    .play-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(79, 70, 229, 0.4);
        opacity: 0;
        backdrop-filter: blur(2px);
        transition: all 0.3s ease;
    }

    .premium-course-card:hover .play-overlay {
        opacity: 1;
    }

    .play-btn-circle {
        width: 50px; height: 50px;
        background: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transform: scale(0.5);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: var(--premium-primary);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .premium-course-card:hover .play-btn-circle {
        transform: scale(1);
    }

    .progress-premium-container {
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-premium-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--premium-primary), var(--premium-secondary));
        border-radius: 10px;
        transition: width 1s ease;
    }

    .glass-badge {
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.65rem;
    }

    .premium-gradient-bg {
        background: linear-gradient(135deg, var(--premium-primary) 0%, var(--premium-secondary) 100%);
    }

    .empty-state-card {
        border-style: dashed !important;
        border-width: 2px !important;
        border-color: #e2e8f0 !important;
    }
</style>

<style>
    .service-card:hover .card { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .service-card:hover .service-icon { transform: scale(1.1); }
    .color-inherit { color: inherit; }
    .course-card-premium { transition: all 0.3s; }
    .course-card-premium:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1) !important; }
    .btn-primary-soft:hover { background: #4f46e5 !important; color: white !important; }
</style>
@endsection
