
<?php $__env->startSection('content'); ?>
<?php
    $title = 'تفاصيل الطلب';
    $subtitle = 'معلومات الطلب وموقع التوصيل والتوقيعات';
    $orderNumber = $order->order_number ?? ('#'.$order->id);
    $total = (float) ($order->total ?? $order->total_amount ?? 0);
    $subtotal = (float) ($order->subtotal ?? 0);
    $deliveryCost = (float) ($order->delivery_cost ?? $order->shipping_cost ?? 0);
    $statusLabels = [
        'pending' => 'قيد الانتظار',
        'confirmed' => 'مؤكد',
        'processing' => 'قيد التجهيز',
        'assigned' => 'معيّن',
        'out_for_delivery' => 'خرج للتوصيل',
        'delivered' => 'تم التسليم',
        'done' => 'مكتمل',
        'cancelled' => 'ملغي',
    ];
?>

<?php if(session('success')): ?>
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('dashboard.driver.index')); ?>" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
            <span>الطلبات</span>
        </a>
    </div>
    <div class="text-sm font-semibold text-gray-800"><?php echo e($orderNumber); ?></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">العميل</div>
        <div class="text-lg font-semibold text-gray-900 mt-1"><?php echo e($order->recipient_name ?? $order->customer?->name ?? $order->user?->name ?? '—'); ?></div>
        <div class="text-sm text-gray-600 mt-1"><?php echo e($order->phone ?? '—'); ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">الحالة</div>
        <div class="text-lg font-semibold text-gray-900 mt-1"><?php echo e($statusLabels[$order->status ?? ''] ?? ($order->status ?? '—')); ?></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">الإجمالي</div>
        <div class="text-lg font-semibold text-emerald-600 mt-1">$<?php echo e(number_format($total, 2)); ?></div>
    </div>
</div>

