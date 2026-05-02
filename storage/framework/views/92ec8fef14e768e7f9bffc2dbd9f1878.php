

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-50/50 flex justify-center items-center p-4 lg:p-8 mesh-gradient-soft noise-overlay">
    <div class="w-full max-w-md bg-white rounded-[32px] shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100 p-8 text-center">
        <div class="mb-6 flex justify-center">
            <div class="w-16 h-16 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-slate-900 mb-2 font-arabic">
            <?php echo e(__('Please Verify Your Email')); ?>

        </h2>

        <p class="text-slate-500 mb-6 font-arabic text-sm leading-relaxed">
            <?php echo e(__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')); ?>

        </p>

        <?php if(session('message') == 'Verification link sent!'): ?>
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium font-arabic">
                <?php echo e(__('A new verification link has been sent to the email address you provided during registration.')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('verification.send')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full py-3 px-4 bg-brand-secondary hover:opacity-90 text-white font-bold rounded-xl shadow-lg shadow-brand-secondary/20 transition-all font-arabic">
                <?php echo e(__('Resend Verification Email')); ?>

            </button>
        </form>

        <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-sm text-slate-400 hover:text-slate-600 font-medium font-arabic">
                <?php echo e(__('Log Out')); ?>

            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>