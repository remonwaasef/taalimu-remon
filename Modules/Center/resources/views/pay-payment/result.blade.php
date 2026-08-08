<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة الدفع — {{ $sale->tenant->name ?? 'المركز' }}</title>
    <meta name="robots" content="noindex">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-[system-ui]">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
            @if($status === 'success')
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h1 class="text-lg font-black text-slate-900 mb-1">تم استلام عملية الدفع بنجاح ✓</h1>
<p class="text-xs text-slate-400 font-semibold mb-6">
                    أهلاً ولي الأمر، تم إشعار المركز بعملية السداد.
                    <br>سيظهر السداد على الفاتورة خلال لحظات.
                </p>
            @elseif($status === 'paid')
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h1 class="text-lg font-black text-slate-900 mb-1">هذه الفاتورة مدفوعة بالكامل ✔</h1>
                <p class="text-xs text-slate-400 font-semibold mb-6">شكراً لك! تم سداد جميع المستحقات الخاصة بهذه الفاتورة.</p>
            @else
                <div class="w-16 h-16 mx-auto rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl mb-4">
                    <i class="fas fa-circle-xmark"></i>
                </div>
                <h1 class="text-lg font-black text-slate-900 mb-1">لم يكتمل الدفع</h1>
<p class="text-xs text-slate-400 font-semibold mb-6">
                    ربما تم إلغاء الدفع أو انتهت مهلة الدفع، يمكنك المحاولة مرة أخرى من صفحة السداد.
                </p>
                <a href="{{ \Modules\Center\Http\Controllers\OnlinePaymentController::payLink($sale) }}"
                   class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-black px-8 py-3 rounded-2xl text-sm transition-all">
                    إعادة المحاولة
                </a>
            @endif
        </div>
    </div>
</body>
</html>