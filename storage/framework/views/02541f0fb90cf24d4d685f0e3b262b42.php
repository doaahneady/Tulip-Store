
<?php $__env->startSection('content'); ?>
<?php $title = 'صحة النظام'; $subtitle = 'حالة الخدمات ومؤشرات الأداء'; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">الحالة العامة</div>
        <div class="text-2xl font-bold mt-1 <?php echo e(($healthSummary['status'] ?? 'healthy') === 'healthy' ? 'text-emerald-700' : 'text-red-700'); ?>">
            <?php echo e(($healthSummary['status'] ?? 'healthy') === 'healthy' ? 'سليم' : 'تحذير'); ?>

        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">وقت الاستجابة</div>
        <div class="text-2xl font-bold mt-1 text-gray-900"><?php echo e(number_format((float) ($healthSummary['avg_response_time_ms'] ?? 0), 0)); ?> ms</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">الأخطاء (آخر 24 ساعة)</div>
        <div class="text-2xl font-bold mt-1 text-gray-900"><?php echo e(number_format((int) ($healthSummary['errors_last_24h'] ?? 0))); ?></div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">الخدمات</h3>
        <a href="<?php echo e(route('dashboard.it.index')); ?>" class="text-sm text-indigo-600">عودة</a>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الخدمة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = ($services ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $up = (bool) ($svc['is_up'] ?? false); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900"><?php echo e($svc['name'] ?? '-'); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs <?php echo e($up ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e($up ? 'تعمل' : 'متوقفة'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="<?php echo e(route('dashboard.it.services.update-status', ['service' => $svc['key'] ?? 'unknown'])); ?>" class="inline-flex items-center gap-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="<?php echo e($up ? 'down' : 'up'); ?>">
                                <button type="submit" class="btn btn-secondary btn-sm"><?php echo e($up ? 'إيقاف' : 'تشغيل'); ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/it/system-health.blade.php ENDPATH**/ ?>