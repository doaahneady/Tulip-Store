<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'icon' => null, 'open' => false, 'subtitle' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'icon' => null, 'open' => false, 'subtitle' => null]); ?>
<?php foreach (array_filter((['title', 'icon' => null, 'open' => false, 'subtitle' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<details class="db4-collapse" <?php if($open): ?> open <?php endif; ?>>
    <summary class="db4-collapse-summary">
        <div class="db4-collapse-title">
            <?php if($icon): ?>
                <span class="db4-collapse-icon" aria-hidden="true"><i class="<?php echo e($icon); ?>"></i></span>
            <?php endif; ?>
            <div class="min-w-0">
                <div class="db4-collapse-heading"><?php echo e($title); ?></div>
                <?php if($subtitle): ?>
                    <div class="db4-collapse-subtitle"><?php echo e($subtitle); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <span class="db4-collapse-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
    </summary>
    <div class="db4-collapse-body">
        <?php echo e($slot); ?>

    </div>
</details>

<?php /**PATH E:\Tulip-Store\resources\views/components/dashboard/collapsible.blade.php ENDPATH**/ ?>