
<?php $__env->startSection('content'); ?>
<?php $title = 'موافقات المالية'; $subtitle = 'مراجعة الاستردادات والعمولات والرواتب وطلبات التحويل'; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">معاملات بانتظار الموافقة</h3>
            <span class="text-sm text-gray-500"><?php echo e($pendingTransactions->total()); ?></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">النوع</th>
                        <th class="text-right py-2">المبلغ</th>
                        <th class="text-right py-2">الطلب</th>
                        <th class="text-right py-2">تاريخ</th>
                        <th class="text-right py-2">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t border-gray-100 align-top">
                            <td class="py-3 text-gray-800">
                                <div class="font-semibold"><?php echo e($t->type); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(\Illuminate\Support\Str::limit($t->description, 60)); ?></div>
                            </td>
                            <td class="py-3 text-gray-900 font-semibold"><?php echo e(number_format((float) ($t->amount ?? 0), 2)); ?> <?php echo e($t->currency ?? 'USD'); ?></td>
                            <td class="py-3 text-gray-700"><?php echo e(optional($t->order)->order_number ?? '-'); ?></td>
                            <td class="py-3 text-gray-500"><?php echo e($t->created_at?->diffForHumans()); ?></td>
                            <td class="py-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <form method="POST" action="<?php echo e(route('dashboard.finance.approvals.transactions.approve', $t->id)); ?>" class="flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input name="notes" placeholder="ملاحظة" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                        <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">موافقة</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('dashboard.finance.approvals.transactions.reject', $t->id)); ?>" class="flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input name="notes" placeholder="سبب الرفض" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                        <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">رفض</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="py-10 text-center text-gray-500">لا توجد معاملات</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <?php echo e($pendingTransactions->withQueryString()->links()); ?>

        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">طلبات تحويل المتاجر</h3>
            <span class="text-sm text-gray-500"><?php echo e($pendingPayouts->total()); ?></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">المتجر</th>
                        <th class="text-right py-2">المبلغ</th>
                        <th class="text-right py-2">تاريخ</th>
                        <th class="text-right py-2">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t border-gray-100 align-top">
                            <td class="py-3 text-gray-800 font-semibold"><?php echo e(optional($p->store)->name ?? ('Store #'.$p->store_id)); ?></td>
                            <td class="py-3 text-gray-900 font-semibold"><?php echo e(number_format((float) ($p->amount ?? 0), 2)); ?></td>
                            <td class="py-3 text-gray-500"><?php echo e($p->created_at?->diffForHumans()); ?></td>
                            <td class="py-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <form method="POST" action="<?php echo e(route('dashboard.finance.approvals.payouts.approve', $p->id)); ?>" class="flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input name="notes" placeholder="ملاحظة" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                        <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">موافقة</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('dashboard.finance.approvals.payouts.reject', $p->id)); ?>" class="flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input name="notes" placeholder="سبب الرفض" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                        <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">رفض</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="py-10 text-center text-gray-500">لا توجد طلبات</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <?php echo e($pendingPayouts->withQueryString()->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/finance/approvals.blade.php ENDPATH**/ ?>