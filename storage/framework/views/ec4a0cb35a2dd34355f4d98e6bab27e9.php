<?php $__env->startSection('content'); ?>
<?php $title = 'المنتجات'; $subtitle = 'استعراض منتجات المتجر والبحث بالاسم أو ID'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="<?php echo e(route('dashboard.cs.products')); ?>" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو SKU أو ID" class="w-72 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">بحث</button>
        </div>
        <a href="<?php echo e(route('dashboard.cs.products')); ?>" class="text-sm text-indigo-600 hover:underline">إعادة ضبط</a>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">المنتج</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">السعر</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">المخزون</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">عرض</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">تعديل</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">حذف</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"><?php echo e($p->id); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-semibold"><?php echo e($p->name); ?></div>
                            <div class="text-xs text-gray-500">
                                <?php echo e($p->category?->name ?? '—'); ?> • <?php echo e($p->store?->name ?? '—'); ?>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo e($p->sku ?? '—'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($p->discount_price ?? $p->price ?? '—'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <?php if(\Illuminate\Support\Facades\Schema::hasColumn('products', 'stock_quantity')): ?>
                                <?php echo e((int) ($p->stock_quantity ?? 0)); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php
                                $status = $p->status ?? null;
                                $isActive = \Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active') ? (bool) ($p->is_active ?? false) : null;
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                <?php echo e($isActive === true ? 'bg-emerald-100 text-emerald-700' : ($status === 'active' || $status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700')); ?>">
                                <?php echo e($status ?? ($isActive === true ? 'active' : '—')); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('product.show', ['id' => $p->id])); ?>" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">
                                <i class="fas fa-eye"></i>
                                <span>فتح</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('dashboard.cs.products.edit', $p)); ?>" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                <i class="fas fa-pen"></i>
                                <span>تعديل</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <form method="POST" action="<?php echo e(route('dashboard.cs.products.delete', $p)); ?>" onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟');">
                                <?php echo csrf_field(); ?>
                                <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                    <i class="fas fa-trash"></i>
                                    <span>حذف</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500">لا توجد منتجات</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-6">
        <?php echo e($products->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/cs/products.blade.php ENDPATH**/ ?>