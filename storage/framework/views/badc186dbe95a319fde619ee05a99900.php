                        <div class="tab-pane fade <?php echo e($activeTab == 'financial' ? 'show active' : ''); ?>" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <h6 class="fw-bold text-primary mb-3"><?php echo e(__('center::settings.financial.title')); ?></h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.financial.currency')); ?></label>
                                    <select name="settings[financial][currency]" class="form-select">
                                        <option value="EGP" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'EGP' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.egp')); ?></option>
                                        <option value="SAR" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'SAR' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.sar')); ?></option>
                                        <option value="USD" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'USD' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.usd')); ?></option>
                                        <option value="EUR" <?php echo e(($tenant->settings['financial']['currency'] ?? '') == 'EUR' ? 'selected' : ''); ?>><?php echo e(__('center::settings.financial.currencies.eur')); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.financial.tax_rate')); ?></label>
                                    <input type="number" name="settings[financial][tax_rate]" class="form-control" value="<?php echo e($tenant->settings['financial']['tax_rate'] ?? '0'); ?>" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.financial.invoice_prefix')); ?></label>
                                    <input type="text" name="settings[financial][invoice_prefix]" class="form-control" value="<?php echo e($tenant->settings['financial']['invoice_prefix'] ?? 'INV-'); ?>" placeholder="INV-">
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/partials/_tab-financial.blade.php ENDPATH**/ ?>