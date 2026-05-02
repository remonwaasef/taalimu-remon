

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><?php echo e($assignment->title); ?></h3>
                    <?php if($assignment->due_date): ?>
                        <span class="badge bg-<?php echo e($assignment->due_date->isPast() ? 'danger' : 'info'); ?>">
                            Due: <?php echo e($assignment->due_date->format('M d, Y H:i')); ?>

                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Instructions:</h5>
                        <p class="text-muted"><?php echo nl2br(e($assignment->description)); ?></p>
                        <p><strong>Max Score:</strong> <?php echo e($assignment->max_score); ?></p>
                    </div>

                    <hr>

                    <?php if($submission): ?>
                        <div class="alert alert-success">
                            <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Submitted</h5>
                            <p class="mb-0">Submitted on: <?php echo e($submission->submitted_at->format('M d, Y H:i')); ?></p>
                            <p class="mb-0">File: <a href="<?php echo e(Storage::url($submission->file_path)); ?>" target="_blank">Download Submission</a></p>
                        </div>

                        <?php if($submission->grade !== null): ?>
                            <div class="card bg-light border-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Grade: <?php echo e($submission->grade); ?> / <?php echo e($assignment->max_score); ?></h5>
                                    <?php if($submission->feedback): ?>
                                        <p class="card-text"><strong>Feedback:</strong> <?php echo e($submission->feedback); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Pending Grading...
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(!$submission || ($submission && $submission->grade === null && (!$assignment->due_date || !$assignment->due_date->isPast()))): ?>
                        <h5 class="mb-3"><?php echo e($submission ? 'Resubmit Assignment' : 'Submit Assignment'); ?></h5>
                        <form action="<?php echo e(route('center.assignments.submit', $assignment)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label">Upload File (PDF, Doc, Image, Zip)</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> <?php echo e($submission ? 'Update Submission' : 'Submit'); ?>

                            </button>
                        </form>
                    <?php elseif($assignment->due_date && $assignment->due_date->isPast()): ?>
                        <div class="alert alert-warning">
                            Submission closed. The due date has passed.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\assignments\show.blade.php ENDPATH**/ ?>