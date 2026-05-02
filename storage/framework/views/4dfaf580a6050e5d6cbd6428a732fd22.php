

<?php $__env->startSection('page-title', __('instructor::groups.title')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::groups.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('instructor.groups.create')); ?>" class="btn btn-glass">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('instructor::groups.create_new')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    

    
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="groupSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="<?php echo e(__('instructor::groups.search_placeholder')); ?>">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span id="groupResultCount" class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 300px;">
                <table class="table table-hover align-middle mb-0 text-center" id="groupsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-start"><?php echo e(__('instructor::groups.table_group')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::groups.students_count')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::groups.registration_link')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::groups.status')); ?></th>
                            <th class="border-0"><?php echo e(__('instructor::groups.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="group-row" data-title="<?php echo e($course->title); ?>">
                            <td class="px-4 py-3 text-start">
                                <div class="fw-bold fs-5" style="color: var(--primary-color);"><?php echo e($course->title); ?></div>
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    <?php $__empty_2 = true; $__currentLoopData = $course->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <?php
                                            $days = [
                                                __('instructor::groups.days.Sunday'),
                                                __('instructor::groups.days.Monday'),
                                                __('instructor::groups.days.Tuesday'),
                                                __('instructor::groups.days.Wednesday'),
                                                __('instructor::groups.days.Thursday'),
                                                __('instructor::groups.days.Friday'),
                                                __('instructor::groups.days.Saturday')
                                            ];
                                        ?>
                                        <span class="badge border border-primary text-primary rounded-pill fw-normal" style="color: var(--primary-color) !important; border-color: var(--primary-color) !important; background: transparent;">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            <?php echo e($days[$schedule->day_of_week]); ?> 
                                            (<?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('h:i A')); ?>)
                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <span class="badge bg-light text-muted border rounded-pill fw-normal"><?php echo e(__('instructor::groups.no_schedules')); ?></span>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('instructor.schedules.create', ['course_id' => $course->id])); ?>" class="badge border rounded-pill fw-normal text-decoration-none ms-1" style="color: var(--primary-color) !important; border-color: var(--primary-color) !important; border-style: dashed !important; background: transparent; transition: all 0.2s;" onmouseover="this.style.background='rgba(16,185,129,0.1)'" onmouseout="this.style.background='transparent'">
                                        <i class="fas fa-plus fa-sm"></i> <?php echo e(__('instructor::groups.add_schedule')); ?>

                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 rounded-pill px-3" style="background-color: rgba(58, 12, 163, 0.1); color: var(--primary-color);"><?php echo e($course->enrollments_count ?? 0); ?> <?php echo e(__('instructor::groups.student')); ?></span>
                            </td>
                            <td>
                                <?php if($course->registration_token): ?>
                                    <div class="input-group input-group-sm rounded-pill overflow-hidden" style="max-width: 250px; margin: 0 auto; border: 1px solid var(--primary-color);">
                                        <input type="text" class="form-control border-0 bg-light text-center" value="<?php echo e($course->getRegistrationUrl()); ?>" readonly id="link_<?php echo e($course->id); ?>">
                                        <button class="btn btn-primary px-3 border-0" style="background: var(--primary-color);" onclick="copyLink('link_<?php echo e($course->id); ?>')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small"><?php echo e(__('instructor::groups.no_link')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo e(__('instructor::groups.active')); ?></span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" data-bs-boundary="viewport" style="color: var(--primary-color);">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
                                        <li><a class="dropdown-item rounded-3" href="<?php echo e(route('instructor.scanner', $course->id)); ?>"><i class="fas fa-qrcode me-2" style="color: var(--primary-color);"></i> <?php echo e(__('instructor::groups.qr_scanner')); ?></a></li>
                                        <li><a class="dropdown-item rounded-3" href="<?php echo e(route('instructor.groups.edit', $course->id)); ?>"><i class="fas fa-edit me-2 text-muted"></i> <?php echo e(__('instructor::groups.edit_data')); ?></a></li>
                                        <li>
                                            <form action="<?php echo e(route('instructor.groups.duplicate', $course->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="dropdown-item"><i class="fas fa-copy me-2 text-muted"></i> <?php echo e(__('instructor::groups.duplicate_group')); ?></button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('instructor.groups.rotate-link', $course->id)); ?>" method="POST" id="rotateForm_<?php echo e($course->id); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="button" class="dropdown-item" onclick="if(confirm('<?php echo e(__('instructor::groups.confirm_rotate_link')); ?>')) document.getElementById('rotateForm_<?php echo e($course->id); ?>').submit();">
                                                    <i class="fas fa-sync me-2 text-muted"></i> <?php echo e(__('instructor::groups.generate_new_link')); ?>

                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="<?php echo e(route('instructor.groups.destroy', $course->id)); ?>" method="POST" id="deleteForm_<?php echo e($course->id); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="dropdown-item text-danger" onclick="if(confirm('<?php echo e(__('instructor::groups.confirm_delete_group')); ?>')) document.getElementById('deleteForm_<?php echo e($course->id); ?>').submit();">
                                                    <i class="fas fa-trash me-2"></i> <?php echo e(__('instructor::groups.delete_group')); ?>

                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-4">
                                    <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.05);">
                                        <i class="fas fa-folder-open text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted fw-bold"><?php echo e(__('instructor::groups.no_groups')); ?></h6>
                                <p class="text-muted small"><?php echo e(__('instructor::groups.no_groups_hint')); ?></p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="noGroupsResults" class="text-center py-5 d-none">
                <i class="fas fa-search-minus display-4 text-light mb-3"></i>
                <p class="text-muted"><?php echo e(__('instructor::groups.no_results')); ?></p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("<?php echo e(__('instructor::groups.link_copied')); ?>");
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('groupSearchInput');
    const rows = document.querySelectorAll('.group-row');
    const noResults = document.getElementById('noGroupsResults');
    const resultCount = document.getElementById('groupResultCount');
    const table = document.getElementById('groupsTable');

    function applyGroupFilters() {
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

        if (resultCount) resultCount.textContent = visibleCount + ' <?php echo e(__('instructor::groups.student')); ?>';
        if (noResults) noResults.classList.toggle('d-none', visibleCount > 0 || rows.length === 0);
        if (table) table.classList.toggle('d-none', visibleCount === 0 && rows.length > 0);
    }

    if (searchInput) searchInput.addEventListener('input', applyGroupFilters);
    applyGroupFilters();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\groups\index.blade.php ENDPATH**/ ?>