<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'value', 'icon', 'color' => 'primary', 'change' => null, 'changeType' => 'up', 'subtitle' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'value', 'icon', 'color' => 'primary', 'change' => null, 'changeType' => 'up', 'subtitle' => null]); ?>
<?php foreach (array_filter((['title', 'value', 'icon', 'color' => 'primary', 'change' => null, 'changeType' => 'up', 'subtitle' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
$colors = [
    'primary' => 'from-primary-500 to-primary-700',
    'green' => 'from-emerald-500 to-emerald-700',
    'blue' => 'from-blue-500 to-blue-700',
    'orange' => 'from-orange-500 to-orange-700',
    'purple' => 'from-purple-500 to-purple-700',
    'pink' => 'from-pink-500 to-pink-700',
    'teal' => 'from-teal-500 to-teal-700',
    'red' => 'from-red-500 to-red-700',
];
$gradient = $colors[$color] ?? $colors['primary'];
$changeDisplay = null;
if ($change !== null) {
    if (is_array($change)) {
        $changeDisplay = $change['value'] ?? null;
        if (is_array($changeDisplay)) {
            $changeDisplay = null;
        }
    } else {
        $changeDisplay = $change;
    }
    if (is_string($changeDisplay) && trim($changeDisplay) === '0.0%') {
        $changeDisplay = null;
    }
}
?>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border border-gray-100 dark:border-gray-700 group">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1"><?php echo e($title); ?></p>
            <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white"><?php echo e($value); ?></h3>
            <?php if($subtitle): ?>
                <p class="text-gray-400 text-xs mt-1"><?php echo e($subtitle); ?></p>
            <?php endif; ?>
            <?php if($changeDisplay !== null && $changeDisplay !== ''): ?>
                <div class="flex items-center gap-1 mt-2">
                    <?php if($changeType === 'up'): ?>
                        <span class="text-emerald-500 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i> <?php echo e($changeDisplay); ?>

                        </span>
                    <?php elseif($changeType === 'down'): ?>
                        <span class="text-red-500 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-down text-xs"></i> <?php echo e($changeDisplay); ?>

                        </span>
                    <?php else: ?>
                        <span class="text-gray-400 text-sm font-medium"><?php echo e($changeDisplay); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?php echo e($gradient); ?> flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
            <i class="<?php echo e($icon); ?> text-white text-xl"></i>
        </div>
    </div>
</div>
<?php /**PATH D:\Tulip-Store\resources\views/components/dashboard/stat-card.blade.php ENDPATH**/ ?>