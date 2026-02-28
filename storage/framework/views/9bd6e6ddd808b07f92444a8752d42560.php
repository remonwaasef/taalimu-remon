

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-slate-50/50 mesh-gradient-soft noise-overlay">
    <div class="max-w-md w-full space-y-8 animate-fade-in-up">
        <div class="bg-white border border-slate-100/50 rounded-[2.5rem] shadow-2xl shadow-blue-900/5 p-8 lg:p-10 backdrop-blur-xl relative overflow-hidden">
            <!-- Decorative glow -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-violet-500/10 blur-[80px] rounded-full pointer-events-none"></div>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold gradient-text mb-2"><?php echo e(__('auth.login.title')); ?></h2>
                <p class="text-muted-foreground"><?php echo e(__('auth.login.subtitle')); ?></p>
            </div>

            <?php if($errors->any()): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-600 rounded-lg p-4 mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle me-3"></i>
                    <span class="text-sm font-medium"><?php echo e($errors->first()); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="bg-blue-500/10 border border-blue-500/20 text-blue-600 rounded-lg p-4 mb-6 flex items-center">
                    <i class="fas fa-info-circle me-3"></i>
                    <span class="text-sm font-medium"><?php echo e(session('info')); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('unified.login.submit')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label for="email" class="block text-sm font-bold text-muted-foreground mb-2"><?php echo e(__('auth.login.email_or_phone')); ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-muted-foreground"></i>
                        </div>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email')); ?>" 
                               class="block w-full ps-10 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="<?php echo e(__('auth.login.email_or_phone')); ?>"
                               required 
                               autofocus>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-muted-foreground mb-2"><?php echo e(__('auth.login.password')); ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-muted-foreground"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="block w-full ps-10 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="••••••••"
                               required>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-hero-cta w-full flex justify-center py-4 px-4 rounded-2xl shadow-lg text-base font-bold transition-all">
                    <?php echo e(__('auth.login.login_button')); ?> 
                    <i class="fas fa-arrow-left ms-2 rtl:rotate-180 transform transition-transform group-hover:-translate-x-1"></i>
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                <a href="<?php echo e(route('auth.google')); ?>" class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Google
                </a>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-muted-foreground mb-3"><?php echo e(__('auth.login.no_account')); ?></p>
                <a href="<?php echo e(route('register')); ?>" class="inline-block px-6 py-2 border-2 border-primary text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors text-sm">
                    <?php echo e(__('auth.login.register_now')); ?>

                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-border text-center">
                <a href="<?php echo e(route('admin.login')); ?>" class="inline-flex items-center text-sm text-muted-foreground hover:text-primary transition-colors">
                    <i class="fas fa-user-shield me-2"></i> <?php echo e(__('auth.login.admin_login')); ?>

                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/auth/unified-login.blade.php ENDPATH**/ ?>