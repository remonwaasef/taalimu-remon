@extends('layouts.landing-new')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Cairo', sans-serif; background: #f8fafc; }
    .success-card { border-radius: 2.5rem; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.06); overflow: hidden; }
    .status-badge { background: #dcfce7; color: #15803d; border-radius: 2rem; padding: 0.5rem 1.5rem; font-weight: 700; font-size: 0.9rem; }
    .qr-container { background: white; border-radius: 2rem; padding: 2rem; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02); border: 2px dashed #e2e8f0; }
    .btn-download { border-radius: 1.25rem; padding: 1rem 2rem; font-weight: 800; transition: all 0.3s; background: #6366f1; border: none; }
    .btn-download:hover { background: #4f46e5; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); }
    .confetti-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
</style>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5 position-relative">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card success-card bg-white animate__animated animate__zoomIn">
            <div class="p-5 text-center">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h2 class="fw-black text-slate-900 mb-2">تم التسجيل بنجاح!</h2>
                    <p class="text-slate-500 mb-4">أهلاً بك يا <strong>{{ $student->name }}</strong> في مجموعة {{ $classroom->name }}</p>
                    <span class="status-badge">حسابك نشط الآن</span>
                </div>

                <div class="qr-container mb-4">
                    <p class="text-slate-400 small fw-bold mb-3 uppercase tracking-widest">بطاقة الهوية الرقمية (QR Code)</p>
                    <img src="{{ $qr_code_uri }}" alt="QR Code" class="img-fluid mb-3 shadow-sm rounded-4" style="max-width: 220px;">
                    <div class="fw-black text-brand-secondary fs-5" dir="ltr">{{ $user->qr_identifier }}</div>
                </div>

                <div class="alert alert-warning border-0 rounded-4 p-4 text-start small mb-4">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-3 mt-1 fs-5"></i>
                        <div>
                            <strong>تنبيه هام:</strong> يرجى تصوير هذه الشاشة (Screenshot) أو حفظ الكود. ستحتاج لإظهاره للمدرس عند دخول كل محاضرة لتسجيل حضورك.
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <button onclick="window.print()" class="btn btn-primary btn-download text-white">
                        <i class="fas fa-download me-2"></i>
                        <span>حفظ كصورة أو بصيغة PDF</span>
                    </button>
                    <a href="/" class="btn btn-link text-slate-400 text-decoration-none fw-bold small">العودة للرئيسية</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Confetti Effect -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    window.addEventListener('load', () => {
        confetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#6366f1', '#a855f7', '#34d399']
        });
    });
</script>
@endsection
