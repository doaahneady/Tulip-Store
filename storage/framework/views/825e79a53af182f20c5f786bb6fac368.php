<?php $__env->startSection('content'); ?>
<?php
    $payout = is_array($trader->payout_settings ?? null) ? ($trader->payout_settings ?? []) : [];
    $business = $payout['business'] ?? [];
    $documents = $payout['documents'] ?? [];
?>

<div class="mb-4 flex items-center justify-between gap-3">
    <a href="<?php echo e(route('dashboard.cs.traders.index')); ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-right"></i>
        رجوع
    </a>
    <div class="flex items-center gap-2">
        <form method="POST" action="<?php echo e(route('dashboard.cs.traders.approve', $trader)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary btn-sm">موافقة</button>
        </form>
        <form method="POST" action="<?php echo e(route('dashboard.cs.traders.reject', $trader)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="reason" value="Rejected by support">
            <button type="submit" class="btn btn-danger btn-sm">رفض</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
        <div>
            <h3 class="text-lg font-black text-gray-900"><?php echo e($trader->name ?? 'Trader #'.$trader->id); ?></h3>
            <p class="text-sm text-gray-600"><?php echo e($trader->company_name ?? ''); ?></p>
            <p class="text-sm text-gray-600 mt-1"><?php echo e($trader->contact_email ?? $trader->email ?? '-'); ?> • <?php echo e($trader->contact_phone ?? $trader->phone ?? '-'); ?></p>
        </div>
        <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700"><?php echo e($trader->status ?? '-'); ?></span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 text-sm">
        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">الشخص المسؤول</div>
            <div class="font-semibold text-gray-900"><?php echo e($business['contact_person'] ?? $trader->responsible_name ?? '-'); ?></div>
        </div>
        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">العنوان</div>
            <div class="font-semibold text-gray-900"><?php echo e($business['business_address'] ?? $trader->work_address ?? '-'); ?></div>
        </div>
        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">رقم السجل</div>
            <div class="font-semibold text-gray-900"><?php echo e($business['registration_number'] ?? '-'); ?></div>
        </div>
        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">الرقم الضريبي</div>
            <div class="font-semibold text-gray-900"><?php echo e($business['tax_id'] ?? '-'); ?></div>
        </div>
    </div>

    <h3 class="text-base font-black text-gray-900 mt-5 mb-3">الوثائق</h3>
    <div class="flex flex-wrap gap-2">
        <?php $__empty_1 = true; $__currentLoopData = ($documents ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docKey => $docPath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if(!empty($docPath)): ?>
                <?php
                    $docUrl = \Illuminate\Support\Str::startsWith($docPath, ['http://','https://','/'])
                        ? $docPath
                        : \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($docPath, '/'));
                ?>
                <a href="<?php echo e($docUrl); ?>" target="_blank" class="px-3 py-2 rounded-xl bg-white border border-gray-200 text-indigo-700 hover:bg-indigo-50">
                    <i class="fas fa-file"></i>
                    <?php echo e(str_replace('_', ' ', (string) $docKey)); ?>

                </a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <span class="text-gray-500">لا يوجد وثائق</span>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', ['title' => 'تفاصيل التاجر', 'subtitle' => 'مراجعة بيانات التاجر والوثائق'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/cs/traders/show.blade.php ENDPATH**/ ?>