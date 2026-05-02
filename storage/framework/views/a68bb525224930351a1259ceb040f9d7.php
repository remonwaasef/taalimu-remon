

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="mb-6">
            <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php echo e(__('Payment Cancelled')); ?></h2>
        <p class="text-gray-600 mb-8"><?php echo e(__('Your payment process was cancelled. No charges were made.')); ?></p>
        <a href="<?php echo e(route('register')); ?>" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
            <?php echo e(__('Try Again')); ?>

        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('landing.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\payment-cancel.blade.php ENDPATH**/ ?>