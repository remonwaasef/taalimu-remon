<?php $__env->startSection('page-title', __('instructor::dashboard.online_classes')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::online_classes.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('instructor.online_classes.create')); ?>" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('instructor::online_classes.add_new')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    

    
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="lessonSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="<?php echo e(__('instructor::online_classes.search')); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 300px;">
                <table class="table table-hover align-middle mb-0 text-center" id="lessonsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start"><?php echo e(__('instructor::online_classes.lesson_details')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::online_classes.group')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::online_classes.start_time')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::online_classes.meeting_link')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::online_classes.status')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::online_classes.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $onlineClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="lesson-row" data-title="<?php echo e($lesson->title); ?>">
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold fs-5" style="color: var(--primary-color);"><?php echo e($lesson->title); ?></div>
                                <div class="text-muted small mb-2"><i class="fas fa-video me-1"></i> <?php echo e(ucfirst($lesson->platform)); ?></div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 rounded-pill px-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);"><?php echo e($lesson->course->title ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <div><?php echo e($lesson->start_time->format('Y-m-d')); ?></div>
                                <div class="text-muted small"><?php echo e($lesson->start_time->format('h:i A')); ?> (<?php echo e($lesson->duration_minutes); ?> <?php echo e(__('instructor::online_classes.minutes')); ?>)</div>
                            </td>
                            <td>
                                <a href="<?php echo e($lesson->meeting_link); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-external-link-alt me-1"></i> <?php echo e(__('instructor::online_classes.open_link')); ?>

                                </a>
                                <?php if($lesson->meeting_id): ?>
                                    <div class="text-muted small mt-1">ID: <?php echo e($lesson->meeting_id); ?></div>
                                <?php endif; ?>
                                <?php if($lesson->meeting_password): ?>
                                    <div class="text-muted small">Pass: <?php echo e($lesson->meeting_password); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($lesson->status == 'scheduled'): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3"><?php echo e(__('instructor::online_classes.scheduled')); ?></span>
                                <?php elseif($lesson->status == 'in_progress'): ?>
                                    <span class="badge bg-info text-white rounded-pill px-3"><?php echo e(__('instructor::online_classes.in_progress')); ?></span>
                                <?php elseif($lesson->status == 'completed'): ?>
                                    <span class="badge bg-success text-white rounded-pill px-3"><?php echo e(__('instructor::online_classes.completed')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white rounded-pill px-3"><?php echo e(__('instructor::online_classes.canceled')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport" style="color: var(--primary-color);">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li><a class="dropdown-item rounded-3" href="<?php echo e(route('instructor.online_classes.edit', $lesson->id)); ?>"><i class="fas fa-edit me-2 text-muted"></i> <?php echo e(__('instructor::online_classes.edit')); ?></a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="<?php echo e(route('instructor.online_classes.destroy', $lesson->id)); ?>" method="POST" id="deleteForm_<?php echo e($lesson->id); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('<?php echo e(__('instructor::online_classes.confirm_delete')); ?>')) document.getElementById('deleteForm_<?php echo e($lesson->id); ?>').submit();">
                                                    <i class="fas fa-trash me-2"></i> <?php echo e(__('instructor::online_classes.delete')); ?>

                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-4">
                                    <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                                        <i class="fas fa-video-slash text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted fw-bold"><?php echo e(__('instructor::online_classes.no_classes')); ?></h6>
                                <p class="text-muted small"><?php echo e(__('instructor::online_classes.no_classes_desc')); ?></p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="noLessonsResults" class="text-center py-5 d-none">
                <i class="fas fa-search-minus display-4 text-light mb-3"></i>
                <p class="text-muted"><?php echo e(__('instructor::online_classes.no_results')); ?></p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('lessonSearchInput');
    const rows = document.querySelectorAll('.lesson-row');
    const noResults = document.getElementById('noLessonsResults');
    const table = document.getElementById('lessonsTable');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.dataset.title.toLowerCase();
            if (!query || title.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (noResults) noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        if (table) table.classList.toggle('d-none', visibleCount === 0 && rows.length > 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\online_classes\index.blade.php ENDPATH**/ ?>