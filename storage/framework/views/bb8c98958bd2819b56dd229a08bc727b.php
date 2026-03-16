
<?php $__env->startSection('content'); ?>
<?php $title = 'لوحة الموارد البشرية'; $subtitle = 'نظرة عامة على الموظفين والحضور والإجازات'; ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.hr.employees')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-users"></i>
            <span>الموظفون</span>
        </a>
        <a href="<?php echo e(route('dashboard.hr.skills')); ?>" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-graduation-cap"></i>
            <span>مهارات الموظفين</span>
        </a>
        <a href="<?php echo e(route('dashboard.hr.attendance')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-calendar-check"></i>
            <span>الحضور</span>
        </a>
        <a href="<?php echo e(route('dashboard.hr.leave-requests')); ?>" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-plane-departure"></i>
            <span>الإجازات</span>
        </a>
        <a href="<?php echo e(route('dashboard.hr.payroll')); ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-money-check-alt"></i>
            <span>الرواتب</span>
        </a>
        <a href="<?php echo e(route('dashboard.administrative-approvals.manage')); ?>" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>إدارة الموافقات</span>
        </a>
        <a href="<?php echo e(route('dashboard.administrative-approvals.index')); ?>" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-file-signature"></i>
            <span>طلباتي</span>
        </a>
        <a href="<?php echo e(route('dashboard.my-attendance.index')); ?>" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'إجمالي الموظفين','value' => number_format($metrics['total_employees'] ?? 0),'icon' => 'fas fa-user-friends','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إجمالي الموظفين','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($metrics['total_employees'] ?? 0)),'icon' => 'fas fa-user-friends','color' => 'blue']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'نشطون','value' => number_format($metrics['active_employees'] ?? 0),'icon' => 'fas fa-user-check','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'نشطون','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($metrics['active_employees'] ?? 0)),'icon' => 'fas fa-user-check','color' => 'green']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'على إجازة اليوم','value' => number_format($metrics['on_leave_today'] ?? 0),'icon' => 'fas fa-plane-departure','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'على إجازة اليوم','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($metrics['on_leave_today'] ?? 0)),'icon' => 'fas fa-plane-departure','color' => 'orange']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'تعيينات هذا الشهر','value' => number_format($metrics['new_hires_month'] ?? 0),'icon' => 'fas fa-user-plus','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'تعيينات هذا الشهر','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($metrics['new_hires_month'] ?? 0)),'icon' => 'fas fa-user-plus','color' => 'purple']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $attributes = $__attributesOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__attributesOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal457ade557f73eaa008f851091260abe1)): ?>
<?php $component = $__componentOriginal457ade557f73eaa008f851091260abe1; ?>
<?php unset($__componentOriginal457ade557f73eaa008f851091260abe1); ?>
<?php endif; ?>
    </div>

<?php
    $todayDate = today()->format('Y-m-d');
    $presentTodayCount = \App\Models\Attendance::where('date', $todayDate)->whereIn('status', ['present', 'late'])->count();
    $absentTodayCount = \App\Models\Attendance::where('date', $todayDate)->where('status', 'absent')->count();
    $lateTodayCount = \App\Models\Attendance::where('date', $todayDate)->where('status', 'late')->count();
    $onLeaveTodayCount = \App\Models\Attendance::where('date', $todayDate)->where('status', 'on_leave')->count();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-calendar-check text-indigo-500 ml-2"></i>ملخص حضور اليوم</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-green-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-green-600"><?php echo e($presentTodayCount); ?></p>
                <p class="text-xs text-gray-500">حاضر</p>
            </div>
            <div class="p-4 bg-red-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-red-600"><?php echo e($absentTodayCount); ?></p>
                <p class="text-xs text-gray-500">غائب</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600"><?php echo e($lateTodayCount); ?></p>
                <p class="text-xs text-gray-500">متأخر</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600"><?php echo e($onLeaveTodayCount); ?></p>
                <p class="text-xs text-gray-500">إجازة</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-pie text-pink-500 ml-2"></i>توزيع الأقسام</h3>
        <canvas id="deptChart" height="220"></canvas>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-list-check text-emerald-500 ml-2"></i>طلبات الإجازة المعلقة</h3>
        <div class="space-y-2 max-h-64 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $pendingLeaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800"><?php echo e(optional($leave->employee)->full_name ?? ('#'.$leave->employee_id)); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($leave->type ?? 'إجازة'); ?> | <?php echo e(optional($leave->start_date)->format('Y-m-d')); ?> - <?php echo e(optional($leave->end_date)->format('Y-m-d')); ?></p>
                </div>
                <span class="px-2 py-1 text-xs rounded bg-amber-100 text-amber-700">معلق</span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد طلبات معلقة</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-birthday-cake text-rose-500 ml-2"></i>أعياد الميلاد هذا الأسبوع</h3>
        <div class="space-y-2 max-h-64 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $upcomingBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-gradient-to-br from-rose-500 to-pink-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    <?php echo e(substr($emp->full_name ?? 'U', 0, 1)); ?>

                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($emp->full_name ?? 'موظف'); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e(optional($emp->date_of_birth)->format('M d')); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد مناسبات</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-briefcase text-indigo-500 ml-2"></i>ذكرى العمل هذا الأسبوع</h3>
        <div class="space-y-2 max-h-64 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $workAnniversaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    <?php echo e(substr($emp->full_name ?? 'U', 0, 1)); ?>

                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($emp->full_name ?? 'موظف'); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e(optional($emp->hire_date)->format('M d')); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد مناسبات</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-calendar-days text-teal-500 ml-2"></i>إجازات مجدولة (الأسبوع الحالي)</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">النوع</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">من</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إلى</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $scheduledLeaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo e(optional($leave->employee)->full_name ?? ('#'.$leave->employee_id)); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo e($leave->type ?? 'إجازة'); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo e(optional($leave->start_date)->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo e(optional($leave->end_date)->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 text-xs rounded <?php echo e($leave->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'); ?>">
                            <?php echo e($leave->status === 'approved' ? 'موافق عليه' : 'معلق'); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد إجازات مجدولة</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const deptCtx = document.getElementById('deptChart').getContext('2d');
const deptData = <?php echo json_encode(($departments ?? collect())->map(fn($d) => ['name' => $d['name'] ?? $d->name ?? '', 'count' => (int)($d['count'] ?? $d->count ?? 0)]), 512) ?>;
new Chart(deptCtx, {
    type: 'doughnut',
    data: {
        labels: deptData.map(d => d.name),
        datasets: [{
            data: deptData.map(d => d.count),
            backgroundColor: ['#6366F1', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#14B8A6', '#F97316', '#22C55E'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/hr/index.blade.php ENDPATH**/ ?>