

<?php $__env->startSection('content'); ?>
<!-- Import Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --font-outfit: 'Outfit', sans-serif;
        --font-cairo: 'Cairo', sans-serif;
        --brand-primary: #2563eb; /* Royal Blue */
        --brand-gradient: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #60a5fa 100%);
        --panel-dark: #0F172A;
        --bg-field: #F8FAFC;
    }
    
    body {
        font-family: var(--font-outfit);
        background-color: #f8faff; /* Match landing page mesh gradient base */
    }
    
    [lang="ar"] body, .font-arabic {
        font-family: var(--font-cairo);
    }

    .plan-card-compact {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(8px);
        margin-bottom: 12px; /* Increased vertical spacing */
    }

    .plan-card-compact.selected {
        border-color: var(--brand-primary);
        background: rgba(37, 99, 235, 0.2); /* Slightly deeper tint */
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.3), 0 8px 10px -6px rgba(37, 99, 235, 0.2);
        transform: scale(1.03); /* Subtle extra scale */
    }

    .plan-card-compact:hover:not(.selected) {
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-3px);
    }

    .label-compact {
        font-size: 13px; /* Increased from 10px */
        font-weight: 600;
        color: #475569; /* slate-600 */
        margin-bottom: 4px;
        display: block;
        padding-inline-start: 4px;
    }

    .input-compact {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid #E2E8F0;
        border-radius: 16px; /* Slightly more modern radius */
        background: var(--bg-field);
    }

    .input-compact:focus {
        border-color: var(--brand-primary);
        background: #FFFFFF;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        transform: translateY(-1px);
        outline: none;
    }

    .btn-submit-compact {
        border-radius: 50px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #60a5fa 100%); /* Hero CTA Gradient */
        background-size: 200% auto;
        color: white;
        border: none;
        font-weight: 800;
    }

    .btn-submit-compact:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.5);
    }

    .btn-submit-compact:active {
        transform: translateY(0);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }

    /* Password Bulb Indicators Refined */
    .bulb-section {
        background: rgba(248, 250, 252, 0.4);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(226, 232, 240, 0.5);
        border-radius: 16px;
        padding: 12px;
        margin-top: 16px;
    }

    .bulb {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #CBD5E1; /* slate-300 */
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .bulb-container.active .bulb {
        transform: scale(1.3);
    }

    .bulb.active-length { background-color: #10B981; box-shadow: 0 0 12px rgba(16, 185, 129, 0.6), 0 0 4px rgba(16, 185, 129, 0.4); }
    .bulb.active-upper  { background-color: #3B82F6; box-shadow: 0 0 12px rgba(59, 130, 246, 0.6), 0 0 4px rgba(59, 130, 246, 0.4); }
    .bulb.active-lower  { background-color: #8B5CF6; box-shadow: 0 0 12px rgba(139, 92, 246, 0.6), 0 0 4px rgba(139, 92, 246, 0.4); }
    .bulb.active-number { background-color: #F59E0B; box-shadow: 0 0 12px rgba(245, 158, 11, 0.6), 0 0 4px rgba(245, 158, 11, 0.4); }
    .bulb.active-symbol { background-color: #EF4444; box-shadow: 0 0 12px rgba(239, 68, 68, 0.6), 0 0 4px rgba(239, 68, 68, 0.4); }

    .bulb-text {
        font-size: 11px;
        font-weight: 700;
        color: #64748B; /* slate-500 */
        transition: all 0.3s ease;
        letter-spacing: -0.01em;
    }

    .bulb-container.active .bulb-text {
        color: #0F172A; /* slate-900 */
    }

    .bulb-container {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 0;
    }

    @keyframes bounceSubtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    .animate-bounce-subtle {
        animation: bounceSubtle 3s ease-in-out infinite;
    }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('registrationForm', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: config.billingCycle || 'monthly',
        packages: config.packages,
        centerName: config.centerName,
        subdomain: config.subdomain,
        manuallyEditedSubdomain: config.manuallyEditedSubdomain,
        showPassword: false,
        password: '',
        password_confirmation: '',
        couponCode: '',
        showCouponInput: false,
        couponStatus: 'none',
        couponMessage: '',
        discountValue: 0,
        discountType: 'percentage',
        discountText: '',
        discountText: '',
        isApplyingCoupon: false,
        userCountry: 'default',

        async init() {
            try {
                const response = await fetch('https://ipapi.co/json/');
                const data = await response.json();
                this.userCountry = data.country_code || 'default';
            } catch(e) { console.log('IP fetch failed', e); }
        },

        get currentPlan() {
            return this.packages.find(p => p.slug === this.selectedPlan) || {name: '', price: ''};
        },

        get activePriceRaw() {
             return this.billingCycle === 'yearly' ? (this.currentPlan.yearly_price_raw || 0) : (this.currentPlan.price_raw || 0);
        },

        get activePriceValue() {
             return this.billingCycle === 'yearly' ? (this.currentPlan.yearly_price_value || '') : (this.currentPlan.price_value || '');
        },

        generateSlug(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        },

        cleanSlug(text) {
            return text.toString().toLowerCase().replace(/[^a-z0-9\-]/g, '');
        },

        get isPasswordMatch() {
            return this.password === this.password_confirmation && this.password.length > 0;
        },

        get passwordCriteria() {
            return {
                length: this.password.length >= 8,
                upper: /[A-Z]/.test(this.password),
                lower: /[a-z]/.test(this.password),
                number: /[0-9]/.test(this.password),
                symbol: /[!@#$%^&*(),.?{}:|<>]/.test(this.password)
            }
        },

        async validateCoupon() {
            if (!this.couponCode) return;
            this.isApplyingCoupon = true;
            this.couponStatus = 'loading';
            try {
                const response = await fetch(`/api/coupons/validate?code=${this.couponCode}&plan=${this.selectedPlan}`);
                const data = await response.json();
                if (data.valid) {
                    this.couponStatus = 'valid';
                    this.couponMessage = data.message;
                    this.discountText = data.discount_text;
                    this.discountType = data.type;
                    this.discountValue = data.value;
                } else {
                    this.couponStatus = 'invalid';
                    this.couponMessage = data.message;
                    this.discountValue = 0;
                }
            } catch (e) {
                this.couponStatus = 'invalid';
                this.couponMessage = '<?php echo e(__('auth.coupon_error')); ?>';
            } finally {
                this.isApplyingCoupon = false;
            }
        },

        get couponDiscountAmount() {
            if (this.couponStatus !== 'valid') return 0;
            const price = this.activePriceRaw || 0;
            if (this.discountType === 'percentage') {
                return (price * (this.discountValue / 100));
            }
            return Math.min(this.discountValue, price);
        },

        get finalPrice() {
            const price = this.activePriceRaw || 0;
            return Math.max(0, price - this.couponDiscountAmount);
        }
    }))
})
</script>

<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay" 
     style="padding-top: 120px;"
     x-data="registrationForm({
        selectedPlan: <?php echo e(Js::from(request('plan', $packages->firstWhere('is_default', true)->slug ?? $packages->first()->slug ?? ''))); ?>,
        billingCycle: <?php echo e(Js::from(request('cycle', 'monthly'))); ?>,
        packages: <?php echo e(Js::from($packagesData)); ?>,
        centerName: <?php echo e(Js::from(old('center_name'))); ?>,
        subdomain: <?php echo e(Js::from(old('subdomain'))); ?>,
        manuallyEditedSubdomain: <?php echo e(old('subdomain') ? 'true' : 'false'); ?>,
        userCountry: 'default'
     })"
     dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
    
    <!-- Main Centered Card Container -->
    <div class="w-full max-w-4xl bg-white rounded-[48px] shadow-2xl shadow-slate-200/60 overflow-hidden flex flex-col lg:flex-row border border-slate-100 min-h-[640px] animate-fade-in-up" style="max-width: 960px;">
        
        <!-- Left Panel: Elite Compact Plan Selection -->
        <div class="lg:w-[32%] text-white flex flex-col p-8 lg:p-10" style="background: linear-gradient(135deg, #172554 0%, #1e40af 100%);">
            <div class="flex-1 flex flex-col w-full">
            <div class="space-y-8 flex-1">
                <?php if(app()->getLocale() == 'ar'): ?>
                <div class="mb-2">
                    <span class="text-[10px] text-blue-300 font-bold uppercase tracking-widest"><?php echo e(__('auth.register.subtitle')); ?></span>
                </div>
                <?php endif; ?>
                <h2 class="text-2xl lg:text-3xl font-bold mb-4 font-arabic leading-tight">
                    <?php echo e(__('auth.register.branding_title')); ?>

                </h2>
                    <p class="text-blue-200/80 text-base font-arabic font-light leading-relaxed">
                        <?php echo e(__('auth.register.branding_subtitle')); ?>

                    </p>
                </div>

                <div class="space-y-3">
                    <label class="text-[9px] font-black text-blue-300/70 uppercase tracking-widest px-1 mb-1 block"><?php echo e(__('auth.register.select_plan')); ?></label>
                    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div @click="selectedPlan = '<?php echo e($package->slug); ?>'"
                        class="w-full text-center p-6 plan-card-compact cursor-pointer relative group/card mb-6 border transition-all duration-300 overflow-hidden"
                        :class="selectedPlan === '<?php echo e($package->slug); ?>' ? 'selected' : 'border-white/5 hover:border-white/20 bg-white/[0.02]'">
                        
                        <!-- Mini Badge for Type -->
                        <div class="inline-flex mb-3">
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-blue-300/40 px-3 py-1 bg-white/5 rounded-full border border-white/5 group-hover/card:text-blue-200 group-hover/card:bg-blue-500/10 transition-all">
                                <?php echo e(app()->getLocale() == 'ar' ? $package->name : $package->name_en); ?>

                            </span>
                        </div>

                        <!-- Ticket Price -->
                        <!-- Ticket Price -->
                            <div class="flex flex-col items-center">
                                <template x-if="billingCycle === 'yearly' ? (<?php echo e($package->price * 12); ?> > <?php echo e($package->yearly_price ?: ($package->price * 10)); ?>) : (<?php echo e($package->old_price ?? 0); ?> > <?php echo e($package->price); ?>)">
                                    <div class="flex items-center gap-3 mb-4 px-5 py-2 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 shadow-lg shadow-emerald-500/10">
                                        <del class="text-[14px] text-white/40 font-bold decoration-white/20" 
                                             x-text="billingCycle === 'yearly' ? '<?php echo e(number_format(($package->old_price ?: $package->price) * 12, 0)); ?>' : '<?php echo e(number_format($package->old_price ?: 0, 0)); ?>'">
                                        </del>
                                        <span class="w-1.5 h-4 bg-white/10 rounded-full"></span>
                                        <span class="text-[16px] font-black text-emerald-400 uppercase tracking-tighter">
                                            <?php echo e(__('auth.register.save')); ?> 
                                            <span x-text="billingCycle === 'yearly' ? '<?php echo e(number_format((($package->old_price ?: $package->price) * 12) - ($package->yearly_price ?: ($package->price * 10)), 0)); ?>' : '<?php echo e(number_format(($package->old_price ?: 0) - $package->price, 0)); ?>'"></span>
                                        </span>
                                    </div>
                                </template>

                            <div class="flex items-start justify-center transition-transform duration-500 group-hover/card:scale-105">
                                <span class="text-5xl font-black text-white tracking-tighter leading-none" 
                                      x-text="billingCycle === 'yearly' ? '<?php echo e(number_format($package->yearly_price ?: ($package->price * 10), 0)); ?>' : '<?php echo e(number_format($package->price, 0)); ?>'">
                                    <?php echo e(number_format($package->price, 0)); ?>

                                </span>
                                <div class="flex flex-col ml-1 rtl:mr-1 rtl:ml-0 mt-1">
                                    <span class="text-[14px] font-bold text-white/30"><?php echo e(\App\Models\SiteSetting::get('currency_symbol', 'جنيه')); ?></span>
                                    <span class="text-[10px] font-bold text-white/50" x-text="billingCycle === 'yearly' ? '<?php echo e(__('landing.pricing.per_year') ?? '/سنوي'); ?>' : '<?php echo e(__('landing.pricing.per_month') ?? '/شهري'); ?>'"></span>
                                </div>
                            </div>
                            <!-- Monthly Equivalent for Yearly -->
                            <template x-if="billingCycle === 'yearly'">
                                <div class="mt-1 text-[11px] text-white/40 font-bold">
                                    (<?php echo e(__('landing.pricing.equivalent_to') ?? 'ما يعادل'); ?> 
                                    <span x-text="Math.round((<?php echo e($package->yearly_price ?: ($package->price * 10)); ?>) / 12)"></span>
                                    <?php echo e(\App\Models\SiteSetting::get('currency_symbol', 'جنيه')); ?>/<?php echo e(__('landing.pricing.month_short') ?? 'شهر'); ?>)
                                </div>
                            </template>

                            <?php if($package->discount_label): ?>
                            <div class="mt-4 text-[9px] font-bold text-blue-300/40 uppercase tracking-[0.15em] border-t border-white/5 pt-3 w-full">
                                <?php echo e($package->discount_label); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="space-y-1.5 mt-3">
                            <?php
                                $features = $package->features->filter(function($feature) {
                                    $val = $feature->pivot->value;
                                    // Skip disabled boolean features or 0 limits
                                    if ($feature->type === 'boolean' && ($val === 'false' || $val === '0' || !$val || trim($val) === '')) return false;
                                    if ($feature->type === 'limit' && ($val === '0' || $val === 0 || !$val)) return false;
                                    return true;
                                })->take(6);
                            ?>
                            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $val = $feature->pivot->value;
                                $displayVal = $val;
                                if ($val === '-1') {
                                    $displayVal = __('features.unlimited') ?? 'غير محدود';
                                } elseif ($feature->type === 'boolean') {
                                    $displayVal = ''; // Don't show value for boolean, just the checkmark and name
                                } elseif (is_numeric($val) && $val > 0) {
                                     $unit = '';
                                     if ($feature->code === 'max_students') $unit = ' ' . (__('admin.students') ?? 'طالب');
                                     if ($feature->code === 'max_instructors') $unit = ' ' . (__('admin.instructors') ?? 'مدرس');
                                     $displayVal = $val . $unit;
                                }
                            ?>
                            <div class="flex items-center gap-2 text-[10px] text-blue-100/90 font-arabic font-medium opacity-80 group-hover:opacity-100 transition-opacity">
                                <div class="flex-shrink-0 w-3 h-3 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-2 h-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>
                                    <?php
                                        $valKey = 'features.' . $feature->code;
                                        $translatedName = __($valKey);
                                        if ($translatedName === $valKey) {
                                            $translatedName = app()->getLocale() === 'en' && $feature->name_en ? $feature->name_en : $feature->name;
                                        }
                                    ?>
                                    <?php echo e($translatedName); ?>

                                    <?php if($displayVal): ?>
                                        <span class="opacity-70">(<?php echo e($displayVal); ?>)</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($package->features->count() > 6): ?>
                            <p class="text-[9px] text-blue-300 font-bold mt-1 pr-5">+ <?php echo e($package->features->count() - 6); ?> <?php echo e(__('auth.register.more_features')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-auto pt-8 border-t border-white/5 opacity-60">
                    <p class="text-[10px] text-blue-200 font-arabic">
                        <?php echo e(__('auth.register.join_leaders')); ?>

                    </p>
                </div>
            </div>
        </div>

            <!-- Right Panel: Compact Registration Form -->
            <div class="lg:w-[68%] bg-white flex flex-col p-8 lg:p-12 relative">
                <div class="absolute top-6 <?php echo e(app()->getLocale() == 'ar' ? 'left-8' : 'right-8'); ?> z-10">
                    <span class="text-sm text-slate-500">
                        <?php echo e(__('auth.login.no_account_link')); ?>

                        <a href="<?php echo e(route('login.portal')); ?>" class="text-blue-600 font-bold hover:text-blue-700 transition-colors ml-1"><?php echo e(__('auth.login.title')); ?></a>
                    </span>
                </div>

                <div class="max-w-[440px] mx-auto pt-10">
                    <div class="mb-8">
                        <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 mb-2 font-arabic tracking-tight">
                            <?php echo e(__('auth.register.title')); ?>

                        </h1>
                        <p class="text-slate-500 text-sm font-arabic font-light">
                            <?php echo e(__('auth.register.subtitle')); ?>

                        </p>
                    </div>
                    <form action="<?php echo e(route('register.submit')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Global Error Alert -->
                        <?php if($errors->any()): ?>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                                        <h3 class="text-sm font-medium text-red-800 font-arabic">
                                            <?php echo e(__('auth.register.registration_error')); ?>

                                        </h3>
                                        <div class="mt-2 text-sm text-red-700 font-arabic">
                                            <ul class="list-disc pl-5 rtl:pr-5 rtl:pl-0 space-y-1">
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="plan" x-model="selectedPlan">
                        <input type="hidden" name="billing_cycle" x-model="billingCycle">
                        <input type="hidden" name="country_code" x-model="userCountry">

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="label-compact px-1 font-arabic"><?php echo e(__('auth.register.center_name')); ?></label>
                                <input type="text" name="center_name" x-model="centerName"
                                    @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); }"
                                    class="w-full h-12 input-compact px-4 text-sm font-medium font-arabic text-slate-900"
                                    placeholder="<?php echo e(__('auth.register.center_name_placeholder')); ?>" 
                                    required>
                                <?php $__errorArgs = ['center_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-bold mt-1 px-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>



                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="label-compact px-1 font-arabic"><?php echo e(__('auth.register.full_name')); ?></label>
                                    <input type="text" name="name" 
                                        class="w-full h-12 input-compact px-4 text-sm font-medium font-arabic text-slate-900"
                                        required value="<?php echo e(old('name')); ?>">
                                </div>
                                <div class="space-y-1">
                                    <label class="label-compact px-1 font-arabic"><?php echo e(__('auth.register.email')); ?></label>
                                    <input type="email" name="email" 
                                        class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900"
                                        placeholder="mail@example.com"
                                        required value="<?php echo e(old('email')); ?>">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex justify-between items-center px-1">
                                    <label class="label-compact font-arabic"><?php echo e(__('auth.register.password')); ?></label>
                                    <button type="button" @click="showPassword = !showPassword" class="text-[10px] font-black text-blue-600 uppercase tracking-tighter">
                                        <span x-text="showPassword ? '<?php echo e(__('auth.register.hide')); ?>' : '<?php echo e(__('auth.register.show')); ?>'"></span>
                                    </button>
                                </div>
                                <!-- Password Fields with Toggle -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="relative group/pass">
                                        <input :type="showPassword ? 'text' : 'password'" type="password" name="password" x-model="password"
                                            class="w-full h-12 input-compact px-4 pr-11 rtl:pl-11 rtl:pr-4 text-sm font-medium text-slate-900"
                                            placeholder="••••••••" required>
                                        <button type="button" @click="showPassword = !showPassword" 
                                            class="absolute right-3 rtl:left-3 rtl:right-auto top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors p-1">
                                            <i class="bi" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                        </button>
                                    </div>
                                    <div class="relative group/pass">
                                        <input :type="showPassword ? 'text' : 'password'" type="password" name="password_confirmation" x-model="password_confirmation"
                                            class="w-full h-12 input-compact px-4 pr-11 rtl:pl-11 rtl:pr-4 text-sm font-medium text-slate-900"
                                            :class="password_confirmation.length > 0 && !isPasswordMatch ? 'border-red-300 bg-red-50 shadow-[0_0_0_4px_rgba(239,68,68,0.1)]' : ''"
                                            placeholder="<?php echo e(__('auth.register.confirm_password')); ?>" required>
                                    </div>
                                </div>

                                <!-- Password Strength Bulbs Refined -->
                                <div class="bulb-section">
                                    <div class="flex flex-wrap items-center justify-between gap-y-3">
                                        <!-- Length -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.length ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.length ? 'active-length' : ''"></div>
                                            <span class="bulb-text font-arabic"><?php echo e(__('auth.register.password_criteria.chars')); ?></span>
                                        </div>
                                        <!-- Uppercase -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.upper ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.upper ? 'active-upper' : ''"></div>
                                            <span class="bulb-text font-arabic"><?php echo e(__('auth.register.password_criteria.upper')); ?></span>
                                        </div>
                                        <!-- Lowercase -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.lower ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.lower ? 'active-lower' : ''"></div>
                                            <span class="bulb-text font-arabic"><?php echo e(__('auth.register.password_criteria.lower')); ?></span>
                                        </div>
                                        <!-- Number -->
                                        <div class="bulb-container pr-2" :class="passwordCriteria.number ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.number ? 'active-number' : ''"></div>
                                            <span class="bulb-text font-arabic"><?php echo e(__('auth.register.password_criteria.numbers')); ?></span>
                                        </div>
                                        <!-- Symbol -->
                                        <div class="bulb-container" :class="passwordCriteria.symbol ? 'active' : ''">
                                            <div class="bulb" :class="passwordCriteria.symbol ? 'active-symbol' : ''"></div>
                                            <span class="bulb-text font-arabic"><?php echo e(__('auth.register.password_criteria.symbols')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coupon Field -->
                            <div class="space-y-2 pt-2">
                                <template x-if="!showCouponInput && couponStatus !== 'valid'">
                                    <button type="button" @click="showCouponInput = true" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center gap-2 group font-arabic transition-all">
                                        <i class="bi bi-tag-fill group-hover:rotate-12 transition-transform"></i>
                                        <?php echo e(__('auth.register.have_coupon') ?? 'هل لديك كود خصم؟'); ?>

                                    </button>
                                </template>

                                <div x-show="showCouponInput || couponStatus === 'valid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-1">
                                    <label class="label-compact px-1 font-arabic"><?php echo e(__('admin.coupon_code')); ?></label>
                                    <div class="relative">
                                        <input type="text" name="coupon_code" x-model="couponCode" @input.debounce.500ms="validateCoupon()"
                                            class="w-full h-12 input-compact px-4 text-sm font-medium text-slate-900 uppercase"
                                            placeholder="PROMO20" :class="couponStatus === 'valid' ? 'border-emerald-300 bg-emerald-50' : (couponStatus === 'invalid' ? 'border-red-300 bg-red-50' : '')">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                            <template x-if="couponStatus === 'loading'">
                                                <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                            </template>
                                            <template x-if="couponStatus === 'valid'">
                                                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                                            </template>
                                            <template x-if="couponStatus === 'invalid'">
                                                <button type="button" @click="couponCode = ''; couponStatus = 'none'; showCouponInput = false" class="text-red-500 hover:text-red-700">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    <template x-if="couponMessage">
                                        <p class="text-[10px] font-bold mt-1 px-1" :class="couponStatus === 'valid' ? 'text-emerald-600' : 'text-red-600'" x-text="couponMessage"></p>
                                    </template>
                                    <template x-if="couponStatus === 'valid'">
                                        <div class="mt-2 p-2 bg-emerald-500/10 border border-emerald-500/20 rounded-lg flex items-center justify-between">
                                            <span class="text-[10px] font-bold text-emerald-700 font-arabic"><?php echo e(__('auth.register.discount_applied')); ?> (<span x-text="couponCode"></span>)</span>
                                            <span class="text-xs font-black text-emerald-700" x-text="discountText"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Indicator (Ticket-Style) -->
                        <div class="mt-10 mb-8 p-6 rounded-[32px] bg-white border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] transition-all duration-300 group/summary">
                            <div class="flex flex-col items-center md:items-start text-center md:text-start">
                                <span class="text-[11px] font-black text-blue-500/40 uppercase tracking-[0.2em] mb-2"><?php echo e(__('auth.register.selected_plan')); ?></span>
                                <h3 class="text-xl font-black text-slate-900 font-arabic leading-none" x-text="currentPlan.name"></h3>
                            </div>
                            
                            <div class="flex flex-col items-center md:items-end">
                                <div class="flex flex-col items-center md:items-end gap-1">
                                    <!-- Original Package Price (Crossed out if package has its own discount) -->
                                    <template x-if="currentPlan.old_price_raw">
                                        <div class="flex items-center gap-2 opacity-30">
                                            <del class="text-sm font-bold" x-text="billingCycle === 'yearly' ? (currentPlan.old_price_raw * 12).toLocaleString() : currentPlan.old_price_value"></del>
                                            <span class="text-[10px] font-bold" x-text="currentPlan.currency"></span>
                                        </div>
                                    </template>

                                    <!-- Current Selection Price and Calculation -->
                                    <div class="flex flex-col items-center md:items-end">
                                        <!-- Price before coupon if coupon added -->
                                        <template x-if="couponStatus === 'valid'">
                                            <div class="flex flex-col items-center md:items-end mb-2">
                                                <div class="text-xs font-bold text-slate-400 line-through" x-text="activePriceValue + ' ' + currentPlan.currency"></div>
                                                <div class="text-[10px] font-black text-emerald-600 uppercase tracking-tight">
                                                    <?php echo e(__('auth.register.discount_applied')); ?>: -<span x-text="couponDiscountAmount.toLocaleString() + ' ' + currentPlan.currency"></span>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Final Final Price -->
                                        <div class="flex items-start justify-end transition-all text-blue-600">
                                            <span class="text-5xl font-black tracking-tighter leading-none" x-text="finalPrice.toLocaleString()"></span>
                                            <div class="flex flex-col ml-1 rtl:mr-1 rtl:ml-0 mt-1">
                                                <span class="text-[14px] font-bold opacity-40" x-text="currentPlan.currency"></span>
                                                <span class="text-[10px] font-bold opacity-40 -mt-1" x-text="billingCycle === 'yearly' ? '<?php echo e(__('landing.pricing.per_year') ?? '/سنوياً'); ?>' : '<?php echo e(__('landing.pricing.per_month') ?? '/شهرياً'); ?>'"></span>
                                            </div>
                                        </div>
                                        <!-- Monthly Equivalent note for Yearly in Summary -->
                                        <template x-if="billingCycle === 'yearly'">
                                            <div class="text-[10px] font-bold text-slate-400 mt-1 text-end">
                                                (<?php echo e(__('landing.pricing.equivalent_to') ?? 'ما يعادل'); ?> 
                                                <span x-text="Math.round(finalPrice / 12).toLocaleString()"></span>
                                                <span x-text="currentPlan.currency"></span>/<?php echo e(__('landing.pricing.month_short') ?? 'شهر'); ?>)
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                :disabled="password.length > 0 && !isPasswordMatch"
                                class="w-full h-16 bg-blue-600 hover:bg-blue-700 text-white rounded-[24px] shadow-xl shadow-blue-500/20 flex items-center justify-center gap-3 group transition-all duration-300 active:scale-95">
                                <span class="text-xl font-black font-arabic"><?php echo e(__('auth.register.cta_main')); ?></span>
                                <svg class="w-6 h-6 transform group-hover:translate-x-1 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                            
                            <p class="mt-6 text-center text-[11px] text-slate-400 font-arabic leading-relaxed">
                                <?php echo e(__('auth.register.terms_prefix')); ?>

                                <a href="#" class="text-slate-900 font-bold hover:underline"><?php echo e(__('auth.register.terms_of_service')); ?></a> 
                                <?php echo e(__('auth.register.and')); ?> 
                                <a href="#" class="text-slate-900 font-bold hover:underline"><?php echo e(__('auth.register.privacy_policy')); ?></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/auth/register.blade.php ENDPATH**/ ?>