<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'cairo', sans-serif;
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
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #3A0CA3;
            margin: 0;
            font-size: 24px;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .receipt-info div {
            width: 48%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            text-align: right;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .table th {
            background-color: #f8f9fa;
            color: #3A0CA3;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .stamp {
            border: 2px solid #28a745;
            color: #28a745;
            display: inline-block;
            padding: 5px 15px;
            transform: rotate(-15deg);
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $tenant->name }}</h1>
            <p>إيصال استلام نقدية</p>
        </div>

        <div class="receipt-info" style="margin-bottom: 10px;">
            <div style="float: right;">
                <strong>الرقم:</strong> #{{ $payment->id }}<br>
                <strong>التاريخ:</strong> {{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : $payment->created_at->format('Y/m/d') }}
            </div>
            <div style="float: left; text-align: left;">
                <strong>رقم الفاتورة:</strong> #{{ $payment->sale_id }}
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="margin-bottom: 20px;">
            <strong>وصلنا من السيد/السيدة:</strong> {{ $payment->sale->student->name }}<br>
            <strong>مبلغ وقدره:</strong> {{ number_format($payment->amount, 2) }} ج.م
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>البيان</th>
                    <th>القيمة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>دفعة من حساب كورس: 
                        {{ $payment->sale->items->first()->item->title ?? 'مبيعات' }}
                        @if($payment->sale->items->count() > 1)
                            (وآخرون)
                        @endif
                    </td>
                    <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                </tr>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between;">
            <div style="float: right;">
                <strong>طريقة الدفع:</strong> {{ $payment->payment_method == 'cash' ? 'نقدي' : ($payment->payment_method == 'card' ? 'فيزا' : 'تحويل') }}<br>
                <strong>المتبقي في الفاتورة:</strong> {{ number_format($payment->sale->total_amount - $payment->sale->paid_amount, 2) }} ج.م
            </div>
            <div style="float: left; text-align: center; width: 150px;">
                <p>توقيع المستلم</p>
                <br>
                <div class="stamp">مدفوع</div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="footer">
            {{ $tenant->address ?? '' }} | {{ $tenant->phone ?? '' }}<br>
            نشكركم على ثقتكم بنا
        </div>
    </div>
</body>
</html>
