        <!-- Plan Selection Modal -->
        <div x-show="showPlanModal" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-cloak>
            <div @click.away="showPlanModal = false" 
                 x-transition:enter="transition ease-out duration-300 delay-100"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden"
                 style="max-height: 90vh;">
                
                <!-- Header -->
                <div class="px-6 pt-6 pb-4">
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 font-arabic">{{ __('auth.plan_modal.select_plan') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 font-arabic">{{ __('auth.plan_modal.subtitle') }}</p>
                        </div>
                        <button type="button" @click="showPlanModal = false" 
                                aria-label="{{ __('auth.plan_modal.close') }}" 
                                class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 hover:bg-slate-200 transition-colors text-slate-500 hover:text-slate-700 flex-shrink-0">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>


                    <!-- Billing Cycle Toggle -->
                    <div class="flex justify-center">
                        <div class="inline-flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl gap-0.5" dir="ltr">
                            <button type="button" @click="billingCycle = 'monthly'" 
                                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200"
                                    :class="billingCycle === 'monthly' 
                                        ? 'bg-white dark:bg-slate-900 text-brand-primary shadow-sm' 
                                        : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
                                {{ __('auth.billing.monthly') }}
                            </button>
                            <button type="button" @click="billingCycle = 'term'" 
                                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200"
                                    :class="billingCycle === 'term' 
                                        ? 'bg-white dark:bg-slate-900 text-brand-primary shadow-sm' 
                                        : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
                                {{ __('auth.billing.term') }}
                            </button>
                            <button type="button" @click="billingCycle = 'yearly'" 
                                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 relative"
                                    :class="billingCycle === 'yearly' 
                                        ? 'bg-white dark:bg-slate-900 text-brand-primary shadow-sm' 
                                        : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
                                {{ __('auth.billing.yearly') }}
                                <span class="absolute -top-2.5 -right-1 bg-amber-400 text-amber-900 text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none whitespace-nowrap">
                                    {{ __('auth.plan_modal.save_badge') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Plan Cards -->
                <div class="px-6 pb-4 space-y-3 overflow-y-auto custom-scrollbar" style="max-height: calc(90vh - 280px);">
                    <template x-for="pkg in packages" :key="pkg.slug">
                        <label class="relative block cursor-pointer group">
                            <input type="radio" name="plan_selector" :value="pkg.slug" x-model="selectedPlan" class="peer sr-only">
                            
                            <!-- Most Popular Badge -->
                            <div x-show="pkg.is_featured" 
                                 class="absolute -top-2.5 left-1/2 -translate-x-1/2 z-10">
                                <span class="bg-gradient-to-r from-brand-primary to-brand-400 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm whitespace-nowrap">
                                    <i class="bi bi-star-fill text-[8px] me-1"></i>{{ __('auth.plan_modal.most_popular') }}
                                </span>
                            </div>

                            <div class="p-4 rounded-2xl border-2 transition-all duration-200"
                                 :class="selectedPlan === pkg.slug 
                                     ? 'border-brand-primary bg-brand-primary/[0.03] shadow-md shadow-brand-primary/10' 
                                     : (pkg.is_featured ? 'border-slate-200 bg-white hover:border-brand-primary/30 hover:shadow-sm mt-1' : 'border-slate-100 bg-white hover:border-slate-200 hover:shadow-sm')">
                                
                                <div class="flex justify-between items-start gap-3">
                                    <!-- Plan Info (Right side in RTL) -->
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <!-- Radio Indicator -->
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 mt-0.5 transition-all duration-200"
                                             :class="selectedPlan === pkg.slug ? 'border-brand-primary bg-brand-primary' : 'border-slate-300 bg-white group-hover:border-slate-400'">
                                            <svg x-show="selectedPlan === pkg.slug" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>

                                        <div class="min-w-0">
                                            <span class="font-bold text-slate-900 text-sm block" x-text="pkg.name"></span>
                                            
                                            <!-- Trial Badge -->
                                            <div x-show="pkg.trial_days > 0" class="mt-1">
                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-brand-primary text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                                    <i class="bi bi-gift text-[9px]"></i>
                                                    <span x-text="pkg.trial_days + ' {{ __('auth.plan_modal.days_trial') }}'"></span>
                                                </span>
                                            </div>

                                            <!-- Top Features (max 2) -->
                                            <div x-show="pkg.features && pkg.features.length > 0" class="mt-2 space-y-0.5">
                                                <template x-for="(feat, fi) in (pkg.features || []).slice(0, 2)" :key="fi">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="bi bi-check-circle-fill text-brand-primary text-[9px] flex-shrink-0"></i>
                                                        <span class="text-[11px] text-slate-500 truncate" x-text="feat"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price (Left side in RTL) -->
                                    <div class="text-left flex-shrink-0" dir="ltr">
                                        <div class="font-bold text-lg leading-tight transition-colors duration-200"
                                             :class="selectedPlan === pkg.slug ? 'text-brand-primary' : 'text-slate-800'">
                                            <span x-text="(billingCycle === 'yearly' ? getPriceData(pkg).yearly : (billingCycle === 'term' ? getPriceData(pkg).term : getPriceData(pkg).amount)).toLocaleString()"></span>
                                            <span class="text-xs font-semibold text-slate-400 ms-0.5" x-text="getPriceData(pkg).currency"></span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-medium"
                                              x-text="billingCycle === 'yearly' ? '/{{ __('auth.billing.yearly_short') }}' : (billingCycle === 'term' ? '/{{ __('auth.billing.term_short') }}' : '/{{ __('auth.billing.monthly_short') }}')"></span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </template>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                    <button type="button" @click="showPlanModal = false" 
                            class="w-full py-2.5 rounded-xl text-sm font-bold transition-all duration-200 bg-brand-primary text-white hover:bg-brand-600 shadow-sm shadow-brand-primary/20 active:scale-[0.98]">
                        {{ __('auth.plan_modal.confirm') }}
                    </button>
                </div>
            </div>
        </div>
