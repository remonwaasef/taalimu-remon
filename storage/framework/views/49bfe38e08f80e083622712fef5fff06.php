

<?php $__env->startSection('title', __('admin.tickets.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('admin.tickets.title')); ?></h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('admin.tickets.id')); ?></th>
                            <th><?php echo e(__('admin.tickets.tenant')); ?></th>
                            <th><?php echo e(__('admin.tickets.subject')); ?></th>
                            <th><?php echo e(__('admin.tickets.category')); ?></th>
                            <th><?php echo e(__('admin.tickets.priority')); ?></th>
                            <th><?php echo e(__('admin.tickets.status')); ?></th>
                            <th><?php echo e(__('admin.tickets.last_updated')); ?></th>
                            <th><?php echo e(__('admin.tickets.action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>#<?php echo e($ticket->id); ?></td>
                            <td><?php echo e($ticket->tenant->name); ?></td>
                            <td><?php echo e($ticket->subject); ?></td>
                            <td><?php echo e(ucfirst($ticket->category)); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'info')); ?>">
                                    <?php echo e(__('admin.tickets.priorities.' . $ticket->priority) ?? ucfirst($ticket->priority)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($ticket->status == 'open' ? 'success' : ($ticket->status == 'closed' ? 'secondary' : 'primary')); ?>">
                                    <?php echo e(__('admin.tickets.statuses.' . $ticket->status) ?? ucfirst($ticket->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($ticket->updated_at->diffForHumans()); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.tickets.show', $ticket->id)); ?>" class="btn btn-info btn-sm"><?php echo e(__('admin.tickets.view')); ?></a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($tickets->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\tickets\index.blade.php ENDPATH**/ ?>