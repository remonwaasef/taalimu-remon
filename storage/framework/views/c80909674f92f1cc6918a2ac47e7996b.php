

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><?php echo e(__('center::courses.title')); ?></h2>
        <a href="<?php echo e(route('center.courses.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> <?php echo e(__('center::courses.add_new')); ?>

        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Search & Filter -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <?php if (isset($component)) { $__componentOriginal0d0ae8bef4b4e146c2af1b2494139103 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d0ae8bef4b4e146c2af1b2494139103 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.search','data' => ['action' => ''.e(route('center.courses.index')).'','placeholder' => ''.e(__('center::courses.search_placeholder')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => ''.e(route('center.courses.index')).'','placeholder' => ''.e(__('center::courses.search_placeholder')).'']); ?>
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
                <div class="col-md-3">
                    <?php if (isset($component)) { $__componentOriginal5964f3cfb7dbda62d89b754b9c63232a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.filter','data' => ['name' => 'status','options' => ['published' => __('center::courses.status_published'), 'draft' => __('center::courses.status_draft'), 'archived' => __('center::courses.status_archived')],'label' => ''.e(__('center::courses.status_label')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.filter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['published' => __('center::courses.status_published'), 'draft' => __('center::courses.status_draft'), 'archived' => __('center::courses.status_archived')]),'label' => ''.e(__('center::courses.status_label')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a)): ?>
<?php $attributes = $__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a; ?>
<?php unset($__attributesOriginal5964f3cfb7dbda62d89b754b9c63232a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5964f3cfb7dbda62d89b754b9c63232a)): ?>
<?php $component = $__componentOriginal5964f3cfb7dbda62d89b754b9c63232a; ?>
<?php unset($__componentOriginal5964f3cfb7dbda62d89b754b9c63232a); ?>
<?php endif; ?>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="table-responsive pb-5" style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start"><?php echo e(__('center::courses.course_name')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.instructor')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.schedules')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.price')); ?></th>
                            <th class="border-0"><?php echo e(__('center::courses.status')); ?></th>
                            <th class="border-0 rounded-end"><?php echo e(__('center::courses.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($course->image): ?>
                                            <img src="<?php echo e(Storage::url($course->image)); ?>" class="rounded-3 me-3" style="width: 48px; height: 48px; object-fit: cover;" alt="<?php echo e($course->title); ?>">
                                        <?php else: ?>
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                📚
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold"><?php echo e($course->title); ?></div>
                                            <small class="text-muted"><?php echo e(Str::limit($course->description, 30)); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo e($course->instructor->name ?? __('center::courses.not_specified')); ?></td>
                                <td>
                                    <?php if($course->schedules->count() > 0): ?>
                                        <div class="<?php echo e(($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown'); ?>">
                                            <button class="btn btn-light btn-sm rounded-pill border shadow-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="far fa-calendar-alt text-primary"></i>
                                                <span class="fw-bold"><?php echo e($course->schedules->count()); ?> مواعيد</span>
                                            </button>
                                            <div class="dropdown-menu border-0 shadow-lg p-2 rounded-4" style="min-width: 250px;">
                                                <h6 class="dropdown-header text-primary fw-bold mb-2">جدول المواعيد</h6>
                                                <div class="d-flex flex-column gap-2">
                                                    <?php $__currentLoopData = $course->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $days = [
                                                                0 => 'الأحد', 1 => 'الاثنين', 2 => 'الثلاثاء', 
                                                                3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'
                                                            ];
                                                            $dayName = $days[$schedule->day_of_week] ?? $schedule->day_of_week;
                                                            $start = \Carbon\Carbon::parse($schedule->start_time)->format('h:i A');
                                                            $end = \Carbon\Carbon::parse($schedule->end_time)->format('h:i A');
                                                        ?>
                                                        <div class="d-flex align-items-center bg-light rounded-3 p-2">
                                                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm text-primary me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                                                                <i class="fas fa-calendar-day fa-sm"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?php echo e($dayName); ?></div>
                                                                <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                                                    <span><?php echo e($start); ?> - <?php echo e($end); ?></span>
                                                                    <?php if($schedule->classroom): ?>
                                                                        <span class="vr mx-1"></span>
                                                                        <i class="fas fa-map-marker-alt text-danger"></i> <?php echo e($schedule->classroom->name); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic"><?php echo e(__('center::courses.not_specified')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-success"><?php echo e(number_format($course->price, 2)); ?> <?php echo e(__('center::courses.currency')); ?></td>
                                <td>
                                    <?php
                                        $badges = [
                                            'published' => 'success',
                                            'draft' => 'secondary',
                                            'archived' => 'warning'
                                        ];
                                        $labels = [
                                            'published' => __('center::courses.status_published'),
                                            'draft' => __('center::courses.status_draft'),
                                            'archived' => __('center::courses.status_archived')
                                        ];
                                    ?>
                                    <span class="badge bg-<?php echo e($badges[$course->status] ?? 'secondary'); ?> bg-opacity-10 text-<?php echo e($badges[$course->status] ?? 'secondary'); ?> rounded-pill px-3">
                                        <?php echo e($labels[$course->status] ?? $course->status); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="<?php echo e(($loop->remaining < 2 && $courses->count() > 2) ? 'dropup' : 'dropdown'); ?>">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.courses.show', $course->id)); ?>"><i class="fas fa-eye me-2 text-muted"></i> <?php echo e(__('center::courses.view')); ?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.courses.show', $course->id)); ?>"><i class="fas fa-user-plus me-2 text-success"></i> إضافة طالب للدورة</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.courses.edit', $course->id)); ?>"><i class="fas fa-edit me-2 text-muted"></i> <?php echo e(__('center::courses.edit')); ?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo e(route('center.curriculum.edit', $course->id)); ?>"><i class="fas fa-book-open me-2 text-muted"></i> <?php echo e(__('center::courses.content')); ?></a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="<?php echo e(route('center.courses.destroy', $course->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('center::courses.delete_confirm')); ?>');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> <?php echo e(__('center::courses.delete')); ?>

                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted"><?php echo e(__('center::courses.no_courses')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($courses->links('components.ui.pagination')); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/courses/index.blade.php ENDPATH**/ ?>