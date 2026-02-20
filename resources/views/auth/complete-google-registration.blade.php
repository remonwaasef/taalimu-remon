@extends('layouts.landing-new')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex justify-center items-center p-4 lg:p-8">
    <div class="w-full max-w-lg">
        {{-- Google User Info Card --}}
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-center">
                <div class="w-16 h-16 mx-auto bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="white" fill-opacity="0.8"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="white" fill-opacity="0.9"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="white" fill-opacity="0.7"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="white"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white mb-1 font-arabic">{{ __('Almost Done!') }}</h2>
                <p class="text-blue-100 text-sm font-arabic">{{ __('Complete your center registration') }}</p>
            </div>

            {{-- Google Account Info --}}
            <div class="px-8 pt-6">
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                        {{ mb_substr(session('google_user.name', ''), 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 text-sm truncate">{{ session('google_user.name') }}</p>
                        <p class="text-slate-500 text-xs truncate">{{ session('google_user.email') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
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
                                   class="block w-full ps-11 pe-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-arabic text-sm"
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
                                   class="block w-full ps-11 pe-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm"
                                   placeholder="010xxxxxxx"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" 
                            class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] font-arabic flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ __('Create My Center') }}
                    </button>
                </form>

                <p class="text-center text-xs text-slate-400 mt-4 font-arabic">
                    {{ __('By continuing, you agree to our') }}
                    <a href="{{ route('terms') }}" class="text-blue-500 hover:underline">{{ __('Terms') }}</a>
                    {{ __('and') }}
                    <a href="{{ route('privacy') }}" class="text-blue-500 hover:underline">{{ __('Privacy Policy') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
