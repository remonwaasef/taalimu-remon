<!-- Pain Points Section -->
<section class="py-24 bg-white relative overflow-hidden"
    x-data="{ visible: false }"
    x-intersect.once="visible = true"
>
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 mb-6">
                <span class="text-xs font-bold text-red-500 uppercase tracking-widest"><?php echo e(__('landing.pain_points.badge')); ?></span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                <?php echo e(__('landing.pain_points.title_prefix')); ?> <span class="text-emerald-600"><?php echo e(__('landing.pain_points.title_highlight')); ?></span> <?php echo e(__('landing.pain_points.title_suffix')); ?>

            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto font-medium">
                <?php echo e(__('landing.pain_points.subtitle')); ?>

            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto" data-stagger>
            <?php $__currentLoopData = [
                ['stat' => '35', 'suffix' => '%', 'color' => 'text-red-500', 'border' => 'border-red-100 hover:border-red-200', 'bg' => 'bg-red-50', 'key' => 'revenue_lost'],
                ['stat' => '12', 'suffix' => 'h', 'color' => 'text-orange-500', 'border' => 'border-orange-100 hover:border-orange-200', 'bg' => 'bg-orange-50', 'key' => 'time_wasted'],
                ['stat' => '24', 'suffix' => '/7', 'color' => 'text-blue-500', 'border' => 'border-blue-100 hover:border-blue-200', 'bg' => 'bg-blue-50', 'key' => 'complaints'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white rounded-2xl p-8 border <?php echo e($pain['border']); ?> transition-all duration-300 hover:-translate-y-1 hover:shadow-lg text-center">
                <div class="text-5xl font-black mb-3 tracking-tighter <?php echo e($pain['color']); ?>"
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
                <h3 class="text-base font-bold text-slate-800 mb-2"><?php echo e(__("landing.pain_points.{$pain['key']}.title")); ?></h3>
                <p class="text-slate-500 text-sm leading-relaxed"><?php echo e(__("landing.pain_points.{$pain['key']}.description")); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Second row -->
        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-6" data-stagger>
            <?php $__currentLoopData = [
                ['stat' => '100', 'suffix' => '%', 'color' => 'text-emerald-500', 'border' => 'border-emerald-100 hover:border-emerald-200', 'key' => 'manual_work'],
                ['stat' => '98', 'suffix' => '%', 'color' => 'text-violet-500', 'border' => 'border-violet-100 hover:border-violet-200', 'key' => 'revenue_lost'],
                ['stat' => '0', 'suffix' => '', 'color' => 'text-slate-800', 'border' => 'border-slate-200 hover:border-slate-300', 'key' => 'complaints'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white rounded-2xl p-8 border <?php echo e($pain['border']); ?> transition-all duration-300 hover:-translate-y-1 hover:shadow-lg text-center">
                <div class="text-5xl font-black mb-3 tracking-tighter <?php echo e($pain['color']); ?>">
                    <?php echo e($pain['stat']); ?><?php echo e($pain['suffix']); ?>

                </div>
                <h3 class="text-base font-bold text-slate-800 mb-2"><?php echo e(__("landing.pain_points.{$pain['key']}.title")); ?></h3>
                <p class="text-slate-500 text-sm leading-relaxed"><?php echo e(__("landing.pain_points.{$pain['key']}.description")); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>


    </div>
</section>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views\landing\partials\pain-points.blade.php ENDPATH**/ ?>