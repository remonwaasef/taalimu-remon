

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4" style="padding-top: 140px; padding-bottom: 60px;">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-6">Terms of Service</h1>
        <div class="prose prose-lg max-w-none">
            <p class="text-muted-foreground"><?php echo e(__('policies.terms.last_updated')); ?>: <?php echo e(date('F d, Y')); ?></p>
            
            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.terms.acceptance.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.terms.acceptance.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.terms.services.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.terms.services.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.terms.user_obligations.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.terms.user_obligations.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.terms.liability.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.terms.liability.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.terms.contact.title')); ?></h2>
                <p class="text-muted-foreground"><?php echo e(__('policies.terms.contact.content')); ?></p>
            </section>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/policies/terms.blade.php ENDPATH**/ ?>