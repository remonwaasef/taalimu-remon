

<?php $__env->startSection('title', __('center::roles.admins_and_roles')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-user-shield me-2"></i> <?php echo e(__('center::roles.roles_permissions')); ?></h5>
                    <a href="<?php echo e(route('center.roles.create', ['tenant' => $tenant->domain])); ?>" class="btn btn-primary btn-sm fw-bold">
                        <i class="fas fa-plus me-1"></i> <?php echo e(__('center::roles.create_new_custom_role')); ?>

                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-bottom-0 text-uppercase small text-muted font-monospace"><?php echo e(__('center::roles.role_name')); ?></th>
                                    <th class="border-bottom-0 text-uppercase small text-muted font-monospace"><?php echo e(__('center::roles.type')); ?></th>
                                    <th class="border-bottom-0 text-uppercase small text-muted font-monospace"><?php echo e(__('center::roles.users_count')); ?></th>
                                    <th class="text-end pe-4 border-bottom-0 text-uppercase small text-muted font-monospace"><?php echo e(__('center::roles.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 <?php echo e(is_null($role->tenant_id) ? 'bg-warning bg-opacity-10 text-warning' : 'bg-primary bg-opacity-10 text-primary'); ?>" style="width: 40px; height: 40px;">
                                                    <i class="fas <?php echo e(is_null($role->tenant_id) ? 'fa-lock' : 'fa-user-tag'); ?>"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark"><?php echo e($role->name); ?></h6>
                                                    <?php if(is_null($role->tenant_id)): ?>
                                                        <small class="text-muted" style="font-size: 0.75rem;"><?php echo e(__('center::roles.system_managed')); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if(is_null($role->tenant_id)): ?>
                                                <span class="badge rounded-pill bg-light text-dark border"><i class="fas fa-shield-alt me-1 text-warning"></i> <?php echo e(__('center::roles.system_role')); ?></span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10"><i class="fas fa-pen-fancy me-1"></i> <?php echo e(__('center::roles.custom_role')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            
                                            <span class="badge bg-light text-secondary border"><?php echo e($role->users_count ?? 0); ?> <?php echo e(__('center::roles.users')); ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                
                                                <a href="<?php echo e(route('center.roles.edit', ['role' => $role->id, 'tenant' => $tenant->domain])); ?>" 
                                                   class="btn btn-sm btn-light border" 
                                                   title="<?php echo e(is_null($role->tenant_id) ? __('center::roles.view_permissions') : __('center::roles.edit_permissions')); ?>">
                                                    <i class="fas <?php echo e(is_null($role->tenant_id) ? 'fa-eye text-secondary' : 'fa-edit text-primary'); ?>"></i>
                                                </a>

                                                
                                                <?php if(!is_null($role->tenant_id)): ?>
                                                    <button type="button" class="btn btn-sm btn-light border text-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteRoleModal<?php echo e($role->id); ?>"
                                                            title="<?php echo e(__('center::roles.delete')); ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-light border disabled" disabled title="<?php echo e(__('center::roles.cannot_delete_system_role')); ?>">
                                                        <i class="fas fa-lock text-muted"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>

                                            <?php if(!is_null($role->tenant_id)): ?>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteRoleModal<?php echo e($role->id); ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-bottom-0">
                                                                <h5 class="modal-title fw-bold text-danger"><?php echo e(__('center::roles.delete_role')); ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <p><?php echo e(__('center::roles.delete_role_confirm_msg', ['role' => $role->name])); ?></p>
                                                            </div>
                                                            <div class="modal-footer border-top-0">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('center::roles.cancel')); ?></button>
                                                                <form action="<?php echo e(route('center.roles.destroy', ['role' => $role->id, 'tenant' => $tenant->domain])); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-danger"><?php echo e(__('center::roles.confirm_delete')); ?></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-3 p-3 rounded-circle bg-light text-secondary">
                                                    <i class="fas fa-shield-alt fa-2x opacity-50"></i>
                                                </div>
                                                <h6 class="fw-bold"><?php echo e(__('center::roles.no_roles_found')); ?></h6>
                                                <p class="small text-muted mb-0"><?php echo e(__('center::roles.start_by_creating_role')); ?></p>
                                            </div>
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
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\roles\index.blade.php ENDPATH**/ ?>