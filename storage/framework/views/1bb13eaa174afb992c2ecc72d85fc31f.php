

<?php $__env->startSection('title', __('center::messages.blade_0025')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('center::messages.blade_0017')); ?></h1>
        <a href="<?php echo e(route('center.analytics.index')); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-right"></i><?php echo e(__('center::messages.blade_0018')); ?></a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('center::messages.blade_0019')); ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('center::messages.blade_0020')); ?></th>
                            <th><?php echo e(__('center::messages.blade_0021')); ?></th>
                            <th><?php echo e(__('center::messages.blade_0022')); ?></th>
                            <th><?php echo e(__('center::messages.blade_0023')); ?></th>
                            <th><?php echo e(__('center::messages.blade_0024')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($course->title); ?></td>
                                <td><?php echo e($course->instructor->name ?? __('center::messages.blade_0026')); ?></td>
                                <td><?php echo e($course->enrollments_count); ?></td>
                                <td><?php echo e($course->schedules_count); ?></td>
                                <td><?php echo e(format_price($course->price)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\analytics\courses.blade.php ENDPATH**/ ?>