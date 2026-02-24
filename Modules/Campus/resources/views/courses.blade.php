@extends('campus::layouts.master')

@section('content')
<div class="row align-items-center mb-5 animate__animated animate__fadeIn">
    <div class="col-md-7">
        <h2 class="fw-bold text-dark mb-2">استكشف رحلتك القادمة 🚀</h2>
        <p class="text-muted mb-0">اختر من بين أفضل الدورات المصممة خصيصاً لتطوير مهاراتك</p>
    </div>
    <div class="col-md-5 text-md-end mt-4 mt-md-0">
        <a href="{{ route('campus.index') }}" class="btn btn-glass rounded-pill px-4 fw-bold transition-all">
            <i class="fas fa-th-large ms-2 small"></i> لوحة التحكم
        </a>
    </div>
</div>

<div class="row g-4 animate__animated animate__fadeInUp">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-soft rounded-5 h-100 premium-course-card overflow-hidden">
                <!-- Course Media -->
                <div class="position-relative overflow-hidden" style="height: 220px;">
                    @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" class="w-100 h-100 object-fit-cover card-img-scale">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center premium-gradient-bg">
                            <i class="fas fa-graduation-cap display-4 text-white opacity-20"></i>
                        </div>
                    @endif
                    
                    <!-- Floating Badges -->
                    <div class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2 align-items-end">
                        <span class="badge glass-badge rounded-pill px-3 py-2 text-white fw-bold shadow-sm">
                            {{ $course->grade?->name ?? 'دورة عامة' }}
                        </span>
                    </div>

                    <div class="position-absolute bottom-0 start-0 m-3">
                        <span class="price-tag shadow-sm">
                            {{ $course->price > 0 ? number_format($course->price, 0) . ' ج.م' : 'مجاني' }}
                        </span>
                    </div>
                </div>

                <!-- Course Content -->
                <div class="card-body p-4 pt-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="instructor-avatar-sm shadow-sm">
                            <i class="fas fa-user-tie text-primary"></i>
                        </div>
                        <span class="extra-small text-muted fw-bold">{{ $course->instructor->name ?? 'أكاديمية الراعي' }}</span>
                    </div>

                    <h5 class="fw-bold text-dark mb-3 line-clamp-2" style="height: 3rem; line-height: 1.5;">
                        {{ $course->title }}
                    </h5>
                    
                    <p class="text-muted extra-small mb-4 line-clamp-3 opacity-75">
                        {{ $course->description ?? 'لا يوجد وصف متاح لهذه الدورة حالياً. انضم إلينا لاكتشاف عالم المعرفة والتميز.' }}
                    </p>

                    <div class="pt-2 border-top border-light d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center text-muted extra-small">
                                <i class="far fa-clock me-1 text-primary"></i>
                                <span>{{ $course->sessions_count ?? 0 }} حصة</span>
                            </div>
                        </div>
                        <span class="text-primary fw-bold small">تصفح الآن <i class="fas fa-chevron-left ms-1 tiny"></i></span>
                    </div>
                </div>
                
                <!-- Explicit Shadow/Glow on hover is handled in CSS -->
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="empty-state-card p-5 rounded-5 bg-white shadow-sm border border-dashed text-center">
                <div class="empty-icon-wrapper mx-auto mb-4">
                    <i class="fas fa-search display-3 text-primary opacity-20"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">لا توجد دورات متاحة حالياً</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">نحن نعمل باستمرار على إضافة دورات جديدة. يرجى العودة لاحقاً لاستكشاف المزيد.</p>
            </div>
        </div>
    @endforelse
</div>

<style>
    :root {
        --premium-primary: #4f46e5;
        --premium-secondary: #7c3aed;
        --glass-white: rgba(255, 255, 255, 0.7);
    }

    .premium-course-card {
        background: #fff;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        border: 1px solid rgba(0,0,0,0.03) !important;
    }

    .premium-course-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.12) !important;
    }

    .card-img-scale {
        transition: transform 0.8s ease;
    }

    .premium-course-card:hover .card-img-scale {
        transform: scale(1.1);
    }

    .premium-gradient-bg {
        background: linear-gradient(135deg, var(--premium-primary) 0%, var(--premium-secondary) 100%);
        position: relative;
    }

    .premium-gradient-bg::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .glass-badge {
        background: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }

    .price-tag {
        background: #fff;
        color: var(--premium-primary);
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.85rem;
    }

    .instructor-avatar-sm {
        width: 28px;
        height: 28px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }

    .btn-glass {
        background: rgba(79, 70, 229, 0.05);
        color: var(--premium-primary);
        border: 1px solid rgba(79, 70, 229, 0.1);
    }

    .btn-glass:hover {
        background: var(--premium-primary);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tiny { font-size: 0.6rem; }

    .border-dashed-premium {
        border: 2px dashed #e5e7eb;
    }

    .empty-icon-wrapper {
        width: 100px;
        height: 100px;
        background: #f9fafb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
