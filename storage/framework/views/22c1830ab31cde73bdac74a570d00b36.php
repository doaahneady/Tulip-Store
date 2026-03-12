
<?php $__env->startSection('content'); ?>
<?php $title = 'مؤشرات أداء المنتجات'; $subtitle = 'ترتيب المنتجات حسب المبيعات والطلب'; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">إجمالي المنتجات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($summary['total_products'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-boxes text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">منتجات نشطة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($summary['active_products'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-toggle-on text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">قريبة النفاد</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($summary['low_stock'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">منتهية المخزون</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($summary['out_of_stock'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h3 class="text-lg font-semibold text-gray-900">أداء المنتجات</h3>
        <form method="GET" action="<?php echo e(route('dashboard.vendor.product-performance-metrics')); ?>" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو SKU" class="form-input w-60">
            <button type="submit" class="btn btn-secondary btn-sm">بحث</button>
            <a href="<?php echo e(route('dashboard.vendor.index')); ?>" class="btn btn-ghost btn-sm">عودة</a>
        </form>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المنتج</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">SKU</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">السعر</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المخزون</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">عدد الطلبات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900"><?php echo e($p->name); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($p->sku ?? '-'); ?></td>
                        <td class="px-4 py-3"><?php echo e(number_format((float) ($p->price ?? 0), 2)); ?></td>
                        <td class="px-4 py-3"><?php echo e(number_format((int) ($p->stock_quantity ?? 0))); ?></td>
                        <td class="px-4 py-3"><?php echo e(number_format((int) ($p->order_items_count ?? 0))); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/vendor/product-performance-metrics.blade.php ENDPATH**/ ?>