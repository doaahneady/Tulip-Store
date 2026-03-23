
<?php $__env->startSection('content'); ?>
<?php $title = 'تعديل سائق'; $subtitle = 'تحديث بيانات السائق والحساب المرتبط'; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="<?php echo e(route('dashboard.supervisor.drivers.update', $driver)); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="md:col-span-2 text-sm font-semibold text-gray-800">بيانات الحساب</div>

        <div>
            <label class="text-sm text-gray-600">الاسم</label>
            <input name="name" value="<?php echo e(old('name', optional($driver->user)->name)); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">البريد</label>
            <input type="email" name="email" value="<?php echo e(old('email', optional($driver->user)->email)); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الهاتف</label>
            <input name="phone" value="<?php echo e(old('phone', optional($driver->user)->phone ?? optional($driver->user)->mobile)); ?>" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">كلمة المرور (اختياري)</label>
            <input type="password" name="password" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>

        <div class="md:col-span-2 mt-2 text-sm font-semibold text-gray-800">بيانات السائق</div>

        <div>
            <label class="text-sm text-gray-600">رقم الرخصة</label>
            <input name="license_number" value="<?php echo e(old('license_number', $driver->license_number)); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">انتهاء الرخصة</label>
            <input type="date" name="license_expiry" value="<?php echo e(old('license_expiry', optional($driver->license_expiry)->format('Y-m-d'))); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">نوع المركبة</label>
            <input name="vehicle_type" value="<?php echo e(old('vehicle_type', $driver->vehicle_type)); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">رقم اللوحة</label>
            <input name="vehicle_plate" value="<?php echo e(old('vehicle_plate', $driver->vehicle_plate)); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الحالة</label>
            <select name="status" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="active" <?php if(old('status', $driver->status)==='active'): echo 'selected'; endif; ?>>نشط</option>
                <option value="inactive" <?php if(old('status', $driver->status)==='inactive'): echo 'selected'; endif; ?>>غير نشط</option>
                <option value="suspended" <?php if(old('status', $driver->status)==='suspended'): echo 'selected'; endif; ?>>موقوف</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">التوفر</label>
            <select name="availability" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="available" <?php if(old('availability', $driver->availability)==='available'): echo 'selected'; endif; ?>>متاح</option>
                <option value="busy" <?php if(old('availability', $driver->availability)==='busy'): echo 'selected'; endif; ?>>مشغول</option>
                <option value="offline" <?php if(old('availability', $driver->availability)==='offline'): echo 'selected'; endif; ?>>غير متصل</option>
                <option value="on_break" <?php if(old('availability', $driver->availability)==='on_break'): echo 'selected'; endif; ?>>في استراحة</option>
            </select>
        </div>

        <div class="md:col-span-2 flex items-center justify-between gap-2 mt-2">
            <button form="delete-driver-form" type="submit" onclick="return confirm('حذف السائق؟');" class="px-4 py-2 rounded-xl border border-red-200 text-red-700">حذف</button>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('dashboard.supervisor.drivers')); ?>" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700">إلغاء</a>
                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">حفظ</button>
            </div>
        </div>
    </form>
    <form id="delete-driver-form" method="POST" action="<?php echo e(route('dashboard.supervisor.drivers.delete', $driver)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/supervisor/driver-edit.blade.php ENDPATH**/ ?>