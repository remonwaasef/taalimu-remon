

<?php $__env->startSection('page-title', __('instructor::attendance.title')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::attendance.subtitle')); ?>

<?php $__env->startSection('content'); ?>

    <div class="row g-4">
        <!-- Today's Sessions -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color: var(--primary-color);"></i><?php echo e(__('instructor::attendance.today_sessions', ['date' => now()->format('Y-m-d')])); ?></h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start"><?php echo e(__('instructor::attendance.group')); ?></th>
                                    <th class="border-0"><?php echo e(__('instructor::attendance.details')); ?></th>
                                    <th class="border-0 text-center"><?php echo e(__('instructor::attendance.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $todaySessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo e($session->course->title); ?></div>
                                            <small class="badge rounded-pill" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                                <?php echo e(\Carbon\Carbon::parse($session->start_time)->format('h:i A')); ?> - 
                                                <?php echo e(\Carbon\Carbon::parse($session->end_time)->format('h:i A')); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($session->classroom->name ?? __('instructor::attendance.classroom_not_specified')); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $sessionEnd = \Carbon\Carbon::parse($session->end_time);
                                                $isEnded = now()->isAfter($sessionEnd);
                                            ?>
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if($isEnded): ?>
                                                    <a href="<?php echo e(route('instructor.attendance.show', $session)); ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                        <i class="bi bi-person-x me-1"></i><?php echo e(__('instructor::attendance.review_attendance')); ?></a>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('instructor.attendance.show', $session)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 border-0" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                                                        <i class="bi bi-card-checklist me-1"></i><?php echo e(__('instructor::attendance.manual_attendance')); ?></a>
                                                    <a href="<?php echo e(route('instructor.scanner', $session->course)); ?>" class="btn btn-primary btn-sm rounded-pill px-3 border-0 shadow-sm" style="background: var(--primary-color);">
                                                        <i class="bi bi-qr-code me-1"></i> <?php echo e(__('instructor::attendance.qr_scan')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted"><?php echo e(__('instructor::attendance.no_sessions_today')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <?php echo e($todaySessions->links()); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-ui-checks me-2 text-success"></i><?php echo e(__('instructor::attendance.recent_activity')); ?></h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $recentAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item px-0 border-0 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?> bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-check text-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?>"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold small"><?php echo e($record->student->name); ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?php echo e($record->course->title); ?></div>
                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo e($record->check_in_time->diffForHumans()); ?></small>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?> bg-opacity-10 text-<?php echo e($record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : 'danger')); ?> rounded-pill" style="font-size: 0.65rem;">
                                            <?php if($record->status == 'present'): ?>
                                                <?php echo e(__('instructor::attendance.present')); ?>

                                            <?php elseif($record->status == 'late'): ?>
                                                <?php echo e(__('instructor::attendance.late')); ?>

                                            <?php else: ?>
                                                <?php echo e(__('instructor::attendance.absent')); ?>

                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4 text-muted small"><?php echo e(__('instructor::attendance.no_recent_records')); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\attendance\index.blade.php ENDPATH**/ ?>