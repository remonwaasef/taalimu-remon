<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Submissions: <?php echo e($assignment->title); ?></h1>
        <a href="<?php echo e(route('center.assignments.edit', $assignment)); ?>" class="btn btn-secondary">Back to Assignment</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Submitted At</th>
                            <th>File</th>
                            <th>Grade</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($submission->user->name); ?> <br> <small class="text-muted"><?php echo e($submission->user->email); ?></small></td>
                                <td>
                                    <?php echo e($submission->submitted_at->format('M d, Y H:i')); ?>

                                    <?php if($assignment->due_date && $submission->submitted_at->gt($assignment->due_date)): ?>
                                        <span class="badge bg-danger">Late</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('center.submissions.download', $submission)); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Download</a>
                                </td>
                                <td>
                                    <?php if($submission->grade !== null): ?>
                                        <span class="badge bg-success"><?php echo e($submission->grade); ?> / <?php echo e($assignment->max_score); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-<?php echo e($submission->id); ?>">
                                        <i class="fas fa-check-square"></i> Grade
                                    </button>

                                    <!-- Grade Modal -->
                                    <div class="modal fade" id="gradeModal-<?php echo e($submission->id); ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="<?php echo e(route('center.submissions.grade', $submission)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Grade Submission: <?php echo e($submission->user->name); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Grade (Max: <?php echo e($assignment->max_score); ?>)</label>
                                                            <input type="number" name="grade" class="form-control" value="<?php echo e($submission->grade); ?>" min="0" max="<?php echo e($assignment->max_score); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Feedback</label>
                                                            <textarea name="feedback" class="form-control" rows="3"><?php echo e($submission->feedback); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Grade</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No submissions yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\assignments\submissions.blade.php ENDPATH**/ ?>