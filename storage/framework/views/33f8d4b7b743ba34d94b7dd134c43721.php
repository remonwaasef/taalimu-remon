                        <div class="tab-pane fade <?php echo e($activeTab == 'appearance' ? 'show active' : ''); ?>" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <h6 class="fw-bold text-primary mb-3"><?php echo e(__('center::settings.appearance.title')); ?></h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.appearance.primary_color')); ?></label>
                                    <input type="color" name="settings[appearance][primary_color]" class="form-control form-control-color w-100" value="<?php echo e($tenant->settings['appearance']['primary_color'] ?? '#10b981'); ?>">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input type="hidden" name="settings[appearance][dark_mode]" value="0">
                                        <input class="form-check-input" type="checkbox" name="settings[appearance][dark_mode]" value="1" id="darkMode" <?php echo e(($tenant->settings['appearance']['dark_mode'] ?? false) ? 'checked' : ''); ?>>
                                        <label class="form-check-label user-select-none" for="darkMode"><?php echo e(__('center::settings.appearance.dark_mode')); ?></label>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/partials/_tab-appearance.blade.php ENDPATH**/ ?>