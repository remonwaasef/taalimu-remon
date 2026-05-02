<footer class="bg-slate-50 pt-20 pb-10 overflow-hidden relative border-t border-slate-200/60 landing-footer">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-5 gap-12 mb-16">
            <!-- Brand -->
            <div class="lg:col-span-2 space-y-6">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
                    <img src="<?php echo e(asset('images/brand/logo-full.png?v=3')); ?>" alt="<?php echo e(config('app.name')); ?>" class="h-9 w-auto">
                    <span class="font-black text-xl text-slate-900 leading-tight tracking-tight">
                        <?php echo e(\App\Models\SiteSetting::get('site_name', 'Taalimu')); ?>

                    </span>
                </a>
                <p class="text-slate-600 font-medium leading-relaxed max-w-sm text-sm">
                    <?php echo e(\App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle'))); ?>

                </p>
                <!-- Social -->
                <div class="flex items-center gap-3">
                    <?php $__currentLoopData = ['facebook-f', 'linkedin-in', 'twitter', 'instagram']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="#" class="w-10 h-10 rounded-xl bg-slate-200/50 flex items-center justify-center text-slate-500 hover:bg-emerald-500 hover:text-white transition-all duration-300">
                        <i class="fab fa-<?php echo e($social); ?> text-sm"></i>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-slate-900 font-bold text-sm uppercase tracking-wider mb-6"><?php echo e(__('landing.footer.product.title')); ?></h4>
                <ul class="space-y-3">
                    <?php $__currentLoopData = ['features', 'pricing', 'integrations', 'updates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="#" class="text-slate-500 hover:text-emerald-400 font-medium text-sm transition-colors"><?php echo e(__("landing.footer.product.$link")); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 font-bold text-sm uppercase tracking-wider mb-6"><?php echo e(__('landing.footer.resources.title')); ?></h4>
                <ul class="space-y-3">
                    <?php $__currentLoopData = ['help', 'docs', 'blog', 'api']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="#" class="text-slate-500 hover:text-emerald-400 font-medium text-sm transition-colors"><?php echo e(__("landing.footer.resources.$link")); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 font-bold text-sm uppercase tracking-wider mb-6"><?php echo e(__('landing.footer.legal.title')); ?></h4>
                <ul class="space-y-3">
                    <?php $__currentLoopData = ['privacy', 'terms', 'cookies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route($link)); ?>" class="text-slate-500 hover:text-emerald-600 font-medium text-sm transition-colors"><?php echo e(__("landing.footer.legal.$link")); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-200/60 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-slate-600 text-sm font-medium">
                <?php echo e(str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright'))); ?>

            </div>
            
            <div class="flex items-center gap-3 px-4 py-2 rounded-full bg-slate-100 border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">All Systems Operational</span>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views\landing\partials\footer.blade.php ENDPATH**/ ?>