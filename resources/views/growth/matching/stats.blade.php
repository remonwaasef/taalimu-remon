<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Matching Stats') }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <a href="{{ route('growth.matching.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 mb-4">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to matching') }}
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">{{ __('Your Matching Stats') }}</h1>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                __('Matched') => $stats['matched'],
                __('Group forming') => $stats['group_forming'],
                __('Group formed') => $stats['group_formed'],
                __('Filled') => $stats['filled'],
                __('Declined') => $stats['declined'],
                __('Students placed') => $stats['total_students'],
            ] as $label => $value)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-5">
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $label }}</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $value }}</p>
                </div>
            @endforeach
            <div class="bg-indigo-600 rounded-2xl shadow p-5">
                <p class="text-xs text-indigo-100">{{ __('Acceptance rate') }}</p>
                <p class="text-3xl font-bold text-white">{{ $stats['acceptance_rate'] }}%</p>
            </div>
        </div>
    </div>
</body>
</html>
