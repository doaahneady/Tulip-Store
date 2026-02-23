<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="<?php echo e(route('dashboard.admin.mart.categories.store')); ?>" enctype="multipart/form-data" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="<?php echo e(old('name')); ?>" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="<?php echo e(old('slug')); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">ترتيب العرض</label>
                <input type="number" min="0" name="display_order" value="<?php echo e(old('display_order', 0)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" <?php if(old('is_active','1')==='1'): echo 'selected'; endif; ?>>نشط</option>
                    <option value="0" <?php if(old('is_active')==='0'): echo 'selected'; endif; ?>>غير نشط</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full"><?php echo e(old('description')); ?></textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">صورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="<?php echo e(route('dashboard.admin.mart')); ?>" class="btn btn-secondary">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', ['title' => 'إضافة تصنيف', 'subtitle' => 'إضافة تصنيف جديد لقسم Mart'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/super-admin/mart-category-create.blade.php ENDPATH**/ ?>