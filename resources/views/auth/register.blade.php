@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">



<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: config.billingCycle || 'monthly',
        packages: config.packages,
        centerName: config.centerName,
        subdomain: config.subdomain,
        manuallyEditedSubdomain: config.manuallyEditedSubdomain,
        showPassword: false,
        password: '',
        password_confirmation: '',
        couponCode: '',
        showCouponInput: false,
        couponStatus: 'none',
        couponMessage: '',
        discountValue: 0,
        discountType: 'percentage',
        discountText: '',
        discountText: '',
        isApplyingCoupon: false,
        userCountry: 'default',

        async init() {
            // Default select first plan if requested plan is invalid or missing
            if (!this.packages.find || !this.packages.find(p => p.slug === this.selectedPlan)) {
                this.selectedPlan = (this.packages && this.packages.length > 0) ? this.packages[0].slug : '';
            }

            try {
                const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                const data = await response.json();
                this.userCountry = data.country || '';
            } catch(e) { console.log('IP fetch failed', e); }
        },

        get currentPlan() {
            const plan = this.packages.find(p => p.slug === this.selectedPlan);
            if (!plan) return {name: 'Plan not found', price: '0', price_raw: 0, currency: '$', yearly_price_raw: 0};
            return plan;
        },

        getPriceData(pkg) {
             if(!pkg) return { amount: 0, currency: '$', yearly: 0, old: 0, discount_label: '' };
             
             let prices = pkg.regional_prices || {};
             // Default from PHP data
             let data = {
                 amount: parseFloat(pkg.price_raw),
                 yearly: parseFloat(pkg.yearly_price_raw),
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

             if (this.userCountry && prices[this.userCountry]) {
                 let r = prices[this.userCountry];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10)); // Default annual logic
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             }
             return data;
        },

        get currentPriceData() {
             return this.getPriceData(this.currentPlan);
        },

        get activePriceRaw() {
            const plan = this.currentPlan;
            const priceData = this.getPriceData(plan);
            return this.billingCycle === 'yearly' ? (priceData.yearly || 0) : (priceData.amount || 0);
        },
        
        get activePriceValue() {
             return this.activePriceRaw.toLocaleString();
        },

        generateSlug(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        },

        cleanSlug(text) {
            return text.toString().toLowerCase().replace(/[^a-z0-9\-]/g, '');
        },

        get isPasswordMatch() {
            return this.password === this.password_confirmation && this.password.length > 0;
        },

        get passwordCriteria() {
            return {
                length: this.password.length >= 8,
                upper: /[A-Z]/.test(this.password),
                lower: /[a-z]/.test(this.password),
                number: /[0-9]/.test(this.password),
                symbol: /[!@#$%^&*(),.?{}:|<>]/.test(this.password)
            }
        },

        async validateCoupon() {
            if (!this.couponCode) return;
            this.isApplyingCoupon = true;
            this.couponStatus = 'loading';
            try {
                const response = await fetch(`/api/coupons/validate?code=${this.couponCode}&plan=${this.selectedPlan}`);
                const data = await response.json();
                if (data.valid) {
                    this.couponStatus = 'valid';
                    this.couponMessage = data.message;
                    this.discountText = data.discount_text;
                    this.discountType = data.type;
                    this.discountValue = data.value;
                } else {
                    this.couponStatus = 'invalid';
                    this.couponMessage = data.message;
                    this.discountValue = 0;
                }
            } catch (e) {
                this.couponStatus = 'invalid';
                this.couponMessage = '{{ __('auth.coupon_error') }}';
            } finally {
                this.isApplyingCoupon = false;
            }
        },

        get couponDiscountAmount() {
            if (this.couponStatus !== 'valid') return 0;
            const price = this.activePriceRaw || 0;
            if (this.discountType === 'percentage') {
                return (price * (this.discountValue / 100));
            }
            return Math.min(this.discountValue, price);
        },

        get finalPrice() {
            const price = this.activePriceRaw || 0;
            return Math.max(0, price - this.couponDiscountAmount);
        }
    }))
})
</script>

<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay" 
     style="padding-top: 120px;"
     x-data="registrationForm({
        selectedPlan: {{ Js::from(request('plan', $packages->firstWhere('is_default', true)?->slug ?? $packages->first()?->slug ?? '')) }},
        billingCycle: {{ Js::from(request('cycle', 'monthly')) }},
        packages: {{ Js::from($packagesData) }},
        centerName: {{ Js::from(old('center_name')) }},
        subdomain: {{ Js::from(old('subdomain')) }},
        manuallyEditedSubdomain: {{ old('subdomain') ? 'true' : 'false' }},
        userCountry: ''
     })"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <!-- Main Centered Card Container -->
    <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden flex flex-col lg:flex-row border border-slate-100/50 min-h-[640px] animate-fade-in-up md:backdrop-blur-xl relative" style="max-width: 960px;">
        
        <!-- Left Panel: Elite Compact Plan Selection -->
        <div class="lg:w-[32%] text-white flex flex-col p-8 lg:p-10 relative overflow-hidden" 
             style="background: linear-gradient(135deg, hsl(263 85% 20%) 0%, hsl(var(--primary-purple)) 40%, hsl(var(--secondary)) 100%);">
            <!-- Subtle glow in left panel -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 blur-[80px] rounded-full pointer-events-none"></div>
            <div class="flex-1 flex flex-col w-full">
            <div class="space-y-8 flex-1">
                @if(app()->getLocale() == 'ar')
                <div class="mb-2">
                    <span class="text-[10px] text-white/50 font-bold uppercase tracking-widest">{{ __('auth.register.subtitle') }}</span>
                </div>
                @endif
                <h2 class="text-2xl lg:text-3xl font-bold mb-4 font-arabic leading-tight text-white">
                    {{ __('auth.register.branding_title') }}
                </h2>
                    <p class="text-white/70 text-base font-arabic font-light leading-relaxed">
                        {{ __('auth.register.branding_subtitle') }}
                    </p>
                </div>

                <div class="space-y-3">
                    <label class="text-[9px] font-black text-white/40 uppercase tracking-widest px-1 mb-1 block">{{ __('auth.register.select_plan') }}</label>
                    @foreach($packages as $package)
                    <div @click="selectedPlan = '{{ $package->slug }}'"
                        class="w-full text-center p-6 plan-card-compact cursor-pointer relative group/card mb-6 border transition-all duration-300 overflow-hidden"
                        :class="selectedPlan === '{{ $package->slug }}' ? 'selected' : 'border-white/5 hover:border-white/20 bg-white/[0.02]'"
                        x-data="{ localPkg: packages.find(p => p.slug === '{{ $package->slug }}') }"
                    >
                        
                        <!-- Mini Badge for Type -->
                        <div class="inline-flex mb-3">
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-white/40 px-3 py-1 bg-white/5 rounded-full border border-white/5 group-hover/card:text-white group-hover/card:bg-white/10 transition-all">
                                {{ app()->getLocale() == 'ar' ? $package->name : $package->name_en }}
                            </span>
                        </div>

                        <!-- Ticket Price -->
                        <div class="flex flex-col items-center" x-data="{ localPrice: getPriceData(localPkg) }" x-effect="localPrice = getPriceData(localPkg)">
                                <template x-if="localPrice.old && ((billingCycle === 'yearly' ? (localPrice.old * 12) : localPrice.old) > (billingCycle === 'yearly' ? localPrice.yearly : localPrice.amount))">
                                    <div class="flex items-center gap-3 mb-4 px-5 py-2 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 shadow-lg shadow-emerald-500/10">
                                        <del class="text-[14px] text-white/40 font-bold decoration-white/20" 
                                             x-text="billingCycle === 'yearly' ? (localPrice.old * 12).toLocaleString() : localPrice.old.toLocaleString()">
                                        </del>
                                        <span class="w-1.5 h-4 bg-white/10 rounded-full"></span>
                                        <span class="text-[16px] font-black text-emerald-400 uppercase tracking-tighter">
                                            {{ __('auth.register.save') }} 
                                            <span x-text="billingCycle === 'yearly' ? ((localPrice.old * 12) - localPrice.yearly).toLocaleString() : (localPrice.old - localPrice.amount).toLocaleString()"></span>
                                        </span>
                                    </div>
                                </template>

                            <div class="flex items-start justify-center transition-transform duration-500 group-hover/card:scale-105">
                                <span class="text-5xl font-black text-white tracking-tighter leading-none" 
                                      x-text="billingCycle === 'yearly' ? localPrice.yearly.toLocaleString() : localPrice.amount.toLocaleString()">
                                </span>
                                <div class="flex flex-col ml-1 rtl:mr-1 rtl:ml-0 mt-1">
                                    <span class="text-[14px] font-bold text-white/30" x-text="localPrice.currency"></span>
                                    <span class="text-[10px] font-bold text-white/50" x-text="billingCycle === 'yearly' ? '{{ __('landing.pricing.per_year') ?? '/سنوي' }}' : '{{ __('landing.pricing.per_month') ?? '/شهري' }}'"></span>
                                </div>
                            </div>
                            <!-- Monthly Equivalent for Yearly -->
                            <template x-if="billingCycle === 'yearly'">
                                <div class="mt-1 text-[11px] text-white/40 font-bold">
                                    ({{ __('landing.pricing.equivalent_to') ?? 'ما يعادل' }} 
                                    <span x-text="Math.round(localPrice.yearly / 12).toLocaleString()"></span>
                                    <span x-text="localPrice.currency"></span>/{{ __('landing.pricing.month_short') ?? 'شهر' }})
                                </div>
                            </template>

                            <template x-if="localPrice.discount_label">
                                <div class="mt-4 text-[9px] font-bold text-white/30 uppercase tracking-[0.15em] border-t border-white/5 pt-3 w-full" x-text="localPrice.discount_label"></div>
                            </template>
                        </div>
                        
                        <div class="space-y-1.5 mt-3">
                            @php
                                $features = $package->features->filter(function($feature) {
                                    $val = $feature->pivot->value;
                                    // Skip disabled boolean features or 0 limits
                                    if ($feature->type === 'boolean' && ($val === 'false' || $val === '0' || !$val || trim($val) === '')) return false;
                                    if ($feature->type === 'limit' && ($val === '0' || $val === 0 || !$val)) return false;
                                    return true;
                                })->take(6);
                            @endphp
                            @foreach($features as $feature)
                            @php
                                $val = $feature->pivot->value;
                                $displayVal = $val;
                                if ($val === '-1') {
                                    $displayVal = __('features.unlimited') ?? 'غير محدود';
                                } elseif ($feature->type === 'boolean') {
                                    $displayVal = ''; // Don't show value for boolean, just the checkmark and name
                                } elseif (is_numeric($val) && $val > 0) {
                                     $unit = '';
                                     if ($feature->code === 'max_students') $unit = ' ' . (__('admin.students') ?? 'طالب');
                                     if ($feature->code === 'max_instructors') $unit = ' ' . (__('admin.instructors') ?? 'مدرس');
                                     $displayVal = $val . $unit;
                                }
                            @endphp
                            <div class="flex items-center gap-2 text-[10px] text-white/80 font-arabic font-medium opacity-80 group-hover:opacity-100 transition-opacity">
                                <div class="flex-shrink-0 w-3 h-3 rounded-full bg-white/10 flex items-center justify-center">
                                    <svg class="w-2 h-2 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>
                                    @php
                                        $valKey = 'features.' . $feature->code;
                                        $translatedName = __($valKey);
                                        if ($translatedName === $valKey) {
                                            $translatedName = app()->getLocale() === 'en' && $feature->name_en ? $feature->name_en : $feature->name;
                                        }
                                    @endphp
                                    {{ $translatedName }}
                                    @if($displayVal)
                                        <span class="opacity-70">({{ $displayVal }})</span>
                                    @endif
                                </span>
                            </div>
                            @endforeach
                            @if($package->features->count() > 6)
                            <p class="text-[9px] text-white/40 font-bold mt-1 pr-5">+ {{ $package->features->count() - 6 }} {{ __('auth.register.more_features') }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-auto pt-8 border-t border-white/5 opacity-60">
                    <p class="text-[10px] text-white/50 font-arabic">
                        {{ __('auth.register.join_leaders') }}
                    </p>
                </div>
            </div>
        </div>

            <!-- Right Panel: Compact Registration Form -->
            <div class="lg:w-[68%] bg-white flex flex-col p-8 lg:p-12 relative">
                <div class="absolute top-6 {{ app()->getLocale() == 'ar' ? 'left-8' : 'right-8' }} z-10">
                    <span class="text-sm text-slate-500">
                        {{ __('auth.login.no_account_link') }}
                        <a href="{{ route('login.portal') }}" class="text-brand-secondary font-bold hover:opacity-80 transition-all ml-1">{{ __('auth.login.title') }}</a>
                    </span>
                </div>

                <div class="max-w-[440px] mx-auto pt-10">
                    <div class="mb-8">
                        <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 mb-2 font-arabic tracking-tight">
                            {{ __('auth.register.title') }}
                        </h1>
                        <p class="text-slate-500 text-sm font-arabic font-light">
                            {{ __('auth.register.subtitle') }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-3 px-4 border border-slate-200 rounded-xl shadow-sm text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>{{ __('Sign up with Google') }}</span>
                        </a>
                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase">
                                <span class="bg-white px-2 text-slate-400 font-bold tracking-wider">{{ __('auth.register.or') ?? 'OR' }}</span>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        @if(request('google_id'))
                            <input type="hidden" name="google_id" value="{{ request('google_id') }}">
                        @endif
                        
                        <!-- Global Error Alert -->
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                                        <h3 class="text-sm font-medium text-red-800 font-arabic">
                                            {{ __('auth.register.registration_error') }}
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 font-arabic">
                                            <ul class="list-disc pl-5 rtl:pr-5 rtl:pl-0 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <input type="hidden" name="plan" x-model="selectedPlan">
                        <input type="hidden" name="billing_cycle" x-model="billingCycle">
                        <input type="hidden" name="country_code" x-model="userCountry">

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="label-compact px-1 font-arabic">{{ __('auth.register.center_name') }}</label>
                                <input type="text" name="center_name" x-model="centerName"
                                    @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); }"
                                    class="w-full h-12 input-compact px-4 text-sm font-medium font-arabic text-slate-900"
                                    placeholder="{{ __('auth.register.center_name_placeholder') }}" 
                                    value="{{ request('center_name') }}"
                                    required>
                                @error('center_name') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>



                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="label-compact px-1 font-arabic">{{ __('auth.register.full_name') }}</label>
                                    <input type="text" name="name" 
                                        class="w-full h-12 input-compact px-4 text-sm font-medium font-arabic text-slate-900"
                                        required value="{{ old('name', request('name')) }}">
                                </div>
                                <div class="space-y-1">
                                    <label class="label-compact px-1 font-arabic">{{ __('auth.register.email') }}</label>
                                    <input type="email" name="email" 
                                        class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900"
                                        placeholder="mail@example.com"
                                        required value="{{ old('email', request('email')) }}">
                                </div>
                                <div class="space-y-1">
                                    <label class="label-compact px-1 font-arabic">{{ __('auth.register.phone') }}</label>
                                    <input type="text" name="phone" 
                                        class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900"
                                        placeholder="010xxxxxxx"
                                        required value="{{ old('phone', request('phone')) }}">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex justify-between items-center px-1">
                                    <label class="label-compact font-arabic">{{ __('auth.register.password') }}</label>
                                    <button type="button" @click="showPassword = !showPassword" class="text-[10px] font-black text-brand-secondary uppercase tracking-tighter">
                                        <span x-text="showPassword ? '{{ __('auth.register.hide') }}' : '{{ __('auth.register.show') }}'"></span>
                                    </button>
                                </div>
                                <!-- Password Fields with Toggle -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="relative group/pass">
                                        <input :type="showPassword ? 'text' : 'password'" type="password" name="password" x-model="password"
                                            class="w-full h-12 input-compact px-4 pr-11 rtl:pl-11 rtl:pr-4 text-sm font-medium text-slate-900"
                                            placeholder="••••••••" required>
                                        <button type="button" @click="showPassword = !showPassword" 
                                            class="absolute right-3 rtl:left-3 rtl:right-auto top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-secondary transition-colors p-1">
                                            <i class="bi" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                        </button>
                                    </div>
                                    <div class="relative group/pass">
                                        <input :type="showPassword ? 'text' : 'password'" type="password" name="password_confirmation" x-model="password_confirmation"
                                            class="w-full h-12 input-compact px-4 pr-11 rtl:pl-11 rtl:pr-4 text-sm font-medium text-slate-900"
                                            :class="password_confirmation.length > 0 && !isPasswordMatch ? 'border-red-300 bg-red-50 shadow-[0_0_0_4px_rgba(239,68,68,0.1)]' : ''"
                                            placeholder="{{ __('auth.register.confirm_password') }}" required>
                                    </div>
                                </div>

                                <!-- Password Strength Bulbs Refined -->
                                <div class="bulb-section">
                                    <div class="flex flex-wrap items-center justify-between gap-y-3">
                                        <!-- Length -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.length ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.length ? 'active-length' : ''"></div>
                                            <span class="bulb-text font-arabic">{{ __('auth.register.password_criteria.chars') }}</span>
                                        </div>
                                        <!-- Uppercase -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.upper ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.upper ? 'active-upper' : ''"></div>
                                            <span class="bulb-text font-arabic">{{ __('auth.register.password_criteria.upper') }}</span>
                                        </div>
                                        <!-- Lowercase -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.lower ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.lower ? 'active-lower' : ''"></div>
                                            <span class="bulb-text font-arabic">{{ __('auth.register.password_criteria.lower') }}</span>
                                        </div>
                                        <!-- Number -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.number ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.number ? 'active-number' : ''"></div>
                                            <span class="bulb-text font-arabic">{{ __('auth.register.password_criteria.numbers') }}</span>
                                        </div>
                                        <!-- Symbol -->
                                        <div class="bulb-container" :class="passwordCriteria.symbol ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.symbol ? 'active-symbol' : ''"></div>
                                            <span class="bulb-text font-arabic">{{ __('auth.register.password_criteria.symbols') }}</span>
                                        </div>
                                        <!-- Match -->
                                        <div class="bulb-container" :class="isPasswordMatch ? 'active' : ''">
                                            <div class="bulb" :class="isPasswordMatch ? 'active-match' : ''"></div>
                                            <span class="bulb-text font-arabic">{{ __('auth.register.password_criteria.match') ?? 'تطابق' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coupon Field -->
                            <div class="space-y-2 pt-2">
                                <template x-if="!showCouponInput && couponStatus !== 'valid'">
                                    <button type="button" @click="showCouponInput = true" class="text-sm font-bold text-brand-secondary hover:opacity-80 flex items-center gap-2 group font-arabic transition-all">
                                        <i class="bi bi-tag-fill group-hover:rotate-12 transition-transform"></i>
                                        {{ __('auth.register.have_coupon') ?? 'هل لديك كود خصم؟' }}
                                    </button>
                                </template>

                                <div x-show="showCouponInput || couponStatus === 'valid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-1">
                                    <label class="label-compact px-1 font-arabic">{{ __('admin.coupon_code') }}</label>
                                    <div class="relative">
                                        <input type="text" name="coupon_code" x-model="couponCode" @input.debounce.500ms="validateCoupon()"
                                            class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900 uppercase"
                                            placeholder="PROMO20" :class="couponStatus === 'valid' ? 'border-emerald-300 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-300 bg-red-50' : '')">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                            <template x-if="couponStatus === 'loading'">
                                                <div class="w-4 h-4 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div>
                                            </template>
                                            <template x-if="couponStatus === 'valid'">
                                                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                                            </template>
                                            <template x-if="couponStatus === 'invalid'">
                                                <button type="button" @click="couponCode = ''; couponStatus = 'none'; showCouponInput = false" class="text-red-500 hover:text-red-700">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    <template x-if="couponMessage">
                                        <p class="text-[10px] font-bold mt-1 px-1" :class="couponStatus === 'valid' ? 'text-emerald-600' : 'text-red-600'" x-text="couponMessage"></p>
                                    </template>
                                    <template x-if="couponStatus === 'valid'">
                                        <div class="mt-2 p-2 bg-emerald-500/10 border border-emerald-500/20 rounded-lg flex items-center justify-between">
                                            <span class="text-[10px] font-bold text-emerald-700 font-arabic">{{ __('auth.register.discount_applied') }} (<span x-text="couponCode"></span>)</span>
                                            <span class="text-xs font-black text-emerald-700" x-text="discountText"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Indicator (Ticket-Style) -->
                        <div class="mt-10 mb-8 p-6 rounded-[32px] bg-white border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] transition-all duration-300 group/summary">
                            <div class="flex flex-col items-center md:items-start text-center md:text-start">
                                <span class="text-[11px] font-black text-blue-500/40 uppercase tracking-[0.2em] mb-2">{{ __('auth.register.selected_plan') }}</span>
                                <h3 class="text-xl font-black text-slate-900 font-arabic leading-none" x-text="currentPlan.name || '{{ __('auth.register.please_select_plan') ?? 'يرجى اختيار باقة' }}'"></h3>
                            </div>
                            
                            <div class="flex flex-col items-center md:items-end">
                                <div class="flex flex-col items-center md:items-end gap-1">
                                    <!-- Original Package Price (Crossed out if package has its own discount) -->
                                    <template x-if="currentPriceData.old">
                                        <div class="flex items-center gap-2 opacity-30">
                                            <del class="text-sm font-bold" x-text="billingCycle === 'yearly' ? (currentPriceData.old).toLocaleString() : currentPriceData.old.toLocaleString()"></del>
                                            <span class="text-[10px] font-bold" x-text="currentPriceData.currency"></span>
                                        </div>
                                    </template>

                                    <!-- Current Selection Price and Calculation -->
                                    <div class="flex flex-col items-center md:items-end">
                                        <!-- Price before coupon if coupon added -->
                                        <template x-if="couponStatus === 'valid'">
                                            <div class="flex flex-col items-center md:items-end mb-2">
                                                <div class="text-xs font-bold text-slate-400 line-through" x-text="activePriceValue + ' ' + currentPriceData.currency"></div>
                                                <div class="text-[10px] font-black text-emerald-600 uppercase tracking-tight">
                                                    {{ __('auth.register.discount_applied') }}: -<span x-text="couponDiscountAmount.toLocaleString() + ' ' + currentPriceData.currency"></span>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Final Final Price -->
                                        <div class="flex items-start justify-end transition-all text-brand-secondary">
                                            <span class="text-5xl font-black tracking-tighter leading-none" x-text="finalPrice.toLocaleString()"></span>
                                            <div class="flex flex-col ml-1 rtl:mr-1 rtl:ml-0 mt-1">
                                                <span class="text-[14px] font-bold opacity-40" x-text="currentPriceData.currency"></span>
                                                <span class="text-[10px] font-bold opacity-40 -mt-1" x-text="billingCycle === 'yearly' ? '{{ __('landing.pricing.per_year') ?? '/سنوياً' }}' : '{{ __('landing.pricing.per_month') ?? '/شهرياً' }}'"></span>
                                            </div>
                                        </div>
                                        <!-- Monthly Equivalent note for Yearly in Summary -->
                                        <template x-if="billingCycle === 'yearly'">
                                            <div class="text-[10px] font-bold text-slate-400 mt-1 text-end">
                                                ({{ __('landing.pricing.equivalent_to') ?? 'ما يعادل' }} 
                                                <span x-text="Math.round(finalPrice / 12).toLocaleString()"></span>
                                                <span x-text="currentPriceData.currency"></span>/{{ __('landing.pricing.month_short') ?? 'شهر' }})
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                :disabled="password.length > 0 && !isPasswordMatch"
                                class="btn-hero-cta w-full h-16 rounded-[24px] flex items-center justify-center gap-3 group transition-all duration-300">
                                <span class="text-xl font-black font-arabic">{{ __('auth.register.cta_main') }}</span>
                                <svg class="w-6 h-6 transform group-hover:translate-x-1 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                            
                            <p class="mt-6 text-center text-[11px] text-slate-400 font-arabic leading-relaxed">
                                {{ __('auth.register.terms_prefix') }}
                                <a href="#" class="text-slate-900 font-bold hover:underline">{{ __('auth.register.terms_of_service') }}</a> 
                                {{ __('auth.register.and') }} 
                                <a href="#" class="text-slate-900 font-bold hover:underline">{{ __('auth.register.privacy_policy') }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
