

<?php $__env->startSection('page-title', __('instructor::schedules.title')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::schedules.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('instructor.schedules.create')); ?>" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('instructor::schedules.add_schedule')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="scheduleSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="<?php echo e(__('instructor::schedules.search_placeholder')); ?>">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span id="scheduleResultCount" class="badge rounded-pill px-3 py-2" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);"></span>
                </div>
            </div>
        </div>
    </div>

    <?php
        $daysOfWeek = [
            0 => ['name' => __('instructor::schedules.days.0'), 'color' => 'primary'],
            1 => ['name' => __('instructor::schedules.days.1'), 'color' => 'success'],
            2 => ['name' => __('instructor::schedules.days.2'), 'color' => 'info'],
            3 => ['name' => __('instructor::schedules.days.3'), 'color' => 'warning'],
            4 => ['name' => __('instructor::schedules.days.4'), 'color' => 'danger'],
            5 => ['name' => __('instructor::schedules.days.5'), 'color' => 'secondary'],
            6 => ['name' => __('instructor::schedules.days.6'), 'color' => 'dark'],
        ];

        $groupedSchedules = $schedules->groupBy('day_of_week')->sortKeys();
    ?>

    <div class="row g-4" id="schedulesContainer">
        <?php $__currentLoopData = $daysOfWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayIndex => $dayInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($groupedSchedules->has($dayIndex)): ?>
                <div class="col-12 day-section" data-day="<?php echo e($dayIndex); ?>">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 rounded-3 me-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);">
                            <i class="fas fa-calendar-day fa-lg"></i>
                        </div>
                        <h4 class="fw-bold mb-0"><?php echo e(__('instructor::schedules.days.' . $dayIndex)); ?></h4>
                        <div class="ms-auto flex-grow-1 mx-3 border-bottom opacity-10"></div>
                        <span class="badge bg-light text-dark rounded-pill day-count"><?php echo e(count($groupedSchedules[$dayIndex])); ?> <?php echo e(__('instructor::schedules.session_word')); ?></span>
                    </div>

                    <div class="row g-3 sessions-row">
                        <?php $__currentLoopData = $groupedSchedules[$dayIndex]->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $confirmedBookings = $schedule->bookings->where('status', 'confirmed')->count();
                                $occupancyRate = $schedule->max_students > 0 ? ($confirmedBookings / $schedule->max_students) * 100 : 0;
                                $statusColor = $occupancyRate >= 100 ? 'danger' : ($occupancyRate > 80 ? 'warning' : 'success');
                            ?>
                            <div class="col-md-6 col-xl-4 session-col" data-title="<?php echo e($schedule->course->title); ?>">
                                <div class="card border-0 shadow-sm rounded-4 h-100 session-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-light rounded-pill mb-2 px-3 py-2" style="color: var(--primary-color);">
                                                    <i class="far fa-clock me-1"></i>
                                                    <?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('h:i A')); ?> - 
                                                    <?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('h:i A')); ?>

                                                </span>
                                                <h5 class="fw-bold text-dark mb-1 course-title"><?php echo e($schedule->course->title); ?></h5>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-link link-dark p-0 text-decoration-none" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                    <li><a class="dropdown-item" href="<?php echo e(route('instructor.schedules.edit', $schedule)); ?>"><i class="fas fa-edit me-2"></i> <?php echo e(__('instructor::sidebar.edit')); ?></a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="<?php echo e(route('instructor.schedules.destroy', $schedule)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('instructor::messages.confirm_delete') ?? 'Are you sure?'); ?>')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i> <?php echo e(__('instructor::sidebar.delete')); ?></button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-map-marker-alt text-muted me-2" style="width: 20px;"></i>
                                                <span class="text-secondary small">
                                                    <?php if($schedule->classroom): ?>
                                                        <?php echo e($schedule->classroom->name); ?>

                                                    <?php elseif($schedule->location): ?>
                                                        <?php echo e($schedule->location); ?>

                                                    <?php else: ?>
                                                        <?php echo e(app('tenant')->name ?? __('instructor::schedules.hall_not_specified')); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted fw-semibold"><?php echo e(__('instructor::schedules.capacity')); ?></small>
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

        <div id="noScheduleResults" class="col-12 text-center py-5 d-none">
            <i class="far fa-calendar-times display-1 text-light mb-3"></i>
            <h4 class="text-muted"><?php echo e(__('instructor::schedules.no_matching_sessions')); ?></h4>
        </div>

        <?php if($groupedSchedules->isEmpty()): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                            <i class="far fa-calendar-times text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                    <h4 class="text-muted fw-bold"><?php echo e(__('instructor::schedules.no_schedules')); ?></h4>
                    <div class="mt-3">
                        <a href="<?php echo e(route('instructor.schedules.create')); ?>" class="btn btn-primary rounded-pill px-4 border-0">
                             <i class="fas fa-plus me-2"></i> <?php echo e(__('instructor::schedules.add_schedule')); ?>

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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('scheduleSearchInput');
        const daySections = document.querySelectorAll('.day-section');
        const noResults = document.getElementById('noScheduleResults');
        const resultCount = document.getElementById('scheduleResultCount');

        function applyScheduleFilters() {
            const query = searchInput.value.trim().toLowerCase();
            let totalVisible = 0;

            daySections.forEach(section => {
                const sessions = section.querySelectorAll('.session-col');
                let dayVisibleCount = 0;

                sessions.forEach(session => {
                    const title = session.dataset.title.toLowerCase();
                    if (!query || title.includes(query)) {
                        session.classList.remove('d-none');
                        dayVisibleCount++;
                    } else {
                        session.classList.add('d-none');
                    }
                });

                // Update day count badge
                const countBadge = section.querySelector('.day-count');
                if (countBadge) countBadge.textContent = dayVisibleCount + ' <?php echo e(__('instructor::schedules.session_word')); ?>';

                // Show/hide day section
                if (dayVisibleCount > 0) {
                    section.classList.remove('d-none');
                    totalVisible += dayVisibleCount;
                } else {
                    section.classList.add('d-none');
                }
            });

            if (resultCount) resultCount.textContent = totalVisible + ' <?php echo e(__('instructor::schedules.total_sessions')); ?>';
            if (noResults) {
                // Only show "no results" if we started with data but filtered everything out
                const hasInitialData = daySections.length > 0;
                noResults.classList.toggle('d-none', totalVisible > 0 || !hasInitialData);
            }
        }

        if (searchInput) searchInput.addEventListener('input', applyScheduleFilters);
        applyScheduleFilters();
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\schedules\index.blade.php ENDPATH**/ ?>