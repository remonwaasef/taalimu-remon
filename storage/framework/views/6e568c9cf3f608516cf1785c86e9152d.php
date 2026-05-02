

<?php $__env->startSection('title', __('center::branches.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-building me-2"></i> <?php echo e(__('center::branches.title')); ?></h5>
                <a href="<?php echo e(route('center.branches.create', ['tenant' => $tenant->domain])); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> <?php echo e(__('center::branches.add_new')); ?>

                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4"><?php echo e(__('center::branches.branch_name')); ?></th>
                                <th><?php echo e(__('center::branches.address')); ?></th>
                                <th><?php echo e(__('center::branches.phone')); ?></th>
                                <th><?php echo e(__('center::branches.manager')); ?></th>
                                <th class="text-end pe-4"><?php echo e(__('center::branches.actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo e($branch->name); ?></td>
                                    <td><?php echo e($branch->address ?? '-'); ?></td>
                                    <td><?php echo e($branch->phone ?? '-'); ?></td>
                                    <td>
                                        <?php if($branch->manager): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 25px; font-size: 0.75rem;">
                                                    <?php echo e(substr($branch->manager->name, 0, 1)); ?>

                                                </div>
                                                <span><?php echo e($branch->manager->name); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small"><?php echo e(__('center::branches.not_assigned')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo e(route('center.branches.edit', ['branch' => $branch->id, 'tenant' => $tenant->domain])); ?>" class="btn btn-sm btn-light text-primary me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('center.branches.destroy', ['branch' => $branch->id, 'tenant' => $tenant->domain])); ?>" method="POST" class="d-inline" onsubmit="return confirm('<?php echo e(__('center::branches.are_you_sure')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-light text-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-building fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0"><?php echo e(__('center::branches.no_branches_found')); ?></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\branches\index.blade.php ENDPATH**/ ?>