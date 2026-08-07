                        <div class="tab-pane fade <?php echo e($activeTab == 'privacy' ? 'show active' : ''); ?>" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                            <div class="alert alert-warning border-0 rounded-4 mb-4">
                                <h6 class="fw-bold"><i class="fas fa-shield-alt me-2"></i><?php echo e(__('center::settings.privacy.title')); ?></h6>
                                <p class="small mb-0 mt-1">
                                    <?php echo e(__('center::settings.privacy.desc')); ?>

                                </p>
                            </div>

                            <!-- Data Export -->
                            <div class="card border bg-light shadow-none mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1"><?php echo e(__('center::settings.privacy.export_title')); ?></h6>
                                            <p class="text-muted small mb-0"><?php echo e(__('center::settings.privacy.export_desc')); ?></p>
                                        </div>
                                        <a href="<?php echo e(tenant_route('gdpr.export', ['tenant' => $tenant->domain ?? 'center'])); ?>" class="btn btn-outline-primary rounded-pill px-4">
                                            <i class="fas fa-download me-2"></i> <?php echo e(__('center::settings.privacy.export_btn')); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Account -->
                            <div class="card border border-danger bg-danger bg-opacity-10 shadow-none">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-danger mb-2"><?php echo e(__('center::settings.privacy.delete_title')); ?></h6>
                                    <p class="text-secondary small mb-3">
                                        <?php echo e(__('center::settings.privacy.delete_desc')); ?>

                                    </p>
                                    
                                    <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2"></i> <?php echo e(__('center::settings.privacy.delete_btn')); ?>

                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal moved to root for stability -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-danger"><?php echo e(__('center::settings.privacy.confirm_delete_title')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(tenant_route('gdpr.delete', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="mb-3 text-muted"><?php echo e(__('center::settings.privacy.confirm_delete_desc')); ?></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.privacy.current_password')); ?></label>
                        <input type="password" name="password" class="form-control bg-light border-0" required placeholder="********">
                    </div>

                    <div class="form-check custom-check p-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="confirm_delete" id="confirmDelete" required>
                        <label class="form-check-label small user-select-none text-danger fw-bold" for="confirmDelete">
                            <?php echo e(__('center::settings.privacy.understand_checkbox')); ?>

                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('center::settings.privacy.cancel')); ?></button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4"><?php echo e(__('center::settings.privacy.delete_perm')); ?></button>
                </div>
            </form>
        </div>
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/partials/_tab-privacy.blade.php ENDPATH**/ ?>