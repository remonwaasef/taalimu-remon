

<?php $__env->startSection('title', 'Support Tickets'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Support Tickets</h1>
        <a href="<?php echo e(route('center.tickets.create')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> New Ticket
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>#<?php echo e($ticket->id); ?></td>
                            <td><?php echo e($ticket->subject); ?></td>
                            <td><?php echo e(ucfirst($ticket->category)); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($ticket->priority == 'high' ? 'danger' : ($ticket->priority == 'medium' ? 'warning' : 'info')); ?>">
                                    <?php echo e(ucfirst($ticket->priority)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($ticket->status == 'open' ? 'success' : ($ticket->status == 'closed' ? 'secondary' : 'primary')); ?>">
                                    <?php echo e(ucfirst($ticket->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($ticket->updated_at->diffForHumans()); ?></td>
                            <td>
                                <a href="<?php echo e(route('center.tickets.show', $ticket->id)); ?>" class="btn btn-info btn-sm">View</a>
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

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/tickets/index.blade.php ENDPATH**/ ?>