

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1><?php echo e(__('center::billing.title')); ?></h1>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header"><?php echo e(__('center::billing.current_subscription')); ?></div>
        <div class="card-body">
            <?php if($subscription): ?>
                <p><strong><?php echo e(__('center::billing.plan')); ?>:</strong> <?php echo e($subscription->package->name ?? __('center::billing.unknown_package')); ?></p>
                <p><strong><?php echo e(__('center::billing.status')); ?>:</strong> <?php echo e(ucfirst($subscription->status)); ?></p>
                <p><strong><?php echo e(__('center::billing.expires_at')); ?>:</strong> <?php echo e($subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : '-'); ?></p>
            <?php else: ?>
                <p class="text-danger"><?php echo e(__('center::billing.no_active_subscription')); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <h2><?php echo e(__('center::billing.available_plans')); ?></h2>
    <div class="row">
        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <?php
                            $transPkg = __('features.packages.' . $package->slug);
                            $pkgName = ($transPkg === 'features.packages.' . $package->slug) ? $package->name : $transPkg;
                        ?>
                        <h5 class="card-title"><?php echo e($pkgName); ?></h5>
                        <p class="card-text"><?php echo e($package->description); ?></p>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo e($package->price); ?> <?php echo e(get_currency_symbol()); ?> / <?php echo e($package->duration_in_days); ?> <?php echo e(__('center::billing.days')); ?></h6>
                        <ul>
                            <?php $__currentLoopData = $package->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $transFeat = __('features.' . $feature->code);
                                    $featName = ($transFeat === 'features.' . $feature->code) ? $feature->name : $transFeat;
                                ?>
                                <li><?php echo e($featName); ?>: <?php echo e($feature->pivot->value == -1 ? __('center::billing.unlimited') : $feature->pivot->value); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button class="btn btn-primary"><?php echo e(__('center::billing.subscribe')); ?></button>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\billing\index.blade.php ENDPATH**/ ?>