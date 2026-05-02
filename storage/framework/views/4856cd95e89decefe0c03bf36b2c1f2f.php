

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('Activity Logs')); ?></h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('User')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                            <th><?php echo e(__('Subject')); ?></th>
                            <th><?php echo e(__('Changes')); ?></th>
                            <th><?php echo e(__('Date')); ?></th>
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
                                    System
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($activity->event == 'created' ? 'success' : ($activity->event == 'updated' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($activity->event ?: $activity->description)); ?>

                                </span>
                            </td>
                            <td>
                                <?php echo e(class_basename($activity->subject_type)); ?>

                                <?php if($activity->subject): ?>
                                    #<?php echo e($activity->subject->id); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($activity->event == 'updated'): ?>
                                    <small>
                                        <?php $__currentLoopData = $activity->changes['attributes'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(!in_array($key, ['password', 'remember_token', 'google2fa_secret'])): ?>
                                                <strong><?php echo e($key); ?>:</strong> 
                                                <span class="text-danger"><?php echo e($activity->changes['old'][$key] ?? 'null'); ?></span> 
                                                -> 
                                                <span class="text-success"><?php echo e(is_array($value) ? json_encode($value) : $value); ?></span><br>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </small>
                                <?php elseif($activity->event == 'created'): ?>
                                    <small>Created</small>
                                <?php elseif($activity->event == 'deleted'): ?>
                                    <small>Deleted</small>
                                <?php else: ?>
                                    <small><?php echo e($activity->description); ?></small>
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

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\activity_logs\index.blade.php ENDPATH**/ ?>