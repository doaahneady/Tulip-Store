
<?php $__env->startSection('content'); ?>
<?php $title = 'لوحة السائق'; $subtitle = 'طلباتي المعيّنة وحالة الاستلام'; ?>

<?php if(session('success')): ?>
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-black text-gray-900">مرحباً <?php echo e(auth('employee')->user()->full_name ?? 'سائق'); ?></h3>
            <p class="text-sm text-gray-500">سجّل الاستلام ثم أكمِل التسليم مع توقيعك وتوقيع العميل — تُحفظ على الفاتورة.</p>
        </div>
        <div class="text-sm text-gray-600">
            <?php if($driver): ?>
                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">سائق #<?php echo e($driver->id); ?></span>
            <?php else: ?>
                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700">لا يوجد ملف سائق مرتبط بحسابك</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
    <h3 class="text-base font-black text-gray-800 mb-3">الطلبات المعيّنة</h3>
    <div class="overflow-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-3 py-2 text-right">رقم الطلب</th>
                    <th class="px-3 py-2 text-right">العميل</th>
                    <th class="px-3 py-2 text-right">الحالة</th>
                    <th class="px-3 py-2 text-right">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t border-gray-100 align-top">
                        <td class="px-3 py-2">#<?php echo e($o->order_number ?? $o->id); ?></td>
                        <td class="px-3 py-2"><?php echo e($o->recipient_name ?? $o->user->name ?? '—'); ?></td>
                        <td class="px-3 py-2"><?php echo e($o->status ?? '—'); ?></td>
                        <td class="px-3 py-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="<?php echo e(route('dashboard.driver.orders.show', $o)); ?>" class="inline-flex items-center gap-1 bg-sky-600 text-white px-3 py-1.5 rounded-lg hover:bg-sky-700">
                                    <i class="fas fa-info-circle"></i>
                                    معلومات
                                </a>
                                <?php if(! in_array($o->status ?? '', ['out_for_delivery', 'delivered', 'done'])): ?>
                                    <form method="POST" action="<?php echo e(route('dashboard.driver.orders.receive', $o)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700">
                                            <i class="fas fa-check"></i>
                                            استلام الطلب
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">لا توجد طلبات معيّنة حالياً</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/driver/index.blade.php ENDPATH**/ ?>