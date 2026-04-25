@extends('dashboards.layouts.app')

@section('content')
@php
    $title = 'تفاصيل طلب المارت';
    $subtitle = 'عرض جميع معلومات الطلب';

    $orderNumber = $order->order_number ?? ('#'.$order->id);
    $customerName = $order->customer->name ?? $order->recipient_name ?? 'Customer';
    if (is_array($customerName)) {
        $customerName = $customerName['ar'] ?? ($customerName['en'] ?? '');
    }
    $total = $order->total_amount ?? $order->total ?? 0;
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.admin.mart.orders.index') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
            <span>طلبات المارت</span>
        </a>
    </div>
    <div class="text-sm text-gray-600">
        <span class="font-semibold">{{ $orderNumber }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">العميل</div>
        <div class="text-lg font-semibold text-gray-900 mt-1">{{ $customerName }}</div>
        <div class="text-sm text-gray-600 mt-1">
            {{ $order->customer->email ?? '' }}
            @if(($order->customer->email ?? null) && ($order->phone ?? null)) <span class="mx-1">•</span> @endif
            {{ $order->phone ?? '' }}
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">الحالة</div>
        <div class="text-lg font-semibold text-gray-900 mt-1">{{ $order->status ?? '-' }}</div>
        <div class="text-sm text-gray-600 mt-1">الدفع: {{ $order->payment_status ?? '-' }}</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="text-xs text-gray-500">الإجمالي</div>
        <div class="text-lg font-semibold text-gray-900 mt-1">{{ number_format((float) $total, 2) }}</div>
        <div class="text-sm text-gray-600 mt-1">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
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
                        @forelse($order->items as $item)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name ?? $item->product_name ?? ('Product #'.$item->product_id) }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->product_sku ?? '' }}</div>
                                </td>
                                <td class="px-4 py-2 text-gray-700">{{ $item->quantity ?? 0 }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ number_format((float) ($item->unit_price ?? 0), 2) }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ number_format((float) ($item->total_price ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد عناصر</td>
                            </tr>
                        @endforelse
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
                    <span class="text-gray-500">العنوان</span>
                    <span class="text-right">{{ $order->address_note ?? ($order->shipping_address['address'] ?? '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">القرية</span>
                    <span>{{ $order->village ?? ($order->shipping_address['village'] ?? '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">إحداثيات العميل</span>
                    <span>{{ ($customerLat && $customerLng) ? ($customerLat.', '.$customerLng) : '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">السائق</span>
                    <span>{{ ($delivery && $delivery->driver && $delivery->driver->name) ? $delivery->driver->name : (($order->assignedDriver && $order->assignedDriver->name) ? $order->assignedDriver->name : '-') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">حالة المهمة</span>
                    <span>{{ $delivery->status ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">تم الإسناد</span>
                    <span>{{ $delivery && $delivery->assigned_at ? $delivery->assigned_at->format('Y-m-d H:i') : '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">تم التسليم</span>
                    <span>{{ $delivery && $delivery->delivered_at ? $delivery->delivered_at->format('Y-m-d H:i') : '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">الخريطة</div>
            <div class="p-5">
                <div id="order-map" class="w-full rounded-xl border border-gray-100" style="height:420px;"></div>
                @if(! $customerLat || ! $customerLng)
                    <div class="mt-3 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                        لا توجد إحداثيات للطلب، لذلك سيتم عرض الخريطة بدون مسار.
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100 font-semibold text-gray-900">سجل الحالة</div>
            <div class="p-5 space-y-2">
                @forelse($auditLogs as $log)
                    @php
                        $oldStatus = $log->old_values['status'] ?? null;
                        $newStatus = $log->new_values['status'] ?? null;
                        $oldPay = $log->old_values['payment_status'] ?? null;
                        $newPay = $log->new_values['payment_status'] ?? null;
                    @endphp
                    <div class="border border-gray-100 rounded-xl p-3">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-800">{{ $log->action }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at ? $log->created_at->format('Y-m-d H:i') : '' }}</div>
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            @if($newStatus)
                                <span>الحالة: {{ $oldStatus ?: '-' }} → {{ $newStatus }}</span>
                            @endif
                            @if($newPay)
                                <span class="mx-1">•</span>
                                <span>الدفع: {{ $oldPay ?: '-' }} → {{ $newPay }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">لا يوجد سجل</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function () {
        var el = document.getElementById('order-map');
        if (!el || typeof L === 'undefined') return;

        var customerLat = {{ $customerLat ? (float) $customerLat : 'null' }};
        var customerLng = {{ $customerLng ? (float) $customerLng : 'null' }};
        var driverLat = {{ $driverLat ? (float) $driverLat : 'null' }};
        var driverLng = {{ $driverLng ? (float) $driverLng : 'null' }};
        var track = @json($driverTrack->map(fn ($p) => ['lat' => (float) $p->latitude, 'lng' => (float) $p->longitude])->values()->all());

        var centerLat = customerLat || driverLat || 33.5138;
        var centerLng = customerLng || driverLng || 36.2765;

        var map = L.map('order-map').setView([centerLat, centerLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var bounds = [];
        if (customerLat && customerLng) {
            L.marker([customerLat, customerLng]).addTo(map).bindPopup('العميل');
            bounds.push([customerLat, customerLng]);
        }
        if (driverLat && driverLng) {
            L.marker([driverLat, driverLng]).addTo(map).bindPopup('السائق');
            bounds.push([driverLat, driverLng]);
        }
        if (Array.isArray(track) && track.length) {
            var pts = track.map(function (p) { return [p.lat, p.lng]; });
            L.polyline(pts, { color: '#1d8bff', weight: 4, opacity: 0.9 }).addTo(map);
            pts.forEach(function (pt) { bounds.push(pt); });
        }
        if (bounds.length) {
            map.fitBounds(bounds, { padding: [20, 20] });
        }
    })();
</script>
@endsection

