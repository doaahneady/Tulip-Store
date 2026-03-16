
<?php $__env->startSection('content'); ?>
<?php $title = 'لوحة تقنية المعلومات'; $subtitle = 'مراقبة النظام والأداء والأمان'; ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.it.system-health')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-heartbeat"></i>
            <span>System Health</span>
        </a>
        <a href="<?php echo e(route('dashboard.it.logs')); ?>" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-file-alt"></i>
            <span>Logs</span>
        </a>
        <a href="<?php echo e(route('dashboard.it.api-errors')); ?>" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-bug"></i>
            <span>API Errors</span>
        </a>
        <a href="<?php echo e(route('dashboard.it.database')); ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-database"></i>
            <span>Database</span>
        </a>
        <a href="<?php echo e(route('dashboard.it.backups')); ?>" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Backups</span>
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

<!-- System Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'المستخدمين','value' => number_format($systemMetrics['total_users']),'icon' => 'fas fa-users','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المستخدمين','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($systemMetrics['total_users'])),'icon' => 'fas fa-users','color' => 'blue']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'الموظفين','value' => number_format($systemMetrics['total_employees']),'icon' => 'fas fa-user-tie','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الموظفين','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($systemMetrics['total_employees'])),'icon' => 'fas fa-user-tie','color' => 'purple']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'المنتجات','value' => number_format($systemMetrics['total_products']),'icon' => 'fas fa-box','color' => 'green']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'المنتجات','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($systemMetrics['total_products'])),'icon' => 'fas fa-box','color' => 'green']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'الطلبات','value' => number_format($systemMetrics['total_orders']),'icon' => 'fas fa-shopping-cart','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الطلبات','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($systemMetrics['total_orders'])),'icon' => 'fas fa-shopping-cart','color' => 'orange']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'السائقين','value' => number_format($systemMetrics['total_drivers']),'icon' => 'fas fa-truck','color' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'السائقين','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($systemMetrics['total_drivers'])),'icon' => 'fas fa-truck','color' => 'indigo']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'حجم قاعدة البيانات','value' => $systemMetrics['database_size'],'icon' => 'fas fa-database','color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'حجم قاعدة البيانات','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($systemMetrics['database_size']),'icon' => 'fas fa-database','color' => 'red']); ?>
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

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'مدة التشغيل','value' => $metrics['system_uptime'] ?? 'N/A','icon' => 'fas fa-clock','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'مدة التشغيل','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($metrics['system_uptime'] ?? 'N/A'),'icon' => 'fas fa-clock','color' => 'gray']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'استخدام CPU','value' => isset($metrics['cpu_usage']) ? (number_format($metrics['cpu_usage'],1).'%' ) : 'N/A','icon' => 'fas fa-microchip','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'استخدام CPU','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($metrics['cpu_usage']) ? (number_format($metrics['cpu_usage'],1).'%' ) : 'N/A'),'icon' => 'fas fa-microchip','color' => 'orange']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'استخدام الذاكرة','value' => isset($metrics['memory_usage']) ? (number_format($metrics['memory_usage'],1).'%' ) : 'N/A','icon' => 'fas fa-memory','color' => 'purple']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'استخدام الذاكرة','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($metrics['memory_usage']) ? (number_format($metrics['memory_usage'],1).'%' ) : 'N/A'),'icon' => 'fas fa-memory','color' => 'purple']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'استخدام القرص','value' => isset($metrics['disk_usage']) ? (number_format($metrics['disk_usage'],1).'%' ) : 'N/A','icon' => 'fas fa-hdd','color' => 'teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'استخدام القرص','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($metrics['disk_usage']) ? (number_format($metrics['disk_usage'],1).'%' ) : 'N/A'),'icon' => 'fas fa-hdd','color' => 'teal']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'شبكة','value' => $metrics['network_throughput'] ?? 'N/A','icon' => 'fas fa-network-wired','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'شبكة','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($metrics['network_throughput'] ?? 'N/A'),'icon' => 'fas fa-network-wired','color' => 'blue']); ?>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- User Activity Today -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-line text-blue-500 ml-2"></i>نشاط اليوم</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                <span class="text-gray-600">تسجيلات جديدة</span>
                <span class="font-bold text-blue-600"><?php echo e($userActivity['new_registrations']); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                <span class="text-gray-600">تسجيلات هذا الأسبوع</span>
                <span class="font-bold text-green-600"><?php echo e($userActivity['new_this_week']); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                <span class="text-gray-600">موظفين نشطين اليوم</span>
                <span class="font-bold text-purple-600"><?php echo e($userActivity['active_employees']); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-xl">
                <span class="text-gray-600">طلبات اليوم</span>
                <span class="font-bold text-orange-600"><?php echo e($userActivity['orders_today']); ?></span>
            </div>
        </div>
    </div>

    <!-- System Services -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-server text-green-500 ml-2"></i>حالة الخدمات</h3>
        <div class="space-y-3">
            <?php $__currentLoopData = $systemServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full <?php echo e($service->status == 'running' ? 'bg-green-500' : ($service->status == 'stopped' ? 'bg-red-500' : 'bg-yellow-500')); ?>"></div>
                    <span class="text-gray-700"><?php echo e($service->display_name ?? $service->name); ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500"><?php echo e($service->uptime ?? 'N/A'); ?></span>
                    <span class="px-2 py-1 text-xs rounded-lg <?php echo e($service->status == 'running' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                        <?php echo e($service->status == 'running' ? 'يعمل' : 'متوقف'); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Backup Status -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-cloud-upload-alt text-indigo-500 ml-2"></i>النسخ الاحتياطية</h3>
        <div class="space-y-4">
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="p-3 bg-blue-50 rounded-xl">
                    <p class="text-2xl font-bold text-blue-600"><?php echo e($backupStats['total']); ?></p>
                    <p class="text-xs text-gray-500">الإجمالي</p>
                </div>
                <div class="p-3 bg-green-50 rounded-xl">
                    <p class="text-2xl font-bold text-green-600"><?php echo e($backupStats['completed']); ?></p>
                    <p class="text-xs text-gray-500">مكتمل</p>
                </div>
                <div class="p-3 bg-red-50 rounded-xl">
                    <p class="text-2xl font-bold text-red-600"><?php echo e($backupStats['failed']); ?></p>
                    <p class="text-xs text-gray-500">فشل</p>
                </div>
            </div>
            <?php if($backupStats['last_backup']): ?>
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500">آخر نسخة احتياطية</p>
                <p class="font-semibold text-gray-800"><?php echo e($backupStats['last_backup']->backup_name); ?></p>
                <p class="text-xs text-gray-500"><?php echo e($backupStats['last_backup']->completed_at?->diffForHumans() ?? 'N/A'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Daily Traffic Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-area text-blue-500 ml-2"></i>حركة المرور (آخر 7 أيام)</h3>
        <canvas id="trafficChart" height="200"></canvas>
    </div>

    <!-- Orders by Status -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-chart-pie text-purple-500 ml-2"></i>حالة الطلبات</h3>
        <canvas id="ordersChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-tachometer-alt text-orange-500 ml-2"></i>أداء واجهة API</h3>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-3 bg-purple-50 rounded-xl text-center">
                <p class="text-xl font-bold text-purple-600"><?php echo e(number_format($apiStats['avg_response_time'] ?? 0, 0)); ?>ms</p>
                <p class="text-xs text-gray-500">متوسط الاستجابة</p>
            </div>
            <div class="p-3 bg-green-50 rounded-xl text-center">
                <?php $success = isset($metrics['error_rate_24h']) ? max(0, 100 - $metrics['error_rate_24h']) : null; ?>
                <p class="text-xl font-bold text-green-600"><?php echo e($success !== null ? (number_format($success, 1).'%') : 'N/A'); ?></p>
                <p class="text-xs text-gray-500">نسبة النجاح</p>
            </div>
            <div class="p-3 bg-red-50 rounded-xl text-center">
                <p class="text-xl font-bold text-red-600"><?php echo e($metrics['slow_queries_today'] ?? 0); ?></p>
                <p class="text-xs text-gray-500">استعلامات بطيئة اليوم</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-bug text-red-500 ml-2"></i>مراقبة الأخطاء</h3>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            <?php $recentErrors = ($systemLogs ?? collect())->where('level','error')->take(10); ?>
            <?php $__empty_1 = true; $__currentLoopData = $recentErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg">
                <div class="w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800"><?php echo e(Str::limit($log->message, 80)); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($log->created_at->diffForHumans()); ?> - <?php echo e($log->channel ?? 'system'); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد أخطاء حديثة</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-code-branch text-indigo-500 ml-2"></i>النشر والإصدارات</h3>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-3 bg-blue-50 rounded-xl text-center">
                <p class="text-xl font-bold text-blue-600"><?php echo e($metrics['deployments_this_month'] ?? 0); ?></p>
                <p class="text-xs text-gray-500">نشـر هذا الشهر</p>
            </div>
            <div class="p-3 bg-green-50 rounded-xl text-center">
                <p class="text-xl font-bold text-green-600"><?php echo e(isset($metrics['deployment_success_rate']) ? (number_format($metrics['deployment_success_rate'],1).'%') : 'N/A'); ?></p>
                <p class="text-xs text-gray-500">معدل النجاح</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl text-center">
                <p class="text-sm font-semibold text-gray-700"><?php echo e(optional($metrics['last_deployment'])->diffForHumans() ?? 'N/A'); ?></p>
                <p class="text-xs text-gray-500">آخر نشر</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Security Stats -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-shield-alt text-red-500 ml-2"></i>إحصائيات الأمان</h3>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600"><?php echo e(number_format($securityStats['total_events'])); ?></p>
                <p class="text-xs text-gray-500">إجمالي الأحداث</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-green-600"><?php echo e($securityStats['today_events']); ?></p>
                <p class="text-xs text-gray-500">أحداث اليوم</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600"><?php echo e($securityStats['failed_logins']); ?></p>
                <p class="text-xs text-gray-500">محاولات فاشلة</p>
            </div>
            <div class="p-4 bg-red-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-red-600"><?php echo e($securityStats['high_risk']); ?></p>
                <p class="text-xs text-gray-500">مخاطر عالية</p>
            </div>
        </div>
    </div>

    <!-- Log Stats -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-file-alt text-orange-500 ml-2"></i>إحصائيات السجلات</h3>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600"><?php echo e(number_format($logStats['total'])); ?></p>
                <p class="text-xs text-gray-500">إجمالي السجلات</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-green-600"><?php echo e($logStats['today']); ?></p>
                <p class="text-xs text-gray-500">سجلات اليوم</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600"><?php echo e($logStats['warnings']); ?></p>
                <p class="text-xs text-gray-500">تحذيرات</p>
            </div>
            <div class="p-4 bg-red-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-red-600"><?php echo e($logStats['errors']); ?></p>
                <p class="text-xs text-gray-500">أخطاء</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Security Logs -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-lock text-indigo-500 ml-2"></i>سجلات الأمان الأخيرة</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-right text-xs text-gray-500 border-b">
                        <th class="pb-3">الحدث</th>
                        <th class="pb-3">الحالة</th>
                        <th class="pb-3">IP</th>
                        <th class="pb-3">الوقت</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php $__empty_1 = true; $__currentLoopData = $securityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50">
                        <td class="py-3"><?php echo e($log->event_type); ?></td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs rounded-lg <?php echo e($log->status == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e($log->status == 'success' ? 'نجاح' : 'فشل'); ?>

                            </span>
                        </td>
                        <td class="py-3 text-gray-500"><?php echo e($log->ip_address ?? 'N/A'); ?></td>
                        <td class="py-3 text-gray-500"><?php echo e($log->created_at->diffForHumans()); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="py-4 text-center text-gray-500">لا توجد سجلات</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Logs -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-terminal text-gray-500 ml-2"></i>سجلات النظام</h3>
        <div class="space-y-2 max-h-80 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $systemLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 mt-2 rounded-full <?php echo e($log->level == 'error' ? 'bg-red-500' : ($log->level == 'warning' ? 'bg-yellow-500' : 'bg-green-500')); ?>"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800"><?php echo e(Str::limit($log->message, 60)); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($log->created_at->diffForHumans()); ?> - <?php echo e($log->action ?? 'system'); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد سجلات</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- API Errors -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-exclamation-triangle text-yellow-500 ml-2"></i>أخطاء API</h3>
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-center">
                <p class="text-xl font-bold text-blue-600"><?php echo e($apiStats['total_errors']); ?></p>
                <p class="text-xs text-gray-500">الإجمالي</p>
            </div>
            <div class="p-3 bg-orange-50 rounded-xl text-center">
                <p class="text-xl font-bold text-orange-600"><?php echo e($apiStats['today_errors']); ?></p>
                <p class="text-xs text-gray-500">اليوم</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-xl text-center">
                <p class="text-xl font-bold text-purple-600"><?php echo e(number_format($apiStats['avg_response_time'], 0)); ?>ms</p>
                <p class="text-xs text-gray-500">متوسط الاستجابة</p>
            </div>
        </div>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $apiErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                <span class="text-gray-700"><?php echo e(Str::limit($error->endpoint, 30)); ?></span>
                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700"><?php echo e($error->status_code); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد أخطاء</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Slow Queries -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-clock text-red-500 ml-2"></i>الاستعلامات البطيئة</h3>
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-center">
                <p class="text-xl font-bold text-blue-600"><?php echo e($queryStats['total']); ?></p>
                <p class="text-xs text-gray-500">الإجمالي</p>
            </div>
            <div class="p-3 bg-yellow-50 rounded-xl text-center">
                <p class="text-xl font-bold text-yellow-600"><?php echo e($queryStats['unoptimized']); ?></p>
                <p class="text-xs text-gray-500">غير محسّن</p>
            </div>
            <div class="p-3 bg-red-50 rounded-xl text-center">
                <p class="text-xl font-bold text-red-600"><?php echo e($queryStats['critical']); ?></p>
                <p class="text-xs text-gray-500">حرج</p>
            </div>
        </div>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $slowQueries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $query): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg text-sm">
                <span class="text-gray-700"><?php echo e(Str::limit($query->table_name ?? $query->query, 25)); ?></span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500"><?php echo e(number_format($query->execution_time, 0)); ?>ms</span>
                    <span class="px-2 py-1 text-xs rounded <?php echo e($query->is_optimized ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?>">
                        <?php echo e($query->is_optimized ? 'محسّن' : 'معلق'); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-500 py-4">لا توجد استعلامات بطيئة</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Logins -->
<div class="bg-white rounded-2xl p-6 shadow-sm mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-sign-in-alt text-green-500 ml-2"></i>آخر تسجيلات الدخول</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-right text-xs text-gray-500 border-b">
                    <th class="pb-3">الموظف</th>
                    <th class="pb-3">البريد الإلكتروني</th>
                    <th class="pb-3">القسم</th>
                    <th class="pb-3">آخر دخول</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php $__empty_1 = true; $__currentLoopData = $recentLogins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                <?php echo e(substr($employee->full_name ?? 'U', 0, 1)); ?>

                            </div>
                            <span><?php echo e($employee->full_name ?? 'N/A'); ?></span>
                        </div>
                    </td>
                    <td class="py-3 text-gray-500"><?php echo e($employee->email); ?></td>
                    <td class="py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg"><?php echo e($employee->department ?? 'N/A'); ?></span>
                    </td>
                    <td class="py-3 text-gray-500"><?php echo e($employee->last_login_at?->diffForHumans() ?? 'N/A'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="py-4 text-center text-gray-500">لا توجد تسجيلات دخول</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Charts Scripts -->
<script>
// Traffic Chart
const trafficCtx = document.getElementById('trafficChart').getContext('2d');
new Chart(trafficCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($dailyTraffic, 'date')); ?>,
        datasets: [{
            label: 'المستخدمين الجدد',
            data: <?php echo json_encode(array_column($dailyTraffic, 'users')); ?>,
            borderColor: '#6366F1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'الطلبات',
            data: <?php echo json_encode(array_column($dailyTraffic, 'orders')); ?>,
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'تسجيلات الدخول',
            data: <?php echo json_encode(array_column($dailyTraffic, 'logins')); ?>,
            borderColor: '#F59E0B',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});

// Orders Status Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
const statusLabels = {
    'pending': 'قيد الانتظار',
    'processing': 'قيد المعالجة',
    'shipped': 'تم الشحن',
    'delivered': 'تم التسليم',
    'cancelled': 'ملغي'
};
const statusColors = {
    'pending': '#F59E0B',
    'processing': '#3B82F6',
    'shipped': '#8B5CF6',
    'delivered': '#10B981',
    'cancelled': '#EF4444'
};
const orderData = <?php echo json_encode($ordersByStatus, 15, 512) ?>;
new Chart(ordersCtx, {
    type: 'doughnut',
    data: {
        labels: orderData.map(item => statusLabels[item.status] || item.status),
        datasets: [{
            data: orderData.map(item => item.count),
            backgroundColor: orderData.map(item => statusColors[item.status] || '#6B7280'),
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

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/it/index.blade.php ENDPATH**/ ?>