<?php $__env->startSection('page-title', __('center::quizzes.title')); ?>
<?php $__env->startSection('page-subtitle', __('center::quizzes.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.questions.index')); ?>" class="btn btn-light shadow-sm">
        <i class="fas fa-database me-2"></i><?php echo e(__('center::quizzes.question_bank')); ?>

    </a>
    <a href="<?php echo e(route('center.courses.index')); ?>" class="btn btn-primary shadow-sm" title="<?php echo e(__('center::quizzes.add_quiz_hint')); ?>">
        <i class="fas fa-plus-circle me-2"></i><?php echo e(__('center::quizzes.add_quiz')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;"><?php echo e(__('center::quizzes.total_quizzes')); ?></p>
                            <h2 class="fw-bolder text-dark mb-0"><?php echo e(number_format($totalQuizzesCount)); ?></h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #ecfdf5;">
                            <i class="fas fa-file-alt fa-lg" style="color: #059669;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;"><?php echo e(__('center::quizzes.student_attempts')); ?></p>
                            <h2 class="fw-bolder text-dark mb-0"><?php echo e(number_format($totalAttemptsCount)); ?></h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #f0f9ff;">
                            <i class="fas fa-users fa-lg" style="color: #0284c7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px;"><?php echo e(__('center::quizzes.avg_passing_rate')); ?></p>
                            <h2 class="fw-bolder text-dark mb-0"><?php echo e(number_format($avgPassingRate, 1)); ?>%</h2>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: #fffbeb;">
                            <i class="fas fa-trophy fa-lg" style="color: #d97706;"></i>
                        </div>
                    </div>
                    <!-- Mini progress bar -->
                    <div class="mt-3">
                        <div class="progress" style="height: 6px; border-radius: 6px; background: #f1f5f9;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e(min($avgPassingRate, 100)); ?>%; background: linear-gradient(90deg, #10b981, #34d399); border-radius: 6px;" aria-valuenow="<?php echo e($avgPassingRate); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quizzes List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-white border-0 p-4 pb-3 d-flex justify-content-between align-items-center" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-list me-2" style="color: #10b981;"></i><?php echo e(__('center::quizzes.current_quizzes_list')); ?></h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('center::quizzes.quiz_title')); ?></th>
                                    <th><?php echo e(__('center::quizzes.passing_score')); ?></th>
                                    <th><?php echo e(__('center::quizzes.duration')); ?></th>
                                    <th class="text-center"><?php echo e(__('center::quizzes.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: #f0fdf4;">
                                                    <i class="fas fa-file-alt" style="color: #10b981;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark"><?php echo e($quiz->title); ?></div>
                                                    <small class="text-muted"><i class="fas fa-book me-1"></i><?php echo e($quiz->lesson->section->course->title); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-2" style="background: #ecfdf5; color: #059669;">
                                                <?php echo e($quiz->passing_score); ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-medium small">
                                                <i class="fas fa-clock me-1" style="color: #10b981;"></i>
                                                <?php echo e($quiz->duration_minutes ?? __('center::quizzes.no_time_limit')); ?> <?php echo e(__('center::quizzes.minutes')); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="<?php echo e(route('center.quizzes.edit', $quiz)); ?>" class="btn btn-sm btn-light" title="<?php echo e(__('center::quizzes.edit')); ?>">
                                                    <i class="fas fa-pen" style="color: #10b981;"></i>
                                                </a>
                                                <a href="<?php echo e(route('center.quizzes.show', $quiz)); ?>" class="btn btn-sm btn-light" title="<?php echo e(__('center::quizzes.view_results')); ?>">
                                                    <i class="fas fa-eye" style="color: #64748b;"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9;">
                                                    <i class="fas fa-inbox fa-2x" style="color: #cbd5e1;"></i>
                                                </div>
                                                <p class="text-muted fw-medium mb-2"><?php echo e(__('center::quizzes.no_quizzes')); ?></p>
                                                <a href="<?php echo e(route('center.courses.index')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i><?php echo e(__('center::quizzes.add_quiz_from_courses')); ?>

                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attempts -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-white border-0 p-4 pb-3" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-bolt me-2" style="color: #f59e0b;"></i><?php echo e(__('center::quizzes.recent_attempts')); ?></h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <?php $__empty_1 = true; $__currentLoopData = $recentAttempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3" style="background: #f8fafc; transition: all 0.2s ease;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: <?php echo e($attempt->passed ? '#ecfdf5' : '#fef2f2'); ?>;">
                                <i class="fas fa-<?php echo e($attempt->passed ? 'check' : 'times'); ?>" style="color: <?php echo e($attempt->passed ? '#059669' : '#dc2626'); ?>;"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-bold text-dark small mb-0"><?php echo e($attempt->user->name); ?></div>
                                <div class="text-muted text-truncate" style="font-size: 0.75rem; max-width: 140px;"><?php echo e($attempt->quiz->title); ?></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge rounded-pill px-2" style="font-size: 0.65rem; background: <?php echo e($attempt->passed ? '#ecfdf5' : '#fef2f2'); ?>; color: <?php echo e($attempt->passed ? '#059669' : '#dc2626'); ?>;">
                                        <?php echo e($attempt->passed ? __('center::quizzes.passed') : __('center::quizzes.failed')); ?>

                                    </span>
                                    <small class="text-muted" style="font-size: 0.65rem;"><i class="fas fa-clock me-1"></i><?php echo e($attempt->completed_at ? $attempt->completed_at->diffForHumans() : __('center::quizzes.incomplete')); ?></small>
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bolder fs-5" style="color: <?php echo e($attempt->passed ? '#059669' : '#dc2626'); ?>;"><?php echo e(number_format($attempt->score, 0)); ?>%</div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9;">
                                <i class="fas fa-chart-bar fa-2x" style="color: #cbd5e1;"></i>
                            </div>
                            <p class="text-muted small fw-medium mb-0"><?php echo e(__('center::quizzes.no_attempts')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\quizzes\index.blade.php ENDPATH**/ ?>