<?php $__env->startSection('content'); ?>
<?php $title = 'تعديل المنتج'; $subtitle = 'تعديل جميع بيانات المنتج بما في ذلك الصورة'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-start justify-between gap-3 mb-6">
        <div>
            <div class="text-sm text-gray-500">Product #<?php echo e($product->id); ?></div>
            <div class="text-lg font-black text-gray-900"><?php echo e($product->name); ?></div>
        </div>
        <a href="<?php echo e(route('dashboard.cs.products')); ?>" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
    </div>

    <form method="POST" action="<?php echo e(route('dashboard.cs.products.update', $product)); ?>" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <?php echo csrf_field(); ?>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">الاسم</label>
            <input name="name" value="<?php echo e(old('name', $product->name)); ?>" required class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">SKU</label>
            <input name="sku" value="<?php echo e(old('sku', $product->sku)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">السوق</label>
            <select name="market" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                <?php $m = old('market', $product->market); ?>
                <option value="" <?php if($m === null || $m === ''): echo 'selected'; endif; ?>>—</option>
                <option value="store" <?php if((string) $m === 'store'): echo 'selected'; endif; ?>>store</option>
                <option value="mart" <?php if((string) $m === 'mart'): echo 'selected'; endif; ?>>mart</option>
            </select>
            <?php $__errorArgs = ['market'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
            <?php if(isset($categories) && $categories->count()): ?>
                <select name="category_id" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">—</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php if((string) old('category_id', $product->category_id) === (string) $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php else: ?>
                <input name="category_id" value="<?php echo e(old('category_id', $product->category_id)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php endif; ?>
            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Store</label>
            <?php if(isset($stores) && $stores->count()): ?>
                <select name="store_id" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">—</option>
                    <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php if((string) old('store_id', $product->store_id) === (string) $s->id): echo 'selected'; endif; ?>><?php echo e($s->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php else: ?>
                <input name="store_id" value="<?php echo e(old('store_id', $product->store_id)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php endif; ?>
            <?php $__errorArgs = ['store_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">السعر</label>
            <input name="price" type="number" step="0.01" value="<?php echo e(old('price', $product->price)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">سعر التخفيض</label>
            <input name="discount_price" type="number" step="0.01" value="<?php echo e(old('discount_price', $product->discount_price)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">سعر التكلفة</label>
            <input name="cost_price" type="number" step="0.01" value="<?php echo e(old('cost_price', $product->cost_price)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['cost_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">الكمية</label>
            <input name="stock_quantity" type="number" value="<?php echo e(old('stock_quantity', $product->stock_quantity)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">حد تنبيه المخزون</label>
            <input name="low_stock_threshold" type="number" value="<?php echo e(old('low_stock_threshold', $product->low_stock_threshold)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['low_stock_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">خيارات</label>
            <div class="flex flex-wrap items-center gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="track_inventory" value="1" <?php if(old('track_inventory', (bool) ($product->track_inventory ?? false))): echo 'checked'; endif; ?>>
                    <span class="text-sm text-gray-700">Track inventory</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', (bool) ($product->is_active ?? false))): echo 'checked'; endif; ?>>
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
            <input name="status" value="<?php echo e(old('status', $product->status)); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 rounded-xl border border-gray-200"><?php echo e(old('description', $product->description)); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">تفاصيل</label>
            <textarea name="details" rows="5" class="w-full px-4 py-2 rounded-xl border border-gray-200"><?php echo e(old('details', $product->details)); ?></textarea>
            <?php $__errorArgs = ['details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">الصورة</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white">
            <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="lg:col-span-2 flex items-center justify-end gap-2 mt-2">
            <a href="<?php echo e(route('dashboard.cs.products')); ?>" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">إلغاء</a>
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">حفظ</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/cs/products-edit.blade.php ENDPATH**/ ?>