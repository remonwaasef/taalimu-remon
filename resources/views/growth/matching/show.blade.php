<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $opportunity->title }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        <a href="{{ route('growth.matching.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 mb-4">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to matching') }}
        </a>

        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $opportunity->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">{{ $opportunity->subject }}{{ $opportunity->level ? ' · ' . $opportunity->level : '' }}</p>
            </div>
            @include('growth.partials.status-badge', ['status' => $opportunity->status])
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-xl" role="alert">{{ $errors->first() }}</div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-3 gap-4 text-center mb-4">
                <div><p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Demand') }}</p><p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $opportunity->demand_volume }}</p></div>
                <div><p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Score') }}</p><p class="text-2xl font-bold text-indigo-600">{{ $opportunity->score }}</p></div>
                <div><p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Course') }}</p><p class="text-sm font-bold text-slate-900 dark:text-white mt-1">{{ $opportunity->matchedCourse?->title ?? '—' }}</p></div>
            </div>
            @if($opportunity->description)
                <p class="text-slate-600 dark:text-slate-300 text-sm">{{ $opportunity->description }}</p>
            @endif
            <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">{{ $opportunity->getExplanation() }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ __('Your decision') }}</h2>
            <form method="POST" action="{{ route('growth.matching.accept', $opportunity) }}" class="mb-3">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">{{ __('Accept opportunity') }}</button>
            </form>
            <form method="POST" action="{{ route('growth.matching.decline', $opportunity) }}" class="flex gap-2">
                @csrf
                <input type="text" name="reason" placeholder="{{ __('Decline reason (optional)') }}" class="flex-1 px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <button type="submit" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium">{{ __('Decline') }}</button>
            </form>
        </div>
    </div>
</body>
</html>
