<!-- Steps Section -->
<section class="py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-20" data-animate>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                <?php echo __('landing.features.title'); ?>

            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                <?php echo e(__('landing.features.subtitle')); ?>

            </p>
        </div>

        <!-- 3-Step Horizontal Timeline -->
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto relative" data-stagger>
            <!-- Connector Line -->
            <div class="hidden md:block absolute top-10 left-[16.66%] right-[16.66%] h-px bg-slate-200"></div>

            <?php $__currentLoopData = [
                ['num' => '01', 'icon' => 'fa-user-plus', 'color' => 'bg-emerald-500', 'key' => 0],
                ['num' => '02', 'icon' => 'fa-cogs', 'color' => 'bg-blue-500', 'key' => 1],
                ['num' => '03', 'icon' => 'fa-rocket', 'color' => 'bg-violet-500', 'key' => 2],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group text-center relative">
                <!-- Step Number Circle -->
                <div class="w-20 h-20 mx-auto mb-8 rounded-full <?php echo e($step['color']); ?> flex items-center justify-center text-white text-2xl shadow-lg group-hover:scale-110 transition-transform relative z-10">
                    <i class="fas <?php echo e($step['icon']); ?>"></i>
                </div>
                <div class="text-xs font-black text-slate-300 uppercase tracking-widest mb-3"><?php echo e(__('landing.automation.badge') ?? 'Step'); ?> <?php echo e($step['num']); ?></div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">
                    <?php echo e(__("landing.automation.step" . ($step['key'] + 1) . ".title")); ?>

                </h3>
                <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">
                    <?php echo e(__("landing.automation.step" . ($step['key'] + 1) . ".description")); ?>

                </p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views\landing\partials\automation.blade.php ENDPATH**/ ?>