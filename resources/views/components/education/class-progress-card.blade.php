@props([
    'title' => 'الصف الأول الثانوي - أ',
    'academicYear' => '2024/2025',
    'overallProgress' => 75,
    'subjects' => [
        ['name' => 'الرياضيات', 'progress' => 80, 'color' => 'bg-indigo-600'],
        ['name' => 'العلوم', 'progress' => 70, 'color' => 'bg-sky-500'],
        ['name' => 'اللغة الإنجليزية', 'progress' => 65, 'color' => 'bg-amber-500'],
        ['name' => 'اللغة العربية', 'progress' => 90, 'color' => 'bg-emerald-500'],
    ]
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all duration-200 font-tajawal']) }}>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $title }}</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $academicYear }}</p>
        </div>

        <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-full border-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs">
                {{ $overallProgress }}%
            </div>
        </div>
    </div>

    <div class="space-y-3 pt-2">
        @foreach($subjects as $subj)
            <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                    <span>{{ $subj['name'] }}</span>
                    <span class="font-mono font-bold">{{ $subj['progress'] }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                    <div class="{{ $subj['color'] }} h-full rounded-full transition-all duration-500" style="width: {{ $subj['progress'] }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
