<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('onboarding.title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/landing-new.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .onboarding-gradient-text {
            /* Landing Page Emerald-to-Green Gradient */
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .step-line-active {
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.4);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen text-slate-800 antialiased relative selection:bg-brand-primary selection:text-white overflow-x-hidden ltr:font-sans rtl:font-arabic">
    
    <!-- Backdrop Orbs -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -right-[5%] w-[600px] h-[600px] rounded-full bg-emerald-500/5 blur-[120px] animate-pulse-slow"></div>
        <div class="absolute top-[20%] -left-[10%] w-[500px] h-[500px] rounded-full bg-brand-secondary/5 blur-[100px] animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8 relative z-10" 
         x-data="onboardingWizard('{{ $status }}')"
         x-init="initWizard()">
        
        <div class="w-full max-w-3xl">
            
            <!-- Welcome Header -->
            <div class="text-center mb-12 animate-fade-in translate-y-0 opacity-100">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full text-xs font-black uppercase tracking-[0.2em] mb-6 border border-emerald-100 shadow-sm">
                    <i class="fa-solid fa-sparkles"></i> One step away
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight leading-tight">
                    <span class="onboarding-gradient-text">{{ __('onboarding.welcome_title') }}</span>
                </h1>
                <p class="text-slate-500 text-base md:text-lg font-medium max-w-xl mx-auto">
                    {{ __('onboarding.welcome_subtitle') }}
                </p>
            </div>

            <!-- Minimal Progress Line -->
            <div class="mb-12">
                <div class="grid grid-cols-4 gap-4 px-2">
                    <template x-for="(stepObj, index) in steps" :key="index">
                        <div class="space-y-3 group cursor-default">
                            <div class="h-1.5 rounded-full overflow-hidden bg-slate-100 relative shadow-inner">
                                <div class="h-full bg-emerald-500 transition-all duration-700 ease-out shadow-[0_0_12px_rgba(34,197,94,0.3)]"
                                     :class="currentStepIndex >= index ? 'step-line-active' : ''"
                                     :style="'width: ' + (currentStepIndex > index ? '100%' : (currentStepIndex === index ? '75%' : '0%'))">
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest transition-colors duration-300"
                                      :class="currentStepIndex >= index ? 'text-emerald-600' : 'text-slate-300'"
                                      x-text="stepObj.label">
                                </span>
                                <span class="text-[10px] font-bold text-slate-400" x-text="'0' + (index + 1)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Elevated Wizard Card -->
            <div class="bg-white/90 backdrop-blur-3xl rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(15,23,42,0.06)] border border-white overflow-hidden relative animate-scale-in">
                
                <!-- Loading Overlay -->
                <div x-show="loading" 
                     x-transition.opacity 
                     class="absolute inset-0 bg-white/60 backdrop-blur-md z-50 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 border-[3px] border-brand-primary/10 border-t-brand-primary rounded-full animate-spin"></div>
                    <p class="mt-4 text-slate-900 font-bold text-sm tracking-tight" x-text="'{{ __('onboarding.loading') }}'"></p>
                </div>

                <div class="p-8 md:p-14">
                    <!-- STEP 1: Core Settings -->
                    <div x-show="currentStep === 'step_1'" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-10">
                        
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50">
                                <i class="fa-solid fa-earth-africa text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_1.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm">{{ __('onboarding.step_1.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_1')" class="space-y-8">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_1.currency_label') }}</label>
                                <select x-model="formData.step_1.currency" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all hover:bg-white focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                    <option value="EGP">{{ __('onboarding.currencies.egp') }}</option>
                                    <option value="SAR">{{ __('onboarding.currencies.sar') }}</option>
                                    <option value="AED">{{ __('onboarding.currencies.aed') }}</option>
                                    <option value="USD">{{ __('onboarding.currencies.usd') }}</option>
                                    <option value="EUR">{{ __('onboarding.currencies.eur') }}</option>
                                </select>
                            </div>
                            
                            <div class="pt-6 flex justify-end">
                                <button type="submit" class="group bg-emerald-500 hover:bg-emerald-600 text-white px-10 py-5 rounded-2xl font-black text-base transition-all shadow-xl shadow-emerald-500/20 flex items-center gap-3 transform hover:-translate-y-1">
                                    {{ __('onboarding.step_1.btn_submit') }} 
                                    <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- STEP 2: Instructor -->
                    <div x-show="currentStep === 'step_2'" style="display: none;"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-10">
                        
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50">
                                <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_2.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm">{{ __('onboarding.step_2.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_2', false)" class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.name_label') }}</label>
                                <input type="text" x-model="formData.step_2.instructor_name" placeholder="{{ __('onboarding.step_2.name_placeholder') }}" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.specialization_label') }}</label>
                                    <input type="text" x-model="formData.step_2.instructor_specialization" placeholder="{{ __('onboarding.step_2.specialization_placeholder') }}" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.phone_label') }}</label>
                                    <input type="tel" x-model="formData.step_2.instructor_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5" required>
                                </div>
                            </div>

                            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-8">
                                    <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2 group">
                                        <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1 transition-transform"></i> {{ __('onboarding.btn_back') }}
                                    </button>
                                    <button type="button" @click="submitStep('step_2', true)" class="text-slate-300 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all">
                                        {{ __('onboarding.step_2.btn_skip') }}
                                    </button>
                                </div>
                                <button type="submit" class="group bg-emerald-500 hover:bg-emerald-600 text-white w-full sm:w-auto px-10 py-5 rounded-2xl font-black text-base transition-all shadow-xl shadow-emerald-500/20 flex justify-center items-center gap-3 transform hover:-translate-y-1">
                                    {{ __('onboarding.step_2.btn_submit') }} 
                                    <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- STEP 3: Course -->
                    <div x-show="currentStep === 'step_3'" style="display: none;"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-10">
                        
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50">
                                <i class="fa-solid fa-book-open text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_3.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm">{{ __('onboarding.step_3.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_3', false)" class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.name_label') }}</label>
                                    <input type="text" x-model="formData.step_3.course_name" placeholder="{{ __('onboarding.step_3.name_placeholder') }}" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.price_label') }}</label>
                                    <div class="relative">
                                        <input type="number" x-model="formData.step_3.price" placeholder="0.00" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5" required>
                                        <div class="absolute inset-y-0 ltr:right-6 rtl:left-6 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                                            <span x-text="formData.step_1.currency"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-black text-slate-900 text-xs uppercase tracking-widest flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-days text-brand-primary"></i> {{ __('onboarding.step_3.schedule_section') }}
                                    </h3>
                                    <button type="button" @click="formData.step_3.schedules.push({day: '0', time: '16:00', time_end: '18:00'})" class="text-[10px] font-black uppercase text-brand-primary hover:underline transition-all">
                                        + {{ __('onboarding.step_3.btn_add_schedule') }}
                                    </button>
                                </div>

                                <template x-for="(schedule, index) in formData.step_3.schedules" :key="index">
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end pb-4 border-b border-white last:border-0 last:pb-0">
                                        <div class="sm:col-span-10 grid grid-cols-3 gap-3">
                                            <select x-model="schedule.day" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold">
                                                <option value="0">{{ __('onboarding.days.0') }}</option>
                                                <option value="1">{{ __('onboarding.days.1') }}</option>
                                                <option value="2">{{ __('onboarding.days.2') }}</option>
                                                <option value="3">{{ __('onboarding.days.3') }}</option>
                                                <option value="4">{{ __('onboarding.days.4') }}</option>
                                                <option value="5">{{ __('onboarding.days.5') }}</option>
                                                <option value="6">{{ __('onboarding.days.6') }}</option>
                                            </select>
                                            <input type="time" x-model="schedule.time" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold">
                                            <input type="time" x-model="schedule.time_end" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold">
                                        </div>
                                        <div class="sm:col-span-2 flex justify-end">
                                            <button type="button" x-show="formData.step_3.schedules.length > 1" @click="formData.step_3.schedules.splice(index, 1)" class="w-10 h-10 rounded-xl bg-white text-red-400 hover:text-red-500 hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-8">
                                    <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2 group">
                                        <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1 transition-transform"></i> {{ __('onboarding.btn_back') }}
                                    </button>
                                    <button type="button" @click="submitStep('step_3', true)" class="text-slate-300 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all">
                                        {{ __('onboarding.step_3.btn_skip') }}
                                    </button>
                                </div>
                                <button type="submit" class="group bg-emerald-500 hover:bg-emerald-600 text-white w-full sm:w-auto px-10 py-5 rounded-2xl font-black text-base transition-all shadow-xl shadow-emerald-500/20 flex justify-center items-center gap-3 transform hover:-translate-y-1">
                                    {{ __('onboarding.step_3.btn_submit') }} 
                                    <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- STEP 4: Student -->
                    <div x-show="currentStep === 'step_4'" style="display: none;"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-10">
                        
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50">
                                <i class="fa-solid fa-user-graduate text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_4.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm">{{ __('onboarding.step_4.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_4', false)" class="space-y-8">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.name_label') }}</label>
                                    <input type="text" x-model="formData.step_4.student_name" placeholder="{{ __('onboarding.step_4.name_placeholder') }}" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.phone_label') }}</label>
                                    <input type="text" x-model="formData.step_4.student_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5" required>
                                </div>
                            </div>

                            <div class="p-6 bg-emerald-50/50 rounded-2xl border-2 border-emerald-100/50 flex items-center gap-4 transition-all hover:bg-emerald-50 group cursor-pointer" @click="formData.step_4.enroll_in_course = !formData.step_4.enroll_in_course">
                                <div class="relative flex items-center">
                                    <input type="checkbox" x-model="formData.step_4.enroll_in_course" class="w-6 h-6 rounded-lg border-emerald-200 text-emerald-500 focus:ring-emerald-500 cursor-pointer transition-all">
                                </div>
                                <label class="text-sm font-bold text-slate-800 cursor-pointer select-none">
                                    {{ __('onboarding.step_4.enroll_checkbox', ['course' => '']) }}
                                    <span class="text-emerald-600 font-black" x-text="formData.step_3.course_name"></span>
                                </label>
                            </div>

                            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-8">
                                    <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2 group">
                                        <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1 transition-transform"></i> {{ __('onboarding.btn_back') }}
                                    </button>
                                    <button type="button" @click="submitStep('step_4', true)" class="text-slate-300 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all">
                                        {{ __('onboarding.step_4.btn_skip') }}
                                    </button>
                                </div>
                                <button type="submit" class="group bg-emerald-500 hover:bg-emerald-600 text-white w-full sm:w-auto px-12 py-6 rounded-[2rem] font-black text-2xl transition-all shadow-[0_20px_50px_-12px_rgba(16,185,129,0.35)] flex justify-center items-center gap-4 transform hover:-translate-y-2 overflow-hidden">
                                    <span class="relative z-10 flex items-center gap-4">
                                        <i class="fa-solid fa-rocket text-white animate-bounce-subtle"></i>
                                        {{ __('onboarding.step_4.btn_submit') }}
                                        <i class="fa-solid fa-chevron-right rtl:rotate-180 group-hover:translate-x-2 transition-transform"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
            <!-- Secure Footer & Language -->
            <div class="text-center mt-12 animate-fade-in opacity-80 hover:opacity-100 transition-opacity flex flex-col items-center gap-6">
                <!-- Language Switcher (Now at the bottom) -->
                <div class="flex items-center gap-1 p-1 bg-white shadow-sm border border-slate-100 rounded-2xl">
                    <button @click="updateLanguage('ar')" 
                            :class="formData.step_1.locale === 'ar' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-50'" 
                            class="px-4 py-2 rounded-xl text-[10px] font-black transition-all flex items-center gap-2">
                        <span class="text-sm">🇸🇦</span> العربية
                    </button>
                    <button @click="updateLanguage('fr')" 
                            :class="formData.step_1.locale === 'fr' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-50'" 
                            class="px-4 py-2 rounded-xl text-[10px] font-black transition-all flex items-center gap-2">
                        <span class="text-sm">🇫🇷</span> Français
                    </button>
                </div>

                <p class="inline-flex items-center gap-3 px-6 py-2 rounded-full text-slate-300 text-[10px] font-black uppercase tracking-widest">
                    <i class="fa-solid fa-shield-halved text-emerald-500 text-sm"></i> {{ __('onboarding.security_note') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Alpine Onboarding Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('onboardingWizard', (initialStatus) => ({
                currentStep: (new URLSearchParams(window.location.search).get('step')) || (initialStatus === 'pending' ? 'step_1' : initialStatus),
                loading: false,
                
                steps: [
                    { id: 'step_1', label: '{{ __('onboarding.steps.step_1') }}' },
                    { id: 'step_2', label: '{{ __('onboarding.steps.step_2') }}' },
                    { id: 'step_3', label: '{{ __('onboarding.steps.step_3') }}' },
                    { id: 'step_4', label: '{{ __('onboarding.steps.step_4') }}' }
                ],
                
                formData: {
                    step_1: { locale: '{{ app()->getLocale() }}', currency: '{{ $tenant->settings['currency'] ?? session('suggested_currency', 'EGP') }}' },
                    step_2: { instructor_name: '', instructor_phone: '', instructor_specialization: '', instructor_email: '' },
                    step_3: { course_name: '', price: '', sessions_count: '1', schedules: [{day: '0', time: '16:00', time_end: '18:00'}] },
                    step_4: { student_name: '', student_phone: '', enroll_in_course: true }
                },
                
                syncSchedules(count) {
                    const n = parseInt(count) || 0;
                    if (n < 1) return;
                    const finalCount = Math.min(n, 12);
                    const currentCount = this.formData.step_3.schedules.length;
                    if (finalCount > currentCount) {
                        for (let i = 0; i < (finalCount - currentCount); i++) {
                            this.formData.step_3.schedules.push({day: '0', time: '16:00', time_end: '18:00'});
                        }
                    } else if (finalCount < currentCount) {
                        this.formData.step_3.schedules.splice(finalCount);
                    }
                },

                get currentStepIndex() {
                    return this.steps.findIndex(s => s.id === this.currentStep);
                },

                prevStep() {
                    const currentIndex = this.currentStepIndex;
                    if (currentIndex > 0) {
                        this.currentStep = this.steps[currentIndex - 1].id;
                        this.updateUrl();
                    }
                },

                updateUrl() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('step', this.currentStep);
                    window.history.pushState({}, '', url);
                },

                initWizard() {
                    if (!this.steps.find(s => s.id === this.currentStep)) {
                        this.currentStep = 'step_1';
                    }
                },
                
                async updateLanguage(locale) {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('center.onboarding.update-locale') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ locale: locale })
                        });
                        if (response.ok) {
                            window.location.reload();
                        }
                    } catch (error) { console.error(error); } 
                    finally { this.loading = false; }
                },

                async submitStep(stepId, skip = false) {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('center.onboarding.submit') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ _token: '{{ csrf_token() }}', step: stepId, skip: skip, ...this.formData[stepId] })
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Error');
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.next_step) {
                            this.currentStep = data.next_step;
                            this.updateUrl();
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: error.message, confirmButtonColor: '#10b981' });
                    } finally { this.loading = false; }
                }
            }));
        });
    </script>
</body>
</html>
