<?php $__env->startSection('content'); ?>
<?php $title = 'تنبيهات نقص المخزون'; $subtitle = 'المنتجات التي وصلت لحد التنبيه وإعادة التوريد'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة النقص</h3>
        <span class="text-sm text-gray-500"><?php echo e($lowStockProducts->count()); ?> منتج</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">#</th>
                    <th class="text-right py-2">المنتج</th>
                    <th class="text-right py-2">المخزون</th>
                    <th class="text-right py-2">حد التنبيه</th>
                    <th class="text-right py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t border-gray-100">
                        <td class="py-3 text-gray-700"><?php echo e($p->id); ?></td>
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
                                    <?php if(!empty($p->image)): ?>
                                        <img src="<?php echo e($p->image); ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fas fa-box text-gray-400"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 truncate"><?php echo e($p->name); ?></div>
                                    <div class="text-xs text-gray-500 truncate"><?php echo e($p->sku); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs bg-amber-100 text-amber-800"><?php echo e((int) $p->stock_quantity); ?></span>
                        </td>
                        <td class="py-3 text-gray-700"><?php echo e((int) $p->low_stock_threshold); ?></td>
                        <td class="py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="<?php echo e(route('dashboard.admin.inventory.history', $p->id)); ?>" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-50">السجل</a>
                                <form method="POST" action="<?php echo e(route('dashboard.admin.inventory.restock', $p->id)); ?>" class="flex items-center gap-2">
                                    <?php echo csrf_field(); ?>
                                    <input name="quantity" type="number" min="1" value="1" class="w-20 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                    <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">توريد</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500">لا يوجد منتجات ناقصة</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/inventory/alerts.blade.php ENDPATH**/ ?>