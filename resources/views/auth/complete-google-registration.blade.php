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
                
                <h2 class="text-3xl font-black text-white mb-3 font-arabic leading-tight relative z-10">{{ __('Almost Done!') }}</h2>
                <p class="text-white/80 text-sm font-arabic font-medium relative z-10 opacity-90">{{ __('Create your educational platform in seconds') }}</p>
            </div>

            {{-- Verified Account Pill --}}
            <div class="px-8 -mt-6 relative z-20">
                <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-xl shadow-blue-900/5 group hover:border-brand-secondary/30 transition-all">
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-brand-secondary font-black text-xl group-hover:scale-110 transition-transform">
                        {{ mb_substr(session('google_user.name', 'U'), 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 text-[13px] truncate">{{ session('google_user.name') }}</p>
                        <p class="text-slate-400 text-[11px] truncate">{{ session('google_user.email') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black ring-1 ring-inset ring-emerald-500/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ __('Verified') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-8 py-10">
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-[13px] font-arabic shadow-sm">
                        <div class="flex gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <div class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p class="font-bold">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('google.complete-registration') }}" method="POST" class="space-y-6" @submit="handleSubmit($event)">
                    @csrf

                    {{-- Center Name --}}
                    <div class="space-y-2">
                        <label for="center_name" class="block text-[13px] font-black text-slate-700 font-arabic px-1 uppercase tracking-wider">
                            {{ __('auth.register.center_name') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                                <i class="bi bi-building text-lg"></i>
                            </div>
                            <input type="text" 
                                   name="center_name" 
                                   id="center_name"
                                   x-model="centerName"
                                   @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                                   class="block w-full ps-12 pe-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all font-arabic text-sm font-bold"
                                   placeholder="{{ __('auth.register.center_name_placeholder') }}"
                                   required 
                                   autofocus>
                        </div>
                    </div>

                    {{-- Subdomain Field --}}
                    <div class="space-y-2">
                        <label class="block text-[13px] font-black text-slate-700 font-arabic px-1 uppercase tracking-wider">
                            {{ app()->getLocale() == 'ar' ? 'رابط المنصة الخاص بك' : 'Your Platform Link' }}
                        </label>
                        <div class="relative flex items-center group">
                            <div class="absolute left-0 rtl:right-0 inset-y-0 flex items-center px-4 pointer-events-none text-slate-400 font-bold text-sm bg-slate-50 border-r rtl:border-r-0 rtl:border-l border-slate-100 rounded-l-2xl rtl:rounded-r-2xl rtl:rounded-l-none">
                                https://
                            </div>
                            <input type="text" 
                                   name="subdomain" 
                                   x-model="subdomain"
                                   @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                                   @input.debounce.500ms="checkSubdomain()"
                                   class="block w-full ps-24 rtl:pr-24 rtl:pl-44 px-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all font-sans text-sm font-bold ltr"
                                   dir="ltr"
                                   placeholder="my-center" 
                                   required>
                            <div class="absolute right-0 rtl:left-0 inset-y-0 flex items-center pr-4 rtl:pl-4 pointer-events-none text-slate-400 font-bold text-sm">
                                .taalimu.com
                            </div>

                            <!-- Availability Indicators -->
                            <div class="absolute -right-10 rtl:-left-10 top-1/2 -translate-y-1/2">
                                <template x-if="subdomainStatus === 'loading'">
                                    <div class="w-6 h-6 border-4 border-brand-secondary border-t-transparent rounded-full animate-spin"></div>
                                </template>
                                <template x-if="subdomainStatus === 'valid'">
                                    <div class="w-7 h-7 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                </template>
                                <template x-if="subdomainStatus === 'invalid'">
                                    <div class="w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-red-500/20">
                                        <i class="bi bi-x-lg"></i>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <template x-if="subdomainMessage">
                            <p class="text-[11px] font-bold px-2" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" x-text="subdomainMessage"></p>
                        </template>
                    </div>

                    {{-- Phone Field --}}
                    <div class="space-y-2">
                        <label for="phone" class="block text-[13px] font-black text-slate-700 font-arabic px-1 uppercase tracking-wider">
                            {{ __('auth.register.phone') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                                <i class="bi bi-phone text-lg"></i>
                            </div>
                            <input type="text" 
                                   name="phone" 
                                   id="phone"
                                   class="block w-full ps-12 pe-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all text-sm font-bold"
                                   placeholder="010xxxxxxx"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit" 
                                :disabled="isSubmitting || subdomainStatus === 'invalid'"
                                class="btn-hero-cta w-full py-5 px-8 rounded-2xl font-arabic flex items-center justify-center gap-3 text-lg font-black shadow-xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-1 transition-all disabled:opacity-50 disabled:grayscale disabled:pointer-events-none">
                            <span x-show="!isSubmitting">{{ __('Create My Center') }}</span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <div class="w-5 h-5 border-3 border-white border-t-transparent rounded-full animate-spin"></div>
                                {{ __('Processing...') }}
                            </span>
                            <i x-show="!isSubmitting" class="bi bi-arrow-right-short text-2xl group-hover:translate-x-1 rtl:rotate-180 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-slate-50 text-center">
                    <p class="text-[11px] text-slate-400 font-arabic leading-relaxed">
                        {{ __('By continuing, you agree to our') }}
                        <a href="{{ route('terms') }}" class="text-brand-secondary font-bold hover:underline">{{ __('Terms') }}</a>
                        {{ __('and') }}
                        <a href="{{ route('privacy') }}" class="text-brand-secondary font-bold hover:underline">{{ __('Privacy Policy') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
