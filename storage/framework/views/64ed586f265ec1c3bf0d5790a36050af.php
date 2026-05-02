
<?php $__env->startSection('page-title', __('center::analytics.total_taxes')); ?>
<?php $__env->startSection('page-subtitle', __('center::analytics.tax_invoices_log')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.analytics.finance')); ?>" class="btn btn-glass shadow-sm">
        <i class="fas fa-arrow-right me-2"></i><?php echo e(__('center::analytics.back_to_finance')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-coins fa-lg"></i>
                        </div>
                        <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3"><?php echo e(__('center::analytics.total_taxes')); ?></span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1"><?php echo e(__('center::analytics.total_taxes')); ?></p>
                    <h3 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalTaxes)); ?></h3>
                </div>
                <div class="bg-dark" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4 px-4">
            <h5 class="fw-bold mb-0 text-dark"><?php echo e(__('center::analytics.tax_invoices_log')); ?></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3"><?php echo e(__('center::sales.invoice_number')); ?></th>
                            <th><?php echo e(__('center::sales.student')); ?></th>
                            <th><?php echo e(__('center::analytics.tax_value')); ?></th>
                            <th><?php echo e(__('center::analytics.subtotal')); ?></th>
                            <th><?php echo e(__('center::analytics.total_with_tax')); ?></th>
                            <th class="px-4"><?php echo e(__('center::sales.date')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4">
                                    <a href="<?php echo e(route('center.sales.show', $sale->id)); ?>" class="fw-bold text-primary text-decoration-none">#<?php echo e($sale->id); ?></a>
                                </td>
                                <td><?php echo e($sale->student->name ?? __('center::analytics.undefined')); ?></td>
                                <td class="fw-bold text-dark"><?php echo e(format_price($sale->tax_amount)); ?></td>
                                <td class="text-muted small"><?php echo e(format_price($sale->subtotal_amount > 0 ? $sale->subtotal_amount : $sale->total_amount - $sale->tax_amount)); ?></td>
                                <td class="fw-bold text-success"><?php echo e(format_price($sale->total_amount)); ?></td>
                                <td class="px-4 small text-muted"><?php echo e($sale->created_at->format('Y-m-d H:i')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-receipt fa-3x text-light"></i>
                                    </div>
                                    <p class="mb-0"><?php echo e(__('center::analytics.no_taxes_applied')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($taxes->hasPages()): ?>
            <div class="p-4 border-top">
                <?php echo e($taxes->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\analytics\finance\taxes.blade.php ENDPATH**/ ?>