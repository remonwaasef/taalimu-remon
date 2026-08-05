@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    /* Premium Identity Missing Classes (since Tailwind JIT isn't running) */
    .bg-emerald-600 { background-color: #2E8B83 !important; }
    .shadow-emerald-600\/20 { box-shadow: 0 10px 15px -3px rgba(46, 139, 131, 0.2), 0 4px 6px -4px rgba(46, 139, 131, 0.1) !important; }
    .from-emerald-600 { --tw-gradient-from: #2E8B83 !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(46, 139, 131, 0)) !important; }
    .to-teal-500 { --tw-gradient-to: #14b8a6 !important; }
    .hover\:from-emerald-700:hover { --tw-gradient-from: #25746D !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(4, 120, 87, 0)) !important; }
    .hover\:to-teal-600:hover { --tw-gradient-to: #0d9488 !important; }

    /* Blur/Unblur effect for account type selection (JIT-only classes missing from built CSS) */
    .blur-\[5px\] { -webkit-filter: blur(5px); filter: blur(5px); }
    .translate-y-\[3vh\] { --tw-translate-y: 3vh; transform: translate(var(--tw-translate-x, 0), var(--tw-translate-y, 0)) scale(var(--tw-scale-x, 1), var(--tw-scale-y, 1)); }
</style>
@include('auth.partials._register-scripts')

<div class="min-h-[85vh] bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
     x-data="registrationForm({
        selectedPlan: {{ Js::from(old('plan', request('plan', $packages->firstWhere('is_default', true)?->slug ?? $packages->first()?->slug ?? ''))) }},
        billingCycle: {{ Js::from(old('billing_cycle', request('cycle', 'monthly'))) }},
        packages: {{ Js::from($packagesData) }},
        centerName: {{ Js::from(old('center_name')) }},
        subdomain: {{ Js::from(old('subdomain')) }},
        manuallyEditedSubdomain: {{ old('subdomain') ? 'true' : 'false' }},
        accountType: {{ Js::from(old('account_type', $accountType)) }},
        selectedCurrency: {{ Js::from(old('currency', request('currency', session('suggested_currency', 'EGP')))) }},
        userCountry: '{{ session('user_country_code', '') }}'
     })"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <!-- Main Centered Card Container (Simplified Single Column) -->
    <div class="w-full transition-all duration-500 bg-white rounded-2xl shadow-2xl shadow-blue-900/5 overflow-hidden border border-slate-100/50 animate-fade-in-up md:backdrop-blur-xl relative"
         x-cloak
         :class="currentStep === 2 ? 'max-w-4xl' : 'max-w-xl'">
        
        <!-- Minimalist Progress & Header -->
        <div class="bg-white p-4 lg:p-6 pb-0">
            <!-- Multi-Step Indicator -->
            <div class="mb-6 relative transition-all duration-500" x-cloak :class="!accountType ? 'blur-[5px] opacity-40 pointer-events-none select-none' : ''">
                <div class="flex justify-between mb-1.5 px-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] transition-colors" :class="currentStep === 1 ? 'text-brand-secondary' : 'text-slate-300'">
                        {{ app()->isLocale('ar') ? 'بيانات المركز' : 'Center Info' }}
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] transition-colors" :class="currentStep === 2 ? 'text-brand-secondary' : 'text-slate-300'">
                        {{ app()->isLocale('ar') ? 'البيانات الشخصية' : 'Personal Details' }}
                    </span>
                </div>
                <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                    <div class="h-full bg-brand-secondary shadow-[0_0_15px_rgba(122,77,252,0.3)] transition-all duration-700 ease-out" 
                         :style="`width: ${currentStep === 1 ? '50%' : '100%'}`"></div>
                </div>
            </div>

            <!-- Contextual Header -->
            <div class="mb-4 text-center transition-all duration-700 ease-in-out" :class="!accountType ? 'transform scale-110 translate-y-[3vh] mb-8' : ''">
                <div x-show="currentStep === 1" x-cloak class="flex flex-col items-center">
                    <h1 class="text-xl lg:text-2xl font-black text-slate-900 mb-2 font-arabic leading-tight">
                        {{ app()->isLocale('ar') ? 'ابدأ رحلتك التعليمية' : 'Start Your Journey' }}
                    </h1>
                    
                    <!-- Premium Trial Badge -->
                    <template x-if="currentPlan.trial_days > 0">
                        <div class="flex flex-col items-center">
                            <div class="inline-flex items-center gap-2.5 py-2 px-5 rounded-full bg-emerald-50 border border-emerald-100 mb-2 shadow-sm animate-fade-in hover:scale-105 transition-transform duration-300">
                                <div class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </div>
                                <span class="text-sm font-black text-emerald-700 font-arabic tracking-tight">
                                     <span x-text="currentPlan.trial_days"></span>
                                     {{ app()->isLocale('ar') ? 'يوم تجربة مجانية بالكامل' : 'Days Full Free Trial' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-1.5 mb-4 opacity-80">
                                <i class="bi bi-shield-check text-emerald-600 text-xs"></i>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    {{ app()->isLocale('ar') ? 'لا يلزم وجود بطاقة ائتمان' : 'No Credit Card Required' }}
                                </span>
                            </div>
                        </div>
                    </template>

                    <template x-if="!currentPlan.trial_days || currentPlan.trial_days <= 0">
                        <div class="inline-flex items-center justify-center gap-2 mb-4 bg-brand-secondary/5 py-1 px-4 rounded-full w-fit mx-auto">
                            <i class="bi bi-rocket-takeoff text-brand-secondary text-xs"></i>
                            <span class="text-[10px] font-black text-brand-secondary uppercase tracking-widest">
                                {{ app()->isLocale('ar') ? 'ابدأ الآن' : 'Start Now' }}
                            </span>
                        </div>
                    </template>

                    <p class="text-slate-500 text-xs font-arabic font-medium opacity-80 max-w-[320px] mx-auto">
                        {{ app()->isLocale('ar') ? 'خطوات بسيطة لامتلاك منصتك التعليمية المتكاملة' : 'Simple steps to own your integrated platform' }}
                    </p>
                </div>
                <div x-show="currentStep === 2" x-cloak>
                    <h1 class="text-lg lg:text-xl font-black text-slate-900 mb-1 font-arabic leading-tight">
                        {{ app()->isLocale('ar') ? 'تأكيد الهوية' : 'Confirm Identity' }}
                    </h1>
                    <p class="text-slate-500 text-xs font-arabic font-medium opacity-80">
                        {{ app()->isLocale('ar') ? 'أدخل بياناتك الشخصية للبدء فوراً' : 'Enter personal details to get started' }}
                    </p>
                </div>
            </div>

@include('auth.partials._register-account-type')

            <!-- Content below account type selection -> blurred until selected -->
            <div class="transition-all duration-500" :class="!accountType ? 'blur-[5px] opacity-40 pointer-events-none select-none' : ''">
                
            <!-- Google Shortcut (Now below selection) -->
            <div class="mb-6">
                <a :href="'{{ route('auth.google') }}?plan=' + selectedPlan + '&cycle=' + billingCycle + '&account_type=' + (accountType || 'center')" class="w-full flex items-center justify-center gap-2 py-2.5 px-6 border-2 border-slate-200 rounded-2xl shadow-sm text-base font-black text-slate-800 bg-white hover:bg-slate-50 hover:border-blue-500/30 hover:shadow-md transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>{{ __('auth.register.google_signup') }}</span>
                </a>
                <div class="relative my-4 px-8">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100/80"></div></div>
                    <div class="relative flex justify-center text-[10px] uppercase"><span class="bg-white px-5 text-slate-300 font-bold tracking-[0.2em]">{{ trans('auth.register.or') }}</span></div>
                </div>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4" @submit="if(currentStep === 1) { $event.preventDefault(); nextStep(); } else { if(formSubmitted) { $event.preventDefault(); return; } formSubmitted = true; const btn = $event.target.querySelector('button[type=submit]'); if(btn) btn.disabled = true; }">
                @csrf
                @if(request('google_id'))
                    <input type="hidden" name="google_id" value="{{ request('google_id') }}">
                @endif
                
                @if ($errors->any())
                    <div class="bg-red-50 border-2 border-red-50 rounded-3xl p-6 mb-8 animate-shake">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 text-red-600">
                                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-red-900 font-arabic mb-1">{{ __('auth.register.registration_error') }}</h3>
                                <ul class="text-[13px] text-red-700 font-arabic opacity-90 space-y-0.5">
                                    @foreach ($errors->all() as $error) <li>• {{ $error }}</li> @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <input type="hidden" name="plan" x-model="selectedPlan">
                <input type="hidden" name="account_type" x-model="accountType">
                <input type="hidden" name="billing_cycle" x-model="billingCycle">
                <input type="hidden" name="country_code" x-model="userCountry">
                <input type="hidden" name="currency" x-model="selectedCurrency">

@include('auth.partials._register-step1')

@include('auth.partials._register-step2')

                <div class="mt-4 text-center">
                    <p class="text-[10px] text-slate-400 font-arabic leading-relaxed">
                        {{ __('auth.register.terms_prefix') }}
                        <a href="{{ route('terms') }}" class="text-slate-900 font-black hover:underline underline-offset-4">{{ __('auth.register.terms_of_service') }}</a> 
                        {{ __('auth.register.and') }} 
                        <a href="{{ route('privacy') }}" class="text-slate-900 font-black hover:underline underline-offset-4">{{ __('auth.register.privacy_policy') }}</a>
                    </p>
                </div>
            </form>
            </div> <!-- End Blurred Wrapper -->
        </div>

@include('auth.partials._register-plan-modal')
    </div>
@endsection
