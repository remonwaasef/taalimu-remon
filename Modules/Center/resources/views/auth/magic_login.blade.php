@extends('layouts.landing-new')

@section('title', 'تأكيد الدخول السريع')

@section('content')
<div class="card border-0 shadow-elite rounded-5 overflow-hidden animate__animated animate__fadeInUp" style="max-width: 450px; margin: auto;">
    <div class="card-body p-5 text-center">
        <!-- Icon -->
        <div class="magic-icon-container mb-4">
            <div class="magic-bg"></div>
            <i class="fas fa-magic fs-1 text-primary position-relative"></i>
        </div>

        <!-- Content -->
        <h3 class="fw-bold text-dark mb-3">مرحباً كابتن، {{ $student->name }}</h3>
        <p class="text-muted mb-5 px-3">
            أنت على وشك تسجيل الدخول السريع إلى حسابك التعليمي. يرجى الضغط على الزر أدناه للمتابعة.
        </p>

        <!-- Form -->
        <form method="POST" action="{{ request()->fullUrl() }}">
            @csrf
            <div class="d-grid gap-3">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3 transition-all hover-lift">
                    <i class="fas fa-sign-in-alt me-2"></i> تأكيد الدخول للمنصة
                </button>
                <a href="{{ route('center.login') }}" class="btn btn-light btn-lg rounded-pill fw-bold py-3 text-muted">
                    إلغاء والذهاب لصفحة الدخول
                </a>
            </div>
        </form>

        <!-- Footer Info -->
        <div class="mt-5 pt-4 border-top">
            <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                <i class="fas fa-shield-alt text-success"></i>
                <span>تسجيل دخول آمن لمرة واحدة</span>
            </div>
            <p class="extra-small text-muted mt-2 opacity-50">هذا الرابط صالح لمدة 15 دقيقة فقط من وقت إنشائه.</p>
        </div>
    </div>
</div>

<style>
    :root {
        --elite-shadow: 0 20px 50px -15px rgba(58, 12, 163, 0.15);
    }
    .shadow-elite { box-shadow: var(--elite-shadow) !important; }
    .rounded-5 { border-radius: 2.5rem !important; }
    .transition-all { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .extra-small { font-size: 0.7rem; }

    .magic-icon-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .magic-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--bs-primary);
        opacity: 0.1;
        border-radius: 35% 65% 70% 30% / 30% 30% 70% 70%;
        animation: blob-animate 8s linear infinite;
    }

    @keyframes blob-animate {
        0%, 100% { border-radius: 35% 65% 70% 30% / 30% 30% 70% 70%; }
        25% { border-radius: 65% 35% 30% 70% / 70% 70% 30% 30%; }
        50% { border-radius: 30% 70% 30% 70% / 30% 70% 30% 70%; }
        75% { border-radius: 70% 30% 70% 30% / 70% 30% 70% 30%; }
    }
</style>
@endsection
