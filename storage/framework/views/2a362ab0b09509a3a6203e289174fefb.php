

<?php $__env->startSection('title', 'Subscription Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Subscription Plans</h1>
    </div>

    <?php if(session('info')): ?>
        <div class="alert alert-info"><?php echo e(session('info')); ?></div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 <?php echo e($tenant->subscribedToPrice($package->stripe_price_id) ? 'border-primary border-2' : ''); ?>">
                    <div class="card-body p-4">
                        <?php if($tenant->subscribedToPrice($package->stripe_price_id)): ?>
                            <span class="badge bg-primary rounded-pill mb-3">خطتك الحالية</span>
                        <?php endif; ?>
                        <h4 class="fw-bold mb-2"><?php echo e($package->name); ?></h4>
                        <div class="mb-3">
                            <span class="display-6 fw-black text-primary"><?php echo e(number_format($package->price, 0)); ?></span>
                            <span class="text-muted">ر.س / شهرياً</span>
                        </div>
                        <p class="text-muted small mb-4"><?php echo e($package->description); ?></p>
                        
                        <ul class="list-unstyled mb-4">
                            <?php $__currentLoopData = $package->display_features ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2 small">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <?php echo e($feature); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <?php if($tenant->subscribedToPrice($package->stripe_price_id)): ?>
                            <button class="btn btn-outline-primary w-100 rounded-pill disabled" disabled>منشط حالياً</button>
                        <?php elseif($package->stripe_price_id): ?>
                            <a href="<?php echo e(route('center.subscription.checkout', $package->id)); ?>" class="btn btn-primary w-100 rounded-pill">ترقية الخطة</a>
                        <?php else: ?>
                            <button class="btn btn-light w-100 rounded-pill disabled" disabled>غير متاح</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules/Center\resources/views/subscription/index.blade.php ENDPATH**/ ?>