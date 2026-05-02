<?php $__env->startSection('title', __('instructor::reports.payment_reports')); ?>
<?php $__env->startSection('page-title', __('instructor::reports.payment_reports')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::reports.payment_reports_subtitle')); ?>

<?php $__env->startSection('content'); ?>


<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-center" style="width: 60px;">
                    <i class="fas fa-university text-primary fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase"><?php echo e(__('instructor::reports.total_due_label')); ?></small>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo e(number_format($totalDue)); ?> <small class="text-muted fs-6"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-4">
                <div class="bg-success bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-check-circle text-success fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase"><?php echo e(__('instructor::reports.total_collected')); ?></small>
                    <h4 class="fw-bold mb-0 text-success"><?php echo e(number_format($totalPaid)); ?> <small class="fs-6"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-4">
                <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                    <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase"><?php echo e(__('instructor::reports.total_remaining')); ?></small>
                    <h4 class="fw-bold mb-0 text-danger"><?php echo e(number_format($totalBalance)); ?> <small class="fs-6"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></small></h4>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0 text-dark fw-bold" style="width: 50px;">#</th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.student')); ?></th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.enrolled_courses')); ?></th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.total_due_label')); ?></th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.total_collected')); ?></th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.remaining')); ?></th>
                        <th class="px-4 border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.status')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-3 text-muted fw-bold"><?php echo e($index + 1); ?></td>
                            <td>
                                <div class="fw-bold text-dark fs-6"><?php echo e($student->name); ?></div>
                                <div class="text-muted small"><i class="fas fa-phone-alt me-1 small"></i> <?php echo e($student->phone); ?></div>
                            </td>
                            <td>
                                <?php $__empty_2 = true; $__currentLoopData = $student->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2 mb-1 border shadow-none">
                                        <i class="fas fa-book-open me-1 small"></i> <?php echo e($enrollment->course->title ?? __('instructor::reports.untitled_course')); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-muted small italic"><?php echo e(__('instructor::reports.no_courses')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold"><?php echo e(number_format($student->total_due)); ?> <?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></span>
                            </td>
                            <td>
                                <span class="fw-bold text-success"><?php echo e(number_format($student->total_paid)); ?> <?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></span>
                            </td>
                            <td>
                                <?php if($student->balance > 0): ?>
                                    <span class="fw-bold text-danger"><?php echo e(number_format($student->balance)); ?> <?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></span>
                                <?php else: ?>
                                    <span class="fw-bold text-success">0</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4">
                                <?php if($student->financial_status === 'paid'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('instructor::reports.paid')); ?></span>
                                <?php elseif($student->financial_status === 'partial'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3"><?php echo e(__('instructor::reports.partial')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3"><?php echo e(__('instructor::reports.unpaid')); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-receipt fs-1 text-muted opacity-25"></i></div>
                                <h6 class="text-muted"><?php echo e(__('instructor::reports.no_students')); ?></h6>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($students->count() > 0): ?>
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="3" class="px-4 py-3 fw-bold"><?php echo e(__('instructor::reports.total')); ?></td>
                        <td class="fw-bold"><?php echo e(number_format($totalDue)); ?> <?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></td>
                        <td class="fw-bold text-success"><?php echo e(number_format($totalPaid)); ?> <?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></td>
                        <td class="fw-bold text-danger"><?php echo e(number_format($totalBalance)); ?> <?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\reports\payments.blade.php ENDPATH**/ ?>