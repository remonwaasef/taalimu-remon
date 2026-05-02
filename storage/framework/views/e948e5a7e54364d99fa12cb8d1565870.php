

<?php $__env->startSection('title', __('center::sales.overdue_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="fas fa-wallet fa-lg"></i>
        </div>
        <div>
            <h2 class="fw-bold text-dark mb-0"><?php echo e(__('center::sales.overdue_list')); ?></h2>
            <p class="text-muted small mb-0"><?php echo e(__('center::sales.overdue_subtitle')); ?></p>
        </div>
    </div>
    <div class="badge bg-danger rounded-pill px-3 py-2">
        <?php echo e(__('center::sales.total_students')); ?> <?php echo e($students->count()); ?>

    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4"><?php echo e(__('center::sales.student')); ?></th>
                        <th class="border-0"><?php echo e(__('center::sales.phone_number')); ?></th>
                        <th class="border-0"><?php echo e(__('center::sales.total_debt')); ?></th>
                        <th class="border-0 text-center"><?php echo e(__('center::sales.action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        <?php echo e(substr($student->name, 0, 1)); ?>

                                    </div>
                                    <span class="fw-bold text-dark"><?php echo e($student->name); ?></span>
                                </div>
                            </td>
                            <td class="text-muted"><?php echo e($student->phone); ?></td>
                            <td>
                                <span class="fw-bold text-danger"><?php echo e(number_format($student->total_debt, 2)); ?> <?php echo e(get_currency_symbol()); ?></span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?php echo e(route('center.sales.account', ['tenant' => $tenant->domain, 'student_id' => $student->id])); ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-user-invoice me-1"></i> <?php echo e(__('center::sales.collect')); ?>

                                    </a>
                                    <a href="<?php echo e(route('center.students.show', ['tenant' => $tenant->domain, 'student' => $student->id])); ?>" class="btn btn-outline-light text-dark btn-sm rounded-pill px-3 border">
                                        <i class="fas fa-user me-1"></i> <?php echo e(__('center::sales.profile')); ?>

                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5 class="fw-bold"><?php echo e(__('center::sales.no_overdue')); ?></h5>
                                    <p class="mb-0"><?php echo e(__('center::sales.no_overdue_subtitle')); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // If we want to auto-select the student in the account page, we can pass the ID
    // The account page already has logic for loading a student via query param if we add it
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\sales\overdue.blade.php ENDPATH**/ ?>