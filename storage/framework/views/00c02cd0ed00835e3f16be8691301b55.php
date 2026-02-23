<?php $__env->startSection('content'); ?>
<?php $title = 'تعيين الطلبات'; $subtitle = 'قائمة الطلبات غير المعينة والسائقين المتاحين'; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">طلبات غير معينة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">رقم الطلب</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">العميل</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">المتجر</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">#<?php echo e($order->id); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e(optional($order->customer)->name ?? '—'); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo e(optional($order->store)->name ?? '—'); ?></td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="<?php echo e(route('dashboard.supervisor.assign-order')); ?>" class="flex items-center gap-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                                <select name="driver_id" class="border rounded-xl px-2 py-1 text-xs">
                                    <?php $__currentLoopData = $availableDrivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($driver->id); ?>"><?php echo e(optional($driver->user)->name ?? $driver->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="number" step="0.01" name="delivery_fee" class="border rounded-xl px-2 py-1 text-xs w-24" placeholder="الرسوم">
                                <input type="text" name="notes" class="border rounded-xl px-2 py-1 text-xs" placeholder="ملاحظة">
                                <button class="px-3 py-1 bg-green-600 text-white rounded-md text-xs">تعيين</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($pendingOrders->count()===0): ?>
                    <tr><td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد طلبات</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">سائقون متاحون</h3>
        <ul class="divide-y divide-gray-200">
            <?php $__currentLoopData = $availableDrivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900"><?php echo e(optional($driver->user)->name ?? $driver->name); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($driver->availability); ?></div>
                    </div>
                    <a href="tel:<?php echo e($driver->phone); ?>" class="text-indigo-600 text-sm">اتصال</a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($availableDrivers->count()===0): ?>
                <li class="py-3 text-center text-sm text-gray-500">لا يوجد سائقون متاحون</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">تعيينات نشطة</h3>
        <ul class="divide-y divide-gray-200">
            <?php $__currentLoopData = $activeAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">#<?php echo e(optional($assign->order)->order_number ?? $assign->order_id); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e(optional(optional($assign->driver)->user)->name ?? '—'); ?></div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo e($assign->status); ?>

                        </span>
                    </div>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($activeAssignments->count()===0): ?>
                <li class="py-3 text-center text-sm text-gray-500">لا توجد تعيينات</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/supervisor/order-assignment.blade.php ENDPATH**/ ?>