<?php if(! in_array($order->status ?? '', ['out_for_delivery', 'delivered', 'done'])): ?>
    <form method="POST" action="<?php echo e(route('dashboard.driver.orders.receive', $order)); ?>" class="mb-6">
        <?php echo csrf_field(); ?>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700">
            <i class="fas fa-check"></i>
            استلام الطلب
        </button>
    </form>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <div class="font-semibold text-gray-900">الخريطة</div>
        <?php if($googleMapsUrl): ?>
            <a href="<?php echo e($googleMapsUrl); ?>" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800">
                <i class="fab fa-google ml-1"></i> فتح في خرائط جوجل
            </a>
        <?php endif; ?>
    </div>
    <div class="p-5">
        <div id="order-map" class="w-full rounded-xl border border-gray-100" style="height:320px;"></div>
        <?php if(! $customerLat || ! $customerLng): ?>
            <div class="mt-3 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                لا توجد إحداثيات للطلب.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">محتوى الطلب</div>
        <div class="p-5">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">المنتج</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">الكمية</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $order->items ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo e($item->product->name ?? $item->product_name ?? '—'); ?></td>
                            <td class="px-4 py-2 text-center"><?php echo e($item->quantity ?? 0); ?></td>
                            <td class="px-4 py-2">$<?php echo e(number_format((float) ($item->total_price ?? 0), 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="mt-3 pt-3 border-t border-gray-100 text-sm">
                <div class="flex justify-between"><span>المجموع الفرعي</span><span>$<?php echo e(number_format($subtotal, 2)); ?></span></div>
                <div class="flex justify-between"><span>التوصيل</span><span>$<?php echo e(number_format($deliveryCost, 2)); ?></span></div>
                <div class="flex justify-between font-semibold mt-2"><span>الإجمالي</span><span>$<?php echo e(number_format($total, 2)); ?></span></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">بيانات التوصيل</div>
        <div class="p-5 text-sm text-gray-700 space-y-2">
            <div><span class="text-gray-500">العنوان:</span> <?php echo e($order->village ?? '-'); ?> <?php echo e($order->address_note ? ' - '.$order->address_note : ''); ?></div>
            <div><span class="text-gray-500">طريقة الدفع:</span> <?php echo e($order->payment_method ?? '-'); ?></div>
        </div>
    </div>
</div>

<?php if(in_array($order->status ?? '', ['out_for_delivery', 'assigned']) && ! in_array($order->status ?? '', ['delivered', 'done'])): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-base font-bold text-gray-800 mb-4">تم التسليم — توقيع المندوب وتوقيع العميل</h3>
    <form method="POST" action="<?php echo e(route('dashboard.driver.orders.deliver', $order)); ?>" class="deliver-form space-y-4">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm font-semibold text-gray-700 mb-2">توقيع المندوب</div>
                <canvas width="560" height="200" class="sig-canvas max-w-full h-40 border border-gray-200 rounded-lg touch-none" style="background:#fff;" data-role="driver"></canvas>
                <button type="button" class="mt-1 text-xs text-gray-600 underline clear-sig">مسح</button>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-700 mb-2">توقيع العميل</div>
                <canvas width="560" height="200" class="sig-canvas max-w-full h-40 border border-gray-200 rounded-lg touch-none" style="background:#fff;" data-role="customer"></canvas>
                <button type="button" class="mt-1 text-xs text-gray-600 underline clear-sig">مسح</button>
            </div>
        </div>
        <input type="hidden" name="driver_signature" class="driver-sig-input">
        <input type="hidden" name="customer_signature" class="customer-sig-input">
        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700">
            <i class="fas fa-signature"></i>
            حفظ التسليم والتوقيعات
        </button>
    </form>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const el = document.getElementById('order-map');
    if (el && typeof window.L !== 'undefined') {
        const customerLat = <?php echo json_encode($customerLat, 15, 512) ?>;
        const customerLng = <?php echo json_encode($customerLng, 15, 512) ?>;
        const map = L.map('order-map').setView([33.5138, 36.2765], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '' }).addTo(map);
        if (customerLat && customerLng) {
            L.marker([customerLat, customerLng]).addTo(map).bindPopup('موقع التوصيل');
            map.setView([customerLat, customerLng], 14);
        }
        setTimeout(() => map.invalidateSize(), 200);
    }

    document.querySelectorAll('.deliver-form').forEach(function (form) {
        function fillWhite(c) {
            const ctx = c.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, c.width, c.height);
        }
        function bindCanvas(canvas) {
            const ctx = canvas.getContext('2d');
            let drawing = false;
            fillWhite(canvas);
            const pos = (e) => {
                const r = canvas.getBoundingClientRect();
                const scaleX = canvas.width / r.width;
                const scaleY = canvas.height / r.height;
                const t = e.touches ? e.touches[0] : e;
                return { x: (t.clientX - r.left) * scaleX, y: (t.clientY - r.top) * scaleY };
            };
            const start = (e) => { drawing = true; ctx.beginPath(); const p = pos(e); ctx.moveTo(p.x, p.y); };
            const draw = (e) => { if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); };
            const stop = () => { drawing = false; };
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#111827';
            canvas.addEventListener('mousedown', start);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stop);
            canvas.addEventListener('mouseleave', stop);
            canvas.addEventListener('touchstart', (e) => { e.preventDefault(); start(e); }, { passive: false });
            canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e); }, { passive: false });
            canvas.addEventListener('touchend', stop);
        }
        form.querySelectorAll('.sig-canvas').forEach(bindCanvas);
        form.querySelectorAll('.clear-sig').forEach(function (btn, idx) {
            btn.addEventListener('click', function () {
                const c = form.querySelectorAll('.sig-canvas')[idx];
                const ctx = c.getContext('2d');
                ctx.clearRect(0, 0, c.width, c.height);
                fillWhite(c);
            });
        });
        form.addEventListener('submit', function () {
            const d = form.querySelector('[data-role="driver"]');
            const c = form.querySelector('[data-role="customer"]');
            if (!d || !c) return;
            form.querySelector('.driver-sig-input').value = d.toDataURL('image/png');
            form.querySelector('.customer-sig-input').value = c.toDataURL('image/png');
        });
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/driver/order.blade.php ENDPATH**/ ?>