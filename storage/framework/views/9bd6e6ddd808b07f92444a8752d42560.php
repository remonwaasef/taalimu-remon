

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-background">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-card border border-border rounded-2xl shadow-xl p-8">
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
                    <label for="email" class="block text-sm font-bold text-muted-foreground mb-2"><?php echo e(__('auth.login.email')); ?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-muted-foreground"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email')); ?>" 
                               class="block w-full ps-10 py-3 bg-muted/30 border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary transition-colors <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="name@example.com"
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
                               class="block w-full ps-10 py-3 bg-muted/30 border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary transition-colors <?php $__errorArgs = ['password'];
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

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <?php echo e(__('auth.login.login_button')); ?> 
                    <i class="fas fa-arrow-left ms-2 rtl:rotate-180 transform transition-transform"></i>
                </button>
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