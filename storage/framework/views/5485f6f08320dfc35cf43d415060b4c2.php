
<?php $__env->startSection('content'); ?>
<?php $title = 'مؤشرات الأقسام'; $subtitle = 'HR و Finance و Support و Drivers'; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-user-tie text-purple-500 ml-2"></i>الموارد البشرية</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-purple-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['hr']['total_employees']['value'] ?? 0)); ?></p>
                <p class="text-xs text-purple-200">إجمالي الموظفين</p>
                <p class="text-xs text-green-300 mt-1">نشطون: <?php echo e(number_format($kpis['hr']['total_employees']['active'] ?? 0)); ?></p>
            </div>
            <div class="p-4 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['hr']['present_today']['value'] ?? 0)); ?></p>
                <p class="text-xs text-blue-200">حضور اليوم</p>
                <p class="text-xs text-blue-300 mt-1"><?php echo e(($kpis['hr']['present_today']['percentage'] ?? 0)); ?>%</p>
            </div>
            <div class="p-4 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['hr']['on_leave']['value'] ?? 0)); ?></p>
                <p class="text-xs text-yellow-100">على إجازة</p>
            </div>
            <div class="p-4 bg-orange-700 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['hr']['pending_requests']['value'] ?? 0)); ?></p>
                <p class="text-xs text-orange-100">طلبات معلّقة</p>
            </div>
            <div class="p-4 bg-red-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['hr']['absent_today']['value'] ?? 0)); ?></p>
                <p class="text-xs text-red-200">غياب اليوم</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-coins text-emerald-500 ml-2"></i>المالية</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-emerald-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(data_get($kpis,'finance.daily_revenue.formatted','0')); ?></p>
                <p class="text-xs text-emerald-200">إيراد اليوم</p>
                <p class="text-xs mt-1 text-emerald-300"><?php echo e(data_get($kpis,'finance.daily_revenue.growth.value', '0%')); ?></p>
            </div>
            <div class="p-4 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(data_get($kpis,'finance.monthly_revenue.formatted','0')); ?></p>
                <p class="text-xs text-blue-200">إيراد الشهر</p>
                <p class="text-xs mt-1 text-blue-300"><?php echo e(data_get($kpis,'finance.monthly_revenue.growth.value', '0%')); ?></p>
            </div>
            <div class="p-4 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(data_get($kpis,'finance.pending_payouts.formatted','0')); ?></p>
                <p class="text-xs text-yellow-100">مدفوعات معلّقة</p>
                <p class="text-xs text-yellow-100 mt-1">عدد: <?php echo e(number_format($kpis['finance']['pending_payouts']['count'] ?? 0)); ?></p>
            </div>
            <div class="p-4 bg-indigo-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(data_get($kpis,'finance.profit_margin.formatted.value','0%')); ?></p>
                <p class="text-xs text-indigo-200">هامش الربح</p>
            </div>
            <div class="p-4 bg-gray-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(data_get($kpis,'finance.total_expenses.formatted','0')); ?></p>
                <p class="text-xs text-gray-200">إجمالي المصروفات</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-headset text-amber-500 ml-2"></i>خدمة العملاء</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-amber-700 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['support']['open_tickets']['value'] ?? 0)); ?></p>
                <p class="text-xs text-amber-100">تذاكر مفتوحة</p>
            </div>
            <div class="p-4 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['support']['pending_tickets']['value'] ?? 0)); ?></p>
                <p class="text-xs text-yellow-100">تذاكر معلّقة</p>
            </div>
            <div class="p-4 bg-green-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['support']['resolved_today']['value'] ?? 0)); ?></p>
                <p class="text-xs text-green-200">تم حلها اليوم</p>
                <p class="text-xs mt-1 text-green-300"><?php echo e(data_get($kpis,'support.resolved_today.growth.value', data_get($kpis,'support.resolved_today.growth','0%'))); ?></p>
            </div>
            <div class="p-4 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(data_get($kpis,'support.avg_response_time.formatted.value', data_get($kpis,'support.avg_response_time.formatted','0'))); ?> <?php echo e(data_get($kpis,'support.avg_response_time.formatted.unit','ساعة')); ?></p>
                <p class="text-xs text-blue-200">متوسط زمن الاستجابة</p>
            </div>
            <div class="p-4 bg-indigo-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['support']['tickets_this_month']['value'] ?? 0)); ?></p>
                <p class="text-xs text-indigo-200">تذاكر هذا الشهر</p>
                <p class="text-xs mt-1 text-indigo-300"><?php echo e(data_get($kpis,'support.tickets_this_month.growth.value', data_get($kpis,'support.tickets_this_month.growth','0%'))); ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-truck text-orange-500 ml-2"></i>السائقون والتوصيل</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-orange-700 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['drivers']['active_drivers']['value'] ?? 0)); ?></p>
                <p class="text-xs text-orange-100">سائقون نشطون</p>
                <p class="text-xs text-orange-200 mt-1"><?php echo e(number_format($kpis['drivers']['active_drivers']['percentage'] ?? 0, 1)); ?>%</p>
            </div>
            <div class="p-4 bg-yellow-600 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['drivers']['pending_deliveries']['value'] ?? 0)); ?></p>
                <p class="text-xs text-yellow-100">طلبات بانتظار التعيين</p>
            </div>
            <div class="p-4 bg-green-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['drivers']['completed_today']['value'] ?? 0)); ?></p>
                <p class="text-xs text-green-200">تم التسليم اليوم</p>
            </div>
            <div class="p-4 bg-blue-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['drivers']['in_transit']['value'] ?? 0)); ?></p>
                <p class="text-xs text-blue-200">قيد التوصيل الآن</p>
            </div>
            <div class="p-4 bg-indigo-800 rounded-xl text-center border border-gray-600">
                <p class="text-2xl font-bold text-white"><?php echo e(number_format($kpis['drivers']['avg_delivery_time']['value'] ?? 0)); ?> <?php echo e(data_get($kpis,'drivers.avg_delivery_time.unit','min')); ?></p>
                <p class="text-xs text-indigo-200">متوسط زمن التوصيل</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/super-admin/cross-department-kpis.blade.php ENDPATH**/ ?>