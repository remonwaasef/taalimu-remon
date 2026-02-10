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
            background-color: #fcfcfc;
            color: #2D3748;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .wrapper {
            padding: 30px;
        }
        .container {
            background: #fff;
            border: 1px solid #E2E8F0;
            padding: 40px;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .top-stripe {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #4F46E5, #7C3AED);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #1A202C;
            margin: 0 0 8px 0;
            font-size: 26px;
            letter-spacing: -0.5px;
        }
        .header .receipt-label {
            display: inline-block;
            background: #EEF2FF;
            color: #4338CA;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 35px;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 10px 0;
            vertical-align: top;
        }
        .label {
            color: #718096;
            font-size: 11px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .value {
            font-size: 14px;
            font-weight: bold;
            color: #2D3748;
        }
        .customer-box {
            background-color: #F8FAFC;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border-right: 4px solid #4F46E5;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        .table th {
            text-align: right;
            padding: 12px 15px;
            background-color: #F1F5F9;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            border-radius: 0;
        }
        .table td {
            padding: 15px;
            border-bottom: 1px solid #F1F5F9;
            font-size: 13px;
        }
        .amount-display {
            font-size: 18px;
            font-weight: bold;
            color: #4F46E5;
        }
        .summary-section {
            width: 100%;
            margin-top: 20px;
        }
        .payment-status {
            text-align: center;
            padding: 20px;
        }
        .stamp {
            border: 3px double #059669;
            color: #059669;
            display: inline-block;
            padding: 8px 25px;
            transform: rotate(-10deg);
            font-weight: bold;
            border-radius: 8px;
            font-size: 18px;
            text-transform: uppercase;
            opacity: 0.8;
            background: rgba(5, 150, 105, 0.05);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
            color: #A0AEC0;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="top-stripe"></div>
            
            <div class="header">
                <h1>{{ $tenant->name }}</h1>
                <div class="receipt-label">
                    {{ $reshaper->reshape('إيصال استلام نقدية | RECEIPT') }}
                </div>
            </div>

            <table class="info-grid">
                <tr>
                    <td class="text-right" style="width: 50%;">
                        <span class="label">{{ $reshaper->reshape('الرقم') }}</span>
                        <span class="value">#{{ $payment->id }}</span>
                    </td>
                    <td class="text-left" style="width: 50%;">
                        <span class="label">{{ $reshaper->reshape('التاريخ') }}</span>
                        <span class="value">{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : $payment->created_at->format('Y/m/d') }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" style="width: 50%;">
                        <span class="label">{{ $reshaper->reshape('رقم الفاتورة') }}</span>
                        <span class="value">#{{ $payment->sale_id }}</span>
                    </td>
                    <td class="text-left" style="width: 50%;">
                        <span class="label">{{ $reshaper->reshape('طريقة الدفع') }}</span>
                        <span class="value">
                            @php
                                $method = $payment->payment_method == 'cash' ? 'نقدي (Cash)' : ($payment->payment_method == 'card' ? 'فيزا (Card)' : 'تحويل (Transfer)');
                            @endphp
                            {{ $reshaper->reshape($method) }}
                        </span>
                    </td>
                </tr>
            </table>

            <div class="customer-box">
                <span class="label">{{ $reshaper->reshape('وصلنا من السيد / السيدة') }}</span>
                <span class="value" style="font-size: 16px;">{{ $payment->sale->student->name }}</span>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">{{ $reshaper->reshape('البيان (Description)') }}</th>
                        <th class="text-left" style="width: 120px; border-top-left-radius: 8px; border-bottom-left-radius: 8px;">{{ $reshaper->reshape('المبلغ') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="value">{{ $payment->sale->items->first()->reshaped_title ?? $reshaper->reshape('مبيعات') }}</div>
                            <div class="label" style="margin-top: 4px;">{{ $reshaper->reshape('دفعة من حساب كورس') }} @if($payment->sale->items->count() > 1) {{ $reshaper->reshape('(وآخرون)') }} @endif</div>
                        </td>
                        <td class="text-left">
                            <span class="amount-display">{{ number_format($payment->amount, 2) }}</span>
                            <span class="label" style="display:inline">{{ $reshaper->reshape('ج.م') }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="summary-section">
                <tr>
                    <td class="text-right" style="vertical-align: middle;">
                        <span class="label">{{ $reshaper->reshape('المتبقي في الفاتورة') }}</span>
                        <span class="value" style="color: #E53E3E;">{{ number_format($payment->sale->total_amount - $payment->sale->paid_amount, 2) }} {{ $reshaper->reshape('ج.م') }}</span>
                    </td>
                    <td class="payment-status">
                        <div class="stamp">{{ $reshaper->reshape('مدفوع | PAID') }}</div>
                    </td>
                </tr>
            </table>

            <div class="footer">
                <div style="margin-bottom: 10px; color: #4A5568; font-weight: bold;">
                    {{ $reshaper->reshape('نشكركم على ثقتكم بنا') }}
                </div>
                {{ $tenant->address ?? '' }} @if($tenant->address && $tenant->phone) | @endif {{ $tenant->phone ?? '' }}
            </div>
        </div>
    </div>
</body>
</html>
