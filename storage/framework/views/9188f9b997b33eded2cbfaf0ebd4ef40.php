<section class="py-24 bg-white relative overflow-hidden section-wave" 
    x-data="{ 
        visible: false,
        animateCounter(el, target) {
            let current = 0;
            const step = Math.ceil(target / 40);
            const timer = setInterval(() => {
                current += step;
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = current + (el.dataset.suffix || '');
            }, 30);
        }
    }"
    x-intersect.once="visible = true"
>
    <!-- Sophisticated Background -->
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-[#f8fafc] rounded-full blur-[120px] -z-10 opacity-80"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#fef2f2] border border-red-50 mb-6">
                <span class="text-xs font-extrabold text-[#ef4444] uppercase tracking-[0.2em]"><?php echo e(__('landing.pain_points.badge') ?? 'The Problem'); ?></span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                <?php echo e(__('landing.pain_points.title_prefix')); ?> <span class="text-[#ef4444]"><?php echo e(__('landing.pain_points.title_highlight')); ?></span> <?php echo e(__('landing.pain_points.title_suffix')); ?>

            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                <?php echo e(__('landing.pain_points.subtitle')); ?>

            </p>
        </div>

        <!-- Pain Points Grid with Animated Counters -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8" data-stagger>
            <?php $__currentLoopData = [
                ['stat' => '35', 'suffix' => '%', 'icon' => 'fa-chart-line-down', 'iconColor' => '#ef4444', 'iconBg' => '#fef2f2', 'key' => 'revenue_lost'],
                ['stat' => '12', 'suffix' => 'h', 'icon' => 'fa-clock', 'iconColor' => '#f97316', 'iconBg' => '#fff7ed', 'key' => 'time_wasted'],
                ['stat' => '24', 'suffix' => '/7', 'icon' => 'fa-exclamation-triangle', 'iconColor' => '#0ea5e9', 'iconBg' => '#f0f9ff', 'key' => 'complaints'],
                ['stat' => '100', 'suffix' => '%', 'icon' => 'fa-hand-paper', 'iconColor' => '#22c55e', 'iconBg' => '#f0fdf4', 'key' => 'manual_work']
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white rounded-3xl p-8 border border-slate-100/50 shadow-sm hover:-translate-y-2 hover:shadow-premium transition-all duration-500 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform" style="background-color: <?php echo e($pain['iconBg']); ?>;">
                    <i class="fas <?php echo e($pain['icon']); ?> text-xl" style="color: <?php echo e($pain['iconColor']); ?>;"></i>
                </div>
                <div class="text-4xl font-black mb-3 tracking-tighter" style="color: <?php echo e($pain['iconColor']); ?>;"
                     x-data="{ shown: false }" x-intersect.once="shown = true"
                >
                    <span x-show="!shown">0<?php echo e($pain['suffix']); ?></span>
                    <span x-show="shown" x-text="''" x-init="
                        $watch('shown', v => {
                            if (!v) return;
                            let el = $el; let current = 0; let target = <?php echo e($pain['stat']); ?>;
                            let step = Math.ceil(target / 30);
                            let timer = setInterval(() => {
                                current += step;
                                if (current >= target) { current = target; clearInterval(timer); }
                                el.textContent = current + '<?php echo e($pain['suffix']); ?>';
                            }, 40);
                        })
                    ">0<?php echo e($pain['suffix']); ?></span>
                </div>
                <h3 class="text-base font-extrabold text-[#0f172a] mb-2"><?php echo e(__("landing.pain_points.{$pain['key']}.title")); ?></h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed"><?php echo e(__("landing.pain_points.{$pain['key']}.description")); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Trust Badges (Translated) -->
        <div class="mt-20 pt-12 border-t border-slate-100" data-animate>
            <div class="flex flex-wrap items-center justify-center gap-12 lg:gap-20">
                <?php $__currentLoopData = [
                    ['icon' => 'fa-shield-check', 'color' => '#10b981', 'label' => __('landing.pain_points.trust_secure') ?? 'Secure Payments'],
                    ['icon' => 'fa-whatsapp', 'color' => '#25D366', 'label' => __('landing.pain_points.trust_whatsapp') ?? 'WhatsApp Verified', 'brand' => true],
                    ['icon' => 'fa-graduation-cap', 'color' => '#6366f1', 'label' => __('landing.pain_points.trust_educators') ?? 'Educator Trusted']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center border border-slate-100 group-hover:scale-110 transition-transform">
                        <i class="<?php echo e(isset($badge['brand']) ? 'fab' : 'fas'); ?> <?php echo e($badge['icon']); ?>" style="color: <?php echo e($badge['color']); ?>;"></i>
                    </div>
                    <span class="font-extrabold text-slate-400 text-sm uppercase tracking-widest group-hover:text-slate-600 transition-colors"><?php echo e($badge['label']); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/pain-points.blade.php ENDPATH**/ ?>