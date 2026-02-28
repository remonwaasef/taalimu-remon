

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4" style="padding-top: 140px; padding-bottom: 60px;">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-6">Privacy Policy</h1>
        <div class="prose prose-lg max-w-none">
            <p class="text-muted-foreground"><?php echo e(__('policies.privacy.last_updated')); ?>: <?php echo e(date('F d, Y')); ?></p>
            
            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.privacy.introduction.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.privacy.introduction.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.privacy.data_collection.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.privacy.data_collection.content')); ?></p>
                <ul class="list-disc list-inside text-muted-foreground space-y-2 mb-4">
                    <li><?php echo e(__('policies.privacy.data_collection.items.name')); ?></li>
                    <li><?php echo e(__('policies.privacy.data_collection.items.email')); ?></li>
                    <li><?php echo e(__('policies.privacy.data_collection.items.contact')); ?></li>
                    <li><?php echo e(__('policies.privacy.data_collection.items.usage')); ?></li>
                </ul>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.privacy.usage.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.privacy.usage.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.privacy.rights.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.privacy.rights.content')); ?></p>
                <ul class="list-disc list-inside text-muted-foreground space-y-2">
                    <li><?php echo e(__('policies.privacy.rights.items.access')); ?></li>
                    <li><?php echo e(__('policies.privacy.rights.items.rectification')); ?></li>
                    <li><?php echo e(__('policies.privacy.rights.items.deletion')); ?></li>
                    <li><?php echo e(__('policies.privacy.rights.items.portability')); ?></li>
                    <li><?php echo e(__('policies.privacy.rights.items.objection')); ?></li>
                </ul>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.privacy.security.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.privacy.security.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.privacy.contact.title')); ?></h2>
                <p class="text-muted-foreground"><?php echo e(__('policies.privacy.contact.content')); ?></p>
            </section>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/policies/privacy.blade.php ENDPATH**/ ?>