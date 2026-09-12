<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
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
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">{{ $title }}</h1>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead>
                        <tr class="text-start text-xs uppercase text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                            <th class="py-3 pe-4 font-medium">{{ __('Title') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Subject') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Course') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Status') }}</th>
                            <th class="py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opportunities as $opportunity)
                            <tr class="border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <td class="py-3 pe-4 font-medium text-slate-900 dark:text-white">{{ $opportunity->title }}</td>
                                <td class="py-3 pe-4 text-slate-600 dark:text-slate-300">{{ $opportunity->subject }}{{ $opportunity->level ? ' · ' . $opportunity->level : '' }}</td>
                                <td class="py-3 pe-4 text-slate-600 dark:text-slate-300">{{ $opportunity->matchedCourse?->title ?? '—' }}</td>
                                <td class="py-3 pe-4">@include('growth.partials.status-badge', ['status' => $opportunity->status])</td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('growth.opportunities.show', $opportunity) }}" class="inline-flex items-center px-3 py-1.5 text-sm bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors font-medium">{{ __('Manage') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-10 text-center text-slate-500 dark:text-slate-400">{{ __('Nothing here yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($opportunities->hasPages())
                <div class="mt-4">{{ $opportunities->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</body>
</html>
