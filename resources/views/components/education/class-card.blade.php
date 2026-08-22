@props([
    'title' => 'الصف الأول الثانوي - أ',
    'academicYear' => '2024/2025',
    'studentsCount' => 32,
    'time' => '10:00 ص',
    'day' => 'الثلاثاء'
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 font-tajawal']) }}>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $title }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $academicYear }}</p>
            </div>
        </div>

        <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <i class="fas fa-ellipsis-v text-xs"></i>
        </button>
    </div>

    <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80 text-center">
        <div>
            <span class="text-sm font-extrabold text-slate-900 dark:text-slate-100 block">{{ $studentsCount }} {{ __('طالب') }}</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('الطلاب') }}</span>
        </div>
        <div>
            <span class="text-sm font-extrabold text-slate-900 dark:text-slate-100 block">{{ $time }}</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('الوقت') }}</span>
        </div>
        <div>
            <span class="text-sm font-extrabold text-slate-900 dark:text-slate-100 block">{{ $day }}</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ __('اليوم') }}</span>
        </div>
    </div>
</div>
