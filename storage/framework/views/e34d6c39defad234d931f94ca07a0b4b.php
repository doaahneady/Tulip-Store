<?php $__env->startSection('content'); ?>
<?php
    $categoriesCount = is_countable($categories ?? null) ? count($categories) : 0;
    $productsTotal = method_exists(($products ?? null), 'total') ? ($products->total() ?? 0) : 0;
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.admin.index')); ?>" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-black transition">
            <i class="fas fa-chart-pie"></i>
            <span>لوحة الإدارة</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.gifts')); ?>" class="inline-flex items-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-xl hover:bg-pink-700 transition">
            <i class="fas fa-gift"></i>
            <span>Tulip Gifts</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.mart')); ?>" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.mart.daily-prices.manage')); ?>" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">
            <i class="fas fa-tags"></i>
            <span>أسعار يومية</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.attendance')); ?>" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضور الموظفين</span>
        </a>
        <a href="<?php echo e(route('dashboard.administrative-approvals.manage')); ?>" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.roles')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-user-shield"></i>
            <span>Rules</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">التصنيفات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($categoriesCount)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-tags text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">المنتجات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($productsTotal)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-boxes text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">منتجات فعالة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format(method_exists(($products ?? null), 'getCollection') ? $products->getCollection()->where('is_active', true)->count() : 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">مخزون منخفض</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format(method_exists(($products ?? null), 'getCollection') ? $products->getCollection()->filter(fn ($p) => (int) ($p->stock_quantity ?? 0) <= (int) ($p->low_stock_threshold ?? 0))->count() : 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 xl:col-span-1">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">التصنيفات</h3>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('dashboard.admin.mart.categories.create')); ?>" class="btn btn-ghost btn-xs">
                    <i class="fas fa-plus"></i>
                    إضافة
                </a>
                <span class="text-sm text-gray-500"><?php echo e(number_format($categoriesCount)); ?></span>
            </div>
        </div>
        <div class="p-4">
            <?php if($categories === null): ?>
                <div class="text-center text-gray-500 py-8">جدول التصنيفات غير موجود</div>
            <?php elseif($categoriesCount === 0): ?>
                <div class="text-center text-gray-500 py-8">لا توجد بيانات</div>
            <?php else: ?>
                <div class="space-y-2">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $selected = (string) request('category_id') === (string) $cat->id; ?>
                        <div class="block p-3 rounded-xl border <?php if($selected): ?> border-indigo-400 bg-indigo-50 <?php else: ?> border-gray-200 hover:bg-gray-50 <?php endif; ?>">
                            <div class="flex items-center justify-between">
                                <a href="<?php echo e(route('dashboard.admin.mart', array_merge(request()->query(), ['category_id' => $cat->id]))); ?>" class="font-semibold text-gray-900">
                                    <?php echo e($cat->name); ?>

                                </a>
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo e(route('dashboard.admin.mart.categories.edit', $cat)); ?>" class="btn btn-ghost btn-xs">
                                        تعديل
                                    </a>
                                    <?php if(\Illuminate\Support\Facades\Schema::hasColumn('categories', 'is_active')): ?>
                                        <form method="POST" action="<?php echo e(route('dashboard.admin.mart.categories.toggle-active', $cat)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-ghost btn-xs">
                                                <?php echo e(($cat->is_active ?? false) ? 'تعطيل' : 'تفعيل'); ?>

                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('dashboard.admin.mart.categories.delete', $cat)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف التصنيف؟')">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1"><?php echo e($cat->slug); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('dashboard.admin.mart', array_diff_key(request()->query(), ['category_id' => true]))); ?>" class="block text-center text-sm text-indigo-600 mt-3">عرض الكل</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 xl:col-span-3">
        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h3 class="text-lg font-semibold text-gray-900">المنتجات</h3>
                <form method="GET" action="<?php echo e(route('dashboard.admin.mart')); ?>" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو SKU" class="form-input w-48 md:w-72">
                    <select name="category_id" class="form-select w-44">
                        <option value="">كل التصنيفات</option>
                        <?php $__currentLoopData = ($categories ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php if((string) request('category_id') === (string) $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="btn btn-ghost btn-sm">
                        <i class="fas fa-filter"></i>
                        تصفية
                    </button>
                    <a class="btn btn-secondary btn-sm" href="<?php echo e(route('dashboard.admin.export.products', array_merge(request()->query(), ['format' => 'csv']))); ?>">
                        <i class="fas fa-download"></i>
                        تصدير
                    </a>
                    <a href="<?php echo e(route('dashboard.admin.mart.products.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                        إضافة منتج
                    </a>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>SKU</th>
                        <th>التصنيف</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($products === null): ?>
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">جدول المنتجات غير موجود</td>
                        </tr>
                    <?php else: ?>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="font-semibold text-gray-900"><?php echo e($p->name); ?></td>
                                <td class="text-gray-600"><?php echo e($p->sku ?? '-'); ?></td>
                                <td class="text-gray-600"><?php echo e($p->category->name ?? '-'); ?></td>
                                <td><?php echo e(number_format((float) ($p->discount_price ?? $p->price), 2)); ?></td>
                                <td><?php echo e(number_format((int) ($p->stock_quantity ?? 0))); ?></td>
                                <td>
                                    <?php $active = (bool) ($p->is_active ?? true); ?>
                                    <span class="px-2 py-1 rounded text-xs <?php if($active): ?> bg-emerald-100 text-emerald-700 <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                        <?php echo e($active ? 'نشط' : 'غير نشط'); ?>

                                    </span>
                                </td>
                                <td class="text-gray-600"><?php echo e(optional($p->created_at)->format('Y-m-d')); ?></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="<?php echo e(route('dashboard.admin.mart.products.toggle-active', $p)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-ghost btn-xs">
                                                <?php echo e($active ? 'تعطيل' : 'تفعيل'); ?>

                                            </button>
                                        </form>
                                        <?php if(\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_featured')): ?>
                                            <form method="POST" action="<?php echo e(route('dashboard.admin.mart.products.toggle-featured', $p)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-ghost btn-xs">
                                                    <?php echo e(($p->is_featured ?? false) ? 'إلغاء تمييز' : 'تمييز'); ?>

                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" action="<?php echo e(route('dashboard.admin.mart.products.delete', $p)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف المنتج؟')">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500">لا توجد منتجات</td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4">
            <?php if(method_exists(($products ?? null), 'links')): ?>
                <?php echo e($products->links()); ?>

            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'إدارة المارت', 'subtitle' => 'إدارة قسم Mart (التصنيفات والمنتجات)'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/super-admin/mart.blade.php ENDPATH**/ ?>