<?php $__env->startSection('page-title', __('instructor::students.create_title')); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0"><?php echo e(__('instructor::students.create_student_data')); ?></h4>
                    <p class="text-muted small"><?php echo e(__('instructor::students.create_student_hint')); ?></p>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('instructor.students.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-12">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::students.student')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-user" style="color: var(--bs-primary);"></i></span>
                                    <input type="text" name="name" class="form-control bg-white focus-ring-primary" placeholder="<?php echo e(__('instructor::students.name_placeholder')); ?>" required value="<?php echo e(old('name')); ?>">
                                </div>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::students.phone')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-phone" style="color: var(--bs-primary);"></i></span>
                                    <input type="tel" name="phone" id="phone_input" class="form-control bg-white focus-ring-primary" placeholder="01XXXXXXXXX" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="<?php echo e(__('instructor::students.phone_length_error')); ?>" value="<?php echo e(old('phone')); ?>">
                                </div>
                                <div id="phone-feedback" class="mt-1 small"></div>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted mt-1 d-block"><?php echo e(__('instructor::students.phone_hint')); ?></small>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::students.email')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope" style="color: var(--bs-primary);"></i></span>
                                    <input type="email" name="email" class="form-control bg-white focus-ring-primary" placeholder="example@mail.com" value="<?php echo e(old('email')); ?>">
                                </div>
                            </div>

                            <!-- Parent Phone -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::students.parent_phone')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-users" style="color: var(--bs-primary);"></i></span>
                                    <input type="tel" name="parent_phone" class="form-control bg-white focus-ring-primary" placeholder="01XXXXXXXXX" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="<?php echo e(__('instructor::students.phone_length_error')); ?>" value="<?php echo e(old('parent_phone')); ?>">
                                </div>
                                <?php $__errorArgs = ['parent_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Parent Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::students.parent_email')); ?></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope" style="color: var(--bs-primary);"></i></span>
                                    <input type="email" name="parent_email" class="form-control bg-white focus-ring-primary" placeholder="parent@mail.com" value="<?php echo e(old('parent_email')); ?>">
                                </div>
                            </div>

                            <!-- Course Selection -->
                            <div class="col-12">
                                <label class="form-label fw-bold mb-3"><?php echo e(__('instructor::students.target_group')); ?> <span class="text-muted fw-normal">(<?php echo e(__('instructor::students.select_multiple_hint') ?? 'يمكنك اختيار أكثر من واحدة'); ?>)</span></label>
                                <?php if($courses->count() > 0): ?>
                                    <div class="row g-3">
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="form-check custom-checkbox-card bg-light border-0 rounded-4 p-3 h-100 d-flex align-items-center transition-all cursor-pointer" onclick="document.getElementById('course_<?php echo e($course->id); ?>').click();">
                                                    <input class="form-check-input ms-0 me-3" style="transform: scale(1.3);" type="checkbox" name="course_ids[]" value="<?php echo e($course->id); ?>" id="course_<?php echo e($course->id); ?>" <?php echo e((is_array(old('course_ids')) && in_array($course->id, old('course_ids'))) ? 'checked' : ''); ?> onclick="event.stopPropagation();">
                                                    <label class="form-check-label w-100 cursor-pointer fw-bold text-dark m-0" for="course_<?php echo e($course->id); ?>" onclick="event.stopPropagation();">
                                                        <?php echo e($course->title); ?>

                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-light border-0 rounded-4 small text-muted">
                                        <i class="fas fa-info-circle me-1"></i> لا توجد مجموعات أو دورات متاحة حالياً.
                                    </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['course_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Actions -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold border-0 shadow-sm">
                                    <i class="fas fa-user-plus me-2"></i> <?php echo e(__('instructor::students.save_and_register')); ?>

                                </button>
                                <a href="<?php echo e(route('instructor.students.list')); ?>" class="btn btn-light w-100 rounded-pill py-3 mt-2 text-muted fw-bold border-0">
                                    <?php echo e(__('instructor::students.back')); ?>

                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone_input');
    const feedback = document.getElementById('phone-feedback');

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            const phone = this.value;
            if (phone.length === 11) {
                feedback.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> <?php echo e(__('instructor::students.checking')); ?>';
                feedback.className = 'mt-1 small text-primary';

                fetch(`<?php echo e(route('instructor.students.check-phone')); ?>?phone=${phone}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'exists') {
                            const studentName = data.name ? data.name : '';
                            feedback.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> <?php echo e(__('instructor::students.already_registered', ['name' => '${studentName}'])); ?>`;
                            feedback.className = 'mt-1 small text-danger fw-bold';
                        } else if (data.status === 'available') {
                            feedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> <?php echo e(__('instructor::students.phone_available')); ?>';
                            feedback.className = 'mt-1 small text-success fw-bold';
                        }
                    });
            } else {
                feedback.innerHTML = '';
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .focus-ring-primary:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(58, 12, 163, 0.1) !important;
        background-color: white !important;
    }

    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.3s ease; }
    .custom-checkbox-card {
        border: 1px solid transparent !important;
    }
    .custom-checkbox-card:hover { 
        transform: translateY(-3px);
        border-color: var(--primary-color) !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
        background-color: rgba(58, 12, 163, 0.02) !important;
    }
    .custom-checkbox-card:has(input:checked) {
        border: 2px solid var(--primary-color) !important;
        background-color: rgba(58, 12, 163, 0.05) !important;
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
    }
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\students\create.blade.php ENDPATH**/ ?>