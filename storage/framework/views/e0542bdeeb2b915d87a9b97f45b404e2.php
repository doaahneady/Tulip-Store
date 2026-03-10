<?php $__env->startSection('content'); ?>
<?php
    $title = 'تفاصيل الطلب';
    $subtitle = 'عرض جميع معلومات الطلب ومسار التوصيل';
?>

<?php
    $orderNumber = $order->order_number ?? ('#'.$order->id);
    $customerName = $order->customer->name ?? $order->recipient_name ?? 'Customer';
    if (is_array($customerName)) {
        $customerName = $customerName['ar'] ?? ($customerName['en'] ?? '');
    }
    $total = $order->total_amount ?? $order->total ?? 0;
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.cs.orders')); ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
            <span>الطلبات</span>
        </a>
    </div>
    <div class="text-sm text-gray-600">
        <span class="font-semibold"><?php echo e($orderNumber); ?></span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">العميل</div>
        <div class="text-lg font-semibold text-gray-900 mt-1"><?php echo e($customerName); ?></div>
        <div class="text-sm text-gray-600 mt-1">
            <?php echo e($order->customer->email ?? ''); ?>

            <?php if(($order->customer->email ?? null) && ($order->phone ?? null)): ?> <span class="mx-1">•</span> <?php endif; ?>
            <?php echo e($order->phone ?? ''); ?>

        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">الحالة</div>
        <div class="text-lg font-semibold text-gray-900 mt-1"><?php echo e($order->status ?? '-'); ?></div>
        <div class="text-sm text-gray-600 mt-1">الدفع: <?php echo e($order->payment_status ?? '-'); ?></div>
        <?php if(auth('employee')->check()): ?>
            <?php
                $opts = $allowedNextStatuses ?? [];
                if (! is_array($opts)) {
                    $opts = [];
                }
                $statusLabels = [
                    'pending' => 'قيد الانتظار',
                    'confirmed' => 'مؤكد',
                    'processing' => 'قيد التجهيز',
                    'ready' => 'جاهز',
                    'shipped' => 'تم الشحن',
                    'out_for_delivery' => 'خرج للتوصيل',
                    'delivered' => 'تم التسليم',
                    'done' => 'مكتمل',
                    'failed' => 'فشل',
                    'cancelled' => 'ملغي',
                    'refunded' => 'مسترجع',
                    'returned' => 'مرتجع',
                ];
            ?>
            <?php if(count($opts)): ?>
                <form class="mt-3" method="POST" action="<?php echo e(route('dashboard.cs.orders.change-status', $order)); ?>">
                    <?php echo csrf_field(); ?>
                    <label class="text-xs text-gray-500 block mb-1">تغيير الحالة</label>
                    <div class="flex items-center gap-2">
                        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200">
                            <?php $__currentLoopData = $opts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($st); ?>"><?php echo e($statusLabels[$st] ?? $st); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-3 py-2 rounded-xl hover:bg-indigo-700 transition">
                            <i class="fas fa-exchange-alt"></i>
                            <span>تحديث</span>
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">الإجمالي</div>
        <div class="text-lg font-semibold text-gray-900 mt-1"><?php echo e(number_format((float) $total, 2)); ?></div>
        <div class="text-sm text-gray-600 mt-1"><?php echo e(optional($order->created_at)->format('Y-m-d H:i')); ?></div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="font-semibold text-gray-900">الخريطة</div>
        <div class="text-xs text-gray-500">المسار يعتمد على نقاط تتبع السائق عند توفرها</div>
    </div>
    <div class="p-5">
        <div id="order-map" class="w-full rounded-xl border border-gray-100" style="height:420px;"></div>
        <?php if(! $customerLat || ! $customerLng): ?>
            <div class="mt-3 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                لا توجد إحداثيات للطلب، لذلك سيتم عرض الخريطة بدون مسار.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">محتوى الطلب</div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">المنتج</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">الكمية</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">السعر</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($item->product->name ?? $item->product_name ?? ('Product #'.$item->product_id)); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($item->product_sku ?? ''); ?></div>
                                </td>
                                <td class="px-4 py-2 text-gray-700"><?php echo e($item->quantity ?? 0); ?></td>
                                <td class="px-4 py-2 text-gray-700"><?php echo e(number_format((float) ($item->unit_price ?? 0), 2)); ?></td>
                                <td class="px-4 py-2 text-gray-700"><?php echo e(number_format((float) ($item->total_price ?? 0), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد عناصر</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">بيانات التوصيل</div>
            <div class="p-5 text-sm text-gray-700 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">طريقة التوصيل</span>
                    <?php
                        $deliveryLabels = [
                            'normal' => 'Normal',
                            'express' => 'Express',
                            'instant' => 'Instant',
                        ];
                    ?>
                    <span><?php echo e($deliveryLabels[$order->delivery_method ?? ''] ?? ($order->delivery_method ?? '-')); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">تاريخ التوصيل المتوقع</span>
                    <span><?php echo e($order->estimated_delivery ? \Carbon\Carbon::parse($order->estimated_delivery)->format('Y-m-d') : '-'); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">العنوان</span>
                    <span class="text-right"><?php echo e($order->address_note ?? ($order->shipping_address['address'] ?? '-')); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">القرية</span>
                    <span><?php echo e($order->village ?? ($order->shipping_address['village'] ?? '-')); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">إحداثيات العميل</span>
                    <span><?php echo e($customerLat && $customerLng ? ($customerLat.', '.$customerLng) : '-'); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">السائق</span>
                    <span><?php echo e($delivery?->driver?->name ?? ($order->assignedDriver?->name ?? '-')); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">حالة المهمة</span>
                    <span><?php echo e($delivery?->status ?? '-'); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">تم الإسناد</span>
                    <span><?php echo e($delivery?->assigned_at?->format('Y-m-d H:i') ?? '-'); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">تم التسليم</span>
                    <span><?php echo e($delivery?->delivered_at?->format('Y-m-d H:i') ?? '-'); ?></span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">سجل الحالة</div>
            <div class="p-5 space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $oldStatus = $log->old_values['status'] ?? null;
                        $newStatus = $log->new_values['status'] ?? null;
                        $oldPay = $log->old_values['payment_status'] ?? null;
                        $newPay = $log->new_values['payment_status'] ?? null;
                    ?>
                    <div class="border border-gray-100 rounded-xl p-3">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-800"><?php echo e($log->action); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($log->created_at?->format('Y-m-d H:i')); ?></div>
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            <?php if($newStatus): ?>
                                <span>الحالة: <?php echo e($oldStatus ?: '-'); ?> → <?php echo e($newStatus); ?></span>
                            <?php endif; ?>
                            <?php if($newPay): ?>
                                <span class="mx-1">•</span>
                                <span>الدفع: <?php echo e($oldPay ?: '-'); ?> → <?php echo e($newPay); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-sm text-gray-500">لا يوجد سجل</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function () {
        const el = document.getElementById('order-map');
        if (!el) return;

        if (typeof window.L === 'undefined') {
            el.innerHTML = '<div class="h-full w-full flex items-center justify-center text-sm text-gray-500">فشل تحميل الخريطة (Leaflet). تحقق من الاتصال بالإنترنت.</div>';
            return;
        }

        const routeUrl = <?php echo json_encode(route('dashboard.cs.orders.route', $order), 512) ?>;
        const initialCustomerLat = <?php echo json_encode($customerLat, 15, 512) ?>;
        const initialCustomerLng = <?php echo json_encode($customerLng, 15, 512) ?>;
        const initialDriverLat = <?php echo json_encode($driverLat, 15, 512) ?>;
        const initialDriverLng = <?php echo json_encode($driverLng, 15, 512) ?>;
        const initialTrack = <?php echo json_encode($driverTrack->map(fn ($p) => ['lat' => (float) $p->latitude, 'lng' => (float) $p->longitude, 't' => optional($p->recorded_at)->toIso8601String()])->values()) ?>;

        const defaultCenter = [33.5138, 36.2765];
        const map = L.map('order-map').setView(defaultCenter, 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '' }).addTo(map);
        setTimeout(() => map.invalidateSize(), 200);

        let customerMarker = null;
        let driverMarker = null;
        let polyline = null;

        function applyRouteData(payload) {
            const bounds = [];
            const customerLat = payload?.customer?.lat ?? null;
            const customerLng = payload?.customer?.lng ?? null;
            const driverLat = payload?.driver?.lat ?? null;
            const driverLng = payload?.driver?.lng ?? null;
            const track = Array.isArray(payload?.track) ? payload.track : [];

            if (customerLat && customerLng) {
                const pos = [customerLat, customerLng];
                if (!customerMarker) {
                    customerMarker = L.marker(pos).addTo(map);
                    customerMarker.bindPopup('موقع العميل');
                } else {
                    customerMarker.setLatLng(pos);
                }
                bounds.push(pos);
            }

            if (driverLat && driverLng) {
                const pos = [driverLat, driverLng];
                if (!driverMarker) {
                    driverMarker = L.circleMarker(pos, { radius: 7, color: '#059669', fillColor: '#10b981', fillOpacity: 0.9 }).addTo(map);
                    driverMarker.bindPopup('موقع السائق (آخر نقطة)');
                } else {
                    driverMarker.setLatLng(pos);
                }
                bounds.push(pos);
            }

            const pts = track.filter(p => p && p.lat && p.lng).map(p => [p.lat, p.lng]);
            if (polyline) {
                map.removeLayer(polyline);
                polyline = null;
            }
            if (pts.length >= 2) {
                polyline = L.polyline(pts, { color: '#4f46e5', weight: 4, opacity: 0.9 }).addTo(map);
                bounds.push(...pts);
            } else if (driverLat && driverLng && customerLat && customerLng) {
                polyline = L.polyline([[driverLat, driverLng], [customerLat, customerLng]], { color: '#4f46e5', weight: 3, dashArray: '6,8' }).addTo(map);
                bounds.push([driverLat, driverLng], [customerLat, customerLng]);
            }

            if (bounds.length) {
                map.fitBounds(bounds, { padding: [20, 20] });
            }
        }

        applyRouteData({
            customer: { lat: initialCustomerLat, lng: initialCustomerLng },
            driver: { lat: initialDriverLat, lng: initialDriverLng },
            track: initialTrack,
        });

        let lastPayload = null;
        async function fetchRoute() {
            try {
                const res = await fetch(routeUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data || !data.success) return;
                const serialized = JSON.stringify([data.customer, data.driver, data.track]);
                if (serialized === lastPayload) return;
                lastPayload = serialized;
                applyRouteData(data);
            } catch (e) {}
        }

        fetchRoute();
        setInterval(fetchRoute, 10000);
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/cs/order.blade.php ENDPATH**/ ?>