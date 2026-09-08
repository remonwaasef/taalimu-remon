<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $seoData['title'] }}</title>
    <meta name="description" content="{{ $seoData['description'] }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoData['title'] }}">
    <meta property="og:description" content="{{ $seoData['description'] }}">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ $seoData['canonical'] }}">
    @if($seoData['og_image'])
        <meta property="og:image" content="{{ $seoData['og_image'] }}">
    @endif

    {{-- Twitter/X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoData['title'] }}">
    <meta name="twitter:description" content="{{ $seoData['description'] }}">
    @if($seoData['og_image'])
        <meta name="twitter:image" content="{{ $seoData['og_image'] }}">
    @endif

    <link rel="canonical" href="{{ $seoData['canonical'] }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind via Vite --}}
    @vite(['resources/css/tailwind.css'])

    {{-- Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Person",
        "name": "{{ $profile->title }}",
        "description": "{{ $profile->headline }}",
        @if($profile->photo_url)
        "image": "{{ $profile->photo_url }}",
        @endif
        "url": "{{ $seoData['canonical'] }}",
        "jobTitle": "{{ $profile->headline }}",
        @if($profile->location)
        "address": {
            "@@type": "PostalAddress",
            "addressLocality": "{{ $profile->location }}"
        },
        @endif
        "worksFor": {
            "@@type": "Organization",
            "name": "{{ $tenant->name }}"
        }
    }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
        [dir="rtl"] body { font-family: 'Cairo', 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    {{-- Cover Photo --}}
    <div class="relative h-48 sm:h-64 bg-gradient-to-br from-blue-600 to-indigo-700">
        @if($profile->cover_photo)
            <img src="{{ asset('storage/' . $profile->cover_photo) }}" alt="" class="w-full h-full object-cover">
        @endif
    </div>

    {{-- Profile Header --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-16 sm:-mt-20 relative z-10">
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                {{-- Avatar --}}
                <div class="relative">
                    @if($profile->photo_url)
                        <img src="{{ $profile->photo_url }}" alt="{{ $profile->title }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="text-white text-3xl sm:text-4xl font-bold">{{ mb_substr($profile->title, 0, 1) }}</span>
                        </div>
                    @endif

                    @if($profile->verification_status === 'verified')
                        <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1.5 border-2 border-white" title="Taalimu Verified">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center sm:text-right">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $profile->title }}</h1>

                    @if($profile->headline)
                        <p class="text-lg text-slate-600 mt-1">{{ $profile->headline }}</p>
                    @endif

                    <div class="flex flex-wrap justify-center sm:justify-end gap-3 mt-4">
                        @if($profile->location && $profile->isFieldVisible('location'))
                            <span class="inline-flex items-center gap-1 text-sm text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $profile->location }}
                            </span>
                        @endif

                        @if($profile->experience_years && $profile->isFieldVisible('experience_years'))
                            <span class="inline-flex items-center gap-1 text-sm text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $profile->experience_years }} {{ __('years experience') }}
                            </span>
                        @endif

                        @if($profile->verification_status === 'verified')
                            <span class="inline-flex items-center gap-1 text-sm text-green-600 font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ __('Taalimu Verified') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Specializations --}}
            @if($profile->specializations && count($profile->specializations) > 0 && $profile->isFieldVisible('specialization'))
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Specializations') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->specializations as $spec)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">{{ $spec }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Teaching Levels --}}
            @if($profile->teaching_levels && count($profile->teaching_levels) > 0)
                <div class="mt-4">
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Teaching Levels') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->teaching_levels as $level)
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm">{{ $level }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Delivery Modes --}}
            @if($profile->delivery_modes && count($profile->delivery_modes) > 0)
                <div class="mt-4">
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">{{ __('Teaching Mode') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->delivery_modes as $mode)
                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm font-medium">
                                @if($mode === 'online') {{ __('Online') }}
                                @elseif($mode === 'offline') {{ __('In-Person') }}
                                @else {{ __('Online & In-Person') }}
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Bio Section --}}
        @if($profile->bio && $profile->isFieldVisible('bio'))
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mt-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('About') }}</h2>
                <div class="prose prose-slate max-w-none">
                    {!! nl2br(e($profile->bio)) !!}
                </div>
            </div>
        @endif

        {{-- Programs Section --}}
        @php
            $publishedCourses = \App\Models\Course::where('tenant_id', $tenant->id)
                ->where('published', true)
                ->with('instructor')
                ->orderByDesc('start_date')
                ->limit(6)
                ->get();
        @endphp
        @if($publishedCourses->count())
            <div class="mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">{{ __('Programs') }}</h2>
                    <a href="{{ route('growth.programs.index', $profile->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">{{ __('View All') }} &rarr;</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($publishedCourses as $course)
                        @php $status = $course->availabilityStatus(); @endphp
                        <a href="{{ route('growth.programs.show', [$profile->slug, $course->slug]) }}" class="block bg-white rounded-2xl shadow-lg p-5 hover:shadow-xl transition-shadow">
                            <div class="flex items-center gap-2 mb-2">
                                @if($status === 'available')
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">{{ __('Available') }}</span>
                                @elseif($status === 'limited_seats')
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">{{ __('Limited') }}</span>
                                @elseif($status === 'full')
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">{{ __('Full') }}</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">{{ __('Soon') }}</span>
                                @endif
                                @if($course->delivery_mode)
                                    <span class="inline-block px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">{{ ucfirst($course->delivery_mode) }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-slate-900">{{ $course->title }}</h3>
                            @if($course->short_description)
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $course->short_description }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-3 text-xs text-slate-400">
                                @if($course->level)
                                    <span>{{ $course->level }}</span>
                                @endif
                                @if($course->price > 0)
                                    <span class="font-semibold text-indigo-600">{{ number_format($course->price, 2) }}</span>
                                @else
                                    <span class="font-semibold text-green-600">{{ __('Free') }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Request Program --}}
        <div class="mt-6">
            <a href="{{ route('growth.demand.show', $profile->slug) }}" class="block bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">{{ __('Can not find what you need?') }}</h3>
                        <p class="text-white/80 text-sm">{{ __('Request a custom program and we will get back to you.') }}</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Center Info --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mt-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Teaching Center') }}</h2>
            <div class="flex items-center gap-4">
                @if($tenant->logo)
                    <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="w-12 h-12 rounded-lg object-cover">
                @endif
                <div>
                    <p class="font-semibold text-slate-900">{{ $tenant->name }}</p>
                    @if($tenant->address)
                        <p class="text-sm text-slate-500">{{ $tenant->address }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        @if($profile->social_links)
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mt-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Connect') }}</h2>
                <div class="flex flex-wrap gap-3">
                    @if(!empty($profile->social_links['whatsapp']))
                        <a href="https://wa.me/{{ $profile->social_links['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    @endif

                    @if(!empty($profile->social_links['facebook']))
                        <a href="{{ $profile->social_links['facebook'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                    @endif

                    @if(!empty($profile->social_links['instagram']))
                        <a href="{{ $profile->social_links['instagram'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            Instagram
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Share Section --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mt-6 mb-12">
            <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Share this Profile') }}</h2>
            <div class="flex flex-wrap gap-3">
                <button onclick="copyLink()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors" id="copy-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ __('Copy Link') }}
                </button>
                <a href="https://wa.me/?text={{ urlencode($profile->title . ' - ' . $tenant->name . ' ' . $profile->getUrl()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($profile->getUrl()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Facebook
                </a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="bg-white border-t border-slate-100 py-6 text-center">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>

    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                const btn = document.getElementById('copy-btn');
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
                setTimeout(() => {
                    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Copy Link';
                }, 2000);
            });
        }
    </script>
</body>
</html>
