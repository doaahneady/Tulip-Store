

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-4xl">
    <form method="POST" action="<?php echo e(route('dashboard.admin.mart.products.store')); ?>" enctype="multipart/form-data" class="space-y-5">
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
                <label class="block text-sm text-gray-600 mb-1">التصنيف</label>
                <select name="category_id" class="form-select w-full">
                    <option value="">بدون</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php if(old('category_id')==$cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <input type="hidden" name="sku" value="<?php echo e(old('sku')); ?>">
            <div>
                <label class="block text-sm text-gray-600 mb-1">السعر</label>
                <input type="number" step="0.01" min="0" name="price" value="<?php echo e(old('price')); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">سعر مخفض (اختياري)</label>
                <input type="number" step="0.01" min="0" name="discount_price" value="<?php echo e(old('discount_price')); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المخزون</label>
                <input type="number" min="0" name="stock_quantity" value="<?php echo e(old('stock_quantity', 0)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">حد انخفاض المخزون</label>
                <input type="number" min="0" name="low_stock_threshold" value="<?php echo e(old('low_stock_threshold', 0)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تتبع المخزون</label>
                <select name="track_inventory" class="form-select w-full">
                    <option value="1" <?php if(old('track_inventory','1')==='1'): echo 'selected'; endif; ?>>مفعل</option>
                    <option value="0" <?php if(old('track_inventory')==='0'): echo 'selected'; endif; ?>>غير مفعل</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" <?php if(old('is_active','1')==='1'): echo 'selected'; endif; ?>>نشط</option>
                    <option value="0" <?php if(old('is_active')==='0'): echo 'selected'; endif; ?>>غير نشط</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تمييز</label>
                <select name="is_featured" class="form-select w-full">
                    <option value="0" <?php if(old('is_featured','0')==='0'): echo 'selected'; endif; ?>>لا</option>
                    <option value="1" <?php if(old('is_featured')==='1'): echo 'selected'; endif; ?>>نعم</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الوحدة (اختياري)</label>
                <input name="unit" value="<?php echo e(old('unit')); ?>" class="form-input w-full" placeholder="كيلو / علبة ...">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المنشأ (اختياري)</label>
                <input name="origin" value="<?php echo e(old('origin')); ?>" class="form-input w-full" placeholder="محلي / تركيا ...">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full"><?php echo e(old('description')); ?></textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">تفاصيل (اختياري)</label>
            <textarea name="details" rows="4" class="form-input w-full"><?php echo e(old('details')); ?></textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">صورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="<?php echo e(route('dashboard.admin.mart.index')); ?>" class="btn btn-secondary">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'إضافة منتج', 'subtitle' => 'إضافة منتج جديد لقسم Mart'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/mart-product-create.blade.php ENDPATH**/ ?>