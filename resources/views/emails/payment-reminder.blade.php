@php
    $tenant = app('tenant');
    $locale = $tenant->settings['locale'] ?? app()->getLocale();
    $isRtl = ($locale === 'ar');
    $dir = $isRtl ? 'rtl' : 'ltr';
    $borderSide = $isRtl ? 'border-right' : 'border-left';

    // Stage descriptions
    $stageHeaders = [
        'ar' => [
            'due_day' => '⏰ اليوم موعد السداد',
            'overdue' => '⚠️ تنبيه: تأخر سداد المصروفات',
            'default' => '📋 تذكير بموعد الدفع القادم',
        ],
        'en' => [
            'due_day' => '⏰ Payment Due Today',
            'overdue' => '⚠️ Alert: Overdue Payment',
            'default' => '📋 Upcoming Payment Reminder',
        ],
        'fr' => [
            'due_day' => '⏰ Paiement dû aujourd\'hui',
            'overdue' => '⚠️ Alerte : Paiement en retard',
            'default' => '📋 Rappel de paiement à venir',
        ],
    ];

    $lHeaders = $stageHeaders[$locale] ?? $stageHeaders['en'];
    if ($stage === 'due_day') {
        $headerText = $lHeaders['due_day'];
    } elseif (str_starts_with($stage, 'overdue')) {
        $headerText = $lHeaders['overdue'];
    } else {
        $headerText = $lHeaders['default'];
    }

    $i18n = [
        'ar' => [
            'greeting' => 'مرحباً،',
            'student_fees' => 'نود تذكيركم بأن مصروفات الطالب/ة',
            'overdue_status' => 'قد <strong style="color: #ef4444;">تأخر سدادها</strong>.',
            'due_today' => 'مستحقة <strong>اليوم</strong>.',
            'due_on_day' => 'مستحقة يوم <strong>:day</strong> من الشهر الحالي.',
            'amount_due' => 'المبلغ المستحق',
            'overdue_warning' => '<strong>⚠️ تنبيه:</strong> لقد انتهى الموعد المحدد للسداد. نرجو التواصل مع الإدارة لتسوية المبلغ المستحق في أقرب وقت.',
            'thanks' => 'شكراً لتعاونكم.',
            'management' => 'إدارة :name',
            'auto_msg' => 'هذه رسالة تلقائية من نظام :name. لا ترد على هذا الإيميل.',
        ],
        'en' => [
            'greeting' => 'Hello,',
            'student_fees' => 'We would like to remind you that the fees for student',
            'overdue_status' => 'are <strong style="color: #ef4444;">overdue</strong>.',
            'due_today' => 'are due <strong>today</strong>.',
            'due_on_day' => 'are due on the <strong>:day</strong> of this month.',
            'amount_due' => 'Amount Due',
            'overdue_warning' => '<strong>⚠️ Notice:</strong> The payment deadline has passed. Please contact the administration to settle the outstanding amount as soon as possible.',
            'thanks' => 'Thank you for your cooperation.',
            'management' => ':name Management',
            'auto_msg' => 'This is an automated message from :name. Please do not reply to this email.',
        ],
        'fr' => [
            'greeting' => 'Bonjour,',
            'student_fees' => 'Nous souhaitons vous rappeler que les frais de l\'élève',
            'overdue_status' => 'sont <strong style="color: #ef4444;">en retard</strong>.',
            'due_today' => 'sont dus <strong>aujourd\'hui</strong>.',
            'due_on_day' => 'sont dus le <strong>:day</strong> de ce mois.',
            'amount_due' => 'Montant dû',
            'overdue_warning' => '<strong>⚠️ Attention :</strong> La date limite de paiement est dépassée. Veuillez contacter l\'administration pour régler le montant dû dès que possible.',
            'thanks' => 'Merci pour votre coopération.',
            'management' => 'La direction de :name',
            'auto_msg' => 'Ceci est un message automatique de :name. Veuillez ne pas répondre à cet e-mail.',
        ],
    ];

    $t = $i18n[$locale] ?? $i18n['en'];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $headerText }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #2E8B83 100%); padding: 50px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">
                                {{ $tenantName }}
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 12px 0 0; font-size: 15px;">
                                {{ $headerText }}
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 50px 40px 40px;">
                            <p style="color: #374151; font-size: 16px; line-height: 1.8; margin: 0 0 32px;">
                                {{ $t['greeting'] }}
                                <br>
                                {!! $t['student_fees'] !!} <strong style="color: #10b981;">{{ $studentName }}</strong>
                                @if(str_starts_with($stage, 'overdue'))
                                    {!! $t['overdue_status'] !!}
                                @elseif($stage === 'due_day')
                                    {!! $t['due_today'] !!}
                                @else
                                    {!! str_replace(':day', $dueDay, $t['due_on_day']) !!}
                                @endif
                            </p>

                            {{-- Amount Card --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #f0fdf4 0%, #E6F4F3 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 24px; text-align: center;">
                                        <p style="color: #6b7280; font-size: 13px; margin: 0 0 8px; font-weight: 600;">{{ $t['amount_due'] }}</p>
                                        <p style="color: #2E8B83; font-size: 32px; font-weight: 800; margin: 0;">
                                            {{ $amount }} <span style="font-size: 16px;">{{ $currency }}</span>
                                        </p>
                                        <p style="color: #9ca3af; font-size: 12px; margin: 8px 0 0;">{{ $currentMonth }}</p>
                                    </td>
                                </tr>
                            </table>

                            @if(!empty($customMessage))
                                <div style="background: #fffbeb; {{ $borderSide }}: 4px solid #f59e0b; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                    <p style="color: #92400e; font-size: 14px; line-height: 1.8; margin: 0;">
                                        {!! nl2br(e($customMessage)) !!}
                                    </p>
                                </div>
                            @endif

                            @if(str_starts_with($stage, 'overdue'))
                                <div style="background: #fef2f2; {{ $borderSide }}: 4px solid #ef4444; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                    <p style="color: #991b1b; font-size: 14px; line-height: 1.8; margin: 0;">
                                        {!! $t['overdue_warning'] !!}
                                    </p>
                                </div>
                            @endif

                            <p style="color: #6b7280; font-size: 14px; line-height: 1.8; margin: 0;">
                                {{ $t['thanks'] }}
                                <br>
                                <strong>{!! str_replace(':name', $tenantName, $t['management']) !!}</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #f3f4f6;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                {{ str_replace(':name', $tenantName, $t['auto_msg']) }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
