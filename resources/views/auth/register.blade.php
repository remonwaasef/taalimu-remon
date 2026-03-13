@extends('layouts.landing-new')

@section('content')
<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">



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
        currentStep: 1,
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
        accountType: null,

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

             if (this.userCountry && prices[this.userCountry]) {
                 let r = prices[this.userCountry];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.term = parseFloat(r.term_price || (data.amount * 4));
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

<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
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
    
    <!-- Main Centered Card Container (Simplified Single Column) -->
    <div class="w-full max-w-xl bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden border border-slate-100/50 animate-fade-in-up md:backdrop-blur-xl relative">
        
        <!-- Minimalist Progress & Header -->
        <div class="bg-white p-8 lg:p-12 pb-0">
            <!-- Multi-Step Indicator -->
            <div class="mb-10 relative transition-all duration-500" x-cloak :class="!accountType ? 'blur-[5px] opacity-40 pointer-events-none select-none' : ''">
                <div class="flex justify-between mb-3 px-1">
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] transition-colors" :class="currentStep === 1 ? 'text-brand-secondary' : 'text-slate-300'">
                        {{ app()->isLocale('ar') ? 'بيانات المركز' : 'Center Info' }}
                    </span>
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] transition-colors" :class="currentStep === 2 ? 'text-brand-secondary' : 'text-slate-300'">
                        {{ app()->isLocale('ar') ? 'البيانات الشخصية' : 'Personal Details' }}
                    </span>
                </div>
                <div class="h-1.5 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                    <div class="h-full bg-brand-secondary shadow-[0_0_15px_rgba(122,77,252,0.3)] transition-all duration-700 ease-out" 
                         :style="`width: ${currentStep === 1 ? '50%' : '100%'}`"></div>
                </div>
            </div>

            <!-- Contextual Header -->
            <div class="mb-8 text-center transition-all duration-700 ease-in-out" :class="!accountType ? 'transform scale-125 translate-y-[10vh] mb-20' : ''">
                <div x-show="currentStep === 1">
                    <h1 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4 font-arabic leading-tight">
                        {{ app()->isLocale('ar') ? 'ابدأ رحلتك التعليمية' : 'Start Your Journey' }}
                    </h1>
                    <p class="text-slate-500 text-base font-arabic font-medium opacity-80 max-w-[320px] mx-auto">
                        {{ app()->isLocale('ar') ? 'خطوات بسيطة لامتلاك منصتك التعليمية المتكاملة' : 'Simple steps to own your integrated platform' }}
                    </p>
                </div>
                <div x-show="currentStep === 2" x-cloak>
                    <h1 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4 font-arabic leading-tight">
                        {{ app()->isLocale('ar') ? 'تأكيد الهوية' : 'Confirm Identity' }}
                    </h1>
                    <p class="text-slate-500 text-base font-arabic font-medium opacity-80">
                        {{ app()->isLocale('ar') ? 'أدخل بياناتك الشخصية للبدء فوراً' : 'Enter personal details to get started' }}
                    </p>
                </div>
            </div>

            <!-- Account Type Selection (Premium Position) -->
            <div x-show="currentStep === 1" class="mb-12 transition-all duration-700 ease-in-out relative z-10" :class="!accountType ? 'transform scale-125 translate-y-[10vh] pb-32 mt-16 pt-8' : ''">
                <label class="text-[14px] font-black text-slate-500 px-1 font-arabic uppercase tracking-wider block mb-6 text-center opacity-70 transition-all duration-700" :class="!accountType ? 'text-xl text-slate-800 opacity-100 font-black mb-16' : ''">
                    {{ app()->isLocale('ar') ? 'ابدأ كـ ...' : 'Start as ...' }}
                </label>
                <div class="grid grid-cols-2 gap-6 px-2">
                    <!-- Instructor Option -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" value="instructor" x-model="accountType" class="peer sr-only">
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-[2rem] border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="[
                                !accountType ? 'p-8 border-slate-100 hover:border-brand-secondary/30' : 'p-6',
                                accountType === 'instructor' ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' : (accountType ? 'border-slate-100 opacity-60' : '')
                             ]">
                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'instructor' || !accountType ? 'bg-brand-secondary/5' : 'bg-slate-100'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:rotate-6 shadow-lg"
                                 :class="{
                                    'bg-brand-secondary text-white shadow-brand-secondary/40 w-16 h-16 mb-4 scale-110': accountType === 'instructor',
                                    'bg-slate-100 text-slate-400 w-[5rem] h-[5rem] mb-6': !accountType,
                                    'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary shadow-slate-200/50 w-16 h-16 mb-4': accountType && accountType !== 'instructor'
                                 }">
                                <i class="fas fa-chalkboard-teacher transition-all duration-500" :class="!accountType ? 'text-4xl' : 'text-3xl'"></i>
                            </div>
                            <span class="font-black transition-all" 
                                  :class="[
                                     !accountType ? 'text-lg text-slate-800' : 'text-sm',
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
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-[2rem] border-2 bg-white transition-all duration-500 hover:shadow-xl overflow-hidden group shadow-md"
                             :class="[
                                !accountType ? 'p-8 border-slate-100 hover:border-brand-secondary/30' : 'p-6',
                                accountType === 'center' ? 'border-brand-secondary ring-4 ring-brand-secondary/10 shadow-brand-secondary/10' : (accountType ? 'border-slate-100 opacity-60' : '')
                             ]">
                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"
                                 :class="accountType === 'center' || !accountType ? 'bg-brand-secondary/5' : 'bg-slate-100'"></div>
                            
                            <!-- Icon Container -->
                            <div class="rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:-rotate-6 shadow-lg"
                                 :class="{
                                    'bg-brand-secondary text-white shadow-brand-secondary/40 w-16 h-16 mb-4 scale-110': accountType === 'center',
                                    'bg-slate-100 text-slate-400 w-[5rem] h-[5rem] mb-6': !accountType,
                                    'bg-slate-100 text-slate-400 group-hover:bg-brand-secondary/10 group-hover:text-brand-secondary shadow-slate-200/50 w-16 h-16 mb-4': accountType && accountType !== 'center'
                                 }">
                                <i class="fas fa-university transition-all duration-500" :class="!accountType ? 'text-4xl' : 'text-3xl'"></i>
                            </div>
                            <span class="font-black transition-all" 
                                  :class="[
                                     !accountType ? 'text-lg text-slate-800' : 'text-sm',
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
            <div class="mb-12">
                <a :href="'{{ route('auth.google') }}?plan=' + selectedPlan + '&cycle=' + billingCycle + '&account_type=' + (accountType || 'center')" class="w-full flex items-center justify-center gap-5 py-5 px-8 border-2 border-slate-200 rounded-[1.5rem] shadow-md text-xl font-black text-slate-800 bg-white hover:bg-slate-50 hover:border-blue-500/30 hover:shadow-lg transition-all group">
                    <svg class="w-8 h-8 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>{{ __('auth.register.google_signup') }}</span>
                </a>
                <div class="relative my-10 px-8">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100/80"></div></div>
                    <div class="relative flex justify-center text-[11px] uppercase"><span class="bg-white px-5 text-slate-300 font-bold tracking-[0.3em]">{{ __('auth.register.or') ?? 'OR' }}</span></div>
                </div>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
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
                <div x-show="currentStep === 1" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            <span x-text="accountType === 'center' ? '{{ __('auth.register.center_name') }}' : ({{ Js::from(app()->isLocale('ar') ? 'اسم المدرس / المنصة' : 'Teacher / Platform Name') }})"></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-building"></i></div>
                            <input type="text" name="center_name" x-model="centerName"
                                @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                class="w-full h-14 ps-12 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-arabic focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
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
                                class="w-full h-14 pl-[80px] pr-[115px] bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-sans focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
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

                    <div class="pt-6">
                        <button type="button" @click="nextStep()"
                            class="w-full h-16 rounded-full flex items-center justify-center gap-4 group bg-brand-secondary text-white shadow-2xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-1 active:scale-95 transition-all">
                            <span class="text-xl font-black font-arabic">{{ app()->isLocale('ar') ? 'استمرار' : 'Continue' }}</span>
                            <i class="bi bi-arrow-right-short text-3xl group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Personal Details -->
                <div x-show="currentStep === 2" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.full_name') }}</label>
                        <input type="text" name="name" x-model="name" class="w-full h-14 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" :required="currentStep === 2">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.email') }}</label>
                            <input type="email" name="email" x-model="email" class="w-full h-14 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="mail@example.com" :required="currentStep === 2">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.phone') }}</label>
                            <input type="text" name="phone" x-model="phone" class="w-full h-14 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="010xxxxxxx" :required="currentStep === 2">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[13px] font-black text-slate-400 font-arabic uppercase tracking-wide">{{ __('auth.register.password') }}</label>
                            <button type="button" @click="showPassword = !showPassword" class="text-[11px] font-black text-brand-secondary uppercase tracking-widest hover:opacity-70 transition-opacity">
                                <span x-text="showPassword ? '{{ __('auth.register.hide') }}' : '{{ __('auth.register.show') }}'"></span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" class="w-full h-14 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="••••••••" :required="currentStep === 2">
                            <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" class="w-full h-14 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" :class="password_confirmation.length > 0 && !isPasswordMatch ? 'border-red-300 bg-red-50' : ''" placeholder="{{ __('auth.register.confirm_password') }}" :required="currentStep === 2">
                        </div>
                    </div>

                    <!-- Coupon -->
                    <div class="pt-2">
                        <button type="button" x-show="!showCouponInput && couponStatus !== 'valid'" @click="showCouponInput = true" 
                                class="text-sm font-black text-brand-secondary hover:underline flex items-center gap-2 font-arabic transition-all">
                            <i class="bi bi-tag-fill"></i> {{ __('auth.register.have_coupon') ?? 'هل لديك كود خصم؟' }}
                        </button>
                        <div x-show="showCouponInput || couponStatus === 'valid'" x-transition class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 px-1 uppercase">{{ __('admin.coupon_code') }}</label>
                            <div class="relative">
                                <input type="text" name="coupon_code" x-model="couponCode" @input.debounce.500ms="validateCoupon()"
                                    class="w-full h-12 px-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-black uppercase focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all"
                                    :class="couponStatus === 'valid' ? 'border-emerald-200 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-200 bg-red-50' : '')">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                                    <template x-if="couponStatus === 'loading'"><div class="w-4 h-4 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                    <template x-if="couponStatus === 'valid'"><i class="bi bi-patch-check-fill text-emerald-500"></i></template>
                                    <template x-if="couponStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-400"></i></template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Simplified Price Summary Card -->
                    <div class="p-6 rounded-3xl bg-slate-50/80 border border-slate-100 relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('auth.register.selected_plan') }}</span>
                                    <button type="button" @click="showPlanModal = true" class="text-[10px] font-black text-brand-secondary underline underline-offset-2 hover:opacity-70 transition-opacity uppercase tracking-widest">
                                        {{ app()->getLocale() == 'ar' ? 'تغيير' : 'Change' }}
                                    </button>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 font-arabic" x-text="currentPlan.name"></h3>
                            </div>
                            <div class="text-right">
                                <template x-if="couponStatus === 'valid'">
                                    <div class="text-[11px] font-black text-emerald-600 mb-1 animate-fade-in">-<span x-text="couponDiscountAmount.toLocaleString()"></span> <span x-text="currentPriceData.currency"></span></div>
                                </template>
                                <div class="flex items-baseline gap-1 text-brand-secondary">
                                    <span class="text-3xl font-black tracking-tighter" x-text="finalPrice.toLocaleString()"></span>
                                    <span class="text-sm font-bold opacity-60" x-text="currentPriceData.currency"></span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400">/ 
                                    <span x-show="billingCycle === 'monthly'">{{ app()->getLocale() == 'ar' ? 'شهر' : 'month' }}</span>
                                    <span x-show="billingCycle === 'term'">{{ app()->getLocale() == 'ar' ? 'ترم (150 يوم)' : 'term (150 days)' }}</span>
                                    <span x-show="billingCycle === 'yearly'">{{ app()->getLocale() == 'ar' ? 'سنة' : 'year' }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Billing Cycle Switcher -->
                        <div class="flex p-1.5 bg-slate-200/50 rounded-2xl mb-6 items-center">
                            <button type="button" @click="billingCycle = 'monthly'" 
                                    class="flex-1 py-2 text-[11px] font-black rounded-xl transition-all"
                                    :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'شهري' : 'Monthly' }}
                            </button>
                            <button type="button" @click="billingCycle = 'term'" 
                                    class="flex-1 py-2 text-[11px] font-black rounded-xl transition-all"
                                    :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'ترم (150 يوم)' : 'Term' }}
                            </button>
                            <button type="button" @click="billingCycle = 'yearly'" 
                                    class="flex-1 py-2 text-[11px] font-black rounded-xl transition-all"
                                    :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                {{ app()->getLocale() == 'ar' ? 'سنوي' : 'Yearly' }}
                            </button>
                        </div>

                        <!-- Conversion Boost: Reassurance -->
                        <div class="pt-4 border-t border-slate-200/60 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-1.5 text-slate-500">
                                <i class="bi bi-shield-check text-emerald-500 text-base"></i>
                                <span class="text-[11px] font-bold font-arabic">{{ app()->getLocale() == 'ar' ? 'دفع آمن 100%' : '100% Secure Payment' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500">
                                <i class="bi bi-arrow-repeat text-brand-secondary text-base"></i>
                                <span class="text-[11px] font-bold font-arabic">{{ app()->getLocale() == 'ar' ? 'إلغاء في أي وقت' : 'Cancel Anytime' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway Selection -->
                    <div class="space-y-3" x-show="selectedPlan !== 'free-trial'">
                        <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            {{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                        </label>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Paymob -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_gateway" value="paymob" checked class="peer sr-only">
                                <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-100 bg-slate-50/30 peer-checked:border-brand-secondary peer-checked:bg-white transition-all hover:border-slate-200">
                                    <i class="bi bi-credit-card-2-back text-2xl mb-2 text-slate-400 peer-checked:text-brand-secondary"></i>
                                    <span class="text-xs font-black text-slate-600 peer-checked:text-slate-900">{{ app()->getLocale() == 'ar' ? 'بطاقة بنكية / محفظة (Paymob)' : 'Credit Card / Wallet (Paymob)' }}</span>
                                </div>
                            </label>
                            <!-- PayPal -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_gateway" value="paypal" class="peer sr-only">
                                <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-100 bg-slate-50/30 peer-checked:border-brand-secondary peer-checked:bg-white transition-all hover:border-slate-200">
                                    <i class="bi bi-paypal text-2xl mb-2 text-slate-400 peer-checked:text-brand-secondary"></i>
                                    <span class="text-xs font-black text-slate-600 peer-checked:text-slate-900">PayPal</span>
                                </div>
                            </label>
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
                             class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden animate-scale-in">
                            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h3 class="text-xl font-black text-slate-900 font-arabic">{{ app()->getLocale() == 'ar' ? 'اختر الباقة المناسبة' : 'Select Plan' }}</h3>
                                <button type="button" @click="showPlanModal = false" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-200 transition-colors">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="p-8 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                                <template x-for="pkg in packages" :key="pkg.slug">
                                    <label class="relative block cursor-pointer group">
                                        <input type="radio" name="plan_selector" :value="pkg.slug" x-model="selectedPlan" @change="showPlanModal = false" class="peer sr-only">
                                        <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-brand-secondary/30 peer-checked:border-brand-secondary peer-checked:bg-brand-secondary/5 transition-all">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 peer-checked:border-brand-secondary flex items-center justify-center transition-all bg-white">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-brand-secondary scale-0 peer-checked:scale-100 transition-transform"></div>
                                                    </div>
                                                    <span class="font-black text-slate-900 uppercase tracking-tight" x-text="pkg.name"></span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-lg font-black text-brand-secondary" x-text="billingCycle === 'yearly' ? pkg.yearly_price : (billingCycle === 'term' ? pkg.term_price : pkg.price)"></span>
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

                    <div class="pt-6 flex flex-col sm:flex-row gap-4">
                        <button type="button" @click="prevStep()" class="flex-1 h-16 rounded-full font-black text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border-2 border-slate-100">
                            {{ app()->isLocale('ar') ? 'رجوع' : 'Back' }}
                        </button>
                        <button type="submit" :disabled="password.length > 0 && !isPasswordMatch"
                                class="flex-[2] h-16 rounded-full font-black text-xl text-white bg-brand-secondary shadow-xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-1 transition-all disabled:opacity-50 disabled:grayscale relative overflow-hidden group">
                            <!-- Button Shine Effect -->
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-white opacity-20 group-hover:animate-[shine_1s] group-hover:left-full transition-all duration-700 ease-in-out"></div>
                            <span class="relative z-10">{{ __('auth.register.cta_main') }}</span>
                        </button>
                    </div>

                    <!-- Conversion Boost: Guarantee & Support -->
                    <div class="mt-6 flex flex-col items-center gap-3">
                        <div class="flex items-center justify-center gap-2 text-slate-600 bg-emerald-50/50 px-4 py-2.5 rounded-xl border border-emerald-100/50 w-full">
                            <i class="bi bi-shield-fill-check text-emerald-500 text-lg"></i>
                            <p class="text-[12px] font-bold font-arabic">{{ app()->getLocale() == 'ar' ? 'ضمان استرجاع الأموال خلال 14 يوماً. بدون رسوم خفية.' : '14-Day Money-Back Guarantee. No Hidden Fees.' }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 text-slate-400 text-[11px] font-bold">
                            <i class="bi bi-headset"></i>
                            <span>{{ app()->getLocale() == 'ar' ? 'تحتاج إلى مساعدة؟' : 'Need help?' }}</span>
                            <a href="#" class="text-brand-secondary hover:underline">{{ app()->getLocale() == 'ar' ? 'تواصل مع الدعم الفني' : 'Contact Support' }}</a>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-[11px] text-slate-400 font-arabic leading-relaxed">
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
        <div class="p-8 bg-slate-50/50 border-t border-slate-100 text-center transition-all duration-500" :class="!accountType ? 'blur-[5px] opacity-40 pointer-events-none select-none' : ''">
            <span class="text-sm text-slate-500 font-arabic font-bold">
                {{ __('auth.login.no_account_link') }}
                <a href="{{ route('login.portal') }}" class="text-brand-secondary font-black hover:underline ml-2">{{ __('auth.login.title') }}</a>
            </span>
        </div>
    </div>
</div>
@endsection
