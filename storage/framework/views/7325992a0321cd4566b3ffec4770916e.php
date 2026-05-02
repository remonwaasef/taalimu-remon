<?php $__env->startSection('title', __('center::expenses.edit_expense')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('center.expenses.index')); ?>"><?php echo e(__('center::expenses.title')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('center::expenses.edit_expense')); ?></li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark mb-0"><?php echo e(__('center::expenses.edit_expense')); ?>: <?php echo e($expense->category); ?></h2>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?php echo e(route('center.expenses.update', $expense->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small"><?php echo e(__('center::expenses.category')); ?></label>
                            <input type="text" name="category" class="form-control rounded-pill <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('category', $expense->category)); ?>">
                            <?php $__errorArgs = ['category'];
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

                        <div class="col-md-6">
                            <label class="form-label fw-bold small"><?php echo e(__('center::expenses.amount')); ?></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-pill"><?php echo e(get_currency_symbol()); ?></span>
                                <input type="number" step="0.01" name="amount" class="form-control border-start-0 rounded-end-pill <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('amount', $expense->amount)); ?>">
                            </div>
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small"><?php echo e(__('center::expenses.date')); ?></label>
                            <input type="date" name="date" class="form-control rounded-pill <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('date', $expense->date->format('Y-m-d'))); ?>">
                            <?php $__errorArgs = ['date'];
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

                        <div class="col-md-6">
                            <label class="form-label fw-bold small"><?php echo e(__('center::expenses.payment_method')); ?></label>
                            <select name="payment_method" class="form-select rounded-pill <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="cash" <?php echo e(old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : ''); ?>><?php echo e(__('center::expenses.cash')); ?></option>
                                <option value="bank" <?php echo e(old('payment_method', $expense->payment_method) == 'bank' ? 'selected' : ''); ?>><?php echo e(__('center::expenses.bank')); ?></option>
                            </select>
                            <?php $__errorArgs = ['payment_method'];
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

                        <div class="col-12">
                            <label class="form-label fw-bold small"><?php echo e(__('center::expenses.description')); ?></label>
                            <textarea name="description" class="form-control rounded-4 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('description', $expense->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
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

                        <div class="col-12">
                            <label class="form-label fw-bold small"><?php echo e(__('center::expenses.attachment')); ?></label>
                            <?php if($expense->attachment): ?>
                                <div class="mb-3 small d-flex align-items-center">
                                    <i class="fas fa-file-image text-primary me-2"></i>
                                    <span><?php echo e(__('center::expenses.current_attachment')); ?></span>
                                    <a href="<?php echo e(asset('storage/' . $expense->attachment)); ?>" target="_blank" class="ms-1 text-primary"><?php echo e(__('center::expenses.view_file')); ?></a>
                                </div>
                            <?php endif; ?>
                            <div class="upload-box p-4 border-dashed rounded-4 text-center bg-light">
                                <input type="file" name="attachment" id="attachment" class="d-none">
                                <label for="attachment" class="cursor-pointer mb-0 w-100">
                                    <i class="fas fa-sync-alt fa-3x text-primary mb-2"></i>
                                    <p class="mb-0 text-muted"><?php echo e(__('center::expenses.replace_attachment')); ?></p>
                                    <small class="text-muted">JPG, PNG, PDF (Max 2MB)</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <hr class="my-4 opacity-10">
                            <a href="<?php echo e(route('center.expenses.index')); ?>" class="btn btn-light rounded-pill px-4 me-2 border"><?php echo e(__('center::expenses.cancel')); ?></a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5"><?php echo e(__('center::expenses.update_expense')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-box {
        border: 2px dashed #ddd;
        transition: all 0.3s ease;
    }
    .upload-box:hover {
        border-color: #4361ee;
        background-color: #f8f9ff !important;
    }
    .cursor-pointer { cursor: pointer; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\expenses\edit.blade.php ENDPATH**/ ?>