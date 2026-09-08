<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Discover Centers') }} — Taalimu</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">{{ __('Discover Centers') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Find reputable learning centers near you') }}</p>
        </div>

        <form method="GET" class="mb-8 bg-white rounded-2xl shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('Center name or keyword...') }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        {{ __('Search') }}
                    </button>
                </div>
            </div>
        </form>

        @if($results->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($results as $result)
                    @php $profile = $result['profile']; @endphp
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                @if($profile->photo_url)
                                    <img src="{{ $profile->photo_url }}" alt="{{ $profile->title }}" class="w-14 h-14 rounded-full object-cover">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-xl font-bold text-indigo-600">{{ substr($profile->title, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-bold text-slate-900">{{ $profile->title }}</h3>
                                    @if($profile->headline)
                                        <p class="text-sm text-slate-500">{{ $profile->headline }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($result['average_rating'])
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($result['average_rating']) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-slate-500">{{ number_format($result['average_rating'], 1) }} ({{ $result['review_count'] }} {{ __('reviews') }})</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between mt-4">
                                <a href="{{ route('growth.reviews.index', $profile->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    {{ __('View Profile') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $results->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-slate-500">{{ __('No centers found matching your criteria.') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
