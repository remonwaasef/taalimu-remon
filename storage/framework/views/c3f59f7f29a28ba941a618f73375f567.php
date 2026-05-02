

<?php $__env->startSection('title', 'Enable Two-Factor Authentication'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Enable Two-Factor Authentication</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Setup 2FA</h6>
                </div>
                <div class="card-body">
                    <p>Scan this QR code with the Google Authenticator app on your phone:</p>
                    
                    <div class="text-center mb-4">
                        <?php echo $qrCodeSvg; ?>

                    </div>

                    <div class="alert alert-info">
                        <strong>Secret Key:</strong> <code><?php echo e($secret); ?></code>
                        <br><small>Save this key in a safe place. You can use it to recover your account if you lose access to your phone.</small>
                    </div>

                    <form action="<?php echo e(route('2fa.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Enter the 6-digit code from your app:</label>
                            <input type="text" name="one_time_password" class="form-control" maxlength="6" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Enable 2FA</button>
                        <a href="<?php echo e(route('center.dashboard')); ?>" class="btn btn-secondary btn-block">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views\auth\2fa\enable.blade.php ENDPATH**/ ?>