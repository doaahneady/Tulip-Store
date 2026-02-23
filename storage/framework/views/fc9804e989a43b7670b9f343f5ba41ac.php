<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex items-center justify-between border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Products</h3>
            <p class="text-sm text-gray-500">Manage your store inventory</p>
        </div>
        <button type="button" onclick="document.getElementById('createProductModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            Add Product
        </button>
    </div>

    <div class="p-6">
        <form method="GET" action="<?php echo e(route('dashboard.vendor.products')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name or SKU" class="border rounded-lg px-3 py-2 w-full">
            <select name="category" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php if(request('category') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="stock_status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">Any Stock</option>
                <option value="low" <?php if(request('stock_status') === 'low'): echo 'selected'; endif; ?>>Low Stock</option>
                <option value="out" <?php if(request('stock_status') === 'out'): echo 'selected'; endif; ?>>Out of Stock</option>
            </select>
            <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900"><?php echo e($product->name); ?></div>
                            <div class="text-xs text-gray-500">SKU: <?php echo e($product->sku); ?></div>
                        </td>
                        <td class="px-6 py-4 text-gray-700"><?php echo e($product->category->name ?? '-'); ?></td>
                        <td class="px-6 py-4 text-gray-700"><?php echo e(number_format($product->price, 2)); ?></td>
                        <td class="px-6 py-4 text-gray-700"><?php echo e($product->stock_quantity); ?></td>
                        <td class="px-6 py-4">
                            <?php $isOut = (bool) ($product->track_inventory ?? true) && (int) ($product->stock_quantity ?? 0) <= 0; ?>
                            <span class="px-2 py-1 text-xs rounded <?php echo e($isOut ? 'bg-red-100 text-red-700' : ($product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')); ?>">
                                <?php echo e($isOut ? 'out_of_stock' : $product->status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700" onclick="openEditModal('<?php echo e($product->id); ?>')">Edit</button>
                            <form action="<?php echo e(route('dashboard.vendor.products.delete', $product)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>

<div id="createProductModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-800">Add Product</h4>
            <button type="button" onclick="document.getElementById('createProductModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="<?php echo e(route('dashboard.vendor.products.create')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Category</label>
                    <select name="category_id" class="border rounded-lg px-3 py-2 w-full" required>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Description</label>
                    <textarea name="description" class="border rounded-lg px-3 py-2 w-full" rows="3" required></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Weight</label>
                    <input type="number" step="0.01" name="weight" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Images</label>
                    <input type="file" name="images[]" multiple class="border rounded-lg px-3 py-2 w-full">
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-lg border" onclick="document.getElementById('createProductModal').classList.add('hidden')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Create</button>
            </div>
        </form>
    </div>
</div>

<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div id="editProductModal-<?php echo e($product->id); ?>" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-800">Edit Product</h4>
            <button type="button" onclick="document.getElementById('editProductModal-<?php echo e($product->id); ?>').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="<?php echo e(route('dashboard.vendor.products.update', $product)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" value="<?php echo e($product->name); ?>" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Category</label>
                    <select name="category_id" class="border rounded-lg px-3 py-2 w-full" required>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php if($product->category_id == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Description</label>
                    <textarea name="description" class="border rounded-lg px-3 py-2 w-full" rows="3" required><?php echo e($product->description); ?></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" value="<?php echo e($product->price); ?>" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" value="<?php echo e($product->cost_price); ?>" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="<?php echo e($product->stock_quantity); ?>" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="<?php echo e($product->low_stock_threshold); ?>" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Status</label>
                    <select name="status" class="border rounded-lg px-3 py-2 w-full" required>
                        <?php $__currentLoopData = ['draft','active','inactive','out_of_stock']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if($product->status === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_',' ',$status))); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-lg border" onclick="document.getElementById('editProductModal-<?php echo e($product->id); ?>').classList.add('hidden')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<script>
function openEditModal(id) {
    const el = document.getElementById('editProductModal-' + id);
    if (el) el.classList.remove('hidden');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'Inventory'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/vendor/products.blade.php ENDPATH**/ ?>