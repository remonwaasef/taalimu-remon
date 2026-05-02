

<?php $__env->startSection('title', __('admin.roles.edit_role')); ?>
<?php $__env->startSection('page-title', __('admin.roles.edit_role')); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4 animate__animated animate__fadeIn">
    <div class="col-12">
        <form action="<?php echo e(route('admin.roles.update', $role->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold text-dark"><?php echo e(__('admin.roles.role_name')); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-user-tag text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control rounded-end-3 shadow-none border <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required value="<?php echo e(old('name', $role->name)); ?>" <?php echo e($role->name == 'super_admin' ? 'readonly' : ''); ?>>
                            </div>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 me-2">
                                <i class="fas fa-save me-2"></i> <?php echo e(__('admin.save_changes')); ?>

                            </button>
                            <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-light rounded-pill px-4 border">
                                <?php echo e(__('admin.cancel')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-4 text-dark px-2">
                <i class="fas fa-key text-primary me-2"></i>
                <?php echo e(__('admin.roles.permissions')); ?>

            </h5>

            <div class="row g-4">
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center">
                                    <span class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <i class="fas fa-folder-open fa-xs"></i>
                                    </span>
                                    <?php echo e(ucfirst($groupName)); ?>

                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-2">
                                    <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check form-switch custom-switch p-0 d-flex justify-content-between align-items-center flex-row-reverse">
                                            <input class="form-check-input ms-0" type="checkbox" id="perm_<?php echo e($permission->id); ?>" name="permissions[]" value="<?php echo e($permission->name); ?>" <?php echo e($role->hasPermissionTo($permission->name) ? 'checked' : ''); ?>>
                                            <label class="form-check-label text-muted small" for="perm_<?php echo e($permission->id); ?>">
                                                <?php echo e(str_replace($groupName, '', $permission->name) ?: $permission->name); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </form>
    </div>
</div>

<style>
    .hover-lift:hover { transform: translateY(-5px); transition: transform 0.2s ease; }
    .custom-switch .form-check-input { width: 40px; height: 20px; cursor: pointer; }
    .form-check-input:checked { background-color: var(--bs-primary); border-color: var(--bs-primary); }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\roles\edit.blade.php ENDPATH**/ ?>