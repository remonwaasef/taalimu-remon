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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('growth.programs.index', $profile->slug) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Programs') }}</a>
        </div>

        @if($course->image)
            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-2xl shadow-lg mb-6">
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            @php $status = $course->availabilityStatus(); @endphp

            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($status === 'available')
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">{{ __('Available') }}</span>
                @elseif($status === 'limited_seats')
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">{{ __('Limited Seats — :count left', ['count' => $course->getAvailableSeats()]) }}</span>
                @elseif($status === 'full')
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">{{ __('Full') }}</span>
                @else
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">{{ __('Coming Soon') }}</span>
                @endif
                @if($course->delivery_mode)
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">{{ ucfirst($course->delivery_mode) }}</span>
                @endif
                @if($course->level)
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">{{ $course->level }}</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-slate-900 mb-4">{{ $course->title }}</h1>

            @if($course->instructor)
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                        {{ mb_substr($course->instructor->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">{{ $course->instructor->name }}</p>
                        <p class="text-sm text-slate-500">{{ __('Instructor') }}</p>
                    </div>
                </div>
            @endif

            <div class="prose prose-slate max-w-none mb-6">
                {!! nl2br(e($course->description)) !!}
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                @if($course->price > 0)
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-slate-500">{{ __('Price') }}</p>
                        <p class="text-xl font-bold text-indigo-600">{{ number_format($course->price, 2) }}</p>
                    </div>
                @endif
                @if($course->sessions_count)
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-slate-500">{{ __('Sessions') }}</p>
                        <p class="text-xl font-bold text-slate-900">{{ $course->sessions_count }}</p>
                    </div>
                @endif
                @if($course->capacity)
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-slate-500">{{ __('Seats') }}</p>
                        <p class="text-xl font-bold text-slate-900">{{ $course->enrolled_count }}/{{ $course->capacity }}</p>
                    </div>
                @endif
                @if($course->start_date)
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-slate-500">{{ __('Starts') }}</p>
                        <p class="text-xl font-bold text-slate-900">{{ $course->start_date->format('M d') }}</p>
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                @if($status === 'available' || $status === 'limited_seats')
                    @if($course->registration_token)
                        <a href="{{ route('group.register', ['tenant' => $tenant->domain, 'token' => $course->registration_token]) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium text-center">
                            {{ __('Register Now') }}
                        </a>
                    @endif
                @elseif($status === 'full')
                    <button onclick="document.getElementById('waitlist-modal').classList.remove('hidden')" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors font-medium">
                        {{ __('Join Waiting List') }} ({{ $waitlistCount }})
                    </button>
                @endif

                <a href="{{ route('growth.demand.show', $profile->slug) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    {{ __('Request Similar Program') }}
                </a>
            </div>
        </div>

        {{-- Schedule --}}
        @if($course->schedules->count())
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mt-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Schedule') }}</h2>
                <div class="space-y-3">
                    @foreach($course->schedules as $schedule)
                        <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $schedule->day ?? $schedule->day_of_week }}</p>
                                <p class="text-sm text-slate-500">{{ $schedule->start_time }} — {{ $schedule->end_time }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Waitlist Modal --}}
    <div id="waitlist-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-slate-900">{{ __('Join Waiting List') }}</h3>
                <button onclick="document.getElementById('waitlist-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <p class="text-sm text-slate-500 mb-4">{{ __('We will notify you when a seat becomes available.') }}</p>

            <form action="{{ route('growth.waitlist.store', [$profile->slug, $course->slug]) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Name') }}</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('Your name') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Phone') }}</label>
                        <input type="text" name="phone" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('Your phone number') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Email') }}</label>
                        <input type="email" name="email" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ __('Your email') }}">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('waitlist-modal').classList.add('hidden')" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 font-medium">{{ __('Join List') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
