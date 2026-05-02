<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::students.form.edit_student')); ?></h2>
        <a href="<?php echo e(route('center.students.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4"><?php echo e(__('center::students.form.back_to_list')); ?></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="<?php echo e(route('center.students.update', $student->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-person me-2"></i><?php echo e(__('center::students.form.personal_info')); ?></h5>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.full_name')); ?></label>
                                <input type="text" name="name" value="<?php echo e(old('name', $student->name)); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['name'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.email')); ?></label>
                                <input type="email" name="email" value="<?php echo e(old('email', $student->email)); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['email'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.student_code')); ?></label>
                                <input type="text" name="code" value="<?php echo e(old('code', $student->code)); ?>" class="form-control form-control-lg bg-light border-0" readonly>
                                <?php $__errorArgs = ['code'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.national_id')); ?></label>
                                <input type="text" name="national_id" value="<?php echo e(old('national_id', $student->national_id)); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['national_id'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.phone_number')); ?></label>
                                <input type="tel" name="phone" value="<?php echo e(old('phone', $student->phone)); ?>" class="form-control form-control-lg bg-light border-0" pattern="[0-9\+\-\s\(\)]*" title="<?php echo e(__('center::students.numbers_only')); ?>">
                                <?php $__errorArgs = ['phone'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.birth_date')); ?></label>
                                <input type="date" name="birth_date" value="<?php echo e(old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '')); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['birth_date'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.gender')); ?></label>
                                <select name="gender" class="form-select form-select-lg bg-light border-0">
                                    <option value=""><?php echo e(__('center::students.form.choose')); ?></option>
                                    <option value="male" <?php echo e(old('gender', $student->gender) == 'male' ? 'selected' : ''); ?>><?php echo e(__('center::students.form.gender_male')); ?></option>
                                    <option value="female" <?php echo e(old('gender', $student->gender) == 'female' ? 'selected' : ''); ?>><?php echo e(__('center::students.form.gender_female')); ?></option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.address')); ?></label>
                                <input type="text" name="address" value="<?php echo e(old('address', $student->address)); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.address_placeholder')); ?>">
                                <?php $__errorArgs = ['address'];
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
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.profile_photo')); ?></label>
                                <?php if($student->profile_photo): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo e(Storage::url($student->profile_photo)); ?>" alt="Profile Photo" class="rounded-circle" width="60" height="60">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="profile_photo" class="form-control bg-light border-0" accept="image/*">
                                <?php $__errorArgs = ['profile_photo'];
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
                        </div>

                        <hr class="my-4">

                        
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-people me-2"></i><?php echo e(__('center::students.form.parent_info')); ?></h5>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.parent_name')); ?></label>
                                <input type="text" name="parent_name" value="<?php echo e(old('parent_name', $student->parent_name)); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['parent_name'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.relation')); ?></label>
                                <input type="text" name="parent_relation" value="<?php echo e(old('parent_relation', $student->parent_relation)); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.relation_placeholder')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.parent_phone')); ?></label>
                                <div class="input-group">
                                    <input type="tel" name="parent_phone" id="parent_phone" value="<?php echo e(old('parent_phone', $student->parent_phone)); ?>" class="form-control form-control-lg bg-light border-0" pattern="[0-9\+\-\s\(\)]*" title="<?php echo e(__('center::students.numbers_only')); ?>">
                                    <span class="input-group-text bg-light border-0 <?php echo e($student->guardian_id ? '' : 'd-none'); ?>" id="guardian-found-badge">
                                        <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle-fill"></i> <?php echo e(__('center::students.form.guardian_found')); ?></span>
                                    </span>
                                </div>
                                <div id="guardian-info-alert" class="alert alert-success border-0 rounded-4 small mt-2 d-none">
                                    <i class="bi bi-info-circle-fill me-1"></i> <?php echo e(__('center::students.form.guardian_recognized', ['name' => '<span id="found-guardian-name"></span>'])); ?></div>
                                <?php $__errorArgs = ['parent_phone'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.emergency_phone')); ?></label>
                                <input type="tel" name="emergency_phone" value="<?php echo e(old('emergency_phone', $student->emergency_phone)); ?>" class="form-control form-control-lg bg-light border-0" pattern="[0-9\+\-\s\(\)]*" title="<?php echo e(__('center::students.numbers_only')); ?>">
                                <?php $__errorArgs = ['emergency_phone'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-envelope me-1 text-info opacity-50"></i> <?php echo e(__('center::students.parent_email')); ?></label>
                                <input type="email" name="parent_email" value="<?php echo e(old('parent_email', $student->parent_email)); ?>" class="form-control form-control-lg bg-light border-0" placeholder="example@email.com">
                                <?php $__errorArgs = ['parent_email'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.parent_job')); ?></label>
                                <input type="text" name="parent_job" value="<?php echo e(old('parent_job', $student->parent_job)); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['parent_job'];
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
                        </div>

                        <hr class="my-4">

                        
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-mortarboard me-2"></i><?php echo e(__('center::students.form.academic_stage')); ?></h5>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.grade_level')); ?></label>
                                <select name="grade_id" class="form-select form-select-lg bg-light border-0">
                                    <option value=""><?php echo e(__('center::students.form.choose_grade')); ?></option>
                                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <optgroup label="📂 <?php echo e($stage->name); ?>">
                                            <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($grade->id); ?>" <?php echo e(old('grade_id', $student->grade_id) == $grade->id ? 'selected' : ''); ?>><?php echo e($grade->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['grade_id'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.school')); ?></label>
                                <input type="text" name="school_name" value="<?php echo e(old('school_name', $student->school_name)); ?>" class="form-control form-control-lg bg-light border-0">
                                <?php $__errorArgs = ['school_name'];
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.section_type_edit_label')); ?></label>
                                <input type="text" name="section_type" value="<?php echo e(old('section_type', $student->section_type)); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.section_placeholder')); ?>">
                                <?php $__errorArgs = ['section_type'];
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
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm"><?php echo e(__('center::students.form.update_student')); ?></button>
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
            // Remove existing warning if any
            let existing = input.parentNode.querySelector('.custom-validation-msg');
            if (existing) existing.remove();

            // Create new warning
            let warning = document.createElement('div');
            warning.className = 'custom-validation-msg text-danger small mt-1 fw-bold';
            warning.style.transition = 'opacity 0.5s';
            warning.innerHTML = '<i class="bi bi-exclamation-triangle ms-1"></i> ' + msg;
            input.parentNode.appendChild(warning);

            // Fade out and remove
            setTimeout(() => {
                warning.style.opacity = '0';
                setTimeout(() => warning.remove(), 500);
            }, 2000);
        }

        // 1. Phone numbers: allow only digits and controls
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                let clean = original.replace(/[^0-9+\s\-()]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "<?php echo e(__('center::students.numbers_only')); ?>");
                }
            });
        });

        // 2. Names: allow only letters and spaces (Arabic & English)
        const nameInputs = document.querySelectorAll('input[name="name"], input[name="parent_name"], input[name="parent_job"], input[name="parent_relation"], input[name="section_type"]');
        nameInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let original = this.value;
                // Remove digits and special symbols
                let clean = original.replace(/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g, '');
                
                if (original !== clean) {
                    this.value = clean;
                    showWarning(this, "<?php echo e(__('center::students.letters_only')); ?>");
                }
            });
        });

        // 3. Guardian Lookup by Phone
        const parentPhoneInput = document.getElementById('parent_phone');
        const badge = document.getElementById('guardian-found-badge');
        const alertBox = document.getElementById('guardian-info-alert');
        const nameSpan = document.getElementById('found-guardian-name');
        
        const parentNameInput = document.querySelector('input[name="parent_name"]');
        const parentJobInput = document.querySelector('input[name="parent_job"]');
        const addressInput = document.querySelector('input[name="address"]');

        let timeout = null;
        parentPhoneInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const phone = this.value.trim();
            
            if (phone.length >= 8) {
                timeout = setTimeout(() => {
                    fetch(`<?php echo e(route('center.guardians.lookup')); ?>?phone=${phone}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.found) {
                                badge.classList.remove('d-none');
                                alertBox.classList.remove('d-none');
                                nameSpan.textContent = data.guardian.name;
                                
                                // Auto-fill if empty
                                if (!parentNameInput.value) parentNameInput.value = data.guardian.name;
                                if (!parentJobInput.value) parentJobInput.value = data.guardian.job || '';
                                if (!addressInput.value) addressInput.value = data.guardian.address || '';
                            } else {
                                badge.classList.add('d-none');
                                alertBox.classList.add('d-none');
                            }
                        })
                        .catch(err => console.error('Error looking up guardian:', err));
                }, 500);
            } else {
                badge.classList.add('d-none');
                alertBox.classList.add('d-none');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\students\edit.blade.php ENDPATH**/ ?>