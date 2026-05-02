

<?php $__env->startSection('title', __('center::users.title')); ?>
<?php $__env->startSection('page-title', __('center::users.subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><?php echo e(__('center::users.list_title')); ?></h5>
                <a href="<?php echo e(route('center.users.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i><?php echo e(__('center::users.add_user')); ?></a>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><?php echo e(__('center::users.name')); ?></th>
                                <th><?php echo e(__('center::users.email')); ?></th>
                                <th><?php echo e(__('center::users.role')); ?></th>
                                <th><?php echo e(__('center::users.joined_at')); ?></th>
                                <th><?php echo e(__('center::users.actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo e($user->name); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <?php if($user->role == 'center_admin'): ?>
                                        <span class="badge bg-primary"><?php echo e(__('center::users.roles.center_admin')); ?></span>
                                    <?php elseif($user->role == 'secretary'): ?>
                                        <span class="badge bg-info text-dark"><?php echo e(__('center::users.roles.secretary')); ?></span>
                                    <?php elseif($user->role == 'accountant'): ?>
                                        <span class="badge bg-success"><?php echo e(__('center::users.roles.accountant')); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e($user->role); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($user->created_at->format('Y-m-d')); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo e(route('center.users.edit', $user->id)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if(auth()->id() !== $user->id): ?>
                                        <form action="<?php echo e(route('center.users.destroy', $user->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('center::users.delete_confirm')); ?>');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted"><?php echo e(__('center::users.no_users')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <?php echo e($users->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\users\index.blade.php ENDPATH**/ ?>