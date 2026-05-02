<?php
    $isEnded = now()->isAfter(\Carbon\Carbon::parse($schedule->end_time));
    $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
?>

<?php $__env->startSection('page-title', __('instructor::attendance.review_attendance')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::attendance.mark_attendance') . ': ' . $schedule->course->title); ?>

<?php $__env->startSection('page-actions'); ?>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo e(route('instructor.scanner', $schedule->course)); ?>" class="btn btn-glass">
            <i class="fas fa-qrcode me-2"></i> <?php echo e(__('instructor::attendance.scan_student_card')); ?>

        </a>

        <?php if($isEnded && $hasUnrecorded): ?>
            <form action="<?php echo e(route('instructor.attendance.bulkAbsent', $schedule)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-glass" style="background: rgba(220, 53, 69, 0.2) !important;">
                    <i class="fas fa-user-xmark me-2"></i> <?php echo e(__('instructor::attendance.mark_remaining_absent')); ?>

                </button>
            </form>
        <?php endif; ?>
        
        <a href="<?php echo e(route('instructor.attendance.index')); ?>" class="btn btn-glass">
            <i class="fas fa-arrow-left me-1"></i> <?php echo e(__('instructor::sidebar.back') ?? __('instructor::groups.back')); ?>

        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1" style="color: var(--primary-color);">
                                <i class="fas fa-users-viewfinder me-2"></i><?php echo e(__('instructor::attendance.enrolled_students')); ?>

                            </h5>
                            <p class="text-secondary mb-0 fw-bold"><?php echo e(__('instructor::attendance.session_details', [
                                'time' => \Carbon\Carbon::parse($schedule->start_time)->format('h:i A'),
                                'hall' => $schedule->classroom->name ?? ($schedule->location ?: __('instructor::attendance.classroom_not_specified'))
                            ])); ?></p>
                        </div>
                        <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                            <i class="fas fa-calendar-alt me-2"></i> <?php echo e(today()->format('Y-m-d')); ?>

                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-dark fw-bold"><?php echo e(__('instructor::attendance.student')); ?></th>
                                    <th class="border-0 text-dark fw-bold"><?php echo e(__('instructor::attendance.student_code')); ?></th>
                                    <th class="border-0 text-dark fw-bold text-center"><?php echo e(__('instructor::reports.status') ?? __('instructor::attendance.today_status')); ?></th>
                                    <th class="border-0 text-dark fw-bold text-center"><?php echo e(__('instructor::attendance.mark_attendance')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $schedule->course->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $student = $enrollment->user->student ?? null;
                                        $attendance = $student ? $attendances->get($student->id) : null;
                                    ?>
                                    <?php if($student): ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                                    <?php echo e(mb_substr($student->name, 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo e($student->name); ?></div>
                                                    <div class="text-muted small"><?php echo e($enrollment->user->email); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="rounded-pill px-3 py-1 fw-bold" style="color: var(--primary-color); background-color: rgba(58, 12, 163, 0.08);">#<?php echo e($student->id); ?></code>
                                        </td>
                                        <td class="text-center">
                                            <?php if($attendance): ?>
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-<?php echo e($attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger')); ?> bg-opacity-10 text-<?php echo e($attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger')); ?> rounded-pill px-3 py-2">
                                                        <?php if($attendance->status == 'late'): ?>
                                                            <?php echo e(__('instructor::attendance.late_minutes', ['minutes' => $attendance->late_minutes])); ?>

                                                        <?php elseif($attendance->status == 'present'): ?>
                                                            <?php echo e(__('instructor::attendance.present')); ?>

                                                        <?php else: ?>
                                                            <?php echo e(__('instructor::attendance.absent')); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                    <small class="text-muted mt-1" style="font-size: 0.7rem;"><?php echo e($attendance->check_in_time->format('h:i A')); ?></small>
                                                </div>
                                            <?php elseif($isEnded): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2"><?php echo e(__('instructor::attendance.absent')); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small"><i class="far fa-clock me-1"></i> <?php echo e(__('instructor::attendance.not_recorded')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="<?php echo e(route('instructor.attendance.store')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo e($schedule->course_id); ?>">
                                                    <input type="hidden" name="schedule_id" value="<?php echo e($schedule->id); ?>">
                                                    <input type="hidden" name="session_date" value="<?php echo e(today()->format('Y-m-d')); ?>">
                                                    <input type="hidden" name="status" value="present">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo e($attendance && $attendance->status == 'present' ? 'success' : 'outline-success'); ?> rounded-pill px-4" <?php echo e($isEnded && (!$attendance || $attendance->status !== 'present') ? 'disabled' : ''); ?>>
                                                        <i class="fas fa-check me-1"></i> <?php echo e(__('instructor::attendance.present')); ?>

                                                    </button>
                                                </form>
                                                
                                                <form action="<?php echo e(route('instructor.attendance.store')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo e($schedule->course_id); ?>">
                                                    <input type="hidden" name="schedule_id" value="<?php echo e($schedule->id); ?>">
                                                    <input type="hidden" name="session_date" value="<?php echo e(today()->format('Y-m-d')); ?>">
                                                    <input type="hidden" name="status" value="late">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo e($attendance && $attendance->status == 'late' ? 'warning' : 'outline-warning'); ?> rounded-pill px-4" <?php echo e($isEnded && (!$attendance || $attendance->status !== 'late') ? 'disabled' : ''); ?>>
                                                        <i class="fas fa-clock me-1"></i> <?php echo e(__('instructor::attendance.late')); ?>

                                                    </button>
                                                </form>

                                                <form action="<?php echo e(route('instructor.attendance.store')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
                                                    <input type="hidden" name="course_id" value="<?php echo e($schedule->course_id); ?>">
                                                    <input type="hidden" name="schedule_id" value="<?php echo e($schedule->id); ?>">
                                                    <input type="hidden" name="session_date" value="<?php echo e(today()->format('Y-m-d')); ?>">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo e($attendance && $attendance->status == 'absent' ? 'danger' : 'outline-danger'); ?> rounded-pill px-4">
                                                        <i class="fas fa-times me-1"></i> <?php echo e(__('instructor::attendance.absent')); ?>

                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="fas fa-user-slash fa-3x text-light"></i>
                                            </div>
                                            <h6 class="text-muted fw-bold"><?php echo e(__('instructor::attendance.no_students_enrolled')); ?></h6>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\attendance\show.blade.php ENDPATH**/ ?>