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
    
    <link rel="stylesheet" href="{{ asset('css/landing-new.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .onboarding-gradient-text {
            /* Premium Slate Gradient for Title */
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .step-line-active {
            box-shadow: 0 0 8px rgba(5, 150, 105, 0.25);
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
            <div class="mb-12" x-show="currentStep !== 'welcome'" x-cloak>
                <div class="grid grid-cols-4 gap-4 px-2">
                    <template x-for="(stepObj, index) in steps" :key="index">
                        <div class="space-y-3 group cursor-default">
                            <div class="h-1.5 rounded-full overflow-hidden bg-slate-100 relative shadow-inner">
                                <div class="h-full bg-emerald-600 transition-all duration-700 ease-out shadow-[0_0_10px_rgba(5,150,105,0.3)]"
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
                    <!-- WELCOME STEP -->
                    <div x-show="currentStep === 'welcome'" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-8 text-center" x-cloak>
                        
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100">
                                <i class="fa-solid fa-hand-sparkles text-4xl"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('onboarding.welcome_step.title', ['name' => auth()->user()->name]) }}مرحباً بك في منصتك التعليمية!</h2>
                        <p class="text-slate-500 font-medium text-lg max-w-lg mx-auto leading-relaxed">
                            نحن سعداء جداً بانضمامك إلينا! لقد قمنا بتهيئة مساحة العمل الخاصة بك بنجاح. 
                            الآن، ومن أجل إعداد المنصة لتناسب احتياجات مركزك وطلابك بشكل مثالي، يرجى إكمال هذه الخطوات السريعة.
                        </p>
                        
                        <div class="pt-8 flex justify-center">
                            <button type="button" @click="currentStep = 'step_1'; updateUrl()" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white px-12 py-5 rounded-[2rem] font-black text-xl transition-all shadow-xl shadow-emerald-600/20 flex items-center gap-4 transform hover:-translate-y-2">
                                إبدأ إعداد المنصة 
                                <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 1: Core Settings -->
                    <div x-show="currentStep === 'step_1'" style="display: none;"
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_1.education_system_label') }}</label>
                                    <select x-model="formData.step_1.education_system" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all hover:bg-white focus:bg-white focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                        @foreach(__('onboarding.education_systems') as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="pt-6 flex justify-end">
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white px-10 py-5 rounded-2xl font-black text-base transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-3 transform hover:-translate-y-1">
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
                            <div class="space-y-6">
                                <template x-for="(instructor, index) in formData.step_2.instructors" :key="index">
                                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 relative">
                                        <div class="absolute top-4 rtl:left-4 ltr:right-4">
                                            <button type="button" x-show="formData.step_2.instructors.length > 1" @click="formData.step_2.instructors.splice(index, 1)" class="w-8 h-8 rounded-full bg-white text-red-400 hover:text-red-500 hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="font-black text-slate-900 text-xs uppercase tracking-widest flex items-center gap-2">
                                                <i class="fa-solid fa-user text-brand-primary"></i> المدرس <span x-text="index + 1"></span>
                                            </h3>
                                        </div>
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.name_label') }}</label>
                                                    <input type="text" x-model="instructor.instructor_name" placeholder="{{ __('onboarding.step_2.name_placeholder') }}" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.email_label') }}</label>
                                                    <input type="email" x-model="instructor.instructor_email" placeholder="{{ __('onboarding.step_2.email_placeholder') }}" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.specialization_label') }}</label>
                                                    <input type="text" x-model="instructor.instructor_specialization" placeholder="{{ __('onboarding.step_2.specialization_placeholder') }}" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.phone_label') }}</label>
                                                    <input type="tel" x-model="instructor.instructor_phone" @input="instructor.instructor_phone = $event.target.value.replace(/[^0-9\+\-\(\)\s]/g, '')" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">نوع العمولة</label>
                                                    <select x-model="instructor.commission_type" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer" required>
                                                        <option value="percentage">نسبة مئوية (%)</option>
                                                        <option value="fixed">مبلغ ثابت</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">قيمة العمولة</label>
                                                    <input type="number" step="0.01" min="0" x-model="instructor.commission_rate" placeholder="0.00" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <div class="flex justify-center">
                                    <button type="button" @click="formData.step_2.instructors.push({ instructor_name: '', instructor_phone: '', instructor_specialization: '', instructor_email: '', commission_type: 'percentage', commission_rate: '0' })" class="text-xs font-black uppercase text-brand-primary hover:underline transition-all flex items-center gap-2">
                                        <i class="fa-solid fa-plus"></i> إضافة مدرس آخر
                                    </button>
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
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white w-full sm:w-auto px-10 py-5 rounded-2xl font-black text-base transition-all shadow-lg shadow-emerald-600/20 flex justify-center items-center gap-3 transform hover:-translate-y-1">
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
                            <div class="space-y-6">
                                <template x-for="(course, courseIndex) in formData.step_3.courses" :key="courseIndex">
                                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 relative">
                                        <div class="absolute top-4 rtl:left-4 ltr:right-4">
                                            <button type="button" x-show="formData.step_3.courses.length > 1" @click="formData.step_3.courses.splice(courseIndex, 1)" class="w-8 h-8 rounded-full bg-white text-red-400 hover:text-red-500 hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="font-black text-slate-900 text-xs uppercase tracking-widest flex items-center gap-2">
                                                <i class="fa-solid fa-book text-brand-primary"></i> الدورة <span x-text="courseIndex + 1"></span>
                                            </h3>
                                        </div>

                                        <div class="space-y-6">
                                            <div x-show="formData.step_2.instructors.length > 0">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">اختر المدرس</label>
                                                <select x-model="course.instructor_index" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                                    <template x-for="(instructor, index) in formData.step_2.instructors" :key="index">
                                                        <option :value="index" x-text="instructor.instructor_name || 'مدرس ' + (index + 1)"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.name_label') }}</label>
                                                    <input type="text" x-model="course.course_name" placeholder="{{ __('onboarding.step_3.name_placeholder') }}" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_3.price_label') }}</label>
                                                    <div class="relative">
                                                        <input type="number" x-model="course.price" placeholder="0.00" min="0" step="0.01" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                        <div class="absolute inset-y-0 ltr:right-6 rtl:left-6 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                                                            <span x-text="formData.step_1.currency"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-6 bg-white rounded-2xl border border-slate-100 space-y-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h3 class="font-black text-slate-900 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                                        <i class="fa-solid fa-calendar-days text-brand-primary"></i> {{ __('onboarding.step_3.schedule_section') }}
                                                    </h3>
                                                    <button type="button" @click="course.schedules.push({day: '0', time: '16:00', time_end: '18:00'})" class="text-[10px] font-black uppercase text-brand-primary hover:underline transition-all">
                                                        + {{ __('onboarding.step_3.btn_add_schedule') }}
                                                    </button>
                                                </div>

                                                <template x-for="(schedule, sIndex) in course.schedules" :key="sIndex">
                                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                                                        <div class="sm:col-span-10 grid grid-cols-3 gap-3">
                                                            <select x-model="schedule.day" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 text-xs font-bold">
                                                                <option value="0">{{ __('onboarding.days.0') }}</option>
                                                                <option value="1">{{ __('onboarding.days.1') }}</option>
                                                                <option value="2">{{ __('onboarding.days.2') }}</option>
                                                                <option value="3">{{ __('onboarding.days.3') }}</option>
                                                                <option value="4">{{ __('onboarding.days.4') }}</option>
                                                                <option value="5">{{ __('onboarding.days.5') }}</option>
                                                                <option value="6">{{ __('onboarding.days.6') }}</option>
                                                            </select>
                                                            <input type="time" x-model="schedule.time" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 text-xs font-bold">
                                                            <input type="time" x-model="schedule.time_end" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 text-xs font-bold">
                                                        </div>
                                                        <div class="sm:col-span-2 flex justify-end">
                                                            <button type="button" x-show="course.schedules.length > 1" @click="course.schedules.splice(sIndex, 1)" class="w-10 h-10 rounded-xl bg-slate-50 text-red-400 hover:text-red-500 hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div class="flex justify-center">
                                    <button type="button" @click="formData.step_3.courses.push({ instructor_index: '0', course_name: '', price: '', sessions_count: '1', schedules: [{day: '0', time: '16:00', time_end: '18:00'}] })" class="text-xs font-black uppercase text-brand-primary hover:underline transition-all flex items-center gap-2">
                                        <i class="fa-solid fa-plus"></i> إضافة دورة أخرى
                                    </button>
                                </div>
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
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white w-full sm:w-auto px-10 py-5 rounded-2xl font-black text-base transition-all shadow-lg shadow-emerald-600/20 flex justify-center items-center gap-3 transform hover:-translate-y-1">
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
                            <div class="space-y-6">
                                <template x-for="(student, index) in formData.step_4.students" :key="index">
                                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 relative">
                                        <div class="absolute top-4 rtl:left-4 ltr:right-4">
                                            <button type="button" x-show="formData.step_4.students.length > 1" @click="formData.step_4.students.splice(index, 1)" class="w-8 h-8 rounded-full bg-white text-red-400 hover:text-red-500 hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="font-black text-slate-900 text-xs uppercase tracking-widest flex items-center gap-2">
                                                <i class="fa-solid fa-user-graduate text-brand-primary"></i> الطالب <span x-text="index + 1"></span>
                                            </h3>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.name_label') }}</label>
                                            <input type="text" x-model="student.student_name" placeholder="{{ __('onboarding.step_4.name_placeholder') }}" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">البريد الإلكتروني للطلاب (اختياري)</label>
                                                <input type="email" x-model="student.student_email" placeholder="example@email.com" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.phone_label') }}</label>
                                                <input type="text" x-model="student.student_phone" @input="student.student_phone = $event.target.value.replace(/[^0-9\+\-\(\)\s]/g, '')" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">اسم ولي الأمر (اختياري)</label>
                                            <input type="text" x-model="student.parent_name" placeholder="مثال: محمد أحمد" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">البريد الإلكتروني لولي الأمر (اختياري)</label>
                                                <input type="email" x-model="student.parent_email" placeholder="parent@email.com" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">رقم هاتف ولي الأمر (اختياري)</label>
                                                <input type="text" x-model="student.parent_phone" @input="student.parent_phone = $event.target.value.replace(/[^0-9\+\-\(\)\s]/g, '')" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_4.grade_label') }}</label>
                                            <select x-model="student.grade_id" class="w-full bg-white border-2 border-white rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 h-14 px-6 text-base font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                                <option value="">{{ __('onboarding.step_4.grade_label') }}...</option>
                                                @foreach($stages as $stage)
                                                    <optgroup label="{{ $stage->name }}">
                                                        @foreach($stage->grades as $grade)
                                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="p-6 bg-emerald-50/50 rounded-2xl border-2 border-emerald-100/50 flex flex-col gap-3 transition-all hover:bg-emerald-50">
                                            <label class="text-sm font-bold text-slate-800 select-none">
                                                اختر الدورات التي ترغب بتسجيل الطالب بها (اختياري)
                                            </label>
                                            <div class="space-y-2">
                                                <template x-for="(course, idx) in formData.step_3.courses" :key="idx">
                                                    <label class="flex items-center gap-3 p-3 bg-white rounded-xl border-2 border-white hover:border-emerald-200 transition-all cursor-pointer select-none" :class="student.enroll_course_indices.includes(idx) ? 'border-emerald-400 bg-emerald-50/50' : ''">
                                                        <input type="checkbox" :value="idx" @change="toggleCourseEnroll(student, idx)" :checked="student.enroll_course_indices.includes(idx)" class="w-5 h-5 rounded-md border-2 border-slate-300 text-emerald-500 focus:ring-emerald-500 transition-all">
                                                        <span class="text-sm font-bold text-slate-700" x-text="course.course_name || 'الدورة ' + (idx + 1)"></span>
                                                        <span class="text-xs text-slate-400 mr-auto" x-show="course.price" x-text="course.price + ' ' + formData.step_1.currency"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="formData.step_4.students.push({ student_name: '', student_email: '', student_phone: '', parent_name: '', parent_phone: '', parent_email: '', grade_id: '', enroll_course_indices: [] })" class="w-full py-4 border-2 border-dashed border-emerald-200 rounded-2xl text-emerald-500 font-bold hover:bg-emerald-50 hover:border-emerald-300 transition-all flex justify-center items-center gap-2">
                                    <i class="fa-solid fa-plus"></i> إضافة طالب آخر
                                </button>
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
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white w-full sm:w-auto px-12 py-6 rounded-[2rem] font-black text-2xl transition-all shadow-lg shadow-emerald-600/25 flex justify-center items-center gap-4 transform hover:-translate-y-2 overflow-hidden">
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
                            :class="formData.step_1.locale === 'ar' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-400 hover:bg-slate-50'" 
                            class="px-4 py-2 rounded-xl text-[10px] font-black transition-all flex items-center gap-2">
                        <span class="text-sm">🇸🇦</span> العربية
                    </button>
                    <button @click="updateLanguage('fr')" 
                            :class="formData.step_1.locale === 'fr' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-400 hover:bg-slate-50'" 
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
                currentStep: (new URLSearchParams(window.location.search).get('step')) || (initialStatus === 'pending' ? 'welcome' : initialStatus),
                loading: false,
                init() {
                    this.initWizard();
                },
                
                steps: [
                    { id: 'step_1', label: '{{ __('onboarding.steps.step_1') }}' },
                    { id: 'step_2', label: '{{ __('onboarding.steps.step_2') }}' },
                    { id: 'step_3', label: '{{ __('onboarding.steps.step_3') }}' },
                    { id: 'step_4', label: '{{ __('onboarding.steps.step_4') }}' }
                ],
                
                formData: {
                    step_1: { 
                        locale: '{{ app()->getLocale() }}', 
                        currency: '{{ $tenant->settings['currency'] ?? session('suggested_currency', 'EGP') }}',
                        education_system: '{{ $tenant->settings['education_system'] ?? 'egyptian_national' }}'
                    },
                    step_2: { instructors: [{ instructor_name: '', instructor_phone: '', instructor_specialization: '', instructor_email: '', commission_type: 'percentage', commission_rate: '0' }] },
                    step_3: { courses: [{ instructor_index: '0', course_name: '', price: '', sessions_count: '1', schedules: [{day: '0', time: '16:00', time_end: '18:00'}] }] },
                    step_4: { students: [{ student_name: '', student_email: '', student_phone: '', parent_name: '', parent_phone: '', parent_email: '', grade_id: '', enroll_course_indices: [] }] }
                },
                
                syncSchedules(count) {
                    const n = parseInt(count) || 0;
                    if (n < 1) return;
                    const finalCount = Math.min(n, 12);
                    const currentCount = this.formData.step_3.courses[0].schedules.length;
                    if (finalCount > currentCount) {
                        for (let i = 0; i < (finalCount - currentCount); i++) {
                            this.formData.step_3.courses[0].schedules.push({day: '0', time: '16:00', time_end: '18:00'});
                        }
                    } else if (finalCount < currentCount) {
                        this.formData.step_3.courses[0].schedules.splice(finalCount);
                    }
                },

                toggleCourseEnroll(student, idx) {
                    if (!Array.isArray(student.enroll_course_indices)) {
                        student.enroll_course_indices = [];
                    }
                    const pos = student.enroll_course_indices.indexOf(idx);
                    if (pos === -1) {
                        student.enroll_course_indices.push(idx);
                    } else {
                        student.enroll_course_indices.splice(pos, 1);
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
                    const savedInstructors = @json($existingInstructors ?? []);
                    if (savedInstructors.length > 0) {
                        this.formData.step_2.instructors = savedInstructors;
                    }

                    const savedCourses = @json($existingCourses ?? []);
                    if (savedCourses.length > 0) {
                        this.formData.step_3.courses = savedCourses;
                    }

                    const savedStudents = @json($existingStudents ?? []);
                    if (savedStudents.length > 0) {
                        this.formData.step_4.students = savedStudents;
                    }

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
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const response = await fetch('{{ route('center.onboarding.submit') }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ step: stepId, skip: skip, ...this.formData[stepId] })
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
                        let errorMessage = error.message;
                        let errorTitle = '{{ app()->getLocale() === 'ar' ? 'عذراً، حدث خطأ' : (app()->getLocale() === 'fr' ? 'Oups, une erreur est survenue' : 'Oops, an error occurred') }}';
                        
                        if (errorMessage.includes('CSRF token mismatch') || errorMessage.includes('419')) {
                            errorMessage = '{{ app()->getLocale() === 'ar' ? 'انتهت مدة الجلسة بسبب عدم النشاط. يرجى تحديث الصفحة والمحاولة مرة أخرى.' : (app()->getLocale() === 'fr' ? 'La session a expiré pour cause d\'inactivité. Veuillez actualiser la page et réessayer.' : 'Session expired due to inactivity. Please refresh the page and try again.') }}';
                        }
                        
                        Swal.fire({ 
                            icon: 'error', 
                            title: errorTitle, 
                            text: errorMessage, 
                            confirmButtonColor: '#10b981',
                            confirmButtonText: '{{ app()->getLocale() === 'ar' ? 'حسناً' : 'OK' }}'
                        });
                    } finally { this.loading = false; }
                }
            }));
        });
    </script>
</body>
</html>
