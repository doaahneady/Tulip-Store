

<?php $__env->startSection('content'); ?>
<?php
    $payout = is_array($trader->payout_settings ?? null) ? ($trader->payout_settings ?? []) : [];
    $bank = $payout['bank'] ?? [];
    $business = $payout['business'] ?? [];
    $documents = $payout['documents'] ?? [];
?>

<div class="mb-4">
    <a href="<?php echo e(route('dashboard.admin.traders')); ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-right"></i>
        رجوع
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 xl:col-span-5">
        <h3 class="text-base font-black text-gray-900 mb-3">معلومات عامة</h3>
        <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500">الاسم</span>
                <span class="font-semibold text-gray-900"><?php echo e($trader->name ?? '-'); ?></span>
            </div>
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500">اسم الشركة</span>
                <span class="font-semibold text-gray-900"><?php echo e($trader->company_name ?? '-'); ?></span>
            </div>
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500">البريد</span>
                <span class="font-semibold text-gray-900"><?php echo e($trader->email ?? $trader->contact_email ?? '-'); ?></span>
            </div>
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500">الهاتف</span>
                <span class="font-semibold text-gray-900"><?php echo e($trader->phone ?? $trader->contact_phone ?? '-'); ?></span>
            </div>
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500">الحالة</span>
                <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700"><?php echo e($trader->status ?? '-'); ?></span>
            </div>
            <div class="flex items-center justify-between gap-3">
                <span class="text-gray-500">تاريخ الإنشاء</span>
                <span class="text-gray-700"><?php echo e($trader->created_at?->format('Y-m-d H:i') ?? '-'); ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 xl:col-span-7">
        <h3 class="text-base font-black text-gray-900 mb-3">بيانات العمل</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
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

        <h3 class="text-base font-black text-gray-900 mt-5 mb-3">بيانات البنك</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">اسم البنك</div>
                <div class="font-semibold text-gray-900"><?php echo e($bank['bank_name'] ?? '-'); ?></div>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">اسم صاحب الحساب</div>
                <div class="font-semibold text-gray-900"><?php echo e($bank['account_holder'] ?? '-'); ?></div>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">رقم الحساب</div>
                <div class="font-semibold text-gray-900"><?php echo e($bank['account_number'] ?? '-'); ?></div>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">IBAN</div>
                <div class="font-semibold text-gray-900"><?php echo e($bank['iban'] ?? '-'); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 mt-4">
    <h3 class="text-base font-black text-gray-900 mb-3">الوثائق</h3>
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


<?php echo $__env->make('dashboards.layouts.app', ['title' => 'ملف التاجر', 'subtitle' => 'تفاصيل حساب التاجر'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/traders/show.blade.php ENDPATH**/ ?>