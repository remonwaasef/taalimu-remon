@extends('layouts.landing-new')

@section('content')
<div class="min-h-dvh flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Payment Successful!') }}</h2>
        <p class="text-gray-600 mb-8">{{ __('Your subscription is now active. You can now access your dashboard.') }}</p>
        
        @if(session('tenant_domain'))
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                 <p class="text-sm text-blue-700 mb-2">{{ __('Your Dashboard URL:') }}</p>
                 <a href="{{ request()->isSecure() ? 'https://' : 'http://' }}{{ session('tenant_domain') }}.{{ config('app.tenant_domain', 'localhost') }}" class="text-lg font-bold text-primary-600 hover:underline">
                     {{ session('tenant_domain') }}.{{ config('app.tenant_domain', 'localhost') }}
                 </a>
            </div>
        @endif

        <a href="{{ route('login.portal') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
            {{ __('Go to Login') }}
        </a>
    </div>
</div>
@endsection
