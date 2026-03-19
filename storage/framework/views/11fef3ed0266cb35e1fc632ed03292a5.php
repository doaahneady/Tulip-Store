
<?php $__env->startSection('content'); ?>
<?php $title = 'إدارة الطلبات'; $subtitle = 'نظرة عامة على جميع الطلبات مع فلاتر وإجراءات'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm text-gray-600">بحث</label>
            <input name="search" value="<?php echo e(request('search')); ?>" class="w-full px-3 py-2 border rounded-lg" placeholder="رقم الطلب أو اسم العميل">
        </div>
        <div>
            <label class="text-sm text-gray-600">الحالة</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg">
                <option value="">الكل</option>
                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php if(request('status')===$st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">حالة الدفع</label>
            <select name="payment_status" class="w-full px-3 py-2 border rounded-lg">
                <option value="">الكل</option>
                <?php $__currentLoopData = $paymentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($ps); ?>" <?php if(request('payment_status')===$ps): echo 'selected'; endif; ?>><?php echo e($ps); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">تطبيق</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">قائمة الطلبات</h3>
        <span class="text-xs text-gray-500">عدد: <?php echo e($orders->total()); ?></span>
    </div>
    <div class="p-6 overflow-x-auto">
        <table class="table-auto w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="px-3 py-2">رقم الطلب</th>
                    <th class="px-3 py-2">العميل</th>
                    <th class="px-3 py-2">المتجر</th>
                    <th class="px-3 py-2">الحالة</th>
                    <th class="px-3 py-2">الدفع</th>
                    <th class="px-3 py-2">السائق</th>
                    <th class="px-3 py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t">
                        <td class="px-3 py-2"><?php echo e($order->order_number ?? ('#'.$order->id)); ?></td>
                        <td class="px-3 py-2"><?php echo e($order->user->name ?? 'ضيف'); ?></td>
                        <td class="px-3 py-2"><?php echo e($order->store->name ?? '-'); ?></td>
                        <td class="px-3 py-2">
                            <form method="POST" action="<?php echo e(route('dashboard.admin.orders.update-status', $order)); ?>" class="flex gap-2">
                                <?php echo csrf_field(); ?>
                                <select name="status" class="px-2 py-1 border rounded">
                                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($st); ?>" <?php if($order->status === $st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <select name="payment_status" class="px-2 py-1 border rounded">
                                    <option value="">بدون تغيير</option>
                                    <?php $__currentLoopData = $paymentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ps); ?>" <?php if(($order->payment_status ?? '') === $ps): echo 'selected'; endif; ?>><?php echo e($ps); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button class="bg-blue-600 text-white px-3 py-1 rounded">تحديث</button>
                            </form>
                        </td>
                        <td class="px-3 py-2"><?php echo e($order->payment_status ?? '-'); ?></td>
                        <td class="px-3 py-2"><?php echo e($order->assigned_driver_id ? ('#'.$order->assigned_driver_id) : '-'); ?></td>
                        <td class="px-3 py-2">
                            <form method="POST" action="<?php echo e(route('dashboard.admin.orders.override-assignment', $order)); ?>" class="flex gap-2">
                                <?php echo csrf_field(); ?>
                                <input type="number" name="driver_id" class="px-2 py-1 border rounded w-24" placeholder="ID سائق" value="<?php echo e($order->assigned_driver_id); ?>">
                                <button class="bg-orange-600 text-white px-3 py-1 rounded">تعيين يدوي</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="px-3 py-6 text-center text-gray-500">لا توجد طلبات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-6">
        <?php echo e($orders->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/super-admin/orders.blade.php ENDPATH**/ ?>