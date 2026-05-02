

<?php $__env->startSection('title', __('sidebar.notifications')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><?php echo e(__('center::sidebar.notifications')); ?></h5>
                <?php if($notifications->count() > 0): ?>
                    <form action="<?php echo e(route('center.notifications.readAll')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="fas fa-check-double me-1"></i> <?php echo e(__('center::sidebar.mark_all_read')); ?>

                        </button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $titleKey = $notification->data['title'];
                        $iconColor = 'primary';
                        $iconClass = $notification->data['icon'] ?? 'fas fa-bell';
                        
                        // Custom logic for colors and translations
                        if (str_contains($titleKey, 'registered') || str_contains($titleKey, 'created')) {
                            $iconColor = 'success'; // Green for addition
                        } elseif (str_contains($titleKey, 'updated') || str_contains($titleKey, 'edited')) {
                            $iconColor = 'info';    // Blue/Info for updates
                        } elseif (str_contains($titleKey, 'deleted') || str_contains($titleKey, 'removed')) {
                            $iconColor = 'danger';  // Red for deletion
                        }
                        
                        // Translation lookup with fallback
                        $translatedTitle = __('center::sidebar.' . $titleKey);
                        if ($translatedTitle === 'center::sidebar.' . $titleKey) {
                            $translatedTitle = $titleKey;
                        }
                    ?>
                    <div class="p-4 border-bottom d-flex align-items-center gap-3 <?php echo e($notification->read_at ? '' : 'bg-light'); ?>">
                        <div class="rounded-circle bg-<?php echo e($iconColor); ?> bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                            <i class="<?php echo e($iconClass); ?> text-<?php echo e($iconColor); ?> fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">
                                        <?php echo e($translatedTitle); ?>

                                    </h6>
                                    <p class="mb-1 text-muted"><?php echo e($notification->data['message'] ?? ''); ?></p>
                                </div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">
                                    <?php echo e($notification->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                            <!-- Created By Info -->
                            <?php if(isset($notification->data['created_by'])): ?>
                                <div class="mt-1 d-flex align-items-center">
                                    <small class="text-muted me-1"><?php echo e(__('center::students.created_by')); ?>:</small>
                                    <span class="badge bg-light text-dark fw-normal border">
                                        <i class="fas fa-user-edit text-primary me-1" style="font-size: 0.7rem;"></i><?php echo e($notification->data['created_by']); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="mt-2">
                                <a href="<?php echo e(route('center.notifications.read', $notification->id)); ?>" class="btn btn-sm btn-link text-decoration-none p-0">
                                    <?php echo e(__('center::sidebar.details')); ?> <i class="fas fa-arrow-left ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <span class="fa-stack fa-2x text-muted opacity-50">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-bell-slash fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <h6 class="text-muted"><?php echo e(__('center::sidebar.no_notifications')); ?></h6>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white py-3">
                <?php echo e($notifications->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\notifications\index.blade.php ENDPATH**/ ?>