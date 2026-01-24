@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts (if not already in layout) -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --font-outfit: 'Outfit', sans-serif;
        --font-cairo: 'Cairo', sans-serif;
        --brand-indigo: #4F46E5;
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
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
        transform: translateY(-1px);
        outline: none;
    }

    .btn-submit-compact {
        border-radius: 50px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        background-color: var(--brand-indigo);
        color: white;
        border: none;
    }

    .btn-submit-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        background-color: #4338CA;
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
            <div class="d-inline-flex justify-center items-center rounded-full bg-indigo-50 text-indigo-600 w-20 h-20 mb-4 mx-auto text-3xl font-bold shadow-sm">
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
                            {{ __('تنبيه') }}
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
                <label class="text-[11px] font-bold text-slate-500 px-1 font-arabic uppercase tracking-wider">{{ __('auth.login.email') }} / رقم الهاتف</label>
                <input type="text" name="email" 
                    class="w-full h-12 input-compact px-4 text-sm font-medium font-arabic text-slate-900 placeholder:text-slate-400"
                    placeholder="name@example.com / 01xxxxxxxx"
                    required autofocus>
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center px-1">
                    <label class="text-[11px] font-bold text-slate-500 font-arabic uppercase tracking-wider">{{ __('auth.login.password') }}</label>
                    <button type="button" @click="showPassword = !showPassword" class="text-[9px] font-black text-indigo-500 hover:text-indigo-600 transition-colors uppercase tracking-widest">
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

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
            <a href="{{ route('home') }}" class="group flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-3.5 h-3.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-arabic">{{ __('landing.navbar.home') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection
