<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>كشف حساب - {{ $student->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', 'Arial', sans-serif; direction: rtl; text-align: right; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #059669; padding-bottom: 10px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
        .info-section { margin-bottom: 20px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .data-table th { background-color: #f8f9fa; color: #059669; font-weight: bold; border: 1px solid #dee2e6; padding: 10px; text-align: center; }
        .data-table td { border: 1px solid #dee2e6; padding: 10px; text-align: center; font-size: 12px; }
        .total-box { margin-top: 30px; text-align: left; }
        .total-amount { display: inline-block; background-color: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; padding: 10px 20px; border-radius: 5px; font-weight: bold; font-size: 18px; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 10px; }
        .badge-paid { background-color: #dcfce7; color: #166534; }
        .badge-partial { background-color: #fef9c3; color: #854d0e; }
        .badge-unpaid { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #059669; margin: 0;">{{ $tenantName }}</h1>
        <h3 style="margin: 5px 0;">كشف حساب طالب</h3>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="15%"><strong>اسم الطالب:</strong></td>
                <td width="35%">{{ $studentName }}</td>
                <td width="15%"><strong>التاريخ:</strong></td>
                <td width="35%">{{ now()->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td><strong>رقم الهاتف:</strong></td>
                <td>{{ $student->phone }}</td>
                <td><strong>المستوى:</strong></td>
                <td>{{ $student->grade_level_name }}</td>
            </tr>
        </table>
    </div>

    <h4 style="border-right: 4px solid #059669; padding-right: 10px;">سجل الحركات المالية</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>رقم الفاتورة</th>
                <th>التاريخ</th>
                <th>البيان</th>
                <th>القيمة الإجمالية</th>
                <th>المبلغ المدفوع</th>
                <th>المتبقي</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>#{{ $sale->id }}</td>
                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                <td>
                    @foreach($sale->items as $item)
                        {{ $item->item?->title ?? 'كورس' }}@if(!$loop->last)、 @endif
                    @endforeach
                </td>
                <td>{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ number_format($sale->paid_amount, 2) }}</td>
                <td>{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</td>
                <td>
                    @if($sale->status == 'paid') <span class="badge badge-paid">مسدد</span>
                    @elseif($sale->status == 'partial') <span class="badge badge-partial">مسدد جزئي</span>
                    @else <span class="badge badge-unpaid">غير مسدد</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <p>إجمالي المديونية المتبقية:</p>
        <div class="total-amount">{{ number_format($totalDebt, 2) }} {{ __('center::sales.currency') }}</div>
    </div>

    <div class="footer">
        {{ $tenantName }} | نظام إدارة Taalimu | {{ now()->year }}
    </div>
</body>
</html>
