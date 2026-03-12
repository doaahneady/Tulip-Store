
<?php $__env->startSection('content'); ?>
<?php $title = 'تحضير الرواتب'; $subtitle = 'إدارة سجلات الرواتب وفلترة الفترات'; ?>
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <form method="GET" action="<?php echo e(route('dashboard.hr.payroll')); ?>" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-sm text-gray-700 mb-1">الفترة</label>
                <select name="pay_period" class="w-48 px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">الكل</option>
                    <?php $__currentLoopData = ($payPeriods ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($period); ?>" <?php if(request('pay_period') == $period): echo 'selected'; endif; ?>><?php echo e($period); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">الحالة</label>
                <select name="status" class="w-48 px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">الكل</option>
                    <option value="draft" <?php if(request('status')=='draft'): echo 'selected'; endif; ?>>مسودة</option>
                    <option value="sent" <?php if(request('status')=='sent'): echo 'selected'; endif; ?>>مُرسلة للمالية</option>
                    <option value="approved" <?php if(request('status')=='approved'): echo 'selected'; endif; ?>>موافقة مالية</option>
                    <option value="paid" <?php if(request('status')=='paid'): echo 'selected'; endif; ?>>مدفوعة</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تصفية</span>
            </button>
            <a href="<?php echo e(route('dashboard.hr.payroll')); ?>" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
        </form>

        <a href="<?php echo e(route('dashboard.finance.payroll')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-coins"></i>
            <span>رواتب المالية</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900">تقارير الرواتب</h3>
            <div class="text-xs text-gray-500 mt-1">افتح تقرير موظف ثم أرسل راتبه للمالية من داخل التقرير.</div>
        </div>
        <form method="GET" action="<?php echo e(route('dashboard.hr.payroll')); ?>" class="flex flex-wrap items-end gap-3">
            <input type="hidden" name="pay_period" value="<?php echo e(request('pay_period')); ?>">
            <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
            <div>
                <label class="block text-sm text-gray-700 mb-1">السنة</label>
                <select name="report_year" class="w-32 px-4 py-2 rounded-xl border border-gray-200">
                    <?php for($y = now()->year - 2; $y <= now()->year + 1; $y++): ?>
                        <option value="<?php echo e($y); ?>" <?php if((int) ($reportYear ?? now()->year) === $y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">الشهر</label>
                <select name="report_month" class="w-32 px-4 py-2 rounded-xl border border-gray-200">
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php if((int) ($reportMonth ?? now()->month) === $m): echo 'selected'; endif; ?>><?php echo e(str_pad($m, 2, '0', STR_PAD_LEFT)); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-gray-950 transition">
                <i class="fas fa-eye"></i>
                <span>عرض</span>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الراتب الشهري</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأجر بالساعة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">صافي (إن وجد)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تقرير</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = ($employees ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $rec = ($periodRecords[$employee->id] ?? null); ?>
                    <tr>
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            <?php echo e(optional($employee->user)->name ?? trim(($employee->first_name ?? '').' '.($employee->last_name ?? '')) ?? ('Employee #'.$employee->id)); ?>

                        </td>
                        <td class="px-6 py-4 text-gray-700"><?php if($employee->monthly_salary): ?> <?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($employee->monthly_salary)); ?> <?php else: ?> - <?php endif; ?></td>
                        <td class="px-6 py-4 text-gray-700"><?php if($employee->hourly_rate): ?> <?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($employee->hourly_rate)); ?> <?php else: ?> - <?php endif; ?></td>
                        <td class="px-6 py-4 text-gray-700"><?php if($rec?->net_pay): ?> <?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($rec->net_pay)); ?> <?php else: ?> - <?php endif; ?></td>
                        <td class="px-6 py-4 text-gray-700"><?php echo e($rec?->status ?? '-'); ?></td>
                        <td class="px-6 py-4">
                            <a href="<?php echo e(route('dashboard.hr.payroll.report', [$employee->id, $reportPayPeriod ?? now()->format('Y-m')])); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-3 py-1 rounded-lg hover:bg-indigo-700 transition text-sm">
                                <i class="fas fa-file-alt"></i>
                                <span>فتح</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجلات الرواتب</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <div class="text-sm text-gray-600 mb-3">بعد الحساب يتم إنشاء سجلات رواتب (بدون إرسال للمالية).</div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الفترة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الصافي</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">معلومات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $payrollRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $sent = !empty(data_get($rec->breakdown, 'salary_tx_id'));
                        $statusLabel = $rec->status === 'draft' && $sent ? 'sent' : $rec->status;
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800"><?php echo e(optional($rec->employee->user)->name ?? ('#'.$rec->employee_id)); ?></td>
                        <td class="px-4 py-3 text-sm"><?php echo e($rec->pay_period); ?></td>
                        <td class="px-4 py-3 text-sm"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($rec->net_pay ?? 0)); ?></td>
                        <td class="px-4 py-3 text-sm"><?php echo e($statusLabel); ?></td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            <?php if($sent): ?>
                                Sent: <?php echo e(data_get($rec->breakdown, 'sent_to_finance_at')); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($payrollRecords->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/hr/payroll.blade.php ENDPATH**/ ?>