@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .bg-emerald-600 { background-color: #2E8B83 !important; }
    .shadow-emerald-600\/20 { box-shadow: 0 10px 15px -3px rgba(46, 139, 131, 0.2), 0 4px 6px -4px rgba(46, 139, 131, 0.1) !important; }
    .from-emerald-600 { --tw-gradient-from: #2E8B83 !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(46, 139, 131, 0)) !important; }
    .to-teal-500 { --tw-gradient-to: #14b8a6 !important; }
    .hover\:from-emerald-700:hover { --tw-gradient-from: #25746D !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(4, 120, 87, 0)) !important; }
    .hover\:to-teal-600:hover { --tw-gradient-to: #0d9488 !important; }
    @keyframes focusPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(46, 139, 131, 0.15); }
        50% { box-shadow: 0 0 0 8px rgba(46, 139, 131, 0); }
    }
    .input-guide-pulse { animation: focusPulse 2s ease-in-out 3; }
</style>
@include('auth.partials._register-scripts')

<div class="min-h-[85vh] min-h-[85dvh] bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
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
    
    <!-- Main Card -->
    <div class="w-full transition-all duration-500 bg-white rounded-2xl shadow-2xl shadow-blue-900/5 overflow-hidden border border-slate-100/50 animate-fade-in-up md:backdrop-blur-xl relative"
         x-cloak
         :class="currentStep === 2 ? 'max-w-4xl' : 'max-w-xl'">
        
        <div class="bg-white p-5 lg:p-6">
            <form action="{{ route('register.submit') }}" method="POST" 
                  @submit="if(currentStep === 1) { $event.preventDefault(); nextStep(); } else { if(formSubmitted) { $event.preventDefault(); return; } formSubmitted = true; const btn = $event.target.querySelector('button[type=submit]'); if(btn) btn.disabled = true; }">
                @csrf
                @if(request('google_id'))
                    <input type="hidden" name="google_id" value="{{ request('google_id') }}">
                @endif
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-100 rounded-xl p-3 mb-3">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm mt-0.5"></i>
                            <ul class="text-[11px] text-red-700 font-arabic space-y-0.5">
                                @foreach ($errors->all() as $error) <li>• {{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <input type="hidden" name="plan" x-model="selectedPlan">
                <input type="hidden" name="account_type" x-model="accountType">
                <input type="hidden" name="billing_cycle" x-model="billingCycle">
                <input type="hidden" name="country_code" x-model="userCountry">
                <input type="hidden" name="currency" x-model="selectedCurrency">

                <!-- Unified Visual Stepper -->
                <div class="mb-5 pb-4 border-b border-slate-100">
                    <div class="flex items-center justify-between max-w-md mx-auto px-2">
                        <!-- Step 1 -->
                        <div class="flex items-center gap-2.5 transition-all" :class="currentStep === 2 ? 'cursor-pointer hover:opacity-80' : ''" @click="if(currentStep === 2) prevStep()">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                 :class="currentStep === 1 ? 'bg-brand-secondary text-white shadow-md shadow-brand-secondary/20 ring-4 ring-brand-secondary/10' : 'bg-emerald-500 text-white'">
                                <template x-if="currentStep > 1"><i class="bi bi-check-lg text-sm"></i></template>
                                <template x-if="currentStep === 1"><span>1</span></template>
                            </div>
                            <div class="text-start">
                                <span class="text-xs font-black block leading-tight font-arabic" :class="currentStep === 1 ? 'text-slate-900' : 'text-slate-500'">
                                    <span x-text="accountType === 'center' ? '{{ __('auth.registration_steps.center_info') }}' : '{{ __('auth.registration_steps.teacher_info') }}'"></span>
                                </span>
                                <span class="text-[10px] font-bold block leading-tight font-arabic" :class="currentStep === 1 ? 'text-brand-secondary' : 'text-emerald-600'">
                                    <span x-show="currentStep === 1">{{ __('auth.registration_steps.step_1') }}</span>
                                    <span x-show="currentStep > 1">مكتملة ✓</span>
                                </span>
                            </div>
                        </div>

                        <!-- Connector Line -->
                        <div class="flex-1 mx-3 sm:mx-4 h-0.5 rounded-full transition-all duration-500"
                             :class="currentStep === 2 ? 'bg-emerald-500' : 'bg-slate-200'"></div>

                        <!-- Step 2 -->
                        <div class="flex items-center gap-2.5 transition-all">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                 :class="currentStep === 2 ? 'bg-brand-secondary text-white shadow-md shadow-brand-secondary/20 ring-4 ring-brand-secondary/10' : 'bg-slate-100 text-slate-400'">
                                <span>2</span>
                            </div>
                            <div class="text-start">
                                <span class="text-xs font-black block leading-tight font-arabic" :class="currentStep === 2 ? 'text-slate-900' : 'text-slate-400'">
                                    {{ __('auth.registration_steps.personal_details') }}
                                </span>
                                <span class="text-[10px] font-bold block leading-tight font-arabic" :class="currentStep === 2 ? 'text-brand-secondary' : 'text-slate-300'">
                                    {{ __('auth.registration_steps.last_step') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 1: Fast & Intuitive Onboarding --}}
                <div x-show="currentStep === 1" x-cloak>

                    <!-- Main Header + Clear Login Link at TOP -->
                    <div class="text-center mb-4">
                        <h1 class="text-xl font-black text-slate-900 mb-1 font-arabic leading-tight">
                            <span x-show="accountType === 'center'">{{ __('auth.registration_steps.start_free_trial') }}</span>
                            <span x-show="accountType === 'instructor'">{{ __('auth.registration_steps.start_instructor_platform') }}</span>
                        </h1>
                        <p class="text-xs text-slate-500 font-arabic font-semibold">
                            <span x-show="accountType === 'instructor'" class="text-emerald-700 font-bold block mb-1">
                                {{ __('auth.registration_steps.instructor_tagline') }}
                            </span>
                            {{ __('auth.login.no_account_link') }}
                            <a href="{{ route('login.portal') }}" class="text-brand-secondary font-black hover:underline me-1 bg-brand-secondary/5 px-2 py-0.5 rounded-full border border-brand-secondary/10">
                                {{ __('auth.login.title') }}
                            </a>
                        </p>
                    </div>

                    <!-- Account Type Selector (First Interaction Step) -->
                    @include('auth.partials._register-account-type')

                    <!-- Google Fast Registration (Right After Account Type Selection) -->
                    <div class="mb-4">
                        <a :href="'{{ route('auth.google') }}?plan=' + selectedPlan + '&cycle=' + billingCycle + '&account_type=' + (accountType || 'center')" 
                           class="w-full flex items-center justify-center gap-3 py-2.5 px-6 border-2 border-slate-200 rounded-xl text-sm font-black text-slate-800 bg-white hover:bg-slate-50 hover:border-brand-secondary/40 hover:shadow-md transition-all group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>{{ __('auth.register.google_signup') }}</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-4 px-6">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                        <div class="relative flex justify-center text-[11px] uppercase">
                            <span class="bg-white px-4 text-slate-400 font-bold tracking-[0.15em]">
                                {{ __('auth.registration_steps.or_fill_below') }}
                            </span>
                        </div>
                    </div>

                    <!-- Center / Platform Name Input -->
                    <div class="space-y-3 mb-4">
                        <div>
                            <label class="text-[11px] font-bold text-slate-500 px-1 font-arabic block mb-1">
                                <span x-text="accountType === 'center' 
                                    ? '{{ __('auth.registration_steps.center_name') }}'
                                    : '{{ __('auth.registration_steps.teacher_name') }}'"></span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors">
                                    <i class="bi text-lg" :class="accountType === 'center' ? 'bi-building' : 'bi-person-badge'"></i>
                                </div>
                                <input type="text" name="center_name" x-model="centerName"
                                    x-ref="centerNameInput"
                                    @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                    class="w-full h-11 ps-11 pe-4 bg-white border border-slate-200 rounded-xl text-sm font-bold font-arabic focus:outline-none focus:ring-2 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all"
                                    :placeholder="accountType === 'center' ? '{{ __('auth.register.center_name_placeholder') }}' : '{{ __('auth.registration_steps.teacher_name_placeholder') }}'" :required="currentStep === 1">
                            </div>
                        </div>

                        <!-- Subdomain (Auto-filled) -->
                        <div>
                            <label class="text-[11px] font-bold text-slate-400 px-1 font-arabic block mb-1">
                                {{ __('auth.registration_steps.platform_url') }}
                            </label>
                            <div class="relative flex items-center w-full" dir="ltr">
                                <div class="absolute left-0 inset-y-0 hidden sm:flex items-center px-3 pointer-events-none text-brand-secondary font-bold text-[11px] bg-brand-secondary/5 border-r border-brand-secondary/10 rounded-l-xl z-10">https://</div>
                                <input type="text" name="subdomain" x-model="subdomain"
                                    @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                    @input.debounce.500ms="checkSubdomain()"
                                    class="w-full h-9 pl-[60px] pr-[92px] sm:pl-[70px] sm:pr-[110px] bg-slate-50/80 border border-slate-200 rounded-xl text-sm font-bold font-sans focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all"
                                    :placeholder="accountType === 'center' ? 'center-name' : '{{ __('auth.registration_steps.teacher_subdomain_placeholder') }}'" :required="currentStep === 1">
                                <div class="absolute right-0 inset-y-0 flex items-center pr-3 pointer-events-none text-slate-400 font-bold text-[11px] gap-2 z-10">
                                    <span>.taalimu.com</span>
                                    <div class="flex items-center justify-center w-4 h-4">
                                        <template x-if="subdomainStatus === 'loading'"><div class="w-3.5 h-3.5 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                        <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i></template>
                                        <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-sm"></i></template>
                                    </div>
                                </div>
                            </div>
                            <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                               class="text-[11px] font-bold px-1 mt-0.5" x-text="subdomainMessage"></p>
                        </div>
                    </div>

                    <!-- Continue Button -->
                    <button type="button" @click="nextStep()"
                        class="w-full h-11 rounded-xl flex items-center justify-center gap-2 group bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-0.5 active:scale-[0.98] transition-all mb-3">
                        <span class="text-sm font-black font-arabic">{{ __('auth.registration_steps.next_personal') }}</span>
                        <i class="bi bi-arrow-left text-base rtl:rotate-0 ltr:rotate-180 group-hover:-translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                    </button>

                    <!-- Trust Signals -->
                    <template x-if="currentPlan.trial_days > 0">
                        <div class="flex items-center justify-center gap-3 text-[11px] text-slate-400 font-bold mb-2">
                            <span class="flex items-center gap-1">
                                <i class="bi bi-shield-check text-emerald-500"></i>
                                <span x-text="currentPlan.trial_days"></span> {{ __('auth.registration_steps.days_free_trial') }}
                            </span>
                            <span class="text-slate-200">|</span>
                            <span class="flex items-center gap-1">
                                <i class="bi bi-credit-card text-slate-300"></i>
                                {{ __('auth.registration_steps.no_credit_card') }}
                            </span>
                        </div>
                    </template>

                    <!-- Footer Terms -->
                    <div class="mt-2 text-center pb-1">
                        <p class="text-[11px] text-slate-400 font-arabic">
                            {{ __('auth.register.terms_prefix') }}
                            <a href="{{ route('terms') }}" class="text-slate-600 font-black hover:underline">{{ __('auth.register.terms_of_service') }}</a> 
                            {{ __('auth.register.and') }} 
                            <a href="{{ route('privacy') }}" class="text-slate-600 font-black hover:underline">{{ __('auth.register.privacy_policy') }}</a>
                        </p>
                    </div>
                </div>

                {{-- STEP 2 CONTAINER --}}
                <div x-show="currentStep === 2" x-cloak>
                    <div class="text-center mb-4">
                        <h1 class="text-xl font-black text-slate-900 mb-1 font-arabic leading-tight">
                            {{ __('auth.registration_steps.enter_details') }}
                        </h1>
                        <p class="text-xs text-slate-400 font-arabic font-medium">
                            {{ __('auth.registration_steps.last_step') }}
                        </p>
                    </div>

                    @include('auth.partials._register-step2')
                </div>

            </form>
        </div>

        @include('auth.partials._register-plan-modal')
    </div>
</div>
@endsection
