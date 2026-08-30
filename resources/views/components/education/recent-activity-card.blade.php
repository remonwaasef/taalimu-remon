@props([
    'title' => 'Recent Activity',
    'activities' => [
        ['icon' => 'fas fa-clipboard-check', 'color' => 'bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400', 'text' => 'تم تسجيل حضور 32 طالب', 'time' => 'منذ 10 دقائق'],
        ['icon' => 'fas fa-receipt', 'color' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400', 'text' => 'تم استلام دفعة جديدة', 'time' => 'منذ 25 دقيقة'],
        ['icon' => 'fas fa-book', 'color' => 'bg-brand-50 text-brand-primary dark:bg-brand-900/30 dark:text-brand-300', 'text' => 'تم إضافة واجب جديد في الرياضيات', 'time' => 'منذ ساعة'],
        ['icon' => 'fas fa-certificate', 'color' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400', 'text' => 'تم إصدار شهادة لأحمد محمد', 'time' => 'منذ 3 ساعات'],
    ]
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 font-tajawal']) }}>
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $title }}</h4>
        <button class="text-xs text-brand-primary dark:text-brand-300 font-bold hover:underline">{{ __('عرض الكل') }}</button>
    </div>

    <div class="space-y-3.5">
        @foreach($activities as $act)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 mt-0.5 {{ $act['color'] }}">
                    <i class="{{ $act['icon'] }}"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight truncate">{{ $act['text'] }}</p>
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 block">{{ $act['time'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
