<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::students.form.add_new_student')); ?></h2>
        <a href="<?php echo e(route('center.students.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4"><?php echo e(__('center::students.form.back_to_list')); ?></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="<?php echo e(route('center.students.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-person me-2"></i><?php echo e(__('center::students.form.basic_info')); ?></h5>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.full_name')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.name_placeholder')); ?>">
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
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.phone_number')); ?> <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.phone_placeholder')); ?>" pattern="[0-9\+\-\s\(\)]*" title="<?php echo e(__('center::students.numbers_only')); ?>">
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
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.email_optional')); ?></label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.email_placeholder')); ?>">
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
                            
                            
                            
                            
                            
                            
                        </div>

                        <hr class="my-4">

                        
                        <div class="row mb-4">
                            <h5 class="text-secondary mb-3"><i class="bi bi-people me-2"></i><?php echo e(__('center::students.form.parent_info')); ?></h5>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.parent_name')); ?></label>
                                <input type="text" name="parent_name" value="<?php echo e(old('parent_name')); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.parent_name_placeholder')); ?>">
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
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.parent_phone')); ?></label>
                                <div class="input-group">
                                    <input type="tel" name="parent_phone" id="parent_phone" value="<?php echo e(old('parent_phone')); ?>" class="form-control form-control-lg bg-light border-0" placeholder="<?php echo e(__('center::students.form.parent_phone_placeholder')); ?>" pattern="[0-9\+\-\s\(\)]*" title="<?php echo e(__('center::students.numbers_only')); ?>">
                                    <span class="input-group-text bg-light border-0 d-none" id="guardian-found-badge">
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
                                <label class="form-label fw-bold"><i class="fas fa-envelope me-1 text-info opacity-50"></i> <?php echo e(__('center::students.parent_email')); ?></label>
                                <input type="email" name="parent_email" value="<?php echo e(old('parent_email')); ?>" class="form-control form-control-lg bg-light border-0" placeholder="parent@email.com">
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
                            
                        </div>

                        <hr class="my-4">

                        
                        <div class="row mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-secondary mb-0"><i class="bi bi-mortarboard me-2"></i><?php echo e(__('center::students.form.academic_stage')); ?></h5>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#gradePickerModal" id="gradePickerTrigger">
                                    <i class="bi bi-grid-3x3-gap me-1"></i> <?php echo e(__('center::students.choose_from_list')); ?>

                                </button>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold"><?php echo e(__('center::students.form.grade_level')); ?> <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select name="grade_id" id="main_grade_select" class="form-select form-select-lg bg-light border-0 shadow-none">
                                        <option value=""><?php echo e(__('center::students.form.choose_grade')); ?></option>
                                        <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <optgroup label="📂 <?php echo e($stage->name); ?>">
                                                <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($grade->id); ?>" <?php echo e(old('grade_id') == $grade->id ? 'selected' : ''); ?> data-stage="<?php echo e($stage->name); ?>"><?php echo e($grade->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div id="selected-grade-chip" class="mt-2 d-none">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-journal-check me-1"></i> <span id="chip-text"></span>
                                        </span>
                                    </div>
                                </div>
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
                        </div>

                        <hr class="my-4">

                        
                        <div class="row mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-secondary mb-0"><i class="bi bi-collection-play me-2"></i><?php echo e(__('center::students.initial_registration_optional')); ?></h5>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold mb-3"><?php echo e(__('center::students.choose_groups_courses')); ?> <span class="text-muted fw-normal">(<?php echo e(__('center::students.choose_more_than_one')); ?>)</span></label>
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
                                        <i class="bi bi-info-circle me-1"></i> <?php echo e(__('center::students.no_available_groups')); ?>

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
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm"><?php echo e(__('center::students.form.save_student')); ?></button>
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
        const nameInputs = document.querySelectorAll('input[name="name"], input[name="parent_name"]');
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
        const addressInput = null; // Removed

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
                                
                                if (!parentNameInput.value) parentNameInput.value = data.guardian.name;
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
        const mainSelect = document.getElementById('main_grade_select');
        const trigger = document.getElementById('gradePickerTrigger');
        const chip = document.getElementById('selected-grade-chip');
        const chipText = document.getElementById('chip-text');

        window.selectGrade = function(id, name, stageName) {
            mainSelect.value = id;
            updateGradeUI(id, name, stageName);
            bootstrap.Modal.getInstance(document.getElementById('gradePickerModal')).hide();
        };

        function updateGradeUI(id, name, stageName) {
            if (id) {
                trigger.classList.remove('pulse-btn', 'btn-outline-primary');
                trigger.classList.add('btn-primary', 'text-white');
                chip.classList.remove('d-none');
                chipText.textContent = `${stageName} - ${name}`;
                
                // Highlight active card in modal
                document.querySelectorAll('.grade-card').forEach(card => {
                    card.classList.toggle('active', card.dataset.gradeId == id);
                });
            } else {
                trigger.classList.add('pulse-btn', 'btn-outline-primary');
                trigger.classList.remove('btn-primary', 'text-white');
                chip.classList.add('d-none');
            }
        }

        mainSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                updateGradeUI(selected.value, selected.text, selected.dataset.stage);
            } else {
                updateGradeUI('', '', '');
            }
        });

        // Initial Check
        if (mainSelect.value) {
            const selected = mainSelect.options[mainSelect.selectedIndex];
            updateGradeUI(mainSelect.value, selected.text, selected.dataset.stage);
        } else {
            updateGradeUI('', '', '');
        }
    });
</script>
<?php $__env->stopSection(); ?>

<!-- Grade Picker Modal -->
<div class="modal fade" id="gradePickerModal" tabindex="-1" aria-labelledby="gradePickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-5">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold" id="gradePickerModalLabel"><?php echo e(__('center::students.choose_from_list')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row g-3">
                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="text-muted fw-bold small text-uppercase letter-spacing-1 border-bottom pb-2">
                                <i class="bi bi-folder2-open me-2"></i><?php echo e($stage->name); ?>

                            </h6>
                        </div>
                        <?php $__currentLoopData = $stage->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-6">
                                <div class="grade-card p-3 rounded-4 border text-center cursor-pointer transition-all hover-shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" 
                                     onclick="selectGrade('<?php echo e($grade->id); ?>', '<?php echo e($grade->name); ?>', '<?php echo e($stage->name); ?>')"
                                     data-grade-id="<?php echo e($grade->id); ?>">
                                    <div class="grade-icon mb-2 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-book text-primary fs-5"></i>
                                    </div>
                                    <span class="fw-bold small"><?php echo e($grade->name); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
<style>
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow-sm:hover, .custom-checkbox-card:hover { 
        transform: translateY(-3px);
        border-color: #3a0ca3 !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
        background-color: rgba(58, 12, 163, 0.02) !important;
    }
    .custom-checkbox-card:has(input:checked) {
        border: 2px solid #3a0ca3 !important;
        background-color: rgba(58, 12, 163, 0.05) !important;
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
    }
    .grade-card.active {
        border-color: #3a0ca3 !important;
        background-color: rgba(58, 12, 163, 0.05);
    }
    .grade-card.active .grade-icon {
        background-color: #3a0ca3 !important;
    }
    .grade-card.active i {
        color: white !important;
    }
    
    #gradePickerTrigger.pulse-btn {
        animation: pulse-primary 2s infinite;
    }
    
    @keyframes pulse-primary {
        0% { box-shadow: 0 0 0 0 rgba(58, 12, 163, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(58, 12, 163, 0); }
        100% { box-shadow: 0 0 0 0 rgba(58, 12, 163, 0); }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\students\create.blade.php ENDPATH**/ ?>