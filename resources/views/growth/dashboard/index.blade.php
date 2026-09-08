<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoData['title'] }}</title>
    <meta name="description" content="{{ $seoData['description'] }}">
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ __('Growth Dashboard') }}</h1>
                <p class="text-slate-500 mt-1">{{ __('Track your growth, demand, and acquisition') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('growth.notifications') }}" class="relative inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
                @if($profile && $profile->slug)
                    <a href="{{ route('growth.programs.index', $profile->slug) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                        {{ __('View Public Profile') }}
                    </a>
                @endif
                <a href="{{ route('growth.insights') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    {{ __('Insights') }}
                </a>
                <a href="{{ route('growth.referrals') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Referrals') }}
                </a>
                <a href="{{ route('growth.discover.teachers') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    {{ __('Discover') }}
                </a>
            </div>
        </div>

        {{-- Growth Score --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">{{ __('Growth Score') }}</h2>
                <span class="text-3xl font-bold text-indigo-600">{{ $growthScore['total'] }}/100</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($growthScore['breakdown'] as $key => $factor)
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-xs text-slate-500 mb-1">{{ $factor['label'] }}</p>
                        <p class="text-lg font-bold text-slate-900">{{ $factor['score'] }}<span class="text-xs text-slate-400">/100</span></p>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 mt-1">
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $factor['score'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-lg p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($profileViews) }}</p>
                        <p class="text-sm text-slate-500">{{ __('Profile Views') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $demandCount }}</p>
                        <p class="text-sm text-slate-500">{{ __('Demand Requests') }}</p>
                    </div>
                </div>
                @if($newDemandCount > 0)
                    <span class="inline-block mt-2 px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">{{ $newDemandCount }} {{ __('new') }}</span>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $waitlistCount }}</p>
                        <p class="text-sm text-slate-500">{{ __('On Waitlist') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $enrollmentCount }}</p>
                        <p class="text-sm text-slate-500">{{ __('Enrollments') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Acquisition Sources --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Acquisition Sources') }}</h2>
                @if(count($sources) > 0)
                    <div class="space-y-3">
                        @foreach($sources as $source => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-700">{{ $source }}</span>
                                <span class="text-sm font-medium text-slate-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">{{ __('No acquisition data yet. Share your profile to start tracking.') }}</p>
                @endif
            </div>

            {{-- Recent Demand --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Recent Demand') }}</h2>
                @if($recentDemand->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentDemand as $demand)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $demand->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $demand->subject }} @if($demand->level) · {{ $demand->level }} @endif</p>
                                </div>
                                <span class="inline-block px-2 py-0.5 text-xs font-medium
                                    {{ $demand->status === 'new' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $demand->status === 'contacted' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $demand->status === 'converted' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $demand->status === 'declined' ? 'bg-slate-100 text-slate-500' : '' }}
                                    rounded-full">{{ ucfirst($demand->status) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">{{ __('No demand yet. Share your profile to start discovering demand.') }}</p>
                @endif
            </div>
        </div>

        {{-- Notifications --}}
        @if($notifications->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">{{ __('Notifications') }}</h2>
                    <a href="{{ route('growth.notifications') }}" class="text-sm text-indigo-600 hover:text-indigo-800">{{ __('View all') }}</a>
                </div>
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }}">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900">{{ $notification->title }}</p>
                                <p class="text-sm text-slate-500">{{ $notification->message }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
