

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar: Curriculum -->
        <div class="col-md-3 bg-light border-end vh-100 overflow-auto p-0">
            <div class="p-3 border-bottom">
                <h5 class="mb-0 text-truncate" title="<?php echo e($course->title); ?>"><?php echo e($course->title); ?></h5>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($enrollment->progress); ?>%"></div>
                </div>
                <small class="text-muted"><?php echo e($enrollment->progress); ?>% Complete</small>
            </div>
            
            <div class="accordion accordion-flush" id="curriculumAccordion">
                <?php $__currentLoopData = $course->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?php echo e($section->id); ?>">
                            <button class="accordion-button <?php echo e($lesson->section_id == $section->id ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo e($section->id); ?>">
                                <?php echo e($section->title); ?>

                            </button>
                        </h2>
                        <div id="collapse-<?php echo e($section->id); ?>" class="accordion-collapse collapse <?php echo e($lesson->section_id == $section->id ? 'show' : ''); ?>" data-bs-parent="#curriculumAccordion">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush">
                                    <?php $__currentLoopData = $section->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $secLesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('center.courses.player', ['course' => $course->id, 'lesson' => $secLesson->id])); ?>" 
                                           class="list-group-item list-group-item-action d-flex align-items-center <?php echo e($lesson->id == $secLesson->id ? 'active' : ''); ?>">
                                            <div class="me-2">
                                                <?php if(in_array($secLesson->id, $completedLessonIds)): ?>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                <?php else: ?>
                                                    <i class="far fa-circle text-muted"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-truncate" style="max-width: 150px;"><?php echo e($secLesson->title); ?></span>
                                                    <small class="text-muted ms-1"><?php echo e($secLesson->duration); ?>m</small>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Main Content: Player -->
        <div class="col-md-9 p-4 vh-100 overflow-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0"><?php echo e($lesson->title); ?></h2>
                <?php if(!in_array($lesson->id, $completedLessonIds)): ?>
                    <form action="<?php echo e(route('center.lessons.complete', ['course' => $course->id, 'lesson' => $lesson->id])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Mark as Complete</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-outline-success" disabled><i class="fas fa-check-double me-1"></i> Completed</button>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if($lesson->type == 'video'): ?>
                        <div class="ratio ratio-16x9 bg-dark">
                            <?php if(Str::contains($lesson->content, 'youtube.com') || Str::contains($lesson->content, 'youtu.be')): ?>
                                <iframe src="<?php echo e(str_replace('watch?v=', 'embed/', $lesson->content)); ?>" allowfullscreen></iframe>
                            <?php elseif(Str::contains($lesson->content, 'vimeo.com')): ?>
                                <iframe src="https://player.vimeo.com/video/<?php echo e(basename($lesson->content)); ?>" allowfullscreen></iframe>
                            <?php else: ?>
                                <!-- Local Video or other URL -->
                                <video controls>
                                    <source src="<?php echo e($lesson->content); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php elseif($lesson->type == 'text'): ?>
                        <div class="p-4">
                            <?php echo strip_tags($lesson->content, '<p><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><a><img><table><thead><tbody><tr><th><td><blockquote><pre><code>'); ?>

                        </div>
                    <?php elseif($lesson->type == 'quiz'): ?>
                        <div class="p-5 text-center">
                            <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                            <h3>الاختبار: <?php echo e($lesson->title); ?></h3>
                            <?php $quiz = $lesson->quiz; ?>
                            <?php if($quiz): ?>
                                <p class="text-muted"><?php echo e(__('center::quizzes.passing_score')); ?>: <?php echo e($quiz->passing_score); ?>% | <?php echo e(__('center::quizzes.duration')); ?>: <?php echo e($quiz->duration_minutes ?? __('center::courses.not_specified')); ?> <?php echo e(__('center::quizzes.minutes')); ?></p>
                                <a href="<?php echo e(route('center.quizzes.show', $quiz)); ?>" class="btn btn-primary btn-lg rounded-pill px-5"><?php echo e(__('center::quizzes.start_quiz')); ?></a>
                            <?php else: ?>
                                <p class="text-danger"><?php echo e(__('center::quizzes.no_questions_prepared')); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php elseif($lesson->type == 'assignment'): ?>
                        <div class="p-5 text-center">
                            <i class="fas fa-file-upload fa-3x text-success mb-3"></i>
                            <h3>التكليف: <?php echo e($lesson->title); ?></h3>
                            <?php $assignment = $lesson->assignment; ?>
                            <?php if($assignment): ?>
                                <p class="text-muted">أقصى درجة: <?php echo e($assignment->max_score); ?> | موعد التسليم: <?php echo e($assignment->due_date ? $assignment->due_date->format('Y-m-d') : 'غير محدد'); ?></p>
                                <a href="<?php echo e(route('center.assignments.show', $assignment)); ?>" class="btn btn-success btn-lg rounded-pill px-5"><?php echo e(__('center::messages.blade_0366')); ?></a>
                            <?php else: ?>
                                <p class="text-danger"><?php echo e(__('center::messages.msg_032')); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm mt-4 border-0 rounded-4">
                <div class="card-header bg-white border-0 p-0 overflow-hidden">
                    <ul class="nav nav-pills nav-fill bg-light border-bottom p-1" id="lessonTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill py-2" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button"><?php echo e(__('center::courses.about_lesson')); ?></button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill py-2" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button">
                                المصادر (<?php echo e($course->resources->count()); ?>)
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="lessonTabsContent">
                        <div class="tab-pane fade show active" id="about">
                            <h4 class="fw-bold mb-3"><?php echo e($lesson->title); ?></h4>
                            <div class="text-muted">
                                <?php echo nl2br(e($lesson->description ?? __('center::courses.no_description'))); ?>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="resources">
                            <div class="row g-3">
                                <?php $__empty_1 = true; $__currentLoopData = $course->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 bg-light transition shadow-sm-hover h-100">
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <i class="fas fa-file-<?php echo e(in_array($res->file_type, ['pdf', 'doc', 'docx']) ? 'pdf' : 'alt'); ?> text-primary fs-4 me-3"></i>
                                                <div class="text-truncate">
                                                    <div class="fw-bold text-truncate" style="max-width: 200px;"><?php echo e($res->title); ?></div>
                                                    <small class="text-muted"><?php echo e(strtoupper($res->file_type)); ?> • <?php echo e(round($res->file_size / 1024 / 1024, 2)); ?> MB</small>
                                                </div>
                                            </div>
                                            <a href="<?php echo e(route('center.resources.download', $res)); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="col-12 text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i>
                                        <p><?php echo e(__('center::courses.no_attachments')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <!-- Navigation Buttons Logic -->
                <?php
                    $allLessons = $course->sections->flatMap->lessons;
                    $currentIndex = $allLessons->search(function($item) use ($lesson) { return $item->id == $lesson->id; });
                    $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                    $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                ?>

                <?php if($prevLesson): ?>
                    <a href="<?php echo e(route('center.courses.player', ['course' => $course->id, 'lesson' => $prevLesson->id])); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-right me-1"></i> الدرس السابق: <?php echo e($prevLesson->title); ?>

                    </a>
                <?php else: ?>
                    <div></div>
                <?php endif; ?>

                <?php if($nextLesson): ?>
                    <a href="<?php echo e(route('center.courses.player', ['course' => $course->id, 'lesson' => $nextLesson->id])); ?>" class="btn btn-primary rounded-pill px-4">
                        الدرس التالي: <?php echo e($nextLesson->title); ?> <i class="fas fa-arrow-left ms-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->startSection('scripts'); ?>
<script>
    // 1. Anti-Copy Protection
    document.addEventListener('contextmenu', event => event.preventDefault()); // Disable Right Click
    
    document.onkeydown = function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P, Ctrl+C
        if (e.keyCode == 123 || 
            (e.ctrlKey && e.shiftKey && (e.keyCode == 'I'.charCodeAt(0) || e.keyCode == 'J'.charCodeAt(0) || e.keyCode == 'C'.charCodeAt(0))) || 
            (e.ctrlKey && (e.keyCode == 'U'.charCodeAt(0) || e.keyCode == 'S'.charCodeAt(0) || e.keyCode == 'P'.charCodeAt(0) || e.keyCode == 'C'.charCodeAt(0)))) {
            return false;
        }
    };

    // 2. Dynamic Watermarking Logic
    const watermarkId = 'wm-' + Math.random().toString(36).substr(2, 9);
    const studentInfo = "<?php echo e(auth()->user()->email); ?> | <?php echo e(auth()->user()->student->phone ?? auth()->user()->id); ?>";
    
    const wm = document.createElement('div');
    wm.id = watermarkId;
    wm.style.position = 'fixed';
    wm.style.zIndex = '9999';
    wm.style.pointerEvents = 'none';
    wm.style.opacity = '0.3';
    wm.style.color = '#fff';
    wm.style.fontSize = '12px';
    wm.style.fontWeight = 'bold';
    wm.style.textShadow = '1px 1px 2px #000';
    wm.style.padding = '5px';
    wm.style.whiteSpace = 'nowrap';
    wm.innerText = studentInfo;
    document.body.appendChild(wm);

    function moveWatermark() {
        // Only move if there is a video on screen
        const videoContainer = document.querySelector('.ratio-16x9') || document.body;
        const rect = videoContainer.getBoundingClientRect();
        
        const x = Math.random() * (rect.width - 200) + rect.left;
        const y = Math.random() * (rect.height - 50) + rect.top;
        
        wm.style.left = x + 'px';
        wm.style.top = y + 'px';
        
        // Randomly change opacity to make it harder to filter out
        wm.style.opacity = (Math.random() * 0.3 + 0.1).toString();
    }

    // Move every 5-10 seconds
    moveWatermark();
    setInterval(moveWatermark, Math.random() * 5000 + 5000);
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\courses\player.blade.php ENDPATH**/ ?>