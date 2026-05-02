<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::instructors.add_new')); ?></h2>
        <a href="<?php echo e(route('center.instructors.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4"><?php echo e(__('center::instructors.back_to_list')); ?></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="<?php echo e(route('center.instructors.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Personal Info -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.name')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control bg-white border" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.specialization')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="specialization" value="<?php echo e(old('specialization')); ?>" class="form-control bg-white border" placeholder="<?php echo e(__('center::instructors.specialization_placeholder')); ?>" required>
                                <?php $__errorArgs = ['specialization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Status & Administrative -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.status')); ?> <span class="text-danger">*</span></label>
                                <select name="status" class="form-select bg-white border" required>
                                    <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.active')); ?></option>
                                    <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.inactive')); ?></option>
                                    <option value="on_hold" <?php echo e(old('status') == 'on_hold' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.on_hold')); ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.gender')); ?></label>
                                <select name="gender" class="form-select bg-white border">
                                    <option value=""><?php echo e(__('center::instructors.select_placeholder')); ?></option>
                                    <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.male')); ?></option>
                                    <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.female')); ?></option>
                                </select>
                                <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.hiring_date')); ?></label>
                                <input type="date" name="hiring_date" value="<?php echo e(old('hiring_date')); ?>" class="form-control bg-white border">
                                <?php $__errorArgs = ['hiring_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Identifiers & Finance -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.commission_rate')); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="commission_type" class="form-select bg-white border" style="max-width: 140px; border-radius: 0 10px 10px 0 !important;" required>
                                        <option value=""><?php echo e(__('center::instructors.select_placeholder')); ?></option>
                                        <option value="percentage" <?php echo e(old('commission_type') == 'percentage' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.commission_percentage')); ?></option>
                                        <option value="fixed" <?php echo e(old('commission_type') == 'fixed' ? 'selected' : ''); ?>><?php echo e(__('center::instructors.commission_fixed')); ?></option>
                                    </select>
                                    <input type="number" step="0.01" name="commission_rate" value="<?php echo e(old('commission_rate', 0)); ?>" class="form-control bg-white border" placeholder="0.00" style="border-radius: 10px 0 0 10px !important;" required>
                                </div>
                                <?php $__errorArgs = ['commission_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php $__errorArgs = ['commission_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.email')); ?></label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control bg-white border">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('center::instructors.phone')); ?> <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control bg-white border" required>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::instructors.bio')); ?></label>
                            <textarea name="bio" class="form-control bg-white border" rows="3"><?php echo e(old('bio')); ?></textarea>
                            <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>



                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold"><?php echo e(__('center::instructors.save_instructor')); ?></button>
                            <a href="<?php echo e(route('center.instructors.index')); ?>" class="btn btn-light rounded-pill py-3"><?php echo e(__('center::instructors.cancel')); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        function showWarning(input, msg) {
            let existing = input.parentNode.querySelector('.custom-validation-msg');
            if (existing) existing.remove();

            let warning = document.createElement('div');
            warning.className = 'custom-validation-msg text-danger small mt-1 fw-bold';
            warning.style.transition = 'opacity 0.5s';
            warning.innerHTML = '<i class="bi bi-exclamation-triangle ms-1"></i> ' + msg;
            input.parentNode.appendChild(warning);

            setTimeout(() => {
                warning.style.opacity = '0';
                setTimeout(() => warning.remove(), 500);
            }, 2000);
        }

        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[^0-9+\s\-()]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "<?php echo e(__('center::instructors.validation_numbers_only')); ?>");
                }
            });
        });

        // Name: Letters only
        const nameInput = document.querySelector('input[name="name"]');
        if(nameInput) {
            nameInput.addEventListener('input', function() {
                let original = this.value;
                // Looser: allow letters, spaces, and dots (for titles like Dr.)
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "<?php echo e(__('center::instructors.validation_letters_only')); ?>");
                }
            });
        }

        // Specialization: Letters, dots, dashes
        const specInput = document.querySelector('input[name="specialization"]');
        if(specInput) {
            specInput.addEventListener('input', function() {
                let original = this.value;
                // Allow letters, spaces, dots, dashes, and parentheses.
                let clean = original.replace(/[0-9!@#$%^&*()_+\=\[\]{};':"\\|,<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "<?php echo e(__('center::instructors.validation_specialization')); ?>");
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\instructors\create.blade.php ENDPATH**/ ?>