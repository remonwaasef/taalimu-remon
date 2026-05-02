<?php $__env->startSection('title', __('instructor::reports.student_reports')); ?>
<?php $__env->startSection('page-title', __('instructor::reports.student_reports')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::reports.student_reports_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0 text-dark fw-bold" style="width: 50px;">#</th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.student_name')); ?></th>
                        <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::reports.enrolled_courses')); ?></th>
                        <th class="border-0 text-dark fw-bold text-center"><?php echo e(__('instructor::reports.attendance_stats')); ?></th>
                        <th class="border-0 text-dark fw-bold text-center" style="min-width: 200px;"><?php echo e(__('instructor::reports.progress')); ?></th>
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
                                    <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2 mb-1 shadow-none border">
                                        <i class="fas fa-book-open me-1 small"></i> <?php echo e($enrollment->course->title ?? __('instructor::reports.untitled_course')); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-muted small italic"><?php echo e(__('instructor::reports.no_courses')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-block bg-light rounded-3 px-3 py-2">
                                    <span class="fw-bold text-dark fs-5"><?php echo e($student->attended_count); ?></span>
                                    <span class="text-muted mx-1">/</span>
                                    <span class="text-muted small"><?php echo e($student->total_sessions); ?></span>
                                </div>
                                <div class="text-muted small mt-1"><?php echo e(__('instructor::reports.sessions')); ?></div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress w-100" style="height: 6px; border-radius: 10px;">
                                        <div class="progress-bar bg-<?php echo e($student->attendance_percentage > 75 ? 'success' : ($student->attendance_percentage > 40 ? 'warning' : 'danger')); ?>" 
                                             role="progressbar" style="width: <?php echo e($student->attendance_percentage); ?>%"></div>
                                    </div>
                                    <span class="fw-bold small"><?php echo e($student->attendance_percentage); ?>%</span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-user-graduate fs-1 text-muted opacity-25"></i></div>
                                <h6 class="text-muted"><?php echo e(__('instructor::reports.no_students')); ?></h6>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\reports\students.blade.php ENDPATH**/ ?>