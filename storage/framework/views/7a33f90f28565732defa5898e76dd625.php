

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex items-center justify-between border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Purchase Orders</h3>
            <p class="text-sm text-gray-500">Create and track restocking orders</p>
        </div>
        <button type="button" onclick="document.getElementById('createPOModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            Create PO
        </button>
    </div>

    <div class="p-6">
        <?php if(session('success')): ?>
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 border border-green-100">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if($errors->has('purchase_order')): ?>
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-100">
                <?php echo e($errors->first('purchase_order')); ?>

            </div>
        <?php endif; ?>

        <form method="GET" action="<?php echo e(route('dashboard.vendor.purchase-orders')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="md:col-span-3">
                <select name="status" class="border rounded-lg px-3 py-2 w-full">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php if(request('status') === $st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">#<?php echo e($po->id); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($po->items?->count() ?? 0); ?> items</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="font-medium"><?php echo e($po->supplier_name ?: '-'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($po->supplier_contact ?: ''); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                                    <?php echo e($po->status); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700"><?php echo e(optional($po->expected_delivery_date)->format('Y-m-d') ?: '-'); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo e(number_format((float) $po->total_cost, 2)); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo e(optional($po->created_at)->format('Y-m-d') ?: '-'); ?></td>
                        </tr>
                        <?php if(($po->items?->count() ?? 0) > 0): ?>
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <?php $__currentLoopData = $po->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between bg-white border border-gray-100 rounded-lg px-3 py-2">
                                                <div class="text-sm text-gray-800">
                                                    <?php echo e($item->product->name ?? ('Product #'.$item->product_id)); ?>

                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    <?php echo e($item->received_quantity); ?>/<?php echo e($item->quantity); ?> @ <?php echo e(number_format((float) $item->unit_cost, 2)); ?>

                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                No purchase orders yet
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($orders->links()); ?>

        </div>
    </div>
</div>

<div id="createPOModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-800">Create Purchase Order</h4>
            <button type="button" onclick="document.getElementById('createPOModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="<?php echo e(route('dashboard.vendor.purchase-orders.create')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Supplier Name</label>
                    <input type="text" name="supplier_name" value="<?php echo e(old('supplier_name')); ?>" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Supplier Contact</label>
                    <input type="text" name="supplier_contact" value="<?php echo e(old('supplier_contact')); ?>" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" value="<?php echo e(old('expected_delivery_date')); ?>" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Notes</label>
                    <textarea name="notes" class="border rounded-lg px-3 py-2 w-full" rows="2"><?php echo e(old('notes')); ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm font-medium text-gray-700">Items</div>
                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 rounded hover:bg-gray-200" onclick="addPOItemRow()">Add item</button>
                    </div>
                    <div id="poItems" class="space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <select name="items[0][product_id]" class="border rounded-lg px-3 py-2 w-full" required>
                                <option value="">Select product</option>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <input type="number" name="items[0][quantity]" min="1" class="border rounded-lg px-3 py-2 w-full" placeholder="Quantity" required>
                            <input type="number" step="0.01" name="items[0][unit_cost]" min="0" class="border rounded-lg px-3 py-2 w-full" placeholder="Unit cost" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('createPOModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    let poItemIndex = 1;
    function addPOItemRow() {
        const container = document.getElementById('poItems');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 md:grid-cols-3 gap-2';
        row.innerHTML = `
            <select name="items[${poItemIndex}][product_id]" class="border rounded-lg px-3 py-2 w-full" required>
                <option value="">Select product</option>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="number" name="items[${poItemIndex}][quantity]" min="1" class="border rounded-lg px-3 py-2 w-full" placeholder="Quantity" required>
            <input type="number" step="0.01" name="items[${poItemIndex}][unit_cost]" min="0" class="border rounded-lg px-3 py-2 w-full" placeholder="Unit cost" required>
        `;
        container.appendChild(row);
        poItemIndex += 1;
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', ['title' => 'Purchase Orders'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/vendor/purchase-orders.blade.php ENDPATH**/ ?>