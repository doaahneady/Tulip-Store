<?php $__env->startSection('content'); ?>
<?php $title = 'لوحة البائع'; $subtitle = 'نظرة عامة على المبيعات والمخزون والأداء'; ?>
<?php $isTraderSession = auth('trader')->check() && !auth('employee')->check(); ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.vendor.orders')); ?>" class="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2 rounded-xl hover:bg-green-800 transition">
            <i class="fas fa-receipt"></i>
            <span>الطلبات</span>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.products')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-boxes"></i>
            <span>المنتجات</span>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.purchase-orders')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-file-invoice"></i>
            <span>أوامر الشراء</span>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.sales-forecasts')); ?>" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-chart-line"></i>
            <span>توقعات المبيعات</span>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.product-performance-metrics')); ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-chart-bar"></i>
            <span>أداء المنتجات</span>
        </a>
        <?php if(! $isTraderSession): ?>
            <a href="<?php echo e(route('dashboard.administrative-approvals.index')); ?>" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
                <i class="fas fa-clipboard-check"></i>
                <span>الموافقات الإدارية</span>
            </a>
            <a href="<?php echo e(route('dashboard.my-attendance.index')); ?>" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
                <i class="fas fa-user-clock"></i>
                <span>حضوري</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">إجمالي الطلبات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['total_orders'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">طلبات هذا الشهر</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['monthly_orders'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">طلبات قيد المعالجة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['pending_orders'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-hourglass-half text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">طلبات مكتملة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['completed_orders'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">إجمالي الإيرادات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['total_revenue'] ?? 0, 2)); ?> ر.س</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">إيرادات هذا الشهر</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['monthly_revenue'] ?? 0, 2)); ?> ر.س</h3>
            </div>
            <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-chart-line text-teal-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">الرصيد المتاح</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['available_balance'] ?? 0, 2)); ?> ر.س</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-wallet text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">دفعات معلقة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['pending_payout'] ?? 0, 2)); ?> ر.س</h3>
            </div>
            <div class="w-12 h-12 bg-pink-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-money-check-alt text-pink-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">إجمالي المنتجات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['total_products'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-boxes text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">منتجات نشطة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['active_products'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-toggle-on text-teal-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">قريبة النفاد</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['low_stock_products'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">منتهية المخزون</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(number_format($metrics['out_of_stock_products'] ?? 0)); ?></h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-times-circle text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">إجراءات سريعة</h3>
        <div class="text-sm text-gray-500">إدارة المنتجات والمخزون</div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <a href="<?php echo e(route('dashboard.vendor.orders')); ?>" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 text-green-700 rounded-xl flex items-center justify-center"><i class="fas fa-receipt"></i></div>
            <div class="font-semibold text-gray-800">إدارة الطلبات</div>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.products')); ?>" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center"><i class="fas fa-boxes"></i></div>
            <div class="font-semibold text-gray-800">إدارة المنتجات</div>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.sales-forecasts')); ?>" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center"><i class="fas fa-chart-area"></i></div>
            <div class="font-semibold text-gray-800">توقعات المبيعات</div>
        </a>
        <a href="<?php echo e(route('dashboard.vendor.product-performance-metrics')); ?>" class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center"><i class="fas fa-bullseye"></i></div>
            <div class="font-semibold text-gray-800">مؤشرات الأداء</div>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">أعلى المنتجات</h3>
        <div class="space-y-3">
            <?php $__currentLoopData = ($metrics['top_products'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800"><?php echo e($product->name); ?></div>
                            <div class="text-xs text-gray-500">طلبات: <?php echo e($product->order_items_count); ?></div>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-gray-700"><?php echo e(number_format($product->price, 2)); ?> ر.س</div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><?php echo e($isTraderSession ? 'طلبات منتجاتي' : 'آخر الطلبات'); ?></h3>
        <div class="space-y-3">
            <?php $__currentLoopData = ($metrics['recent_orders'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">طلب #<?php echo e($order->id); ?></div>
                            <div class="text-xs text-gray-500">
                                <?php echo e($order->created_at->format('Y-m-d H:i')); ?>

                                <?php $items = $order->items ?? collect(); ?>
                                <?php if($items->count()): ?>
                                    <span class="mx-1">•</span>
                                    <?php $__currentLoopData = $items->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span><?php echo e($item->product->name ?? ('#'.$item->product_id)); ?> x<?php echo e($item->quantity ?? 0); ?></span><?php if(! $loop->last): ?>, <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($items->count() > 2): ?>
                                        <span>, +<?php echo e($items->count() - 2); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-gray-700"><?php echo e(number_format($order->total_amount ?? $order->total ?? 0, 2)); ?> ر.س</div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/vendor/index.blade.php ENDPATH**/ ?>