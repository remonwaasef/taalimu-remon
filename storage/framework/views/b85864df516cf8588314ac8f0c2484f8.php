<?php $__env->startSection('page-title', __('center::instructors.title')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.instructors.create')); ?>" class="btn btn-primary shadow-sm">
        <span class="me-2">+</span> <?php echo e(__('center::instructors.add_new')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #ecfdf5; color: #064e3b;">
                <div class="card-body p-4">
                    <h6 class="opacity-75 small fw-bold" style="color: #065f46;"><?php echo e(__('center::instructors.total_instructors')); ?></h6>
                    <h2 class="fw-bold mb-0"><?php echo e($instructors->total()); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="text-muted small fw-bold"><?php echo e(__('center::instructors.available_balance_alt')); ?></h6>
                    <h2 class="fw-bold mb-0"><?php echo e($activeCount); ?></h2>
                </div>
            </div>
        </div>
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
                            <th class="border-0"><?php echo e(__('center::instructors.status')); ?></th>
                            <th class="border-0"><?php echo e(__('center::instructors.phone')); ?></th>
                            <th class="border-0"><?php echo e(__('center::instructors.courses_count')); ?></th>
                            <th class="border-0 rounded-end"><?php echo e(__('center::instructors.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <?php if($instructor->image): ?>
                                                <img src="<?php echo e(Storage::url($instructor->image)); ?>" 
                                                     class="rounded-circle border shadow-sm" 
                                                     style="width: 45px; height: 45px; object-fit: cover;" 
                                                     alt="<?php echo e($instructor->name); ?>"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <?php endif; ?>
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                 style="width: 45px; height: 45px; <?php echo e($instructor->image ? 'display: none;' : ''); ?>">
                                                <?php echo e(mb_substr($instructor->name, 0, 1)); ?>

                                            </div>
                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('center.instructors.show', $instructor->id)); ?>" class="fw-bold text-dark text-decoration-none hover-primary"><?php echo e($instructor->name); ?></a>
                                            <?php if($instructor->hiring_date): ?>
                                                <small class="text-muted" style="font-size: 0.7rem;"><?php echo e(__('center::instructors.hired_on')); ?> <?php echo e($instructor->hiring_date->format('Y/m/d')); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo e($instructor->specialization ?? '-'); ?></td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'on_hold' => 'danger'
                                        ];
                                        $color = $statusColors[$instructor->status] ?? 'info';
                                    ?>
                                    <span class="badge bg-<?php echo e($color); ?> bg-opacity-10 text-<?php echo e($color); ?> rounded-pill px-3 fw-bold" style="font-size: 0.75rem;">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        <?php echo e(__('center::instructors.' . $instructor->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($instructor->email): ?>
                                        <div class="small text-muted mb-1"><i class="far fa-envelope me-1"></i> <?php echo e($instructor->email); ?></div>
                                    <?php endif; ?>
                                    <?php if($instructor->phone): ?>
                                        <div class="small text-muted"><i class="fas fa-phone-alt me-1"></i> <?php echo e($instructor->phone); ?></div>
                                    <?php endif; ?>
                                    <?php if(!$instructor->email && !$instructor->phone): ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?php echo e($instructor->courses_count ?? 0); ?></td>
                                <td class="text-end px-4">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-light rounded-circle shadow-none" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4">
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('center.instructors.show', $instructor->id)); ?>"><i class="far fa-eye me-2 text-primary opacity-75"></i> <?php echo e(__('center::instructors.show')); ?></a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1" href="<?php echo e(route('center.instructors.edit', $instructor->id)); ?>"><i class="far fa-edit me-2 text-success opacity-75"></i> <?php echo e(__('center::instructors.edit')); ?></a></li>
                                            <li><hr class="dropdown-divider opacity-10"></li>
                                            <li>
                                                <form action="<?php echo e(route('center.instructors.destroy', $instructor->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('<?php echo e(__('center::instructors.confirm_delete_instructor')); ?>');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item rounded-3 text-danger mb-0">
                                                        <i class="fas fa-trash-alt me-2 opacity-75"></i> <?php echo e(__('center::instructors.delete')); ?>

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

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\instructors\index.blade.php ENDPATH**/ ?>