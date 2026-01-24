@extends('campus::layouts.master')

@section('content')
<div class="row g-4 mb-5">
    <!-- Welcome and Stats -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100 position-relative overflow-hidden">
            <div class="position-relative" style="z-index: 2;">
                <h2 class="fw-bold mb-2">أهلاً بك يا {{ auth()->user()->name }}! 👋</h2>
                <p class="text-white-50 lead mb-4">يسعدنا رؤية تقدمك. واصل التعلم لتحقيق أهدافك.</p>
                
                @if($lastEnrollment && $nextLesson)
                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-play text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-white-50 d-block">استكمال من حيث توقفت:</small>
                        <span class="fw-bold">{{ $nextLesson->title }}</span>
                    </div>
                    <a href="{{ route('center.courses.player', ['course' => $lastEnrollment->course_id, 'lesson' => $nextLesson->id]) }}" class="btn btn-light rounded-pill px-4 fw-bold">استمرار</a>
                </div>
                @endif
            </div>
            <i class="fas fa-graduation-cap position-absolute end-0 bottom-0 mb-n4 me-n4 text-white-50" style="font-size: 200px; opacity: 0.1;"></i>
        </div>
    </div>

    <!-- Gamification Mini Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
            <h5 class="fw-bold mb-4">إنجازاتك 🏆</h5>
            <div class="d-flex flex-column gap-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-lg rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <div class="text-muted small">رصيد النقاط</div>
                        <div class="fw-bold fs-4">{{ number_format($points) }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-lg rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <div class="text-muted small">ترتيبك الحالي</div>
                        <div class="fw-bold fs-4">#{{ $rank }}</div>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('center.leaderboard.index') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">عرض لوحة الصدارة</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold text-dark mb-1">دوراتي المسجلة</h4>
        <p class="text-muted small mb-0">لديك {{ $enrollments->count() }} دورات قيد التنفيذ</p>
    </div>
    <a href="{{ route('campus.courses.index') }}" class="btn btn-light rounded-pill px-4 border">تصفح الكل</a>
</div>

<div class="row g-4">
    @forelse($enrollments as $enrollment)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 course-card overflow-hidden transition">
                <div class="position-relative">
                    <div class="bg-primary bg-gradient d-flex align-items-center justify-content-center text-white" style="height: 200px; background: linear-gradient(135deg, {{ $loop->even ? '#0ea5e9 0%, #2dd4bf' : '#6366f1 0%, #a855f7' }} 100%) !important;">
                        @if($enrollment->course->image)
                            <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="fas {{ $loop->even ? 'fa-flask' : 'fa-calculator' }} display-3 opacity-50"></i>
                        @endif
                    </div>
                    <div class="position-absolute bottom-0 end-0 m-3">
                        <span class="badge bg-white text-dark shadow-sm rounded-pill py-2 px-3">
                            <i class="fas fa-play-circle {{ $loop->even ? 'text-info' : 'text-primary' }} me-1"></i> 
                            {{ $enrollment->course->sections->sum(fn($s) => $s->lessons->count()) }} درس
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <span class="badge bg-opacity-10 rounded-pill py-2 px-3 small mb-3 {{ $loop->even ? 'bg-success text-success' : 'bg-info text-info' }}">
                        {{ $enrollment->course->grade?->name ?? 'دورة عامة' }}
                    </span>
                    <h5 class="fw-bold mb-3 text-dark">{{ $enrollment->course->title }}</h5>
                    
                    <div class="bg-light rounded-4 p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold">نسبة التقدم</span>
                            <span class="text-primary small fw-bold">{{ $enrollment->progress }}%</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 6px;">
                            <div class="progress-bar rounded-pill {{ $loop->even ? 'bg-info' : 'bg-primary' }}" role="progressbar" style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('center.courses.player', ['course' => $enrollment->course_id]) }}" class="btn rounded-pill py-2 fw-bold shadow-sm transition {{ $loop->even ? 'btn-info text-white' : 'btn-primary' }}">
                             {{ $enrollment->progress > 0 ? 'استكمال التعلم' : 'بدء التعلم' }} <i class="fas fa-arrow-left ms-2"></i>
                        </a>
                        
                        @if($enrollment->progress >= 100 && $enrollment->certificate)
                            <a href="{{ route('campus.certificates.download', $enrollment->certificate->id) }}" class="btn btn-success rounded-pill py-2 fw-bold shadow-sm transition">
                                <i class="fas fa-download me-2"></i> تحميل الشهادة
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="mb-4 display-1 opacity-25">📚</div>
            <h4 class="fw-bold text-dark">لا تملك أي دورات حالياً</h4>
            <p class="text-muted mb-4">اشترك في دورة تعليمية لتبدأ رحلتك التفاعلية معنا.</p>
            <a href="{{ route('campus.courses.index') }}" class="btn btn-primary rounded-pill px-5 py-2">تصفح الدورات المتاحة</a>
        </div>
    @endforelse
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .backdrop-blur { backdrop-filter: blur(10px); }
    .transition { transition: all 0.3s ease; }
    .course-card:hover { 
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
</style>
@endsection

