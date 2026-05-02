

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php echo e(__('Payment Successful!')); ?></h2>
        <p class="text-gray-600 mb-8"><?php echo e(__('Your subscription is now active. You can now access your dashboard.')); ?></p>
        
        <?php if(session('tenant_domain')): ?>
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                 <p class="text-sm text-blue-700 mb-2"><?php echo e(__('Your Dashboard URL:')); ?></p>
                 <a href="<?php echo e(request()->isSecure() ? 'https://' : 'http://'); ?><?php echo e(session('tenant_domain')); ?>.<?php echo e(config('app.tenant_domain', 'localhost')); ?>" class="text-lg font-bold text-primary-600 hover:underline">
                     <?php echo e(session('tenant_domain')); ?>.<?php echo e(config('app.tenant_domain', 'localhost')); ?>

                 </a>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('login.portal')); ?>" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
            <?php echo e(__('Go to Login')); ?>

        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('landing.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\payment-success.blade.php ENDPATH**/ ?>