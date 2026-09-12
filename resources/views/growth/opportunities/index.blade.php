<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Opportunities') }}</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Opportunities') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Student demand converted into teachable groups') }}</p>
            </div>
            <a href="{{ route('growth.opportunities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Opportunity') }}
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-xl" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-xl" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('growth.opportunities.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="text" id="filter-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search subject or title') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <input type="text" id="filter-subject" name="subject" value="{{ $filters['subject'] ?? '' }}" placeholder="{{ __('Subject') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <input type="text" id="filter-level" name="level" value="{{ $filters['level'] ?? '' }}" placeholder="{{ __('Level') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                <select id="filter-status" name="status" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white">
                    <option value="">{{ __('All statuses') }}</option>
                    @foreach(['open', 'matched', 'group_forming', 'group_formed', 'filled'] as $s)
                        <option value="{{ $s }}" {{ ($filters['status'] ?? '') === $s ? 'selected' : '' }}>{{ __(str_replace('_', ' ', ucfirst($s))) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-900 dark:bg-slate-600 text-white rounded-xl hover:bg-slate-700 transition-colors font-medium">{{ __('Filter') }}</button>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead>
                        <tr class="text-start text-xs uppercase text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                            <th class="py-3 pe-4 font-medium">{{ __('Title') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Subject') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Demand') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Score') }}</th>
                            <th class="py-3 pe-4 font-medium">{{ __('Status') }}</th>
                            <th class="py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opportunities as $opportunity)
                            <tr class="border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <td class="py-3 pe-4 font-medium text-slate-900 dark:text-white">{{ $opportunity->title }}</td>
                                <td class="py-3 pe-4 text-slate-600 dark:text-slate-300">{{ $opportunity->subject }}{{ $opportunity->level ? ' · ' . $opportunity->level : '' }}</td>
                                <td class="py-3 pe-4 text-slate-600 dark:text-slate-300">{{ $opportunity->demand_volume }}</td>
                                <td class="py-3 pe-4 font-bold text-indigo-600">{{ $opportunity->score }}</td>
                                <td class="py-3 pe-4">@include('growth.partials.status-badge', ['status' => $opportunity->status])</td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('growth.opportunities.show', $opportunity) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors font-medium">{{ __('View') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-500 dark:text-slate-400">{{ __('No opportunities yet. Aggregate demand to create the first one.') }}</td>
                            </tr>
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
