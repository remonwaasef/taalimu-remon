@extends('layouts.landing-new')

@section('content')
<!-- Import Cairo & Outfit Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('googleRegistration', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: config.selectedCycle || 'monthly',
        packages: config.packages,
        centerName: '{{ old('center_name') }}',
        subdomain: '{{ old('subdomain') }}',
        manuallyEditedSubdomain: {{ old('subdomain') ? 'true' : 'false' }},
        subdomainStatus: 'idle',
        subdomainMessage: '',
        isSubmitting: false,
        userCountry: 'default',
        selectedCurrency: config.selectedCurrency || 'EGP',
        accountType: config.accountType || 'center',
        paymentGateway: 'paymob', // Default to Paymob

        showPlanModal: false,
        phoneNumber: '{{ old("phone") }}',
        countryCode: '{{ old("country_code", app()->getLocale() === 'fr' ? '33' : '20') }}',
        phoneVerified: false,
        otpSent: false,
        otpCode: '',
        otpStatus: 'idle',
        otpMessage: '',
        otpCountdown: 0,
        otpTimer: null,
        isSendingOtp: false,
        isVerifyingOtp: false,

        couponCode: '',
        showCouponInput: false,
        couponStatus: 'none',
        couponMessage: '',
        discountValue: 0,
        discountType: 'percentage',
        discountText: '',
        isApplyingCoupon: false,

        async init() {
            // Smart IP Auto-Detection for Currency and Country Code
            if (!{{ Js::from(session()->has('suggested_currency') || request()->has('currency')) }}) {
                try {
                    const res = await fetch('https://ipapi.co/json/');
                    if(res.ok) {
                        const data = await res.json();
                        const country = data.country_code;
                        if (country === 'EG') this.selectedCurrency = 'EGP';
                        else if (country === 'SA') this.selectedCurrency = 'SAR';
                        else if (country === 'AE') this.selectedCurrency = 'AED';
                        else if (['FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'GR', 'PT', 'FI', 'IE'].includes(country)) this.selectedCurrency = 'EUR';
                        else this.selectedCurrency = 'USD';
                        
                        // Auto-set phone code
                        if (data.country_calling_code) {
                            this.countryCode = data.country_calling_code.replace('+', '');
                        }
                    }
                } catch(e) {
                    console.warn('IP detection failed, using defaults.');
                }
            }

            // Auto-select gateway based on detected currency/country
            if (this.selectedCurrency === 'EGP') {
                this.paymentGateway = 'paymob';
            } else {
                this.paymentGateway = 'paypal';
            }
        },

        get currentPlan() {
            return this.packages.find(p => p.slug === this.selectedPlan) || this.packages[0];
        },

        getPriceData(pkg) {
             if(!pkg) return { amount: 0, currency: '$', yearly: 0, old: 0, discount_label: '' };
             let prices = pkg.regional_prices || {};
             let data = {
                 amount: parseFloat(pkg.price_raw),
                 yearly: parseFloat(pkg.yearly_price_raw),
                 term: parseFloat(pkg.term_price_raw || (pkg.price_raw * 4)),
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

             // Map currency code to the regional_prices key (country code)
             const currencyToRegionKey = {
                 'EGP': 'EG',
                 'EUR': 'FR',
                 'USD': 'default',
                 'SAR': 'SA',
                 'AED': 'AE'
             };
             const regionKey = currencyToRegionKey[this.selectedCurrency] || 'default';

             if (prices[regionKey]) {
                 let r = prices[regionKey];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
                 data.term = parseFloat(r.term_price || (data.amount * 4));
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             } else if (prices['default']) {
                 let r = prices['default'];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
                 data.term = parseFloat(r.term_price || (data.amount * 4));
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             }
             return data;
        },

        get currentPriceData() {
             return this.getPriceData(this.currentPlan);
        },

        get activePriceRaw() {
             if (this.billingCycle === 'yearly') return (this.currentPriceData.yearly || 0);
             if (this.billingCycle === 'term') return (this.currentPriceData.term || 0);
             return (this.currentPriceData.amount || 0);
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
        },

        async sendOtp() {
            if (!this.phoneNumber || this.phoneNumber.length < 10) {
                this.otpMessage = {{ Js::from(__('auth.validation.invalid_phone')) }};
                this.otpStatus = 'error';
                return;
            }
            this.isSendingOtp = true;
            this.otpStatus = 'sending';
            this.otpMessage = '';
            try {
                const response = await fetch('/api/phone/send-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ phone: this.phoneNumber, country_code: this.countryCode })
                });
                const data = await response.json();
                if (data.success) {
                    this.otpSent = true;
                    this.otpStatus = 'sent';
                    this.otpMessage = data.message;
                    this.startCountdown(120);
                } else {
                    this.otpStatus = 'error';
                    this.otpMessage = data.message;
                }
            } catch (e) {
                this.otpStatus = 'error';
                this.otpMessage = {{ Js::from(__('auth.validation.error_occurred')) }};
            } finally {
                this.isSendingOtp = false;
            }
        },

        async verifyOtp() {
            if (!this.otpCode || this.otpCode.length !== 6) return;
            this.isVerifyingOtp = true;
            this.otpStatus = 'verifying';
            try {
                const response = await fetch('/api/phone/verify-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ phone: this.phoneNumber, otp: this.otpCode })
                });
                const data = await response.json();
                if (data.success) {
                    this.phoneVerified = true;
                    this.otpStatus = 'verified';
                    this.otpMessage = data.message;
                    if (this.otpTimer) { clearInterval(this.otpTimer); this.otpTimer = null; }
                } else {
                    this.otpStatus = 'error';
                    this.otpMessage = data.message;
                }
            } catch (e) {
                this.otpStatus = 'error';
                this.otpMessage = {{ Js::from(__('auth.validation.error_occurred')) }};
            } finally {
                this.isVerifyingOtp = false;
            }
        },

        startCountdown(seconds) {
            this.otpCountdown = seconds;
            if (this.otpTimer) clearInterval(this.otpTimer);
            this.otpTimer = setInterval(() => {
                this.otpCountdown--;
                if (this.otpCountdown <= 0) {
                    clearInterval(this.otpTimer);
                    this.otpTimer = null;
                }
            }, 1000);
        },

        get formattedCountdown() {
            const m = Math.floor(this.otpCountdown / 60);
            const s = this.otpCountdown % 60;
            return `${m}:${s.toString().padStart(2, '0')}`;
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
                this.couponMessage = 'Coupon error';
            } finally {
                this.isApplyingCoupon = false;
            }
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

        async checkSubdomain() {
            if (!this.subdomain) {
                this.subdomainStatus = 'idle';
                this.subdomainMessage = '';
                return;
            }
            this.subdomain = this.cleanSlug(this.subdomain);
            this.subdomainStatus = 'loading';
            try {
                const response = await fetch(`/api/validate-subdomain?subdomain=${this.subdomain}`);
                const data = await response.json();
                this.subdomainStatus = data.available ? 'valid' : 'invalid';
                this.subdomainMessage = data.message;
            } catch (e) {
                this.subdomainStatus = 'idle';
            }
        },

        handleSubmit(e) {
            // Prevent double submission
            if (this.isSubmitting) {
                e.preventDefault();
                return;
            }
            if (this.subdomainStatus === 'invalid') {
                e.preventDefault();
                alert('{{ __('auth.validation.subdomain_taken') }}');
                return;
            }
            this.isSubmitting = true;
            // Disable the form submit button via DOM as extra safety
            const btn = e.target.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;
        },

        handlePageShow(event) {
            // Reset isSubmitting when page is shown (handles browser Back button)
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                this.isSubmitting = false;
            }
        }
    }))
});

