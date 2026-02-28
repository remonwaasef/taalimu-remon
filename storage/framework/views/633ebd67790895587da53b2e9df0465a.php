

<?php $__env->startSection('content'); ?>
<!-- Import Cairo & Outfit Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('googleRegistration', (config) => ({
        selectedPlan: config.selectedPlan,
        billingCycle: 'monthly',
        packages: config.packages,
        centerName: '<?php echo e(old('center_name')); ?>',
        subdomain: '<?php echo e(old('subdomain')); ?>',
        manuallyEditedSubdomain: <?php echo e(old('subdomain') ? 'true' : 'false'); ?>,
        subdomainStatus: 'idle',
        subdomainMessage: '',
        isSubmitting: false,
        userCountry: 'default',

        async init() {
            try {
                const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                const data = await response.json();
                this.userCountry = data.country || '';
            } catch(e) { console.log('IP fetch failed', e); }
        },

        get currentPlan() {
            return this.packages.find(p => p.slug === this.selectedPlan) || this.packages[0];
        },

        getPriceData(pkg) {
             if(!pkg) return { amount: 0, currency: '$', yearly: 0, old: 0, discount_label: '' };
             let prices = pkg.regional_prices || {};
             let data = {
                 amount: parseFloat(pkg.price_raw),
                 yearly: parseFloat(pkg.yearly_price_raw),
                 currency: pkg.currency || '$',
                 old: parseFloat(pkg.old_price_raw || 0),
                 discount_label: pkg.discount_label
             };

             if (this.userCountry && prices[this.userCountry]) {
                 let r = prices[this.userCountry];
                 data.currency = r.currency || data.currency;
                 data.amount = parseFloat(r.amount || data.amount);
                 data.yearly = parseFloat(r.yearly_price || (data.amount * 10));
                 data.old = parseFloat(r.old_price || 0);
                 data.discount_label = r.discount_label || data.discount_label;
             }
             return data;
        },

        get currentPriceData() {
             return this.getPriceData(this.currentPlan);
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

        async checkSubdomain() {
            if (!this.subdomain) {
                this.subdomainStatus = 'idle';
                this.subdomainMessage = '';
                return;
            }
            this.subdomain = this.cleanSlug(this.subdomain);
            this.subdomainStatus = 'loading';
            try {
                const response = await fetch(`/api/validate-subdomain?subdomain=${this.subdomain}`);
                const data = await response.json();
                this.subdomainStatus = data.available ? 'valid' : 'invalid';
                this.subdomainMessage = data.message;
            } catch (e) {
                this.subdomainStatus = 'idle';
            }
        },

        handleSubmit(e) {
            if (this.subdomainStatus === 'invalid') {
                e.preventDefault();
                alert('<?php echo e(app()->getLocale() == 'ar' ? 'هذا الرابط مستخدم بالفعل' : 'This subdomain is already taken'); ?>');
                return;
            }
            this.isSubmitting = true;
        }
    }))
});
</script>

