

<?php $__env->startSection('title', __('center::sales.checkout_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5 pt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4 border-0 text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-credit-card fa-4x text-primary p-4 bg-light rounded-circle"></i>
                    </div>
                    <h3 class="fw-bold mb-3"><?php echo e(__('center::sales.checkout_title')); ?></h3>
                    <p class="text-muted mb-4">
                        <?php echo e(__('center::sales.checkout_review')); ?>

                    </p>

                     <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><?php echo e(__('center::sales.invoice_number')); ?>:</span>
                            <span class="fw-bold text-dark">#<?php echo e($sale->id); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><?php echo e(__('center::sales.student')); ?>:</span>
                            <span class="fw-bold text-dark"><?php echo e($sale->student->name); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><?php echo e(__('center::sales.total_invoice')); ?>:</span>
                            <span class="fw-bold text-dark"><?php echo e(number_format($sale->total_amount, 2)); ?> <?php echo e(get_currency_symbol()); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted"><?php echo e(__('center::sales.paid_amount_label')); ?>:</span>
                            <span class="fw-bold text-success"><?php echo e(number_format($sale->paid_amount, 2)); ?> <?php echo e(get_currency_symbol()); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-danger"><?php echo e(__('center::sales.required_now')); ?>:</span>
                            <h4 class="fw-bold text-danger mb-0"><?php echo e(number_format($sale->total_amount - $sale->paid_amount, 2)); ?> <?php echo e(get_currency_symbol()); ?></h4>
                        </div>
                    </div>

                     <a href="<?php echo e(route('center.sales.checkout.success', $sale->id)); ?>" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow-sm d-flex justify-content-center align-items-center py-3">
                        <i class="fas fa-lock me-2"></i> <?php echo e(__('center::sales.confirm_pay_now')); ?>

                    </a>
                    
                     <div class="mt-4">
                        <a href="<?php echo e(route('center.sales.show', $sale->id)); ?>" class="text-muted text-decoration-none hover-primary">
                            <i class="fas fa-arrow-right me-1"></i> <?php echo e(__('center::sales.return_to_invoice')); ?>

                        </a>
                    </div>
                </div>
            </div>
            
             <div class="text-center mt-4 text-muted small">
                <i class="fas fa-shield-alt text-success me-1"></i> <?php echo e(__('center::sales.checkout_secure_notice')); ?>

            </div>
        </div>
    </div>
</div>

<style>
    .hover-primary:hover {
        color: var(--bs-primary) !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\sales\checkout.blade.php ENDPATH**/ ?>