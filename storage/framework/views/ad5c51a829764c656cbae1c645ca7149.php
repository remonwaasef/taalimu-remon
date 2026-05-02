<?php $__env->startSection('page-title', __('instructor::groups.group_details_edit')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0"><?php echo e(__('instructor::groups.group_details_edit')); ?>: <?php echo e($course->title); ?></h4>
                    <p class="text-muted small"><?php echo e(__('instructor::groups.subtitle')); ?></p>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('instructor.groups.update', $course->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::groups.group_name')); ?></label>
                                <input type="text" name="title" class="form-control rounded-3 py-2 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title', $course->title)); ?>" required placeholder="<?php echo e(__('instructor::groups.group_name_input_placeholder')); ?>">
                                <?php $__errorArgs = ['title'];
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
                                <label class="form-label fw-bold"><?php echo e(__('instructor::groups.group_description')); ?></label>
                                <textarea name="description" class="form-control rounded-3 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" placeholder="<?php echo e(__('instructor::groups.group_description_placeholder')); ?>"><?php echo e(old('description', $course->description)); ?></textarea>
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

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::groups.group_price_label')); ?></label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control rounded-start-3 py-2 <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('price', $course->price)); ?>" required step="0.01" min="0" placeholder="0.00">
                                    <span class="input-group-text rounded-end-3 bg-light border-start-0"><?php echo e(app('tenant')->settings['currency'] ?? 'EGP'); ?></span>
                                </div>
                                <?php $__errorArgs = ['price'];
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

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::groups.sessions_count')); ?></label>
                                <input type="number" name="sessions_count" class="form-control rounded-3 py-2 <?php $__errorArgs = ['sessions_count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('sessions_count', $course->sessions_count)); ?>" required min="1" placeholder="<?php echo e(__('instructor::groups.sessions_placeholder')); ?>">
                                <?php $__errorArgs = ['sessions_count'];
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

                             <div class="col-12 mt-5">
                                <div class="d-flex gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm border-0" style="background: var(--primary-color);">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('instructor::groups.update_group_btn')); ?>

                                    </button>
                                    <a href="<?php echo e(route('instructor.groups.list')); ?>" class="btn btn-light rounded-pill px-4 py-2 text-muted fw-bold"><?php echo e(__('instructor::groups.back')); ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\groups\edit.blade.php ENDPATH**/ ?>