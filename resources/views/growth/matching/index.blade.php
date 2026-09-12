<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Matching Opportunities') }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Matching Opportunities') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Open demand matched to your specialization') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('growth.matching.matched') }}" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium">{{ __('Matched') }}</a>
                <a href="{{ route('growth.matching.stats') }}" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium">{{ __('Stats') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-xl" role="alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-xl" role="alert">{{ $errors->first() }}</div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('growth.matching.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input type="text" id="filter-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search subject or title') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <input type="text" id="filter-subject" name="subject" value="{{ $filters['subject'] ?? '' }}" placeholder="{{ __('Subject') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <input type="text" id="filter-level" name="level" value="{{ $filters['level'] ?? '' }}" placeholder="{{ __('Level') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-slate-600 text-white rounded-xl hover:bg-slate-700 transition-colors font-medium">{{ __('Filter') }}</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($opportunities as $opportunity)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 flex flex-col">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $opportunity->title }}</h2>
                        @include('growth.partials.status-badge', ['status' => $opportunity->status])
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">{{ $opportunity->subject }}{{ $opportunity->level ? ' · ' . $opportunity->level : '' }}</p>
                    <div class="flex items-center gap-4 text-sm mb-4">
                        <span class="text-slate-600 dark:text-slate-300">{{ __('Demand') }}: <strong>{{ $opportunity->demand_volume }}</strong></span>
                        <span class="font-bold text-indigo-600">{{ __('Score') }}: {{ $opportunity->score }}</span>
                    </div>
                    <a href="{{ route('growth.matching.show', $opportunity) }}" class="mt-auto inline-flex justify-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">{{ __('View details') }}</a>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-10 text-center text-slate-500 dark:text-slate-400">
                    {{ __('No matching opportunities right now.') }}
                </div>
            @endforelse
        </div>
        @if($opportunities->hasPages())
            <div class="mt-4">{{ $opportunities->appends(request()->query())->links() }}</div>
        @endif
    </div>
</body>
</html>
