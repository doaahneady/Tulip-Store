
<?php $__env->startSection('content'); ?>
<?php $title = 'الموظفون'; $subtitle = 'عرض الموظفين وإدارة بياناتهم'; ?>
<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="GET" action="<?php echo e(route('dashboard.hr.employees')); ?>" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث" class="form-input w-56">
        <select name="department" class="form-select w-44">
            <option value="">القسم</option>
            <?php $__currentLoopData = ($departments ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($d); ?>" <?php if(request('department')==$d): echo 'selected'; endif; ?>><?php echo e($d); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="form-select w-40">
            <option value="">الحالة</option>
            <option value="active" <?php if(request('status')=='active'): echo 'selected'; endif; ?>>نشط</option>
            <option value="inactive" <?php if(request('status')=='inactive'): echo 'selected'; endif; ?>>غير نشط</option>
            <option value="on_leave" <?php if(request('status')=='on_leave'): echo 'selected'; endif; ?>>على إجازة</option>
            <option value="terminated" <?php if(request('status')=='terminated'): echo 'selected'; endif; ?>>منتهي العقد</option>
        </select>
        <select name="employment_type" class="form-select w-44">
            <option value="">نوع التوظيف</option>
            <option value="full_time" <?php if(request('employment_type')=='full_time'): echo 'selected'; endif; ?>>دوام كامل</option>
            <option value="part_time" <?php if(request('employment_type')=='part_time'): echo 'selected'; endif; ?>>دوام جزئي</option>
            <option value="contract" <?php if(request('employment_type')=='contract'): echo 'selected'; endif; ?>>عقد</option>
            <option value="intern" <?php if(request('employment_type')=='intern'): echo 'selected'; endif; ?>>متدرب</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">تصفية</button>
        <a href="<?php echo e(route('dashboard.hr.employees.create')); ?>" class="btn btn-primary btn-sm">إضافة موظف</a>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">قائمة الموظفين</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">القسم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المنصب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">
                            <a href="<?php echo e(route('dashboard.hr.employees.edit', $emp)); ?>" class="font-semibold text-indigo-700 hover:underline">
                                <?php echo e($emp->full_name ?: ('#'.$emp->id)); ?>

                            </a>
                            <?php if($emp->email): ?>
                                <div class="text-xs text-gray-500"><?php echo e($emp->email); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-sm"><?php echo e($emp->department); ?></td>
                        <td class="px-4 py-3 text-sm"><?php echo e($emp->position); ?></td>
                        <td class="px-4 py-3 text-sm"><?php echo e($emp->status); ?></td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('dashboard.hr.employees.edit', $emp)); ?>" class="btn btn-secondary btn-xs">تعديل</a>
                                <form method="POST" action="<?php echo e(route('dashboard.hr.employees.delete', $emp)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-ghost btn-xs">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-4"><?php echo e($employees->links()); ?></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/hr/employees.blade.php ENDPATH**/ ?>