@php
    $tenant = app('tenant');
    $locale = $tenant->settings['locale'] ?? app()->getLocale();
    $isRtl = ($locale === 'ar');
    $dir = $isRtl ? 'rtl' : 'ltr';

    $greetings = [
        'ar' => 'مرحباً',
        'en' => 'Hello',
        'fr' => 'Bonjour',
    ];
    $titles = [
        'ar' => 'إشعار جديد',
        'en' => 'New Notification',
        'fr' => 'Nouvelle Notification',
    ];
    $regards = [
        'ar' => 'مع التحية،',
        'en' => 'Regards,',
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
<body style="font-family: Arial, sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
            <h2 style="color: #10b981; margin: 0;">{{ $senderName }}</h2>
        </div>
        
        <h3 style="color: #1e293b; margin-top: 0;">{{ ($greetings[$locale] ?? $greetings['en']) }} {{ $studentName }}،</h3>
        
        <div style="background: #f8fafc; padding: 20px; border-radius: 8px; line-height: 1.6; white-space: pre-wrap;">{{ $messageContent }}</div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 14px; color: #64748b; text-align: center;">
            <p style="margin: 0;">{{ $regards[$locale] ?? $regards['en'] }}</p>
            <p style="margin: 5px 0 0 0;"><strong>{{ $senderName }}</strong></p>
            <p style="margin: 15px 0 0 0; font-size: 12px;">{{ $autoMsg[$locale] ?? $autoMsg['en'] }}</p>
        </div>
    </div>
</body>
</html>
