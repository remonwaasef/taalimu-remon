

<?php $__env->startSection('title', 'تعديل المركز'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">تعديل المركز</h2>
        <a href="<?php echo e(route('admin.tenants.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4">
            عودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="<?php echo e(route('admin.tenants.update', $tenant->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">اسم المركز</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="مثال: أكاديمية النور" value="<?php echo e(old('name', $tenant->name)); ?>" required>
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

                        <div class="mb-4">
                            <label class="form-label fw-bold">النطاق الفرعي (Subdomain)</label>
                            <div class="input-group">
                                <input type="text" name="domain" class="form-control form-control-lg bg-light border-0" placeholder="academy" value="<?php echo e(old('domain', $tenant->domain)); ?>" required>
                                <span class="input-group-text border-0 bg-white text-muted">.<?php echo e(config('app.tenant_domain', 'localhost')); ?></span>
                            </div>
                            <?php $__errorArgs = ['domain'];
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

                        <div class="mb-4">
                            <label class="form-label fw-bold">الحالة</label>
                            <select name="status" class="form-select form-select-lg bg-light border-0">
                                <option value="active" <?php echo e(old('status', $tenant->status) == 'active' ? 'selected' : ''); ?>>نشط</option>
                                <option value="inactive" <?php echo e(old('status', $tenant->status) == 'inactive' ? 'selected' : ''); ?>>غير نشط</option>
                            </select>
                            <?php $__errorArgs = ['status'];
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

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">تحديث المركز</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Admin\resources\views\tenants\edit.blade.php ENDPATH**/ ?>