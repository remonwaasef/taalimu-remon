@extends('layouts.landing-new')

@section('content')
<div class="min-h-screen mesh-gradient-soft noise-overlay flex justify-center items-center p-4 lg:p-8">
    <div class="w-full max-w-lg">
        {{-- Google User Info Card --}}
        <div class="bg-white rounded-[1.5rem] shadow-xl shadow-blue-900/5 overflow-hidden border border-white/50 backdrop-blur-xl relative">
            {{-- Header --}}
            <div class="gradient-hero px-8 py-8 text-center relative overflow-hidden">
                {{-- Decorative light glow in header --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 bg-white/20 blur-3xl rounded-full pointer-events-none"></div>
                
                <div class="w-16 h-16 mx-auto bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-4 border border-white/30 shadow-lg shadow-black/5 relative z-10">
                    <svg class="w-8 h-8 text-white drop-shadow-sm" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="white" fill-opacity="0.9"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="white" fill-opacity="1"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="white" fill-opacity="0.8"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="white"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2 font-arabic tracking-tight relative z-10">{{ __('Almost Done!') }}</h2>
                <p class="text-white/70 text-sm font-arabic font-medium relative z-10">{{ __('Complete your center registration') }}</p>
            </div>

            {{-- Google Account Info --}}
            <div class="px-8 pt-8 pb-2">
                <div class="flex items-center gap-4 p-4 bg-slate-50/80 rounded-2xl border border-slate-100/50 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md hover:shadow-brand-secondary/5 group">
                    <div class="w-12 h-12 bg-brand-secondary-light rounded-full flex items-center justify-center text-brand-secondary font-bold text-lg group-hover:scale-105 transition-transform">
                        {{ mb_substr(session('google_user.name', ''), 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-sm truncate">{{ session('google_user.name') }}</p>
                        <p class="text-slate-500 text-xs truncate">{{ session('google_user.email') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold ring-1 ring-inset ring-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ __('Verified') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-8 py-6">
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('google.complete-registration') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Center Name --}}
                    <div class="space-y-2">
                        <label for="center_name" class="block text-sm font-bold text-slate-700 font-arabic">
                            {{ __('auth.register.center_name') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="center_name" 
                                   id="center_name"
                                   class="block w-full ps-11 pe-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-arabic text-sm"
                                   placeholder="{{ __('auth.register.center_name_placeholder') }}"
                                   value="{{ old('center_name') }}"
                                   required 
                                   autofocus>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-bold text-slate-700 font-arabic">
                            {{ __('auth.register.phone') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   class="block w-full ps-11 pe-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"
                                   placeholder="010xxxxxxx"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4">
                        <button type="submit" 
                                class="btn-hero-cta w-full py-4 px-6 font-arabic flex items-center justify-center gap-3 text-[15px]">
                            <span>{{ __('Create My Center') }}</span>
                            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <p class="text-center text-xs text-slate-400 mt-4 font-arabic">
                    {{ __('By continuing, you agree to our') }}
                    <a href="{{ route('terms') }}" class="text-primary hover:underline">{{ __('Terms') }}</a>
                    {{ __('and') }}
                    <a href="{{ route('privacy') }}" class="text-primary hover:underline">{{ __('Privacy Policy') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
