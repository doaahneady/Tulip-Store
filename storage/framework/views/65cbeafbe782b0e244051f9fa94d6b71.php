
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
                <tr class="text-gray-500 bg-gray-50">
                    <th class="text-right py-3 px-4">المنتج</th>
                    <th class="text-right py-3 px-4">التاجر</th>
                    <th class="text-right py-3 px-4">المخزون</th>
                    <th class="text-right py-3 px-4">حد التنبيه</th>
                    <th class="text-right py-3 px-4">تواصل</th>
                    <th class="text-right py-3 px-4">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $traderName = $p->trader?->name ?? $p->store?->name ?? 'متجر توليب';
                        $traderPhone = $p->trader?->contact_phone ?? $p->store?->phone ?? '';
                        // Clean phone for WhatsApp (remove spaces, etc)
                        $cleanPhone = preg_replace('/[^0-9]/', '', $traderPhone);
                        // Add country code if missing (assuming SYR +963 if it starts with 09)
                        if (str_starts_with($cleanPhone, '09')) {
                            $cleanPhone = '963' . substr($cleanPhone, 1);
                        }
                    ?>
                    <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center border border-gray-200">
                                    <?php if(!empty($p->image)): ?>
                                        <img src="<?php echo e($p->image); ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fas fa-box text-gray-400"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-900 truncate"><?php echo e($p->name); ?></div>
                                    <div class="text-xs text-gray-500 truncate"><?php echo e($p->sku); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-gray-700 font-medium">
                            <?php echo e($traderName); ?>

                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                <?php echo e((int) $p->stock_quantity); ?>

                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-700 font-medium">
                            <?php echo e((int) $p->low_stock_threshold); ?>

                        </td>
                        <td class="py-4 px-4">
                            <?php if($cleanPhone): ?>
                                <a href="https://wa.me/<?php echo e($cleanPhone); ?>" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold text-xs bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                    <i class="fab fa-whatsapp text-sm"></i>
                                    واتساب
                                </a>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">لا يوجد رقم</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="<?php echo e(route('dashboard.admin.inventory.history', $p->id)); ?>" class="px-3 py-2 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-50 font-medium shadow-sm">
                                    <i class="fas fa-history ml-1"></i>
                                    السجل
                                </a>
                                <form method="POST" action="<?php echo e(route('dashboard.admin.inventory.restock', $p->id)); ?>" class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                                    <?php echo csrf_field(); ?>
                                    <input name="quantity" type="number" min="1" value="1" class="w-16 px-2 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200 font-bold" title="الكمية المراد توريدها">
                                    <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700 font-bold shadow-sm transition-all">
                                        توريد
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-500">لا يوجد منتجات ناقصة</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/inventory/alerts.blade.php ENDPATH**/ ?>