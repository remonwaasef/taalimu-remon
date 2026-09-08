<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Referrals') }} — {{ __('Growth Dashboard') }} — Taalimu</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('growth.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Dashboard') }}</a>
            <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ __('Referral Program') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Invite students and earn rewards') }}</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                <p class="text-3xl font-bold text-indigo-600">{{ $stats['total'] }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Total Referrals') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Completed') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                <p class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Pending') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                <p class="text-3xl font-bold text-slate-900">{{ $stats['conversion_rate'] }}%</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Conversion Rate') }}</p>
            </div>
        </div>

        {{-- Referral Link --}}
        @if($referralLink)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="font-bold text-slate-900 mb-3">{{ __('Your Referral Link') }}</h3>
                <p class="text-sm text-slate-500 mb-3">{{ __('Share this link with friends. When they enroll, you get credited!') }}</p>
                <div class="flex items-center gap-2">
                    <input type="text" value="{{ $referralLink }}" readonly
                        class="flex-1 rounded-xl border-slate-200 bg-slate-50 text-sm font-mono"
                        id="referral-link">
                    <button onclick="navigator.clipboard.writeText(document.getElementById('referral-link').value); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy', 2000)"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors whitespace-nowrap">
                        {{ __('Copy') }}
                    </button>
                </div>
                @if($profile && $profile->referral_code)
                    <p class="text-xs text-slate-400 mt-2">{{ __('Your referral code:') }} <span class="font-mono font-bold">{{ $profile->referral_code }}</span></p>
                @endif
            </div>
        @endif

        {{-- Recent Referrals --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="font-bold text-slate-900 mb-4">{{ __('Recent Referrals') }}</h3>
            @if($recentReferrals->count() > 0)
                <div class="space-y-3">
                    @foreach($recentReferrals as $referral)
                        <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                                    <span class="text-xs font-bold text-slate-600">{{ substr($referral->referred->name ?? '?', 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $referral->referred->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-slate-400">{{ $referral->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $referral->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $referral->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $referral->status === 'expired' ? 'bg-slate-100 text-slate-500' : '' }}
                            ">
                                {{ ucfirst($referral->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-slate-500">{{ __('No referrals yet. Share your link to get started!') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
