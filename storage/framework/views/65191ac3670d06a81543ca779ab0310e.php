

<?php $__env->startSection('title', __('center::roles.create_role')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary"><?php echo e(__('center::roles.create_new_role')); ?></h5>
                        <a href="<?php echo e(route('center.roles.index', ['tenant' => $tenant->domain])); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> <?php echo e(__('center::roles.back_to_list')); ?>

                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?php echo e(route('center.roles.store', ['tenant' => $tenant->domain])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Role Name -->
                        <div class="mb-5" style="max-width: 600px;">
                            <label for="name" class="form-label fw-bold text-dark"><?php echo e(__('center::roles.role_name')); ?></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> shadow-none p-2 border" 
                                   id="name" name="name" value="<?php echo e(old('name')); ?>" 
                                   placeholder="<?php echo e(__('center::roles.role_name_placeholder')); ?>">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text text-muted"><?php echo e(__('center::roles.role_name_help')); ?></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                            <h6 class="fw-bold mb-0 text-uppercase tracking-wider text-secondary"><?php echo e(__('center::roles.permissions_matrix')); ?></h6>
                            <button type="button" class="btn btn-sm btn-light text-primary fw-bold" onclick="toggleAllPermissions()">
                                <?php echo e(__('center::roles.toggle_all_globally')); ?>

                            </button>
                        </div>

                        <!-- Permission Matrix -->
                        <div class="row g-4">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-xl-4 permission-group-card">
                                    <div class="card h-100 border shadow-none hover-shadow transition-all">
                                        <div class="card-header bg-light border-bottom-0 d-flex justify-content-between align-items-center py-2">
                                            <span class="fw-bold text-uppercase small text-dark">
                                                <i class="fas fa-layer-group me-1 text-muted"></i> <?php echo e(__('center::roles.group_' . $group)); ?>

                                            </span>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input group-toggle" type="checkbox" role="switch" 
                                                       data-group="group-<?php echo e(Str::slug($group)); ?>" 
                                                       id="toggle_<?php echo e(Str::slug($group)); ?>"
                                                       title="<?php echo e(__('center::roles.select_all_in_group')); ?>">
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="list-group list-group-flush">
                                                <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer border-0 py-2 px-3">
                                                        <input class="form-check-input me-3 mt-0 group-<?php echo e(Str::slug($group)); ?> permission-checkbox" 
                                                               type="checkbox" name="permissions[]" 
                                                               value="<?php echo e($permission->name); ?>" 
                                                               id="perm_<?php echo e($permission->id); ?>">
                                                        <span class="small user-select-none">
                                                            <?php echo e(__('center::roles.perm_' . str_replace(' ', '_', $permission->name))); ?>

                                                        </span>
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="<?php echo e(route('center.roles.index', ['tenant' => $tenant->domain])); ?>" class="btn btn-light me-2 px-4"><?php echo e(__('center::roles.cancel')); ?></a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> <?php echo e(__('center::roles.create_role_btn')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Group Toggles
        document.querySelectorAll('.group-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const groupClass = this.dataset.group;
                const isChecked = this.checked;
                document.querySelectorAll('.' + groupClass).forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });
        });

        // Handle Individual Checkbox Changes to update Group Toggle state
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Find which group this belongs to
                let groupClass = null;
                this.classList.forEach(cls => {
                    if (cls.startsWith('group-')) groupClass = cls;
                });

                if (groupClass) {
                    const allInGroup = document.querySelectorAll('.' + groupClass);
                    const allChecked = Array.from(allInGroup).every(cb => cb.checked);
                    const toggle = document.querySelector('.group-toggle[data-group="' + groupClass + '"]');
                    if (toggle) toggle.checked = allChecked;
                }
            });
        });
    });

    function toggleAllPermissions() {
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        // Check if all are currently checked
        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        
        // Toggle them
        allCheckboxes.forEach(cb => cb.checked = !allChecked);
        
        // Update all group toggles
        document.querySelectorAll('.group-toggle').forEach(toggle => toggle.checked = !allChecked);
    }
</script>

<style>
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.05)!important;
        transform: translateY(-2px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\roles\create.blade.php ENDPATH**/ ?>