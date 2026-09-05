@extends('layouts.landing-new')

@section('content')
@include('partials.login-dark-theme')

<div class="login-page-wrap min-h-screen flex flex-col justify-center bg-slate-50 dark:bg-[#09101d] transition-colors duration-300"
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
     x-data="{ showPassword: false, isSubmitting: false }">

    <div class="w-full min-h-screen flex flex-col lg:grid lg:grid-cols-12">
        
        <!-- Left Branding & Showcase Panel (Visible on large screens) -->
        <div class="relative hidden lg:flex lg:col-span-5 xl:col-span-5 flex-col justify-between p-10 xl:p-14 overflow-hidden"
             style="background: linear-gradient(145deg, #0a1526 0%, #0d1f35 45%, #082d27 100%);">
            
            <!-- Ambient Background Glow & Gradients -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#168F7C]/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#168F7C_1px,transparent_1px)] [background-size:24px_24px] opacity-15 pointer-events-none"></div>

            <!-- Top Brand Identity -->
            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#168F7C] to-[#2ecc71] p-2.5 shadow-xl shadow-[#168F7C]/30 flex items-center justify-center transition-transform group-hover:scale-105">
                        <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu Logo" class="w-full h-full object-contain filter brightness-0 invert">
                    </div>
                    <div>
                        <span class="text-2xl font-black text-white font-arabic tracking-tight block">تـعـلـيـمـي</span>
                        <span class="text-[10px] text-teal-300/80 font-bold tracking-widest uppercase block">Taalimu Education OS</span>
                    </div>
                </a>
            </div>

            <!-- Middle Value Showcase -->
            <div class="relative z-10 my-auto py-10">
                <!-- Floating Feature Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-400/20 text-teal-300 text-xs font-bold font-arabic mb-6 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-ping"></span>
                    <span class="w-2 h-2 rounded-full bg-teal-400 -ms-4"></span>
                    المنصة السحابية المتكاملة للمراكز التعليمية
                </div>

                <h1 class="text-3xl xl:text-4xl font-black text-white leading-tight font-arabic mb-4">
                    إدارة ذكية ومبسطة <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-emerald-300 to-teal-100">
                        لكل تفاصيل مركزك التعليمي
                    </span>
                </h1>

                <p class="text-slate-300 text-sm leading-relaxed mb-8 max-w-md font-arabic font-normal">
                    تحكّم كامل في الفصول، تسجيل الحضور، تقارير الطلاب، والتحصيل المالي في شاشة واحدة صُممت لتوفير وقتك وجهدك.
                </p>

                <!-- Value Highlights Cards -->
                <div class="space-y-3.5 max-w-md">
                    <!-- Feature 1 -->
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] hover:bg-white/[0.07] border border-white/10 backdrop-blur-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-teal-300 text-base shrink-0">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-arabic">إدارة المجموعات والطلاب</h3>
                            <p class="text-xs text-slate-400 font-arabic">تنظيم الجداول وتنبيهات فورية لأولياء الأمور عبر واتساب</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] hover:bg-white/[0.07] border border-white/10 backdrop-blur-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-300 text-base shrink-0">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-arabic">الفوترة والحسابات الدقيقة</h3>
                            <p class="text-xs text-slate-400 font-arabic">متابعة الاشتراكات، المصروفات، ورواتب المدرسين بكل شفافية</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/[0.04] hover:bg-white/[0.07] border border-white/10 backdrop-blur-md transition-all">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-500/30 flex items-center justify-center text-cyan-300 text-base shrink-0">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white font-arabic">عزل بيانات فائق الأمان</h3>
                            <p class="text-xs text-slate-400 font-arabic">نظام Multi-Tenant مشفر وسحابي يضمن سرية بيانات كل مركز</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Trust Proof -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="flex text-amber-400 text-xs">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="text-xs text-slate-300 font-arabic font-medium">4.9 / 5 تقييم العملاء</span>
                </div>
                <div class="text-xs text-teal-300/90 font-arabic font-bold">
                    +500 مركز تعليمي يثق بنا
                </div>
            </div>
        </div>

        <!-- Right Login Form Column -->
        <div class="flex-1 lg:col-span-7 xl:col-span-7 flex flex-col justify-between p-6 sm:p-10 lg:p-14 relative z-10">
            
            <!-- Top Navigation & Return Link -->
            <div class="flex items-center justify-between mb-8 lg:mb-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-[#168F7C] dark:hover:text-teal-400 transition-colors py-2 px-3 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fas fa-arrow-right rtl:rotate-0 ltr:rotate-180 text-xs"></i>
                    <span>العودة للرئيسية</span>
                </a>

                <!-- Mobile Brand Header (Visible only on mobile) -->
                <div class="lg:hidden flex items-center gap-2">
                    <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="w-8 h-8 object-contain">
                    <span class="font-black text-slate-900 dark:text-white font-arabic text-lg">تعليمي</span>
                </div>

                <!-- Empty space to balance layout (since dark theme toggle is anchored or fixed) -->
                <div class="w-8"></div>
            </div>

            <!-- Main Auth Card Container -->
            <div class="max-w-md w-full mx-auto my-auto py-4">
                
                <div class="login-card bg-white dark:bg-[#0f172a] rounded-[28px] border border-slate-200/80 dark:border-slate-800/80 shadow-2xl shadow-slate-900/5 dark:shadow-black/40 p-7 sm:p-10 relative overflow-hidden">
                    
                    <!-- Decorative subtle accent line on top -->
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-[#168F7C] via-teal-400 to-[#0D7465]"></div>

                    <!-- Header -->
                    <div class="text-start mb-7">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-[#168F7C] dark:text-teal-400 mb-4 shadow-sm">
                            <i class="fas fa-right-to-bracket text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white font-arabic tracking-tight mb-1.5">
                            {{ __('auth.login.title') }}
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-arabic">
                            {{ __('auth.login.subtitle') }} — أدخل بيانات حسابك للوصول إلى لوحة التحكم
                        </p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 rounded-2xl p-4 mb-6 flex items-start gap-3 animate-fade-in-up" role="alert">
                            <i class="fas fa-circle-exclamation text-base mt-0.5 shrink-0"></i>
                            <div class="text-xs sm:text-sm font-medium font-arabic leading-snug">
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <!-- Info Alert -->
                    @if (session('info'))
                        <div class="bg-teal-50 dark:bg-teal-950/30 border border-teal-200 dark:border-teal-900/50 text-teal-800 dark:text-teal-300 rounded-2xl p-4 mb-6 flex items-start gap-3" role="status">
                            <i class="fas fa-circle-info text-base mt-0.5 shrink-0"></i>
                            <div class="text-xs sm:text-sm font-medium font-arabic leading-snug">
                                {{ session('info') }}
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('unified.login.submit') }}" 
                          @submit="if(isSubmitting) { $event.preventDefault(); return; } isSubmitting = true;"
                          class="space-y-5">
                        @csrf
                        
                        <!-- Email or Phone -->
                        <div>
                            <label for="email" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 font-arabic">
                                {{ __('auth.login.email_or_phone') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <i class="fas fa-envelope text-sm"></i>
                                </div>
                                <input type="text" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       class="block w-full ps-10 pe-4 py-3.5 bg-slate-50 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-sm focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-4 focus:ring-[#168F7C]/15 focus:border-[#168F7C] transition-all @error('email') border-red-500 dark:border-red-500 focus:ring-red-500/15 @enderror" 
                                       placeholder="example@domain.com أو 01xxxxxxxxx"
                                       required 
                                       autocomplete="username"
                                       autofocus>
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-arabic font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 font-arabic">
                                    {{ __('auth.login.password') }}
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#168F7C] dark:text-teal-400 hover:underline font-arabic">
                                        {{ __('auth.login.forgot_password') }}
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <i class="fas fa-lock text-sm"></i>
                                </div>
                                <input :type="showPassword ? 'text' : 'password'" 
                                       id="password" 
                                       name="password" 
                                       class="block w-full ps-10 pe-11 py-3.5 bg-slate-50 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 text-sm focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-4 focus:ring-[#168F7C]/15 focus:border-[#168F7C] transition-all @error('password') border-red-500 dark:border-red-500 focus:ring-red-500/15 @enderror" 
                                       placeholder="••••••••"
                                       required
                                       autocomplete="current-password">
                                
                                <!-- Show / Hide Password Toggle -->
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 end-0 pe-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors focus:outline-none"
                                        aria-label="Toggle password visibility">
                                    <i class="fas text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-arabic font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" 
                                       name="remember" 
                                       id="remember"
                                       class="w-4 h-4 rounded text-[#168F7C] border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-[#168F7C] focus:ring-offset-0 focus:ring-2 cursor-pointer">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 font-arabic">
                                    {{ __('auth.login.remember_me') }}
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#168F7C] to-[#0D7465] text-white font-black text-sm sm:text-base font-arabic shadow-lg shadow-[#168F7C]/25 hover:shadow-xl hover:shadow-[#168F7C]/35 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-75 disabled:cursor-not-allowed transition-all duration-200">
                            <template x-if="!isSubmitting">
                                <span class="flex items-center gap-2">
                                    <span>{{ __('auth.login.login_button') }}</span>
                                    <i class="fas fa-arrow-left rtl:rotate-0 ltr:rotate-180 text-xs transition-transform group-hover:-translate-x-1"></i>
                                </span>
                            </template>
                            <template x-if="isSubmitting">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-circle-notch fa-spin text-sm"></i>
                                    <span>جاري تسجيل الدخول...</span>
                                </span>
                            </template>
                        </button>

                        <!-- Divider -->
                        <div class="relative my-6 text-center">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="px-3 bg-white dark:bg-[#0f172a] text-xs font-bold text-slate-400 dark:text-slate-500 font-arabic">
                                    {{ __('auth.login.or_continue_with') }}
                                </span>
                            </div>
                        </div>

                        <!-- Google SSO Button -->
                        <a href="{{ route('auth.google') }}" 
                           class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-bold font-arabic shadow-sm hover:shadow transition-all duration-200">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>{{ __('auth.login.google') }}</span>
                        </a>
                    </form>

                    <!-- New Center / Account Prompt -->
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-arabic mb-3">
                            {{ __('auth.login.no_account') }}
                        </p>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-2xl border-2 border-[#168F7C] text-[#168F7C] dark:text-teal-400 dark:border-teal-500/40 hover:bg-[#168F7C] hover:text-white dark:hover:bg-[#168F7C] dark:hover:text-white font-bold text-xs sm:text-sm font-arabic transition-all duration-200">
                            <i class="fas fa-plus-circle text-xs"></i>
                            <span>{{ __('auth.login.register_now') }} (تجربة مجانية)</span>
                        </a>
                    </div>

                    <!-- Super Admin Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.login') }}" 
                           class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-300 font-arabic transition-colors">
                            <i class="fas fa-user-shield text-xs"></i>
                            <span>{{ __('auth.login.admin_login') }}</span>
                        </a>
                    </div>

                </div>

                <!-- Subtle Bottom Security Guarantee -->
                <div class="mt-6 flex items-center justify-center gap-4 text-slate-400 dark:text-slate-600 text-xs font-arabic">
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-lock text-[10px] text-teal-600 dark:text-teal-500"></i>
                        اتصال مشفر 256-bit SSL
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-shield-check text-[10px] text-teal-600 dark:text-teal-500"></i>
                        حماية كاملة للخصوصية
                    </span>
                </div>
            </div>

            <!-- Footer Meta -->
            <div class="text-center py-2 text-xs text-slate-400 dark:text-slate-600 font-arabic">
                © {{ date('Y') }} منصة تعليمي. جميع الحقوق محفوظة.
            </div>
        </div>

    </div>
</div>
@endsection
