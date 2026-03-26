<section 
    class="hero-section relative pt-24 lg:pt-32 pb-20 overflow-hidden bg-white" 
    id="hero"
>
    <!-- Subtle Premium Background -->
    <div class="absolute inset-0 bg-spotlight pointer-events-none"></div>
    <div class="absolute inset-0 bg-noise opacity-[0.02] pointer-events-none"></div>

    <div
        dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>"
        class="container relative mx-auto px-4 lg:px-12 z-10"
    >
        <div class="max-w-4xl mx-auto text-center">
            <!-- Elegant News Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-50 border border-slate-100 mb-10 transition-transform hover:scale-105 duration-300">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#22c55e]"></span>
                </span>
                <span class="text-[12px] font-bold text-slate-600 uppercase tracking-widest"><?php echo e(__('landing.hero.badge')); ?></span>
            </div>

            <!-- Balanced Premium Headline -->
            <h1 class="font-cairo text-4xl md:text-5xl lg:text-[4rem] font-extrabold text-[#0f172a] leading-[1.3] mb-8 tracking-[-0.01em]">
                أدِر مركزك التعليمي <span class="text-[#22c55e]">بذكاء</span><br class="hidden lg:block">
                ووفّر ساعات من العمل أسبوعياً
            </h1>

            <!-- Elegant Subheadline -->
            <p class="text-lg md:text-xl text-slate-500/80 mb-12 max-w-2xl mx-auto font-medium leading-[1.6]">
                <?php echo e(__('landing.hero.subtitle')); ?>

            </p>

            <!-- Premium Button Group -->
            <div class="flex flex-col sm:flex-row gap-5 justify-center mb-24">
                <a href="<?php echo e(route('register')); ?>" class="group relative bg-[#22c55e] text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-xl shadow-green-500/10 hover:shadow-green-500/25 transition-all active:scale-95">
                    <span class="relative z-10 flex items-center gap-2 justify-center">
                        <?php echo e(__('landing.hero.cta_primary')); ?>

                        <i class="fas fa-chevron-left text-xs rtl:rotate-0 rotate-180 opacity-70 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </a>
                <a href="#demo" class="bg-white text-slate-700 border border-slate-200 px-10 py-5 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all hover:border-slate-300">
                    <?php echo e(__('landing.hero.cta_secondary')); ?>

                </a>
            </div>

            <!-- The Browser Frame Mockup -->
            <div 
                class="relative max-w-5xl mx-auto opacity-0" 
                style="animation: heroFadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;"
            >
                <div class="browser-frame">
                    <div class="browser-header">
                        <div class="flex gap-1.5">
                            <div class="dot red"></div>
                            <div class="dot yellow"></div>
                            <div class="dot green"></div>
                        </div>
                        <div class="browser-url">app.taalimu.com</div>
                    </div>
                    <div class="bg-slate-50">
                        <img 
                            src="<?php echo e(asset('images/hero-dashboard.png')); ?>" 
                            alt="Taalimu Dashboard" 
                            class="w-full h-auto"
                        >
                    </div>
                </div>
                
                <!-- Floating Soft Orbs (Minimalist edition) -->
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-green-500/5 blur-[100px] rounded-full -z-10"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-500/5 blur-[100px] rounded-full -z-10"></div>
            </div>

            <!-- Refined Social Proof -->
            <div class="mt-32 py-10 border-t border-slate-100">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-10"><?php echo e(__('landing.hero.ratings') ?? 'Trusted by Modern Educators'); ?></p>
                <div class="flex flex-wrap justify-center gap-12 lg:gap-24">
                    <?php $__currentLoopData = [
                        ['value' => '500+', 'label' => __('landing.hero.trust_centers') ?? 'Centers'],
                        ['value' => '10,000+', 'label' => __('landing.hero.trust_students') ?? 'Students'],
                        ['value' => '98%', 'label' => __('landing.hero.trust_satisfaction') ?? 'Satisfaction'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group">
                        <div class="text-3xl lg:text-4xl font-extrabold text-[#0f172a] group-hover:text-[#22c55e] transition-colors duration-300"><?php echo e($stat['value']); ?></div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5"><?php echo e($stat['label']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/hero.blade.php ENDPATH**/ ?>