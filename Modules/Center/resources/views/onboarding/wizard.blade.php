@extends('layouts.landing-new')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-white py-12 px-4 pt-32 ltr:font-sans rtl:font-arabic" 
     x-data="onboardingWizard('{{ $status }}')"
     x-init="initWizard()">
    
    <!-- Hero Orbs Decoration -->
    <div class="hero-orb orb-1 opacity-60"></div>
    <div class="hero-orb orb-2 opacity-40"></div>

    <div class="w-full max-w-4xl relative z-20">
        
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-brand-primary/10 rounded-3xl mb-6 shadow-inner animate-bounce-slow">
                <i class="fa-solid fa-rocket text-4xl text-brand-primary"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight leading-tight">
                <span class="gradient-text">{{ __('onboarding.welcome_title') }}</span>
            </h1>
            <p class="text-slate-500 text-lg md:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
                {{ __('onboarding.welcome_subtitle') }}
            </p>
        </div>

        <!-- Progress Bar (Premium Style) -->
        <div class="mb-12 max-w-3xl mx-auto">
            <div class="flex justify-between items-center relative">
                <!-- Connecting Line -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-100 rounded-full z-0">
                    <div class="h-full bg-brand-primary transition-all duration-700 ease-in-out shadow-[0_0_15px_rgba(58,12,163,0.3)]"
                         :style="'width: ' + ((currentStepIndex / (steps.length - 1)) * 100) + '%'">
                    </div>
                </div>

                <!-- Step Orbs -->
                <template x-for="(stepObj, index) in steps" :key="index">
                    <div class="relative z-10 flex flex-col items-center group">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-500 font-bold text-sm shadow-xl"
                             :class="currentStepIndex >= index ? 'bg-brand-primary text-white scale-110' : 'bg-white text-slate-400 border border-slate-100'">
                            <span x-show="currentStepIndex > index"><i class="fa-solid fa-check text-xs"></i></span>
                            <span x-show="currentStepIndex <= index" x-text="index + 1"></span>
                        </div>
                        <div class="absolute -bottom-8 whitespace-nowrap text-[10px] font-black uppercase tracking-widest transition-colors duration-300"
                             :class="currentStepIndex >= index ? 'text-brand-primary' : 'text-slate-400'"
                             x-text="stepObj.label">
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Wizard Card (Glass Premium) -->
        <div class="glass-premium rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-white/60 overflow-hidden relative min-h-[500px] mt-16 animate-scale-in">
            
            <!-- Loading Overlay -->
            <div x-show="loading" 
                 x-transition.opacity 
                 class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center">
                <div class="w-16 h-16 border-4 border-brand-primary/20 border-t-brand-primary rounded-full animate-spin"></div>
                <p class="mt-6 text-slate-900 font-black text-xl tracking-tight" x-text="'{{ __('onboarding.loading') }}'"></p>
            </div>

            <!-- Language Switcher (Pill Style) -->
            <div class="p-6 bg-white/30 border-b border-white/60 backdrop-blur-sm flex justify-center gap-4">
                <button @click="updateLanguage('ar')" 
                        :class="formData.step_1.locale === 'ar' ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-white/50 text-slate-500 hover:bg-white transition-all'" 
                        class="px-5 py-2.5 rounded-2xl text-sm font-black transition-all flex items-center gap-2">
                    <span class="text-base">🇸🇦</span> العربية
                </button>
                <button @click="updateLanguage('en')" 
                        :class="formData.step_1.locale === 'en' ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-white/50 text-slate-500 hover:bg-white transition-all'" 
                        class="px-5 py-2.5 rounded-2xl text-sm font-black transition-all flex items-center gap-2">
                    <span class="text-base">🇬🇧</span> English
                </button>
                <button @click="updateLanguage('fr')" 
                        :class="formData.step_1.locale === 'fr' ? 'bg-slate-900 text-white shadow-xl scale-105' : 'bg-white/50 text-slate-500 hover:bg-white transition-all'" 
                        class="px-5 py-2.5 rounded-2xl text-sm font-black transition-all flex items-center gap-2">
                    <span class="text-base">🇫🇷</span> Français
                </button>
            </div>

            <div class="p-8 md:p-14">
                <!-- STEP 1: Core Settings -->
                <div x-show="currentStep === 'step_1'" 
                     x-transition:enter="transition ease-out duration-500 transform"
                     x-transition:enter-start="opacity-0 translate-y-12"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-10">
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-brand-primary/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-brand-primary shadow-inner">
                            <i class="fa-solid fa-earth-africa text-3xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_1.title') }}</h2>
                        <p class="text-slate-500 mt-2 text-lg font-medium">{{ __('onboarding.step_1.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_1')" class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_1.currency_label') }}</label>
                            <div class="relative">
                                <select x-model="formData.step_1.currency" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white">
                                    <option value="EGP">{{ __('onboarding.currencies.egp') }}</option>
                                    <option value="SAR">{{ __('onboarding.currencies.sar') }}</option>
                                    <option value="AED">{{ __('onboarding.currencies.aed') }}</option>
                                    <option value="USD">{{ __('onboarding.currencies.usd') }}</option>
                                    <option value="EUR">{{ __('onboarding.currencies.eur') }}</option>
                                </select>
                                <div class="absolute inset-y-0 ltr:right-6 rtl:left-6 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-slate-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-8 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="group bg-slate-900 hover:bg-slate-800 text-white px-10 py-5 rounded-[1.5rem] font-black text-lg transition-all shadow-2xl hover:shadow-slate-900/40 flex items-center gap-3 transform hover:-translate-y-1">
                                {{ __('onboarding.step_1.btn_submit') }} 
                                <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 2: First Instructor -->
                <div x-show="currentStep === 'step_2'" style="display: none;"
                     x-transition:enter="transition ease-out duration-500 transform"
                     x-transition:enter-start="opacity-0 translate-y-12"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-10">
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-brand-primary/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-brand-primary shadow-inner">
                            <i class="fa-solid fa-graduation-cap text-3xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_2.title') }}</h2>
                        <p class="text-slate-500 mt-2 text-lg font-medium">{{ __('onboarding.step_2.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_2', false)" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.name_label') }}</label>
                            <input type="text" x-model="formData.step_2.instructor_name" placeholder="{{ __('onboarding.step_2.name_placeholder') }}" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white" required>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.specialization_label') }}</label>
                                <input type="text" x-model="formData.step_2.instructor_specialization" placeholder="{{ __('onboarding.step_2.specialization_placeholder') }}" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.phone_label') }}</label>
                                <input type="tel" x-model="formData.step_2.instructor_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white text-right" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_2.email_label') }}</label>
                            <input type="email" x-model="formData.step_2.instructor_email" dir="ltr" placeholder="{{ __('onboarding.step_2.email_placeholder') }}" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white text-right">
                        </div>

                        <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-8">
                                <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-black uppercase tracking-widest text-[10px] transition-all flex items-center gap-3 group">
                                    <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1.5 transition-transform"></i> 
                                    {{ __('onboarding.btn_back') }}
                                </button>
                                <button type="button" @click="submitStep('step_2', true)" class="text-slate-400 hover:text-slate-900 font-black uppercase tracking-widest text-[10px] transition-all">
                                    {{ __('onboarding.step_2.btn_skip') }}
                                </button>
                            </div>
                            <button type="submit" class="group bg-slate-900 hover:bg-slate-800 text-white w-full sm:w-auto px-10 py-5 rounded-[1.5rem] font-black text-xl transition-all shadow-2xl hover:shadow-slate-900/40 flex justify-center items-center gap-4 transform hover:-translate-y-1">
                                {{ __('onboarding.step_2.btn_submit') }} 
                                <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 3: First Course -->
                <div x-show="currentStep === 'step_3'" style="display: none;"
                     x-transition:enter="transition ease-out duration-500 transform"
                     x-transition:enter-start="opacity-0 translate-y-12"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-10">
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-brand-primary/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-brand-primary shadow-inner">
                            <i class="fa-solid fa-book-open text-3xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_3.title') }}</h2>
                        <p class="text-slate-500 mt-2 text-lg font-medium">{{ __('onboarding.step_3.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_3', false)" class="space-y-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.name_label') }}</label>
                                <input type="text" x-model="formData.step_3.course_name" placeholder="{{ __('onboarding.step_3.name_placeholder') }}" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.price_label') }}</label>
                                <div class="relative">
                                    <input type="number" x-model="formData.step_3.price" placeholder="0.00" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white" required>
                                    <div class="absolute inset-y-0 ltr:right-6 rtl:left-6 flex items-center pointer-events-none text-slate-400 font-bold">
                                        <span x-text="formData.step_1.currency"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.sessions_label') }}</label>
                            <input type="number" x-model="formData.step_3.sessions_count" 
                                   @input="syncSchedules($event.target.value)"
                                   placeholder="12" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white" required>
                        </div>

                        <!-- Schedule Section (Enhanced Visuals) -->
                        <div class="p-8 bg-white/50 backdrop-blur-md rounded-[2.5rem] border-2 border-brand-primary/5 space-y-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-black text-slate-900 text-base flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-brand-primary text-white flex items-center justify-center text-xs shadow-lg shadow-brand-primary/20">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                    {{ __('onboarding.step_3.schedule_section') }}
                                </h3>
                                <button type="button" @click="formData.step_3.schedules.push({day: '0', time: '16:00', time_end: '18:00'})" class="text-[10px] font-black uppercase tracking-widest text-brand-primary hover:text-brand-primary-dark flex items-center gap-2 transition-all">
                                    <i class="fa-solid fa-plus-circle text-base"></i> {{ __('onboarding.step_3.btn_add_schedule') }}
                                </button>
                            </div>

                            <template x-for="(schedule, index) in formData.step_3.schedules" :key="index">
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-end pb-8 border-b border-slate-100 last:border-0 last:pb-0 group">
                                    <div class="sm:col-span-4">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_3.day_label') }}</label>
                                        <select x-model="schedule.day" class="w-full bg-white border-2 border-slate-100 rounded-xl focus:ring-brand-primary focus:border-brand-primary h-12 px-4 font-bold text-sm">
                                            <option value="0">{{ __('onboarding.days.0') }}</option>
                                            <option value="1">{{ __('onboarding.days.1') }}</option>
                                            <option value="2">{{ __('onboarding.days.2') }}</option>
                                            <option value="3">{{ __('onboarding.days.3') }}</option>
                                            <option value="4">{{ __('onboarding.days.4') }}</option>
                                            <option value="5">{{ __('onboarding.days.5') }}</option>
                                            <option value="6">{{ __('onboarding.days.6') }}</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_3.time_label') }}</label>
                                        <input type="time" x-model="schedule.time" class="w-full bg-white border-2 border-slate-100 rounded-xl focus:ring-brand-primary focus:border-brand-primary h-12 px-4 font-bold text-sm">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_3.time_end_label') }}</label>
                                        <input type="time" x-model="schedule.time_end" class="w-full bg-white border-2 border-slate-100 rounded-xl focus:ring-brand-primary focus:border-brand-primary h-12 px-4 font-bold text-sm">
                                    </div>
                                    <div class="sm:col-span-2 flex justify-end">
                                        <button type="button" x-show="formData.step_3.schedules.length > 1" @click="formData.step_3.schedules.splice(index, 1)" class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 hover:bg-red-100 hover:scale-110 flex items-center justify-center transition-all shadow-sm" title="{{ __('onboarding.step_3.btn_remove_schedule') }}">
                                            <i class="fa-solid fa-trash-can text-base"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-8">
                                <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-black uppercase tracking-widest text-[10px] transition-all flex items-center gap-3 group">
                                    <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1.5 transition-transform"></i> 
                                    {{ __('onboarding.btn_back') }}
                                </button>
                                <button type="button" @click="submitStep('step_3', true)" class="text-slate-400 hover:text-slate-900 font-black uppercase tracking-widest text-[10px] transition-all">
                                    {{ __('onboarding.step_3.btn_skip') }}
                                </button>
                            </div>
                            <button type="submit" class="group bg-brand-primary hover:bg-brand-primary-dark text-white w-full sm:w-auto px-10 py-5 rounded-[1.5rem] font-black text-xl transition-all shadow-2xl shadow-brand-primary/20 flex justify-center items-center gap-4 transform hover:-translate-y-1">
                                {{ __('onboarding.step_3.btn_submit') }} 
                                <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 4: First Student -->
                <div x-show="currentStep === 'step_4'" style="display: none;"
                     x-transition:enter="transition ease-out duration-500 transform"
                     x-transition:enter-start="opacity-0 translate-y-12"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-10">
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-brand-primary/10 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-brand-primary shadow-inner">
                            <i class="fa-solid fa-user-graduate text-3xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_4.title') }}</h2>
                        <p class="text-slate-500 mt-2 text-lg font-medium">{{ __('onboarding.step_4.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_4', false)" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.name_label') }}</label>
                            <input type="text" x-model="formData.step_4.student_name" placeholder="{{ __('onboarding.step_4.name_placeholder') }}" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.phone_label') }}</label>
                            <input type="text" x-model="formData.step_4.student_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white/50 border-2 border-slate-100 rounded-2xl focus:ring-brand-primary focus:border-brand-primary h-14 px-6 text-lg font-bold transition-all hover:bg-white text-right" required>
                        </div>

                        <div class="p-8 bg-brand-primary/5 rounded-[2.5rem] border-2 border-brand-primary/10 flex items-center gap-6 group transition-all hover:bg-brand-primary/10">
                            <div class="relative flex items-center">
                                <input type="checkbox" x-model="formData.step_4.enroll_in_course" class="w-8 h-8 rounded-xl border-brand-primary/20 text-brand-primary focus:ring-brand-primary transition-all cursor-pointer">
                                <div class="absolute inset-0 pointer-events-none rounded-xl ring-4 ring-brand-primary/0 group-hover:ring-brand-primary/10 transition-all"></div>
                            </div>
                            <label class="text-lg font-bold text-slate-900 cursor-pointer select-none">
                                {{ __('onboarding.step_4.enroll_checkbox', ['course' => '']) }}
                                <span class="gradient-text underline" x-text="formData.step_3.course_name"></span>
                            </label>
                        </div>

                        <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-8">
                                <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-black uppercase tracking-widest text-[10px] transition-all flex items-center gap-3 group">
                                    <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1.5 transition-transform"></i> 
                                    {{ __('onboarding.btn_back') }}
                                </button>
                                <button type="button" @click="submitStep('step_4', true)" class="text-slate-400 hover:text-slate-900 font-black uppercase tracking-widest text-[10px] transition-all">
                                    {{ __('onboarding.step_4.btn_skip') }}
                                </button>
                            </div>
                            <button type="submit" class="group bg-slate-900 hover:bg-slate-800 text-white w-full sm:w-auto px-12 py-6 rounded-[2rem] font-black text-2xl transition-all shadow-2xl hover:shadow-slate-900/40 flex justify-center items-center gap-6 transform hover:-translate-y-2 overflow-hidden">
                                <span class="relative z-10 flex items-center gap-4">
                                    <i class="fa-solid fa-bolt text-brand-primary animate-pulse"></i>
                                    {{ __('onboarding.step_4.btn_submit') }}
                                    <i class="fa-solid fa-chevron-right rtl:rotate-180 group-hover:translate-x-2 transition-transform"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        
        <div class="text-center mt-12 animate-fade-in" style="animation-delay: 0.5s;">
            <p class="inline-flex items-center gap-3 bg-slate-50/50 backdrop-blur-sm px-6 py-3 rounded-full border border-slate-100 text-slate-400 text-sm font-bold">
                <i class="fa-solid fa-shield-halved text-brand-primary"></i> {{ __('onboarding.security_note') }}
            </p>
        </div>
    </div>
</div>

@push('scripts')
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
                step_1: { locale: '{{ app()->getLocale() }}', currency: 'EGP' },
                step_2: { instructor_name: '', instructor_phone: '', instructor_specialization: '', instructor_email: '' },
                step_3: { course_name: '', price: '', sessions_count: '1', schedules: [{day: '0', time: '16:00', time_end: '18:00'}] },
                step_4: { student_name: '', student_phone: '', enroll_in_course: true }
            },
            
            syncSchedules(count) {
                const n = parseInt(count) || 0;
                if (n < 1) return;
                
                // Limit to a reasonable number to avoid UI freeze
                const finalCount = Math.min(n, 20);
                
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
                }
            },

            initWizard() {
                // Ensure we don't start on an invalid step if something went wrong
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
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ locale: locale })
                    });
                    
                    if (response.ok) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('step', this.currentStep);
                        window.location.href = url.toString();
                    }
                } catch (error) {
                    console.error('Failed to update language:', error);
                } finally {
                    this.loading = false;
                }
            },

            async submitStep(stepId, skip = false) {
                if (this.loading) return;
                this.loading = true;

                try {
                    const payload = {
                        _token: '{{ csrf_token() }}',
                        step: stepId,
                        skip: skip,
                        ...this.formData[stepId]
                    };

                    const response = await fetch('{{ route('center.onboarding.submit') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'حدث خطأ غير متوقع');
                    }

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (stepId === 'step_1') {
                        // Reload page without the step param to let server-side status take over
                        window.location.href = window.location.pathname;
                    } else if (data.next_step) {
                        this.currentStep = data.next_step;
                        // Update UI and URL to prevent jumping back on refresh
                        const url = new URL(window.location.href);
                        url.searchParams.set('step', this.currentStep);
                        window.history.pushState({}, '', url);
                        
                        // Scroll to top of wizard
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('onboarding.error_title') }}',
                        text: error.message || '{{ __('onboarding.error_fallback') }}',
                        confirmButtonText: '{{ __('onboarding.btn_ok') }}',
                        confirmButtonColor: '#0f172a'
                    });
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush
@endsection
