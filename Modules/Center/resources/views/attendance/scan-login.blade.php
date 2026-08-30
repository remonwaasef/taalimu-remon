<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ __('center::attendance.scan_attendance_code') }} - {{ config('app.name', 'Taalimu') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', 'Inter', system-ui, -apple-system, sans-serif;
        }
        body {
            background-color: #FAFAFA;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1rem;
        }
        .container {
            width: 100%;
            max-width: 440px;
        }
        .header-box {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .icon-badge {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: #E8F5F1;
            color: #168F7C;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 12px rgba(22, 143, 124, 0.12);
        }
        .title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        .session-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-top: 0.25rem;
        }
        .card {
            background: #ffffff;
            border: 1px solid #EAEFF2;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            padding: 1.75rem 1.5rem;
        }
        .tabs {
            display: flex;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 4px;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            gap: 4px;
        }
        .tab-btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 10px;
            border: none;
            background: transparent;
            font-size: 0.78rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tab-btn.active {
            background: #ffffff;
            color: #168F7C;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }
        .alert {
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .alert-info {
            background: #E8F5F1;
            border: 1px solid #bbf0e3;
            color: #168F7C;
        }
        .alert-warning {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
        }
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .form-group {
            margin-bottom: 1rem;
            text-align: start;
        }
        .label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.4rem;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            right: 14px;
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
        }
        html[dir="ltr"] .input-icon {
            right: auto;
            left: 14px;
        }
        .input-control {
            width: 100%;
            height: 48px;
            padding: 0 40px 0 14px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
        }
        html[dir="ltr"] .input-control {
            padding: 0 14px 0 40px;
        }
        .input-control:focus {
            background: #ffffff;
            border-color: #168F7C;
            box-shadow: 0 0 0 3px rgba(22, 143, 124, 0.15);
        }
        .btn-submit {
            width: 100%;
            height: 50px;
            background: #168F7C;
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(22, 143, 124, 0.25);
            transition: all 0.2s ease;
            margin-top: 0.5rem;
        }
        .btn-submit:hover, .btn-submit:active {
            background: #0f6c5e;
            transform: translateY(-1px);
        }
        .footer-note {
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header-box">
        <div class="icon-badge">
            <i class="fas fa-qrcode"></i>
        </div>
        <h1 class="title">{{ __('center::attendance.scan_attendance_code') }}</h1>
        <div class="session-info">
            <span>{{ $schedule->course->title }}</span>
            <span>•</span>
            <span dir="ltr">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</span>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <!-- Mode Switcher Tabs -->
        <div class="tabs">
            <button type="button" class="tab-btn active" id="tabPhoneBtn" onclick="switchTab('phone')">
                <i class="fas fa-mobile-alt me-1"></i> رقم الهاتف / الكود
            </button>
            <button type="button" class="tab-btn" id="tabLoginBtn" onclick="switchTab('login')">
                <i class="fas fa-key me-1"></i> البريد وكلمة المرور
            </button>
        </div>

        @if(isset($message))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Form 1: Quick Phone / Code Attendance (Default - No Password Needed) -->
        <form id="phoneForm" method="POST" action="{{ route('center.attendance.loginAndMark', array_merge(['schedule' => $schedule], request()->query())) }}">
            @csrf
            <input type="hidden" name="qr_url" value="{{ $qrUrl ?? request()->fullUrl() }}">
            
            <div class="form-group">
                <label class="label" for="phone_or_code">رقم الهاتف المسجل أو كود الطالب</label>
                <div class="input-wrapper">
                    <i class="fas fa-phone input-icon"></i>
                    <input 
                        type="text" 
                        id="phone_or_code" 
                        name="phone_or_code" 
                        value="{{ old('phone_or_code') }}" 
                        placeholder="أدخل رقم الهاتف (مثال: 01012345678)"
                        required 
                        autofocus
                        class="input-control"
                    >
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-check-circle"></i>
                <span>تأكيد تسجيل الحضور فوراً</span>
            </button>
        </form>

        <!-- Form 2: Email & Password (Alternative) -->
        <form id="loginForm" method="POST" action="{{ route('center.attendance.loginAndMark', array_merge(['schedule' => $schedule], request()->query())) }}" style="display: none;">
            @csrf
            <input type="hidden" name="qr_url" value="{{ $qrUrl ?? request()->fullUrl() }}">
            
            <div class="form-group">
                <label class="label" for="email">البريد الإلكتروني</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="student@example.com"
                        class="input-control"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="label" for="password">كلمة المرور</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        class="input-control"
                    >
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i>
                <span>تسجيل الدخول والحضور</span>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer-note">
        <i class="fas fa-shield-alt"></i>
        <span>نظام الحضور الذكي والآمن — Taalimu</span>
    </div>
</div>

<script>
    function switchTab(mode) {
        const phoneForm = document.getElementById('phoneForm');
        const loginForm = document.getElementById('loginForm');
        const tabPhoneBtn = document.getElementById('tabPhoneBtn');
        const tabLoginBtn = document.getElementById('tabLoginBtn');
        const phoneInput = document.getElementById('phone_or_code');
        const emailInput = document.getElementById('email');

        if (mode === 'phone') {
            phoneForm.style.display = 'block';
            loginForm.style.display = 'none';
            tabPhoneBtn.classList.add('active');
            tabLoginBtn.classList.remove('active');
            if (phoneInput) phoneInput.focus();
        } else {
            phoneForm.style.display = 'none';
            loginForm.style.display = 'block';
            tabPhoneBtn.classList.remove('active');
            tabLoginBtn.classList.add('active');
            if (emailInput) emailInput.focus();
        }
    }

    @if($errors->has('email') || old('email'))
        switchTab('login');
    @endif
</script>

</body>
</html>
