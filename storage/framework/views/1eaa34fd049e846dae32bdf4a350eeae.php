<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(isset($schedule) ? __('center::schedules.edit_schedule') : __('center::schedules.add_new')); ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('center.schedules.index')); ?>"><?php echo e(__('center::schedules.schedules_list')); ?></a></li>
                <li class="breadcrumb-item active"><?php echo e(isset($schedule) ? __('center::schedules.edit') : __('center::schedules.new')); ?></li>
            </ol>
        </nav>
    </div>

    <?php if($errors->has('conflict')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo e($errors->first('conflict')); ?>

        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="<?php echo e(isset($schedule) ? route('center.schedules.update', $schedule) : route('center.schedules.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($schedule)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.course')); ?></label>
                                <select name="course_id" class="form-select <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value=""><?php echo e(__('center::schedules.choose_course')); ?></option>
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
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.classroom')); ?></label>
                                <select name="classroom_id" class="form-select <?php $__errorArgs = ['classroom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value=""><?php echo e(__('center::schedules.choose_classroom')); ?></option>
                                    <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($classroom->id); ?>" <?php echo e(old('classroom_id', $schedule->classroom_id ?? '') == $classroom->id ? 'selected' : ''); ?>>
                                            <?php echo e($classroom->name); ?> (<?php echo e(__('center::schedules.capacity')); ?>: <?php echo e($classroom->capacity ?? '∞'); ?>)
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

                            <div class="col-md-12">
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.instructor')); ?></label>
                                <select name="instructor_id" class="form-select <?php $__errorArgs = ['instructor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value=""><?php echo e(__('center::schedules.choose_instructor')); ?></option>
                                    <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($instructor->id); ?>" <?php echo e(old('instructor_id', $schedule->instructor_id ?? '') == $instructor->id ? 'selected' : ''); ?>>
                                            <?php echo e($instructor->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['instructor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted"><?php echo e(__('center::schedules.instructor_change_hint')); ?></small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.day')); ?></label>
                                <select name="day_of_week" class="form-select <?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php
                                        $days = [
                                            0 => __('center::schedules.sunday'),
                                            1 => __('center::schedules.monday'),
                                            2 => __('center::schedules.tuesday'),
                                            3 => __('center::schedules.wednesday'),
                                            4 => __('center::schedules.thursday'),
                                            5 => __('center::schedules.friday'),
                                            6 => __('center::schedules.saturday'),
                                        ];
                                    ?>
                                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.start_time')); ?></label>
                                <input type="time" name="start_time" class="form-control <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_time', $schedule->start_time ?? '')); ?>">
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
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.end_time')); ?></label>
                                <input type="time" name="end_time" class="form-control <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_time', $schedule->end_time ?? '')); ?>">
                                <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::schedules.max_students')); ?></label>
                                <input type="number" name="max_students" class="form-control <?php $__errorArgs = ['max_students'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('max_students', $schedule->max_students ?? '')); ?>" placeholder="<?php echo e(__('center::schedules.max_students_placeholder')); ?>">
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

                        <div class="d-flex gap-2 pb-5">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill"><?php echo e(__('center::schedules.save')); ?></button>
                            <a href="<?php echo e(route('center.schedules.index')); ?>" class="btn btn-light px-4 rounded-pill"><?php echo e(__('center::schedules.cancel')); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i><?php echo e(__('center::schedules.important_instructions')); ?></h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 small">• <?php echo e(__('center::schedules.info_1')); ?></li>
                        <li class="mb-2 small">• <?php echo e(__('center::schedules.info_2')); ?></li>
                        <li class="small">• <?php echo e(__('center::schedules.info_3')); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
        <style>
            .ts-control {
                border-radius: 0.5rem !important;
                padding: 0.75rem 1rem !important;
                border-color: #dee2e6 !important;
            }
            .ts-control:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
            }
            .ts-dropdown {
                border-radius: 0.5rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                border: 1px solid #eee !important;
                padding: 0.5rem !important;
                z-index: 2000 !important;
            }
            .ts-dropdown .option {
                border-radius: 0.375rem !important;
                padding: 0.5rem 1rem !important;
            }
            .ts-dropdown .active {
                background-color: var(--primary-color, #3A0CA3) !important;
                color: #fff !important;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.querySelectorAll('select').forEach((el) => {
                new TomSelect(el, {
                    plugins: ['dropdown_input'],
                    dropdownParent: 'body',
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    render:{
                        no_results:function(data,escape){
                            return '<div class="no-results p-2 text-muted"><?php echo e(__('center::schedules.no_results')); ?></div>';
                        }
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\schedules\create.blade.php ENDPATH**/ ?>