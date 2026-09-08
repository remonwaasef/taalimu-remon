<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoData['title'] }}</title>
    <meta name="description" content="{{ $seoData['description'] }}">
    <meta property="og:title" content="{{ $seoData['title'] }}">
    <meta property="og:description" content="{{ $seoData['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seoData['canonical'] }}">
    <link rel="canonical" href="{{ $seoData['canonical'] }}">
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('growth.teacher.show', $profile->slug) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Profile') }}</a>
            <h1 class="text-3xl font-bold text-slate-900 mt-4">{{ __('Programs') }}</h1>
            <p class="text-slate-500 mt-2">{{ __('Available programs and courses at') }} {{ $tenant->name }}</p>
        </div>

        @if($courses->isEmpty())
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <h2 class="text-xl font-semibold text-slate-700 mb-2">{{ __('No programs available yet') }}</h2>
                <p class="text-slate-500">{{ __('Check back later or request a program below.') }}</p>
                <a href="{{ route('growth.demand.show', $profile->slug) }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                    {{ __('Request a Program') }}
                </a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($courses as $course)
                    @php
                        $status = $course->availabilityStatus();
                    @endphp
                    <a href="{{ route('growth.programs.show', [$profile->slug, $course->slug]) }}" class="block bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                <span class="text-white text-4xl font-bold">{{ mb_substr($course->title, 0, 1) }}</span>
                            </div>
                        @endif

                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                @if($status === 'available')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">{{ __('Available') }}</span>
                                @elseif($status === 'limited_seats')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">{{ __('Limited Seats') }}</span>
                                @elseif($status === 'full')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">{{ __('Full') }}</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">{{ __('Coming Soon') }}</span>
                                @endif
                                @if($course->delivery_mode)
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">{{ ucfirst($course->delivery_mode) }}</span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $course->title }}</h3>

                            @if($course->short_description)
                                <p class="text-sm text-slate-500 mb-3 line-clamp-2">{{ $course->short_description }}</p>
                            @endif

                            <div class="flex items-center gap-3 text-xs text-slate-400">
                                @if($course->instructor)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $course->instructor->name }}
                                    </span>
                                @endif
                                @if($course->level)
                                    <span>{{ $course->level }}</span>
                                @endif
                                @if($course->start_date)
                                    <span>{{ $course->start_date->format('M Y') }}</span>
                                @endif
                            </div>

                            @if($course->price > 0)
                                <div class="mt-3 pt-3 border-t border-slate-100">
                                    <span class="text-lg font-bold text-indigo-600">{{ number_format($course->price, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
