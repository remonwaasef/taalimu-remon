<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0fdf4; color: #333; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(16, 185, 129, 0.1); overflow: hidden;">
        
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">🎓 مرحباً بك!</h1>
            <p style="color: #d1fae5; margin: 10px 0 0 0; font-size: 14px;">{{ $senderName }}</p>
        </div>

        {{-- Body --}}
        <div style="padding: 30px;">
            <h3 style="color: #1e293b; margin-top: 0; font-size: 18px;">مرحباً {{ $studentName }}،</h3>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; line-height: 1.8; white-space: pre-wrap; font-size: 15px; border-right: 4px solid #10b981;">{{ $messageContent }}</div>
        </div>

        {{-- Footer --}}
        <div style="background: #f8fafc; padding: 20px 30px; border-top: 1px solid #e2e8f0; text-align: center;">
            <p style="margin: 0; font-size: 14px; color: #64748b;">مع أطيب التحيات،</p>
            <p style="margin: 5px 0 0 0; font-size: 14px;"><strong style="color: #10b981;">{{ $senderName }}</strong></p>
            <p style="margin: 15px 0 0 0; font-size: 11px; color: #94a3b8;">هذه الرسالة تم توليدها تلقائياً، يرجى عدم الرد عليها مباشرة.</p>
        </div>
    </div>
</body>
</html>
