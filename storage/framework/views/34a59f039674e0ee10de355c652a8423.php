<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Curriculum for: <?php echo e($course->title); ?></h1>
        <a href="<?php echo e(route('center.courses.index')); ?>" class="btn btn-secondary">Back to Courses</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <!-- Course Resources Section -->
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-paperclip text-primary me-2"></i><?php echo e(__('center::curriculum.course_resources')); ?></h5>
            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#resourceForm">
                <i class="fas fa-plus me-1"></i><?php echo e(__('center::curriculum.add_file')); ?></button>
        </div>
        <div class="collapse" id="resourceForm">
            <div class="card-body bg-light border-top">
                <form action="<?php echo e(route('center.resources.store', $course)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="title" class="form-control" placeholder="<?php echo e(__('center::curriculum.file_title_placeholder')); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100"><?php echo e(__('center::curriculum.upload')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__empty_1 = true; $__currentLoopData = $course->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-white">
                            <div class="d-flex align-items-center overflow-hidden">
                                <i class="fas fa-file-pdf text-danger fs-4 me-3"></i>
                                <div class="text-truncate">
                                    <div class="fw-bold text-truncate" style="max-width: 150px;"><?php echo e($res->title); ?></div>
                                    <small class="text-muted"><?php echo e(strtoupper($res->file_type)); ?> • <?php echo e(round($res->file_size / 1024 / 1024, 2)); ?> MB</small>
                                </div>
                            </div>
                            <form action="<?php echo e(route('center.resources.destroy', $res)); ?>" method="POST" class="ms-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('<?php echo e(__('center::curriculum.confirm_delete_file')); ?>')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center text-muted py-3"><?php echo e(__('center::curriculum.no_files')); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Section Form -->
    <div class="card mb-4 border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form action="<?php echo e(route('center.sections.store', $course)); ?>" method="POST" class="row g-3 align-items-center">
                <?php echo csrf_field(); ?>
                <div class="col-auto flex-grow-1">
                    <input type="text" name="title" class="form-control rounded-pill" placeholder="<?php echo e(__('center::curriculum.section_title_placeholder')); ?>" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><?php echo e(__('center::curriculum.add_section')); ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sections List -->
    <div id="sections-list" data-url="<?php echo e(route('center.sections.reorder', $course)); ?>">
        <?php $__currentLoopData = $course->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-3 section-item" data-id="<?php echo e($section->id); ?>">
                <div class="card-header d-flex justify-content-between align-items-center handle" style="cursor: move; background-color: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-grip-lines me-2 text-muted"></i>
                        <form action="<?php echo e(route('center.sections.update', $section)); ?>" method="POST" class="d-inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="text" name="title" value="<?php echo e($section->title); ?>" class="form-control form-control-sm border-0 bg-transparent fw-bold" style="width: 300px;">
                        </form>
                    </div>
                    <div>
                        <form action="<?php echo e(route('center.sections.destroy', $section)); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this section and all its lessons?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Lessons List -->
                    <ul class="list-group lessons-list" id="lessons-section-<?php echo e($section->id); ?>" data-url="<?php echo e(route('center.lessons.reorder', $section)); ?>">
                        <?php $__currentLoopData = $section->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center lesson-item" data-id="<?php echo e($lesson->id); ?>">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical me-2 text-muted handle-lesson" style="cursor: move;"></i>
                                    <i class="fas fa-<?php echo e($lesson->type == 'video' ? 'video' : ($lesson->type == 'quiz' ? 'question-circle' : 'file-alt')); ?> me-2 text-primary"></i>
                                    <span><?php echo e($lesson->title); ?></span>
                                    <?php if($lesson->is_free): ?>
                                        <span class="badge bg-success ms-2">Free Preview</span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editLessonModal-<?php echo e($lesson->id); ?>"><i class="fas fa-edit"></i></button>
                                    <form action="<?php echo e(route('center.lessons.destroy', $lesson)); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this lesson?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    
                    <!-- Add Lesson Form -->
                    <div class="mt-3">
                        <form action="<?php echo e(route('center.lessons.store', $section)); ?>" method="POST" class="row g-2">
                            <?php echo csrf_field(); ?>
                            <div class="col-auto">
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="New Lesson Title" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Add Lesson</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Edit Lesson Modals (Moved outside the loop) -->
    <?php $__currentLoopData = $course->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $section->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="modal fade" id="editLessonModal-<?php echo e($lesson->id); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?php echo e(route('center.lessons.update', $lesson)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Lesson</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" id="lesson_title_<?php echo e($lesson->id); ?>" class="form-control" value="<?php echo e($lesson->title); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="video" <?php echo e($lesson->type == 'video' ? 'selected' : ''); ?>>Video</option>
                                        <option value="text" <?php echo e($lesson->type == 'text' ? 'selected' : ''); ?>>Text</option>
                                        <option value="quiz" <?php echo e($lesson->type == 'quiz' ? 'selected' : ''); ?>>Quiz</option>
                                        <option value="assignment" <?php echo e($lesson->type == 'assignment' ? 'selected' : ''); ?>>Assignment</option>
                                    </select>
                                </div>

                                <?php if($lesson->type == 'quiz'): ?>
                                    <div class="mb-3">
                                        <?php if($lesson->quiz): ?>
                                            <a href="<?php echo e(route('center.quizzes.edit', $lesson->quiz)); ?>" class="btn btn-sm btn-info w-100">Manage Quiz Questions</a>
                                        <?php else: ?>
                                            <div class="p-3 border rounded bg-light">
                                                <p class="small text-muted mb-2">Quiz Setup required</p>
                                                <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="submitInitialSetup('<?php echo e(route('center.quizzes.store', $lesson)); ?>', 'lesson_title_<?php echo e($lesson->id); ?>', {passing_score: 50})">
                                                    Initial Quiz Setup
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif($lesson->type == 'assignment'): ?>
                                    <div class="mb-3">
                                        <?php if($lesson->assignment): ?>
                                            <a href="<?php echo e(route('center.assignments.edit', $lesson->assignment)); ?>" class="btn btn-sm btn-warning w-100">Edit Assignment Details</a>
                                        <?php else: ?>
                                            <div class="p-3 border rounded bg-light">
                                                <p class="small text-muted mb-2">Assignment Setup required</p>
                                                <button type="button" class="btn btn-sm btn-outline-warning w-100" onclick="submitInitialSetup('<?php echo e(route('center.assignments.store', $lesson)); ?>', 'lesson_title_<?php echo e($lesson->id); ?>', {max_score: 100})">
                                                    Initial Assignment Setup
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label">Content (URL or Text)</label>
                                    <textarea name="content" class="form-control" rows="3"><?php echo e($lesson->content); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" name="duration" class="form-control" value="<?php echo e($lesson->duration); ?>">
                                </div>
                                <div class="form-check">
                                    <input type="hidden" name="is_free" value="0">
                                    <input type="checkbox" name="is_free" value="1" class="form-check-input" id="freeCheckModal-<?php echo e($lesson->id); ?>" <?php echo e($lesson->is_free ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="freeCheckModal-<?php echo e($lesson->id); ?>">Free Preview</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Hidden form for initial setup actions (avoiding nested forms) -->
    <form id="initialSetupForm" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="title" id="setup_title">
        <div id="setup_extra_fields"></div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Sections Sorting
    new Sortable(document.getElementById('sections-list'), {
        handle: '.handle',
        animation: 150,
        onEnd: function (evt) {
            let url = document.getElementById('sections-list').dataset.url;
            let sections = Array.from(document.querySelectorAll('.section-item')).map(el => el.dataset.id);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ sections: sections })
            });
        }
    });

    // Lessons Sorting
    document.querySelectorAll('.lessons-list').forEach(function(el) {
        new Sortable(el, {
            group: 'lessons', // Allow dragging between sections
            handle: '.handle-lesson',
            animation: 150,
            onEnd: function (evt) {
                let sectionId = evt.to.id.replace('lessons-section-', '');
                let url = evt.to.dataset.url;
                let lessons = Array.from(evt.to.querySelectorAll('.lesson-item')).map(el => el.dataset.id);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ lessons: lessons })
                });
            }
    });

    // Helper for initial setup without nested forms
    function submitInitialSetup(url, titleInputId, extraFields = {}) {
        const form = document.getElementById('initialSetupForm');
        const titleInput = document.getElementById(titleInputId);
        const extraContainer = document.getElementById('setup_extra_fields');
        
        form.action = url;
        document.getElementById('setup_title').value = titleInput.value;
        
        // Clear and add extra fields
        extraContainer.innerHTML = '';
        for (const [key, value] of Object.entries(extraFields)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            extraContainer.appendChild(input);
        }
        
        form.submit();
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\curriculum\edit.blade.php ENDPATH**/ ?>