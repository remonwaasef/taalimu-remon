<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::instructors.show')); ?></h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('center.instructors.edit', $instructor->id)); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-edit me-2"></i> <?php echo e(__('center::instructors.edit')); ?>

            </a>
            <a href="<?php echo e(route('center.instructors.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <?php echo e(__('center::messages.back')); ?>

            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar: Basic Info & Profile Pic -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body text-center p-5">
                    <div class="position-relative d-inline-block mb-4">
                        <?php if($instructor->image): ?>
                            <img src="<?php echo e(Storage::url($instructor->image)); ?>" class="rounded-circle border border-4 border-white shadow" style="width: 150px; height: 150px; object-fit: cover;" alt="<?php echo e($instructor->name); ?>">
                        <?php else: ?>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow" style="width: 150px; height: 150px; font-size: 3rem;">
                                <?php echo e(mb_substr($instructor->name, 0, 1)); ?>

                            </div>
                        <?php endif; ?>
                        <span class="position-absolute bottom-0 end-0 p-2 bg-white rounded-circle shadow-sm">
                            <?php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'on_hold' => 'danger'
                                ];
                                $color = $statusColors[$instructor->status] ?? 'info';
                            ?>
                            <i class="fas fa-circle text-<?php echo e($color); ?>"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1"><?php echo e($instructor->name); ?></h4>
                    <p class="text-muted mb-3"><?php echo e($instructor->specialization ?? '-'); ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge bg-<?php echo e($color); ?> bg-opacity-10 text-<?php echo e($color); ?> rounded-pill px-3 py-2 fw-bold">
                            <?php echo e(__('center::instructors.' . $instructor->status)); ?>

                        </span>
                    </div>

                    <hr class="opacity-10">

                    <div class="text-start mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-primary"><i class="fas fa-envelope fa-fw"></i></div>
                            <div>
                                <small class="text-muted d-block"><?php echo e(__('center::instructors.email')); ?></small>
                                <span class="fw-bold"><?php echo e($instructor->email ?? '-'); ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-success"><i class="fas fa-phone fa-fw"></i></div>
                            <div>
                                <small class="text-muted d-block"><?php echo e(__('center::instructors.phone')); ?></small>
                                <span class="fw-bold"><?php echo e($instructor->phone ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-4"><?php echo e(__('center::instructors.contact_info')); ?></h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <h4 class="fw-bold mb-0 text-primary"><?php echo e($instructor->courses_count); ?></h4>
                                <small class="text-muted d-block mt-1"><?php echo e(__('center::instructors.registered_phone')); ?></small>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 border border-primary border-opacity-10 position-relative overflow-hidden">
                                <h4 class="fw-bold mb-0 text-primary"><?php echo e(format_price($instructor->outstanding_balance)); ?></h4>
                                <small class="text-muted d-block mt-1"><?php echo e(__('center::instructors.available_balance')); ?></small>
                                <?php if($instructor->outstanding_balance > 0): ?>
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill mt-2 w-100" data-bs-toggle="modal" data-bs-target="#payoutModal">
                                        <i class="fas fa-hand-holding-usd me-1"></i> <?php echo e(__('center::instructors.payout')); ?>

                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold mb-0"><?php echo e(__('center::instructors.biography')); ?></h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1"><?php echo e(__('center::instructors.gender')); ?></label>
                            <span class="fw-bold"><?php echo e($instructor->gender ? __('center::instructors.' . $instructor->gender) : '-'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1"><?php echo e(__('center::instructors.hiring_date')); ?></label>
                            <span class="fw-bold text-primary"><?php echo e($instructor->hiring_date ? $instructor->hiring_date->format('Y/m/d') : '-'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1"><?php echo e(__('center::instructors.national_id')); ?></label>
                            <span class="fw-bold"><?php echo e($instructor->national_id ?? '-'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1"><?php echo e(__('center::instructors.commission_rate')); ?></label>
                            <span class="fw-bold text-success"><?php echo e(__('center::instructors.commission_from_sales', ['rate' => $instructor->commission_rate])); ?></span>
                        </div>
                        <div class="col-12">
                            <hr class="opacity-10 my-2">
                            <label class="text-muted small d-block mb-2"><?php echo e(__('center::instructors.bio')); ?></label>
                            <p class="text-dark bg-light p-3 rounded-3 mb-0" style="white-space: pre-line;"><?php echo e($instructor->bio ?? __('center::instructors.no_bio')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Records / History -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><?php echo e(__('center::instructors.course_statistics')); ?></h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 me-2"><?php echo e(format_price($instructor->outstanding_balance)); ?></div>
                        <a href="<?php echo e(route('center.instructors.statement', $instructor->id)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fas fa-file-invoice-dollar me-1"></i> <?php echo e(__('center::instructors.account_statement')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 p-3 h6 small fw-bold"><?php echo e(__('center::instructors.table_date')); ?></th>
                                    <th class="border-0 p-3 h6 small fw-bold"><?php echo e(__('center::instructors.table_student')); ?></th>
                                    <th class="border-0 p-3 h6 small fw-bold text-center"><?php echo e(__('center::instructors.table_rate')); ?></th>
                                    <th class="border-0 p-3 h6 small fw-bold"><?php echo e(__('center::instructors.table_amount')); ?></th>
                                    <th class="border-0 p-3 h6 small fw-bold"><?php echo e(__('center::instructors.table_status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="p-3 small text-muted"><?php echo e($commission->created_at->format('Y-m-d')); ?></td>
                                        <td class="p-3">
                                            <div class="fw-bold"><?php echo e($commission->sale->student->name); ?></div>
                                            <div class="small text-muted">فاتورة #<?php echo e($commission->sale_id); ?></div>
                                        </td>
                                        <td class="p-3 text-center small fw-bold text-primary"><?php echo e($commission->rate); ?>%</td>
                                        <td class="p-3 fw-bold text-success"><?php echo e(format_price($commission->amount)); ?></td>
                                        <td class="p-3">
                                            <?php
                                                $cStatusColors = [
                                                    'earned' => 'success',
                                                    'pending' => 'warning',
                                                    'paid' => 'info'
                                                ];
                                                $cStat = $cStatusColors[$commission->status] ?? 'secondary';
                                            ?>
                                            <span class="badge rounded-pill bg-<?php echo e($cStat); ?> bg-opacity-10 text-<?php echo e($cStat); ?> px-3">
                                                <?php echo e(__('center::instructors.' . $commission->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0"><?php echo e(__('center::instructors.no_financial_records')); ?></p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($commissions->hasPages()): ?>
                    <div class="card-footer bg-white border-0 py-3">
                        <?php echo e($commissions->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>

    <!-- Payout Modal -->
    <div class="modal fade" id="payoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form action="<?php echo e(route('center.instructors.payout', $instructor->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-bold mb-0"><?php echo e(__('center::instructors.register_payout')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-4 text-center p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1"><?php echo e(__('center::instructors.available_for_payout')); ?></small>
                            <h4 class="fw-bold mb-0 text-success"><?php echo e(format_price($instructor->outstanding_balance)); ?></h4>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold"><?php echo e(__('center::instructors.amount_to_payout')); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3"><?php echo e(get_currency_symbol()); ?></span>
                                <input type="number" name="amount" step="0.01" class="form-control border-start-0 rounded-end-3" 
                                    max="<?php echo e($instructor->outstanding_balance); ?>" min="1" value="<?php echo e($instructor->outstanding_balance); ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold"><?php echo e(__('center::instructors.payment_method')); ?></label>
                                <select name="payment_method" class="form-select rounded-3" required>
                                    <option value="cash"><?php echo e(__('center::instructors.cash')); ?></option>
                                    <option value="bank_transfer"><?php echo e(__('center::instructors.bank_transfer')); ?></option>
                                    <option value="online"><?php echo e(__('center::instructors.online')); ?></option>
                                    <option value="other"><?php echo e(__('center::instructors.other')); ?></option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold"><?php echo e(__('center::instructors.table_date')); ?></label>
                                <input type="date" name="payout_date" class="form-control rounded-3" value="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold"><?php echo e(__('center::instructors.notes')); ?></label>
                            <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="<?php echo e(__('center::instructors.optional')); ?>"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal"><?php echo e(__('center::instructors.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><?php echo e(__('center::instructors.confirm_payout')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\instructors\show.blade.php ENDPATH**/ ?>