// Global listener for bfcache (back-forward cache)
window.addEventListener('pageshow', (event) => {
    // Dispatch custom event to Alpine components if needed, 
    // but here we can just target our data if we had a reference.
    // However, the best way is to do it inside the component if possible or just reset all
    window.dispatchEvent(new CustomEvent('reset-submission-state', { detail: { persisted: event.persisted } }));
});
</script>

<div class="min-h-dvh bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
     x-data="googleRegistration({
        selectedPlan: {{ Js::from($selectedPlanSlug) }},
        selectedCycle: {{ Js::from($selectedCycle) }},
        accountType: {{ Js::from($accountType) }},
        packages: {{ Js::from($packagesData) }},
        selectedCurrency: '{{ session('suggested_currency', 'EGP') }}'
     })"
     @reset-submission-state.window="isSubmitting = false"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <!-- Main Centered Card Container (Optimized Layout) -->
    <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden border border-slate-100/50 animate-fade-in-up md:backdrop-blur-xl relative" x-cloak>
        
        <div class="grid grid-cols-1 lg:grid-cols-12">
            <!-- Left Info Panel (Hidden on Mobile or as Sidebar) -->
            <div class="lg:col-span-4 bg-slate-50/50 border-e border-slate-100 p-6 lg:p-10 flex flex-col justify-between">
                <div>
                    {{-- Verified Account Pill --}}
                    <div class="mb-8 animate-fade-in">
                        <div class="flex items-center gap-3 p-3 bg-white border border-slate-100 rounded-2xl shadow-sm group hover:border-brand-secondary/30 transition-all">
                            <div class="w-10 h-10 bg-slate-50 border border-brand-secondary/10 rounded-full flex items-center justify-center text-brand-secondary font-black text-lg group-hover:scale-110 transition-transform shadow-sm">
                                {{ mb_substr(session('google_user.name', 'U'), 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-slate-900 text-xs truncate uppercase tracking-tight">{{ session('google_user.name', 'User') }}</p>
                                <p class="text-slate-500 text-[11px] truncate font-medium opacity-80">{{ session('google_user.email') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h1 class="text-2xl font-black text-slate-900 mb-2 font-arabic leading-tight">
                            {{ __('auth.google_registration.confirm_account') }}
                        </h1>
                        <p class="text-slate-500 text-xs font-arabic font-medium opacity-80 leading-relaxed">
                            {{ __('auth.google_registration.setup_complete') }}
                        </p>
                    </div>

                    <!-- Price Summary Card (Integrated into Sidebar) -->
                    <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('auth.register.selected_plan') }}</span>
                                    <button type="button" @click="showPlanModal = true" class="text-[11px] font-black text-brand-secondary underline hover:opacity-70 transition-opacity uppercase tracking-widest">
                                        {{ __('auth.google_registration.change') }}
                                    </button>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 font-arabic">
                                    <span x-text="currentPlan.name"></span>
                                    <span class="text-sm font-bold text-slate-400 ms-1" x-text="'(' + (billingCycle === 'yearly' ? (currentPriceData.yearly || 0).toLocaleString() : (billingCycle === 'term' ? (currentPriceData.term || 0).toLocaleString() : (currentPriceData.amount || 0).toLocaleString())) + ' ' + currentPriceData.currency + ')'"></span>
                                </h3>
                            </div>
                        </div>

                        <!-- Mini Features List -->
                        <div x-data="{ openFeatures: false }" class="py-3 border-y border-slate-50">
                            <button type="button" @click="openFeatures = !openFeatures" class="w-full flex items-center justify-center gap-2 text-[12px] font-black text-slate-700 font-arabic hover:text-brand-secondary transition-colors pb-2 cursor-pointer">
                                <span>{{ __('auth.google_registration.view_features') }}</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 transform" :class="openFeatures ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="openFeatures" x-transition.opacity.duration.300ms class="space-y-2 pt-2 border-t border-slate-50">
                                <template x-for="feature in (currentPlan.features || [])" :key="feature">
                                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 font-arabic">
                                        <i class="bi bi-check2 text-emerald-500"></i>
                                        <span x-text="feature"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Price & Switcher -->
                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-black text-slate-500 uppercase tracking-wide">{{ __('auth.register.total') ?? 'Total' }}</span>
                                <div class="text-right">
                                    <template x-if="currentPlan.trial_days > 0 && couponStatus !== 'valid'">
                                        <div class="flex items-center justify-end mb-2 animate-fade-in">
                                            <span class="text-[11px] font-black bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg border border-emerald-100 uppercase tracking-wider inline-flex items-center gap-1.5 shadow-sm">
                                                <i class="bi bi-gift-fill text-[11px]"></i>
                                                <span x-text="currentPlan.trial_days"></span> {{ __('auth.google_registration.days_free') }}
                                            </span>
                                        </div>
                                    </template>
                                    <template x-if="(couponStatus === 'valid' || currentPriceData.old_price_raw > finalPrice) && currentPlan.trial_days === 0">
                                        <div class="flex items-center justify-end gap-2 mb-1 animate-fade-in">
                                            <span class="text-[11px] font-bold text-slate-300 line-through">
                                                <span x-text="((couponStatus === 'valid' ? currentPriceData.price_raw : currentPriceData.old_price_raw) || 0).toLocaleString()"></span>
                                                <span x-text="currentPriceData.currency"></span>
                                            </span>
                                            <span class="text-[11px] font-black bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md">
                                                <template x-if="couponStatus === 'valid'">
                                                    <span>- <span x-text="couponDiscountAmount.toLocaleString()"></span></span>
                                                </template>
                                                <template x-if="couponStatus !== 'valid' && currentPriceData.old_price_raw > finalPrice">
                                                    <span>{{ __('auth.google_registration.discount_available') }}</span>
                                                </template>
                                            </span>
                                        </div>
                                    </template>
                                    <div class="flex items-baseline gap-1 justify-end" :class="currentPlan.trial_days > 0 ? 'text-emerald-500' : 'text-brand-secondary'">
                                        <span class="text-3xl font-black tracking-tighter" x-text="currentPlan.trial_days > 0 ? '0' : finalPrice.toLocaleString()"></span>
                                        <span class="text-sm font-bold opacity-60" x-text="currentPlan.trial_days > 0 ? ({{ Js::from(__('auth.google_registration.free')) }}) : currentPriceData.currency"></span>
                                    </div>
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest" x-text="'/ ' + (billingCycle === 'yearly' ? '{{ __('auth.register.billing_yearly_short') ?? 'year' }}' : (billingCycle === 'term' ? '{{ __('auth.register.billing_term') ?? 'term' }}' : '{{ __('auth.register.billing_monthly_short') ?? 'month' }}'))"></span>
                                </div>
                            </div>
                            
                            <div class="flex p-1 bg-slate-100 rounded-xl items-center">
                                    <button type="button" @click="billingCycle = 'monthly'" 
                                            class="flex-1 py-2 min-h-[40px] text-[11px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ __('auth.register.billing_monthly') ?? 'Month' }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'term'" 
                                            class="flex-1 py-2 min-h-[40px] text-[11px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ __('auth.register.billing_term') ?? 'Term' }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'yearly'" 
                                            class="flex-1 py-2 min-h-[40px] text-[11px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ __('auth.register.billing_yearly') ?? 'Yearly' }}
                                    </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-3 text-slate-400 text-[11px] font-bold opacity-40 px-6">
                    <i class="bi bi-shield-check text-emerald-500 text-sm"></i>
                    <span>{{ __('auth.register.all_data_secure') ?? 'All data is encrypted and secure' }}</span>
                </div>
            </div>

            <!-- Right Content Panel (Form) -->
            <div class="lg:col-span-8 p-6 lg:p-10">
                @if ($errors->any())
                    <div class="bg-red-50 border-2 border-red-50 rounded-2xl p-4 mb-6 animate-shake">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 text-red-600">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-black text-red-900 font-arabic mb-1">{{ __('auth.register.registration_error') }}</h3>
                                <ul class="text-[11px] text-red-700 font-arabic opacity-90">
                                    @foreach ($errors->all() as $error) <li>â€¢ {{ $error }}</li> @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('google.complete-registration') }}" method="POST" class="space-y-4" @submit="handleSubmit($event)">
                    @csrf
                    <input type="hidden" name="plan" :value="selectedPlan">
                    <input type="hidden" name="account_type" :value="accountType">
                    <input type="hidden" name="billing_cycle" :value="billingCycle">
                    <input type="hidden" name="currency" x-model="selectedCurrency">

                    {{-- Center Name --}}
                    <div class="space-y-1">
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            <span x-text="accountType === 'center' ? '{{ __('auth.register.center_name') }}' : ({{ Js::from(__('auth.registration_steps.teacher_name')) }})"></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-building"></i></div>
                            <input type="text" name="center_name" x-model="centerName"
                                @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                class="w-full h-11 ps-10 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-xl text-base font-bold font-arabic focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="{{ __('auth.register.center_name_placeholder') }}" required autofocus>
                        </div>
                    </div>

                    {{-- Subdomain Field --}}
                    <div class="space-y-1">
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.registration_steps.platform_link') }}</label>
                        <div class="relative flex items-center w-full group" dir="ltr">
                            <div class="absolute left-0 inset-y-0 hidden sm:flex items-center px-3 pointer-events-none text-brand-secondary font-black text-[11px] bg-brand-secondary/5 border-r border-brand-secondary/10 rounded-l-xl">https://</div>
                            <input type="text" name="subdomain" x-model="subdomain"
                                @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                @input.debounce.500ms="checkSubdomain()"
                                class="w-full h-11 pl-[60px] pr-[92px] sm:pl-[70px] sm:pr-[110px] bg-slate-50/50 border-2 border-slate-100 rounded-xl text-base font-bold font-sans focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="center-name" required>
                            <div class="absolute right-0 inset-y-0 flex items-center pr-3 pointer-events-none text-slate-400 font-bold text-[11px] gap-2">
                                <span>.taalimu.com</span>
                                <div class="flex items-center justify-center w-4 h-4">
                                    <template x-if="subdomainStatus === 'loading'"><div class="w-3 h-3 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                    <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-base"></i></template>
                                    <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-base"></i></template>
                                </div>
                            </div>
                        </div>
                        <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                           class="text-[11px] font-bold px-2 mt-0.5 animate-fade-in" x-text="subdomainMessage"></p>
                    </div>

                    {{-- Phone Field with OTP Verification --}}
                    <div class="space-y-1.5">
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.phone') }} <span class="text-[11px] font-normal text-slate-300">({{ __('auth.register.optional') ?? 'Optional' }})</span></label>
                        
                        {{-- Country Code + Phone Input + Send OTP Button --}}
                        <div class="relative flex gap-2">
                            {{-- Country Code Selector --}}
                            <div class="relative" dir="ltr">
                                <select x-model="countryCode" name="country_code"
                                    class="h-11 pl-2 pr-7 bg-slate-50/50 border-2 border-slate-100 rounded-xl text-sm font-black text-slate-700 focus:outline-none focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all appearance-none cursor-pointer"
                                    :class="phoneVerified ? 'border-emerald-300 bg-emerald-50/30 pointer-events-none opacity-60' : 'border-slate-100'">
                                    @include('partials.country-codes')
                                </select>
                                <div class="absolute inset-y-0 right-1 flex items-center pointer-events-none">
                                    <i class="bi bi-chevron-down text-[11px] text-slate-400"></i>
                                </div>
                            </div>
                            {{-- Phone Input --}}
                            <div class="relative flex-1 group">
                                <input type="text" name="phone" x-model="phoneNumber"
                                    class="w-full h-11 px-4 bg-slate-50/50 border-2 rounded-xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary border-slate-100 transition-all shadow-inner"
                                    placeholder="10xxxxxxx" dir="ltr">
                            </div>
                            {{-- Removed OTP Button for Google Auth --}}
                        </div>
                        {{-- Removed OTP verification block --}}

                    <!-- Coupon (Compact inline) -->
                    <div class="pt-2">
                        <button type="button" x-show="!showCouponInput && couponStatus !== 'valid'" @click="showCouponInput = true" 
                                class="text-[11px] font-black text-brand-secondary hover:underline flex items-center gap-1 font-arabic">
                            <i class="bi bi-tag-fill"></i> {{ __('auth.register.have_coupon') ?? 'هل لديك كود خصم؟' }}
                        </button>
                        <div x-show="showCouponInput || couponStatus === 'valid'" x-cloak class="space-y-2">
                            <div class="relative flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" name="coupon_code" x-model="couponCode" @keyup.enter="validateCoupon()"
                                        placeholder="{{ __('admin.coupon_code') }}"
                                        class="w-full h-11 px-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-black uppercase focus:outline-none focus:border-brand-secondary transition-all"
                                        :class="couponStatus === 'valid' ? 'border-emerald-200 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-200 bg-red-50' : '')">
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <template x-if="couponStatus === 'valid'"><i class="bi bi-patch-check-fill text-emerald-500 text-base"></i></template>
                                        <template x-if="couponStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-base"></i></template>
                                    </div>
                                </div>
                                <button type="button" @click="validateCoupon()" :disabled="isApplyingCoupon || !couponCode"
                                        class="h-11 px-6 rounded-xl bg-slate-900 text-white font-black text-[11px] uppercase tracking-widest hover:bg-brand-secondary transition-all disabled:opacity-50 flex items-center justify-center min-w-[80px]">
                                    <template x-if="isApplyingCoupon"><div class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></div></template>
                                    <span x-show="!isApplyingCoupon">{{ __('auth.google_registration.apply') }}</span>
                                </button>
                            </div>
                            <p x-show="couponMessage" :class="couponStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                               class="text-[11px] font-black px-2 animate-fade-in" x-text="couponMessage"></p>
                        </div>
                    </div>

                    <!-- Payment Gateway Selection -->
                    <div class="space-y-2 pt-2" x-show="currentPlan.trial_days === 0">
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            {{ __('auth.register.payment_method') ?? 'Payment' }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Paymob -->
                            <label class="relative cursor-pointer group" x-show="userCountry === 'EG' || userCountry === 'default'">
                                <input type="radio" name="payment_gateway" value="paymob" x-model="paymentGateway" class="peer sr-only">
                                <div class="flex items-center gap-2 p-3 rounded-xl border-2 border-slate-100 bg-slate-50/50 peer-checked:border-brand-secondary peer-checked:bg-white transition-all shadow-sm">
                                    <i class="bi bi-credit-card-2-back text-lg text-slate-400 peer-checked:text-brand-secondary"></i>
                                    <span class="text-xs font-black text-slate-600 peer-checked:text-slate-900">Paymob</span>
                                </div>
                            </label>
                            <!-- PayPal -->
                            <label class="relative cursor-pointer group" x-show="userCountry !== 'EG' && userCountry !== 'default'">
                                <input type="radio" name="payment_gateway" value="paypal" x-model="paymentGateway" class="peer sr-only">
                                <div class="flex items-center gap-2 p-3 rounded-xl border-2 border-slate-100 bg-slate-50/50 peer-checked:border-brand-secondary peer-checked:bg-white transition-all shadow-sm">
                                    <i class="bi bi-paypal text-lg text-slate-400 peer-checked:text-brand-secondary"></i>
                                    <span class="text-xs font-black text-slate-600 peer-checked:text-slate-900">PayPal</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="isSubmitting || subdomainStatus === 'invalid' || (phoneNumber.length > 0 && phoneNumber.length < 8)"
                                class="w-full h-14 rounded-full flex items-center justify-center gap-3 group bg-brand-secondary text-white shadow-xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-0.5 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale">
                             <span x-show="!isSubmitting" class="text-lg font-black font-arabic" 
                                   x-text="currentPlan.trial_days > 0 ? ({{ Js::from(__('auth.google_registration.start_free_trial')) }}) : (finalPrice === 0 ? '{{ __('auth.register.cta_main') }}' : '{{ __('auth.google_registration.pay_complete') }}')">
                            </span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <div class="w-5 h-5 border-3 border-white border-t-transparent rounded-full animate-spin"></div>
                                {{ __('auth.google_registration.completing') }}
                            </span>
                            <i x-show="!isSubmitting" class="bi bi-arrow-right-short text-2xl group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                        </button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-[11px] text-slate-400 font-arabic leading-relaxed">
                            {{ __('auth.register.terms_prefix') }}
                            <a href="{{ route('terms') }}" class="text-slate-900 font-black hover:underline underline-offset-4">{{ __('auth.register.terms_of_service') }}</a> 
                            {{ __('auth.register.and') }} 
                            <a href="{{ route('privacy') }}" class="text-slate-900 font-black hover:underline underline-offset-4">{{ __('auth.register.privacy_policy') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Plan Selection Modal (Restored) -->
    <div x-show="showPlanModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak>
        <div @click.away="showPlanModal = false" 
             class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden animate-scale-in">
            <div class="p-5 sm:p-8 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-xl font-black text-slate-900 font-arabic">{{ __('auth.plan_modal.select_plan') }}</h3>
                    
                    <!-- Billing Country Selector -->
                    <div class="flex items-center gap-2 bg-slate-100/50 p-1.5 rounded-2xl border border-slate-200">
                        <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider px-2 whitespace-nowrap"><i class="bi bi-globe-americas me-1"></i> {{ __('auth.plan_modal.billing_region') }}</span>
                        <div class="relative" dir="ltr">
                            <select x-model="selectedCurrency" class="h-8 pl-3 pr-8 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-secondary/20 focus:border-brand-secondary appearance-none cursor-pointer shadow-sm min-w-[120px]">
                                <option value="EGP">ðŸ‡ªðŸ‡¬ Egypt (EGP)</option>
                                <option value="SAR">ðŸ‡¸ðŸ‡¦ Saudi Arabia (SAR)</option>
                                <option value="AED">ðŸ‡¦ðŸ‡ª UAE (AED)</option>
                                <option value="EUR">ðŸ‡ªðŸ‡º Europe (EUR)</option>
                                <option value="USD">ðŸ‡ºðŸ‡¸ Global (USD)</option>
                            </select>
                            <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
                                <i class="bi bi-chevron-down text-[11px] text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" @click="showPlanModal = false" aria-label="{{ __('auth.plan_modal.close') }}" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-200 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <!-- Billing Toggle in Modal -->
                <div class="flex justify-center">
                    <div class="inline-flex flex-wrap justify-center bg-slate-200/50 p-1.5 rounded-2xl border border-slate-200 gap-1" dir="ltr">
                        <button type="button" @click="billingCycle = 'monthly'" 
                                class="px-4 sm:px-5 py-2 min-h-[40px] rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                            {{ __('auth.billing.monthly') }}
                        </button>
                        <button type="button" @click="billingCycle = 'term'" 
                                class="px-4 sm:px-5 py-2 min-h-[40px] rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                            {{ __('auth.billing.term') }}
                        </button>
                        <button type="button" @click="billingCycle = 'yearly'" 
                                class="px-4 sm:px-5 py-2 min-h-[40px] rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                            {{ __('auth.billing.yearly') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-5 sm:p-8 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <template x-for="pkg in packages" :key="pkg.slug">
                    <label class="relative block cursor-pointer group">
                        <input type="radio" name="plan_selector" :value="pkg.slug" x-model="selectedPlan" @change="showPlanModal = false" class="peer sr-only">
                        <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-brand-secondary/30 peer-checked:border-brand-secondary peer-checked:bg-brand-secondary/5 transition-all">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white"
                                         :class="selectedPlan === pkg.slug ? 'border-brand-secondary' : 'border-slate-200'">
                                        <div class="w-2.5 h-2.5 rounded-full bg-brand-secondary transition-transform"
                                             :class="selectedPlan === pkg.slug ? 'scale-100' : 'scale-0'"></div>
                                    </div>
                                    <span class="font-black text-slate-900 uppercase tracking-tight" x-text="pkg.name"></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-black text-brand-secondary" x-text="(billingCycle === 'yearly' ? getPriceData(pkg).yearly : (billingCycle === 'term' ? getPriceData(pkg).term : getPriceData(pkg).amount)).toLocaleString() + ' ' + getPriceData(pkg).currency"></span>
                                </div>
                            </div>
                        </div>
                    </label>
                </template>
            </div>
            <div class="p-6 bg-slate-50 text-center">
                <button type="button" @click="showPlanModal = false" class="text-sm font-black text-slate-500 hover:text-slate-700 transition-colors uppercase tracking-widest">
                    {{ __('auth.plan_modal.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
