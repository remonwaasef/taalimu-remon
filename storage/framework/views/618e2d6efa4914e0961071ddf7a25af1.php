<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('center::partials._sidebar-next', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (! empty(trim($__env->yieldContent('page-title')))): ?>
        <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('title', null, []); ?> <?php echo $__env->yieldContent('page-title'); ?> <?php $__env->endSlot(); ?>
            <?php if (! empty(trim($__env->yieldContent('page-subtitle')))): ?>
                 <?php $__env->slot('subtitle', null, []); ?> <?php echo $__env->yieldContent('page-subtitle'); ?> <?php $__env->endSlot(); ?>
            <?php endif; ?>
            <?php if (! empty(trim($__env->yieldContent('page-actions')))): ?>
                 <?php $__env->slot('actions', null, []); ?> <?php echo $__env->yieldContent('page-actions'); ?> <?php $__env->endSlot(); ?>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
    <?php endif; ?>
    <?php echo $__env->yieldContent('panel-content'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/auto-save.js')); ?>"></script>
    <script src="<?php echo e(asset('js/instant-search.js')); ?>"></script>
    <script src="<?php echo e(asset('js/crash-recovery.js')); ?>"></script>
    <script src="<?php echo e(asset('js/status-indicators.js')); ?>"></script>
    <script src="<?php echo e(asset('js/image-compressor.js')); ?>"></script>
    <script src="<?php echo e(asset('js/keyboard-shortcuts.js')); ?>"></script>
    <?php echo $__env->make('center::partials.bug-report-widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    if (!e.target.checkValidity()) return;
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        const isDelete = e.target.querySelector('input[name="_method"][value="DELETE"]') != null;
                        const loadingText = isDelete ? 'جاري الحذف...' : 'جاري التنفيذ...';
                        submitBtn.style.minWidth = submitBtn.offsetWidth + 'px';
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mx-1"></i> ' + loadingText;
                    }, 0);
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-next', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/layouts/app-next.blade.php ENDPATH**/ ?>