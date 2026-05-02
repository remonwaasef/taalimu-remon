

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <h4 class="fw-bold mb-1"><i class="bi bi-person-check me-2"></i><?php echo e(__('center::messages.blade_0125')); ?></h4>
                    <p class="mb-0"><?php echo e($schedule->course->title); ?></p>
                </div>
                <div class="card-body p-5">
                    <?php if(isset($message)): ?>
                        <div class="alert alert-warning text-center">
                            <?php echo e($message); ?>

                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center mb-4">
                            <i class="bi bi-info-circle me-1"></i><?php echo e(__('center::messages.blade_0126')); ?></div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger text-center">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="mb-0"><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('center.attendance.loginAndMark', $schedule)); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="qr_url" value="<?php echo e($qrUrl ?? request()->fullUrl()); ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo e(__('center::messages.blade_0127')); ?></label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label"><?php echo e(__('center::messages.blade_0128')); ?></label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-2"></i><?php echo e(__('center::messages.blade_0129')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\attendance\scan-login.blade.php ENDPATH**/ ?>