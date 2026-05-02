

<?php $__env->startSection('title', __('center::branches.create_branch')); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary"><?php echo e(__('center::branches.create_branch')); ?></h5>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo e(route('center.branches.store', ['tenant' => $tenant->domain])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold"><?php echo e(__('center::branches.branch_name')); ?></label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
 
                    <div class="mb-3">
                        <label for="address" class="form-label"><?php echo e(__('center::branches.address')); ?></label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
 
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label"><?php echo e(__('center::branches.phone')); ?></label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label"><?php echo e(__('center::branches.manager')); ?></label>
                            <select class="form-select" id="manager_id" name="manager_id">
                                <option value=""><?php echo e(__('center::branches.select_manager')); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->role); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
 
                    <div class="d-flex justify-content-end mt-4">
                        <a href="<?php echo e(route('center.branches.index', ['tenant' => $tenant->domain])); ?>" class="btn btn-light me-2"><?php echo e(__('center::branches.cancel')); ?></a>
                        <button type="submit" class="btn btn-primary px-4"><?php echo e(__('center::branches.create_branch')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\branches\create.blade.php ENDPATH**/ ?>