
<?php $__env->startSection('content'); ?>
<?php $title = 'سجل التدقيق'; $subtitle = 'نشاط النظام والعمليات'; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm text-gray-700 mb-1">المستخدم</label>
            <input type="text" name="user_id" value="<?php echo e(request('user_id')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">الإجراء</label>
            <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">الكل</option>
                <?php $__currentLoopData = ($actions ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($action); ?>" <?php if(request('action')===$action): echo 'selected'; endif; ?>><?php echo e($action); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">النموذج</label>
            <select name="model_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">الكل</option>
                <?php $__currentLoopData = ($modelTypes ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($mt); ?>" <?php if(request('model_type')===$mt): echo 'selected'; endif; ?>><?php echo e($mt); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">تصفية</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الوقت</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">المستخدم</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الإجراء</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">النموذج</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">تفاصيل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = ($logs ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-600"><?php echo e($log->created_at); ?></td>
                    <td class="px-4 py-2 text-sm text-gray-800"><?php echo e($log->user->name ?? 'غير معروف'); ?></td>
                    <td class="px-4 py-2 text-sm text-gray-800"><?php echo e($log->action); ?></td>
                    <td class="px-4 py-2 text-sm text-gray-800"><?php echo e($log->model_type ?? '-'); ?></td>
                    <td class="px-4 py-2 text-xs text-gray-600">
                        <?php if(is_array($log->new_values)): ?>
                            <code><?php echo e(json_encode($log->new_values)); ?></code>
                        <?php elseif(is_string($log->new_values)): ?>
                            <code><?php echo e($log->new_values); ?></code>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">لا توجد سجلات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <?php if(method_exists(($logs ?? null),'links')): ?>
            <?php echo e($logs->links()); ?>

        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/audit-logs.blade.php ENDPATH**/ ?>