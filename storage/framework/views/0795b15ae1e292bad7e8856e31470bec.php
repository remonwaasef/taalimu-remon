<?php $__env->startSection('page-title', isset($schedule) ? __('instructor::schedules.edit_title') : __('instructor::schedules.create_title')); ?>
<?php $__env->startSection('page-subtitle', __('instructor::schedules.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('instructor.schedules.index')); ?>" class="btn btn-glass">
        <i class="fas fa-arrow-left me-1"></i> <?php echo e(__('instructor::sidebar.back') ?? __('instructor::groups.back')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <?php if($errors->has('conflict')): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo e($errors->first('conflict')); ?>

            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-calendar-plus fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="color: var(--primary-color);"><?php echo e(isset($schedule) ? __('instructor::schedules.update_schedule') : __('instructor::schedules.add_schedule')); ?></h4>
                        <p class="text-muted small mb-0"><?php echo e(__('instructor::schedules.subtitle')); ?></p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo e(isset($schedule) ? route('instructor.schedules.update', $schedule) : route('instructor.schedules.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($schedule)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.group')); ?> <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select rounded-pill px-3 <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="" disabled selected><?php echo e(__('instructor::schedules.select_group')); ?></option>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id', $schedule->course_id ?? request()->course_id) == $course->id ? 'selected' : ''); ?>>
                                        <?php echo e($course->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0"><?php echo e(__('instructor::schedules.hall')); ?> <span class="text-muted small">(<?php echo e(__('instructor::online_classes.optional')); ?>)</span></label>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#addClassroomModal">
                                    <i class="fas fa-plus-circle me-1"></i><?php echo e(__('instructor::schedules.add_hall') ?? 'إضافة قاعة'); ?>

                                </button>
                            </div>
                            <select name="classroom_id" id="classroom_id" class="form-select rounded-pill px-3 <?php $__errorArgs = ['classroom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="" selected><?php echo e(__('instructor::schedules.select_hall')); ?></option>
                                <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($classroom->id); ?>" <?php echo e(old('classroom_id', $schedule->classroom_id ?? '') == $classroom->id ? 'selected' : ''); ?>>
                                        <?php echo e($classroom->name); ?> (<?php echo e(__('instructor::schedules.capacity')); ?>: <?php echo e($classroom->capacity ?? '∞'); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['classroom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.location')); ?> <span class="text-muted small">(<?php echo e(__('instructor::online_classes.optional')); ?>)</span></label>
                            <input type="text" name="location" class="form-control rounded-pill px-3 <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('location', $schedule->location ?? '')); ?>" placeholder="<?php echo e(__('instructor::schedules.location_placeholder')); ?>">
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <input type="hidden" name="instructor_id" value="<?php echo e(auth()->user()->instructor->id ?? ''); ?>">

                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.day')); ?> <span class="text-danger">*</span></label>
                            <select name="day_of_week" class="form-select rounded-pill px-3 <?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="" disabled selected><?php echo e(__('instructor::schedules.select_day')); ?></option>
                                <?php $__currentLoopData = __('instructor::schedules.days'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(old('day_of_week', $schedule->day_of_week ?? '') == $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.start_time')); ?> <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control rounded-pill px-3 <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_time', $schedule->start_time ?? '')); ?>" required>
                            <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.end_time')); ?> <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control rounded-pill px-3 <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_time', $schedule->end_time ?? '')); ?>" required>
                            <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold"><?php echo e(__('instructor::groups.max_students')); ?> <span class="text-muted small">(<?php echo e(__('instructor::online_classes.optional')); ?>)</span></label>
                            <input type="number" name="max_students" class="form-control rounded-pill px-3 <?php $__errorArgs = ['max_students'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('max_students', $schedule->max_students ?? '')); ?>" placeholder="30">
                            <?php $__errorArgs = ['max_students'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-flex justify-content-end gap-2 mt-4 pb-2">
                        <a href="<?php echo e(route('instructor.schedules.index')); ?>" class="btn btn-light rounded-pill px-4 fw-bold text-muted"><?php echo e(__('instructor::sidebar.cancel')); ?></a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold text-white shadow-sm border-0" style="background: var(--primary-color);">
                            <i class="fas fa-save me-1"></i> <?php echo e(__('instructor::schedules.save_schedule')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header border-0 pb-0 pt-4 bg-white">
                <h5 class="fw-bold mb-0" style="color: var(--primary-color);">
                    <i class="fas fa-info-circle me-2"></i> <?php echo e(__('instructor::schedules.notes')); ?>

                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3 p-3 rounded-4 bg-light bg-opacity-50">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                            <i class="fas fa-check small"></i>
                        </div>
                        <p class="mb-0 small text-muted"><?php echo e(__('instructor::schedules.notes_conflict')); ?></p>
                    </div>
                    
                    <div class="d-flex align-items-start gap-3 p-3 rounded-4 bg-light bg-opacity-50">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                            <i class="fas fa-user-clock small"></i>
                        </div>
                        <p class="mb-0 small text-muted"><?php echo e(__('instructor::schedules.notes_vacancy')); ?></p>
                    </div>

                    <div class="d-flex align-items-start gap-3 p-3 rounded-4 bg-light bg-opacity-50">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                            <i class="fas fa-sync-alt small"></i>
                        </div>
                        <p class="mb-0 small text-muted"><?php echo e(__('instructor::schedules.notes_link')); ?></p>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-4 text-white position-relative overflow-hidden" style="background: var(--primary-gradient);">
                    <i class="fas fa-calendar-alt position-absolute end-0 bottom-0 mb-n4 me-n2 opacity-25" style="font-size: 6rem;"></i>
                    <h6 class="fw-bold mb-2"><?php echo e(__('instructor::sidebar.instructor')); ?></h6>
                    <p class="mb-0 small opacity-75"><?php echo e(__('instructor::sidebar.panel_title')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('modals'); ?>
<div class="modal fade" id="addClassroomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><?php echo e(__('instructor::schedules.add_hall') ?? 'إضافة قاعة جديدة'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.hall_name') ?? 'اسم القاعة'); ?></label>
                    <input type="text" id="new_classroom_name" class="form-control rounded-pill" placeholder="مثلاً: قاعة 101">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><?php echo e(__('instructor::schedules.capacity') ?? 'السعة'); ?></label>
                    <input type="number" id="new_classroom_capacity" class="form-control rounded-pill" placeholder="30">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" id="saveClassroomBtn" class="btn btn-primary w-100 rounded-pill border-0" style="background: var(--primary-color);"><?php echo e(__('instructor::sidebar.save') ?? 'حفظ'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveClassroomBtn');
    const classroomSelect = document.getElementById('classroom_id');
    const modal = new bootstrap.Modal(document.getElementById('addClassroomModal'));

    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const name = document.getElementById('new_classroom_name').value;
            const capacity = document.getElementById('new_classroom_capacity').value;

            if (!name) {
                alert('يرجى إدخال اسم القاعة');
                return;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جارٍ الحفظ...';

            fetch('<?php echo e(route('instructor.classrooms.store')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name, capacity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update select options
                    classroomSelect.innerHTML = '<option value=""><?php echo e(__('instructor::schedules.select_hall')); ?></option>';
                    data.classrooms.forEach(c => {
                        const option = document.createElement('option');
                        option.value = c.id;
                        option.textContent = `${c.name} (${c.capacity || '∞'})`;
                        if (c.id == data.new_id) option.selected = true;
                        classroomSelect.appendChild(option);
                    });
                    
                    modal.hide();
                    document.getElementById('new_classroom_name').value = '';
                    document.getElementById('new_classroom_capacity').value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ القاعة');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<?php echo e(__('instructor::sidebar.save') ?? 'حفظ'); ?>';
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\schedules\create.blade.php ENDPATH**/ ?>