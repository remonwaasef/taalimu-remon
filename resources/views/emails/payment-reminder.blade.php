<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكير بالمصروفات</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 50px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">
                                {{ $tenantName }}
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 12px 0 0; font-size: 15px;">
                                @if($stage === 'due_day')
                                    ⏰ اليوم موعد السداد
                                @elseif(str_starts_with($stage, 'overdue'))
                                    ⚠️ تنبيه: تأخر سداد المصروفات
                                @else
                                    📋 تذكير بموعد الدفع القادم
                                @endif
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 50px 40px 40px;">
                            <p style="color: #374151; font-size: 16px; line-height: 1.8; margin: 0 0 32px;">
                                مرحباً،
                                <br>
                                نود تذكيركم بأن مصروفات الطالب/ة <strong style="color: #10b981;">{{ $studentName }}</strong>
                                @if(str_starts_with($stage, 'overdue'))
                                    قد <strong style="color: #ef4444;">تأخر سدادها</strong>.
                                @elseif($stage === 'due_day')
                                    مستحقة <strong>اليوم</strong>.
                                @else
                                    مستحقة يوم <strong>{{ $dueDay }}</strong> من الشهر الحالي.
                                @endif
                            </p>

                            {{-- Amount Card --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 24px; text-align: center;">
                                        <p style="color: #6b7280; font-size: 13px; margin: 0 0 8px; font-weight: 600;">المبلغ المستحق</p>
                                        <p style="color: #059669; font-size: 32px; font-weight: 800; margin: 0;">
                                            {{ $amount }} <span style="font-size: 16px;">{{ $currency }}</span>
                                        </p>
                                        <p style="color: #9ca3af; font-size: 12px; margin: 8px 0 0;">{{ $currentMonth }}</p>
                                    </td>
                                </tr>
                            </table>

                            @if(!empty($customMessage))
                                <div style="background: #fffbeb; border-right: 4px solid #f59e0b; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                    <p style="color: #92400e; font-size: 14px; line-height: 1.8; margin: 0;">
                                        {!! nl2br(e($customMessage)) !!}
                                    </p>
                                </div>
                            @endif

                            @if(str_starts_with($stage, 'overdue'))
                                <div style="background: #fef2f2; border-right: 4px solid #ef4444; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                    <p style="color: #991b1b; font-size: 14px; line-height: 1.8; margin: 0;">
                                        <strong>⚠️ تنبيه:</strong> لقد انتهى الموعد المحدد للسداد. نرجو التواصل مع الإدارة لتسوية المبلغ المستحق في أقرب وقت.
                                    </p>
                                </div>
                            @endif

                            <p style="color: #6b7280; font-size: 14px; line-height: 1.8; margin: 0;">
                                شكراً لتعاونكم.
                                <br>
                                <strong>إدارة {{ $tenantName }}</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #f3f4f6;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                هذه رسالة تلقائية من نظام {{ $tenantName }}. لا ترد على هذا الإيميل.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
