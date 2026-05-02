@php
    $tenant = app('tenant');
    $locale = $tenant->settings['locale'] ?? app()->getLocale();
    $isRtl = ($locale === 'ar');
    $dir = $isRtl ? 'rtl' : 'ltr';
    $borderSide = $isRtl ? 'border-right' : 'border-left';

    $greetings = [
        'ar' => 'مرحباً',
        'en' => 'Hello',
        'fr' => 'Bonjour',
    ];
    $titles = [
        'ar' => '🎓 مرحباً بك!',
        'en' => '🎓 Welcome!',
        'fr' => '🎓 Bienvenue !',
    ];
    $regards = [
        'ar' => 'مع أطيب التحيات،',
        'en' => 'Best regards,',
        'fr' => 'Cordialement,',
    ];
    $autoMsg = [
        'ar' => 'هذه الرسالة تم توليدها تلقائياً، يرجى عدم الرد عليها مباشرة.',
        'en' => 'This is an automated message. Please do not reply directly.',
        'fr' => 'Ce message est généré automatiquement. Veuillez ne pas y répondre directement.',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titles[$locale] ?? $titles['en'] }}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0fdf4; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(16, 185, 129, 0.1); overflow: hidden;">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">{{ $titles[$locale] ?? $titles['en'] }}</h1>
            <p style="color: #d1fae5; margin: 10px 0 0 0; font-size: 14px;">{{ $senderName }}</p>
        </div>

        {{-- Body --}}
        <div style="padding: 30px;">
            <h3 style="color: #1e293b; margin-top: 0; font-size: 18px;">{{ ($greetings[$locale] ?? $greetings['en']) }} {{ $studentName }}،</h3>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; line-height: 1.8; white-space: pre-wrap; font-size: 15px; {{ $borderSide }}: 4px solid #10b981;">{{ $messageContent }}</div>
        </div>

        {{-- Footer --}}
        <div style="background: #f8fafc; padding: 20px 30px; border-top: 1px solid #e2e8f0; text-align: center;">
            <p style="margin: 0; font-size: 14px; color: #64748b;">{{ $regards[$locale] ?? $regards['en'] }}</p>
            <p style="margin: 5px 0 0 0; font-size: 14px;"><strong style="color: #10b981;">{{ $senderName }}</strong></p>
            <p style="margin: 15px 0 0 0; font-size: 11px; color: #94a3b8;">{{ $autoMsg[$locale] ?? $autoMsg['en'] }}</p>
        </div>
    </div>
</body>
</html>
