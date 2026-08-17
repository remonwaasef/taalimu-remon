@extends('layouts.app-next')

@section('title', 'المدفوعات والفواتير')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'finances'])
@endsection

@section('content')
    <x-ui.page-header title="المدفوعات والفواتير 💳" subtitle="تتبع جميع الرسوم والفواتير الخاصة بأبنائك." />

    <!-- Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <x-ui.stats-card
            title="إجمالي الرسوم"
            value="{{ format_price($children->sum(fn ($s) => $perChild[$s->id]['total'] ?? 0)) }}"
            change="مدفوع"
            changeType="positive"
            changeLabel="مجمّع لجميع الأبناء"
            icon="fas fa-file-invoice-dollar"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="إجمالي المدفوع"
            value="{{ format_price($children->sum(fn ($s) => $perChild[$s->id]['paid'] ?? 0)) }}"
            change="سداد"
            changeType="positive"
            changeLabel="تم تحصيله"
            icon="fas fa-circle-check"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="المتبقي"
            value="{{ format_price($totalDebt) }}"
            change="رصيد"
            changeType="{{ $totalDebt > 0 ? 'negative' : 'positive' }}"
            changeLabel="مستحق السداد"
            icon="fas fa-wallet"
            iconColor="text-rose-600 bg-rose-50"
        />
    </div>

    @foreach($children as $student)
        @php $child = $perChild[$student->id]; @endphp
        <x-ui.card title="فواتير {{ $student->name }}" subtitle="سجل الرسوم والمدفوعات" class="mb-6">
            @if($child['sales']->isEmpty())
                <x-ui.empty-state title="لا توجد فواتير" description="لا توجد رسوم مسجلة على {{ $student->name }}." icon="fas fa-receipt" />
            @else
                <div class="overflow-x-auto" data-mobile-cards>
                    <table class="w-full text-xs text-right">
                        <thead>
                            <tr class="text-[11px] text-slate-400 border-b border-brand-border dark:border-slate-800">
                                <th class="py-3 px-3 font-semibold">الفاتورة</th>
                                <th class="py-3 px-3 font-semibold">الوصف</th>
                                <th class="py-3 px-3 font-semibold">التاريخ</th>
                                <th class="py-3 px-3 font-semibold">الإجمالي</th>
                                <th class="py-3 px-3 font-semibold">المدفوع</th>
                                <th class="py-3 px-3 font-semibold">المتبقي</th>
                                <th class="py-3 px-3 font-semibold">الحالة</th>
                                <th class="py-3 px-3 font-semibold">دفع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($child['sales'] as $sale)
                                @php $remaining = $sale->total_amount - $sale->paid_amount; @endphp
                                <tr class="border-b border-brand-border/50 dark:border-slate-800/50 last:border-0">
                                    <td class="py-3 px-3 font-semibold text-slate-700 dark:text-slate-200">#{{ $sale->id }}</td>
                                    <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $sale->title ?? 'رسوم دورة' }}</td>
                                    <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $sale->date?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="py-3 px-3 font-semibold text-slate-700 dark:text-slate-200">{{ format_price($sale->total_amount) }}</td>
                                    <td class="py-3 px-3 text-emerald-600 font-semibold">{{ format_price($sale->paid_amount) }}</td>
                                    <td class="py-3 px-3 font-semibold {{ $remaining > 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ format_price($remaining) }}</td>
<td class="py-3 px-3">
                                        <x-ui.badge variant="{{ $remaining > 0 ? 'warning' : 'success' }}" size="sm">
                                            {{ $remaining > 0 ? 'متبقي' : 'مسدد' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="py-3 px-3">
                                        @if($remaining > 0)
                                            <a href="{{ \Modules\Center\Http\Controllers\OnlinePaymentController::payLink($sale) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors">
                                                <i class="fas fa-credit-card text-[11px]"></i>
                                                ادفع الآن
                                            </a>
                                        @else
                                            <span class="text-[11px] text-slate-300 dark:text-slate-600 font-semibold">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>
    @endforeach
@endsection