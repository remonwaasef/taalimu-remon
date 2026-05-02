

<?php $__env->startSection('title', __('Security: 2FA Verification')); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center align-items-center tfa-container">
    <div class="col-md-5">
        <div class="card shadow border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2"></i> <?php echo e(__('Two-Factor Authentication')); ?></h5>
            </div>
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 tfa-icon">
                        <i class="fas fa-lock fa-2x text-primary"></i>
                    </div>
                    <h6><?php echo e(__('Authentication Required')); ?></h6>
                    <p class="text-muted small"><?php echo e(__('Please enter the 6-digit code from your authenticator app to continue.')); ?></p>
                </div>

                <form action="<?php echo e(route('2fa.verify.post', ['tenant' => $tenant->domain])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <input type="text" 
                               name="one_time_password" 
                               id="one_time_password" 
                               class="form-control form-control-lg text-center font-monospace <?php $__errorArgs = ['one_time_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="000 000" 
                               required 
                               maxlength="6"
                               autofocus>
                        <?php $__errorArgs = ['one_time_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            <?php echo e(__('Verify & Continue')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <form action="<?php echo e(route('center.logout', ['tenant' => $tenant->domain])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-link text-secondary text-decoration-none">
                    <i class="fas fa-sign-out-alt me-1"></i> <?php echo e(__('Logout')); ?>

                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\2fa\verify.blade.php ENDPATH**/ ?>