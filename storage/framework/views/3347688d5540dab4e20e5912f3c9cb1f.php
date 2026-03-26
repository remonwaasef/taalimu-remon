<section id="features" class="relative py-24 bg-[#f8fafc] overflow-hidden section-wave section-wave-white">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white rounded-full blur-[120px] -z-10 opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#f0fdf4] rounded-full blur-[100px] -z-10 opacity-50"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-100 mb-6 shadow-sm">
                <span class="text-xs font-extrabold text-[#22c55e] uppercase tracking-[0.2em]"><?php echo e(__('landing.features.badge')); ?></span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-[1.1]">
                <?php echo __('landing.features.title'); ?>

            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium leading-relaxed">
                <?php echo e(__('landing.features.subtitle')); ?>

            </p>
        </div>

        <!-- Bento Grid: Mixed Sizes -->
        <?php
            $featuresData = [
                ['icon' => 'fa-users', 'color' => '#22c55e', 'bg' => '#f0fdf4', 'size' => 'large'],
                ['icon' => 'fa-calendar-check', 'color' => '#0ea5e9', 'bg' => '#f0f9ff', 'size' => 'large'],
                ['icon' => 'fa-comments', 'color' => '#22c55e', 'bg' => '#f0fdf4', 'size' => 'small'],
                ['icon' => 'fa-credit-card', 'color' => '#8b5cf6', 'bg' => '#f5f3ff', 'size' => 'small'],
                ['icon' => 'fa-chart-pie', 'color' => '#22c55e', 'bg' => '#f0fdf4', 'size' => 'small'],
                ['icon' => 'fa-shield-alt', 'color' => '#0ea5e9', 'bg' => '#f0f9ff', 'size' => 'small'],
                ['icon' => 'fa-clock', 'color' => '#f97316', 'bg' => '#fff7ed', 'size' => 'small'],
                ['icon' => 'fa-check-double', 'color' => '#8b5cf6', 'bg' => '#f5f3ff', 'size' => 'small']
            ];
        ?>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6" data-stagger>
            <?php $__currentLoopData = __('landing.features.items'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $data = $featuresData[$index] ?? $featuresData[0]; ?>
                <div
                    class="group relative bg-white rounded-3xl p-8 border border-slate-100/80 hover:shadow-premium transition-all duration-500 overflow-hidden
                    <?php echo e($index < 2 ? 'lg:col-span-2 lg:flex lg:items-center lg:gap-10' : ''); ?>"
                >
                    <!-- Hover Accent Line -->
                    <div class="absolute top-0 left-0 w-0 h-1 rounded-full group-hover:w-full transition-all duration-700" style="background-color: <?php echo e($data['color']); ?>;"></div>

                    <!-- Icon -->
                    <div class="w-16 h-16 rounded-2xl mb-6 <?php echo e($index < 2 ? 'lg:mb-0 lg:w-20 lg:h-20 lg:flex-shrink-0' : ''); ?> flex items-center justify-center transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3" style="background-color: <?php echo e($data['bg']); ?>; color: <?php echo e($data['color']); ?>;">
                        <i class="fas <?php echo e($data['icon']); ?> text-2xl <?php echo e($index < 2 ? 'lg:text-3xl' : ''); ?>"></i>
                    </div>

                    <div>
                        <!-- Title -->
                        <h3 class="text-lg font-extrabold text-[#0f172a] mb-3 tracking-tight group-hover:text-[#22c55e] transition-colors <?php echo e($index < 2 ? 'lg:text-xl' : ''); ?>">
                            <?php echo e($item['title']); ?>

                        </h3>

                        <!-- Description -->
                        <p class="text-slate-500 font-medium leading-[1.8] text-[14px]">
                            <?php echo e($item['description']); ?>

                        </p>
                    </div>

                    <!-- Learn More -->
                    <div class="mt-6 flex items-center gap-2 text-[#22c55e] opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 <?php echo e($index < 2 ? 'lg:hidden' : ''); ?>">
                        <span class="text-xs font-extrabold uppercase tracking-wider"><?php echo e(__('landing.features.learn_more')); ?></span>
                        <i class="fas fa-arrow-right text-[10px] rtl:rotate-180"></i>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/features.blade.php ENDPATH**/ ?>