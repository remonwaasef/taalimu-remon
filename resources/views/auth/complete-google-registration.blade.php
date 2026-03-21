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
        accountType: config.accountType || 'center',

        showPlanModal: false,
        couponCode: '',
        showCouponInput: false,
        couponStatus: 'none',
        couponMessage: '',
        discountValue: 0,
        discountType: 'percentage',
        discountText: '',
        isApplyingCoupon: false,

        async init() {
            try {
                const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                const data = await response.json();
                this.userCountry = data.country || '';
            } catch(e) { console.log('IP fetch failed', e); }
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
                 term: parseFloat(pkg.term_price_raw || 0),
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

             if (this.userCountry && prices[this.userCountry]) {
                 let r = prices[this.userCountry];
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
            if (this.subdomainStatus === 'invalid') {
                e.preventDefault();
                alert('{{ app()->getLocale() == 'ar' ? 'هذا الرابط مستخدم بالفعل' : 'This subdomain is already taken' }}');
                return;
            }
            this.isSubmitting = true;
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

<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
     x-data="googleRegistration({
        selectedPlan: {{ Js::from($selectedPlanSlug) }},
        selectedCycle: {{ Js::from($selectedCycle) }},
        accountType: {{ Js::from($accountType) }},
        packages: {{ Js::from($packagesData) }}
     })"
     @reset-submission-state.window="isSubmitting = false"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <!-- Main Centered Card Container (Optimized Layout) -->
    <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden border border-slate-100/50 animate-fade-in-up md:backdrop-blur-xl relative">
        
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
                                <p class="text-slate-500 text-[10px] truncate font-medium opacity-80">{{ session('google_user.email') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h1 class="text-2xl font-black text-slate-900 mb-2 font-arabic leading-tight">
                            {{ app()->isLocale('ar') ? 'تأكيد الحساب' : 'Confirm Account' }}
                        </h1>
                        <p class="text-slate-500 text-xs font-arabic font-medium opacity-80 leading-relaxed">
                            {{ app()->isLocale('ar') ? 'خطوة واحدة لنبدأ في تجهيز منصتك التعليمية' : 'One last step to complete your platform setup' }}
                        </p>
                    </div>

                    <!-- Price Summary Card (Integrated into Sidebar) -->
                    <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __('auth.register.selected_plan') }}</span>
                                    <button type="button" @click="showPlanModal = true" class="text-[9px] font-black text-brand-secondary underline hover:opacity-70 transition-opacity uppercase tracking-widest">
                                        {{ app()->getLocale() == 'ar' ? 'تغيير' : 'Change' }}
                                    </button>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 font-arabic" x-text="currentPlan.name"></h3>
                            </div>
                        </div>

                        <!-- Mini Features List -->
                        <div class="space-y-2 py-3 border-y border-slate-50">
                            <template x-for="feature in currentPlan.features.slice(0, 3)" :key="feature">
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-600 font-arabic">
                                    <i class="bi bi-check2 text-emerald-500"></i>
                                    <span x-text="feature"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Price & Switcher -->
                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ app()->getLocale() == 'ar' ? 'الإجمالي' : 'Total' }}</span>
                                <div class="text-right">
                                    <template x-if="couponStatus === 'valid' || currentPriceData.old_price_raw > finalPrice">
                                        <div class="flex items-center justify-end gap-2 mb-1 animate-fade-in">
                                            <span class="text-[10px] font-bold text-slate-300 line-through">
                                                <span x-text="((couponStatus === 'valid' ? currentPriceData.price_raw : currentPriceData.old_price_raw) || 0).toLocaleString()"></span>
                                                <span x-text="currentPriceData.currency"></span>
                                            </span>
                                            <span class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded-md">
                                                <template x-if="couponStatus === 'valid'">
                                                    <span>- <span x-text="couponDiscountAmount.toLocaleString()"></span></span>
                                                </template>
                                                <template x-if="couponStatus !== 'valid' && currentPriceData.old_price_raw > finalPrice">
                                                    <span>{{ app()->isLocale('ar') ? 'خصم متاح' : 'Discount' }}</span>
                                                </template>
                                            </span>
                                        </div>
                                    </template>
                                    <div class="flex items-baseline gap-1 text-brand-secondary">
                                        <span class="text-2xl font-black tracking-tighter" x-text="finalPrice.toLocaleString()"></span>
                                        <span class="text-xs font-bold opacity-60" x-text="currentPriceData.currency"></span>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400" x-text="'/' + (billingCycle === 'yearly' ? '{{ app()->getLocale() == 'ar' ? 'سنة' : 'year' }}' : (billingCycle === 'term' ? '{{ app()->getLocale() == 'ar' ? 'ترم' : 'term' }}' : '{{ app()->getLocale() == 'ar' ? 'شهر' : 'month' }}'))"></span>
                                </div>
                            </div>
                            
                            <div class="flex p-1 bg-slate-100 rounded-xl items-center">
                                <button type="button" @click="billingCycle = 'monthly'" 
                                        class="flex-1 py-1.5 text-[9px] font-black rounded-lg transition-all"
                                        :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                    {{ app()->getLocale() == 'ar' ? 'شهري' : 'Month' }}
                                </button>
                                <button type="button" @click="billingCycle = 'term'" 
                                        class="flex-1 py-1.5 text-[9px] font-black rounded-lg transition-all"
                                        :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                    {{ app()->getLocale() == 'ar' ? 'ترم' : 'Term' }}
                                </button>
                                <button type="button" @click="billingCycle = 'yearly'" 
                                        class="flex-1 py-1.5 text-[9px] font-black rounded-lg transition-all"
                                        :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                    {{ app()->getLocale() == 'ar' ? 'سنوي' : 'Yearly' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-3 text-slate-400 text-[10px] font-bold opacity-40 px-6">
                    <i class="bi bi-shield-check text-emerald-500 text-sm"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'جميع البيانات مشفرة وآمنة تماماً' : 'All data is encrypted and secure' }}</span>
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
                                    @foreach ($errors->all() as $error) <li>• {{ $error }}</li> @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('google.complete-registration') }}" method="POST" class="space-y-4" @submit="handleSubmit($event)">
                    @csrf
                    <input type="hidden" name="plan" x-model="selectedPlan">
                    <input type="hidden" name="account_type" x-model="accountType">
                    <input type="hidden" name="billing_cycle" x-model="billingCycle">

                    {{-- Center Name --}}
                    <div class="space-y-1">
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                            <span x-text="accountType === 'center' ? '{{ __('auth.register.center_name') }}' : ({{ Js::from(app()->isLocale('ar') ? 'اسم المدرس / المنصة' : 'Teacher / Platform Name') }})"></span>
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
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ app()->isLocale('ar') ? 'رابط المنصة' : 'Platform Link' }}</label>
                        <div class="relative flex items-center w-full group" dir="ltr">
                            <div class="absolute left-0 inset-y-0 flex items-center px-3 pointer-events-none text-brand-secondary font-black text-[10px] bg-brand-secondary/5 border-r border-brand-secondary/10 rounded-l-xl">https://</div>
                            <input type="text" name="subdomain" x-model="subdomain"
                                @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                @input.debounce.500ms="checkSubdomain()"
                                class="w-full h-11 pl-[70px] pr-[110px] bg-slate-50/50 border-2 border-slate-100 rounded-xl text-base font-bold font-sans focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="center-name" required>
                            <div class="absolute right-0 inset-y-0 flex items-center pr-3 pointer-events-none text-slate-400 font-bold text-[10px] gap-2">
                                <span>.taalimu.com</span>
                                <div class="flex items-center justify-center w-4 h-4">
                                    <template x-if="subdomainStatus === 'loading'"><div class="w-3 h-3 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                    <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-base"></i></template>
                                    <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-base"></i></template>
                                </div>
                            </div>
                        </div>
                        <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                           class="text-[10px] font-bold px-2 mt-0.5 animate-fade-in" x-text="subdomainMessage"></p>
                    </div>

                    {{-- Phone Field --}}
                    <div class="space-y-1">
                        <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.phone') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-telephone"></i></div>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full h-11 ps-10 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                placeholder="010xxxxxxx" required>
                        </div>
                    </div>

                    <!-- Coupon (Compact inline) -->
                    <div class="pt-2">
                        <button type="button" x-show="!showCouponInput && couponStatus !== 'valid'" @click="showCouponInput = true" 
                                class="text-[11px] font-black text-brand-secondary hover:underline flex items-center gap-1 font-arabic">
                            <i class="bi bi-tag-fill"></i> {{ __('auth.register.have_coupon') ?? 'هل لديك كود خصم؟' }}
                        </button>
                        <div x-show="showCouponInput || couponStatus === 'valid'" class="relative">
                            <input type="text" name="coupon_code" x-model="couponCode" @input.debounce.500ms="validateCoupon()"
                                placeholder="{{ __('admin.coupon_code') }}"
                                class="w-full h-10 px-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-black uppercase focus:outline-none focus:border-brand-secondary transition-all"
                                :class="couponStatus === 'valid' ? 'border-emerald-200 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-200 bg-red-50' : '')">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                <template x-if="couponStatus === 'loading'"><div class="w-3 h-3 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                <template x-if="couponStatus === 'valid'"><i class="bi bi-patch-check-fill text-emerald-500"></i></template>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit" :disabled="isSubmitting || subdomainStatus === 'invalid'"
                                class="w-full h-14 rounded-full flex items-center justify-center gap-3 group bg-brand-secondary text-white shadow-xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-0.5 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale">
                            <span x-show="!isSubmitting" class="text-lg font-black font-arabic">{{ __('auth.register.cta_main') }}</span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <div class="w-5 h-5 border-3 border-white border-t-transparent rounded-full animate-spin"></div>
                                {{ app()->isLocale('ar') ? 'جاري الإكمال...' : 'Completing...' }}
                            </span>
                            <i x-show="!isSubmitting" class="bi bi-arrow-right-short text-2xl group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform"></i>
                        </button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-[10px] text-slate-400 font-arabic leading-relaxed">
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
</div>
@endsection
