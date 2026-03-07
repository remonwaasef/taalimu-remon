@extends('layouts.landing-new')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Cairo', sans-serif; background: #f8fafc; }
    .reg-card { border-radius: 2rem; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.05); overflow: hidden; }
    .brand-glow { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    .form-control { border-radius: 1rem; padding: 0.8rem 1.2rem; border: 2px solid #e2e8f0; transition: all 0.3s; }
    .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
    .btn-submit { border-radius: 1rem; padding: 1rem; font-weight: 800; letter-spacing: 0.5px; transition: all 0.3s; background: #6366f1; border: none; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); background: #4f46e5; }
</style>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card reg-card bg-white animate__animated animate__fadeInUp">
            <div class="brand-glow p-5 text-white text-center">
                <h2 class="fw-black mb-2">{{ $tenant->name }}</h2>
                <p class="opacity-75 mb-0">{{ __('التسجيل في مجموعة') }}: {{ $classroom->name }}</p>
            </div>
            
            <div class="p-5">
                @if(session('info'))
                    <div class="alert alert-info border-0 rounded-4 p-4 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>{{ session('info') }}</div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('center.groups.join.submit', ['tenant' => $tenant->domain, 'uuid' => $classroom->invite_uuid]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-slate-600 mb-2 small uppercase tracking-wider">{{ __('الأسم بالكامل') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="أدخل اسمك الثلاثي" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-slate-600 mb-2 small uppercase tracking-wider">{{ __('رقم الهاتف (للدخول)') }}</label>
                        <input type="text" name="phone" class="form-control" placeholder="01xxxxxxxxx" required>
                        <div class="form-text mt-2 text-slate-400 small">سيتم استخدام هذا الرقم للدخول إلى حسابك لاحقاً</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-slate-600 mb-2 small uppercase tracking-wider">{{ __('رقم هاتف ولي الأمر') }}</label>
                        <input type="text" name="parent_phone" class="form-control" placeholder="01xxxxxxxxx">
                    </div>

                    <button type="submit" class="btn btn-primary w-full btn-submit text-white">
                        <span>{{ __('تأكيد التسجيل الآن') }}</span>
                        <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </form>

                <div class="mt-5 pt-4 border-top text-center">
                    <p class="text-slate-400 small mb-0">نظام إدارة المحاضرات {{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
