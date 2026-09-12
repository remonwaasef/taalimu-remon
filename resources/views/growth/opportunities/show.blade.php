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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <a href="{{ route('growth.opportunities.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 mb-4">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to opportunities') }}
        </a>

        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $opportunity->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">{{ $opportunity->subject }}{{ $opportunity->level ? ' · ' . $opportunity->level : '' }}</p>
            </div>
            @include('growth.partials.status-badge', ['status' => $opportunity->status])
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-xl" role="alert">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-xl" role="alert">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Demand volume') }}</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['demand_volume'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Score') }}</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $stats['score'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Students in groups') }}</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_students'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Converted demands') }}</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['conversions_count'] }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow p-4">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Conversion rate') }}</p>
                <p class="text-2xl font-bold text-teal-600">{{ $stats['conversion_rate'] }}%</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ __('Explanation') }}</h2>
                <p class="text-slate-600 dark:text-slate-300">{{ $opportunity->getExplanation() }}</p>
                @if(!empty($stats['score_breakdown']))
                    <div class="mt-4 space-y-2">
                        @foreach($stats['score_breakdown'] as $key => $factor)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-500 dark:text-slate-400">{{ $factor['label'] ?? $key }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $factor['score'] }}</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5">
                                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ min(100, $factor['score']) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <dl class="mt-4 text-sm space-y-1">
                    <div class="flex justify-between"><dt class="text-slate-500 dark:text-slate-400">{{ __('Teacher') }}</dt><dd class="font-medium text-slate-900 dark:text-white">{{ $stats['matched_teacher']?->name ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500 dark:text-slate-400">{{ __('Course') }}</dt><dd class="font-medium text-slate-900 dark:text-white">{{ $stats['matched_course']?->title ?? '—' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ __('Actions') }}</h2>
                <div class="space-y-3">
                    @if($opportunity->status === 'open')
                        <form method="POST" action="{{ route('growth.opportunities.accept', $opportunity) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">{{ __('Accept as instructor') }}</button>
                        </form>
                        <form method="POST" action="{{ route('growth.opportunities.decline', $opportunity) }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="reason" placeholder="{{ __('Decline reason (optional)') }}" class="flex-1 px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                            <button type="submit" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium">{{ __('Decline') }}</button>
                        </form>
                    @endif
                    @if($opportunity->status === 'matched')
                        <form method="POST" action="{{ route('growth.opportunities.start-group', $opportunity) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">{{ __('Start group formation') }}</button>
                        </form>
                    @endif
                    @if($opportunity->status === 'matched' && $opportunity->matched_course_id)
                        <form method="POST" action="{{ route('growth.opportunities.groups.store', $opportunity) }}">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $opportunity->matched_course_id }}">
                            <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-medium">{{ __('Create group for :course', ['course' => $stats['matched_course']?->title ?? __('course')]) }}</button>
                        </form>
                    @endif
                    @if($opportunity->status === 'group_forming')
                        <form method="POST" action="{{ route('growth.opportunities.complete-group', $opportunity) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">{{ __('Complete group formation') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ __('Groups') }} ({{ $stats['groups_count'] }})</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead>
                        <tr class="text-start text-xs uppercase text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                            <th class="py-3 pe-4 font-medium">{{ __('Course') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Students') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Status') }}</th>
                            <th class="py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['groups'] as $group)
                            <tr class="border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <td class="py-3 pe-4 font-medium text-slate-900 dark:text-white">{{ $group->course?->title ?? '—' }}</td>
                                <td class="py-3 pe-4 text-slate-600 dark:text-slate-300">{{ $group->student_count }}</td>
                                <td class="py-3 pe-4">@include('growth.partials.status-badge', ['status' => $group->status])</td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('growth.opportunities.groups.show', [$opportunity, $group]) }}" class="inline-flex items-center px-3 py-1.5 text-sm bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors font-medium">{{ __('Manage') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-slate-500 dark:text-slate-400">{{ __('No groups yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
