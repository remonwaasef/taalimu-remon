@props([
    'name' => 'علي أحمد خالد',
    'studentCode' => 'STU-2024-00125',
    'status' => 'نشط',
    'attendanceRate' => 95,
    'examsRate' => 88,
    'rating' => 4.7
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 font-tajawal']) }}>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-12 h-12 rounded-full bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 font-extrabold flex items-center justify-center text-base ring-2 ring-brand-100 dark:ring-brand-900/40">
                    {{ mb_substr($name, 0, 2) }}
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $name }}</h4>
                    <x-ui.badge variant="success" size="sm">{{ $status }}</x-ui.badge>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">{{ $studentCode }}</p>
            </div>
        </div>

        <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <i class="fas fa-ellipsis-h text-xs"></i>
        </button>
    </div>

    <div class="grid grid-cols-3 gap-2 py-3 border-y border-slate-100 dark:border-slate-800/80 text-center">
        <div>
            <span class="text-base font-extrabold text-slate-900 dark:text-slate-100 block">{{ $attendanceRate }}%</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('الحضور') }}</span>
        </div>
        <div>
            <span class="text-base font-extrabold text-slate-900 dark:text-slate-100 block">{{ $examsRate }}%</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('الاختبارات') }}</span>
        </div>
        <div>
            <span class="text-base font-extrabold text-amber-500 block flex items-center justify-center gap-1">
                <span>{{ $rating }}</span>
                <i class="fas fa-star text-[10px]"></i>
            </span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('التقييم') }}</span>
        </div>
    </div>

    <!-- Student Quick Subtabs -->
    <div class="flex items-center justify-between pt-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
        <button class="text-brand-primary dark:text-brand-300 font-bold flex items-center gap-1.5 hover:underline">
            <i class="fas fa-user text-[11px]"></i>
            <span>{{ __('الملف الشخصي') }}</span>
        </button>
        <button class="hover:text-slate-800 dark:hover:text-slate-200 flex items-center gap-1.5">
            <i class="fas fa-calendar-check text-[11px]"></i>
            <span>{{ __('الحضور') }}</span>
        </button>
        <button class="hover:text-slate-800 dark:hover:text-slate-200 flex items-center gap-1.5">
            <i class="fas fa-award text-[11px]"></i>
            <span>{{ __('الدرجات') }}</span>
        </button>
        <button class="hover:text-slate-800 dark:hover:text-slate-200 flex items-center gap-1.5">
            <i class="fas fa-wallet text-[11px]"></i>
            <span>{{ __('المدفوعات') }}</span>
        </button>
    </div>
</div>
