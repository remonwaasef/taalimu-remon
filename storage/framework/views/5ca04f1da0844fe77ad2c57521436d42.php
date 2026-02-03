

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::instructors.title')); ?></h2>
        <a href="<?php echo e(route('center.instructors.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> <?php echo e(__('center::instructors.add_new')); ?>

        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <?php if (isset($component)) { $__componentOriginal0d0ae8bef4b4e146c2af1b2494139103 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.search','data' => ['action' => ''.e(route('center.instructors.index')).'','placeholder' => ''.e(__('center::instructors.search_placeholder')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => ''.e(route('center.instructors.index')).'','placeholder' => ''.e(__('center::instructors.search_placeholder')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103)): ?>
<?php $attributes = $__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103; ?>
<?php unset($__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d0ae8bef4b4e146c2af1b2494139103)): ?>
<?php $component = $__componentOriginal0d0ae8bef4b4e146c2af1b2494139103; ?>
<?php unset($__componentOriginal0d0ae8bef4b4e146c2af1b2494139103); ?>
<?php endif; ?>
                </div>
            </div>

            <!-- Instructors Table -->
            <div class="table-responsive pb-5">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start"><?php echo e(__('center::instructors.name')); ?></th>
                            <th class="border-0"><?php echo e(__('center::instructors.specialization')); ?></th>
                            <th class="border-0">بيانات الاتصال</th>
                            <th class="border-0"><?php echo e(__('center::instructors.courses_count')); ?></th>
                            <th class="border-0 rounded-end"><?php echo e(__('center::instructors.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($instructor->image): ?>
                                            <img src="<?php echo e(Storage::url($instructor->image)); ?>" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;" alt="<?php echo e($instructor->name); ?>">
                                        <?php else: ?>
                                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <?php echo e(substr($instructor->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="fw-bold"><?php echo e($instructor->name); ?></div>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo e($instructor->specialization ?? '-'); ?></td>
                                <td>
                                    <?php if($instructor->email): ?>
                                        <div class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i> <?php echo e($instructor->email); ?></div>
                                    <?php endif; ?>
                                    <?php if($instructor->phone): ?>
                                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i> <?php echo e($instructor->phone); ?></div>
                                    <?php endif; ?>
                                    <?php if(!$instructor->email && !$instructor->phone): ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?php echo e($instructor->courses_count ?? 0); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.instructors.edit', $instructor->id)); ?>"><?php echo e(__('center::instructors.edit')); ?></a></li>
                                            <li>
                                                <form action="<?php echo e(route('center.instructors.destroy', $instructor->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من عملية الحذف؟');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item text-danger action-delete">
                                                        <?php echo e(__('center::instructors.delete')); ?>

                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted"><?php echo e(__('center::instructors.no_instructors')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($instructors->links('components.ui.pagination')); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/instructors/index.blade.php ENDPATH**/ ?>