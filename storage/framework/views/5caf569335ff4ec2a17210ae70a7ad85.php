

<?php $__env->startSection('content'); ?>
<div class="container text-center mt-5">
    <div class="card shadow-lg mx-auto border-success" style="max-width: 400px;">
        <div class="card-body py-5">
            <div class="display-1 text-success mb-3">✅</div>
            <h2 class="text-success">Attendance Recorded!</h2>
            <p class="lead"><?php echo e($message); ?></p>
            <a href="<?php echo e(route('center.dashboard', ['tenant' => request()->route('tenant')])); ?>" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\attendance\success.blade.php ENDPATH**/ ?>