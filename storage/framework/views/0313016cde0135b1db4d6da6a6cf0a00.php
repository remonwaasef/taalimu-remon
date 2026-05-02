<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?php echo e(__('center::quizzes.builder_title', ['title' => $quiz->title])); ?></h1>
        <a href="<?php echo e(route('center.curriculum.edit', $quiz->lesson->section->course)); ?>" class="btn btn-secondary"><?php echo e(__('center::quizzes.back_to_curriculum')); ?></a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header"><?php echo e(__('center::quizzes.quiz_settings')); ?></div>
        <div class="card-body">
            <form action="<?php echo e(route('center.quizzes.update', $quiz)); ?>" method="POST" class="row g-3">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo e(__('center::quizzes.quiz_title')); ?></label>
                    <input type="text" name="title" class="form-control" value="<?php echo e($quiz->title); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo e(__('center::quizzes.passing_score_pct')); ?></label>
                    <input type="number" name="passing_score" class="form-control" value="<?php echo e($quiz->passing_score); ?>" min="0" max="100" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo e(__('center::quizzes.duration_mins')); ?></label>
                    <input type="number" name="duration_minutes" class="form-control" value="<?php echo e($quiz->duration_minutes); ?>" min="0">
                    <small class="text-muted"><?php echo e(__('center::quizzes.unlimited_hint')); ?></small>
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo e(__('center::quizzes.description')); ?></label>
                    <textarea name="description" class="form-control" rows="2"><?php echo e($quiz->description); ?></textarea>
                </div>
                <div class="col-12 py-2 border-bottom mb-3">
                    <h6 class="fw-bold text-primary"><i class="fas fa-random me-2"></i> <?php echo e(__('center::quizzes.randomization_logic')); ?></h6>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_randomized" value="1" id="is_randomized" <?php echo e($quiz->is_randomized ? 'checked' : ''); ?>>
                        <label class="form-check-label fw-bold" for="is_randomized"><?php echo e(__('center::quizzes.enable_randomization')); ?></label>
                    </div>
                    <small class="text-muted"><?php echo e(__('center::quizzes.randomization_hint')); ?></small>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('center::quizzes.questions_to_pick')); ?></label>
                    <input type="number" name="random_questions_count" class="form-control" value="<?php echo e($quiz->random_questions_count); ?>" min="1">
                    <small class="text-muted"><?php echo e(__('center::quizzes.questions_to_pick_hint')); ?></small>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('center::quizzes.source_category')); ?></label>
                    <select name="category_id" class="form-select">
                        <option value=""><?php echo e(__('center::quizzes.all_bank_questions')); ?></option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e($quiz->category_id == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::quizzes.update_settings')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><?php echo e(__('center::questions.title')); ?></span>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addQuestionModal"><i class="fas fa-plus"></i> <?php echo e(__('center::quizzes.add_question')); ?></button>
        </div>
        <div class="card-body">
            <?php $__currentLoopData = $quiz->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3 border-primary">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="mb-0">Q<?php echo e($index + 1); ?>: <?php echo e(Str::limit($question->content, 50)); ?> <span class="badge bg-info">
                            <?php echo e($question->type == 'mcq' ? __('center::questions.mcq') : __('center::questions.true_false')); ?>

                        </span></h5>
                        <div>
                            <span class="badge bg-secondary me-2"><?php echo e($question->points); ?> <?php echo e(__('center::questions.points_suffix')); ?></span>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editQuestionModal-<?php echo e($question->id); ?>"><i class="fas fa-edit"></i></button>
                            <form action="<?php echo e(route('center.questions.destroy', $question)); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('<?php echo e(__('center::questions.delete_confirm')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="lead"><?php echo e($question->content); ?></p>
                        
                        <!-- Options -->
                        <ul class="list-group">
                            <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo e($option->is_correct ? 'list-group-item-success' : ''); ?>">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <?php if($question->type == 'mcq'): ?>
                                            <form action="<?php echo e(route('center.options.correct', $option)); ?>" method="POST" class="me-2">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm <?php echo e($option->is_correct ? 'btn-success' : 'btn-outline-secondary'); ?>" title="<?php echo e(__('center::quizzes.mark_as_correct')); ?>">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form action="<?php echo e(route('center.options.update', $option)); ?>" method="POST" class="flex-grow-1">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="content" class="form-control border-0 bg-transparent" value="<?php echo e($option->content); ?>">
                                                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-save"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <?php if($question->type == 'mcq'): ?>
                                        <form action="<?php echo e(route('center.options.destroy', $option)); ?>" method="POST" class="ms-2" onsubmit="return confirm('<?php echo e(__('center::quizzes.delete_option_confirm')); ?>');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <?php if($question->type == 'mcq'): ?>
                            <div class="mt-2">
                                <form action="<?php echo e(route('center.options.store', $question)); ?>" method="POST" class="d-flex">
                                    <?php echo csrf_field(); ?>
                                    <input type="text" name="content" class="form-control form-control-sm me-2" placeholder="<?php echo e(__('center::quizzes.new_option')); ?>" required>
                                    <button type="submit" class="btn btn-sm btn-outline-primary"><?php echo e(__('center::questions.add_option')); ?></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Edit Question Modal -->
                <div class="modal fade" id="editQuestionModal-<?php echo e($question->id); ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="<?php echo e(route('center.questions.update', $question)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?php echo e(__('center::questions.edit')); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label><?php echo e(__('center::questions.content')); ?></label>
                                        <textarea name="content" class="form-control" rows="3" required><?php echo e($question->content); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label><?php echo e(__('center::questions.points')); ?></label>
                                        <input type="number" name="points" class="form-control" value="<?php echo e($question->points); ?>" min="1" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('center::quizzes.close')); ?></button>
                                    <button type="submit" class="btn btn-primary"><?php echo e(__('center::quizzes.save_changes')); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?php echo e(route('center.questions.store', $quiz)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('center::questions.new_question')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label><?php echo e(__('center::questions.content')); ?></label>
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label><?php echo e(__('center::questions.type')); ?></label>
                        <select name="type" class="form-select">
                            <option value="mcq"><?php echo e(__('center::questions.mcq')); ?></option>
                            <option value="true_false"><?php echo e(__('center::questions.true_false')); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label><?php echo e(__('center::questions.points')); ?></label>
                        <input type="number" name="points" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('center::quizzes.close')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('center::quizzes.add_question')); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\quizzes\edit.blade.php ENDPATH**/ ?>