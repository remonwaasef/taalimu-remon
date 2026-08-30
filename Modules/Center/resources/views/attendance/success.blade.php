<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ __('center::attendance.attendance_recorded') ?? 'تم تسجيل الحضور' }} - {{ config('app.name', 'Taalimu') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
            padding: 1.5rem 1rem;
        }
        .container {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .card {
            background: #ffffff;
            border: 1px solid #EAEFF2;
            border-radius: 28px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            padding: 2.25rem 1.75rem;
        }
        .success-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #E8F5F1;
            color: #168F7C;
            border: 4px solid #bbf0e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.25rem;
            animation: scaleIn 0.4s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .title {
            font-size: 1.35rem;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .message {
            font-size: 0.875rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .details-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            text-align: start;
        }
        .details-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 0;
        }
        .details-val {
            font-weight: 800;
            color: #0f172a;
            font-family: 'Inter', 'Cairo', monospace;
        }
        .details-val.brand {
            color: #168F7C;
        }
        .btn-close-window {
            width: 100%;
            height: 48px;
            background: #f1f5f9;
            color: #334155;
            border: none;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-close-window:hover {
            background: #e2e8f0;
        }
        .footer-note {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 1.25rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h2 class="title">تم تسجيل الحضور بنجاح! 🎉</h2>
        <p class="message">{{ $message ?? 'تم تسجيل بيانات حضورك لهذه الحصة بنجاح.' }}</p>

        <div class="details-box">
            @if(isset($student))
                <div class="details-row">
                    <span>اسم الطالب:</span>
                    <span class="details-val">{{ $student->name }}</span>
                </div>
            @endif
            @if(isset($schedule) && $schedule->course)
                <div class="details-row">
                    <span>المادة / الدورة:</span>
                    <span class="details-val">{{ $schedule->course->title }}</span>
                </div>
            @endif
            <div class="details-row">
                <span>تاريخ اليوم:</span>
                <span class="details-val" dir="ltr">{{ today()->format('Y-m-d') }}</span>
            </div>
            <div class="details-row">
                <span>وقت التسجيل:</span>
                <span class="details-val brand" dir="ltr">{{ now()->format('h:i A') }}</span>
            </div>
        </div>

        <button type="button" class="btn-close-window" onclick="window.close();">
            إغلاق هذه الصفحة
        </button>
    </div>

    <p class="footer-note">Taalimu — النظام التعليمي الذكي</p>
</div>

</body>
</html>
