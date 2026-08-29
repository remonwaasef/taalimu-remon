@extends('layouts.auth-minimal')

@section('title', 'تأكيد الدخول للمنصة')

@section('content')
<div class="card border-0 shadow-lg rounded-5 overflow-hidden animate__animated animate__fadeInUp" style="max-width: 420px; width: 100%; margin: auto; background: white;">
    <div class="card-body p-5 text-center">
        <!-- Icon section -->
        <div class="mb-4 d-flex justify-content-center">
            <div class="magic-icon-bg">
                <i class="fas fa-magic fs-2 text-primary"></i>
            </div>
        </div>

        <!-- Heading -->
        <h4 class="fw-bold text-dark mb-2">مرحباً كابتن، {{ $student->name }}</h4>
        <p class="text-secondary small mb-5">أنت على وشك الدخول السريع إلى حسابك التعليمي. يرجى التأكيد للمتابعة.</p>

        <!-- Action Form -->
        <form method="POST" action="{{ request()->fullUrl() }}">
            @csrf
            <div class="d-grid gap-3">
                <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm border-0 transition-all hover-scale">
                    <i class="fas fa-sign-in-alt me-2"></i> تأكيد الدخول للمنصة
                </button>
                <a href="{{ route('center.login') }}" class="btn btn-light rounded-pill py-3 fw-bold text-muted border-0">
                    العودة لصفحة الدخول
                </a>
            </div>
        </form>

        <!-- Information Footer -->
        <div class="mt-5 pt-4 border-top">
            <div class="d-flex align-items-center justify-content-center gap-2 text-success small fw-semibold">
                <i class="bi bi-shield-check"></i>
                <span>تسجيل دخول آمن ومباشر</span>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size: 0.75rem; opacity: 0.8;">هذا الرابط صالح لمدة 15 دقيقة فقط.</p>
        </div>
    </div>
</div>

<style>
    body { background: #f0f2f5 !important; }
    .rounded-5 { border-radius: 1.75rem !important; }
    .text-primary { color: #168F7C !important; }
    .btn-primary { background-color: #168F7C !important; }
    
    .magic-icon-bg {
        width: 70px;
        height: 70px;
        background: rgba(22, 143, 124, 0.1);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: rotate(-10deg);
    }
    
    .transition-all { transition: all 0.3s ease; }
    .hover-scale:hover { transform: scale(1.02); }
</style>
@endsection
