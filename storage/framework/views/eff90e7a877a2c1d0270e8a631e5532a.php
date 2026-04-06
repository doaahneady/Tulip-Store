

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="<?php echo e(route('dashboard.admin.mart.categories.update', $category)); ?>" enctype="multipart/form-data" class="space-y-5">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="<?php echo e(old('name', $category->name)); ?>" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="<?php echo e(old('slug', $category->slug)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">ترتيب العرض</label>
                <input type="number" min="0" name="display_order" value="<?php echo e(old('display_order', $category->display_order ?? 0)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" <?php if((string)old('is_active', (string)(int)($category->is_active ?? 1))==='1'): echo 'selected'; endif; ?>>نشط</option>
                    <option value="0" <?php if((string)old('is_active', (string)(int)($category->is_active ?? 1))==='0'): echo 'selected'; endif; ?>>غير نشط</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full"><?php echo e(old('description', $category->description)); ?></textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">تحديث الصورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
            <?php if(!empty($category->image)): ?>
                <div class="text-xs text-gray-500 mt-2"><?php echo e($category->image); ?></div>
            <?php endif; ?>
        </div>

        <div class="flex items-center justify-between gap-2 flex-wrap">
            <button form="delete-category-form" type="submit" class="btn btn-ghost text-red-600" onclick="return confirm('حذف التصنيف؟')">حذف</button>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('dashboard.admin.mart.index')); ?>" class="btn btn-secondary">رجوع</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </form>
    <form id="delete-category-form" method="POST" action="<?php echo e(route('dashboard.admin.mart.categories.delete', $category)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'تعديل تصنيف', 'subtitle' => 'تعديل تصنيف لقسم Mart'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/mart-category-edit.blade.php ENDPATH**/ ?>