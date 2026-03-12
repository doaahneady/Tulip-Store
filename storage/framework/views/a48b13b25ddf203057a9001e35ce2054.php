
<?php $__env->startSection('content'); ?>
<?php($title = 'لوحة خدمة العملاء')
@php($subtitle = 'إدارة التذاكر والردود والمرتجعات')
@php($resolvedGrowth = '')
@php($ticketsGrowth = '')
@php($avgFormatted = 'N/A')
@php
    $resolvedGrowth = $kpi['resolved_today']['growth'] ?? '';
    if (is_array($resolvedGrowth)) {
        $resolvedGrowth = $resolvedGrowth['value'] ?? '';
    }
    if ($resolvedGrowth === '0.0%') {
        $resolvedGrowth = '';
    }

    $ticketsGrowth = $kpi['tickets_this_month']['growth'] ?? '';
    if (is_array($ticketsGrowth)) {
        $ticketsGrowth = $ticketsGrowth['value'] ?? '';
    }
    if ($ticketsGrowth === '0.0%') {
        $ticketsGrowth = '';
    }

    $avgFormatted = $kpi['avg_response_time']['formatted'] ?? $avgFormatted;
    if (is_array($avgFormatted)) {
        $avgFormatted = $avgFormatted['value'] ?? 'N/A';
    }
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.cs.orders')); ?>" class="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2 rounded-xl hover:bg-green-800 transition">
            <i class="fas fa-receipt"></i>
            <span>الطلبات</span>
        </a>
        <a href="<?php echo e(route('dashboard.cs.tickets')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-inbox"></i>
            <span>التذاكر</span>
        </a>
        <a href="<?php echo e(route('dashboard.cs.tickets')); ?>?assigned_to=<?php echo e(auth('employee')->id()); ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-user-check"></i>
            <span>تذاكري</span>
        </a>
        <a href="<?php echo e(route('dashboard.cs.trader-products')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>مراجعة المنتجات</span>
        </a>
        <a href="<?php echo e(route('dashboard.cs.payrolls')); ?>" class="inline-flex items-center gap-2 bg-sky-600 text-white px-4 py-2 rounded-xl hover:bg-sky-700 transition">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Payrolls</span>
        </a>
        <a href="<?php echo e(route('dashboard.administrative-approvals.index')); ?>" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="<?php echo e(route('dashboard.my-attendance.index')); ?>" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 mb-4">
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">التذاكر المفتوحة</p>
            <h3 class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($kpi['open_tickets']['value'] ?? 0)); ?></h3>
        </div>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">قيد المعالجة</p>
            <h3 class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($kpi['pending_tickets']['value'] ?? 0)); ?></h3>
        </div>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">تم الحل اليوم</p>
            <h3 class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($kpi['resolved_today']['value'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1"><?php echo e($resolvedGrowth ?? ''); ?></p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">متوسط وقت الاستجابة</p>
            <h3 class="text-xl font-black text-gray-900 leading-tight"><?php echo e($avgFormatted ?? 'N/A'); ?></h3>
        </div>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">تذاكر هذا الشهر</p>
            <h3 class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($kpi['tickets_this_month']['value'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1"><?php echo e($ticketsGrowth ?? ''); ?></p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4 mb-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-6">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-bolt text-red-600 ml-2"></i>تذاكر عاجلة</h3>
        <div class="space-y-2 max-h-[340px] overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $urgentTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $ticketSubject = $t->subject ?? '';
                    if (is_array($ticketSubject)) {
                        $ticketSubject = $ticketSubject['ar'] ?? ($ticketSubject['en'] ?? '');
                    }
                    $userName = optional($t->user)->name ?? null;
                    if (is_array($userName)) {
                        $userName = $userName['ar'] ?? ($userName['en'] ?? '');
                    }
                    $userLabel = $userName ?: ('User #'.$t->user_id);
                ?>
                <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate"><?php echo e($t->ticket_number); ?> — <?php echo e($ticketSubject); ?></p>
                        <p class="text-xs text-gray-600 truncate"><?php echo e($userLabel); ?> • <?php echo e($t->created_at?->diffForHumans()); ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700"><?php echo e($t->priority); ?></span>
                        <a href="<?php echo e(route('dashboard.cs.tickets.show', $t->id)); ?>" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-100">فتح</a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500 py-6">لا توجد تذاكر عاجلة</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-3">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-layer-group text-indigo-600 ml-2"></i>حسب الأولوية</h3>
        <canvas id="priorityChart" height="170"></canvas>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-3">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-chart-pie text-purple-600 ml-2"></i>حسب الحالة</h3>
        <canvas id="statusChart" height="170"></canvas>
    </div>
</div>

<?php
    $priorityLabels = array_keys($priority ?? []);
    $priorityValues = array_values($priority ?? []);
    $statusLabels = array_keys($statusDist ?? []);
    $statusValues = array_values($statusDist ?? []);
?>

<script>
    const priorityLabels = <?php echo json_encode($priorityLabels, 15, 512) ?>;
    const priorityValues = <?php echo json_encode($priorityValues, 15, 512) ?>;
    const statusLabels = <?php echo json_encode($statusLabels, 15, 512) ?>;
    const statusValues = <?php echo json_encode($statusValues, 15, 512) ?>;

    const pctx = document.getElementById('priorityChart');
    if (pctx) {
        new Chart(pctx, {
            type: 'bar',
            data: {
                labels: priorityLabels,
                datasets: [{
                    data: priorityValues,
                    backgroundColor: ['#EF4444','#F59E0B','#6366F1','#22C55E']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    const sctx = document.getElementById('statusChart');
    if (sctx) {
        new Chart(sctx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#6366F1','#22C55E','#F59E0B','#EF4444','#64748B','#8B5CF6']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/cs/index.blade.php ENDPATH**/ ?>