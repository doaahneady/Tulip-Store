<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'icon' => 'fas fa-chart-bar', 'chartId']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'icon' => 'fas fa-chart-bar', 'chartId']); ?>
<?php foreach (array_filter((['title', 'icon' => 'fas fa-chart-bar', 'chartId']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="<?php echo e($icon); ?> text-primary-500"></i>
            <?php echo e($title); ?>

        </h3>
        <?php echo e($actions ?? ''); ?>

    </div>
    <div class="relative" style="height: clamp(160px, 22vh, 220px);">
        <canvas id="<?php echo e($chartId); ?>"></canvas>
    </div>
</div>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/components/dashboard/chart-card.blade.php ENDPATH**/ ?>