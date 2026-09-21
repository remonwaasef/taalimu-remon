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
            if (!text) return '';
            const transliteration = {
                'ا': 'a', 'أ': 'a', 'إ': 'a', 'آ': 'a',
                'ب': 'b', 'ت': 't', 'ث': 'th', 'ج': 'g', 'ح': 'h', 'خ': 'kh',
                'د': 'd', 'ذ': 'dh', 'ر': 'r', 'ز': 'z', 'س': 's', 'ش': 'sh',
                'ص': 's', 'ض': 'd', 'ط': 't', 'ظ': 'z', 'ع': 'a', 'غ': 'gh',
                'ف': 'f', 'ق': 'k', 'ك': 'k', 'ل': 'l', 'م': 'm', 'ن': 'n',
                'ه': 'h', 'و': 'w', 'ي': 'y', 'ة': 'h', 'ى': 'a', 'ئ': 'e',
                'ء': 'a', 'ؤ': 'o'
            };
            let str = text.toString().toLowerCase();
            str = str.split('').map(c => transliteration[c] || c).join('');
            return str
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9\-]+/g, '')
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
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl shadow-slate-900/5 overflow-hidden border border-slate-100 animate-fade-in-up relative" x-cloak>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
            <!-- Sidebar Panel (Info & Selected Plan Summary) -->
            <div class="lg:col-span-5 bg-slate-50/70 border-b lg:border-b-0 lg:border-e border-slate-100 p-6 lg:p-8 flex flex-col justify-between space-y-6">
                <div class="space-y-6">
                    {{-- Verified Account Pill --}}
                    <div>
                        <div class="flex items-center gap-3 p-3 bg-white border border-slate-200/80 rounded-2xl shadow-sm hover:border-brand-primary/30 transition-all">
                            <div class="w-10 h-10 bg-brand-50 border border-brand-200 rounded-full flex items-center justify-center text-brand-primary font-black text-lg shadow-sm flex-shrink-0">
                                {{ mb_substr(session('google_user.name', 'U'), 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <p class="font-bold text-slate-900 text-xs truncate">{{ session('google_user.name', 'User') }}</p>
                                    <i class="bi bi-patch-check-fill text-emerald-500 text-xs" title="Google Verified"></i>
                                </div>
                                <p class="text-slate-500 text-[11px] truncate font-medium">{{ session('google_user.email') }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h1 class="text-xl lg:text-2xl font-black text-slate-900 mb-1.5 font-arabic leading-tight">
                            {{ __('auth.google_registration.confirm_account') }}
                        </h1>
                        <p class="text-slate-500 text-xs font-arabic font-medium leading-relaxed">
                            {{ __('auth.google_registration.setup_complete') }}
                        </p>
                    </div>

                    <!-- Price Summary Card -->
                    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <span class="text-[11px] font-bold text-slate-400 font-arabic block">{{ __('auth.register.selected_plan') }}</span>
                                <h3 class="text-base font-black text-slate-900 font-arabic">
                                    <span x-text="currentPlan.name"></span>
                                    <span class="text-xs font-bold text-slate-400 ms-1 font-sans" x-text="'(' + (billingCycle === 'yearly' ? (currentPriceData.yearly || 0).toLocaleString() : (billingCycle === 'term' ? (currentPriceData.term || 0).toLocaleString() : (currentPriceData.amount || 0).toLocaleString())) + ' ' + currentPriceData.currency + ')'"></span>
                                </h3>
                            </div>
                            <button type="button" @click="showPlanModal = true" class="text-xs font-bold text-brand-primary hover:underline font-arabic cursor-pointer">
                                {{ __('auth.google_registration.change') }}
                            </button>
                        </div>

                        {{-- CASE 1: FREE TRIAL BANNER --}}
                        <template x-if="currentPlan.trial_days > 0">
                            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-100/80 text-center space-y-1">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-black font-arabic">
                                    <i class="bi bi-gift-fill text-emerald-600"></i>
                                    <span x-text="currentPlan.trial_days"></span> {{ __('auth.google_registration.days_free') }}
                                </div>
                                <div class="flex items-baseline justify-center gap-1 text-emerald-600 pt-1">
                                    <span class="text-3xl font-black tracking-tight">0</span>
                                    <span class="text-sm font-bold font-arabic">{{ __('auth.google_registration.free') }}</span>
                                </div>
                                <p class="text-[11px] font-bold text-slate-400 font-arabic">بدون بطاقة ائتمان • تفعيل فوري لكامل المميزات</p>
                            </div>
                        </template>

                        {{-- CASE 2: PAID PLAN SUMMARY --}}
                        <template x-if="currentPlan.trial_days === 0">
                            <div class="pt-1 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-600 font-arabic">{{ __('auth.register.total') }}</span>
                                    <div class="text-end">
                                        <template x-if="couponStatus === 'valid' || (currentPriceData.old_price_raw > finalPrice)">
                                            <div class="flex items-center gap-1.5 justify-end text-xs text-slate-400 line-through">
                                                <span x-text="((couponStatus === 'valid' ? currentPriceData.price_raw : currentPriceData.old_price_raw) || 0).toLocaleString()"></span>
                                                <span x-text="currentPriceData.currency"></span>
                                            </div>
                                        </template>
                                        <div class="flex items-baseline gap-1 justify-end text-brand-primary">
                                            <span class="text-2xl font-black tracking-tight" x-text="finalPrice.toLocaleString()"></span>
                                            <span class="text-xs font-bold text-slate-500" x-text="currentPriceData.currency"></span>
                                            <span class="text-xs font-medium text-slate-400 font-arabic" x-text="'/ ' + (billingCycle === 'yearly' ? '{{ __('auth.register.billing_yearly_short') }}' : (billingCycle === 'term' ? '{{ __('auth.register.billing_term') }}' : '{{ __('auth.register.billing_monthly_short') }}'))"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex p-1 bg-slate-100 rounded-xl items-center gap-1">
                                    <button type="button" @click="billingCycle = 'monthly'" 
                                            class="flex-1 py-1.5 min-h-[36px] text-xs font-bold rounded-lg transition-all font-arabic cursor-pointer"
                                            :class="billingCycle === 'monthly' ? 'bg-white text-brand-primary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ __('auth.register.billing_monthly') }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'term'" 
                                            class="flex-1 py-1.5 min-h-[36px] text-xs font-bold rounded-lg transition-all font-arabic cursor-pointer"
                                            :class="billingCycle === 'term' ? 'bg-white text-brand-primary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ __('auth.register.billing_term') }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'yearly'" 
                                            class="flex-1 py-1.5 min-h-[36px] text-xs font-bold rounded-lg transition-all font-arabic cursor-pointer"
                                            :class="billingCycle === 'yearly' ? 'bg-white text-brand-primary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ __('auth.register.billing_yearly') }}
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Mini Features List -->
                        <div x-data="{ openFeatures: false }" class="pt-2 border-t border-slate-100">
                            <button type="button" @click="openFeatures = !openFeatures" class="w-full flex items-center justify-between text-xs font-bold text-slate-600 font-arabic hover:text-brand-primary transition-colors py-1 cursor-pointer">
                                <span class="flex items-center gap-1.5">
                                    <i class="bi bi-stars text-amber-500"></i>
                                    <span>{{ __('auth.google_registration.view_features') }}</span>
                                </span>
                                <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="openFeatures ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="openFeatures" x-transition.opacity.duration.200ms class="space-y-2 pt-2 border-t border-slate-50">
                                <template x-for="feature in (currentPlan.features || []).filter(f => !f.toLowerCase().includes(': false') && !f.toLowerCase().endsWith('false'))" :key="feature">
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-slate-600 font-arabic">
                                        <i class="bi bi-check2 text-emerald-500 flex-shrink-0"></i>
                                        <span x-text="feature"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-slate-400 text-xs font-medium font-arabic pt-2">
                    <i class="bi bi-shield-check text-emerald-500 text-base"></i>
                    <span>{{ __('auth.register.all_data_secure') }}</span>
                </div>
            </div>

            <!-- Form Panel -->
            <div class="lg:col-span-7 p-6 lg:p-10 flex flex-col justify-center">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-100 rounded-2xl p-4 mb-5">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 text-red-600 text-sm">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-black text-red-900 font-arabic mb-1">{{ __('auth.register.registration_error') }}</h3>
                                <ul class="text-[11px] text-red-700 font-arabic space-y-0.5">
                                    @foreach ($errors->all() as $error) <li>• {{ $error }}</li> @endforeach
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

                    <!-- Account Type: Clean Segmented Tab -->
                    <div class="flex items-center justify-center mb-2">
                        <div class="inline-flex p-1 bg-slate-100/80 rounded-xl border border-slate-200/60 shadow-inner">
                            <button type="button" @click="accountType = 'instructor'"
                                    class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer"
                                    :class="accountType === 'instructor' 
                                        ? 'bg-white text-emerald-800 shadow-sm font-black ring-1 ring-slate-200/80' 
                                        : 'text-slate-500 hover:text-slate-700'">
                                <i class="bi bi-person-video3 text-sm"></i>
                                {{ __('auth.registration_steps.tutor') }}
                            </button>
                            <button type="button" @click="accountType = 'center'"
                                    class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer"
                                    :class="accountType === 'center' 
                                        ? 'bg-white text-emerald-800 shadow-sm font-black ring-1 ring-slate-200/80' 
                                        : 'text-slate-500 hover:text-slate-700'">
                                <i class="bi bi-building text-sm"></i>
                                {{ __('auth.registration_steps.center') }}
                            </button>
                        </div>
                    </div>

                    {{-- Center / Teacher Name --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 font-arabic">
                            <span x-text="accountType === 'center' ? '{{ __('auth.register.center_name') }}' : '{{ __('auth.registration_steps.teacher_name') }}'"></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-primary transition-colors">
                                <i class="bi text-base" :class="accountType === 'center' ? 'bi-building' : 'bi-person-badge'"></i>
                            </div>
                            <input type="text" name="center_name" x-model="centerName"
                                @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                class="w-full h-11 ps-10 pe-4 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold font-arabic focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all shadow-inner"
                                :placeholder="accountType === 'center' ? '{{ __('auth.register.center_name_placeholder') }}' : '{{ __('auth.registration_steps.teacher_name_placeholder') }}'" required autofocus>
                        </div>
                    </div>

                    {{-- Subdomain Field --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 font-arabic">{{ __('auth.registration_steps.platform_link') }}</label>
                        <div class="relative flex items-center w-full group" dir="ltr">
                            <div class="absolute left-0 inset-y-0 hidden sm:flex items-center px-3 pointer-events-none text-brand-primary font-bold text-xs bg-brand-primary/5 border-r border-brand-primary/10 rounded-l-xl">https://</div>
                            <input type="text" name="subdomain" x-model="subdomain"
                                @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                @input.debounce.500ms="checkSubdomain()"
                                class="w-full h-11 pl-[60px] pr-[92px] sm:pl-[70px] sm:pr-[110px] bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold font-sans focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all shadow-inner"
                                :placeholder="accountType === 'center' ? 'center-name' : '{{ __('auth.registration_steps.teacher_subdomain_placeholder') }}'" required>
                            <div class="absolute right-0 inset-y-0 flex items-center pr-3 pointer-events-none text-slate-400 font-bold text-xs gap-2">
                                <span>.taalimu.com</span>
                                <div class="flex items-center justify-center w-4 h-4">
                                    <template x-if="subdomainStatus === 'loading'"><div class="w-3.5 h-3.5 border-2 border-brand-primary border-t-transparent rounded-full animate-spin"></div></template>
                                    <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i></template>
                                    <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-sm"></i></template>
                                </div>
                            </div>
                        </div>
                        <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                           class="text-[11px] font-bold px-1 mt-0.5" x-text="subdomainMessage"></p>
                    </div>

                    {{-- Phone Field --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 font-arabic">
                            {{ __('auth.register.phone') }} 
                            <span class="text-[11px] font-normal text-slate-400">({{ __('auth.register.optional') }})</span>
                        </label>
                        <div class="relative flex gap-2">
                            {{-- Country Code Selector --}}
                            <div class="relative" dir="ltr">
                                <select x-model="countryCode" name="country_code"
                                    class="h-11 pl-2 pr-7 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all appearance-none cursor-pointer">
                                    @include('partials.country-codes')
                                </select>
                                <div class="absolute inset-y-0 right-1.5 flex items-center pointer-events-none">
                                    <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                                </div>
                            </div>
                            {{-- Phone Input --}}
                            <div class="relative flex-1 group">
                                <input type="text" name="phone" x-model="phoneNumber"
                                    class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-primary/10 focus:border-brand-primary transition-all shadow-inner"
                                    placeholder="10xxxxxxx" dir="ltr">
                            </div>
                        </div>
                    </div>

                    <!-- Coupon (Compact inline) -->
                    <div class="pt-1">
                        <button type="button" x-show="!showCouponInput && couponStatus !== 'valid'" @click="showCouponInput = true" 
                                class="text-xs font-bold text-brand-primary hover:underline flex items-center gap-1.5 font-arabic cursor-pointer">
                            <i class="bi bi-tag-fill"></i> {{ __('auth.register.have_coupon') }}
                        </button>
                        <div x-show="showCouponInput || couponStatus === 'valid'" x-cloak class="space-y-1.5">
                            <div class="relative flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" name="coupon_code" x-model="couponCode" @keyup.enter="validateCoupon()"
                                        placeholder="{{ __('admin.coupon_code') }}"
                                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold uppercase focus:outline-none focus:border-brand-primary transition-all"
                                        :class="couponStatus === 'valid' ? 'border-emerald-200 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-200 bg-red-50' : '')">
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <template x-if="couponStatus === 'valid'"><i class="bi bi-patch-check-fill text-emerald-500 text-base"></i></template>
                                        <template x-if="couponStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-base"></i></template>
                                    </div>
                                </div>
                                <button type="button" @click="validateCoupon()" :disabled="isApplyingCoupon || !couponCode"
                                        class="h-11 px-5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-brand-primary transition-all disabled:opacity-50 flex items-center justify-center min-w-[75px] cursor-pointer">
                                    <template x-if="isApplyingCoupon"><div class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></div></template>
                                    <span x-show="!isApplyingCoupon">{{ __('auth.google_registration.apply') }}</span>
                                </button>
                            </div>
                            <p x-show="couponMessage" :class="couponStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                               class="text-[11px] font-bold px-1" x-text="couponMessage"></p>
                        </div>
                    </div>

                    <!-- Payment Gateway Selection -->
                    <div class="space-y-2 pt-1" x-show="currentPlan.trial_days === 0">
                        <label class="block text-xs font-bold text-slate-700 font-arabic">
                            {{ __('auth.register.payment_method') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Paymob -->
                            <label class="relative cursor-pointer group" x-show="userCountry === 'EG' || userCountry === 'default'">
                                <input type="radio" name="payment_gateway" value="paymob" x-model="paymentGateway" class="peer sr-only">
                                <div class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50/50 peer-checked:border-brand-primary peer-checked:bg-brand-primary/5 transition-all shadow-sm">
                                    <i class="bi bi-credit-card-2-back text-lg text-slate-400 peer-checked:text-brand-primary"></i>
                                    <span class="text-xs font-bold text-slate-700 peer-checked:text-brand-primary">Paymob</span>
                                </div>
                            </label>
                            <!-- PayPal -->
                            <label class="relative cursor-pointer group" x-show="userCountry !== 'EG' && userCountry !== 'default'">
                                <input type="radio" name="payment_gateway" value="paypal" x-model="paymentGateway" class="peer sr-only">
                                <div class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50/50 peer-checked:border-brand-primary peer-checked:bg-brand-primary/5 transition-all shadow-sm">
                                    <i class="bi bi-paypal text-lg text-slate-400 peer-checked:text-brand-primary"></i>
                                    <span class="text-xs font-bold text-slate-700 peer-checked:text-brand-primary">PayPal</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-3">
                        <button type="submit" 
                                :disabled="isSubmitting || subdomainStatus === 'invalid' || (phoneNumber.length > 0 && phoneNumber.length < 8)"
                                class="w-full h-12 rounded-xl flex items-center justify-center gap-2.5 group bg-brand-primary hover:bg-brand-600 text-white shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/30 hover:-translate-y-0.5 active:scale-[0.99] transition-all disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                            <template x-if="isSubmitting">
                                <span class="inline-flex items-center gap-2 text-sm font-bold font-arabic">
                                    <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    <span>{{ __('auth.google_registration.completing') }}</span>
                                </span>
                            </template>
                            <template x-if="!isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <span class="text-base font-black font-arabic" 
                                          x-text="currentPlan.trial_days > 0 ? ({{ Js::from(__('auth.google_registration.start_free_trial')) }}) : (finalPrice === 0 ? '{{ __('auth.register.cta_main') }}' : '{{ __('auth.google_registration.pay_complete') }}')">
                                    </span>
                                    <i class="bi bi-arrow-left text-lg rtl:inline ltr:hidden group-hover:-translate-x-1 transition-transform"></i>
                                    <i class="bi bi-arrow-right text-lg ltr:inline rtl:hidden group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </template>
                        </button>
                    </div>

                    {{-- Terms --}}
                    <div class="mt-4 text-center">
                        <p class="text-xs text-slate-400 font-arabic leading-relaxed">
                            {{ __('auth.register.terms_prefix') }}
                            <a href="{{ route('terms') }}" class="text-slate-700 font-bold hover:underline underline-offset-4">{{ __('auth.register.terms_of_service') }}</a> 
                            {{ __('auth.register.and') }} 
                            <a href="{{ route('privacy') }}" class="text-slate-700 font-bold hover:underline underline-offset-4">{{ __('auth.register.privacy_policy') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Plan Selection Modal -->
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
             class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-scale-in">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex justify-between items-center mb-4 gap-4">
                    <h3 class="text-lg font-black text-slate-900 font-arabic">{{ __('auth.plan_modal.select_plan') }}</h3>
                    
                    <!-- Billing Country Selector -->
                    <div class="flex items-center gap-2 bg-white px-2.5 py-1 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-[11px] font-bold text-slate-500 whitespace-nowrap"><i class="bi bi-globe-americas me-1 text-brand-primary"></i> {{ __('auth.plan_modal.billing_region') }}</span>
                        <div class="relative" dir="ltr">
                            <select x-model="selectedCurrency" class="h-7 pl-2 pr-6 bg-transparent border-0 text-xs font-bold text-slate-700 focus:outline-none appearance-none cursor-pointer">
                                <option value="EGP">🇪🇬 Egypt (EGP)</option>
                                <option value="SAR">🇸🇦 Saudi Arabia (SAR)</option>
                                <option value="AED">🇦🇪 UAE (AED)</option>
                                <option value="EUR">🇪🇺 Europe (EUR)</option>
                                <option value="USD">🇺🇸 Global (USD)</option>
                            </select>
                            <div class="absolute inset-y-0 right-1 flex items-center pointer-events-none">
                                <i class="bi bi-chevron-down text-[10px] text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" @click="showPlanModal = false" aria-label="{{ __('auth.plan_modal.close') }}" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-200 transition-colors cursor-pointer text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <!-- Billing Toggle in Modal -->
                <div class="flex justify-center">
                    <div class="inline-flex flex-wrap justify-center bg-slate-200/60 p-1 rounded-xl border border-slate-200/80 gap-1" dir="ltr">
                        <button type="button" @click="billingCycle = 'monthly'" 
                                class="px-4 py-1.5 min-h-[36px] rounded-lg text-xs font-bold transition-all cursor-pointer font-arabic"
                                :class="billingCycle === 'monthly' ? 'bg-white text-brand-primary shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                            {{ __('auth.billing.monthly') }}
                        </button>
                        <button type="button" @click="billingCycle = 'term'" 
                                class="px-4 py-1.5 min-h-[36px] rounded-lg text-xs font-bold transition-all cursor-pointer font-arabic"
                                :class="billingCycle === 'term' ? 'bg-white text-brand-primary shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                            {{ __('auth.billing.term') }}
                        </button>
                        <button type="button" @click="billingCycle = 'yearly'" 
                                class="px-4 py-1.5 min-h-[36px] rounded-lg text-xs font-bold transition-all cursor-pointer font-arabic"
                                :class="billingCycle === 'yearly' ? 'bg-white text-brand-primary shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                            {{ __('auth.billing.yearly') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-5 space-y-3 max-h-[55vh] overflow-y-auto custom-scrollbar">
                <template x-for="pkg in packages" :key="pkg.slug">
                    <label class="relative block cursor-pointer group">
                        <input type="radio" name="plan_selector" :value="pkg.slug" x-model="selectedPlan" @change="showPlanModal = false" class="peer sr-only">
                        <div class="p-4 sm:p-5 rounded-2xl border-2 border-slate-100 bg-white hover:border-brand-primary/30 peer-checked:border-brand-primary peer-checked:bg-brand-primary/5 transition-all">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all bg-white"
                                         :class="selectedPlan === pkg.slug ? 'border-brand-primary' : 'border-slate-200'">
                                        <div class="w-2.5 h-2.5 rounded-full bg-brand-primary transition-transform"
                                             :class="selectedPlan === pkg.slug ? 'scale-100' : 'scale-0'"></div>
                                    </div>
                                    <span class="font-bold text-slate-900 text-sm font-arabic" x-text="pkg.name"></span>
                                </div>
                                <div class="text-end">
                                    <span class="text-sm font-black text-brand-primary" x-text="(billingCycle === 'yearly' ? getPriceData(pkg).yearly : (billingCycle === 'term' ? getPriceData(pkg).term : getPriceData(pkg).amount)).toLocaleString() + ' ' + getPriceData(pkg).currency"></span>
                                </div>
                            </div>
                        </div>
                    </label>
                </template>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                <button type="button" @click="showPlanModal = false" class="text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors font-arabic cursor-pointer">
                    {{ __('auth.plan_modal.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
