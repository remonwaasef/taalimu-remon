<header 
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    x-data="{ 
        scrolled: false,
        isMenuOpen: false
    }"
    @scroll.window="scrolled = window.pageYOffset > 20"
    :class="scrolled ? 'bg-white/80 backdrop-blur-xl border-b border-slate-200/50 py-3' : 'bg-transparent py-5'"
>
    <div class="container mx-auto px-4 lg:px-12">
        <div class="flex items-center justify-between">
            <!-- Logo area -->
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 group">
                <div class="relative">
                    <img src="<?php echo e(asset('images/brand/logo-full.png?v=3')); ?>" alt="Logo" class="h-9 lg:h-11 w-auto mix-blend-multiply group-hover:scale-105 transition-transform">
                </div>
                <div class="hidden sm:flex flex-col border-s border-slate-200 ps-3">
                    <span class="font-black text-lg text-[#0f172a] leading-tight tracking-tighter uppercase">
                        <?php echo e(\App\Models\SiteSetting::get('site_name', 'Taalimu')); ?>

                    </span>
                    <span class="text-[10px] font-bold text-[#22c55e] uppercase tracking-[0.2em] opacity-80">
                        <?php echo e(__('landing.navbar.badge_short') ?? 'Smart Ed'); ?>

                    </span>
                </div>
            </a>

            <!-- Navigation: Airy & Clean -->
            <nav class="hidden lg:flex items-center gap-10">
                <?php $__currentLoopData = ['features', 'pricing', 'testimonials', 'faq']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nav): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="#<?php echo e($nav); ?>" class="text-[15px] font-bold text-[#1e293b] hover:text-[#22c55e] transition-all relative group">
                    <?php echo e(__("landing.nav.$nav")); ?>

                    <span class="absolute -bottom-1 start-0 w-0 h-0.5 bg-[#22c55e] transition-all group-hover:w-full"></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <!-- Secondary Actions -->
            <div class="hidden lg:flex items-center gap-6">
                <!-- Lang Switcher (Minimal) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas fa-globe text-xs"></i>
                        <?php echo e(strtoupper(app()->getLocale())); ?>

                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-3 w-40 glass-premium rounded-xl shadow-xl py-2 overflow-hidden z-50 animate-fade-in">
                        <?php $__currentLoopData = ['en' => 'English', 'ar' => 'العربية', 'fr' => 'Français']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('lang.switch', ['locale' => $code])); ?>" class="block px-4 py-2 text-sm <?php if(app()->isLocale($code)): ?> text-[#22c55e] font-black <?php else: ?> text-slate-600 font-bold hover:bg-slate-50 <?php endif; ?>">
                            <?php echo e($label); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <a href="<?php echo e(route('login.portal')); ?>" class="text-sm font-bold text-[#0f172a] hover:text-[#22c55e] transition-colors">
                    <?php echo e(__('landing.nav.sign_in')); ?>

                </a>
                
                <a href="<?php echo e(route('register')); ?>" class="relative group">
                    <div class="absolute inset-0 bg-[#22c55e] rounded-full blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative bg-[#22c55e] text-white px-7 py-3 rounded-full font-black text-sm flex items-center gap-2 transition-transform active:scale-95 group-hover:scale-[1.02]">
                        <?php echo e(__('landing.nav.start_trial')); ?>

                        <i class="fas fa-rocket text-[10px] opacity-80"></i>
                    </div>
                </a>
            </div>

            <!-- Mobile Trigger -->
            <button @click="isMenuOpen = !isMenuOpen" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu: Clean Glass -->
    <div 
        x-show="isMenuOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-100 shadow-xl py-6 px-4 z-40"
    >
        <nav class="flex flex-col gap-5">
            <?php $__currentLoopData = ['features', 'pricing', 'testimonials', 'faq']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nav): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="#<?php echo e($nav); ?>" @click="isMenuOpen = false" class="text-lg font-bold text-slate-700 hover:text-[#22c55e]">
                <?php echo e(__("landing.nav.$nav")); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="h-px bg-slate-100 my-2"></div>
            <a href="<?php echo e(route('login.portal')); ?>" class="text-lg font-bold text-slate-700"><?php echo e(__('landing.nav.sign_in')); ?></a>
            <a href="<?php echo e(route('register')); ?>" class="bg-[#22c55e] text-white text-center py-4 rounded-xl font-black shadow-lg shadow-green-500/20">
                <?php echo e(__('landing.nav.start_trial')); ?>

            </a>
        </nav>
    </div>
</header>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/header.blade.php ENDPATH**/ ?>