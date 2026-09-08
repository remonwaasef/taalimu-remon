<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Student Listings') }} — Taalimu</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">{{ __('Student Listings') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Students looking for teachers — respond to their needs') }}</p>
        </div>

        <form method="GET" class="mb-8 bg-white rounded-2xl shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('Subject or keyword...') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Subject') }}</label>
                    <input type="text" name="subject" value="{{ $filters['subject'] ?? '' }}"
                        class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('Mathematics, Physics...') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        {{ __('Search') }}
                    </button>
                </div>
            </div>
        </form>

        @if($listings->count() > 0)
            <div class="space-y-4">
                @foreach($listings as $listing)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-block px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full">
                                        {{ $listing->subject }}
                                    </span>
                                    @if($listing->level)
                                        <span class="inline-block px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-full">
                                            {{ $listing->level }}
                                        </span>
                                    @endif
                                    @if($listing->location)
                                        <span class="text-xs text-slate-400">{{ $listing->location }}</span>
                                    @endif
                                </div>

                                @if($listing->description)
                                    <p class="text-sm text-slate-600 mb-3">{{ $listing->description }}</p>
                                @endif

                                <div class="flex items-center gap-4 text-xs text-slate-400">
                                    @if($listing->budget_range)
                                        <span>{{ $listing->budget_range }}</span>
                                    @endif
                                    @if($listing->preferred_schedule)
                                        <span>{{ $listing->preferred_schedule }}</span>
                                    @endif
                                    <span>{{ $listing->created_at->diffForHumans() }}</span>
                                    <span>{{ $listing->view_count }} {{ __('views') }}</span>
                                </div>
                            </div>

                            @if($listing->user)
                                <div class="text-right ml-4">
                                    <p class="text-sm font-medium text-slate-900">{{ $listing->user->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $listings->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-slate-500">{{ __('No active listings at the moment.') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
