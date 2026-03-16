
<?php $__env->startSection('content'); ?>
<?php $title = 'إدارة المركبات'; $subtitle = 'إضافة وتعديل وحذف وربط المركبات بالسائقين'; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <a href="<?php echo e(route('dashboard.supervisor.vehicles.create')); ?>" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-plus"></i>
            <span>إضافة مركبة</span>
        </a>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="border rounded-xl px-4 py-2" placeholder="بحث: لوحة / نوع / VIN">
        <select name="status" class="border rounded-xl px-4 py-2">
            <option value="">الحالة</option>
            <option value="active" <?php if(request('status')==='active'): echo 'selected'; endif; ?>>نشط</option>
            <option value="inactive" <?php if(request('status')==='inactive'): echo 'selected'; endif; ?>>غير نشط</option>
            <option value="maintenance" <?php if(request('status')==='maintenance'): echo 'selected'; endif; ?>>صيانة</option>
        </select>
        <div class="md:col-span-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">تصفية</button>
            <a href="<?php echo e(route('dashboard.supervisor.vehicles')); ?>" class="px-4 py-2 border rounded-md ml-2">مسح</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">اللوحة</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">النوع</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الموديل</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الحالة</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">السائق</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $driverName = optional(optional($v->driver)->user)->name ?? optional(optional($v->driver)->user)->user_full_name ?? optional(optional($v->driver)->user)->email; ?>
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 font-semibold"><?php echo e($v->plate_number); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($v->vehicle_type); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        <?php echo e(trim(($v->make ?? '').' '.($v->model ?? '')) !== '' ? trim(($v->make ?? '').' '.($v->model ?? '')) : '—'); ?>

                        <?php if($v->year): ?> <span class="text-gray-400">(<?php echo e($v->year); ?>)</span> <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            <?php if($v->status==='active'): ?> bg-green-100 text-green-800
                            <?php elseif($v->status==='maintenance'): ?> bg-amber-100 text-amber-800
                            <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                            <?php echo e($v->status); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <?php echo e($driverName ?: '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="<?php echo e(route('dashboard.supervisor.vehicles.edit', $v)); ?>" class="text-sm text-indigo-600 hover:text-indigo-900">تعديل</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($vehicles->count()===0): ?>
                <tr><td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد بيانات</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">
        <?php echo e($vehicles->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/supervisor/vehicles.blade.php ENDPATH**/ ?>