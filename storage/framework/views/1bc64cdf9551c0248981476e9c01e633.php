<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::courses.edit_title', ['title' => $course->title])); ?></h2>
        <a href="<?php echo e(route('center.courses.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4"><?php echo e(__('center::courses.cancel')); ?></a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="<?php echo e(route('center.courses.update', $course->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::courses.course_name')); ?></label>
                            <input type="text" name="title" value="<?php echo e(old('title', $course->title)); ?>" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::courses.instructor')); ?></label>
                            <select name="instructor_id" class="form-select form-select-lg bg-light border-0 <?php $__errorArgs = ['instructor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('center::courses.choose_instructor')); ?></option>
                                <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($instructor->id); ?>" <?php echo e(old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : ''); ?>><?php echo e($instructor->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['instructor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::courses.price')); ?> (<?php echo e(get_currency_symbol()); ?>)</label>
                                <input type="number" name="price" value="<?php echo e(old('price', $course->price)); ?>" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0" step="0.01">
                                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::courses.sessions_count')); ?></label>
                                <input type="number" name="sessions_count" value="<?php echo e(old('sessions_count', $course->sessions_count)); ?>" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['sessions_count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="0">
                                <?php $__errorArgs = ['sessions_count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><?php echo e(__('center::courses.status_label')); ?></label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="status_draft" value="draft" <?php echo e(old('status', $course->status) == 'draft' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-secondary flex-grow-1 rounded-pill" for="status_draft">
                                        <i class="fas fa-pencil-alt me-1"></i><?php echo e(__('center::courses.status_draft')); ?></label>
                                    <input type="radio" class="btn-check" name="status" id="status_published" value="published" <?php echo e(old('status', $course->status) == 'published' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-success flex-grow-1 rounded-pill" for="status_published">
                                        <i class="fas fa-check-circle me-1"></i><?php echo e(__('center::courses.status_published')); ?></label>
                                </div>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::courses.course_image')); ?></label>
                            <?php if($course->image): ?>
                                <div class="mb-2">
                                    <img src="<?php echo e(Storage::url($course->image)); ?>" alt="Current Image" class="img-thumbnail rounded" style="height: 100px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><?php echo e(__('center::courses.description')); ?></label>
                            <textarea name="description" class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4"><?php echo e(old('description', $course->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle me-1"></i><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span><?php echo e(__('center::courses.course_schedules')); ?></span>
                                <button type="button" id="add-schedule-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-plus"></i><?php echo e(__('center::courses.add_schedule')); ?></button>
                            </label>
                            
                            <!-- Schedule Count Info Bar -->
                            <div id="schedule-count-info" class="alert py-2 mb-3" style="display:none;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="schedule-count-text"></span>
                            </div>
                            
                            <div id="schedules-container">
                                 <?php $__currentLoopData = $course->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="schedule-item card bg-light border-0 mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="fw-bold text-primary"><?php echo e(__('center::schedules.item_number')); ?> <span class="schedule-index"><?php echo e($index + 1); ?></span></h6>
                                                <button type="button" class="btn-close remove-schedule"></button>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1"><?php echo e(__('center::schedules.day')); ?></label>
                                                    <select name="schedules[<?php echo e($index); ?>][day_of_week]" class="form-select border-0">
                                                        <option value="saturday" <?php echo e($schedule->day_of_week === 6 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.saturday')); ?></option>
                                                        <option value="sunday" <?php echo e($schedule->day_of_week === 0 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.sunday')); ?></option>
                                                        <option value="monday" <?php echo e($schedule->day_of_week === 1 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.monday')); ?></option>
                                                        <option value="tuesday" <?php echo e($schedule->day_of_week === 2 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.tuesday')); ?></option>
                                                        <option value="wednesday" <?php echo e($schedule->day_of_week === 3 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.wednesday')); ?></option>
                                                        <option value="thursday" <?php echo e($schedule->day_of_week === 4 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.thursday')); ?></option>
                                                        <option value="friday" <?php echo e($schedule->day_of_week === 5 ? 'selected' : ''); ?>><?php echo e(__('center::schedules.friday')); ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1"><?php echo e(__('center::schedules.classroom')); ?></label>
                                                    <select name="schedules[<?php echo e($index); ?>][classroom_id]" class="form-select border-0">
                                                        <option value=""><?php echo e(__('center::schedules.choose_classroom')); ?></option>
                                                        <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($classroom->id); ?>" <?php echo e($schedule->classroom_id == $classroom->id ? 'selected' : ''); ?>><?php echo e($classroom->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1"><?php echo e(__('center::schedules.from')); ?></label>
                                                    <input type="time" name="schedules[<?php echo e($index); ?>][start_time]" value="<?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('H:i')); ?>" class="form-control border-0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="small text-muted mb-1"><?php echo e(__('center::schedules.to')); ?></label>
                                                    <input type="time" name="schedules[<?php echo e($index); ?>][end_time]" value="<?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('H:i')); ?>" class="form-control border-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <template id="schedule-template">
                            <div class="schedule-item card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold text-primary"><?php echo e(__('center::schedules.item_number')); ?> <span class="schedule-index"></span></h6>
                                        <button type="button" class="btn-close remove-schedule"></button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1"><?php echo e(__('center::schedules.day')); ?></label>
                                            <select name="schedules[INDEX][day_of_week]" class="form-select border-0">
                                                <option value="saturday"><?php echo e(__('center::schedules.saturday')); ?></option>
                                                <option value="sunday"><?php echo e(__('center::schedules.sunday')); ?></option>
                                                <option value="monday"><?php echo e(__('center::schedules.monday')); ?></option>
                                                <option value="tuesday"><?php echo e(__('center::schedules.tuesday')); ?></option>
                                                <option value="wednesday"><?php echo e(__('center::schedules.wednesday')); ?></option>
                                                <option value="thursday"><?php echo e(__('center::schedules.thursday')); ?></option>
                                                <option value="friday"><?php echo e(__('center::schedules.friday')); ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1"><?php echo e(__('center::schedules.classroom')); ?></label>
                                            <select name="schedules[INDEX][classroom_id]" class="form-select border-0">
                                                <option value=""><?php echo e(__('center::schedules.choose_classroom')); ?></option>
                                                <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($classroom->id); ?>"><?php echo e($classroom->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1"><?php echo e(__('center::schedules.from')); ?></label>
                                            <input type="time" name="schedules[INDEX][start_time]" class="form-control border-0">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1"><?php echo e(__('center::schedules.to')); ?></label>
                                            <input type="time" name="schedules[INDEX][end_time]" class="form-control border-0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-grid">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-lg rounded-pill shadow-sm"><?php echo e(__('center::courses.save_course')); ?></button>
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
        const container = document.getElementById('schedules-container');
        const addButton = document.getElementById('add-schedule-btn');
        const template = document.getElementById('schedule-template');
        const courseId = <?php echo e($course->id); ?>;
        const instructorSelect = document.querySelector('select[name="instructor_id"]');
        const sessionsCountInput = document.querySelector('input[name="sessions_count"]');
        const submitBtn = document.getElementById('submit-btn');
        const scheduleCountInfo = document.getElementById('schedule-count-info');
        const scheduleCountText = document.getElementById('schedule-count-text');
        
        // === Sessions-Schedules Link Functions ===
        function getRequiredSchedules() {
            return parseInt(sessionsCountInput.value) || 0;
        }

        function getCurrentScheduleCount() {
            return container.querySelectorAll('.schedule-item').length;
        }

        function updateScheduleCountUI() {
            const required = getRequiredSchedules();
            const current = getCurrentScheduleCount();
            
            let countValid = true;
            if (required > 0) {
                if (current !== required) countValid = false;
            }

            const validation = validateAllSchedules();
            
            if (required <= 0) {
                scheduleCountInfo.style.display = validation.isComplete && !validation.hasConflicts ? 'none' : 'block';
                addButton.style.display = '';
            } else {
                scheduleCountInfo.style.display = 'block';
            }

            if (required > 0 && current < required) {
                scheduleCountInfo.className = 'alert alert-warning py-2 mb-3';
                scheduleCountText.textContent = "<?php echo e(__('center::courses.schedules_count_info', ['required' => '__REQ__', 'current' => '__CUR__'])); ?>"
                    .replace('__REQ__', required)
                    .replace('__CUR__', current);
                addButton.style.display = '';
            } else if (required > 0 && current > required) {
                scheduleCountInfo.className = 'alert alert-danger py-2 mb-3';
                scheduleCountText.textContent = "<?php echo e(__('center::courses.schedules_count_info', ['required' => '__REQ__', 'current' => '__CUR__'])); ?>"
                    .replace('__REQ__', required)
                    .replace('__CUR__', current);
                addButton.style.display = 'none';
            } else if (!validation.isComplete) {
                scheduleCountInfo.className = 'alert alert-warning py-2 mb-3';
                scheduleCountText.textContent = "<?php echo e(__('center::schedules.incomplete_schedules')); ?>";
                addButton.style.display = (required > 0) ? 'none' : '';
            } else if (validation.hasConflicts) {
                scheduleCountInfo.className = 'alert alert-danger py-2 mb-3';
                scheduleCountText.textContent = "<?php echo e(__('center::schedules.conflict_error')); ?>";
                addButton.style.display = (required > 0) ? 'none' : '';
            } else if (required > 0 && current === required) {
                scheduleCountInfo.className = 'alert alert-success py-2 mb-3';
                scheduleCountText.textContent = "<?php echo e(__('center::courses.schedules_count_complete')); ?>";
                addButton.style.display = 'none';
            } else {
                scheduleCountInfo.style.display = 'none';
            }

            const canSubmit = countValid && validation.isComplete && !validation.hasConflicts;
            submitBtn.disabled = !canSubmit;
            if (canSubmit) {
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            } else {
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            }
        }

        function validateAllSchedules() {
            const items = container.querySelectorAll('.schedule-item');
            let isComplete = true;
            let hasConflicts = false;
            const data = [];

            items.forEach((item, index) => {
                const dayField = item.querySelector('select[name*="day_of_week"]');
                const classroomField = item.querySelector('select[name*="classroom_id"]');
                const startField = item.querySelector('input[name*="start_time"]');
                const endField = item.querySelector('input[name*="end_time"]');

                const day = dayField ? dayField.value : '';
                const classroom = classroomField ? classroomField.value : '';
                const start = startField ? startField.value : '';
                const end = endField ? endField.value : '';

                if (!day || !start || !end) {
                    isComplete = false;
                }
                
                const internalWarning = item.querySelector('.internal-conflict-warning');
                if (internalWarning) internalWarning.remove();

                data.push({ item, index, day, classroom, start, end });
            });

            for (let i = 0; i < data.length; i++) {
                for (let j = i + 1; j < data.length; j++) {
                    const a = data[i];
                    const b = data[j];

                    if (a.day && a.start && a.end && 
                        a.day === b.day && 
                        ((a.start >= b.start && a.start < b.end) || (b.start >= a.start && b.start < a.end))) {
                        
                        hasConflicts = true;
                        showInternalConflict(a.item, b.index + 1);
                        showInternalConflict(b.item, a.index + 1);
                    }
                }
            }

            return { isComplete, hasConflicts: hasConflicts || !!container.querySelector('.alert-danger.conflict-indicator') };
        }

        function showInternalConflict(item, otherIndex) {
            if (item.querySelector('.internal-conflict-warning')) return;
            const warning = document.createElement('div');
            warning.className = 'internal-conflict-warning alert alert-danger py-1 mt-2 small';
            warning.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> <?php echo e(__('center::schedules.internal_conflict', ['index' => '__INDEX__'])); ?>`.replace('__INDEX__', otherIndex);
            item.querySelector('.card-body').appendChild(warning);
        }

        function reindexSchedules() {
            container.querySelectorAll('.schedule-item').forEach((item, index) => {
                const numberSpan = item.querySelector('.schedule-index');
                if (numberSpan) numberSpan.textContent = index + 1;
                
                const inputs = item.querySelectorAll('select, input');
                inputs.forEach(input => {
                    input.name = input.name.replace(/\[\d+\]|\[INDEX\]/g, `[${index}]`);
                });
            });
        }

        sessionsCountInput.addEventListener('input', function() {
            const required = getRequiredSchedules();
            let current = getCurrentScheduleCount();

            if (required > current) {
                for (let i = current; i < required; i++) {
                    addScheduleItem();
                }
            }
            if (required > 0 && required < current) {
                const items = container.querySelectorAll('.schedule-item');
                for (let i = items.length - 1; i >= required; i--) {
                    items[i].remove();
                }
            }

            updateScheduleCountUI();
        });

        function addScheduleItem() {
            const clone = template.content.cloneNode(true);
            const index = getCurrentScheduleCount();
            
            const inputs = clone.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', index);
            });

            container.appendChild(clone);
            
            const newItem = container.lastElementChild;
            const indexSpan = newItem.querySelector('.schedule-index');
            if (indexSpan) indexSpan.textContent = index + 1;
            
            attachConflictChecker(newItem);
            updateScheduleCountUI();
        }

        addButton.addEventListener('click', function() {
            if (getRequiredSchedules() > 0 && getCurrentScheduleCount() >= getRequiredSchedules()) return;
            addScheduleItem();
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-schedule')) {
                e.target.closest('.schedule-item').remove();
                reindexSchedules();
                updateScheduleCountUI();
            }
        });

        function attachConflictChecker(scheduleItem) {
            const selects = scheduleItem.querySelectorAll('select');
            const inputs = scheduleItem.querySelectorAll('input[type="time"]');
            
            [...selects, ...inputs].forEach(el => {
                el.addEventListener('change', () => {
                    checkScheduleConflict(scheduleItem).then(() => {
                        updateScheduleCountUI();
                    });
                });
            });
        }

        async function checkScheduleConflict(scheduleItem) {
            const daySelect = scheduleItem.querySelector('select[name*="day_of_week"]');
            const classroomSelect = scheduleItem.querySelector('select[name*="classroom_id"]');
            const startTime = scheduleItem.querySelector('input[name*="start_time"]');
            const endTime = scheduleItem.querySelector('input[name*="end_time"]');
            
            const existingIndicator = scheduleItem.querySelector('.conflict-indicator');
            if (existingIndicator) existingIndicator.remove();
            
            if (!daySelect.value || !startTime.value || !endTime.value) return;
            
            try {
                const response = await fetch('<?php echo e(route("center.schedules.check-conflict")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        day_of_week: daySelect.value,
                        start_time: startTime.value,
                        end_time: endTime.value,
                        classroom_id: classroomSelect?.value || null,
                        instructor_id: instructorSelect?.value || null,
                        exclude_course_id: courseId
                    })
                });
                
                const data = await response.json();
                const indicator = document.createElement('div');
                indicator.className = 'conflict-indicator mt-2 alert py-2';
                
                if (data.status === 'conflict') {
                    indicator.classList.add('alert-danger');
                    indicator.innerHTML = data.conflicts.map(c => `<div>${c}</div>`).join('');
                } else {
                    indicator.classList.add('alert-success');
                    indicator.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + "<?php echo e(__('center::schedules.schedule_available')); ?>";
                    setTimeout(() => { if (indicator.parentNode) indicator.remove(); updateScheduleCountUI(); }, 3000);
                }
                scheduleItem.querySelector('.card-body').appendChild(indicator);
            } catch (error) {
                console.error('Error checking conflict:', error);
            }
        }

        document.querySelectorAll('.schedule-item').forEach(attachConflictChecker);

        instructorSelect.addEventListener('change', () => {
            document.querySelectorAll('.schedule-item').forEach(checkScheduleConflict);
        });

        // Initial UI update
        updateScheduleCountUI();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\courses\edit.blade.php ENDPATH**/ ?>