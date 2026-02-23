@extends('campus::layouts.master')

@section('content')
<div class="row align-items-center mb-5 animate__animated animate__fadeIn">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-1">استكشف الدورات</h3>
        <p class="text-muted small mb-0">اعثر على شغفك الجديد وابدأ رحلة التعلم اليوم</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('campus.index') }}" class="btn btn-primary-soft rounded-pill px-4 fw-bold">
            <i class="fas fa-arrow-right ms-2 small"></i> لوحة التحكم
        </a>
    </div>
</div>

<div class="row g-4 animate__animated animate__fadeInUp">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 course-card-premium overflow-hidden transition-all">
                <div class="position-relative">
                    <div class="course-cover-mini position-relative" style="height: 180px;">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-premium">
                                <i class="fas fa-microscope display-4 text-white opacity-25"></i>
                            </div>
                        @endif
                        <div class="course-badge-mini">
                            {{ $course->grade?->name ?? 'عامة' }}
                        </div>
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-white text-dark shadow-sm rounded-pill py-2 px-3 fw-bold">
                                {{ $course->price > 0 ? $course->price . ' ج.م' : 'مجاني' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-2 line-clamp-1" title="{{ $course->title }}">
                        {{ $course->title }}
                    </h5>
                    <p class="text-muted small mb-4 line-clamp-2 opacity-75">{{ $course->description }}</p>

                    <button class="btn btn-primary-soft w-100 rounded-pill py-2 fw-bold transition-all">
                        عرض التفاصيل
                        <i class="fas fa-info-circle ms-2 small"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="p-5 rounded-5 border-dashed-premium bg-white shadow-sm">
                <div class="mb-4 fs-1 opacity-25">🔍</div>
                <h5 class="fw-bold text-dark mb-2">لا توجد دورات متاحة حالياً</h5>
                <p class="text-muted mb-0 opacity-75">يرجى العودة لاحقاً أو التواصل مع الإدارة للاستفسار</p>
            </div>
        </div>
    @endforelse
</div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .transition {
            transition: all 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
        }
    </style>
@endsection
