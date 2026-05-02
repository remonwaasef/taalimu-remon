

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header sticky-top bg-white shadow-sm d-flex justify-content-between align-items-center" style="z-index: 1000;">
                    <h3 class="mb-0"><?php echo e($quiz->title); ?></h3>
                    <?php if($quiz->duration_minutes > 0 && isset($endTime)): ?>
                        <div class="text-center">
                            <small class="text-muted d-block"><?php echo e(__('center::quizzes.time_remaining')); ?></small>
                            <span id="timer" class="badge bg-danger fs-5">
                                <i class="fas fa-clock"></i> --:--
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if($quiz->description): ?>
                        <p class="text-muted mb-4"><?php echo e($quiz->description); ?></p>
                    <?php endif; ?>

                    <form action="<?php echo e(route('center.quizzes.submit', $quiz)); ?>" method="POST" id="quizForm">
                        <?php echo csrf_field(); ?>
                        
                        <?php $__currentLoopData = $quiz->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">
                                        <span class="badge bg-primary me-2"><?php echo e($index + 1); ?></span>
                                        <?php echo e($question->content); ?>

                                    </h5>
                                    <div class="list-group">
                                        <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="list-group-item list-group-item-action border-0 rounded mb-2" style="background-color: #f8f9fa;">
                                                <input class="form-check-input me-2" type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($option->id); ?>" required>
                                                <?php echo e($option->content); ?>

                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i> <?php echo e(__('center::quizzes.submit_quiz')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if($quiz->duration_minutes > 0 && isset($endTime)): ?>
            const endTime = new Date("<?php echo e($endTime->format('Y-m-d H:i:s')); ?>").getTime();
            const timerElement = document.getElementById('timer');
            const form = document.getElementById('quizForm');
            
            const timerInterval = setInterval(function() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    clearInterval(timerInterval);
                    timerElement.innerHTML = "<?php echo e(__('center::quizzes.expired')); ?>";
                    // Auto submit
                    alert('<?php echo e(__('center::quizzes.time_up_alert')); ?>');
                    form.submit();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timerElement.innerHTML = minutes + "m " + seconds + "s ";
                
                // Visual warning when less than 1 minute
                if (distance < 60000) {
                    timerElement.classList.remove('bg-danger');
                    timerElement.classList.add('bg-warning', 'text-dark', 'animate__animated', 'animate__flash');
                }
            }, 1000);
        <?php endif; ?>

        // Prevent accidental navigation
        window.onbeforeunload = function() {
            return "<?php echo e(__('center::quizzes.leave_confirm')); ?>";
        };

        document.getElementById('quizForm').onsubmit = function() {
            window.onbeforeunload = null;
        };
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\quizzes\show.blade.php ENDPATH**/ ?>