<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay" 
     style="padding-top: 120px;"
     x-data="googleRegistration({
        selectedPlan: <?php echo e(Js::from($selectedPlanSlug)); ?>,
        packages: <?php echo e(Js::from($packagesData)); ?>

     })"
     dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
    
    <!-- Main Centered Card Container (Simplified Single Column) -->
    <div class="w-full max-w-xl bg-white rounded-[2.5rem] shadow-2xl shadow-blue-900/5 overflow-hidden border border-slate-100/50 animate-fade-in-up md:backdrop-blur-xl relative">
        
        <!-- Header & Account Info -->
        <div class="bg-white p-8 lg:p-12 pb-0">
            
            <div class="mb-10 animate-fade-in">
                <div class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-[2rem] shadow-sm group hover:border-brand-secondary/30 transition-all">
                    <div class="w-14 h-14 bg-white border-2 border-brand-secondary/10 rounded-full flex items-center justify-center text-brand-secondary font-black text-2xl group-hover:scale-110 transition-transform shadow-sm">
                        <?php echo e(mb_substr(session('google_user.name', 'U'), 0, 1)); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-900 text-sm truncate uppercase tracking-tight"><?php echo e(session('google_user.name', 'User')); ?></p>
                        <p class="text-slate-500 text-xs truncate font-medium opacity-80"><?php echo e(session('google_user.email')); ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-black ring-1 ring-inset ring-emerald-500/20 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <?php echo e(app()->getLocale() == 'ar' ? 'حساب موثق' : 'Verified Account'); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Contextual Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4 font-arabic leading-tight">
                    <?php echo e(app()->isLocale('ar') ? 'تأكيد الحساب' : 'Confirm Account'); ?>

                </h1>
                <p class="text-slate-500 text-base font-arabic font-medium opacity-80 max-w-[320px] mx-auto">
                    <?php echo e(app()->isLocale('ar') ? 'خطوة واحدة لنبدأ في تجهيز منصتك التعليمية' : 'One last step to complete your platform setup'); ?>

                </p>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8 lg:p-12 pt-0">
            <?php if($errors->any()): ?>
                <div class="bg-red-50 border-2 border-red-50 rounded-3xl p-6 mb-8 animate-shake">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 text-red-600">
                            <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-red-900 font-arabic mb-1"><?php echo e(__('auth.register.registration_error')); ?></h3>
                            <ul class="text-[13px] text-red-700 font-arabic opacity-90 space-y-0.5">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li>• <?php echo e($error); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('google.complete-registration')); ?>" method="POST" class="space-y-6" @submit="handleSubmit($event)">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="plan" x-model="selectedPlan">

                
                <div class="space-y-1.5">
                    <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide"><?php echo e(__('auth.register.center_name')); ?></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-building"></i></div>
                        <input type="text" name="center_name" x-model="centerName"
                            @input="if(!manuallyEditedSubdomain) { subdomain = generateSlug(centerName); checkSubdomain(); }"
                            class="w-full h-14 ps-12 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-arabic focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                            placeholder="<?php echo e(__('auth.register.center_name_placeholder')); ?>" required autofocus>
                    </div>
                </div>

                
                <div class="space-y-1.5">
                    <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide"><?php echo e(app()->isLocale('ar') ? 'رابط المنصة' : 'Platform Link'); ?></label>
                    <div class="relative flex items-center w-full group" dir="ltr">
                        <div class="absolute left-0 inset-y-0 flex items-center px-4 pointer-events-none text-brand-secondary font-black text-xs bg-brand-secondary/5 border-r border-brand-secondary/10 rounded-l-2xl">https://</div>
                        <input type="text" name="subdomain" x-model="subdomain"
                            @input="manuallyEditedSubdomain = true; subdomain = cleanSlug(subdomain);"
                            @input.debounce.500ms="checkSubdomain()"
                            class="w-full h-14 pl-[80px] pr-[115px] bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold font-sans focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                            placeholder="center-name" required>
                        <div class="absolute right-0 inset-y-0 flex items-center pr-4 pointer-events-none text-slate-400 font-bold text-xs gap-3">
                            <span>.taalimu.com</span>
                            <div class="flex items-center justify-center w-5 h-5">
                                <template x-if="subdomainStatus === 'loading'"><div class="w-4 h-4 border-2 border-brand-secondary border-t-transparent rounded-full animate-spin"></div></template>
                                <template x-if="subdomainStatus === 'valid'"><i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i></template>
                                <template x-if="subdomainStatus === 'invalid'"><i class="bi bi-x-circle-fill text-red-500 text-lg"></i></template>
                            </div>
                        </div>
                    </div>
                    <p x-show="subdomainMessage" :class="subdomainStatus === 'valid' ? 'text-emerald-600' : 'text-red-500'" 
                       class="text-[11px] font-bold px-2 mt-1 animate-fade-in" x-text="subdomainMessage"></p>
                </div>

                
                <div class="space-y-1.5">
                    <label class="text-[13px] font-black text-slate-400 px-1 font-arabic uppercase tracking-wide"><?php echo e(__('auth.register.phone')); ?></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 start-0 ps-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-brand-secondary transition-colors"><i class="bi bi-telephone"></i></div>
                        <input type="text" name="phone" value="<?php echo e(old('phone')); ?>"
                            class="w-full h-14 ps-12 pe-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-base font-bold focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-secondary/5 focus:border-brand-secondary transition-all shadow-inner"
                            placeholder="010xxxxxxx" required>
                    </div>
                </div>

                <!-- Simplified Price Summary Card -->
                <div class="p-6 rounded-3xl bg-slate-50/80 border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo e(__('auth.register.selected_plan')); ?></span>
                            <h3 class="text-xl font-black text-slate-900 font-arabic" x-text="currentPlan.name"></h3>
                        </div>
                        <div class="text-right">
                            <div class="flex items-baseline gap-1 text-brand-secondary">
                                <span class="text-3xl font-black tracking-tighter" x-text="currentPriceData.amount.toLocaleString()"></span>
                                <span class="text-sm font-bold opacity-60" x-text="currentPriceData.currency"></span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400">/<?php echo e(__('landing.pricing.month_short') ?? 'شهر'); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="pt-6">
                    <button type="submit" :disabled="isSubmitting || subdomainStatus === 'invalid'"
                            class="w-full h-16 rounded-full flex items-center justify-center gap-4 group bg-brand-secondary text-white shadow-2xl shadow-brand-secondary/20 hover:shadow-brand-secondary/40 hover:-translate-y-1 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale">
                        <span x-show="!isSubmitting" class="text-xl font-black font-arabic"><?php echo e(__('auth.register.cta_main')); ?></span>
                        <span x-show="isSubmitting" class="flex items-center gap-3">
                            <div class="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                            <?php echo e(__('auth.register.processing') ?? 'جاري المعالجة...'); ?>

                        </span>
                        <i x-show="!isSubmitting" class="bi bi-rocket-takeoff text-2xl group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[11px] text-slate-400 font-arabic leading-relaxed">
                    <?php echo e(__('auth.register.terms_prefix')); ?>

                    <a href="<?php echo e(route('terms')); ?>" class="text-slate-900 font-black hover:underline underline-offset-4"><?php echo e(__('auth.register.terms_of_service')); ?></a> 
                    <?php echo e(__('auth.register.and')); ?> 
                    <a href="<?php echo e(route('privacy')); ?>" class="text-slate-900 font-black hover:underline underline-offset-4"><?php echo e(__('auth.register.privacy_policy')); ?></a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/auth/complete-google-registration.blade.php ENDPATH**/ ?>