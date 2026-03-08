<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التسجيل بنجاح - {{ config('app.name') }}</title>
    
    <!-- Google Fonts (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            max-width: 450px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }
        .qr-container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            border: 2px dashed #e2e8f0;
            margin: 25px 0;
            display: inline-block;
        }
        .btn-save {
            background: #10b981;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            text-decoration: none;
            display: block;
        }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
        </div>
        <h3 class="fw-bold">تم تسجيلك بنجاح!</h3>
        <p class="text-muted">مرحباً بك يا <strong>{{ $user->name }}</strong>. هذا هو كود الحضور الخاص بك، يرجى الاحتفاظ بصورة منه لإظهاره عند الدخول.</p>

        <div class="qr-container">
            {!! $qrCode !!}
        </div>

        <div class="alert alert-info small text-start">
            <i class="fas fa-info-circle me-2"></i>
            يمكنك الدخول للنظام لاحقاً باستخدام رقم هاتفك ككلمة سر افتراضية.
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('student.portal', $user->qr_identifier) }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
                <i class="fas fa-user-circle me-2"></i> دخول بوابة الطالب الخاصة بك
            </a>
            <button onclick="window.print()" class="btn btn-outline-success w-100 rounded-pill py-2">
                <i class="fas fa-download me-2"></i> حفظ كود الـ QR
            </button>
        </div>
        
        <a href="/" class="btn btn-link text-muted small mt-3">العودة للرئيسية</a>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</body>
</html>
