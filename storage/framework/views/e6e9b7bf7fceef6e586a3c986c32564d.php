

<?php $__env->startSection('page-title', __('center::questions.title')); ?>
<?php $__env->startSection('page-subtitle', __('center::questions.subtitle')); ?>

<?php $__env->startSection('page-actions'); ?>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="fas fa-tags me-2"></i><?php echo e(__('center::questions.categories')); ?>

    </button>
    <a href="<?php echo e(route('center.questions.create')); ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i><?php echo e(__('center::questions.new_question')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4"><?php echo e(__('center::questions.content')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::questions.category')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::questions.difficulty')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::questions.points')); ?></th>
                                    <th class="border-0"><?php echo e(__('center::questions.type')); ?></th>
                                    <th class="border-0 text-end pe-4"><?php echo e(__('center::questions.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="text-dark fw-semibold text-truncate" style="max-width: 300px;"><?php echo e($question->content); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark rounded-pill px-3"><?php echo e($question->category->name ?? __('center::questions.uncategorized')); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $diffColor = [
                                                'easy' => 'success',
                                                'medium' => 'warning',
                                                'hard' => 'danger'
                                            ][$question->difficulty] ?? 'secondary';
                                            
                                            $diffLabel = [
                                                'easy' => __('center::questions.easy'),
                                                'medium' => __('center::questions.medium'),
                                                'hard' => __('center::questions.hard')
                                            ][$question->difficulty] ?? $question->difficulty;
                                        ?>
                                        <span class="badge bg-<?php echo e($diffColor); ?> bg-opacity-10 text-<?php echo e($diffColor); ?> rounded-pill px-3">
                                            <?php echo e($diffLabel); ?>

                                        </span>
                                    </td>
                                    <td><span class="fw-bold text-primary"><?php echo e($question->points); ?></span><?php echo e(__('center::questions.points_suffix')); ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo e($question->type == 'mcq' ? __('center::questions.mcq') : __('center::questions.true_false')); ?>

                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?php echo e(route('center.questions.edit', $question)); ?>" class="btn btn-light btn-sm rounded-circle" title="<?php echo e(__('center::questions.edit')); ?>">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <form action="<?php echo e(route('center.questions.destroy', $question)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('center::questions.delete_confirm')); ?>')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-light btn-sm rounded-circle" title="<?php echo e(__('center::questions.delete')); ?>">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="<?php echo e(asset('assets/img/empty-box.png')); ?>" class="mb-3" style="width: 80px; opacity: 0.5;">
                                        <p class="text-muted"><?php echo e(__('center::questions.no_questions')); ?></p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($questions->hasPages()): ?>
                <div class="card-footer bg-white border-0 py-3">
                    <?php echo e($questions->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><?php echo e(__('center::questions.manage_categories')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('center.questions.categories.store')); ?>" method="POST" class="mb-4">
                    <?php echo csrf_field(); ?>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control rounded-start-pill" placeholder="<?php echo e(__('center::questions.category_placeholder')); ?>" required>
                                <button class="btn btn-primary rounded-end-pill px-4" type="submit"><?php echo e(__('center::questions.add')); ?></button>
                    </div>
                </form>
                
                <h6 class="fw-bold small text-muted mb-3 text-uppercase"><?php echo e(__('center::questions.existing_categories')); ?></h6>
                <div class="d-flex flex-wrap gap-2">
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="badge bg-light text-dark rounded-pill py-2 px-3 border">
                            <?php echo e($category->name); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="small text-muted"><?php echo e(__('center::questions.no_categories')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\questions\index.blade.php ENDPATH**/ ?>