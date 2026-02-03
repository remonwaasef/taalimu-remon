<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'options', 'label' => 'Filter']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'options', 'label' => 'Filter']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="dropdown">
    <button class="btn btn-light shadow-sm rounded-pill px-4 dropdown-toggle d-flex align-items-center justify-content-between" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="height: 48px; min-width: 150px;">
        <span><?php echo e(request($name) ? $options[request($name)] : $label); ?></span>
    </button>
    <ul class="dropdown-menu shadow border-0 mt-2 rounded-3">
        <li>
            <a class="dropdown-item <?php echo e(!request($name) ? 'active' : ''); ?>" href="<?php echo e(request()->fullUrlWithQuery([$name => null])); ?>">
                All
            </a>
        </li>
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a class="dropdown-item <?php echo e(request($name) == $value ? 'active' : ''); ?>" href="<?php echo e(request()->fullUrlWithQuery([$name => $value])); ?>">
                    <?php echo e($text); ?>

                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/components/ui/filter.blade.php ENDPATH**/ ?>