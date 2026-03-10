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

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 border border-gray-100 dark:border-gray-700 group h-full">
    <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-gray-500 dark:text-gray-400 text-xs font-semibold"><?php echo e($title); ?></p>
            <h3 class="text-xl lg:text-2xl font-black text-gray-800 dark:text-white leading-tight"><?php echo e($value); ?></h3>
            <?php if($subtitle): ?>
                <p class="text-gray-400 text-xs mt-1"><?php echo e($subtitle); ?></p>
            <?php endif; ?>
            <?php if($changeDisplay !== null && $changeDisplay !== ''): ?>
                <div class="flex items-center gap-1 mt-1.5">
                    <?php if($changeType === 'up'): ?>
                        <span class="text-emerald-600 text-xs font-semibold flex items-center gap-1">
                            <i class="fas fa-arrow-up text-xs"></i> <?php echo e($changeDisplay); ?>

                        </span>
                    <?php elseif($changeType === 'down'): ?>
                        <span class="text-red-600 text-xs font-semibold flex items-center gap-1">
                            <i class="fas fa-arrow-down text-xs"></i> <?php echo e($changeDisplay); ?>

                        </span>
                    <?php else: ?>
                        <span class="text-gray-500 text-xs font-semibold"><?php echo e($changeDisplay); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br <?php echo e($gradient); ?> flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform shrink-0">
            <i class="<?php echo e($icon); ?> text-white text-lg"></i>
        </div>
    </div>
</div>
<?php /**PATH D:\Tulip-Store\resources\views/components/dashboard/stat-card.blade.php ENDPATH**/ ?>