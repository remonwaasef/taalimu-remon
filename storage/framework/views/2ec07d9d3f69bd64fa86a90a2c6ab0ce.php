<?php $__env->startSection('page-title', __('center::online_classes.live_classes')); ?>
<?php $__env->startSection('page-subtitle', __('center::online_classes.subtitle')); ?>

<?php $__env->startSection('content'); ?>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-video fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0"><?php echo e(__('center::online_classes.total_classes')); ?></p>
                            <h4 class="fw-bold mb-0 text-dark"><?php echo e($stats['total']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-calendar-alt fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0"><?php echo e(__('center::online_classes.scheduled')); ?></p>
                            <h4 class="fw-bold mb-0 text-dark"><?php echo e($stats['scheduled']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                            <i class="fas fa-signal fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0"><?php echo e(__('center::online_classes.in_progress')); ?></p>
                            <h4 class="fw-bold mb-0 text-dark"><?php echo e($stats['in_progress']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0"><?php echo e(__('center::online_classes.completed')); ?></p>
                            <h4 class="fw-bold mb-0 text-dark"><?php echo e($stats['completed']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="<?php echo e(route('center.online_classes.index')); ?>" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted"><?php echo e(__('center::online_classes.filter_by_instructor')); ?></label>
                    <select name="instructor_id" class="form-select rounded-pill">
                        <option value=""><?php echo e(__('center::online_classes.all_instructors')); ?></option>
                        <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($inst->id); ?>" <?php echo e(request('instructor_id') == $inst->id ? 'selected' : ''); ?>><?php echo e($inst->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted"><?php echo e(__('center::online_classes.class_status')); ?></label>
                    <select name="status" class="form-select rounded-pill">
                        <option value=""><?php echo e(__('center::online_classes.all_statuses')); ?></option>
                        <option value="scheduled" <?php echo e(request('status') == 'scheduled' ? 'selected' : ''); ?>><?php echo e(__('center::online_classes.scheduled_badge')); ?></option>
                        <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('center::online_classes.in_progress')); ?></option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>><?php echo e(__('center::online_classes.completed_badge')); ?></option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('center::online_classes.cancelled')); ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted"><?php echo e(__('center::online_classes.date')); ?></label>
                    <input type="date" name="date" class="form-control rounded-pill" value="<?php echo e(request('date')); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary rounded-pill w-100"><i class="fas fa-filter me-2"></i> <?php echo e(__('center::online_classes.filter_results')); ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start"><?php echo e(__('center::online_classes.class_details')); ?></th>
                            <th class="border-0"><?php echo e(__('center::online_classes.instructor_group')); ?></th>
                            <th class="border-0"><?php echo e(__('center::online_classes.start_time')); ?></th>
                            <th class="border-0"><?php echo e(__('center::online_classes.link_data')); ?></th>
                            <th class="border-0"><?php echo e(__('center::online_classes.status')); ?></th>
                            <th class="border-0"><?php echo e(__('center::online_classes.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $onlineClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold" style="color: var(--bs-primary);"><?php echo e($lesson->title); ?></div>
                                <div class="text-muted small"><i class="fas fa-desktop me-1"></i> <?php echo e(ucfirst($lesson->platform)); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($lesson->instructor->name ?? 'N/A'); ?></div>
                                <div class="text-muted small"><?php echo e($lesson->course->title ?? 'N/A'); ?></div>
                            </td>
                            <td>
                                <div><?php echo e($lesson->start_time->format('Y-m-d')); ?></div>
                                <div class="text-muted small fw-bold"><?php echo e($lesson->start_time->format('h:i A')); ?> (<?php echo e($lesson->duration_minutes); ?> <?php echo e(__('center::online_classes.minutes')); ?>)</div>
                            </td>
                            <td>
                                <a href="<?php echo e($lesson->meeting_link); ?>" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 text-primary border">
                                    <i class="fas fa-external-link-alt me-1"></i> <?php echo e(__('center::online_classes.open_link')); ?>

                                </a>
                                <?php if($lesson->meeting_id): ?>
                                    <div class="text-muted small mt-1">ID: <?php echo e($lesson->meeting_id); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($lesson->status == 'scheduled'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3"><?php echo e(__('center::online_classes.scheduled_badge')); ?></span>
                                <?php elseif($lesson->status == 'in_progress'): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3"><?php echo e(__('center::online_classes.in_progress')); ?></span>
                                <?php elseif($lesson->status == 'completed'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('center::online_classes.completed_badge')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3"><?php echo e(__('center::online_classes.cancelled')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li>
                                            <form action="<?php echo e(route('center.online_classes.destroy', $lesson->id)); ?>" method="POST" id="deleteForm_<?php echo e($lesson->id); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('<?php echo e(__('center::online_classes.delete_confirm')); ?>')) document.getElementById('deleteForm_<?php echo e($lesson->id); ?>').submit();">
                                                    <i class="fas fa-trash me-2"></i> <?php echo e(__('center::online_classes.delete_and_cancel')); ?>

                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-video-slash fa-3x text-muted mb-3 opacity-50"></i>
                                <h6 class="text-muted"><?php echo e(__('center::online_classes.no_classes_found')); ?></h6>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($onlineClasses->hasPages()): ?>
                <div class="p-3 border-top">
                    <?php echo e($onlineClasses->links('components.ui.pagination')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\online_classes\index.blade.php ENDPATH**/ ?>