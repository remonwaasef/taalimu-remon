<!-- STEP 2: Personal Details & Summary -->
<div x-show="currentStep === 2" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-8 items-start">
        
        <!-- Right Column (in RTL): Personal Details Form -->
        <div class="space-y-3.5">
            <!-- Full Name -->
            <div class="space-y-1">
                <label class="text-[12px] font-black text-slate-500 px-1 font-arabic">{{ __('auth.register.full_name') }}</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                        <i class="bi bi-person text-base"></i>
                    </div>
                    <input type="text" name="name" x-model="name" 
                        class="w-full h-11 ps-10 pe-4 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold font-arabic focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all" 
                        placeholder="{{ __('auth.register.full_name') }}" :required="currentStep === 2">
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-1">
                <label class="text-[12px] font-black text-slate-500 px-1 font-arabic">{{ __('auth.register.email') }}</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-secondary transition-colors">
                        <i class="bi bi-envelope text-base"></i>
                    </div>
                    <input type="email" name="email" x-model="email" 
                        class="w-full h-11 ps-10 pe-4 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold font-sans focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all" 
                        placeholder="mail@example.com" :required="currentStep === 2">
                </div>
            </div>



            <!-- Password & Confirm Password (Side-by-side) -->
            <div class="space-y-1">
                <div class="flex justify-between items-center px-1">
                    <label class="text-[12px] font-black text-slate-500 font-arabic">{{ __('auth.register.password') }}</label>
                    <button type="button" @click="showPassword = !showPassword" class="text-[11px] font-black text-brand-secondary hover:underline transition-colors flex items-center gap-1">
                        <i class="bi" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                        <span x-text="showPassword ? '{{ __('auth.register.hide') }}' : '{{ __('auth.register.show') }}'"></span>
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" 
                            class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all" 
                            placeholder="{{ __('auth.register.password') }}" :required="currentStep === 2">
                    </div>
                    <div>
                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" 
                            class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:bg-white focus:ring-2 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all" 
                            :class="password_confirmation.length > 0 && !isPasswordMatch ? 'border-red-300 bg-red-50' : ''" 
                            placeholder="{{ __('auth.register.confirm_password') }}" :required="currentStep === 2">
                    </div>
                </div>

                <!-- Password Live Criteria Indicators -->
                <div x-show="password.length > 0" x-collapse x-cloak class="px-1 pt-1">
                    <div class="flex flex-wrap gap-x-3 gap-y-1">
                        <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.length ? 'text-emerald-500' : 'text-slate-400'">
                            <i class="bi" :class="passwordCriteria.length ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ __('auth.registration_steps.chars_8') }}
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.upper ? 'text-emerald-500' : 'text-slate-400'">
                            <i class="bi" :class="passwordCriteria.upper ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ __('auth.registration_steps.uppercase') }}
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.lower ? 'text-emerald-500' : 'text-slate-400'">
                            <i class="bi" :class="passwordCriteria.lower ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ __('auth.registration_steps.lowercase') }}
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.number ? 'text-emerald-500' : 'text-slate-400'">
                            <i class="bi" :class="passwordCriteria.number ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ __('auth.registration_steps.number') }}
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.symbol ? 'text-emerald-500' : 'text-slate-400'">
                            <i class="bi" :class="passwordCriteria.symbol ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ __('auth.registration_steps.symbol') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Column (in RTL): Clean Plan Summary Card -->
        <div class="space-y-3">
            
            {{-- CASE 1: FREE TRIAL PLAN (Clean, Focused, High-Converting) --}}
            <div x-show="currentPlan.trial_days > 0" class="p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-emerald-50/30 border border-emerald-100/80 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">{{ __('auth.register.selected_plan') }}</span>
                        <h3 class="text-base font-black text-slate-900 font-arabic" x-text="currentPlan.name"></h3>
                    </div>
                    <button type="button" @click="showPlanModal = true" class="text-xs font-black text-brand-secondary hover:underline transition-all">
                        {{ __('auth.google_registration.change') }}
                    </button>
                </div>

                {{-- Trial Callout Banner --}}
                <div class="my-4 p-3.5 rounded-xl bg-white border border-emerald-200/60 shadow-sm text-center">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-black mb-2">
                        <i class="bi bi-gift-fill text-emerald-600"></i>
                        <span x-text="currentPlan.trial_days"></span> {{ __('auth.registration_steps.days_free_trial') }}
                    </div>
                    <div class="flex items-baseline justify-center gap-1 text-emerald-600">
                        <span class="text-3xl font-black tracking-tight">0</span>
                        <span class="text-sm font-bold">{{ __('auth.google_registration.free') }}</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 mt-1">تفعيل فوري لكامل مميزات المنصة بدون أي تكلفة</p>
                </div>

                {{-- Key Features Highlights --}}
                <div class="space-y-2 py-1">
                    <template x-for="feature in (currentPlan.features || []).slice(0, 4)" :key="feature">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-700 font-arabic">
                            <i class="bi bi-check2-circle text-emerald-500 text-sm"></i>
                            <span x-text="feature"></span>
                        </div>
                    </template>
                </div>

                {{-- Mini Trust Checklist --}}
                <div class="pt-3.5 mt-3.5 border-t border-slate-100 space-y-2 text-[11px] font-bold text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-credit-card text-emerald-500 text-sm"></i>
                        <span>{{ __('auth.registration_steps.no_credit_card') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-arrow-repeat text-brand-secondary text-sm"></i>
                        <span>{{ __('auth.register.flexible') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-500 text-sm"></i>
                        <span>{{ __('auth.register.secure') }}</span>
                    </div>
                </div>
            </div>

            {{-- CASE 2: PAID PLAN (If trial_days === 0) --}}
            <div x-show="currentPlan.trial_days === 0" x-cloak class="p-5 rounded-2xl bg-slate-50/80 border border-slate-100 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">{{ __('auth.register.selected_plan') }}</span>
                        <h3 class="text-base font-black text-slate-900 font-arabic" x-text="currentPlan.name"></h3>
                    </div>
                    <button type="button" @click="showPlanModal = true" class="text-xs font-black text-brand-secondary hover:underline">
                        {{ __('auth.google_registration.change') }}
                    </button>
                </div>

                <!-- Billing Cycle Switcher -->
                <div class="flex p-1 bg-slate-200/60 rounded-xl items-center gap-1">
                    <button type="button" @click="billingCycle = 'monthly'" 
                            class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all"
                            :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-600 hover:bg-slate-100'">
                        {{ __('auth.register.billing_monthly') }}
                    </button>
                    <button type="button" @click="billingCycle = 'term'" 
                            class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all"
                            :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-600 hover:bg-slate-100'">
                        {{ __('auth.register.billing_term') }}
                    </button>
                    <button type="button" @click="billingCycle = 'yearly'" 
                            class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all"
                            :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-600 hover:bg-slate-100'">
                        {{ __('auth.register.billing_yearly') }}
                    </button>
                </div>

                <div class="flex items-baseline justify-between pt-1">
                    <span class="text-xs font-bold text-slate-400">الإجمالي:</span>
                    <div class="text-xl font-black text-slate-900">
                        <span x-text="finalPrice.toLocaleString()"></span>
                        <span class="text-xs font-bold text-slate-400" x-text="currentPriceData.currency"></span>
                    </div>
                </div>

                <!-- Payment Gateway Selection -->
                <div class="pt-2 border-t border-slate-200/60 space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 block">{{ __('auth.register.payment_method') ?? 'طريقة الدفع' }}</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="relative cursor-pointer" x-show="selectedCurrency === 'EGP'">
                            <input type="radio" name="payment_gateway" value="paymob" :checked="selectedCurrency === 'EGP'" class="peer sr-only">
                            <div class="flex items-center gap-2 p-2 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-brand-secondary peer-checked:bg-brand-secondary/5 transition-all">
                                <i class="bi bi-credit-card text-slate-500 peer-checked:text-brand-secondary"></i>
                                <span class="text-xs font-black text-slate-700">Paymob</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer" x-show="selectedCurrency !== 'EGP'">
                            <input type="radio" name="payment_gateway" value="paypal" :checked="selectedCurrency !== 'EGP'" class="peer sr-only">
                            <div class="flex items-center gap-2 p-2 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-brand-secondary peer-checked:bg-brand-secondary/5 transition-all">
                                <i class="bi bi-paypal text-slate-500 peer-checked:text-brand-secondary"></i>
                                <span class="text-xs font-black text-slate-700">PayPal</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Fallback hidden inputs to ensure backend validation is always satisfied --}}
            <input type="hidden" name="payment_gateway" value="paymob" x-show="false" :disabled="currentPlan.trial_days === 0">

        </div>
    </div>

    <!-- Action Buttons -->
    <div class="pt-3 flex flex-col sm:flex-row gap-3">
        <button type="button" @click="prevStep()" class="sm:w-36 h-12 rounded-xl font-black text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all border border-slate-200">
            {{ __('auth.registration_steps.back') }}
        </button>
        <button type="submit" :disabled="(password.length > 0 && !isPasswordMatch)"
                class="flex-1 h-12 rounded-xl font-black text-base text-white bg-gradient-to-r from-emerald-600 to-teal-500 shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-0.5 active:scale-[0.98] transition-all disabled:opacity-50 disabled:grayscale disabled:pointer-events-none relative overflow-hidden group">
            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-white opacity-20 group-hover:animate-[shine_1s] group-hover:left-full transition-all duration-700 ease-in-out"></div>
            <span class="relative z-10" x-text="currentPlan.trial_days > 0 ? ({{ Js::from(__('auth.google_registration.start_free_trial')) }}) : (finalPrice === 0 ? '{{ __('auth.register.cta_main') }}' : '{{ __('auth.registration_steps.pay_complete_short') }}')"></span>
        </button>
    </div>

    <!-- Bottom Reassurance Badge -->
    <div class="mt-2 text-center">
        <div class="inline-flex items-center gap-2 text-slate-500 bg-emerald-50/50 px-4 py-1.5 rounded-full border border-emerald-100 text-xs font-bold font-arabic">
            <i class="bi bi-shield-check text-emerald-600"></i>
            <span>{{ __('auth.guarantee.trial_reassurance') }}</span>
        </div>
    </div>
</div>
