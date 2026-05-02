

<?php $__env->startSection('title', __('center::users.edit_user')); ?>
<?php $__env->startSection('page-title', __('center::users.subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><?php echo e(__('center::users.edit_user')); ?>: <?php echo e($user->name); ?></h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('center.users.update', $user->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label"><?php echo e(__('center::users.full_name')); ?></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>">
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
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><?php echo e(__('center::users.email')); ?></label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="alert alert-info py-2">
                        <small><i class="fas fa-info-circle me-1"></i><?php echo e(__('center::users.password_help')); ?></small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label"><?php echo e(__('center::users.new_password')); ?></label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label"><?php echo e(__('center::users.password_confirmation')); ?></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label"><?php echo e(__('center::users.role')); ?></label>
                        <?php
                            $roleData = __('center::users.role_details');
                        ?>
                        <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" onchange="showRoleDescription(this)">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $normalizedName = strtolower(str_replace(' ', '_', $r->name));
                                    $title = $roleData[$normalizedName]['title'] ?? ucfirst(str_replace('_', ' ', $r->name));
                                    $desc = $roleData[$normalizedName]['desc'] ?? __('center::users.role_help');
                                ?>
                                <option value="<?php echo e($r->name); ?>" data-desc="<?php echo e($desc); ?>" <?php echo e(old('role', $user->role) == $r->name ? 'selected' : ''); ?>>
                                    <?php echo e($title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="form-text mt-2 p-2 rounded bg-light border border-info border-start border-4 text-dark fw-bold" id="roleDescription" style="display: none;">
                            <i class="fas fa-info-circle text-info me-2"></i> <span id="roleDescText"></span>
                        </div>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4" id="permissionsSection" style="display: none;">
                        <label class="form-label fw-bold mb-3"><i class="fas fa-shield-alt text-warning me-2"></i><?php echo e(__('center::users.permissions.extra_perms')); ?></label>
                        <div class="row g-3 p-3 bg-light rounded border">
                            <?php
                                $groupedPermissions = $permissions->groupBy(function($perm) {
                                    return explode(' ', $perm->name)[1] ?? 'other';
                                });
                                
                                $translations = __('center::users.permissions');
                                
                                function translatePerm($name, $translations) {
                                    $parts = explode(' ', $name);
                                    $action = $translations[$parts[0]] ?? $parts[0];
                                    $resource = $translations[$parts[1] ?? ''] ?? ($parts[1] ?? '');
                                    return $action . ' ' . $resource;
                                }

                                $userPermissions = $user->permissions->pluck('name')->toArray();
                            ?>

                            <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2 border-bottom">
                                            <span class="fw-bold text-primary small"><?php echo e($translations[$group] ?? ucfirst($group)); ?></span>
                                        </div>
                                        <div class="card-body p-2">
                                            <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-switch mb-1">
                                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>" id="perm_<?php echo e($permission->id); ?>" <?php echo e(in_array($permission->name, old('permissions', $userPermissions)) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label small text-dark" for="perm_<?php echo e($permission->id); ?>">
                                                        <?php echo e(translatePerm($permission->name, $translations)); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="form-text mt-2 text-muted small"><i class="fas fa-info-circle me-1"></i> <?php echo e(__('center::users.permissions.help')); ?></div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="<?php echo e(route('center.users.index')); ?>" class="btn btn-outline-secondary"><?php echo e(__('center::users.cancel')); ?></a>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('center::users.save_changes')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const rolePermissions = <?php echo json_encode($rolePermissions ?? [], 15, 512) ?>;

    function showRoleDescription(selectElement, isInitialLoad = false) {
        const descBox = document.getElementById('roleDescription');
        const descText = document.getElementById('roleDescText');
        const permissionsSection = document.getElementById('permissionsSection');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const roleName = selectedOption.value;
        
        if (roleName) {
            descText.textContent = selectedOption.getAttribute('data-desc');
            descBox.style.display = 'block';
            permissionsSection.style.display = 'block';
            
            // Only update checkboxes automatically if the user manually changes the role
            if (!isInitialLoad) {
                const permissions = rolePermissions[roleName] || [];
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                
                checkboxes.forEach(cb => {
                    cb.checked = permissions.includes(cb.value);
                });
            }
            
        } else {
            descBox.style.display = 'none';
            permissionsSection.style.display = 'none';
        }
    }

    // Trigger on load
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect) {
            showRoleDescription(roleSelect, true);
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\users\edit.blade.php ENDPATH**/ ?>