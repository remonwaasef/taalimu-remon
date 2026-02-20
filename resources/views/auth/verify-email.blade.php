@extends('layouts.landing-new')

@section('content')
<div class="min-h-screen bg-slate-50/50 flex justify-center items-center p-4 lg:p-8 mesh-gradient-soft noise-overlay">
    <div class="w-full max-w-md bg-white rounded-[32px] shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100 p-8 text-center">
        <div class="mb-6 flex justify-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-slate-900 mb-2 font-arabic">
            {{ __('Please Verify Your Email') }}
        </h2>

        <p class="text-slate-500 mb-6 font-arabic text-sm leading-relaxed">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>

        @if (session('message') == 'Verification link sent!')
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium font-arabic">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all font-arabic">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="text-sm text-slate-400 hover:text-slate-600 font-medium font-arabic">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
@endsection
