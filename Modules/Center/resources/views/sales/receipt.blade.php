@inject('reshaper', 'App\Services\ArabicReshaper')
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            border: 2px solid #3A0CA3;
            padding: 20px;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #3A0CA3;
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            text-align: right;
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        .table th {
            background-color: #f8f9fa;
            color: #3A0CA3;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .stamp {
            border: 2px solid #28a745;
            color: #28a745;
            display: inline-block;
            padding: 3px 10px;
            transform: rotate(-15deg);
            font-weight: bold;
            margin-top: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $tenant->name }}</h1>
            <p>{{ $reshaper->reshape('إيصال استلام نقدية (Receipt)') }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td class="text-right">
                    <strong>{{ $reshaper->reshape(__('center::messages.blade_0606')) }}</strong> #{{ $payment->id }}<br>
                    <strong>{{ $reshaper->reshape(__('center::messages.blade_0607')) }}</strong> {{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : $payment->created_at->format('Y/m/d') }}
                </td>
                <td class="text-left">
                    <strong>{{ $reshaper->reshape(__('center::messages.blade_0608')) }}</strong> #{{ $payment->sale_id }}
                </td>
            </tr>
        </table>

        <div style="margin-bottom: 15px; font-size: 13px;">
            <strong>{{ $reshaper->reshape('وصلنا من السيد/السيدة:') }}</strong> {{ $payment->sale->student->name }}<br>
            <strong>{{ $reshaper->reshape(__('center::messages.blade_0609')) }}</strong> {{ number_format($payment->amount, 2) }} {{ $reshaper->reshape(__('center::messages.blade_0610', ['currency' => get_currency_symbol()])) }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ $reshaper->reshape('البيان (Description)') }}</th>
                    <th style="width: 100px;">{{ $reshaper->reshape(__('center::messages.blade_0611')) }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $reshaper->reshape(__('center::messages.blade_0612')) }} 
                        {{ $payment->sale->items->first()->reshaped_title ?? $reshaper->reshape(__('center::messages.blade_0613')) }}
                        @if($payment->sale->items->count() > 1)
                            {{ $reshaper->reshape(__('center::messages.blade_0614')) }}
                        @endif
                    </td>
                    <td>{{ number_format($payment->amount, 2) }} {{ $reshaper->reshape(__('center::messages.blade_0615', ['currency' => get_currency_symbol()])) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="info-table">
            <tr>
                <td class="text-right" style="width: 60%;">
                    <strong>{{ $reshaper->reshape(__('center::messages.blade_0616')) }}</strong> 
                    @php
                        $method = $payment->payment_method == 'cash' ? __('center::messages.blade_0617') : ($payment->payment_method == 'card' ? __('center::messages.blade_0618') : __('center::messages.blade_0619'));
                    @endphp
                    {{ $reshaper->reshape($method) }}<br>
                    <strong>{{ $reshaper->reshape(__('center::messages.blade_0620')) }}</strong> {{ number_format($payment->sale->total_amount - $payment->sale->paid_amount, 2) }} {{ $reshaper->reshape(__('center::messages.blade_0621', ['currency' => get_currency_symbol()])) }}
                    <br>
                    <span style="font-size: 10px; color: #666;">
                        <strong>{{ $reshaper->reshape('إجمالي الفاتورة:') }}</strong> {{ number_format($payment->sale->total_amount, 2) }}
                        @if($payment->sale->discount_amount > 0) | <strong>{{ $reshaper->reshape('الخصم:') }}</strong> {{ number_format($payment->sale->discount_amount, 2) }} @endif
                        @if($payment->sale->tax_amount > 0) | <strong>{{ $reshaper->reshape('الضريبة:') }}</strong> {{ number_format($payment->sale->tax_amount, 2) }} @endif
                    </span>
                </td>
                <td class="text-center" style="width: 40%;">
                    <p style="margin-bottom: 5px;">{{ $reshaper->reshape(__('center::messages.blade_0622')) }}</p>
                    <div class="stamp">{{ $reshaper->reshape('مدفوع PAID') }}</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            {{ $tenant->address ?? '' }} | {{ $tenant->phone ?? '' }}<br>
            {{ $reshaper->reshape('نشكركم على ثقتكم بنا (Thank you for your trust)') }}
        </div>
    </div>
</body>
</html>
