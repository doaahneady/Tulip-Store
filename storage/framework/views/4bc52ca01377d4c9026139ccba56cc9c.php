

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-4xl">
    <form method="POST" action="<?php echo e(route('dashboard.admin.mart.products.update', $product)); ?>" enctype="multipart/form-data" class="space-y-5">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="<?php echo e(old('name', $product->name)); ?>" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="<?php echo e(old('slug', $product->slug)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">التصنيف</label>
                <select name="category_id" class="form-select w-full" id="categorySelect">
                    <option value="">بدون</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php if((string)old('category_id', (string)$product->category_id)===(string)$cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php if(!empty($subcategories) && $subcategories->count()): ?>
            <div>
                <label class="block text-sm text-gray-600 mb-1">التصنيف الفرعي</label>
                <select name="subcategory_id" class="form-select w-full" id="subcategorySelect">
                    <option value="">بدون</option>
                    <?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sub->id); ?>" data-category="<?php echo e($sub->category_id); ?>" <?php if((string)old('subcategory_id', (string)($product->subcategory_id ?? ''))===(string)$sub->id): echo 'selected'; endif; ?>><?php echo e($sub->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>
            <input type="hidden" name="sku" value="<?php echo e(old('sku', $product->sku)); ?>">
            <div>
                <label class="block text-sm text-gray-600 mb-1">السعر</label>
                <input type="number" step="0.01" min="0" name="price" value="<?php echo e(old('price', $product->price)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">سعر مخفض (اختياري)</label>
                <input type="number" step="0.01" min="0" name="discount_price" value="<?php echo e(old('discount_price', $product->discount_price)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المخزون</label>
                <input type="number" min="0" name="stock_quantity" value="<?php echo e(old('stock_quantity', $product->stock_quantity)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">حد انخفاض المخزون</label>
                <input type="number" min="0" name="low_stock_threshold" value="<?php echo e(old('low_stock_threshold', $product->low_stock_threshold)); ?>" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تتبع المخزون</label>
                <select name="track_inventory" class="form-select w-full">
                    <?php $track = (string) old('track_inventory', (string) (int) ($product->track_inventory ?? 1)); ?>
                    <option value="1" <?php if($track==='1'): echo 'selected'; endif; ?>>مفعل</option>
                    <option value="0" <?php if($track==='0'): echo 'selected'; endif; ?>>غير مفعل</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <?php $active = (string) old('is_active', (string) (int) ($product->is_active ?? 1)); ?>
                    <option value="1" <?php if($active==='1'): echo 'selected'; endif; ?>>نشط</option>
                    <option value="0" <?php if($active==='0'): echo 'selected'; endif; ?>>غير نشط</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تمييز</label>
                <select name="is_featured" class="form-select w-full">
                    <?php $featured = (string) old('is_featured', (string) (int) ($product->is_featured ?? 0)); ?>
                    <option value="0" <?php if($featured==='0'): echo 'selected'; endif; ?>>لا</option>
                    <option value="1" <?php if($featured==='1'): echo 'selected'; endif; ?>>نعم</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الوحدة (اختياري)</label>
                <input name="unit" value="<?php echo e(old('unit', $attrs['unit'] ?? '')); ?>" class="form-input w-full" placeholder="كيلو / علبة ...">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المنشأ (اختياري)</label>
                <input name="origin" value="<?php echo e(old('origin', $attrs['origin'] ?? '')); ?>" class="form-input w-full" placeholder="محلي / تركيا ...">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full"><?php echo e(old('description', $product->description)); ?></textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">تفاصيل (اختياري)</label>
            <textarea name="details" rows="4" class="form-input w-full"><?php echo e(old('details', $product->details)); ?></textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">تحديث الصورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
            <?php if(!empty($product->image)): ?>
                <div class="text-xs text-gray-500 mt-2"><?php echo e($product->image); ?></div>
            <?php endif; ?>
        </div>

        <div class="flex items-center justify-between gap-2 flex-wrap">
            <a href="<?php echo e(route('dashboard.admin.mart.index')); ?>" class="btn btn-secondary">رجوع</a>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </form>
</div>

<?php if(!empty($subcategories) && $subcategories->count()): ?>
<script>
    (function () {
        const categorySelect = document.getElementById('categorySelect');
        const subcategorySelect = document.getElementById('subcategorySelect');
        if (!categorySelect || !subcategorySelect) return;

        const allOptions = Array.from(subcategorySelect.querySelectorAll('option'));
        const placeholder = allOptions.shift();

        function syncSubcategories() {
            const categoryId = categorySelect.value;
            const selected = subcategorySelect.value;

            subcategorySelect.innerHTML = '';
            if (placeholder) subcategorySelect.appendChild(placeholder.cloneNode(true));

            const filtered = allOptions.filter((opt) => {
                const c = opt.getAttribute('data-category') || '';
                return categoryId === '' || c === categoryId;
            });
            filtered.forEach((opt) => subcategorySelect.appendChild(opt.cloneNode(true)));

            if (selected) {
                const exists = Array.from(subcategorySelect.options).some((o) => o.value === selected);
                subcategorySelect.value = exists ? selected : '';
            }
        }

        categorySelect.addEventListener('change', syncSubcategories);
        syncSubcategories();
    })();
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'تعديل منتج', 'subtitle' => 'تعديل منتج في قسم Mart'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/mart-product-edit.blade.php ENDPATH**/ ?>