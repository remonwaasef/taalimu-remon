@props([
    'name' => 'أ. محمد إبراهيم',
    'subject' => 'معلم رياضيات',
    'avatar' => null,
    'status' => 'نشط',
    'studentsCount' => 28,
    'attendanceRate' => 95,
    'rating' => 4.8
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 font-tajawal']) }}>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                @if($avatar)
                    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-50 dark:ring-indigo-950">
                @else
                    <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-base ring-2 ring-indigo-100 dark:ring-indigo-900/40">
                        {{ mb_substr($name, 0, 2) }}
                    </div>
                @endif
                <span class="absolute bottom-0 end-0 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900"></span>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $name }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subject }}</p>
            </div>
        </div>

        <x-ui.badge variant="success" size="sm">{{ $status }}</x-ui.badge>
    </div>

    <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80 text-center">
        <div>
            <span class="text-base font-extrabold text-slate-900 dark:text-slate-100 block">{{ $studentsCount }}</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('الطلاب') }}</span>
        </div>
        <div>
            <span class="text-base font-extrabold text-slate-900 dark:text-slate-100 block">{{ $attendanceRate }}%</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('الحضور') }}</span>
        </div>
        <div>
            <span class="text-base font-extrabold text-amber-500 block flex items-center justify-center gap-1">
                <span>{{ $rating }}</span>
                <i class="fas fa-star text-[10px]"></i>
            </span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('التقييم') }}</span>
        </div>
    </div>
</div>
