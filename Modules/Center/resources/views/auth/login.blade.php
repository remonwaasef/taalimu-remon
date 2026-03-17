@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts (if not already in layout) -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --font-outfit: 'Outfit', sans-serif;
        --font-cairo: 'Cairo', sans-serif;
        --brand-indigo: #162963;
        --brand-indigo-hover: #1e3a8a;
        --panel-dark: #0F172A;
        --bg-field: #F8FAFC;
    }
    
    body {
        font-family: var(--font-outfit);
        background-color: #FFFFFF;
    }
    
    [lang="ar"] body, .font-arabic {
        font-family: var(--font-cairo);
    }

    .input-compact {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        background: var(--bg-field);
    }

    .input-compact:focus {
        border-color: var(--brand-indigo);
        background: #FFFFFF;
        box-shadow: 0 0 0 4px rgba(22, 41, 99, 0.08);
        transform: translateY(-1px);
        outline: none;
    }

    .btn-submit-compact {
        border-radius: 50px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(22, 41, 99, 0.2);
        background-color: var(--brand-indigo);
        color: white;
        border: none;
    }

    .btn-submit-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(22, 41, 99, 0.3);
        background-color: var(--brand-indigo-hover);
    }

    .btn-submit-compact:active {
        transform: translateY(0);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="min-h-screen bg-slate-50/50 flex justify-center items-center p-4 lg:p-8" 
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <div class="w-full max-w-md bg-white rounded-[40px] shadow-2xl shadow-slate-200/60 p-8 lg:p-10 border border-slate-100 animate-fade-in-up">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="d-inline-flex justify-center items-center rounded-full w-20 h-20 mb-4 mx-auto text-3xl font-bold shadow-sm" style="background: rgba(22, 41, 99, 0.08); color: #162963;">
                {{ substr($tenant->name ?? 'C', 0, 1) }}
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2 font-arabic tracking-tight">
                {{ $tenant->name ?? __('auth.login.title') }}
            </h1>
            <p class="text-slate-500 text-sm font-arabic font-light">
                {{ __('auth.login.subtitle') }}
            </p>
        </div>

        <!-- Global Error Alert -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                         <h3 class="text-sm font-bold text-red-800 font-arabic mb-1">
                            {{ __(__('center::messages.blade_0155')) }}
                        </h3>
                        <p class="text-sm text-red-600 font-arabic leading-relaxed">
                            {{ $errors->first() }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('center.login.submit') }}" method="POST" class="space-y-5" x-data="{ showPassword: false }">
            @csrf
            
            <div class="space-y-1.5">
                <label class="text-[11px] font-bold text-slate-500 px-1 font-arabic uppercase tracking-wider">{{ __('auth.login.email_or_phone') }}</label>
                <input type="text" name="email" 
                    class="w-full h-12 input-compact px-4 text-sm font-medium font-arabic text-slate-900 placeholder:text-slate-400"
                    placeholder="{{ __('auth.login.email_or_phone') }}"
                    required autofocus>
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center px-1">
                    <label class="text-[11px] font-bold text-slate-500 font-arabic uppercase tracking-wider">{{ __('auth.login.password') }}</label>
                    <button type="button" @click="showPassword = !showPassword" class="text-[9px] font-black transition-colors uppercase tracking-widest" style="color: #162963;">
                        <span x-text="showPassword ? ({{ app()->getLocale() == 'ar' ? '\'إخفاء\'' : '\'HIDE\'' }}) : ({{ app()->getLocale() == 'ar' ? '\'إظهار\'' : '\'SHOW\'' }})"></span>
                    </button>
                </div>
                <input :type="showPassword ? 'text' : 'password'" name="password" 
                    class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900 placeholder:text-slate-400"
                    placeholder="••••••••" required>
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full h-14 btn-submit-compact text-white font-bold text-base flex justify-center items-center gap-2 group">
                    <span class="font-arabic">{{ __('auth.login.login_button') }}</span>
                    <svg class="w-4 h-4 text-white/80 group-hover:translate-x-1 {{ app()->getLocale() == 'ar' ? 'group-hover:-translate-x-1' : '' }} transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ app()->getLocale() == 'ar' ? 'M10 19l-7-7m0 0l7-7m-7 7h18' : 'M14 5l7 7m0 0l-7 7m7-7H3' }}"></path>
                    </svg>
                </button>
            </div>
        </form>

        {{-- Separator --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 bg-white text-slate-400 font-arabic font-semibold">{{ __('or') }}</span>
            </div>
        </div>

        {{-- Google Sign-in Button --}}
        <a href="{{ route('auth.google') }}" 
           class="w-full h-12 flex items-center justify-center gap-3 border-2 border-slate-200 rounded-[20px] text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 group">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            <span class="font-arabic">{{ __('Sign in with Google') }}</span>
        </a>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            <a href="{{ route('home') }}" class="group flex items-center gap-2 text-xs font-semibold text-slate-400 transition-colors" style="--tw-text-opacity: 1;" onmouseover="this.style.color='#162963'" onmouseout="this.style.color=''>
                <svg class="w-3.5 h-3.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-arabic">{{ __('landing.navbar.home') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection
