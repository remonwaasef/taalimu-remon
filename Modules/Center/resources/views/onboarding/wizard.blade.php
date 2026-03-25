<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إعداد المركز | المنصة التعليمية</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Custom Tailwind Utilities for Wizard */
        .bg-primary { background-color: #3b82f6; }
        .bg-primary-focus { background-color: #2563eb; }
        .text-primary { color: #3b82f6; }
        .ring-primary { --tw-ring-color: #3b82f6; }
        .border-primary { border-color: #3b82f6; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen text-slate-800 antialiased relative selection:bg-primary selection:text-white">
    
    <!-- Blurred Background map -->
    <div class="fixed inset-0 z-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-repeat mix-blend-overlay"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>

    <!-- Decorative Glows -->
    <div class="fixed -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-blue-600/20 blur-[100px] mix-blend-screen pointer-events-none z-0"></div>
    <div class="fixed -bottom-40 -left-40 w-[600px] h-[600px] rounded-full bg-purple-600/20 blur-[100px] mix-blend-screen pointer-events-none z-0"></div>

<div class="min-h-screen flex items-center justify-center p-4 relative z-10 overflow-hidden" 
     x-data="onboardingWizard('{{ $status }}')"
     x-init="initWizard()">
    
    <!-- Background Elements -->
    <div class="absolute -top-[500px] -right-[500px] w-[1000px] h-[1000px] rounded-full bg-primary/5 blur-3xl mix-blend-multiply pointer-events-none"></div>
    <div class="absolute -bottom-[500px] -left-[500px] w-[1000px] h-[1000px] rounded-full bg-secondary/5 blur-3xl mix-blend-multiply pointer-events-none"></div>

    <div class="w-full max-w-2xl relative z-10">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">أهلاً بك في منصتك التعليمية الجديدة 🚀</h1>
            <p class="text-slate-600">لنجعل مركزك جاهزاً للانطلاق في 4 خطوات بسيطة فقط.</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <template x-for="(stepObj, index) in steps" :key="index">
                    <div class="flex-1 text-center relative group">
                        <div class="text-xs font-semibold mb-1 transition-colors duration-300"
                             :class="currentStepIndex >= index ? 'text-primary' : 'text-slate-400'"
                             x-text="stepObj.label">
                        </div>
                        <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden mx-1">
                            <div class="h-full bg-primary transition-all duration-500 ease-out"
                                 :style="'width: ' + (currentStepIndex > index ? '100%' : (currentStepIndex === index ? '50%' : '0%'))">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Cards Container -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative min-h-[400px]">
            
            <!-- Loading Overlay -->
            <div x-show="loading" 
                 x-transition.opacity 
                 class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center">
                <div class="w-12 h-12 border-4 border-primary/30 border-t-primary rounded-full animate-spin"></div>
                <p class="mt-4 text-slate-600 font-medium">جاري معالجة البيانات...</p>
            </div>

            <div class="p-8">
                <!-- STEP 1: Core Settings -->
                <div x-show="currentStep === 'step_1'" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">
                    
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-primary">
                            <i class="fa-solid fa-gear text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">الإعدادات الأساسية</h2>
                        <p class="text-slate-500 mt-1">دعنا نبدأ بضبط خيارات عرض منصتك.</p>
                    </div>

                    <form @submit.prevent="submitStep('step_1')" class="space-y-5">
                        <!-- Language -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">لغة النظام الافتراضية</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="border rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-primary/50 relative"
                                       :class="formData.step_1.locale === 'ar' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-slate-200'">
                                    <input type="radio" x-model="formData.step_1.locale" value="ar" class="sr-only">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🇸🇦</span>
                                        <span class="font-medium text-slate-800">العربية</span>
                                    </div>
                                    <div x-show="formData.step_1.locale === 'ar'" class="absolute top-2 right-2 text-primary">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </label>
                                <label class="border rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-primary/50 relative"
                                       :class="formData.step_1.locale === 'en' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-slate-200'">
                                    <input type="radio" x-model="formData.step_1.locale" value="en" class="sr-only">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🇬🇧</span>
                                        <span class="font-medium text-slate-800">English</span>
                                    </div>
                                    <div x-show="formData.step_1.locale === 'en'" class="absolute top-2 right-2 text-primary">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </label>
                                <label class="border rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-primary/50 relative"
                                       :class="formData.step_1.locale === 'fr' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-slate-200'">
                                    <input type="radio" x-model="formData.step_1.locale" value="fr" class="sr-only">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🇫🇷</span>
                                        <span class="font-medium text-slate-800">Français</span>
                                    </div>
                                    <div x-show="formData.step_1.locale === 'fr'" class="absolute top-2 right-2 text-primary">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Currency -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">العملة المحلية</label>
                            <select x-model="formData.step_1.currency" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary h-12">
                                <option value="EGP">جنيه مصري (EGP)</option>
                                <option value="SAR">ريال سعودي (SAR)</option>
                                <option value="AED">درهم إماراتي (AED)</option>
                                <option value="USD">دولار أمريكي (USD)</option>
                                <option value="EUR">يورو (EUR)</option>
                            </select>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="bg-primary hover:bg-primary-focus text-white px-8 py-3 rounded-xl font-medium transition-colors flex items-center gap-2">
                                حفظ والمتابعة <i class="fa-solid fa-arrow-left mt-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 2: First Instructor -->
                <div x-show="currentStep === 'step_2'" style="display: none;"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">
                    
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600">
                            <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">أضف أول مدرس</h2>
                        <p class="text-slate-500 mt-1">قم بإضافة المدرس الأول لمنصتك للبدء في ربط المواد به.</p>
                    </div>

                    <form @submit.prevent="submitStep('step_2', false)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">اسم المدرس</label>
                            <input type="text" x-model="formData.step_2.instructor_name" placeholder="مثال: أ. أحمد محمد" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary h-12" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">رقم الهاتف <span class="text-slate-400 font-normal text-xs">(سيستخدم لتسجيل الدخول)</span></label>
                            <input type="text" x-model="formData.step_2.instructor_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary h-12 text-right" required>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="button" @click="submitStep('step_2', true)" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">
                                تخطي هذه الخطوة مؤقتاً
                            </button>
                            <button type="submit" class="bg-primary hover:bg-primary-focus text-white w-full sm:w-auto px-8 py-3 rounded-xl font-medium transition-colors flex justify-center items-center gap-2">
                                إضافة ومتابعة <i class="fa-solid fa-arrow-left mt-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 3: First Course -->
                <div x-show="currentStep === 'step_3'" style="display: none;"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">
                    
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-600">
                            <i class="fa-solid fa-book-open text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">إنشاء الدروس</h2>
                        <p class="text-slate-500 mt-1">أضف المادة العلمية الأولى أو الدورة التدريبية.</p>
                    </div>

                    <form @submit.prevent="submitStep('step_3', false)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">اسم المادة / الدورة</label>
                            <input type="text" x-model="formData.step_3.course_name" placeholder="مثال: لغة عربية - الصف الأول الثانوي" class="w-full border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 h-12" required>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="button" @click="submitStep('step_3', true)" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">
                                تخطي مؤقتاً
                            </button>
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white w-full sm:w-auto px-8 py-3 rounded-xl font-medium transition-colors flex justify-center items-center gap-2">
                                إنشاء المادة <i class="fa-solid fa-arrow-left mt-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 4: First Student -->
                <div x-show="currentStep === 'step_4'" style="display: none;"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-6">
                    
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-purple-600">
                            <i class="fa-solid fa-user-graduate text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">الخطوة الأخيرة: أول طالب 🎉</h2>
                        <p class="text-slate-500 mt-1">قم بتسجيل أول طالب في منصتك للبدء بالتفاعل.</p>
                    </div>

                    <form @submit.prevent="submitStep('step_4', false)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">اسم الطالب</label>
                            <input type="text" x-model="formData.step_4.student_name" placeholder="مثال: عمر ممدوح" class="w-full border-slate-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 h-12" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">رقم هاتف الطالب</label>
                            <input type="text" x-model="formData.step_4.student_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full border-slate-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 h-12 text-right" required>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="button" @click="submitStep('step_4', true)" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">
                                تخطي والذهاب للوحة التحكم
                            </button>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 shadow-lg shadow-purple-200 text-white w-full sm:w-auto px-8 py-3 rounded-xl font-medium transition-colors flex justify-center items-center gap-2">
                                إنهاء الإعداد والبدء! <i class="fa-solid fa-rocket mt-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        
        <p class="text-center text-slate-400 text-sm mt-8">
            <i class="fa-solid fa-shield-halved ml-1"></i> معلوماتك و بيانات مركزك مشفرة ومؤمنة بالكامل
        </p>
    </div>
</div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('onboardingWizard', (initialStatus) => ({
            currentStep: initialStatus === 'pending' ? 'step_1' : initialStatus,
            loading: false,
            
            steps: [
                { id: 'step_1', label: 'الإعدادات الأساسية' },
                { id: 'step_2', label: 'المدرسين' },
                { id: 'step_3', label: 'المواد والدورات' },
                { id: 'step_4', label: 'الطلاب' }
            ],
            
            formData: {
                step_1: { locale: 'ar', currency: 'EGP' },
                step_2: { instructor_name: '', instructor_phone: '' },
                step_3: { course_name: '' },
                step_4: { student_name: '', student_phone: '' }
            },

            get currentStepIndex() {
                return this.steps.findIndex(s => s.id === this.currentStep);
            },

            initWizard() {
                // Ensure we don't start on an invalid step if something went wrong
                if (!this.steps.find(s => s.id === this.currentStep)) {
                    this.currentStep = 'step_1';
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
                    } else if (data.next_step) {
                        this.currentStep = data.next_step;
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'عفواً',
                        text: error.message,
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#0f172a'
                    });
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
</script>
</body>
</html>
