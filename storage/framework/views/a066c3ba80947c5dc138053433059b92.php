

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4" style="padding-top: 140px; padding-bottom: 60px;">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-6">Cookie Policy</h1>
        <div class="prose prose-lg max-w-none">
            <p class="text-muted-foreground"><?php echo e(__('policies.cookies.last_updated')); ?>: <?php echo e(date('F d, Y')); ?></p>
            
            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.cookies.what.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.cookies.what.content')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.cookies.types.title')); ?></h2>
                
                <h3 class="text-xl font-semibold text-foreground mt-6 mb-3"><?php echo e(__('gdpr.settings.essential.title')); ?></h3>
                <p class="text-muted-foreground mb-4"><?php echo e(__('gdpr.settings.essential.description')); ?></p>
                
                <h3 class="text-xl font-semibold text-foreground mt-6 mb-3"><?php echo e(__('gdpr.settings.analytics.title')); ?></h3>
                <p class="text-muted-foreground mb-4"><?php echo e(__('gdpr.settings.analytics.description')); ?></p>
                
                <h3 class="text-xl font-semibold text-foreground mt-6 mb-3"><?php echo e(__('gdpr.settings.marketing.title')); ?></h3>
                <p class="text-muted-foreground mb-4"><?php echo e(__('gdpr.settings.marketing.description')); ?></p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4"><?php echo e(__('policies.cookies.manage.title')); ?></h2>
                <p class="text-muted-foreground mb-4"><?php echo e(__('policies.cookies.manage.content')); ?></p>
                <button onclick="openCookieSettings()" class="px-6 py-3 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors">
                    <?php echo e(__('gdpr.banner.settings')); ?>

                </button>
            </section>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\resources\views/policies/cookies.blade.php ENDPATH**/ ?>