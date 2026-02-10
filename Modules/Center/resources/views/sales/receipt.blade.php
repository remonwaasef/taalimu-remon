@inject('reshaper', 'App\Services\ArabicReshaper')
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #f7f9fc;
            color: #1a202c;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .wrapper {
            padding: 40px;
            position: relative;
        }
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(79, 70, 229, 0.03);
            white-space: nowrap;
            z-index: -1;
            font-weight: bold;
        }
        .container {
            background: #ffffff;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 0;
            overflow: hidden;
            border: 1px solid #edf2f7;
        }
        .header-section {
            background-color: #4f46e5;
            color: white;
            padding: 40px;
            overflow: hidden;
            position: relative;
        }
        .header-section::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .brand-logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 12px;
            text-align: center;
            line-height: 60px;
            color: #4f46e5;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .content {
            padding: 40px;
        }
        .info-card {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .value {
            font-size: 15px;
            font-weight: bold;
            color: #2d3748;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .table th {
            text-align: right;
            padding: 15px;
            color: #4a5568;
            border-bottom: 2px solid #edf2f7;
            font-size: 11px;
            text-transform: uppercase;
        }
        .table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f1f5f9;
        }
        .amount-text {
            font-size: 22px;
            font-weight: bold;
            color: #4f46e5;
        }
        .qr-placeholder {
            width: 80px;
            height: 80px;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            padding: 5px;
            display: inline-block;
        }
        .qr-inner {
            width: 100%;
            height: 100%;
            background: #f8fafc;
            border: 1px dashed #cbd5e0;
            border-radius: 5px;
            position: relative;
        }
        .qr-inner::after {
            content: 'QR';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #a0aec0;
            font-size: 10px;
        }
        .status-stamp {
            border: 3px solid #059669;
            color: #059669;
            padding: 8px 30px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
            transform: rotate(-15deg);
            display: inline-block;
            background: rgba(5, 150, 105, 0.02);
        }
        .footer {
            text-align: center;
            padding: 30px;
            background: #f8fafc;
            border-top: 1px solid #edf2f7;
            color: #718096;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="watermark">{{ $reshaper->reshape($tenant->name) }}</div>
    
    <div class="wrapper">
        <div class="container">
            <div class="header-section">
                <table class="header-table">
                    <tr>
                        <td class="text-right">
                            <div class="brand-logo">{{ mb_substr($tenant->name, 0, 1) }}</div>
                            <h1 style="margin:0; font-size: 24px;">{{ $tenant->name }}</h1>
                        </td>
                        <td class="text-left">
                            <div style="font-size: 28px; font-weight: bold; margin-bottom: 5px;">{{ $reshaper->reshape('إيصال نقدية') }}</div>
                            <div style="opacity: 0.8; font-size: 14px;">OFFICIAL PAYMENT RECEIPT</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="content">
                <div class="info-card">
                    <table class="info-table">
                        <tr>
                            <td class="text-right" style="width: 33%;">
                                <div class="label">{{ $reshaper->reshape('رقم الإيصال') }}</div>
                                <div class="value">#REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="text-center" style="width: 33%;">
                                <div class="label">{{ $reshaper->reshape('رقم الفاتورة') }}</div>
                                <div class="value">#INV-{{ $payment->sale_id }}</div>
                            </td>
                            <td class="text-left" style="width: 33%;">
                                <div class="label">{{ $reshaper->reshape('تاريخ السداد') }}</div>
                                <div class="value">{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : $payment->created_at->format('Y/m/d') }}</div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom: 40px;">
                    <div class="label" style="margin-bottom: 10px;">{{ $reshaper->reshape('بيانات العميل (Student Details)') }}</div>
                    <div style="font-size: 18px; font-weight: bold;">{{ $payment->sale->student->name }}</div>
                    <div style="color: #718096; font-size: 13px;">{{ $payment->sale->student->phone ?? '' }}</div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ $reshaper->reshape('الوصف (Description)') }}</th>
                            <th class="text-left" style="width: 150px;">{{ $reshaper->reshape('القيمة المدفوعة') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="value" style="font-size: 16px;">{{ $payment->sale->items->first()->reshaped_title ?? $reshaper->reshape('مبيعات عامة') }}</div>
                                <div style="color: #718096; font-size: 12px; margin-top: 5px;">
                                    {{ $reshaper->reshape('طريقة الدفع:') }} 
                                    @php $method = $payment->payment_method == 'cash' ? 'نقدي (Cash)' : ($payment->payment_method == 'card' ? 'فيزا (Card)' : 'تحويل (Transfer)'); @endphp
                                    {{ $reshaper->reshape($method) }}
                                </div>
                            </td>
                            <td class="text-left">
                                <div class="amount-text">{{ number_format($payment->amount, 2) }}</div>
                                <div class="label" style="display:inline">{{ $reshaper->reshape('جنيه مصري') }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 60%;">
                            <div class="info-card" style="margin-bottom: 0;">
                                <div class="label">{{ $reshaper->reshape('حالة الحساب') }}</div>
                                <div style="margin-top: 5px;">
                                    <span style="color: #718096;">{{ $reshaper->reshape('المتبقي المطلوب سداده:') }}</span>
                                    <span class="value" style="color: #e53e3e; margin-right: 10px;">{{ number_format($payment->sale->total_amount - $payment->sale->paid_amount, 2) }} {{ $reshaper->reshape('ج.م') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-left" style="width: 40%; padding-left: 20px;">
                            <div class="status-stamp">{{ $reshaper->reshape('مدفوع | PAID') }}</div>
                        </td>
                    </tr>
                </table>

                <table style="width: 100%; margin-top: 60px;">
                    <tr>
                        <td style="width: 50%;">
                            <div class="qr-placeholder">
                                <div class="qr-inner"></div>
                            </div>
                            <div style="font-size: 9px; color: #a0aec0; margin-top: 5px;">
                                {{ $reshaper->reshape('مسح الرمز للتحقق') }}
                            </div>
                        </td>
                        <td class="text-left" style="vertical-align: bottom;">
                            <div style="width: 150px; border-top: 1px solid #cbd5e0; display: inline-block;"></div>
                            <div class="label" style="margin-top: 5px;">{{ $reshaper->reshape('توقيع المحاسب / الختم') }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <div style="font-weight: bold; color: #4a5568; margin-bottom: 8px;">{{ $reshaper->reshape('نشكركم لاختياركم منصتنا التعليمية') }}</div>
                <div>{{ $tenant->address ?? '' }} | {{ $tenant->phone ?? '' }} | {{ $tenant->email ?? '' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
