
<?php $__env->startSection('content'); ?>
<?php $title = 'إضافة سائق'; $subtitle = 'إنشاء سائق جديد وربطه بحساب مستخدم'; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <?php if($errors->any()): ?>
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200">
            <ul class="text-sm text-red-700 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('dashboard.supervisor.drivers.store')); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php echo csrf_field(); ?>

        <div class="md:col-span-2 text-sm font-semibold text-gray-800">بيانات الحساب</div>

        <div>
            <label class="text-sm text-gray-600">الاسم</label>
            <input name="name" value="<?php echo e(old('name')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">البريد</label>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الهاتف</label>
            <input name="phone" value="<?php echo e(old('phone')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">كلمة المرور</label>
            <input type="password" name="password" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>

        <div class="md:col-span-2 mt-2 text-sm font-semibold text-gray-800">بيانات السائق</div>

        <div>
            <label class="text-sm text-gray-600">رقم الرخصة</label>
            <input name="license_number" value="<?php echo e(old('license_number')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">انتهاء الرخصة</label>
            <input type="date" name="license_expiry" value="<?php echo e(old('license_expiry')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">نوع المركبة</label>
            <input name="vehicle_type" value="<?php echo e(old('vehicle_type')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">رقم اللوحة</label>
            <input name="vehicle_plate" value="<?php echo e(old('vehicle_plate')); ?>" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الحالة</label>
            <select name="status" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="active" <?php if(old('status','active')==='active'): echo 'selected'; endif; ?>>نشط</option>
                <option value="inactive" <?php if(old('status')==='inactive'): echo 'selected'; endif; ?>>غير نشط</option>
                <option value="suspended" <?php if(old('status')==='suspended'): echo 'selected'; endif; ?>>موقوف</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">التوفر</label>
            <select name="availability" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="available" <?php if(old('availability','available')==='available'): echo 'selected'; endif; ?>>متاح</option>
                <option value="busy" <?php if(old('availability')==='busy'): echo 'selected'; endif; ?>>مشغول</option>
                <option value="offline" <?php if(old('availability','available')==='offline'): echo 'selected'; endif; ?>>غير متصل</option>
                <option value="on_break" <?php if(old('availability')==='on_break'): echo 'selected'; endif; ?>>في استراحة</option>
            </select>
        </div>

        <div class="md:col-span-2 flex items-center justify-end gap-2 mt-2">
            <a href="<?php echo e(route('dashboard.supervisor.drivers')); ?>" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700">إلغاء</a>
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">حفظ</button>
        </div>

        <div class="md:col-span-2 mt-4 p-3 rounded-xl bg-indigo-50 border border-indigo-100 text-sm text-indigo-800">
            <strong>تسجيل الدخول:</strong> بعد حفظ السائق، يمكنه تسجيل الدخول من <a href="<?php echo e(url('/employee/login')); ?>" class="underline font-semibold">/employee/login</a> باستخدام نفس البريد الإلكتروني وكلمة المرور أعلاه.
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/supervisor/driver-create.blade.php ENDPATH**/ ?>