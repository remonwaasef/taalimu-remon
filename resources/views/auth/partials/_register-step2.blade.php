                <!-- STEP 2: Personal Details & Summary -->
                <div x-show="currentStep === 2" x-cloak :class="{'hidden': currentStep !== 2}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-8 items-start">
                        <!-- Left Column: Form -->
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.full_name') }}</label>
                                <input type="text" name="name" x-model="name" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" :required="currentStep === 2">
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">{{ __('auth.register.email') }}</label>
                                    <input type="email" name="email" x-model="email" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="mail@example.com" :required="currentStep === 2">
                                </div>
                                <div class="space-y-1.5">
                                                         {{-- Country Code Selector --}}
                                        <div class="relative" dir="ltr">
                                            <select x-model="countryCode" name="country_code"
                                                class="h-11 pl-2 pr-7 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-sm font-black text-slate-700 focus:outline-none focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all appearance-none cursor-pointer"
                                                :class="phoneVerified ? 'border-emerald-300 bg-emerald-50/30 pointer-events-none opacity-60' : 'border-slate-100'">
                                                @include('partials.country-codes')
                                            </select>
                                            <div class="absolute inset-y-0 right-1 flex items-center pointer-events-none">
                                                <i class="bi bi-chevron-down text-[9px] text-slate-400"></i>
                                            </div>
                                        </div>
                                        {{-- Phone Input --}}
                                        <div class="relative flex-1 group">
                                            <input type="text" name="phone" x-model="phone" 
                                                :readonly="phoneVerified"
                                                class="w-full h-11 px-4 bg-slate-50/50 border-2 rounded-xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                                                :class="phoneVerified ? 'border-emerald-300 bg-emerald-50/30 pointer-events-none' : 'border-slate-100'"
                                                placeholder="10xxxxxxx" :required="currentStep === 2" dir="ltr">
                                            {{-- Verified Badge --}}
                                            <div x-show="phoneVerified" class="absolute inset-y-0 end-0 pe-3 flex items-center">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    {{ app()->isLocale('ar') ? 'تم التحقق' : 'Verified' }}
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Send OTP Button --}}
                                        <button type="button" @click="sendPhoneOtp()" 
                                                x-show="!phoneVerified"
                                                :disabled="isSendingOtp || otpCountdown > 0 || !phone || phone.length < 7"
                                                class="h-11 px-4 rounded-2xl font-black text-[11px] font-arabic transition-all whitespace-nowrap flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                                :class="otpSent ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600'">
                                            <template x-if="isSendingOtp">
                                                <div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                            </template>
                                            <template x-if="!isSendingOtp && otpCountdown > 0">
                                                <span x-text="otpFormattedCountdown"></span>
                                            </template>
                                            <template x-if="!isSendingOtp && otpCountdown <= 0">
                                                <span>{{ app()->isLocale('ar') ? 'إرسال كود' : 'Send OTP' }}</span>
                                            </template>
                                        </button>
                                    </div>

                                    {{-- OTP Input (appears after sending) --}}
                                    <div x-show="otpSent && !phoneVerified" x-cloak 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="space-y-2">
                                        <div class="relative flex gap-2">
                                            <div class="relative flex-1">
                                                <input type="text" x-model="otpCode" maxlength="6" inputmode="numeric" pattern="[0-9]*"
                                                    @input="otpCode = otpCode.replace(/[^0-9]/g, ''); if(otpCode.length === 6) verifyPhoneOtp()"
                                                    class="w-full h-11 px-4 bg-amber-50/50 border-2 border-amber-200 rounded-2xl text-center text-xl font-black tracking-[0.5em] focus:outline-none focus:ring-4 focus:ring-brand-secondary/10 focus:border-brand-secondary transition-all"
                                                    placeholder="● ● ● ● ● ●">
                                            </div>
                                            <button type="button" @click="verifyPhoneOtp()" 
                                                    :disabled="isVerifyingOtp || otpCode.length !== 6"
                                                    class="h-11 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-500 text-white font-black text-[11px] font-arabic shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5">
                                                <template x-if="isVerifyingOtp">
                                                    <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                                </template>
                                                <template x-if="!isVerifyingOtp">
                                                    <span>{{ app()->isLocale('ar') ? 'تحقق' : 'Verify' }}</span>
                                                </template>
                                            </button>
                                        </div>
                                        <p class="text-[10px] font-bold font-arabic text-amber-600 flex items-center gap-1 px-1">
                                            <i class="bi bi-whatsapp text-emerald-500"></i>
                                            {{ app()->isLocale('ar') ? 'تم إرسال كود التحقق عبر واتساب والبريد الإلكتروني' : 'Verification code sent via WhatsApp and Email' }}
                                        </p>
                                    </div>

                                    {{-- OTP Status Message --}}
                                    <p x-show="otpMessage && otpStatus !== 'sent'" x-cloak
                                       :class="{
                                           'text-emerald-600': otpStatus === 'verified',
                                           'text-red-500': otpStatus === 'error',
                                           'text-amber-600': otpStatus === 'sending' || otpStatus === 'verifying'
                                       }"
                                       class="text-[11px] font-bold px-1 font-arabic animate-fade-in" x-text="otpMessage"></p>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex justify-between items-center px-1">
                                    <label class="text-[12px] font-black text-slate-400 font-arabic uppercase tracking-wide">{{ __('auth.register.password') }}</label>
                                    <button type="button" @click="showPassword = !showPassword" class="text-[10px] font-black text-brand-secondary uppercase tracking-widest hover:opacity-70 transition-opacity">
                                        <span x-text="showPassword ? '{{ __('auth.register.hide') }}' : '{{ __('auth.register.show') }}'"></span>
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" placeholder="••••••••" :required="currentStep === 2">
                                    
                                    <!-- Password Live Criteria Indicators -->
                                    <div x-show="password.length > 0" x-collapse x-cloak class="px-1 py-1">
                                        <div class="flex flex-wrap gap-x-3 gap-y-1.5">
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.length ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.length ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? '٨ أحرف على الأقل' : '8+ Characters' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.upper ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.upper ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'حرف كبير' : 'Uppercase' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.lower ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.lower ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'حرف صغير' : 'Lowercase' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.number ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.number ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'رقم' : 'Number' }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[10px] font-black font-arabic transition-all duration-300" :class="passwordCriteria.symbol ? 'text-emerald-500' : 'text-slate-400'">
                                                <i class="bi" :class="passwordCriteria.symbol ? 'bi-check-circle-fill' : 'bi-circle'"></i> {{ app()->isLocale('ar') ? 'رمز (!@#$)' : 'Symbol (!@#$)' }}
                                            </div>
                                        </div>
                                    </div>

                                    <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" class="w-full h-11 px-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner" :class="password_confirmation.length > 0 && !isPasswordMatch ? 'border-red-300 bg-red-50' : ''" placeholder="{{ __('auth.register.confirm_password') }}" :required="currentStep === 2">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Summary & Payment -->
                        <div class="space-y-4">
                            <!-- Simplified Price Summary Card -->
                            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 relative overflow-hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __('auth.register.selected_plan') }}</span>
                                            <button type="button" @click="showPlanModal = true" class="text-[9px] font-black text-brand-secondary underline underline-offset-2 hover:opacity-70 transition-opacity uppercase tracking-widest">
                                                {{ app()->getLocale() == 'ar' ? 'تغيير' : 'Change' }}
                                            </button>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 font-arabic">
                                            <span x-text="currentPlan.name"></span>
                                            <span class="text-xs font-bold text-slate-400 ms-1" x-text="'(' + (billingCycle === 'yearly' ? (currentPriceData.yearly || 0).toLocaleString() : (billingCycle === 'term' ? (currentPriceData.term || 0).toLocaleString() : (currentPriceData.amount || 0).toLocaleString())) + ' ' + currentPriceData.currency + ')'"></span>
                                        </h3>
                                    </div>
                                    <div class="text-right">
                                        <template x-if="currentPlan.trial_days > 0">
                                            <div class="text-[11px] font-black text-emerald-600 mb-1 animate-fade-in uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100 inline-block">
                                                <i class="bi bi-gift-fill me-1"></i>
                                                <span x-text="currentPlan.trial_days"></span> {{ app()->isLocale('ar') ? 'يوم تجربة مجانية' : 'Days Free Trial' }}
                                            </div>
                                        </template>
                                        <template x-if="couponStatus === 'valid' && currentPlan.trial_days === 0">
                                            <div class="text-[10px] font-black text-emerald-600 mb-1 animate-fade-in">-<span x-text="couponDiscountAmount.toLocaleString()"></span> <span x-text="currentPriceData.currency"></span></div>
                                        </template>
                                        <div class="flex items-baseline gap-1 justify-end" :class="currentPlan.trial_days > 0 ? 'text-emerald-500' : 'text-brand-secondary'">
                                            <span class="text-2xl font-black tracking-tighter" x-text="currentPlan.trial_days > 0 ? '0' : finalPrice.toLocaleString()"></span>
                                            <span class="text-xs font-bold opacity-60" x-text="currentPlan.trial_days > 0 ? ({{ app()->isLocale('ar') ? '\'مجاناً\'' : '\'FREE\'' }}) : currentPriceData.currency"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mini Features List -->
                                <div x-data="{ openFeatures: false }" class="py-3 border-y border-slate-100/50 mb-3">
                                    <button type="button" @click="openFeatures = !openFeatures" class="w-full flex items-center justify-center gap-2 text-[12px] font-black text-slate-700 font-arabic hover:text-brand-secondary transition-colors pb-2 cursor-pointer">
                                        <span>{{ app()->isLocale('ar') ? 'عرض المميزات' : 'View Features' }}</span>
                                        <i class="bi bi-chevron-down transition-transform duration-300 transform" :class="openFeatures ? 'rotate-180' : ''"></i>
                                    </button>
                                    <div x-show="openFeatures" x-transition.opacity.duration.300ms class="space-y-2 pt-2 border-t border-slate-50">
                                        <template x-for="feature in (currentPlan.features || [])" :key="feature">
                                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-600 font-arabic">
                                                <i class="bi bi-check2 text-emerald-500"></i>
                                                <span x-text="feature"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Billing Cycle Switcher (More compact) -->
                                <div class="flex p-1 bg-slate-200/50 rounded-xl mb-3 items-center">
                                    <button type="button" @click="billingCycle = 'monthly'" 
                                            class="flex-1 py-1.5 text-[10px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'monthly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ app()->getLocale() == 'ar' ? 'شهري' : 'Monthly' }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'term'" 
                                            class="flex-1 py-1.5 text-[10px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'term' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ app()->getLocale() == 'ar' ? 'ترم' : 'Term' }}
                                    </button>
                                    <button type="button" @click="billingCycle = 'yearly'" 
                                            class="flex-1 py-1.5 text-[10px] font-black rounded-lg transition-all"
                                            :class="billingCycle === 'yearly' ? 'bg-white text-brand-secondary shadow-sm' : 'text-slate-500 hover:bg-slate-50'">
                                        {{ app()->getLocale() == 'ar' ? 'سنوي' : 'Yearly' }}
                                    </button>
                                </div>

                                <!-- Coupon (Compact inline) -->
                                <div class="pt-0 border-t border-slate-100 mb-3 pt-2">
                                    <button type="button" x-show="!showCouponInput && couponStatus !== 'valid'" @click="showCouponInput = true" 
                                            class="text-[10px] font-black text-brand-secondary hover:underline flex items-center gap-1 font-arabic">
                                        <i class="bi bi-tag-fill"></i> {{ __('auth.register.have_coupon') ?? 'هل لديك كود خصم؟' }}
                                    </button>
                                    <div x-show="showCouponInput || couponStatus === 'valid'" x-cloak class="space-y-1.5">
                                        <div class="relative flex gap-1.5">
                                            <div class="relative flex-1">
                                                <input type="text" name="coupon_code" x-model="couponCode" @keyup.enter="validateCoupon()"
                                                    placeholder="{{ __('admin.coupon_code') }}"
                                                    class="w-full h-9 px-3 bg-white border-2 border-slate-100 rounded-lg text-[10px] font-black uppercase focus:outline-none focus:border-brand-secondary transition-all"
                                                    :class="couponStatus === 'valid' ? 'border-emerald-200 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-200 bg-red-50' : '')">
                                                <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                                    <template x-if="couponStatus === 'valid'"><i class="bi bi-patch-check-fill text-emerald-500 text-xs"></i></template>
                                                    <template x-if="couponStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-xs"></i></template>
                                                </div>
                                            </div>
                                            <button type="button" @click="validateCoupon()" :disabled="isApplyingCoupon || !couponCode"
                                                    class="h-9 px-3 rounded-lg bg-slate-900 text-white font-black text-[9px] uppercase tracking-widest hover:bg-brand-secondary transition-all disabled:opacity-50 flex items-center justify-center min-w-[60px]">
                                                <template x-if="isApplyingCoupon"><div class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></div></template>
                                                <span x-show="!isApplyingCoupon">{{ app()->isLocale('ar') ? 'تطبيق' : 'Apply' }}</span>
                                            </button>
                                        </div>
                                        <p x-show="couponMessage" :class="couponStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                                           class="text-[9px] font-black px-1 animate-fade-in" x-text="couponMessage"></p>
                                    </div>
                                </div>

                                <!-- Trust Info (Compact) -->
                                <div class="flex items-center justify-between gap-2 opacity-60">
                                    <div class="flex items-center gap-1 text-slate-500 text-[9px] font-bold font-arabic">
                                        <i class="bi bi-shield-check text-emerald-500"></i>
                                        <span>{{ app()->getLocale() == 'ar' ? 'دفع آمن' : 'Secure' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-slate-500 text-[9px] font-bold font-arabic">
                                        <i class="bi bi-arrow-repeat text-brand-secondary"></i>
                                        <span>{{ app()->getLocale() == 'ar' ? 'إلغاء مرن' : 'Flexible' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Gateway Selection -->
                            <div class="space-y-2" x-show="currentPlan.trial_days === 0">
                                <label class="text-[12px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide">
                                    {{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment' }}
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Paymob (EGP only) -->
                                    <label class="relative cursor-pointer group" x-show="selectedCurrency === 'EGP'">
                                        <input type="radio" name="payment_gateway" value="paymob" :checked="selectedCurrency === 'EGP'" class="peer sr-only">
                                        <div class="flex items-center gap-2 p-2 rounded-xl border-2 border-slate-100 bg-slate-50/30 peer-checked:border-brand-secondary peer-checked:bg-white transition-all">
                                            <i class="bi bi-credit-card-2-back text-sm text-slate-400 peer-checked:text-brand-secondary"></i>
                                            <span class="text-[10px] font-black text-slate-600 peer-checked:text-slate-900">Paymob</span>
                                        </div>
                                    </label>
                                    <!-- PayPal (USD/EUR only) -->
                                    <label class="relative cursor-pointer group" x-show="selectedCurrency !== 'EGP'">
                                        <input type="radio" name="payment_gateway" value="paypal" :checked="selectedCurrency !== 'EGP'" class="peer sr-only">
                                        <div class="flex items-center gap-2 p-2 rounded-xl border-2 border-slate-100 bg-slate-50/30 peer-checked:border-brand-secondary peer-checked:bg-white transition-all">
                                            <i class="bi bi-paypal text-sm text-slate-400 peer-checked:text-brand-secondary"></i>
                                            <span class="text-[10px] font-black text-slate-600 peer-checked:text-slate-900">PayPal</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="prevStep()" class="flex-1 h-12 rounded-full font-black text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border-2 border-slate-100">
                            {{ app()->isLocale('ar') ? 'رجوع' : 'Back' }}
                        </button>
                        <button type="submit" :disabled="(password.length > 0 && !isPasswordMatch) || !phoneVerified"
                                class="flex-[2] h-12 rounded-full font-black text-base text-white bg-gradient-to-r from-emerald-600 to-teal-500 shadow-lg shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-1 transition-all disabled:opacity-50 disabled:grayscale relative overflow-hidden group">
                            <!-- Button Shine Effect -->
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-white opacity-20 group-hover:animate-[shine_1s] group-hover:left-full transition-all duration-700 ease-in-out"></div>
                            <span class="relative z-10" x-text="currentPlan.trial_days > 0 ? ({{ Js::from(app()->isLocale('ar') ? 'ابدأ الفترة التجريبية' : 'Start Free Trial') }}) : (finalPrice === 0 ? '{{ __('auth.register.cta_main') }}' : '{{ app()->isLocale('ar') ? 'ادفع واستكمل التسجيل' : 'Pay & Complete' }}')"></span>
                        </button>
                    </div>

                    <!-- Conversion Boost: Guarantee & Support -->
                    <div class="mt-4 flex flex-col items-center gap-2">
                        <div class="flex items-center justify-center gap-2 text-slate-600 bg-emerald-50/50 px-4 py-2 rounded-xl border border-emerald-100/50 w-full text-center">
                            <i class="bi bi-shield-fill-check text-emerald-500 text-base"></i>
                            <p class="text-[11px] font-bold font-arabic">{{ app()->getLocale() == 'ar' ? 'ضمان استرجاع الأموال خلال 30 يوماً.' : '30-Day Money-Back Guarantee.' }}</p>
                        </div>
                    </div>
                </div>
