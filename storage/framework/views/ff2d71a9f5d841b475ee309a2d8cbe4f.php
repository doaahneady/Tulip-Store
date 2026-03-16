
<?php $__env->startSection('content'); ?>
<?php $title = 'الأدوار والصلاحيات'; $subtitle = 'إدارة RBAC والمصفوفة'; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h3 class="text-lg font-bold text-gray-800">صلاحيات الموظفين (لوحات التحكم)</h3>
        <form method="GET" action="<?php echo e(route('dashboard.admin.roles')); ?>" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو البريد أو كود الموظف" class="form-input w-64">
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-search"></i>
                بحث
            </button>
        </form>
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الموظف</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">البريد</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">اللوحات</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">حفظ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = ($employees ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $explicit = method_exists($emp, 'getExplicitDashboardKeys') ? $emp->getExplicitDashboardKeys() : [];
                        $selected = $explicit;
                        if (in_array('__none__', $selected, true)) {
                            $selected = [];
                        }
                        if (empty($selected) && method_exists($emp, 'getAllowedDashboardKeys')) {
                            $selected = $emp->getAllowedDashboardKeys();
                        }
                    ?>
                    <?php $formId = 'emp-rules-'.$emp->id; ?>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            <div class="font-semibold"><?php echo e($emp->full_name); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($emp->employee_code ?? '-'); ?></div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($emp->email); ?></td>
                        <td class="px-4 py-3">
                            <?php
                                $options = [
                                    'admin' => 'Admin',
                                    'it' => 'IT',
                                    'hr' => 'HR',
                                    'cs' => 'CS',
                                    'finance' => 'Finance',
                                    'supervisor' => 'Supervisor',
                                    'vendor' => 'Trader',
                                ];
                            ?>
                            <div class="flex flex-wrap items-center gap-3">
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input form="<?php echo e($formId); ?>" type="checkbox" name="dashboards[]" value="<?php echo e($key); ?>" class="form-checkbox"
                                            <?php if(in_array($key, $selected, true)): echo 'checked'; endif; ?>>
                                        <span><?php echo e($label); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                            <form id="<?php echo e($formId); ?>" method="POST" action="<?php echo e(route('dashboard.admin.roles.employees.update', $emp)); ?>" class="inline-flex items-center gap-2">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary btn-xs">حفظ</button>
                                <a href="<?php echo e(route('dashboard.admin.employees.dashboards.edit', $emp)); ?>" class="text-indigo-600 hover:underline text-sm">تفاصيل</a>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php if(method_exists(($employees ?? null), 'links')): ?>
            <?php echo e($employees->links()); ?>

        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">الأدوار</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الاسم</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الوصف</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الصلاحيات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800"><?php echo e($role->display_name ?? $role->name); ?></td>
                    <td class="px-4 py-2 text-sm text-gray-600"><?php echo e($role->description); ?></td>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        <?php $__currentLoopData = ($role->permissions ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-block px-2 py-1 text-xs rounded bg-gray-100 text-gray-700 mr-1 mb-1">
                                <?php echo e($perm->display_name ?? $perm->name); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">مصفوفة الصلاحيات حسب الفئات</h3>
    <?php $__currentLoopData = ($permissions ?? collect())->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-2"><?php echo e(is_string($category) ? $category : 'عام'); ?></p>
            <div class="flex flex-wrap gap-2">
                <?php $__currentLoopData = ($perms ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="px-3 py-1 text-xs rounded bg-indigo-50 text-indigo-700">
                        <?php echo e(is_array($perm) ? ($perm['display_name'] ?? $perm['name'] ?? 'غير معروف') : ($perm->display_name ?? $perm->name)); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/roles.blade.php ENDPATH**/ ?>