

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <?php if($attempt->passed): ?>
                        <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                        <h2 class="text-success"><?php echo e(__('center::quizzes.congratulations')); ?></h2>
                        <p class="lead"><?php echo e(__('center::quizzes.passed_msg')); ?></p>
                    <?php else: ?>
                        <i class="fas fa-times-circle text-danger fa-5x mb-3"></i>
                        <h2 class="text-danger"><?php echo e(__('center::quizzes.keep_trying')); ?></h2>
                        <p class="lead"><?php echo e(__('center::quizzes.failed_msg')); ?></p>
                    <?php endif; ?>

                    <div class="display-1 fw-bold my-4 <?php echo e($attempt->passed ? 'text-success' : 'text-danger'); ?>">
                        <?php echo e($attempt->score); ?>%
                    </div>

                    <p class="text-muted"><?php echo e(__('center::quizzes.passing_score_label')); ?> <?php echo e($attempt->quiz->passing_score); ?>%</p>

                    <div class="mt-4">
                        <a href="<?php echo e(route('center.courses.player', ['course' => $attempt->quiz->lesson->section->course_id, 'lesson' => $attempt->quiz->lesson_id])); ?>" class="btn btn-primary"><?php echo e(__('center::quizzes.back_to_lesson')); ?></a>
                        <?php if(!$attempt->passed): ?>
                            <a href="<?php echo e(route('center.quizzes.show', $attempt->quiz)); ?>" class="btn btn-outline-secondary"><?php echo e(__('center::quizzes.retake_quiz')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\quizzes\result.blade.php ENDPATH**/ ?>