<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سداد فاتورة — {{ $sale->tenant->name ?? 'المركز' }}</title>
    <meta name="robots" content="noindex">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-[system-ui]">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            @if(session('error'))
                <div class="bg-red-50 border-b border-red-100 px-6 py-3 text-xs font-semibold text-red-600">
                    <i class="fas fa-triangle-exclamation me-1"></i> {{ session('error') }}
                </div>
            @endif

            <div class="p-7">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400">{{ $sale->tenant->name ?? 'المركز' }}</p>
                        <h1 class="text-lg font-black text-slate-900">سداد فاتورة</h1>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold">الطالب</span>
                        <span class="font-bold text-slate-800">{{ $sale->student->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold">الفاتورة</span>
                        <span class="font-bold text-slate-800">#{{ $sale->id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold">الوصف</span>
                        <span class="font-semibold text-slate-700">{{ $sale->notes ?? 'رسوم دراسية' }}</span>
                    </div>
                    @if($sale->items->count())
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-semibold">البنود</span>
                            <span class="font-semibold text-slate-700">{{ $sale->items->pluck('item_id')->count() }} بند</span>
                        </div>
                    @endif
                    <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
                        <span class="text-slate-400 font-semibold">إجمالي الفاتورة</span>
                        <span class="text-sm font-bold text-slate-600">{{ number_format($sale->total_amount, 2) }} ج.م</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold">المدفوع</span>
                        <span class="text-sm font-bold text-emerald-600">{{ number_format($sale->paid_amount, 2) }} ج.م</span>
                    </div>
                    <div class="flex items-center justify-between bg-emerald-50 -m-5 mt-3 p-5 rounded-b-2xl">
                        <span class="text-xs font-black text-emerald-700">المتبقي للسداد</span>
                        <span class="text-xl font-black text-emerald-700">{{ number_format($remaining, 2) }} ج.م</span>
                    </div>
                </div>

                <form method="POST" action="{{ $checkoutUrl }}" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl text-sm transition-all shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2">
                        <i class="fas fa-lock text-xs"></i>
                        ادفع الآن بشكل آمن
                    </button>
                    <p class="text-center text-[10px] text-slate-400 font-semibold mt-3 flex items-center justify-center gap-1.5">
                        <i class="fas fa-shield-halved"></i> دفع مشفر وآمن · بطاقات · محافظ موبايل (فودافون كاش، STC Pay)
                    </p>
                </form>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-400 font-semibold mt-5">جميع الحقوق محفوظة © تعليم — منصة إدارة المراكز التعليمية</p>
    </div>
</body>
</html>