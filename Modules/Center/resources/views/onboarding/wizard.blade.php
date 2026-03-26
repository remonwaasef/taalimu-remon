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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/landing-new.css'])
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
            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ __('onboarding.welcome_title') }}</h1>
            <p class="text-slate-600">{{ __('onboarding.welcome_subtitle') }}</p>
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
                <p class="mt-4 text-slate-600 font-medium" x-text="'{{ __('onboarding.loading') }}'"></p>
            </div>

            <!-- Global Language Switcher -->
            <div class="border-b border-slate-100 bg-slate-50/50 p-4 flex justify-center gap-4">
                <button @click="updateLanguage('ar')" :class="formData.step_1.locale === 'ar' ? 'bg-white shadow-sm ring-1 ring-primary/20 text-primary' : 'text-slate-500 hover:text-primary'" class="px-3 py-1.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span>🇸🇦</span> العربية
                </button>
                <button @click="updateLanguage('en')" :class="formData.step_1.locale === 'en' ? 'bg-white shadow-sm ring-1 ring-primary/20 text-primary' : 'text-slate-500 hover:text-primary'" class="px-3 py-1.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span>🇬🇧</span> English
                </button>
                <button @click="updateLanguage('fr')" :class="formData.step_1.locale === 'fr' ? 'bg-white shadow-sm ring-1 ring-primary/20 text-primary' : 'text-slate-500 hover:text-primary'" class="px-3 py-1.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span>🇫🇷</span> Français
                </button>
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
                        <h2 class="text-2xl font-bold text-slate-800">{{ __('onboarding.step_1.title') }}</h2>
                        <p class="text-slate-500 mt-1">{{ __('onboarding.step_1.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_1')" class="space-y-5">
                        <!-- Currency -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('onboarding.step_1.currency_label') }}</label>
                            <select x-model="formData.step_1.currency" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary h-12">
                                <option value="EGP">{{ __('onboarding.currencies.egp') }}</option>
                                <option value="SAR">{{ __('onboarding.currencies.sar') }}</option>
                                <option value="AED">{{ __('onboarding.currencies.aed') }}</option>
                                <option value="USD">{{ __('onboarding.currencies.usd') }}</option>
                                <option value="EUR">{{ __('onboarding.currencies.eur') }}</option>
                            </select>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="bg-primary hover:bg-primary-focus text-white px-8 py-3 rounded-xl font-medium transition-colors flex items-center gap-2">
                                {{ __('onboarding.step_1.btn_submit') }} <i class="fa-solid fa-arrow-right rtl:rotate-180 mt-1"></i>
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
                        <h2 class="text-2xl font-bold text-slate-800">{{ __('onboarding.step_2.title') }}</h2>
                        <p class="text-slate-500 mt-1">{{ __('onboarding.step_2.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_2', false)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('onboarding.step_2.name_label') }}</label>
                            <input type="text" x-model="formData.step_2.instructor_name" placeholder="{{ __('onboarding.step_2.name_placeholder') }}" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary h-12" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('onboarding.step_2.phone_label') }} <span class="text-slate-400 font-normal text-xs">{{ __('onboarding.step_2.phone_hint') }}</span></label>
                            <input type="text" x-model="formData.step_2.instructor_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary h-12 text-right" required>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-700 font-medium transition-colors text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-left rtl:rotate-180"></i> {{ __('onboarding.btn_back') }}
                                </button>
                                <button type="button" @click="submitStep('step_2', true)" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">
                                    {{ __('onboarding.step_2.btn_skip') }}
                                </button>
                            </div>
                            <button type="submit" class="bg-primary hover:bg-primary-focus text-white w-full sm:w-auto px-8 py-3 rounded-xl font-medium transition-colors flex justify-center items-center gap-2">
                                {{ __('onboarding.step_2.btn_submit') }} <i class="fa-solid fa-arrow-right rtl:rotate-180 mt-1"></i>
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
                        <h2 class="text-2xl font-bold text-slate-800">{{ __('onboarding.step_3.title') }}</h2>
                        <p class="text-slate-500 mt-1">{{ __('onboarding.step_3.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_3', false)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('onboarding.step_3.name_label') }}</label>
                            <input type="text" x-model="formData.step_3.course_name" placeholder="{{ __('onboarding.step_3.name_placeholder') }}" class="w-full border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 h-12" required>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-700 font-medium transition-colors text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-left rtl:rotate-180"></i> {{ __('onboarding.btn_back') }}
                                </button>
                                <button type="button" @click="submitStep('step_3', true)" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">
                                    {{ __('onboarding.step_3.btn_skip') }}
                                </button>
                            </div>
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white w-full sm:w-auto px-8 py-3 rounded-xl font-medium transition-colors flex justify-center items-center gap-2">
                                {{ __('onboarding.step_3.btn_submit') }} <i class="fa-solid fa-arrow-right rtl:rotate-180 mt-1"></i>
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
                        <h2 class="text-2xl font-bold text-slate-800">{{ __('onboarding.step_4.title') }}</h2>
                        <p class="text-slate-500 mt-1">{{ __('onboarding.step_4.subtitle') }}</p>
                    </div>

                    <form @submit.prevent="submitStep('step_4', false)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('onboarding.step_4.name_label') }}</label>
                            <input type="text" x-model="formData.step_4.student_name" placeholder="{{ __('onboarding.step_4.name_placeholder') }}" class="w-full border-slate-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 h-12" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('onboarding.step_4.phone_label') }}</label>
                            <input type="text" x-model="formData.step_4.student_phone" dir="ltr" placeholder="01xxxxxxxxx" class="w-full border-slate-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 h-12 text-right" required>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <button type="button" @click="prevStep()" class="text-slate-400 hover:text-slate-700 font-medium transition-colors text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-left rtl:rotate-180"></i> {{ __('onboarding.btn_back') }}
                                </button>
                                <button type="button" @click="submitStep('step_4', true)" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">
                                    {{ __('onboarding.step_4.btn_skip') }}
                                </button>
                            </div>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 shadow-lg shadow-purple-200 text-white w-full sm:w-auto px-8 py-3 rounded-xl font-medium transition-colors flex justify-center items-center gap-2">
                                {{ __('onboarding.step_4.btn_submit') }} <i class="fa-solid fa-rocket rtl:mr-2 ltr:ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        
        <p class="text-center text-slate-400 text-sm mt-8">
            <i class="fa-solid fa-shield-halved rtl:ml-1 ltr:mr-1"></i> {{ __('onboarding.security_note') }}
        </p>
    </div>
</div>
</div>

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
                step_2: { instructor_name: '', instructor_phone: '' },
                step_3: { course_name: '' },
                step_4: { student_name: '', student_phone: '' }
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
</body>
</html>
