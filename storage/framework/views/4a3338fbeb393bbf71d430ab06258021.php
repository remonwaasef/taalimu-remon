<footer class="bg-[#0f172a] pt-24 pb-12 overflow-hidden relative">
    <!-- Sophisticated Accents -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-900/10 rounded-full blur-[120px] -z-10"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-5 gap-16 mb-20">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-8">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
                    <img src="<?php echo e(asset('images/brand/logo-full.png?v=3')); ?>" alt="<?php echo e(config('app.name')); ?>" class="h-10 w-auto brightness-0 invert">
                    <div class="flex flex-col">
                        <span class="font-black text-xl text-white leading-tight tracking-tighter">
                            <?php echo e(\App\Models\SiteSetting::get('site_name', 'Taalimu')); ?>

                        </span>
                        <span class="text-[10px] font-black text-[#22c55e] uppercase tracking-[0.2em]">
                            <?php echo e(__('landing.navbar.badge_short') ?? 'Smart Education'); ?>

                        </span>
                    </div>
                </a>
                <p class="text-slate-400 font-medium leading-relaxed max-w-sm">
                    <?php echo e(\App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle'))); ?>

                </p>
                <!-- Social Links -->
                <div class="flex items-center gap-4">
                    <?php $__currentLoopData = ['facebook-f', 'linkedin-in', 'twitter', 'instagram']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-slate-400 hover:bg-[#22c55e] hover:text-white transition-all duration-500 shadow-soft group">
                        <i class="fab fa-<?php echo e($social); ?> text-sm"></i>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Links Columns -->
            <div>
                <h4 class="text-white font-black text-sm uppercase tracking-[0.2em] mb-8"><?php echo e(__('landing.footer.product.title')); ?></h4>
                <ul class="space-y-4">
                    <?php $__currentLoopData = ['features', 'pricing', 'integrations', 'updates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="#" class="text-slate-400 hover:text-[#22c55e] font-bold text-sm transition-colors"><?php echo e(__("landing.footer.product.$link")); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-black text-sm uppercase tracking-[0.2em] mb-8"><?php echo e(__('landing.footer.resources.title')); ?></h4>
                <ul class="space-y-4">
                    <?php $__currentLoopData = ['help', 'docs', 'blog', 'api']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="#" class="text-slate-400 hover:text-[#22c55e] font-bold text-sm transition-colors"><?php echo e(__("landing.footer.resources.$link")); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-black text-sm uppercase tracking-[0.2em] mb-8"><?php echo e(__('landing.footer.legal.title')); ?></h4>
                <ul class="space-y-4">
                    <?php $__currentLoopData = ['privacy', 'terms', 'cookies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route($link)); ?>" class="text-slate-400 hover:text-[#22c55e] font-bold text-sm transition-colors"><?php echo e(__("landing.footer.legal.$link")); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>

        <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-slate-400 text-sm font-bold">
                <?php echo e(str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright'))); ?>

            </div>
            
            <div class="flex items-center gap-4 px-5 py-2.5 rounded-full bg-slate-50 border border-slate-100">
                <span class="w-2.5 h-2.5 rounded-full bg-[#22c55e] animate-pulse"></span>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">All Systems Operational</span>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/footer.blade.php ENDPATH**/ ?>