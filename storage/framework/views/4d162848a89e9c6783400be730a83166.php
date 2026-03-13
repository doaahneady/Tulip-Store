
<?php $__env->startSection('content'); ?>
<?php $title = 'المصروفات'; $subtitle = 'إدارة المصروفات وتتبعها'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6" id="new-expense">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">إضافة مصروف</h3>
    </div>
    <form method="POST" action="<?php echo e(route('dashboard.finance.expenses.create')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <?php echo csrf_field(); ?>
        <input name="description" value="<?php echo e(old('description')); ?>" required placeholder="الوصف" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="category" value="<?php echo e(old('category')); ?>" placeholder="الفئة (اختياري)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input type="number" step="0.01" min="0.01" name="amount" value="<?php echo e(old('amount')); ?>" required placeholder="المبلغ" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="currency" value="<?php echo e(old('currency','USD')); ?>" placeholder="العملة" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <button class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-plus"></i>
            <span>إضافة</span>
        </button>

        <select name="store_id" class="w-full md:col-span-3 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">المتجر (اختياري)</option>
            <?php $__currentLoopData = ($stores ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $storeName = data_get($s, 'name'); ?>
                <option value="<?php echo e($s->id); ?>" <?php if(old('store_id') == $s->id): echo 'selected'; endif; ?>><?php echo e(is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="employee_id" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الموظف (اختياري)</option>
            <?php $__currentLoopData = ($employees ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($e->id); ?>" <?php if(old('employee_id') == $e->id): echo 'selected'; endif; ?>><?php echo e(($e->first_name ?? '') . ' ' . ($e->last_name ?? '')); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="md:col-span-1"></div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php if (isset($component)) { $__componentOriginal457ade557f73eaa008f851091260abe1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal457ade557f73eaa008f851091260abe1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'إجمالي المصروفات','value' => '$'.number_format($expenseStats['total_expenses'] ?? 0, 2),'icon' => 'fas fa-wallet','color' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'إجمالي المصروفات','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($expenseStats['total_expenses'] ?? 0, 2)),'icon' => 'fas fa-wallet','color' => 'red']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'مصروفات الشهر','value' => '$'.number_format($expenseStats['monthly_expenses'] ?? 0, 2),'icon' => 'fas fa-calendar-alt','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'مصروفات الشهر','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($expenseStats['monthly_expenses'] ?? 0, 2)),'icon' => 'fas fa-calendar-alt','color' => 'orange']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'متوسط المصروف','value' => '$'.number_format($expenseStats['avg_expense'] ?? 0, 2),'icon' => 'fas fa-chart-line','color' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'متوسط المصروف','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($expenseStats['avg_expense'] ?? 0, 2)),'icon' => 'fas fa-chart-line','color' => 'indigo']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard.stat-card','data' => ['title' => 'الفئات','value' => number_format(count($expenseStats['expense_categories'] ?? [])),'icon' => 'fas fa-tags','color' => 'gray']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dashboard.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'الفئات','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format(count($expenseStats['expense_categories'] ?? []))),'icon' => 'fas fa-tags','color' => 'gray']); ?>
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

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="<?php echo e(route('dashboard.finance.expenses')); ?>" class="grid grid-cols-1 md:grid-cols-7 gap-3">
        <input name="category" value="<?php echo e(request('category')); ?>" placeholder="الفئة (اختياري)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <select name="store_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">المتجر</option>
            <?php $__currentLoopData = ($stores ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $storeName = data_get($s, 'name'); ?>
                <option value="<?php echo e($s->id); ?>" <?php if((string) request('store_id') === (string) $s->id): echo 'selected'; endif; ?>><?php echo e(is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="employee_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الموظف</option>
            <?php $__currentLoopData = ($employees ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($e->id); ?>" <?php if((string) request('employee_id') === (string) $e->id): echo 'selected'; endif; ?>><?php echo e(($e->first_name ?? '') . ' ' . ($e->last_name ?? '')); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <button class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-filter"></i>
            <span>تصفية</span>
        </button>
        <a href="<?php echo e(route('dashboard.finance.expenses')); ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
    </form>
    </div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة المصروفات</h3>
        <span class="text-sm text-gray-500"><?php echo e($expenses->total()); ?></span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">المعرف</th>
                    <th class="text-right py-2">الوصف</th>
                    <th class="text-right py-2">المبلغ</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">تاريخ</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t border-gray-100">
                        <td class="py-3 text-gray-900 font-semibold"><?php echo e($e->transaction_id); ?></td>
                        <td class="py-3 text-gray-700"><?php echo e($e->description); ?></td>
                        <td class="py-3 text-gray-900 font-semibold"><?php echo e(number_format((float) ($e->amount ?? 0), 2)); ?> <?php echo e($e->currency ?? 'USD'); ?></td>
                        <td class="py-3 text-gray-700"><?php echo e($e->status); ?></td>
                        <td class="py-3 text-gray-500"><?php echo e($e->created_at?->format('Y-m-d H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <?php echo e($expenses->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/finance/expenses.blade.php ENDPATH**/ ?>