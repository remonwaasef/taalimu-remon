@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    /* Premium Identity Missing Classes (since Tailwind JIT isn't running) */
    .bg-emerald-600 { background-color: #059669 !important; }
    .shadow-emerald-600\/20 { box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.2), 0 4px 6px -4px rgba(5, 150, 105, 0.1) !important; }
    .from-emerald-600 { --tw-gradient-from: #059669 !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(5, 150, 105, 0)) !important; }
    .to-teal-500 { --tw-gradient-to: #14b8a6 !important; }
    .hover\:from-emerald-700:hover { --tw-gradient-from: #047857 !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(4, 120, 87, 0)) !important; }
    .hover\:to-teal-600:hover { --tw-gradient-to: #0d9488 !important; }
</style>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: config.billingCycle || 'monthly',
        packages: config.packages,
        centerName: config.centerName,
        subdomain: config.subdomain,
        manuallyEditedSubdomain: config.manuallyEditedSubdomain,
        name: '{{ old('name', request('name')) }}',
        email: '{{ old('email', request('email')) }}',
        phone: '{{ old('phone', request('phone')) }}',
        currentStep: {{ $errors->hasAny(['name', 'email', 'phone', 'password']) ? 2 : 1 }},
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
        selectedCurrency: config.selectedCurrency || 'EGP',
        accountType: config.accountType || null,
        showPlanModal: false,
        formSubmitted: false,
        phoneVerified: false,
        countryCode: '{{ old("country_code", app()->getLocale() === "fr" ? "33" : "20") }}',
        otpSent: false,
        otpCode: '',
        otpStatus: 'idle',
        otpMessage: '',
        otpCountdown: 0,
        otpTimer: null,
        isSendingOtp: false,
        isVerifyingOtp: false,

        async init() {
            // Default select first plan if requested plan is invalid or missing
            if (!this.packages.find || !this.packages.find(p => p.slug === this.selectedPlan)) {
                this.selectedPlan = (this.packages && this.packages.length > 0) ? this.packages[0].slug : '';
            }

            try {
                const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                const data = await response.json();
                this.userCountry = data.country || '';
                
                // If currency wasn't passed in URL, try to guess from country
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.has('currency')) {
                    if (this.userCountry === 'EG') this.selectedCurrency = 'EGP';
                    else if (['FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'PT', 'IE'].includes(this.userCountry)) this.selectedCurrency = 'EUR';
                    else this.selectedCurrency = 'USD';
                }
            } catch(e) { console.log('IP fetch failed', e); }
        },

        get currentPlan() {
            const plan = this.packages.find(p => p.slug === this.selectedPlan);
            if (!plan) return {name: 'Plan not found', price: '0', price_raw: 0, currency: '$', yearly_price_raw: 0};
            return plan;
        },

        getPriceData(pkg) {
             if(!pkg) return { amount: 0, currency: '$', term: 0, yearly: 0, old: 0, discount_label: '' };
             
             let prices = pkg.regional_prices || {};
             // Default from PHP data
             let data = {
                 amount: parseFloat(pkg.price_raw),
                 term: parseFloat(pkg.term_price_raw || (pkg.price_raw * 4)),
                 yearly: parseFloat(pkg.yearly_price_raw),
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

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
                 data.term = parseFloat(r.term_price || (data.amount * 4));
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
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
            const plan = this.currentPlan;
            const priceData = this.getPriceData(plan);
            if (this.billingCycle === 'yearly') return (priceData.yearly || 0);
            if (this.billingCycle === 'term') return (priceData.term || 0);
            return (priceData.amount || 0);
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

        subdomainStatus: 'idle', // idle, loading, valid, invalid
        subdomainMessage: '',
        
        async checkSubdomain() {
            if (!this.subdomain) {
                this.subdomainStatus = 'idle';
                this.subdomainMessage = '';
                return;
            }
            
            // Auto clean
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

        async sendPhoneOtp() {
            if (!this.phone || this.phone.length < 10) {
                this.otpMessage = {{ Js::from(app()->isLocale('ar') ? 'يرجى إدخال رقم هاتف صحيح' : 'Please enter a valid phone number') }};
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
                    body: JSON.stringify({ phone: this.phone, country_code: this.countryCode })
                });
                const data = await response.json();
                if (data.success) {
                    this.otpSent = true;
                    this.otpStatus = 'sent';
                    this.otpMessage = data.message;
                    this.startOtpCountdown(120);
                } else {
                    this.otpStatus = 'error';
                    this.otpMessage = data.message;
                }
            } catch (e) {
                this.otpStatus = 'error';
                this.otpMessage = {{ Js::from(app()->isLocale('ar') ? 'حدث خطأ. حاول مرة أخرى.' : 'An error occurred. Please try again.') }};
            } finally {
                this.isSendingOtp = false;
            }
        },

        async verifyPhoneOtp() {
            if (!this.otpCode || this.otpCode.length !== 6) return;
            this.isVerifyingOtp = true;
            this.otpStatus = 'verifying';
            try {
                const response = await fetch('/api/phone/verify-otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ phone: this.phone, otp: this.otpCode })
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
                this.otpMessage = {{ Js::from(app()->isLocale('ar') ? 'حدث خطأ. حاول مرة أخرى.' : 'An error occurred. Please try again.') }};
            } finally {
                this.isVerifyingOtp = false;
            }
        },

        startOtpCountdown(seconds) {
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

        get otpFormattedCountdown() {
            const m = Math.floor(this.otpCountdown / 60);
            const s = this.otpCountdown % 60;
            return `${m}:${s.toString().padStart(2, '0')}`;
        },

        nextStep() {
            // New logic: Step 1 is Center Details
            if (this.currentStep === 1) {
                if (!this.centerName || !this.subdomain) {
                    alert('{{ app()->getLocale() == 'ar' ? 'يرجى إدخال اسم المركز والرابط' : 'Please enter center name and subdomain' }}');
                    return;
                }
                if (this.subdomainStatus === 'invalid') {
                    alert('{{ app()->getLocale() == 'ar' ? 'هذا الرابط مستخدم بالفعل' : 'This subdomain is already taken' }}');
                    return;
                }
                this.currentStep = 2;
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        prevStep() {
            this.currentStep = 1;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        showPlanModal: false,

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

<div class="min-h-[85vh] bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
     x-data="registrationForm({
        selectedPlan: {{ Js::from(old('plan', request('plan', $packages->firstWhere('is_default', true)?->slug ?? $packages->first()?->slug ?? ''))) }},
        billingCycle: {{ Js::from(old('billing_cycle', request('cycle', 'monthly'))) }},
        packages: {{ Js::from($packagesData) }},
        centerName: {{ Js::from(old('center_name')) }},
        subdomain: {{ Js::from(old('subdomain')) }},
        manuallyEditedSubdomain: {{ old('subdomain') ? 'true' : 'false' }},
        accountType: {{ Js::from(old('account_type', $accountType)) }},
        selectedCurrency: {{ Js::from(old('currency', request('currency', session('suggested_currency', app()->getLocale() === 'fr' ? 'EUR' : 'EGP')))) }},
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

            <!-- Account Type Selection (Premium Position) -->
            <div x-show="currentStep === 1" x-cloak class="mb-4 transition-all duration-700 ease-in-out relative z-10" :class="!accountType ? 'transform scale-110 translate-y-[3vh] pb-12 mt-4' : ''">
                <label class="text-[11px] font-black text-slate-500 px-1 font-arabic uppercase tracking-wider block mb-3 text-center opacity-70 transition-all duration-700" :class="!accountType ? 'text-base text-slate-800 opacity-100 font-black mb-6' : ''">
                    {{ app()->isLocale('ar') ? 'ابدأ كـ ...' : 'Start as ...' }}
                </label>
                <div class="grid grid-cols-2 gap-4 px-2">
                    <!-- Instructor Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" value="instructor" x-model="accountType" class="peer sr-only">
                        <div class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="[
                                !accountType ? 'p-6 border-slate-100 hover:border-brand-secondary/30' : 'p-4',
                                accountType === 'instructor' ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' : (accountType ? 'border-slate-100 opacity-60' : '')
                             ]">
                            <!-- Trial Badge -->
                            <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-bl-xl rounded-tr-2xl z-20 shadow-lg shadow-emerald-600/20">
                                <i class="bi bi-lightning-fill me-1 text-[8px]"></i>
                                <span x-text="currentPlan.trial_days > 0 ? currentPlan.trial_days : '30'"></span> {{ app()->isLocale('ar') ? 'يوم مجاناً' : 'Days Free' }}
                            </div>

                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'instructor' || !accountType ? 'bg-brand-secondary/5' : 'bg-slate-100'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-xl flex items-center justify-center transition-all duration-500 group-hover:rotate-6 shadow-lg"
                                 :class="{
                                    'bg-brand-secondary text-white shadow-brand-secondary/40 w-10 h-10 mb-2 scale-110': accountType === 'instructor',
                                    'bg-slate-100 text-slate-400 w-12 h-12 mb-3': !accountType,
                                    'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary shadow-slate-200/50 w-10 h-10 mb-2': accountType && accountType !== 'instructor'
                                 }">
                                <i class="fas fa-chalkboard-teacher transition-all duration-500" :class="!accountType ? 'text-2xl' : 'text-xl'"></i>
                            </div>
                            <span class="font-black transition-all" 
                                  :class="[
                                     !accountType ? 'text-base text-slate-800' : 'text-xs',
                                     accountType === 'instructor' ? 'text-brand-secondary' : 'text-slate-500'
                                  ]">{{ app()->isLocale('ar') ? 'مدرس مستقل' : 'Independent Tutor' }}</span>
                            
                            <!-- Success Dot -->
                            <div class="absolute top-4 right-4 opacity-0 scale-0 transition-all duration-300"
                                 :class="accountType === 'instructor' ? 'opacity-100 scale-100' : ''">
                                <div class="w-3 h-3 bg-brand-secondary rounded-full ring-4 ring-brand-secondary/20"></div>
                            </div>
                        </div>
                    </label>

                    <!-- Center Option (Premium) -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" value="center" x-model="accountType" class="peer sr-only">
                        <div class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="[
                                !accountType ? 'p-6 border-slate-100 hover:border-brand-secondary/30' : 'p-4',
                                accountType === 'center' ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' : (accountType ? 'border-slate-100 opacity-60' : '')
                             ]">
                            <!-- Trial Badge -->
                            <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-bl-xl rounded-tr-2xl z-20 shadow-lg shadow-emerald-600/20">
                                <i class="bi bi-lightning-fill me-1 text-[8px]"></i>
                                <span x-text="currentPlan.trial_days > 0 ? currentPlan.trial_days : '30'"></span> {{ app()->isLocale('ar') ? 'يوم مجاناً' : 'Days Free' }}
                            </div>

                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'center' || !accountType ? 'bg-brand-secondary/5' : 'bg-slate-100'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-xl flex items-center justify-center transition-all duration-500 group-hover:-rotate-6 shadow-lg"
                                 :class="{
                                    'bg-brand-secondary text-white shadow-brand-secondary/40 w-10 h-10 mb-2 scale-110': accountType === 'center',
                                    'bg-slate-100 text-slate-400 w-12 h-12 mb-3': !accountType,
                                    'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary shadow-slate-200/50 w-10 h-10 mb-2': accountType && accountType !== 'center'
                                 }">
                                <i class="fas fa-university transition-all duration-500" :class="!accountType ? 'text-2xl' : 'text-xl'"></i>
                            </div>
                            <span class="font-black transition-all" 
                                  :class="[
                                     !accountType ? 'text-base text-slate-800' : 'text-xs',
                                     accountType === 'center' ? 'text-brand-secondary' : 'text-slate-500'
                                  ]">{{ app()->isLocale('ar') ? 'مركز تعليمي' : 'Educational Center' }}</span>
                            
                            <!-- Success Dot -->
                            <div class="absolute top-4 right-4 opacity-0 scale-0 transition-all duration-300"
                                 :class="accountType === 'center' ? 'opacity-100 scale-100' : ''">
                                <div class="w-3 h-3 bg-brand-secondary rounded-full ring-4 ring-brand-secondary/20"></div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

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

                <!-- STEP 1: Center Details -->
                <div x-show="currentStep === 1" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            <span x-text="accountType === 'center' ? '{{ __('auth.register.center_name') }}' : ({{ Js::from(app()->isLocale('ar') ? 'اسم المدرس / المنصة' : 'Teacher / Platform Name') }})"></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-building"></i></div>
                            <input type="text" name="center_name" x-model="centerName"
                                @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                class="w-full h-10 ps-12 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-arabic focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="{{ __('auth.register.center_name_placeholder') }}" :required="currentStep === 1">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ app()->isLocale('ar') ? 'رابط المنصة' : 'Platform Link' }}</label>
                        <div class="relative flex items-center w-full group" dir="ltr">
                            <div class="absolute left-0 inset-y-0 flex items-center px-4 pointer-events-none text-brand-secondary font-black text-xs bg-brand-secondary/5 border-r border-brand-secondary/10 rounded-l-2xl">https://</div>
                            <input type="text" name="subdomain" x-model="subdomain"
                                @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                @input.debounce.500ms="checkSubdomain()"
                                class="w-full h-10 pl-[80px] pr-[115px] bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-sans focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="center-name" :required="currentStep === 1">
                            <div class="absolute right-0 inset-y-0 flex items-center pr-4 pointer-events-none text-slate-400 font-bold text-xs gap-3">
                                <span>.taalimu.com</span>
                                <div class="flex items-center justify-center w-5 h-5">
                                    <template x-if="subdomainStatus === 'loading'"><div class="w-4 h-4 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                    <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i></template>
                                    <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-lg"></i></template>
                                </div>
                            </div>
                        </div>
                        <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                           class="text-[11px] font-bold px-2 mt-1 animate-fade-in" x-text="subdomainMessage"></p>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="nextStep()"
                            class="w-full h-11 rounded-full flex items-center justify-center gap-3 group bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-1 active:scale-95 transition-all">
                            <span class="text-base font-black font-arabic">{{ app()->isLocale('ar') ? 'استمرار' : 'Continue' }}</span>
                            <i class="bi bi-arrow-right-short text-xl group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Personal Details & Summary -->
                <div x-show="currentStep === 2" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-8 items-start">
                        <!-- Left Column: Form -->
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.full_name') }}</label>
                                <input type="text" name="name" x-model="name" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" :required="currentStep === 2">
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.email') }}</label>
                                    <input type="email" name="email" x-model="email" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="mail@example.com" :required="currentStep === 2">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.phone') }}</label>
                                    
                                    {{-- Country Code + Phone Input + Send OTP Button --}}
                                    <div class="relative flex gap-2">
                                        {{-- Country Code Selector --}}
                                        <div class="relative" dir="ltr">
                                            <select x-model="countryCode" name="country_code"
                                                :disabled="phoneVerified"
                                                class="h-11 pl-2 pr-7 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-sm font-black text-slate-700 focus:outline-none focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all appearance-none cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                                                :class="phoneVerified ? 'border-emerald-300 bg-emerald-50/30' : 'border-slate-100'">
                                                @include('partials.country-codes')
                                            </select>
                                            <div class="absolute inset-y-0 right-1 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-[9px] text-slate-400"></i>
                                            </div>
                                        </div>
                                        {{-- Phone Input --}}
                                        <div class="relative flex-1 group">
                                            <input type="text" name="phone" x-model="phone" 
                                                :disabled="phoneVerified"
                                                class="w-full h-11 px-4 bg-slate-50/50 border-2 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner disabled:opacity-60 disabled:cursor-not-allowed"
                                                :class="phoneVerified ? 'border-emerald-300 bg-emerald-50/30' : 'border-slate-100'"
                                                placeholder="10xxxxxxx" :required="currentStep === 2" dir="ltr">
                                            {{-- Verified Badge --}}
                                            <div x-show="phoneVerified" class="absolute inset-y-0 end-0 pe-3 flex items-center">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    {{ app()->isLocale('ar') ? 'تم التحقق' : 'Verified' }}
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Send OTP Button --}}
                                        <button type="button" @click="sendPhoneOtp()" 
                                                x-show="!phoneVerified"
                                                :disabled="isSendingOtp || otpCountdown > 0 || !phone || phone.length < 7"
                                                class="h-11 px-4 rounded-2xl font-black text-[11px] font-arabic transition-all whitespace-nowrap flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                                :class="otpSent ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600'">
                                            <template x-if="isSendingOtp">
                                                <div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                            </template>
                                            <template x-if="!isSendingOtp && otpCountdown > 0">
                                                <span x-text="otpFormattedCountdown"></span>
                                            </template>
                                            <template x-if="!isSendingOtp && otpCountdown <= 0">
                                                <span>{{ app()->isLocale('ar') ? 'إرسال كود' : 'Send OTP' }}</span>
                                            </template>
                                        </button>
                                    </div>

                                    {{-- OTP Input (appears after sending) --}}
                                    <div x-show="otpSent && !phoneVerified" x-cloak 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="space-y-2">
                                        <div class="relative flex gap-2">
                                            <div class="relative flex-1">
                                                <input type="text" x-model="otpCode" maxlength="6" inputmode="numeric" pattern="[0-9]*"
                                                    @input="otpCode = otpCode.replace(/[^0-9]/g, ''); if(otpCode.length === 6) verifyPhoneOtp()"
                                                    class="w-full h-11 px-4 bg-amber-50/50 border-2 border-amber-200 rounded-2xl text-center text-xl font-black tracking-[0.5em] focus:outline-none focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all"
                                                    placeholder="● ● ● ● ● ●">
                                            </div>
                                            <button type="button" @click="verifyPhoneOtp()" 
                                                    :disabled="isVerifyingOtp || otpCode.length !== 6"
                                                    class="h-11 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-500 text-white font-black text-[11px] font-arabic shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5">
                                                <template x-if="isVerifyingOtp">
                                                    <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                                </template>
                                                <template x-if="!isVerifyingOtp">
                                                    <span>{{ app()->isLocale('ar') ? 'تحقق' : 'Verify' }}</span>
                                                </template>
                                            </button>
                                        </div>
                                        <p class="text-[10px] font-bold font-arabic text-amber-600 flex items-center gap-1 px-1">
                                            <i class="bi bi-whatsapp text-emerald-500"></i>
                                            {{ app()->isLocale('ar') ? 'تم إرسال كود التحقق عبر واتساب' : 'Verification code sent via WhatsApp' }}
                                        </p>
                                    </div>

                                    {{-- OTP Status Message --}}
                                    <p x-show="otpMessage && otpStatus !== 'sent'" x-cloak
                                       :class="{
                                           'text-emerald-600': otpStatus === 'verified',
                                           'text-red-500': otpStatus === 'error',
                                           'text-amber-600': otpStatus === 'sending' || otpStatus === 'verifying'
                                       }"
                                       class="text-[11px] font-bold px-1 font-arabic animate-fade-in" x-text="otpMessage"></p>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex justify-between items-center px-1">
                                    <label class="text-[12px] font-black text-slate-400 font-arabic uppercase tracking-wide">{{ __('auth.register.password') }}</label>
                                    <button type="button" @click="showPassword = !showPassword" class="text-[10px] font-black text-brand-secondary uppercase tracking-widest hover:opacity-70 transition-opacity">
                                        <span x-text="showPassword ? '{{ __('auth.register.hide') }}' : '{{ __('auth.register.show') }}'"></span>
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="••••••••" :required="currentStep === 2">
                                    
                                    <!-- Password Live Criteria Indicators -->
                                    <div x-show="password.length > 0" x-collapse x-cloak class="px-1 py-1">
                                        <div class="flex flex-wrap gap-x-3 gap-y-1.5">
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.length ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.length ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? '٨ أحرف على الأقل' : '8+ Characters' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.upper ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.upper ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'حرف كبير' : 'Uppercase' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.lower ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.lower ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'حرف صغير' : 'Lowercase' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.number ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.number ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'رقم' : 'Number' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.symbol ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.symbol ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'رمز (!@#$)' : 'Symbol (!@#$)' }}
                                            </div>
                                        </div>
                                    </div>

                                    <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" :class="password_confirmation.length > 0 && !isPasswordMatch ? 'border-red-300 bg-red-50' : ''" placeholder="{{ __('auth.register.confirm_password') }}" :required="currentStep === 2">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Summary & Payment -->
                        <div class="space-y-4">
                            <!-- Simplified Price Summary Card -->
                            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 relative overflow-hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __('auth.register.selected_plan') }}</span>
                                            <button type="button" @click="showPlanModal = true" class="text-[9px] font-black text-brand-secondary underline underline-offset-2 hover:opacity-70 transition-opacity uppercase tracking-widest">
                                                {{ app()->getLocale() == 'ar' ? 'تغيير' : 'Change' }}
                                            </button>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 font-arabic">
                                            <span x-text="currentPlan.name"></span>
                                            <span class="text-xs font-bold text-slate-400 ms-1" x-text="'(' + (billingCycle === 'yearly' ? (currentPriceData.yearly || 0).toLocaleString() : (billingCycle === 'term' ? (currentPriceData.term || 0).toLocaleString() : (currentPriceData.amount || 0).toLocaleString())) + ' ' + currentPriceData.currency + ')'"></span>
                                        </h3>
                                    </div>
                                    <div class="text-right">
                                        <template x-if="currentPlan.trial_days > 0">
                                            <div class="text-[11px] font-black text-emerald-600 mb-1 animate-fade-in uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100 inline-block">
                                                <i class="bi bi-gift-fill me-1"></i>
                                                <span x-text="currentPlan.trial_days"></span> {{ app()->isLocale('ar') ? 'يوم تجربة مجانية' : 'Days Free Trial' }}
                                            </div>
                                        </template>
                                        <template x-if="couponStatus === 'valid' && currentPlan.trial_days === 0">
                                            <div class="text-[10px] font-black text-emerald-600 mb-1 animate-fade-in">-<span x-text="couponDiscountAmount.toLocaleString()"></span> <span x-text="currentPriceData.currency"></span></div>
                                        </template>
                                        <div class="flex items-baseline gap-1 justify-end" :class="currentPlan.trial_days > 0 ? 'text-emerald-500' : 'text-brand-secondary'">
                                            <span class="text-2xl font-black tracking-tighter" x-text="currentPlan.trial_days > 0 ? '0' : finalPrice.toLocaleString()"></span>
                                            <span class="text-xs font-bold opacity-60" x-text="currentPlan.trial_days > 0 ? ({{ app()->isLocale('ar') ? '\'مجاناً\'' : '\'FREE\'' }}) : currentPriceData.currency"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mini Features List -->
                                <div x-data="{ openFeatures: false }" class="py-3 border-y border-slate-100/50 mb-3">
                                    <button type="button" @click="openFeatures = !openFeatures" class="w-full flex items-center justify-center gap-2 text-[12px] font-black text-slate-700 font-arabic hover:text-brand-secondary transition-colors pb-2 cursor-pointer">
                                        <span>{{ app()->isLocale('ar') ? 'عرض المميزات' : 'View Features' }}</span>
                                        <i class="bi bi-chevron-down transition-transform duration-300 transform" :class="openFeatures ? 'rotate-180' : ''"></i>
                                    </button>
                                    <div x-show="openFeatures" x-transition.opacity.duration.300ms class="space-y-2 pt-2 border-t border-slate-50">
                                        <template x-for="feature in (currentPlan.features || [])" :key="feature">
                                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-600 font-arabic">
                                                <i class="bi bi-check2 text-emerald-500"></i>
                                                <span x-text="feature"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Billing Cycle Switcher (More compact) -->
                                <div class="flex p-1 bg-slate-200/50 rounded-xl mb-3 items-center">
                                    <button type="button" @click="billingCycle = 'monthly'" 
                                            class="flex-1 py-1.5 text-[10px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ app()->getLocale() == 'ar' ? 'شهري' : 'Monthly' }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'term'" 
                                            class="flex-1 py-1.5 text-[10px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ app()->getLocale() == 'ar' ? 'ترم' : 'Term' }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'yearly'" 
                                            class="flex-1 py-1.5 text-[10px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ app()->getLocale() == 'ar' ? 'سنوي' : 'Yearly' }}
                                    </button>
                                </div>

                                <!-- Coupon (Compact inline) -->
                                <div class="pt-0 border-t border-slate-100 mb-3 pt-2">
                                    <button type="button" x-show="!showCouponInput && couponStatus !== 'valid'" @click="showCouponInput = true" 
                                            class="text-[10px] font-black text-brand-secondary hover:underline flex items-center gap-1 font-arabic">
                                        <i class="bi bi-tag-fill"></i> {{ __('auth.register.have_coupon') ?? 'هل لديك كود خصم؟' }}
                                    </button>
                                    <div x-show="showCouponInput || couponStatus === 'valid'" x-cloak class="space-y-1.5">
                                        <div class="relative flex gap-1.5">
                                            <div class="relative flex-1">
                                                <input type="text" name="coupon_code" x-model="couponCode" @keyup.enter="validateCoupon()"
                                                    placeholder="{{ __('admin.coupon_code') }}"
                                                    class="w-full h-9 px-3 bg-white border-2 border-slate-100 rounded-lg text-[10px] font-black uppercase focus:outline-none focus:border-brand-secondary transition-all"
                                                    :class="couponStatus === 'valid' ? 'border-emerald-200 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-200 bg-red-50' : '')">
                                                <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                                    <template x-if="couponStatus === 'valid'"><i class="bi bi-patch-check-fill text-emerald-500 text-xs"></i></template>
                                                    <template x-if="couponStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-xs"></i></template>
                                                </div>
                                            </div>
                                            <button type="button" @click="validateCoupon()" :disabled="isApplyingCoupon || !couponCode"
                                                    class="h-9 px-3 rounded-lg bg-slate-900 text-white font-black text-[9px] uppercase tracking-widest hover:bg-brand-secondary transition-all disabled:opacity-50 flex items-center justify-center min-w-[60px]">
                                                <template x-if="isApplyingCoupon"><div class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></div></template>
                                                <span x-show="!isApplyingCoupon">{{ app()->isLocale('ar') ? 'تطبيق' : 'Apply' }}</span>
                                            </button>
                                        </div>
                                        <p x-show="couponMessage" :class="couponStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                                           class="text-[9px] font-black px-1 animate-fade-in" x-text="couponMessage"></p>
                                    </div>
                                </div>

                                <!-- Trust Info (Compact) -->
                                <div class="flex items-center justify-between gap-2 opacity-60">
                                    <div class="flex items-center gap-1 text-slate-500 text-[9px] font-bold font-arabic">
                                        <i class="bi bi-shield-check text-emerald-500"></i>
                                        <span>{{ app()->getLocale() == 'ar' ? 'دفع آمن' : 'Secure' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-slate-500 text-[9px] font-bold font-arabic">
                                        <i class="bi bi-arrow-repeat text-brand-secondary"></i>
                                        <span>{{ app()->getLocale() == 'ar' ? 'إلغاء مرن' : 'Flexible' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Gateway Selection -->
                            <div class="space-y-2" x-show="currentPlan.trial_days === 0">
                                <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                                    {{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment' }}
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Paymob (EGP only) -->
                                    <label class="relative cursor-pointer group" x-show="selectedCurrency === 'EGP'">
                                        <input type="radio" name="payment_gateway" value="paymob" :checked="selectedCurrency === 'EGP'" class="peer sr-only">
                                        <div class="flex items-center gap-2 p-2 rounded-xl border-2 border-slate-100 bg-slate-50/30 peer-checked:border-brand-secondary peer-checked:bg-white transition-all">
                                            <i class="bi bi-credit-card-2-back text-sm text-slate-400 peer-checked:text-brand-secondary"></i>
                                            <span class="text-[10px] font-black text-slate-600 peer-checked:text-slate-900">Paymob</span>
                                        </div>
                                    </label>
                                    <!-- PayPal (USD/EUR only) -->
                                    <label class="relative cursor-pointer group" x-show="selectedCurrency !== 'EGP'">
                                        <input type="radio" name="payment_gateway" value="paypal" :checked="selectedCurrency !== 'EGP'" class="peer sr-only">
                                        <div class="flex items-center gap-2 p-2 rounded-xl border-2 border-slate-100 bg-slate-50/30 peer-checked:border-brand-secondary peer-checked:bg-white transition-all">
                                            <i class="bi bi-paypal text-sm text-slate-400 peer-checked:text-brand-secondary"></i>
                                            <span class="text-[10px] font-black text-slate-600 peer-checked:text-slate-900">PayPal</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="prevStep()" class="flex-1 h-12 rounded-full font-black text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border-2 border-slate-100">
                            {{ app()->isLocale('ar') ? 'رجوع' : 'Back' }}
                        </button>
                        <button type="submit" :disabled="(password.length > 0 && !isPasswordMatch) || !phoneVerified"
                                class="flex-[2] h-12 rounded-full font-black text-base text-white bg-gradient-to-r from-emerald-600 to-teal-500 shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-1 transition-all disabled:opacity-50 disabled:grayscale relative overflow-hidden group">
                            <!-- Button Shine Effect -->
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-white opacity-20 group-hover:animate-[shine_1s] group-hover:left-full transition-all duration-700 ease-in-out"></div>
                            <span class="relative z-10" x-text="currentPlan.trial_days > 0 ? ({{ Js::from(app()->isLocale('ar') ? 'ابدأ الفترة التجريبية' : 'Start Free Trial') }}) : (finalPrice === 0 ? '{{ __('auth.register.cta_main') }}' : '{{ app()->isLocale('ar') ? 'ادفع واستكمل التسجيل' : 'Pay & Complete' }}')"></span>
                        </button>
                    </div>

                    <!-- Conversion Boost: Guarantee & Support -->
                    <div class="mt-4 flex flex-col items-center gap-2">
                        <div class="flex items-center justify-center gap-2 text-slate-600 bg-emerald-50/50 px-4 py-2 rounded-xl border border-emerald-100/50 w-full text-center">
                            <i class="bi bi-shield-fill-check text-emerald-500 text-base"></i>
                            <p class="text-[11px] font-bold font-arabic">{{ app()->getLocale() == 'ar' ? 'ضمان استرجاع الأموال خلال 30 يوماً.' : '30-Day Money-Back Guarantee.' }}</p>
                        </div>
                    </div>
                </div>

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

        <!-- Footer Link -->
        <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-center transition-all duration-500" :class="!accountType ? 'blur-[5px] opacity-40 pointer-events-none select-none' : ''">
            <span class="text-xs text-slate-500 font-arabic font-bold">
                {{ __('auth.login.no_account_link') }}
                <a href="{{ route('login.portal') }}" class="text-brand-secondary font-black hover:underline ml-2">{{ __('auth.login.title') }}</a>
            </span>
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
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-slate-900 font-arabic">{{ app()->getLocale() == 'ar' ? 'اختر الباقة المناسبة' : 'Select Plan' }}</h3>
                        <button type="button" @click="showPlanModal = false" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-200 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <!-- Billing Toggle in Modal -->
                    <div class="flex justify-center">
                        <div class="inline-flex bg-slate-200/50 p-1.5 rounded-2xl border border-slate-200" dir="ltr">
                            <button type="button" @click="billingCycle = 'monthly'" 
                                    class="px-5 py-2 rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                    :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'شهري' : 'Month' }}
                            </button>
                            <button type="button" @click="billingCycle = 'term'" 
                                    class="px-5 py-2 rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                    :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'ترم' : 'Term' }}
                            </button>
                            <button type="button" @click="billingCycle = 'yearly'" 
                                    class="px-5 py-2 rounded-xl text-[13px] font-black transition-all font-sans uppercase tracking-wide"
                                    :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'سنوي' : 'Yearly' }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-8 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
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
                        {{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
