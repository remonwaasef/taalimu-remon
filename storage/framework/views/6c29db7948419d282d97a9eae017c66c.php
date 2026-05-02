

<?php $__env->startSection('title', __('admin.activity_log.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('admin.activity_log.title')); ?></h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('admin.activity_log.user')); ?></th>
                            <th><?php echo e(__('admin.activity_log.action')); ?></th>
                            <th><?php echo e(__('admin.activity_log.subject')); ?></th>
                            <th><?php echo e(__('admin.activity_log.changes')); ?></th>
                            <th><?php echo e(__('admin.activity_log.date')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php if($activity->causer): ?>
                                    <?php echo e($activity->causer->name); ?>

                                    <small class="d-block text-muted">(<?php echo e($activity->causer->role); ?>)</small>
                                <?php else: ?>
                                    <?php echo e(__('admin.activity_log.system')); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($activity->event == 'created' ? 'success' : ($activity->event == 'updated' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(__('admin.activity_log.events.' . $activity->event) ?? ucfirst($activity->event)); ?>

                                </span>
                            </td>
                            <td>
                                <?php echo e(__('center::dashboard.models.' . class_basename($activity->subject_type)) ?? class_basename($activity->subject_type)); ?>

                                <?php if($activity->subject): ?>
                                    #<?php echo e($activity->subject->id); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($activity->event == 'updated'): ?>
                                    <small>
                                        <?php $__currentLoopData = $activity->changes['attributes'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <strong><?php echo e($key); ?>:</strong> 
                                            <?php
                                                $oldValue = $activity->changes['old'][$key] ?? 'null';
                                            ?>
                                            <span class="text-danger">
                                                <?php echo e(is_array($oldValue) ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : $oldValue); ?>

                                            </span> 
                                            -> 
                                            <span class="text-success">
                                                <?php echo e(is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value); ?>

                                            </span><br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </small>
                                <?php elseif($activity->event == 'created'): ?>
                                    <small><?php echo e(__('admin.activity_log.events.created')); ?></small>
                                <?php else: ?>
                                    <small><?php echo e(__('admin.activity_log.events.deleted')); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($activity->created_at->format('Y-m-d H:i:s')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($activities->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\activity_logs\index.blade.php ENDPATH**/ ?>