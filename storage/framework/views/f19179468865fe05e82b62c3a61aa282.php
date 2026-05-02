

<?php $__env->startSection('title', 'Subscription Successful'); ?>
<?php $__env->startSection('page-title', 'Subscription Successful'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    <h2 class="h4 text-gray-900 mb-4">Subscription Successful!</h2>
                    <p class="mb-4">Thank you for subscribing. Your plan is now active.</p>
                    <?php
                        $user = auth()->user();
                        $dashboardRoute = ($user && $user->role === 'instructor') ? route('instructor.dashboard', ['tenant' => app('tenant')->domain]) : route('center.dashboard');
                    ?>
                    <a href="<?php echo e($dashboardRoute); ?>" class="btn btn-primary">
                        الذهاب للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->check() && auth()->user()->role === 'instructor' ? 'instructor::components.layouts.master' : 'center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\subscription\success.blade.php ENDPATH**/ ?>