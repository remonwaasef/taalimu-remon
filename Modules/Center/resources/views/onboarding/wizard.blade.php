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
<body class="bg-[#F8FAFC] min-h-screen text-slate-800 antialiased selection:bg-brand-primary selection:text-white overflow-hidden ltr:font-sans rtl:font-arabic">

    <div class="flex flex-col lg:flex-row h-screen w-full" x-data="onboardingWizard('{{ $status }}')" x-init="initWizard()">
        
        <!-- Sidebar (Split-Screen Left/Right) -->
        <div class="w-full lg:w-2/5 xl:w-1/3 bg-slate-900 text-white flex flex-col justify-between p-8 md:p-12 relative overflow-hidden shrink-0 z-20 lg:shadow-2xl h-auto lg:h-screen">
            <!-- Backdrop Orbs -->
            <div class="absolute -top-[10%] -right-[5%] w-[400px] h-[400px] rounded-full bg-emerald-500/20 blur-[100px] animate-pulse-slow pointer-events-none"></div>
            
            <div class="relative z-10">
                <!-- Header / Logo -->
                <div class="mb-10 lg:mb-16">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] border border-white/10 backdrop-blur-md">
                        <i class="fa-solid fa-sparkles text-emerald-400"></i> One Step Away
                    </div>
                </div>

                <!-- Welcome Text (Visible only on welcome step) -->
                <div x-show="currentStep === 'welcome'" x-transition x-cloak>
                    <h1 class="text-3xl lg:text-5xl font-black mb-6 tracking-tight leading-tight">
                        أهلاً بك <br><span class="text-emerald-400">في منصتك</span>
                    </h1>
                    <p class="text-slate-400 text-base lg:text-lg leading-relaxed">
                        نحن نجهز بيئة العمل الخاصة بك للبدء في إدارة مركزك التعليمي بسهولة واحترافية عالية.
                    </p>
                </div>

                <!-- Steps Progress (Visible when not welcome step) -->
                <div x-show="currentStep !== 'welcome'" x-transition x-cloak class="space-y-10 lg:space-y-14">
                    <div>
                        <h2 class="text-3xl lg:text-4xl font-black mb-4 tracking-tight">إعداد <span class="text-emerald-400">المنصة</span></h2>
                        <p class="text-slate-400 text-sm lg:text-base">أكمل الخطوات التالية لتهيئة منصتك بالكامل للانطلاق.</p>
                    </div>

                    <!-- Vertical Progress List -->
                    <div class="space-y-8 relative before:absolute before:inset-y-0 before:ltr:left-[19px] before:rtl:right-[19px] before:w-0.5 before:bg-slate-800 hidden lg:block">
                        <template x-for="(stepObj, index) in steps" :key="index">
                            <div class="relative flex items-center gap-6 group cursor-default">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border-4 z-10 transition-all duration-500 shadow-sm"
                                     :class="currentStepIndex > index ? 'bg-emerald-500 border-emerald-500/30 text-white' : (currentStepIndex === index ? 'bg-slate-900 border-emerald-500 text-emerald-400 scale-110' : 'bg-slate-900 border-slate-700 text-slate-500')">
                                    <i x-show="currentStepIndex > index" class="fa-solid fa-check text-sm"></i>
                                    <span x-show="currentStepIndex <= index" class="text-sm font-bold" x-text="'0' + (index + 1)"></span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm lg:text-base transition-colors" :class="currentStepIndex >= index ? 'text-white' : 'text-slate-500'" x-text="stepObj.label"></h3>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="relative z-10 mt-12 pt-8 border-t border-white/10 flex items-center justify-between">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-black flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-emerald-500/70 text-sm"></i> آمن ومشفر
                </p>
                <div class="flex gap-3">
                    <button @click="updateLanguage('ar')" :class="formData.step_1.locale === 'ar' ? 'text-white bg-white/10' : 'text-slate-400 hover:text-white'" class="px-2 py-1 rounded text-xs font-bold transition-all">عربي</button>
                    <span class="text-slate-700">|</span>
                    <button @click="updateLanguage('fr')" :class="formData.step_1.locale === 'fr' ? 'text-white bg-white/10' : 'text-slate-400 hover:text-white'" class="px-2 py-1 rounded text-xs font-bold transition-all">FR</button>
                </div>
            </div>
        </div>

        <!-- Main Form Area (Split-Screen Right/Left) -->
        <div class="w-full lg:w-3/5 xl:w-2/3 h-screen overflow-y-auto bg-slate-50 relative p-6 md:p-12 lg:p-16 flex justify-center items-start lg:items-center">
            
            <div class="w-full max-w-2xl relative z-10 pb-20 lg:pb-0">
                
                <!-- Loading Overlay -->
                <div x-show="loading" 
                     x-transition.opacity 
                     class="absolute inset-0 bg-slate-50/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-3xl">
                    <div class="w-12 h-12 border-[3px] border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin"></div>
                    <p class="mt-4 text-slate-900 font-bold text-sm tracking-tight" x-text="'{{ __('onboarding.loading') }}'"></p>
                </div>

                <!-- Forms Wrapper: Removing heavy backgrounds since it's now split screen -->
                <div class="bg-white lg:bg-transparent lg:shadow-none shadow-xl rounded-[2.5rem] lg:rounded-none p-8 lg:p-0">

                    <!-- WELCOME STEP -->
                    <div x-show="currentStep === 'welcome'" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-8 text-center" x-cloak>
                        
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50 rotate-3">
                                <i class="fa-solid fa-hand-sparkles text-4xl -rotate-3"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('onboarding.welcome_step.title', ['name' => auth()->user()->name]) }}مرحباً بك!</h2>
                        <p class="text-slate-500 font-medium text-lg leading-relaxed max-w-md mx-auto">
                            نحن سعداء جداً بانضمامك إلينا! لقد قمنا بتهيئة مساحة العمل الخاصة بك بنجاح. 
                            الآن يرجى إكمال هذه الخطوات السريعة لضبط إعدادات مركزك.
                        </p>
                        
                        <div class="pt-8 flex justify-center">
                            <button type="button" @click="currentStep = 'step_1'; updateUrl()" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white px-10 py-5 rounded-2xl font-black text-lg transition-all shadow-xl shadow-emerald-600/20 flex items-center gap-3 transform hover:-translate-y-1">
                                إبدأ الإعداد الآن
                                <i class="fa-solid fa-arrow-right rtl:rotate-180 group-hover:translate-x-1 transition-transform"></i>
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
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50 shrink-0">
                                <i class="fa-solid fa-earth-africa text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_1.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm mt-1">{{ __('onboarding.step_1.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_1')" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_1.currency_label') }}</label>
                                    <select x-model="formData.step_1.currency" class="w-full bg-slate-100 lg:bg-white border-2 border-transparent focus:border-emerald-500 rounded-2xl h-14 px-6 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                        <option value="EGP">{{ __('onboarding.currencies.egp') }}</option>
                                        <option value="SAR">{{ __('onboarding.currencies.sar') }}</option>
                                        <option value="AED">{{ __('onboarding.currencies.aed') }}</option>
                                        <option value="USD">{{ __('onboarding.currencies.usd') }}</option>
                                        <option value="EUR">{{ __('onboarding.currencies.eur') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">{{ __('onboarding.step_1.education_system_label') }}</label>
                                    <select x-model="formData.step_1.education_system" class="w-full bg-slate-100 lg:bg-white border-2 border-transparent focus:border-emerald-500 rounded-2xl h-14 px-6 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                        @foreach(__('onboarding.education_systems') as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="pt-8 flex justify-end">
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white px-10 py-4 rounded-2xl font-black text-sm transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-3 transform hover:-translate-y-1">
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
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50 shrink-0">
                                <i class="fa-solid fa-chalkboard-user text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_2.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm mt-1">{{ __('onboarding.step_2.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_2', false)" class="space-y-6">
                            <div class="space-y-6">
                                <template x-for="(instructor, index) in formData.step_2.instructors" :key="index">
                                    <div class="p-6 bg-slate-100/50 lg:bg-white rounded-3xl border border-slate-100 relative">
                                        <div class="absolute top-4 rtl:left-4 ltr:right-4">
                                            <button type="button" x-show="formData.step_2.instructors.length > 1" @click="formData.step_2.instructors.splice(index, 1)" class="w-8 h-8 rounded-full bg-slate-50 text-red-400 hover:text-red-500 hover:bg-white hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="mb-5">
                                            <h3 class="font-black text-slate-900 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                                <i class="fa-solid fa-user text-emerald-500"></i> المدرس <span x-text="index + 1"></span>
                                            </h3>
                                        </div>
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.name_label') }}</label>
                                                    <input type="text" x-model="instructor.instructor_name" placeholder="{{ __('onboarding.step_2.name_placeholder') }}" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.email_label') }}</label>
                                                    <input type="email" x-model="instructor.instructor_email" placeholder="{{ __('onboarding.step_2.email_placeholder') }}" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.specialization_label') }}</label>
                                                    <input type="text" x-model="instructor.instructor_specialization" placeholder="{{ __('onboarding.step_2.specialization_placeholder') }}" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_2.phone_label') }}</label>
                                                    <input type="tel" x-model="instructor.instructor_phone" @input="instructor.instructor_phone = $event.target.value.replace(/[^0-9\+\-\(\)\s]/g, '')" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">نوع العمولة</label>
                                                    <select x-model="instructor.commission_type" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer" required>
                                                        <option value="percentage">نسبة مئوية (%)</option>
                                                        <option value="fixed">مبلغ ثابت</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">قيمة العمولة</label>
                                                    <input type="number" step="0.01" min="0" x-model="instructor.commission_rate" placeholder="0.00" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <div class="flex justify-center">
                                    <button type="button" @click="formData.step_2.instructors.push({ instructor_name: '', instructor_phone: '', instructor_specialization: '', instructor_email: '', commission_type: 'percentage', commission_rate: '0' })" class="text-xs font-black uppercase text-emerald-600 hover:text-emerald-700 hover:underline transition-all flex items-center gap-2">
                                        <i class="fa-solid fa-plus"></i> إضافة مدرس آخر
                                    </button>
                                </div>
                            </div>

                            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-6">
                                    <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2 group">
                                        <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1 transition-transform"></i> {{ __('onboarding.btn_back') }}
                                    </button>
                                    <button type="button" @click="submitStep('step_2', true)" class="text-slate-300 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all">
                                        {{ __('onboarding.step_2.btn_skip') }}
                                    </button>
                                </div>
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white w-full sm:w-auto px-10 py-4 rounded-2xl font-black text-sm transition-all shadow-lg shadow-emerald-600/20 flex justify-center items-center gap-3 transform hover:-translate-y-1">
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
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50 shrink-0">
                                <i class="fa-solid fa-book-open text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_3.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm mt-1">{{ __('onboarding.step_3.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_3', false)" class="space-y-6">
                            <div class="space-y-6">
                                <template x-for="(course, courseIndex) in formData.step_3.courses" :key="courseIndex">
                                    <div class="p-6 bg-slate-100/50 lg:bg-white rounded-3xl border border-slate-100 relative">
                                        <div class="absolute top-4 rtl:left-4 ltr:right-4">
                                            <button type="button" x-show="formData.step_3.courses.length > 1" @click="formData.step_3.courses.splice(courseIndex, 1)" class="w-8 h-8 rounded-full bg-slate-50 text-red-400 hover:text-red-500 hover:bg-white hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="mb-5">
                                            <h3 class="font-black text-slate-900 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                                <i class="fa-solid fa-book text-emerald-500"></i> الدورة <span x-text="courseIndex + 1"></span>
                                            </h3>
                                        </div>

                                        <div class="space-y-5">
                                            <div x-show="formData.step_2.instructors.length > 0">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">اختر المدرس</label>
                                                <select x-model="course.instructor_index" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
                                                    <template x-for="(instructor, index) in formData.step_2.instructors" :key="index">
                                                        <option :value="index" x-text="instructor.instructor_name || 'مدرس ' + (index + 1)"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_3.name_label') }}</label>
                                                    <input type="text" x-model="course.course_name" placeholder="{{ __('onboarding.step_3.name_placeholder') }}" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_3.price_label') }}</label>
                                                    <div class="relative">
                                                        <input type="number" x-model="course.price" placeholder="0.00" min="0" step="0.01" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                                        <div class="absolute inset-y-0 ltr:right-5 rtl:left-5 flex items-center pointer-events-none text-slate-400 font-bold text-[10px]">
                                                            <span x-text="formData.step_1.currency"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-5 bg-white lg:bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h3 class="font-black text-slate-900 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                                        <i class="fa-solid fa-calendar-days text-emerald-500"></i> {{ __('onboarding.step_3.schedule_section') }}
                                                    </h3>
                                                    <button type="button" @click="course.schedules.push({day: '0', time: '16:00', time_end: '18:00'})" class="text-[10px] font-black uppercase text-emerald-600 hover:text-emerald-700 hover:underline transition-all">
                                                        + {{ __('onboarding.step_3.btn_add_schedule') }}
                                                    </button>
                                                </div>

                                                <template x-for="(schedule, sIndex) in course.schedules" :key="sIndex">
                                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end pb-3 border-b border-slate-100 last:border-0 last:pb-0">
                                                        <div class="sm:col-span-10 grid grid-cols-3 gap-2">
                                                            <select x-model="schedule.day" class="w-full bg-slate-50 lg:bg-white border border-slate-200 lg:border-transparent focus:border-emerald-500 rounded-lg px-3 py-2 text-xs font-bold">
                                                                <option value="0">{{ __('onboarding.days.0') }}</option>
                                                                <option value="1">{{ __('onboarding.days.1') }}</option>
                                                                <option value="2">{{ __('onboarding.days.2') }}</option>
                                                                <option value="3">{{ __('onboarding.days.3') }}</option>
                                                                <option value="4">{{ __('onboarding.days.4') }}</option>
                                                                <option value="5">{{ __('onboarding.days.5') }}</option>
                                                                <option value="6">{{ __('onboarding.days.6') }}</option>
                                                            </select>
                                                            <input type="time" x-model="schedule.time" class="w-full bg-slate-50 lg:bg-white border border-slate-200 lg:border-transparent focus:border-emerald-500 rounded-lg px-3 py-2 text-xs font-bold">
                                                            <input type="time" x-model="schedule.time_end" class="w-full bg-slate-50 lg:bg-white border border-slate-200 lg:border-transparent focus:border-emerald-500 rounded-lg px-3 py-2 text-xs font-bold">
                                                        </div>
                                                        <div class="sm:col-span-2 flex justify-end">
                                                            <button type="button" x-show="course.schedules.length > 1" @click="course.schedules.splice(sIndex, 1)" class="w-9 h-9 rounded-lg bg-white text-red-400 hover:text-red-500 hover:shadow-sm transition-all flex items-center justify-center border border-slate-200">
                                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div class="flex justify-center">
                                    <button type="button" @click="formData.step_3.courses.push({ instructor_index: '0', course_name: '', price: '', sessions_count: '1', schedules: [{day: '0', time: '16:00', time_end: '18:00'}] })" class="text-xs font-black uppercase text-emerald-600 hover:text-emerald-700 hover:underline transition-all flex items-center gap-2">
                                        <i class="fa-solid fa-plus"></i> إضافة دورة أخرى
                                    </button>
                                </div>
                            </div>

                            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div class="flex items-center gap-6">
                                    <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2 group">
                                        <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1 transition-transform"></i> {{ __('onboarding.btn_back') }}
                                    </button>
                                    <button type="button" @click="submitStep('step_3', true)" class="text-slate-300 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all">
                                        {{ __('onboarding.step_3.btn_skip') }}
                                    </button>
                                </div>
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white w-full sm:w-auto px-10 py-4 rounded-2xl font-black text-sm transition-all shadow-lg shadow-emerald-600/20 flex justify-center items-center gap-3 transform hover:-translate-y-1">
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
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner border border-emerald-100/50 shrink-0">
                                <i class="fa-solid fa-user-graduate text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('onboarding.step_4.title') }}</h2>
                                <p class="text-slate-500 font-medium text-sm mt-1">{{ __('onboarding.step_4.subtitle') }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitStep('step_4', false)" class="space-y-8">
                            <div class="space-y-6">
                                <template x-for="(student, index) in formData.step_4.students" :key="index">
                                    <div class="p-6 bg-slate-100/50 lg:bg-white rounded-3xl border border-slate-100 relative">
                                        <div class="absolute top-4 rtl:left-4 ltr:right-4">
                                            <button type="button" x-show="formData.step_4.students.length > 1" @click="formData.step_4.students.splice(index, 1)" class="w-8 h-8 rounded-full bg-slate-50 text-red-400 hover:text-red-500 hover:bg-white hover:shadow-md transition-all flex items-center justify-center border border-slate-100">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="mb-5">
                                            <h3 class="font-black text-slate-900 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                                <i class="fa-solid fa-user-graduate text-emerald-500"></i> الطالب <span x-text="index + 1"></span>
                                            </h3>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_4.name_label') }}</label>
                                            <input type="text" x-model="student.student_name" placeholder="{{ __('onboarding.step_4.name_placeholder') }}" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">البريد الإلكتروني للطلاب (اختياري)</label>
                                                <input type="email" x-model="student.student_email" placeholder="example@email.com" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_4.phone_label') }}</label>
                                                <input type="text" x-model="student.student_phone" @input="student.student_phone = $event.target.value.replace(/[^0-9\+\-\(\)\s]/g, '')" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5" required>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">اسم ولي الأمر (اختياري)</label>
                                            <input type="text" x-model="student.parent_name" placeholder="مثال: محمد أحمد" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">البريد الإلكتروني لولي الأمر (اختياري)</label>
                                                <input type="email" x-model="student.parent_email" placeholder="parent@email.com" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">رقم هاتف ولي الأمر (اختياري)</label>
                                                <input type="text" x-model="student.parent_phone" @input="student.parent_phone = $event.target.value.replace(/[^0-9\+\-\(\)\s]/g, '')" dir="ltr" placeholder="01xxxxxxxxx" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5">
                                            </div>
                                        </div>

                                        <div class="mb-5">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">{{ __('onboarding.step_4.grade_label') }}</label>
                                            <select x-model="student.grade_id" class="w-full bg-white lg:bg-slate-50 border border-transparent focus:border-emerald-500 rounded-xl h-12 px-5 text-sm font-bold transition-all focus:shadow-lg focus:shadow-emerald-500/5 appearance-none cursor-pointer">
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

                                        <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 flex flex-col gap-3 transition-all hover:bg-emerald-50">
                                            <label class="text-[10px] uppercase tracking-widest font-black text-slate-500 select-none">
                                                تسجيل الطالب في الدورات (اختياري)
                                            </label>
                                            <div class="space-y-2">
                                                <template x-for="(course, idx) in formData.step_3.courses" :key="idx">
                                                    <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-white hover:border-emerald-200 transition-all cursor-pointer select-none" :class="student.enroll_course_indices.includes(idx) ? 'border-emerald-400 shadow-sm' : ''">
                                                        <input type="checkbox" :value="idx" @change="toggleCourseEnroll(student, idx)" :checked="student.enroll_course_indices.includes(idx)" class="w-4 h-4 rounded border-2 border-slate-300 text-emerald-500 focus:ring-emerald-500 transition-all">
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
                                <div class="flex items-center gap-6">
                                    <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2 group">
                                        <i class="fa-solid fa-arrow-left rtl:rotate-180 group-hover:-translate-x-1 transition-transform"></i> {{ __('onboarding.btn_back') }}
                                    </button>
                                    <button type="button" @click="submitStep('step_4', true)" class="text-slate-300 hover:text-slate-900 font-bold uppercase tracking-widest text-[10px] transition-all">
                                        {{ __('onboarding.step_4.btn_skip') }}
                                    </button>
                                </div>
                                <button type="submit" class="group bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white w-full sm:w-auto px-10 py-5 rounded-3xl font-black text-lg transition-all shadow-xl shadow-emerald-600/25 flex justify-center items-center gap-4 transform hover:-translate-y-1 overflow-hidden">
                                    <span class="relative z-10 flex items-center gap-3">
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
