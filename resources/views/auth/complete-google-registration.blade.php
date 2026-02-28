@extends('layouts.landing-new')

@section('content')
<!-- Import Cairo Font -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('googleRegistration', () => ({
        centerName: '{{ old('center_name') }}',
        subdomain: '{{ old('subdomain') }}',
        manuallyEditedSubdomain: {{ old('subdomain') ? 'true' : 'false' }},
        subdomainStatus: 'idle',
        subdomainMessage: '',
        isSubmitting: false,

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

<div class="min-h-screen mesh-gradient-soft noise-overlay flex justify-center items-center p-4 lg:p-8" 
     x-data="googleRegistration()"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    
    <div class="w-full max-w-lg animate-fade-in-up">
        {{-- Main Card --}}
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-blue-900/5 overflow-hidden border border-white/50 backdrop-blur-xl relative">
            
            {{-- Header Section --}}
            <div class="gradient-hero px-8 py-10 text-center relative overflow-hidden">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-48 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>
                
                <div class="w-20 h-20 mx-auto bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/30 shadow-xl relative z-10 transition-transform hover:rotate-3">
                    <svg class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="white" fill-opacity="0.9"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="white" fill-opacity="1"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="white" fill-opacity="0.8"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="white"/>
                    </svg>
                </div>
                
                <h2 class="text-3xl lg:text-4xl font-black text-white mb-3 font-arabic leading-tight relative z-10">
                    {{ app()->getLocale() == 'ar' ? 'أهلاً بك! لنكمل تجهيز مركزك' : 'Welcome! Let\'s complete setup' }}
                </h2>
                <p class="text-white/90 text-sm lg:text-base font-arabic font-medium relative z-10 opacity-90 drop-shadow-sm">
                    {{ app()->getLocale() == 'ar' ? 'خطوة أخيرة للبدء في استخدام منصتك التعليمية' : 'One last step to start your educational platform' }}
                </p>
            </div>

            {{-- Verified Account Pill --}}
            <div class="px-8 -mt-8 relative z-20">
                <div class="flex items-center gap-4 p-5 bg-white/95 backdrop-blur-md rounded-2xl border border-white shadow-2xl shadow-blue-900/10 group hover:border-brand-secondary/30 transition-all transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-full flex items-center justify-center text-brand-secondary font-black text-2xl group-hover:scale-110 transition-transform shadow-inner">
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

            <div class="px-8 py-12">
                @if ($errors->any())
                    <div class="mb-8 p-5 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-[14px] font-arabic shadow-sm flex gap-3 items-start animate-shake">
                        <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                        <div class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="font-bold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('google.complete-registration') }}" method="POST" class="space-y-8" @submit="handleSubmit($event)">
                    @csrf

                    {{-- Center Name --}}
                    <div class="space-y-2">
                        <label for="center_name" class="block text-[14px] font-black text-slate-700 font-arabic px-1 uppercase tracking-tight opacity-70">
                            {{ __('auth.register.center_name') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                                <i class="bi bi-building-fill text-xl"></i>
                            </div>
                            <input type="text" 
                                   name="center_name" 
                                   id="center_name"
                                   x-model="centerName"
                                   @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                   class="block w-full ps-14 pe-5 py-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all font-arabic text-base font-bold shadow-inner"
                                   placeholder="{{ __('auth.register.center_name_placeholder') }}"
                                   required 
                                   autofocus>
                        </div>
                    </div>

                    {{-- Subdomain Field --}}
                    <div class="space-y-2">
                        <label class="block text-[14px] font-black text-slate-700 font-arabic px-1 uppercase tracking-tight opacity-70">
                            {{ app()->getLocale() == 'ar' ? 'رابط المنصة الخاص بك' : 'Your Platform Link' }}
                        </label>
                        <div class="relative flex items-center group" dir="ltr">
                            <!-- Left Side: HTTPS prefix -->
                            <div class="absolute left-0 inset-y-0 flex items-center px-4 pointer-events-none text-brand-secondary font-black text-sm bg-brand-secondary/5 border-r border-brand-secondary/20 rounded-l-2xl z-10">
                                https://
                            </div>
                            
                            <!-- Input Field -->
                            <input type="text" 
                                   name="subdomain" 
                                   x-model="subdomain"
                                   @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                   @input.debounce.500ms="checkSubdomain()"
                                   class="block w-full pl-[90px] pr-[120px] py-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all font-sans text-base font-bold text-left shadow-inner"
                                   placeholder="center-name" 
                                   required>
                                   
                            <!-- Right Side: Domain suffix & Indicator -->
                            <div class="absolute right-0 inset-y-0 flex items-center pr-4 pointer-events-none text-slate-400 font-bold text-sm z-10 gap-3">
                                <span class="opacity-60">.taalimu.com</span>
                                
                                <!-- Availability Indicators -->
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
                    <div class="space-y-2">
                        <label for="phone" class="block text-[14px] font-black text-slate-700 font-arabic px-1 uppercase tracking-tight opacity-70">
                            {{ __('auth.register.phone') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                                <i class="bi bi-telephone-fill text-xl"></i>
                            </div>
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   class="block w-full ps-14 pe-5 py-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all text-base font-bold shadow-inner"
                                   placeholder="010xxxxxxx"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6">
                        <button type="submit" 
                                :disabled="isSubmitting || subdomainStatus === 'invalid'"
                                class="btn-hero-cta w-full py-6 px-10 rounded-[1.5rem] font-arabic flex items-center justify-center gap-4 text-xl font-black shadow-2xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-1.5 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:pointer-events-none group">
                            <span x-show="!isSubmitting">{{ __('Start Your Journey Now') }}</span>
                            <span x-show="isSubmitting" class="flex items-center gap-3">
                                <div class="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                                {{ __('Processing...') }}
                            </span>
                            <i x-show="!isSubmitting" class="bi bi-rocket-takeoff text-2xl group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-10 border-t border-slate-50 text-center">
                    <p class="text-[12px] text-slate-400 font-arabic font-medium leading-relaxed opacity-60">
                        {{ __('By continuing, you agree to our') }}
                        <a href="{{ route('terms') }}" class="text-brand-secondary font-black hover:underline hover:opacity-100 transition-opacity">{{ __('Terms') }}</a>
                        {{ __('and') }}
                        <a href="{{ route('privacy') }}" class="text-brand-secondary font-black hover:underline hover:opacity-100 transition-opacity">{{ __('Privacy Policy') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

