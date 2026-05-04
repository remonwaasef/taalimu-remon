@extends('layouts.landing-new')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-white py-8 px-4 pt-24">
    <!-- Hero Orbs Decoration -->
    <div class="hero-orb orb-1 opacity-60"></div>
    <div class="hero-orb orb-2 opacity-40"></div>

    <div class="max-w-2xl w-full relative z-10">
        <!-- Success Icon -->
        <div class="text-center mb-8 animate-fade-in-up">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-500 rounded-full mb-5 shadow-2xl shadow-emerald-500/20 animate-scale-in">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 mb-3 tracking-tight leading-tight">
                <span class="gradient-text">{{ __('auth.registration.success_title') }}</span>
            </h1>
            <p class="text-slate-500 text-base md:text-lg font-medium max-w-lg mx-auto">
                {{ __('auth.registration.success_subtitle') }}
            </p>
        </div>

        @php
            $mode = config('app.tenancy_mode', 'subdomain');
            $protocol = request()->isSecure() ? 'https://' : 'http://';
            $port = (request()->getPort() && !in_array(request()->getPort(), [80, 443])) ? ':' . request()->getPort() : '';
            $domain = config('app.tenant_domain', 'localhost');
            
            if ($mode === 'path') {
                $accessUrl = url('/c/' . session('tenant_domain'));
            } else {
                $accessUrl = $protocol . session('tenant_domain') . '.' . $domain . $port;
            }
        @endphp

        <!-- Center Details Glass Card -->
        <div class="glass-premium rounded-[2rem] p-6 md:p-8 space-y-6 animate-scale-in border border-white/60 shadow-2xl shadow-slate-200/50" style="animation-delay: 0.1s;">
            <!-- Center Name -->
            <div class="text-center pb-6 border-b border-slate-100/80">
                <div class="inline-flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-brand-primary/10 flex items-center justify-center text-brand-primary shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">
                        {{ session('center_name') }}
                    </h2>
                </div>
            </div>

            <!-- Access URL -->
            <div class="space-y-3">
                <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-1">
                    {{ __('auth.registration.your_center_url') }}
                </label>
                <div class="flex flex-col sm:flex-row items-center gap-2 p-1.5 bg-white/60 backdrop-blur-md rounded-[1.5rem] border-2 border-slate-100/80 shadow-inner group-focus-within:border-brand-primary/30 transition-all transition-all duration-500">
                    <input 
                        type="text" 
                        readonly 
                        value="{{ $accessUrl }}"
                        class="flex-1 bg-transparent border-0 text-base font-mono font-bold text-slate-600 px-4 py-2 focus:outline-none select-all w-full sm:w-auto"
                        id="centerUrl"
                        dir="ltr"
                    >
                    <button 
                        onclick="copyUrl()"
                        class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 text-white rounded-[1.2rem] hover:bg-slate-800 transition-all shadow-xl hover:shadow-slate-900/30 flex items-center justify-center gap-2 font-black text-sm"
                        id="copyBtn"
                    >
                        <i class="fas fa-copy"></i>
                        <span>{{ __('auth.registration.copy') }}</span>
                    </button>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-white/50 backdrop-blur-md p-5 rounded-2xl border border-white/80 shadow-sm transition-all hover:shadow-md hover:border-brand-primary/20 group">
                    <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-brand-primary transition-colors">
                        <i class="fas fa-envelope opacity-70"></i>
                        {{ __('auth.registration.email') }}
                    </label>
                    <div class="text-slate-900 font-extrabold break-all text-sm tracking-tight">
                        {{ session('admin_email') }}
                    </div>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-5 rounded-2xl border border-white/80 shadow-sm transition-all hover:shadow-md hover:border-brand-primary/20 group">
                    <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-brand-primary transition-colors">
                        <i class="fas fa-key opacity-70"></i>
                        {{ __('auth.registration.password') }}
                    </label>
                    <div class="text-slate-500 font-bold text-xs leading-relaxed">
                        {{ __('auth.registration.password_hint') }}
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <a 
                    href="{{ $accessUrl }}"
                    class="group relative flex items-center justify-center gap-3 w-full py-4 px-6 bg-slate-900 text-white rounded-[1.5rem] font-black text-lg hover:bg-slate-800 transition-all shadow-xl hover:shadow-slate-900/40 transform hover:-translate-y-1 overflow-hidden"
                >
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-xl opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative z-10 flex items-center gap-3">
                        <i class="fas fa-rocket text-emerald-400 animate-pulse"></i>
                        {{ __('auth.registration.access_center') }}
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left group-hover:-translate-x-2' : 'fa-arrow-right group-hover:translate-x-2' }} transition-transform duration-300"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyUrl() {
    const urlInput = document.getElementById('centerUrl');
    const copyBtn = document.getElementById('copyBtn');
    
    urlInput.select();
    urlInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(urlInput.value).then(() => {
        const originalHTML = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check"></i> <span class="text-sm">{{ __('auth.registration.copied') }}</span>';
        copyBtn.classList.add('bg-success-green', 'scale-110');
        
        setTimeout(() => {
            copyBtn.innerHTML = originalHTML;
            copyBtn.classList.remove('bg-success-green', 'scale-110');
        }, 2000);
    }).catch(() => {
        document.execCommand('copy');
        alert('{{ __('auth.registration.url_copied') }}');
    });
}
</script>
@endsection
