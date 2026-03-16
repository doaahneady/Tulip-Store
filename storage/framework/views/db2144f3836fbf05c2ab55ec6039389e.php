
<?php $__env->startSection('content'); ?>
<?php $isTraderSession = auth('trader')->check() && !auth('employee')->check(); ?>

<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800"><?php echo e($isTraderSession ? 'طلبات منتجاتي' : 'الطلبات'); ?></h3>
            <p class="text-sm text-gray-500">Orders history, status, and customer details</p>
        </div>
        <a href="<?php echo e(route('dashboard.vendor.index')); ?>" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Total</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo e(number_format($orderStats['total'] ?? 0)); ?></div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Pending</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo e(number_format($orderStats['pending'] ?? 0)); ?></div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Processing</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo e(number_format($orderStats['processing'] ?? 0)); ?></div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Delivered</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo e(number_format($orderStats['delivered'] ?? 0)); ?></div>
            </div>
        </div>

        <form method="GET" action="<?php echo e(route('dashboard.vendor.orders')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-6">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search order/customer/phone" class="border rounded-lg px-3 py-2 w-full md:col-span-2">
            <select name="status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Status</option>
                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php if(request('status') === $st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="payment_status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Payments</option>
                <?php $__currentLoopData = $paymentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($ps); ?>" <?php if(request('payment_status') === $ps): echo 'selected'; endif; ?>><?php echo e($ps); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="border rounded-lg px-3 py-2 w-full">
            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="border rounded-lg px-3 py-2 w-full">
            <div class="md:col-span-6 flex items-center gap-2">
                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">Filter</button>
                <a href="<?php echo e(route('dashboard.vendor.orders')); ?>" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $items = $order->items ?? collect();
                            $customerName = $order->customer->name ?? $order->recipient_name ?? 'Customer';
                            $customerEmail = $order->customer->email ?? null;
                            $customerPhone = $order->phone ?? ($order->customer->phone ?? null);
                            $total = $order->total_amount ?? $order->total ?? 0;
                            $logs = ($orderLogs[$order->id] ?? collect())->take(6);
                        ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e($order->order_number ?? ('#'.$order->id)); ?></div>
                                <div class="text-xs text-gray-500">
                                    <?php $__currentLoopData = $items->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span><?php echo e($item->product->name ?? ('#'.$item->product_id)); ?> x<?php echo e($item->quantity ?? 0); ?></span><?php if(! $loop->last): ?>, <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($items->count() > 2): ?>
                                        <span>, +<?php echo e($items->count() - 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="font-medium"><?php echo e($customerName); ?></div>
                                <div class="text-xs text-gray-500">
                                    <?php if($customerEmail): ?> <span><?php echo e($customerEmail); ?></span> <?php endif; ?>
                                    <?php if($customerEmail && $customerPhone): ?> <span class="mx-1">•</span> <?php endif; ?>
                                    <?php if($customerPhone): ?> <span><?php echo e($customerPhone); ?></span> <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700"><?php echo e($order->status ?? '-'); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700 border border-blue-100"><?php echo e($order->payment_status ?? '-'); ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-700"><?php echo e(number_format((float) $total, 2)); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo e(optional($order->created_at)->format('Y-m-d H:i')); ?></td>
                            <td class="px-6 py-4">
                                <button type="button" class="px-3 py-1 text-sm bg-emerald-600 text-white rounded hover:bg-emerald-700" onclick="toggleOrderDetails(<?php echo e($order->id); ?>)">
                                    Details
                                </button>
                            </td>
                        </tr>
                        <tr id="order-details-<?php echo e($order->id); ?>" class="hidden bg-gray-50">
                            <td colspan="7" class="px-6 py-4">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                        <div class="font-semibold text-gray-800 mb-2">Items</div>
                                        <div class="space-y-2">
                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flex items-center justify-between border border-gray-100 rounded-lg px-3 py-2">
                                                    <div class="text-sm text-gray-800"><?php echo e($item->product->name ?? ('Product #'.$item->product_id)); ?></div>
                                                    <div class="text-sm text-gray-600">x<?php echo e($item->quantity ?? 0); ?></div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($items->isEmpty()): ?>
                                                <div class="text-sm text-gray-500">No items</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                        <div class="font-semibold text-gray-800 mb-2">Status History</div>
                                        <div class="space-y-2">
                                            <?php $__empty_2 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <?php
                                                    $oldStatus = $log->old_values['status'] ?? null;
                                                    $newStatus = $log->new_values['status'] ?? null;
                                                    $oldPay = $log->old_values['payment_status'] ?? null;
                                                    $newPay = $log->new_values['payment_status'] ?? null;
                                                ?>
                                                <div class="border border-gray-100 rounded-lg px-3 py-2">
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-sm text-gray-800"><?php echo e($log->action); ?></div>
                                                        <div class="text-xs text-gray-500"><?php echo e(optional($log->created_at)->format('Y-m-d H:i')); ?></div>
                                                    </div>
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        <?php if($newStatus): ?>
                                                            <span>Status: <?php echo e($oldStatus ?: '-'); ?> → <?php echo e($newStatus); ?></span>
                                                        <?php endif; ?>
                                                        <?php if($newPay): ?>
                                                            <span class="mx-1">•</span>
                                                            <span>Payment: <?php echo e($oldPay ?: '-'); ?> → <?php echo e($newPay); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <div class="text-sm text-gray-500">No history logs</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">No orders found</td>
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

<script>
    function toggleOrderDetails(orderId) {
        const row = document.getElementById(`order-details-${orderId}`);
        if (!row) return;
        row.classList.toggle('hidden');
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboards.layouts.app', ['title' => 'Orders'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/vendor/orders.blade.php ENDPATH**/ ?>