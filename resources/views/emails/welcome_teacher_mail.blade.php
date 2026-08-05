@php
    $tenant = app('tenant');
    $locale = $tenant->settings['locale'] ?? app()->getLocale();
    $isRtl = true; 
    $dir = $isRtl ? 'rtl' : 'ltr';
    $borderSide = $isRtl ? 'border-right' : 'border-left';
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في منصة {{ $centerName }}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0fdf4; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(16, 185, 129, 0.1); overflow: hidden;">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #10b981 0%, #2E8B83 100%); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">🎓 مرحباً بك كمعلم معنا!</h1>
            <p style="color: #CCE9E7; margin: 10px 0 0 0; font-size: 14px;">{{ $centerName }}</p>
        </div>

        {{-- Body --}}
        <div style="padding: 30px; text-align: right;">
            <h3 style="color: #1e293b; margin-top: 0; font-size: 18px;">مرحباً أ. {{ $teacherName }}،</h3>
            
            <p style="font-size: 15px; color: #475569; line-height: 1.6;">
                تم إنشاء حسابك كمعلم في منصة <strong>{{ $centerName }}</strong> بنجاح. يمكنك الآن الدخول إلى المنصة والبدء في إدارة دوراتك وطلابك.
            </p>

            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; line-height: 1.8; font-size: 15px; border-right: 4px solid #10b981; margin: 20px 0;">
                <p style="margin: 0 0 10px 0;"><strong>بيانات الدخول:</strong></p>
                <p style="margin: 0 0 5px 0;"><strong>رابط الدخول:</strong> <a href="{{ $loginLink }}" style="color: #10b981; text-decoration: none;">{{ $loginLink }}</a></p>
                <p style="margin: 0 0 5px 0;"><strong>البريد الإلكتروني:</strong> <span dir="ltr">{{ $emailAddress }}</span></p>
                <p style="margin: 0 0 0 0;"><strong>كلمة المرور:</strong> <span dir="ltr" style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">{{ $plainPassword }}</span></p>
            </div>

            <p style="font-size: 15px; color: #475569; line-height: 1.6;">
                نوصي بتغيير كلمة المرور الخاصة بك بعد أول تسجيل دخول لأسباب أمنية.
            </p>
        </div>

        {{-- Footer --}}
        <div style="background: #f8fafc; padding: 20px 30px; border-top: 1px solid #e2e8f0; text-align: center;">
            <p style="margin: 0; font-size: 14px; color: #64748b;">مع أطيب التحيات،</p>
            <p style="margin: 5px 0 0 0; font-size: 14px;"><strong style="color: #10b981;">فريق {{ $centerName }}</strong></p>
            <p style="margin: 15px 0 0 0; font-size: 11px; color: #94a3b8;">هذه الرسالة تم توليدها تلقائياً، يرجى عدم الرد عليها مباشرة.</p>
        </div>
    </div>
</body>
</html>
