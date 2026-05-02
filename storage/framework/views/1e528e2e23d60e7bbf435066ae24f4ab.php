

<?php $__env->startSection('page-title', __('center::classrooms.title')); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('center.classrooms.create')); ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i> <?php echo e(__('center::classrooms.add_new')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Classrooms Table -->
            <div class="table-responsive" style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start"><?php echo e(__('center::classrooms.name')); ?></th>
                            <th class="border-0"><?php echo e(__('center::classrooms.capacity')); ?></th>
                            <th class="border-0"><?php echo e(__('center::classrooms.assets_count')); ?></th>
                            <th class="border-0"><?php echo e(__('center::classrooms.created_at')); ?></th>
                            <th class="border-0 rounded-end"><?php echo e(__('center::classrooms.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 shadow-sm border border-2 border-white" style="width: 40px; height: 40px; background-color: <?php echo e($classroom->color ?? '#435ebe'); ?>; color: white; font-size: 1.2rem;">
                                            <?php if($classroom->type == 'lab'): ?> 💻 <?php elseif($classroom->type == 'virtual'): ?> 🌐 <?php else: ?> 🏢 <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo e($classroom->name); ?></div>
                                            <span class="badge bg-light text-muted border-0 p-0" style="font-size: 0.7rem;">
                                                <?php echo e($classroom->type == 'lab' ? __('center::messages.blade_0221') : ($classroom->type == 'virtual' ? __('center::messages.blade_0222') : __('center::messages.blade_0223'))); ?>

                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($classroom->capacity ? trans_choice('center::classrooms.students_count', $classroom->capacity, ['count' => $classroom->capacity]) : __('center::classrooms.not_specified')); ?></td>
                                <td>
                                    <?php if($classroom->assets->count() > 0): ?>
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 250px;">
                                            <?php $__currentLoopData = $classroom->assets->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $icon = 'fa-box';
                                                    $color = 'info';
                                                    if (Str::contains($asset->name, [__('center::messages.blade_0224'), 'Projector'])) { $icon = 'fa-video'; $color = 'primary'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0225'), 'AC'])) { $icon = 'fa-snowflake'; $color = 'info'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0226'), 'TV'])) { $icon = 'fa-tv'; $color = 'dark'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0227')])) { $icon = 'fa-chalkboard'; $color = 'secondary'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0228')])) { $icon = 'fa-video-slash'; $color = 'danger'; }
                                                    elseif (Str::contains($asset->name, [__('center::messages.blade_0229')])) { $icon = 'fa-volume-up'; $color = 'warning'; }
                                                ?>
                                                <span class="badge bg-<?php echo e($color); ?> bg-opacity-10 text-<?php echo e($color); ?> border border-<?php echo e($color); ?> border-opacity-25 py-1 px-2" style="font-size: 0.65rem;" title="<?php echo e($asset->name); ?>">
                                                    <i class="fas <?php echo e($icon); ?> me-1"></i> <?php echo e($asset->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($classroom->assets->count() > 4): ?>
                                                <span class="badge bg-light text-muted border py-1 px-1" style="font-size: 0.65rem;">
                                                    +<?php echo e($classroom->assets->count() - 4); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">---</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?php echo e($classroom->created_at->format('Y-m-d')); ?></td>
                                <td>
                                    <div class="<?php echo e(($loop->remaining < 2 && $classrooms->count() > 2) ? 'dropup' : 'dropdown'); ?>">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.classrooms.show', $classroom)); ?>"><i class="fas fa-eye me-2 text-primary"></i> <?php echo e(__('center::classrooms.view_schedule')); ?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.assets.create', ['classroom_id' => $classroom->id])); ?>"><i class="fas fa-plus me-2 text-info"></i> <?php echo e(__('center::classrooms.add_asset')); ?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.classrooms.edit', $classroom)); ?>"><i class="fas fa-edit me-2 text-warning"></i> <?php echo e(__('center::classrooms.edit')); ?></a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="<?php echo e(route('center.classrooms.destroy', $classroom)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('center::classrooms.confirm_delete')); ?>')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button class="dropdown-item text-danger"><?php echo e(__('center::classrooms.delete')); ?></button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted"><?php echo e(__('center::classrooms.empty')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($classrooms->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\classrooms\index.blade.php ENDPATH**/ ?>