<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Assignment: <?php echo e($assignment->title); ?></h1>
        <div>
            <a href="<?php echo e(route('center.assignments.submissions', $assignment)); ?>" class="btn btn-info text-white me-2"><i class="fas fa-users"></i> View Submissions</a>
            <a href="<?php echo e(route('center.curriculum.edit', $assignment->lesson->section->course)); ?>" class="btn btn-secondary">Back to Curriculum</a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('center.assignments.update', $assignment)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo e($assignment->title); ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="datetime-local" name="due_date" class="form-control" value="<?php echo e($assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Score</label>
                        <input type="number" name="max_score" class="form-control" value="<?php echo e($assignment->max_score); ?>" min="1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description / Instructions</label>
                    <textarea name="description" class="form-control" rows="5"><?php echo e($assignment->description); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Assignment</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\assignments\edit.blade.php ENDPATH**/ ?>