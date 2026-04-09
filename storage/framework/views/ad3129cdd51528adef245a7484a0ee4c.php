
<?php ($indexRoute = $indexRoute ?? 'dashboard.admin.traders'); ?>
<?php ($showRoute = $showRoute ?? 'dashboard.admin.traders.show'); ?>
<?php ($heading = $heading ?? 'التجار'); ?>
<?php ($searchPlaceholder = $searchPlaceholder ?? 'ابحث بالاسم أو البريد'); ?>
<?php ($emptyState = $emptyState ?? 'لا يوجد تجار'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-900"><?php echo e($heading); ?></h3>
            <form method="GET" action="<?php echo e(route($indexRoute)); ?>" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e($searchPlaceholder); ?>" class="form-input w-56">
                <select name="status" class="form-select w-44">
                    <option value="">كل الحالات</option>
                    <?php $__currentLoopData = ($statusOptions ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(request('status') === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn btn-ghost btn-sm">
                    <i class="fas fa-filter"></i>
                    تصفية
                </button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>التاجر</th>
                    <th>البريد الإلكتروني</th>
                    <th>الهاتف</th>
                    <th>الحالة</th>
                    <th>تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $traders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route($showRoute, $trader)); ?>" class="font-semibold text-indigo-700 hover:underline">
                                <?php echo e($trader->name ?? 'Trader #'.$trader->id); ?>

                            </a>
                            <?php if(!empty($trader->company_name)): ?>
                                <div class="text-xs text-gray-500"><?php echo e($trader->company_name); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($trader->email ?? $trader->contact_email ?? '-'); ?></td>
                        <td><?php echo e($trader->phone ?? $trader->contact_phone ?? '-'); ?></td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700"><?php echo e($trader->status ?? '-'); ?></span>
                        </td>
                        <td class="text-xs text-gray-500"><?php echo e($trader->created_at?->diffForHumans()); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-8"><?php echo e($emptyState); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-4">
        <?php echo e(($traders ?? collect())->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboards.layouts.app', ['title' => $title ?? 'إدارة التجار', 'subtitle' => $subtitle ?? 'عرض ومراجعة ملفات التجار'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/traders/index.blade.php ENDPATH**/ ?>