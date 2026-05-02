

<?php $__env->startSection('page-title', __('center::sidebar.schedules')); ?>
<?php $__env->startSection('page-subtitle', __('center::schedules.weekly_overview')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.schedules.create')); ?>" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('center::schedules.add_new')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $daysOfWeek = [
            0 => ['name' => __('center::schedules.sunday'), 'color' => 'primary'],
            1 => ['name' => __('center::schedules.monday'), 'color' => 'success'],
            2 => ['name' => __('center::schedules.tuesday'), 'color' => 'info'],
            3 => ['name' => __('center::schedules.wednesday'), 'color' => 'warning'],
            4 => ['name' => __('center::schedules.thursday'), 'color' => 'danger'],
            5 => ['name' => __('center::schedules.friday'), 'color' => 'secondary'],
            6 => ['name' => __('center::schedules.saturday'), 'color' => 'dark'],
        ];

        $groupedSchedules = $schedules->groupBy('day_of_week')->sortKeys();
    ?>

    <div class="row g-4">
        <?php $__currentLoopData = $daysOfWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayIndex => $dayInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($groupedSchedules->has($dayIndex)): ?>
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 rounded-3 bg-<?php echo e($dayInfo['color']); ?> bg-opacity-10 text-<?php echo e($dayInfo['color']); ?> me-3">
                            <i class="fas fa-calendar-day fa-lg"></i>
                        </div>
                        <h4 class="fw-bold mb-0"><?php echo e($dayInfo['name']); ?></h4>
                        <div class="ms-auto flex-grow-1 mx-3 border-bottom opacity-10"></div>
                        <span class="badge bg-light text-dark rounded-pill"><?php echo e(count($groupedSchedules[$dayIndex])); ?> <?php echo e(__('center::schedules.sessions')); ?></span>
                    </div>

                    <div class="row g-3">
                        <?php $__currentLoopData = $groupedSchedules[$dayIndex]->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $confirmedBookings = $schedule->bookings->where('status', 'confirmed')->count();
                                $occupancyRate = $schedule->max_students > 0 ? ($confirmedBookings / $schedule->max_students) * 100 : 0;
                                $statusColor = $occupancyRate >= 100 ? 'danger' : ($occupancyRate > 80 ? 'warning' : 'success');
                            ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100 session-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-light text-primary rounded-pill mb-2 px-3 py-2">
                                                    <i class="far fa-clock me-1"></i>
                                                    <?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('h:i A')); ?> - 
                                                    <?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('h:i A')); ?>

                                                </span>
                                                <h5 class="fw-bold text-dark mb-1"><?php echo e($schedule->course->title); ?></h5>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                    <li><a class="dropdown-item" href="<?php echo e(route('center.schedules.edit', $schedule)); ?>"><i class="fas fa-edit me-2"></i> <?php echo e(__('center::schedules.edit')); ?></a></li>
                                                    <li><a class="dropdown-item" href="<?php echo e(route('center.attendance.qr', $schedule->id)); ?>"><i class="fas fa-qrcode me-2"></i> <?php echo e(__('center::schedules.qr_code')); ?></a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="<?php echo e(route('center.schedules.destroy', $schedule)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('center::schedules.confirm_delete')); ?>')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i> <?php echo e(__('center::schedules.delete')); ?></button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-user-tie text-muted me-2" style="width: 20px;"></i>
                                                <span class="text-secondary small"><?php echo e($schedule->instructor->name ?? __('center::schedules.instructor')); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-door-open text-muted me-2" style="width: 20px;"></i>
                                                <span class="text-secondary small"><?php echo e($schedule->classroom->name ?? __('center::schedules.classroom')); ?></span>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted fw-semibold"><?php echo e(__('center::schedules.capacity')); ?></small>
                                                <small class="fw-bold text-<?php echo e($statusColor); ?>"><?php echo e($confirmedBookings); ?>/<?php echo e($schedule->max_students); ?></small>
                                            </div>
                                            <div class="progress rounded-pill shadow-none" style="height: 6px;">
                                                <div class="progress-bar bg-<?php echo e($statusColor); ?>" role="progressbar" style="width: <?php echo e($occupancyRate); ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($groupedSchedules->isEmpty()): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                            <i class="fas fa-calendar-alt text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                    <h5 class="text-muted fw-bold"><?php echo e(__('center::schedules.no_schedules_found')); ?></h5>
                    <div class="mt-3">
                        <a href="<?php echo e(route('center.schedules.create')); ?>" class="btn btn-primary rounded-pill px-4">
                            <?php echo e(__('center::schedules.add_your_first')); ?>

                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .session-card {
            transition: all 0.3s ease;
            cursor: default;
        }
        .session-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\schedules\index.blade.php ENDPATH**/ ?>