<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Notifications') }} — Taalimu</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('growth.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Dashboard') }}</a>
                <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ __('Notifications') }}</h1>
            </div>
            <form action="{{ route('growth.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">{{ __('Mark all as read') }}</button>
            </form>
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-2xl shadow-sm p-4 {{ $notification->read_at ? '' : 'border-l-4 border-indigo-500' }}">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                @if($notification->type === 'new_demand')
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                @elseif($notification->type === 'new_registration')
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @elseif($notification->type === 'waitlist_threshold')
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900">{{ $notification->title }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">{{ $notification->message }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->read_at)
                                <form action="{{ route('growth.notification.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-slate-400 hover:text-slate-600">{{ __('Mark read') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <p class="text-slate-500">{{ __('No notifications yet.') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
