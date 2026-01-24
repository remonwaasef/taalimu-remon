@extends('campus::layouts.master')

@section('content')
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-2">تصفح الدورات المتاحة</h2>
            <p class="text-muted mb-0">اكتشف دورات جديدة لتنمية مهاراتك</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2">
                <a href="{{ route('campus.index') }}" class="btn btn-light rounded-pill px-4">
                    <i class="bi bi-arrow-right ms-2"></i> العودة لدوراتي
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-ultra h-100 course-card overflow-hidden transition">
                    <div class="position-relative">
                        <div class="bg-primary bg-gradient d-flex align-items-center justify-content-center text-white" style="height: 180px; background: linear-gradient(135deg, {{ $loop->even ? '#0ea5e9 0%, #2dd4bf' : '#6366f1 0%, #a855f7' }} 100%) !important;">
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <span class="display-3">{{ $loop->even ? '🧪' : '📐' }}</span>
                            @endif
                        </div>
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-white text-dark shadow-sm rounded-pill py-2 px-3">
                                {{ $course->price > 0 ? $course->price . ' ج.م' : 'مجاني' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="badge bg-opacity-10 rounded-pill py-2 px-3 small {{ $loop->even ? 'bg-success text-success' : 'bg-info text-info' }}">
                                {{ $course->grade?->name ?? 'دورة عامة' }}
                            </span>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">{{ $course->title }}</h5>
                        <p class="text-muted small mb-4 line-clamp-2">{{ $course->description }}</p>
                        
                        <div class="d-grid gap-2">
                             <button class="btn btn-outline-primary rounded-pill py-2 fw-bold shadow-sm transition">
                                 التفاصيل
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4 display-1 opacity-25">🔍</div>
                <h4 class="fw-bold text-dark">لا توجد دورات متاحة حالياً</h4>
                <p class="text-muted mb-4">يرجى التحقق لاحقاً أو التواصل مع الإدارة.</p>
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
