
<?php $__env->startSection('page-title', __('center::analytics.teacher_commissions')); ?>
<?php $__env->startSection('page-subtitle', __('center::analytics.commissions_detail')); ?>

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
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3"><?php echo e(__('center::analytics.teacher_commissions')); ?></span>
                    </div>
                    <p class="text-muted fw-bold text-uppercase small mb-1"><?php echo e(__('center::analytics.total_commissions')); ?></p>
                    <h3 class="fw-bold text-dark mb-0"><?php echo e(format_price($totalCommissions)); ?></h3>
                </div>
                <div class="bg-info" style="height: 4px; width: 100%; opacity: 0.5;"></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4 px-4">
            <h5 class="fw-bold mb-0 text-dark"><?php echo e(__('center::analytics.commission_log')); ?></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3"><?php echo e(__('center::analytics.transaction_number')); ?></th>
                            <th><?php echo e(__('center::analytics.instructor')); ?></th>
                            <th><?php echo e(__('center::analytics.invoice_student')); ?></th>
                            <th><?php echo e(__('center::analytics.commission_value')); ?></th>
                            <th><?php echo e(__('center::analytics.due_date')); ?></th>
                            <th class="px-4"><?php echo e(__('center::analytics.status')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4">#<?php echo e($commission->id); ?></td>
                                <td>
                                    <?php if($commission->instructor): ?>
                                        <a href="<?php echo e(route('center.instructors.show', $commission->instructor_id)); ?>" class="fw-bold text-primary text-decoration-none">
                                            <?php echo e($commission->instructor->name); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small"><?php echo e(__('center::analytics.undefined')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($commission->sale): ?>
                                        <a href="<?php echo e(route('center.sales.show', $commission->sale_id)); ?>" class="text-decoration-none text-dark fw-medium">#<?php echo e($commission->sale_id); ?></a>
                                        <small class="d-block text-muted"><?php echo e($commission->sale->student->name ?? ''); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-info"><?php echo e(format_price($commission->amount)); ?></td>
                                <td class="small text-muted"><?php echo e($commission->created_at->format('Y-m-d H:i')); ?></td>
                                <td class="px-4">
                                    <?php if($commission->status == 'paid'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i><?php echo e(__('center::analytics.paid_f')); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i><?php echo e(__('center::analytics.due_f')); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-info-circle fa-3x text-light"></i>
                                    </div>
                                    <p class="mb-0"><?php echo e(__('center::analytics.no_commissions_recorded')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($commissions->hasPages()): ?>
            <div class="p-4 border-top">
                <?php echo e($commissions->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\analytics\finance\commissions.blade.php ENDPATH**/ ?>