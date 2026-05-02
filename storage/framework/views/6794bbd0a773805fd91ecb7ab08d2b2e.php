

<?php $__env->startSection('page-title', __('center::expenses.title')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.expenses.create')); ?>" class="btn btn-glass shadow-sm">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('center::expenses.new_expense')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('center.expenses.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold"><?php echo e(__('center::expenses.category')); ?></label>
                <select name="category" class="form-select rounded-pill">
                    <option value=""><?php echo e(__('center::expenses.all_categories')); ?></option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold"><?php echo e(__('center::expenses.start_date')); ?></label>
                <input type="date" name="start_date" class="form-control rounded-pill" value="<?php echo e(request('start_date')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold"><?php echo e(__('center::expenses.end_date')); ?></label>
                <input type="date" name="end_date" class="form-control rounded-pill" value="<?php echo e(request('end_date')); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-light rounded-pill px-4 border w-100">
                    <i class="fas fa-filter me-2"></i> <?php echo e(__('center::expenses.filter')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4 py-3"><?php echo e(__('center::expenses.category')); ?></th>
                        <th class="border-0"><?php echo e(__('center::expenses.amount')); ?></th>
                        <th class="border-0"><?php echo e(__('center::expenses.date')); ?></th>
                        <th class="border-0"><?php echo e(__('center::expenses.payment_method')); ?></th>
                        <th class="border-0"><?php echo e(__('center::expenses.created_by')); ?></th>
                        <th class="border-0 rounded-end px-4"><?php echo e(__('center::expenses.action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover-bg">
                        <td class="ps-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-bold">
                                <?php echo e($expense->category); ?>

                            </span>
                        </td>
                        <td class="fw-bold text-danger">-<?php echo e(format_price($expense->amount)); ?></td>
                        <td class="text-muted small"><?php echo e($expense->date->format('Y-m-d')); ?></td>
                        <td>
                            <span class="small text-dark fw-medium">
                                <i class="fas <?php echo e($expense->payment_method == 'cash' ? 'fa-money-bill-wave text-success' : 'fa-credit-card text-info'); ?> me-1"></i>
                                <?php echo e(__('center::expenses.' . $expense->payment_method)); ?>

                            </span>
                        </td>
                        <td>
                            <div class="small"><?php echo e($expense->creator?->name ?? 'System'); ?></div>
                        </td>
                        <td class="px-4">
                            <div class="btn-group">
                                <a href="<?php echo e(route('center.expenses.edit', $expense->id)); ?>" class="btn btn-sm btn-light border-0 rounded-pill me-1">
                                    <i class="fas fa-edit text-muted"></i>
                                </a>
                                <?php if($expense->attachment): ?>
                                <a href="<?php echo e(asset('storage/' . $expense->attachment)); ?>" target="_blank" class="btn btn-sm btn-light border-0 rounded-pill me-1">
                                    <i class="fas fa-paperclip text-muted"></i>
                                </a>
                                <?php endif; ?>
                                <form action="<?php echo e(route('center.expenses.destroy', $expense->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-light border-0 rounded-pill" onclick="return confirm('<?php echo e(__('center::expenses.delete_confirm')); ?>')">
                                        <i class="fas fa-trash text-danger opacity-75"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                            <p><?php echo e(__('center::expenses.no_expenses')); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($expenses->hasPages()): ?>
    <div class="card-footer bg-white border-0 py-3">
        <?php echo e($expenses->links()); ?>

    </div>
    <?php endif; ?>
</div>

<style>
    .hover-bg:hover {
        background-color: rgba(67, 97, 238, 0.02);
    }
    .form-select, .form-control {
        border-color: #eee;
    }
    .form-select:focus, .form-control:focus {
        border-color: #4361ee;
        box-shadow: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\expenses\index.blade.php ENDPATH**/ ?>