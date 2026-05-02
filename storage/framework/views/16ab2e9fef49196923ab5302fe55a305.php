<?php $__env->startSection('page-title', __('instructor::online_classes.add_new')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-video fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0" style="color: var(--primary-color);"><?php echo e(__('instructor::online_classes.add_online_class')); ?></h4>
                            <p class="text-muted small mb-0"><?php echo e(__('instructor::online_classes.add_online_class_desc')); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('instructor.online_classes.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.lesson_title')); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control rounded-pill px-3 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>" required placeholder="<?php echo e(__('instructor::online_classes.lesson_title_placeholder')); ?>">
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.course_group')); ?> <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select rounded-pill px-3 <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="" disabled selected><?php echo e(__('instructor::online_classes.select_group')); ?></option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>><?php echo e($course->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.stream_platform')); ?> <span class="text-danger">*</span></label>
                                <select name="platform" class="form-select rounded-pill px-3 <?php $__errorArgs = ['platform'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="zoom" <?php echo e(old('platform') == 'zoom' ? 'selected' : ''); ?>>Zoom</option>
                                    <option value="google_meet" <?php echo e(old('platform') == 'google_meet' ? 'selected' : ''); ?>>Google Meet</option>
                                    <option value="microsoft_teams" <?php echo e(old('platform') == 'microsoft_teams' ? 'selected' : ''); ?>>Microsoft Teams</option>
                                    <option value="other" <?php echo e(old('platform') == 'other' ? 'selected' : ''); ?>><?php echo e(__('instructor::online_classes.other')); ?></option>
                                </select>
                                <?php $__errorArgs = ['platform'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.meeting_link')); ?> <span class="text-danger">*</span></label>
                                <input type="url" name="meeting_link" class="form-control rounded-pill px-3 <?php $__errorArgs = ['meeting_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('meeting_link')); ?>" required placeholder="<?php echo e(__('instructor::online_classes.enter_link_placeholder')); ?>">
                                <?php $__errorArgs = ['meeting_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.meeting_id')); ?> <span class="text-muted small"><?php echo e(__('instructor::online_classes.optional')); ?></span></label>
                                <input type="text" name="meeting_id" class="form-control rounded-pill px-3 <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('meeting_id')); ?>" placeholder="123 456 789">
                                <?php $__errorArgs = ['meeting_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.password')); ?> <span class="text-muted small"><?php echo e(__('instructor::online_classes.optional')); ?></span></label>
                                <input type="text" name="meeting_password" class="form-control rounded-pill px-3 <?php $__errorArgs = ['meeting_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('meeting_password')); ?>" placeholder="123456">
                                <?php $__errorArgs = ['meeting_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.start_time')); ?> <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_time" class="form-control rounded-pill px-3 <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_time')); ?>" required>
                                <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.duration')); ?> <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" class="form-control rounded-pill px-3 <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('duration_minutes', 60)); ?>" min="1" required>
                                <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold"><?php echo e(__('instructor::online_classes.status')); ?> <span class="text-danger">*</span></label>
                                <select name="status" class="form-select rounded-pill px-3 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="scheduled" <?php echo e(old('status') == 'scheduled' ? 'selected' : ''); ?>><?php echo e(__('instructor::online_classes.scheduled')); ?></option>
                                    <option value="in_progress" <?php echo e(old('status') == 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('instructor::online_classes.in_progress')); ?></option>
                                    <option value="completed" <?php echo e(old('status') == 'completed' ? 'selected' : ''); ?>><?php echo e(__('instructor::online_classes.completed')); ?></option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('instructor.online_classes.index')); ?>" class="btn btn-light rounded-pill px-4"><?php echo e(__('instructor::online_classes.cancel')); ?></a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: var(--primary-color);"><?php echo e(__('instructor::online_classes.save_and_schedule')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('instructor::components.layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Instructor\resources\views\online_classes\create.blade.php ENDPATH**/ ?>