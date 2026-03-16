
<?php $__env->startSection('content'); ?>
<?php $title = 'لوحة الإدارة'; $subtitle = 'نظرة عامة على المنصة'; ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.admin.orders')); ?>" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-black transition">
            <i class="fas fa-clipboard-list"></i>
            <span>إدارة الطلبات</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.users')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-users"></i>
            <span>إدارة العملاء</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.categories')); ?>" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-tags"></i>
            <span>التصنيفات</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.inventory.alerts')); ?>" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-exclamation-triangle"></i>
            <span>تنبيهات المخزون</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.attendance')); ?>" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضور الموظفين</span>
        </a>
        <a href="<?php echo e(route('dashboard.hr.skills')); ?>" class="inline-flex items-center gap-2 bg-violet-600 text-white px-4 py-2 rounded-xl hover:bg-violet-700 transition">
            <i class="fas fa-graduation-cap"></i>
            <span>مهارات الموظفين</span>
        </a>
        <a href="<?php echo e(route('dashboard.my-attendance.index')); ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.gifts')); ?>" class="inline-flex items-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-xl hover:bg-pink-700 transition">
            <i class="fas fa-gift"></i>
            <span>Tulip Gifts</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.gifts.creation')); ?>" class="inline-flex items-center gap-2 bg-rose-600 text-white px-4 py-2 rounded-xl hover:bg-rose-700 transition">
            <i class="fas fa-hammer"></i>
            <span>Gifts Creation Stuff</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.mart')); ?>" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="<?php echo e(route('dashboard.admin.roles')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-user-shield"></i>
            <span>Rules</span>
        </a>
        <a href="<?php echo e(route('dashboard.administrative-approvals.manage')); ?>" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
    </div>
    <a href="<?php echo e(route('dashboard.admin.cross-department-kpis')); ?>" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
        <i class="fas fa-chart-bar"></i>
        <span>مؤشرات الأقسام</span>
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 mb-4">
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">إيرادات اليوم</p>
            <h3 id="kpiRevenueToday" class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($metrics['revenue_today'] ?? 0, 2)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1">هذا الشهر: <span id="kpiRevenueMonth"><?php echo e(number_format($metrics['monthly_revenue'] ?? 0, 2)); ?></span></p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">طلبات نشطة</p>
            <h3 id="kpiActiveOrders" class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($metrics['active_orders'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1">معلّقة: <span id="kpiPendingOrders"><?php echo e(number_format($metrics['pending_orders'] ?? 0)); ?></span></p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">المنتجات</p>
            <h3 id="kpiTotalProducts" class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($metrics['total_products'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1">نشطة: <span id="kpiActiveProducts"><?php echo e(number_format($metrics['active_products'] ?? 0)); ?></span></p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">العملاء</p>
            <h3 id="kpiTotalUsers" class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($metrics['total_users'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1">نشطون: <span id="kpiActiveUsers"><?php echo e(number_format($metrics['active_users'] ?? 0)); ?></span></p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">تنبيهات نقص المخزون</p>
            <h3 id="kpiLowStock" class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($metrics['low_stock_alerts'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1"><a class="text-indigo-600 hover:underline" href="<?php echo e(route('dashboard.admin.inventory.alerts')); ?>">عرض التفاصيل</a></p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-3">
            <p class="text-gray-500 text-xs font-semibold">تذاكر الدعم المعلّقة</p>
            <h3 id="kpiPendingTickets" class="text-xl font-black text-gray-900 leading-tight"><?php echo e(number_format($metrics['pending_support_tickets'] ?? 0)); ?></h3>
        </div>
        <p class="text-xs text-gray-500 mt-1"><a class="text-indigo-600 hover:underline" href="<?php echo e(route('dashboard.cs.tickets')); ?>">فتح الدعم</a></p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4 mb-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-8">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-chart-line text-indigo-600 ml-2"></i>الإيرادات (آخر 30 يوم)</h3>
        <canvas id="revenueChart" height="130"></canvas>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-4">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-chart-pie text-purple-600 ml-2"></i>توزيع حالات الطلبات (آخر 30 يوم)</h3>
        <canvas id="orderStatusChart" height="130"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4 mb-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-4">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-trophy text-amber-600 ml-2"></i>أفضل المنتجات (آخر 30 يوم)</h3>
        <div class="overflow-auto max-h-[320px]">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">المنتج</th>
                        <th class="text-right py-2">الكمية</th>
                        <th class="text-right py-2">الإيراد</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = ($metrics['top_products_30d'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t border-gray-100">
                            <td class="py-2 text-gray-800"><?php echo e($row->name ?? 'منتج'); ?></td>
                            <td class="py-2 text-gray-700"><?php echo e(number_format((int) ($row->total_sold ?? 0))); ?></td>
                            <td class="py-2 text-gray-700"><?php echo e(number_format((float) ($row->total_revenue ?? 0), 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" class="py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-4">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-list text-indigo-600 ml-2"></i>آخر النشاطات</h3>
        <div class="space-y-2 max-h-[320px] overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = ($metrics['recent_activities'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-100 flex items-center justify-center">
                        <i class="fas <?php echo e($activity['icon'] ?? 'fa-bolt'); ?> <?php echo e($activity['color'] ?? 'text-gray-700'); ?>"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900"><?php echo e($activity['title'] ?? 'Activity'); ?></p>
                        <p class="text-xs text-gray-600"><?php echo e($activity['description'] ?? ''); ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?php echo e(optional($activity['time'] ?? null)->diffForHumans() ?? ''); ?></p>
                    </div>
                    <?php if(isset($activity['amount']) && $activity['amount'] !== null): ?>
                        <div class="text-xs font-semibold text-gray-700"><?php echo e(number_format((float) $activity['amount'], 2)); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500 py-6">لا توجد نشاطات</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 xl:col-span-4">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-map-marked-alt text-emerald-600 ml-2"></i>توزيع الطلبات جغرافياً (آخر 30 يوم)</h3>
        <canvas id="geoChart" height="180"></canvas>
        <div class="mt-4">
            <h4 class="text-sm font-black text-gray-900 mb-2">تنبيهات المخزون (الأقل)</h4>
            <div class="space-y-2 max-h-28 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = ($metrics['low_stock_products'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                        <span class="text-gray-800"><?php echo e($p->name ?? 'منتج'); ?></span>
                        <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-700"><?php echo e((int) ($p->stock_quantity ?? 0)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center text-gray-500 py-2 text-sm">لا يوجد نقص مخزون</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100" role="region" aria-labelledby="growthHeading">
        <h3 id="growthHeading" class="text-base font-black text-gray-900 mb-3"><i class="fas fa-chart-line text-blue-600 ml-2" aria-hidden="true"></i>نمو النظام</h3>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-4 rounded-2xl text-center border border-blue-100 bg-gradient-to-b from-blue-50 to-white" aria-label="نمو المستخدمين">
                <p class="text-2xl font-extrabold text-blue-700 leading-none"><span dir="ltr"><?php echo e(number_format($metrics['user_growth'] ?? 0, 1)); ?>%</span></p>
                <p class="text-xs text-gray-600 mt-1">نمو المستخدمين</p>
            </div>
            <div class="p-4 rounded-2xl text-center border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white" aria-label="نمو الإيرادات">
                <p class="text-2xl font-extrabold text-emerald-700 leading-none"><span dir="ltr"><?php echo e(number_format($metrics['revenue_growth'] ?? 0, 1)); ?>%</span></p>
                <p class="text-xs text-gray-600 mt-1">نمو الإيرادات</p>
            </div>
            <div class="p-4 rounded-2xl text-center border border-orange-100 bg-gradient-to-b from-orange-50 to-white" aria-label="نمو الطلبات">
                <p class="text-2xl font-extrabold text-orange-700 leading-none"><span dir="ltr"><?php echo e(number_format($metrics['order_growth'] ?? 0, 1)); ?>%</span></p>
                <p class="text-xs text-gray-600 mt-1">نمو الطلبات</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-bell text-red-600 ml-2"></i>التنبيهات</h3>
        <div class="grid grid-cols-2 gap-3">
            <div class="p-3 bg-red-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($metrics['system_alerts'] ?? 0)); ?></p>
                <p class="text-xs text-red-200">تنبيهات النظام</p>
            </div>
            <div class="p-3 bg-purple-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($metrics['avg_order_value'] ?? 0, 2)); ?></p>
                <p class="text-xs text-purple-200">متوسط قيمة الطلب</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <h3 class="text-base font-black text-gray-900 mb-3"><i class="fas fa-store text-indigo-600 ml-2"></i>أفضل المتاجر</h3>
        <div class="space-y-2 max-h-[280px] overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = ($metrics['top_performing_stores'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                    <?php $storeName = is_array($store->name ?? null) ? json_encode($store->name) : ($store->name ?? 'متجر'); ?>
                    <span class="text-gray-700"><?php echo e($storeName); ?></span>
                    <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-700"><?php echo e(number_format($store->orders_sum_total ?? 0, 2)); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500 py-4">لا توجد بيانات</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
    $revLabels = collect($metrics['revenue_chart_30d'] ?? [])->pluck('date')->all();
    $revValues = collect($metrics['revenue_chart_30d'] ?? [])->pluck('revenue')->map(fn ($v) => (float) $v)->all();
    $statusLabels = $metrics['orders_by_status_30d']['labels'] ?? [];
    $statusValues = $metrics['orders_by_status_30d']['values'] ?? [];
    $geoPoints = $metrics['geo_orders_30d'] ?? [];
?>

<script>
    const revLabels = <?php echo json_encode($revLabels, 15, 512) ?>;
    const revValues = <?php echo json_encode($revValues, 15, 512) ?>;
    const statusLabels = <?php echo json_encode($statusLabels, 15, 512) ?>;
    const statusValues = <?php echo json_encode($statusValues, 15, 512) ?>;
    const geoPoints = <?php echo json_encode($geoPoints, 15, 512) ?>;
    const metricsUrl = <?php echo json_encode(route('dashboard.admin.metrics'), 15, 512) ?>;
    const num = new Intl.NumberFormat(undefined, { maximumFractionDigits: 2 });
    const num0 = new Intl.NumberFormat(undefined, { maximumFractionDigits: 0 });

    const revenueCtx = document.getElementById('revenueChart');
    let revenueChartInstance = null;
    if (revenueCtx) {
        revenueChartInstance = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revLabels,
                datasets: [{
                    label: 'الإيرادات',
                    data: revValues,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79,70,229,0.15)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { maxTicksLimit: 10 } },
                    y: { beginAtZero: true }
                }
            }
        });
    }

    const statusCtx = document.getElementById('orderStatusChart');
    let statusChartInstance = null;
    if (statusCtx) {
        statusChartInstance = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#6366F1', '#22C55E', '#F59E0B', '#EF4444', '#0EA5E9',
                        '#8B5CF6', '#14B8A6', '#64748B', '#E11D48'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    const geoCtx = document.getElementById('geoChart');
    let geoChartInstance = null;
    if (geoCtx) {
        geoChartInstance = new Chart(geoCtx, {
            type: 'bubble',
            data: {
                datasets: [{
                    label: 'الطلبات',
                    data: geoPoints,
                    backgroundColor: 'rgba(16,185,129,0.25)',
                    borderColor: 'rgba(16,185,129,0.7)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: 'Longitude' } },
                    y: { title: { display: true, text: 'Latitude' } }
                }
            }
        });
    }

    async function refreshAdminMetrics() {
        const res = await fetch(metricsUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        if (!res.ok) {
            return;
        }
        const metrics = await res.json();

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = value;
            }
        };

        setText('kpiRevenueToday', num.format(metrics.revenue_today || 0));
        setText('kpiRevenueMonth', num.format(metrics.monthly_revenue || 0));
        setText('kpiActiveOrders', num0.format(metrics.active_orders || 0));
        setText('kpiPendingOrders', num0.format(metrics.pending_orders || 0));
        setText('kpiTotalProducts', num0.format(metrics.total_products || 0));
        setText('kpiActiveProducts', num0.format(metrics.active_products || 0));
        setText('kpiTotalUsers', num0.format(metrics.total_users || 0));
        setText('kpiActiveUsers', num0.format(metrics.active_users || 0));
        setText('kpiLowStock', num0.format(metrics.low_stock_alerts || 0));
        setText('kpiPendingTickets', num0.format(metrics.pending_support_tickets || 0));

        const series = metrics.revenue_chart_30d || [];
        if (revenueChartInstance && Array.isArray(series)) {
            revenueChartInstance.data.labels = series.map(r => r.date);
            revenueChartInstance.data.datasets[0].data = series.map(r => Number(r.revenue || 0));
            revenueChartInstance.update();
        }

        const byStatus = metrics.orders_by_status_30d || {};
        if (statusChartInstance && Array.isArray(byStatus.labels) && Array.isArray(byStatus.values)) {
            statusChartInstance.data.labels = byStatus.labels;
            statusChartInstance.data.datasets[0].data = byStatus.values.map(v => Number(v || 0));
            statusChartInstance.update();
        }

        const points = metrics.geo_orders_30d || [];
        if (geoChartInstance && Array.isArray(points)) {
            geoChartInstance.data.datasets[0].data = points;
            geoChartInstance.update();
        }
    }

    window.addEventListener('dashboard.updated', function (ev) {
        const data = ev?.detail;
        if (!data || data.dashboard !== 'admin') {
            return;
        }
        refreshAdminMetrics();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/super-admin/index.blade.php ENDPATH**/ ?>