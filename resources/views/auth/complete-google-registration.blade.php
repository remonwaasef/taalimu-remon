@extends('layouts.landing-new')

@section('content')
<!-- Import Cairo & Outfit Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('googleRegistration', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: 'monthly',
        packages: config.packages,
        centerName: '{{ old('center_name') }}',
        subdomain: '{{ old('subdomain') }}',
        manuallyEditedSubdomain: {{ old('subdomain') ? 'true' : 'false' }},
        subdomainStatus: 'idle',
        subdomainMessage: '',
        isSubmitting: false,
        userCountry: 'default',

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
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

             if (this.userCountry && prices[this.userCountry]) {
                 let r = prices[this.userCountry];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             }
             return data;
        },

        get currentPriceData() {
             return this.getPriceData(this.currentPlan);
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
        }
    }))
});
</script>

<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay" 
     style="padding-top: 120px;"
     x-data="googleRegistration({
        selectedPlan: {{ Js::from($selectedPlanSlug) }},
        packages: {{ Js::from($packagesData) }}
     })"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <!-- Main Centered Card Container (Dual Panel) -->
    <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden flex flex-col lg:flex-row border border-slate-100/50 min-h-[640px] animate-fade-in-up md:backdrop-blur-xl relative" style="max-width: 960px;">
        
        <!-- Left Panel: Plan Selection (Sidebar) -->
        <div class="hidden lg:flex lg:w-[32%] text-white flex-col p-8 lg:p-10 relative overflow-hidden" 
             style="background: linear-gradient(135deg, hsl(263 85% 20%) 0%, hsl(var(--primary-purple)) 40%, hsl(var(--secondary)) 100%);">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 blur-[80px] rounded-full pointer-events-none"></div>
            
            <div class="space-y-8 flex-1">
                <h2 class="text-2xl lg:text-3xl font-bold mb-4 font-arabic leading-tight text-white">
                    {{ __('Your Educational Platform is Ready!') }}
                </h2>
                <p class="text-white/70 text-base font-arabic font-light leading-relaxed">
                    {{ __('Complete these simple steps to start your free trial and explore all the powerful management tools.') }}
                </p>

                <div class="space-y-4 pt-6">
                    <label class="text-[9px] font-black text-white/40 uppercase tracking-widest px-1 mb-1 block">{{ __('auth.register.select_plan') }}</label>
                    <template x-for="pkg in packages" :key="pkg.slug">
                        <div @click="selectedPlan = pkg.slug"
                            class="w-full p-6 rounded-2xl plan-card-compact cursor-pointer relative group/card mb-4 border transition-all duration-300 overflow-hidden"
                            :class="selectedPlan === pkg.slug ? 'selected' : 'border-white/5 hover:border-white/20 bg-white/[0.02]'">
                            
                            <div class="inline-flex mb-3">
                                <span class="text-[9px] font-black uppercase tracking-[0.25em] text-white/40 px-3 py-1 bg-white/5 rounded-full border border-white/5" x-text="pkg.name"></span>
                            </div>

                            <div class="flex items-start justify-center" x-data="{ pData: {} }" x-effect="pData = getPriceData(pkg)">
                                <span class="text-4xl font-black text-white tracking-tighter leading-none" x-text="pData.amount.toLocaleString()"></span>
                                <div class="flex flex-col ml-1 rtl:mr-1 rtl:ml-0 mt-1">
                                    <span class="text-[12px] font-bold text-white/50" x-text="pData.currency"></span>
                                    <span class="text-[9px] font-bold text-white/40">/{{ __('landing.pricing.month_short') ?? 'شهر' }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-auto pt-8 border-t border-white/5 opacity-60">
                <p class="text-[10px] text-white/50 font-arabic">
                    {{ __('Join 5,000+ educational centers leading the digital future.') }}
                </p>
            </div>
        </div>

        <!-- Right Panel: Google Registration Form -->
        <div class="lg:w-[68%] bg-white flex flex-col p-8 lg:p-12 relative">
            <div class="max-w-[440px] mx-auto w-full pt-4">
                
                {{-- Verified Account Pill --}}
                <div class="mb-10 animate-fade-in">
                    <div class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-[2rem] shadow-sm group hover:border-brand-secondary/30 transition-all">
                        <div class="w-14 h-14 bg-white border-2 border-brand-secondary/10 rounded-full flex items-center justify-center text-brand-secondary font-black text-2xl group-hover:scale-110 transition-transform shadow-sm">
                            {{ mb_substr(session('google_user.name', 'U'), 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-slate-900 text-sm truncate uppercase tracking-tight">{{ session('google_user.name') }}</p>
                            <p class="text-slate-500 text-xs truncate font-medium opacity-80">{{ session('google_user.email') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-black ring-1 ring-inset ring-emerald-500/20 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('Verified') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- STEP HEADINGS -->
                <div class="mb-8">
                    <h1 class="text-2xl lg:text-4xl font-black text-slate-900 mb-3 font-arabic leading-tight">
                        {{ app()->getLocale() == 'ar' ? 'أهلاً بك! لنكمل تجهيز مركزك' : 'Welcome! Let\'s complete setup' }}
                    </h1>
                    <p class="text-slate-500 text-sm lg:text-base font-arabic font-medium opacity-80">
                        {{ app()->getLocale() == 'ar' ? 'خطوة أخيرة للبدء في استخدام منصتك التعليمية' : 'One last step to start your educational platform' }}
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-[13px] font-arabic shadow-sm animate-shake">
                        <ul class="space-y-1 font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('google.complete-registration') }}" method="POST" class="space-y-6" @submit="handleSubmit($event)">
                    @csrf
                    <input type="hidden" name="plan" x-model="selectedPlan">

                    {{-- Center Name --}}
                    <div class="space-y-1">
                        <label for="center_name" class="label-compact px-1 font-arabic opacity-70">
                            {{ __('auth.register.center_name') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                                <i class="bi bi-building-fill text-xl"></i>
                            </div>
                            <input type="text" name="center_name" id="center_name" x-model="centerName"
                                   @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                   class="block w-full h-14 ps-14 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all font-arabic text-base font-bold shadow-inner"
                                   placeholder="{{ __('auth.register.center_name_placeholder') }}" required autofocus>
                        </div>
                    </div>

                    {{-- Subdomain Field --}}
                    <div class="space-y-1">
                        <label class="label-compact px-1 font-arabic opacity-70">
                            {{ app()->getLocale() == 'ar' ? 'رابط المنصة الخاص بك' : 'Your Platform Link' }}
                        </label>
                        <div class="relative flex items-center group" dir="ltr">
                            <!-- Left Side: HTTPS prefix -->
                            <div class="absolute left-0 inset-y-0 flex items-center px-4 pointer-events-none text-brand-secondary font-black text-sm bg-brand-secondary/5 border-r border-brand-secondary/20 rounded-l-2xl z-10">
                                https://
                            </div>
                            
                            <!-- Input Field -->
                            <input type="text" name="subdomain" x-model="subdomain"
                                   @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                   @input.debounce.500ms="checkSubdomain()"
                                   class="block w-full h-14 pl-[90px] pr-[120px] bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all font-sans text-base font-bold text-left shadow-inner"
                                   placeholder="center-name" required>
                                   
                            <!-- Right Side: Domain suffix & Indicator -->
                            <div class="absolute right-0 inset-y-0 flex items-center pr-4 pointer-events-none text-slate-400 font-bold text-sm z-10 gap-3">
                                <span class="opacity-60">.taalimu.com</span>
                                
                                <div class="flex items-center justify-center w-6 h-6">
                                    <template x-if="subdomainStatus === 'loading'">
                                        <div class="w-5 h-5 border-3 border-brand-secondary border-t-transparent rounded-full animate-spin"></div>
                                    </template>
                                    <template x-if="subdomainStatus === 'valid'">
                                        <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20 animate-scale-in">
                                            <i class="bi bi-check-lg text-sm"></i>
                                        </div>
                                    </template>
                                    <template x-if="subdomainStatus === 'invalid'">
                                        <div class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-red-500/20 animate-shake">
                                            <i class="bi bi-x-lg text-sm"></i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <template x-if="subdomainMessage">
                            <p class="text-[12px] font-bold px-2 animate-fade-in" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" x-text="subdomainMessage"></p>
                        </template>
                    </div>

                    {{-- Phone Field --}}
                    <div class="space-y-1">
                        <label for="phone" class="label-compact px-1 font-arabic opacity-70">
                            {{ __('auth.register.phone') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                                <i class="bi bi-telephone-fill text-xl"></i>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="block w-full h-14 ps-14 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all text-base font-bold shadow-inner"
                                   placeholder="010xxxxxxx" required>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6">
                        <button type="submit" :disabled="isSubmitting || subdomainStatus === 'invalid'"
                                class="btn-hero-cta w-full h-16 rounded-full font-arabic flex items-center justify-center gap-4 text-xl font-black shadow-2xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-1.5 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:pointer-events-none group">
                            <span x-show="!isSubmitting">{{ __('Start Your Journey Now') }}</span>
                            <span x-show="isSubmitting" class="flex items-center gap-3">
                                <div class="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                                {{ __('Processing...') }}
                            </span>
                            <i x-show="!isSubmitting" class="bi bi-rocket-takeoff text-2xl group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-slate-50 text-center">
                    <p class="text-[12px] text-slate-400 font-arabic font-medium opacity-60">
                        {{ __('By continuing, you agree to our') }}
                        <a href="{{ route('terms') }}" class="text-brand-secondary font-black hover:underline">{{ __('Terms') }}</a>
                        {{ __('and') }}
                        <a href="{{ route('privacy') }}" class="text-brand-secondary font-black hover:underline">{{ __('Privacy Policy') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

