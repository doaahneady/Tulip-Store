@extends('dashboards.layouts.app')
@section('content')
@php $title = 'لوحة مشرف السائقين'; $subtitle = 'نظرة عامة على السائقين والتوصيلات والأداء'; @endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.supervisor.live-tracking') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-map-marked-alt"></i>
            <span>التتبع المباشر</span>
        </a>
        <a href="{{ route('dashboard.supervisor.drivers') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-truck"></i>
            <span>السائقون</span>
        </a>
        <a href="{{ route('dashboard.supervisor.vehicles') }}" class="inline-flex items-center gap-2 bg-sky-600 text-white px-4 py-2 rounded-xl hover:bg-sky-700 transition">
            <i class="fas fa-car"></i>
            <span>المركبات</span>
        </a>
        <a href="{{ route('dashboard.supervisor.order-assignment') }}" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-clipboard-list"></i>
            <span>تعيين الطلبات</span>
        </a>
        <a href="{{ route('dashboard.supervisor.route-optimization') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-route"></i>
            <span>تحسين المسارات</span>
        </a>
        <a href="{{ route('dashboard.supervisor.vehicle-maintenance') }}" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-xl hover:bg-orange-700 transition">
            <i class="fas fa-tools"></i>
            <span>صيانة المركبات</span>
        </a>
        <a href="{{ route('dashboard.supervisor.delivery-proof') }}" class="inline-flex items-center gap-2 bg-fuchsia-600 text-white px-4 py-2 rounded-xl hover:bg-fuchsia-700 transition">
            <i class="fas fa-file-signature"></i>
            <span>مراجعة الإثبات</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.index') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.my-attendance.index') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-4">
    <x-dashboard.stat-card title="إجمالي السائقين" :value="number_format($metrics['total_drivers'] ?? 0)" icon="fas fa-truck" color="teal" />
    <x-dashboard.stat-card title="سائقون نشطون" :value="number_format($metrics['active_drivers'] ?? 0)" icon="fas fa-user-check" color="green" />
    <x-dashboard.stat-card title="سائقون على توصيل" :value="number_format($metrics['drivers_on_delivery'] ?? 0)" icon="fas fa-route" color="indigo" />
    <x-dashboard.stat-card title="طلبات قيد التعيين" :value="number_format($metrics['orders_awaiting_assignment'] ?? 0)" icon="fas fa-clipboard-list" color="orange" />
    <x-dashboard.stat-card title="طلبات قيد النقل" :value="number_format($metrics['orders_in_transit'] ?? 0)" icon="fas fa-shipping-fast" color="blue" />
    <x-dashboard.stat-card title="مكتمل اليوم" :value="number_format($metrics['completed_today'] ?? 0)" icon="fas fa-check-double" color="purple" />
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4 mb-4">
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 xl:col-span-7">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-black text-gray-800">خريطة تتبع مباشر</h3>
        <a href="{{ route('dashboard.supervisor.live-tracking') }}" class="text-sm text-indigo-600">تفاصيل</a>
    </div>
    <div id="overview-map" class="w-full h-[240px] rounded-xl border border-gray-200"></div>
</div>

<div class="xl:col-span-5 space-y-4">
    <x-dashboard.collapsible title="حالة التشغيل" icon="fas fa-gauge-high" subtitle="السائقون والتوصيلات والمركبات">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <x-dashboard.stat-card title="متاحون" :value="number_format($metrics['available_drivers'] ?? 0)" icon="fas fa-check-circle" color="emerald" />
            <x-dashboard.stat-card title="غير متصلين" :value="number_format($metrics['offline_drivers'] ?? 0)" icon="fas fa-power-off" color="gray" />
            <x-dashboard.stat-card title="في استراحة" :value="number_format($metrics['on_break_drivers'] ?? 0)" icon="fas fa-coffee" color="yellow" />
            <x-dashboard.stat-card title="تقييم السائقين" :value="number_format($metrics['avg_driver_rating'] ?? 0, 1)" icon="fas fa-star" color="amber" />
            <x-dashboard.stat-card title="تعيينات معلقة" :value="number_format($metrics['pending_assignments'] ?? 0)" icon="fas fa-hourglass-half" color="pink" />
            <x-dashboard.stat-card title="توصيلات نشطة" :value="number_format($metrics['active_deliveries'] ?? 0)" icon="fas fa-truck-loading" color="orange" />
            <x-dashboard.stat-card title="فشل اليوم" :value="number_format($metrics['failed_deliveries'] ?? 0)" icon="fas fa-times-circle" color="red" />
            <x-dashboard.stat-card title="مركبات في الصيانة" :value="number_format($metrics['vehicles_in_maintenance'] ?? 0)" icon="fas fa-tools" color="purple" />
        </div>
    </x-dashboard.collapsible>

    <x-dashboard.collapsible title="توصيلات اليوم" icon="fas fa-truck-fast" subtitle="نظرة عامة سريعة" :open="true">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="p-3 border border-gray-600 rounded-xl bg-gray-800">
                <div class="text-xs text-gray-300 font-semibold">إجمالي</div>
                <div class="mt-1 text-2xl font-black text-white">{{ number_format($metrics['deliveries_today_total'] ?? 0) }}</div>
            </div>
            <div class="p-3 border border-gray-600 rounded-xl bg-blue-800">
                <div class="text-xs text-blue-100 font-semibold">قيد التنفيذ</div>
                <div class="mt-1 text-2xl font-black text-white">{{ number_format($metrics['in_progress_today'] ?? 0) }}</div>
            </div>
            <div class="p-3 border border-gray-600 rounded-xl bg-green-800">
                <div class="text-xs text-green-100 font-semibold">مكتمل</div>
                <div class="mt-1 text-2xl font-black text-white">{{ number_format($metrics['completed_today'] ?? 0) }}</div>
            </div>
            <div class="p-3 border border-gray-600 rounded-xl bg-orange-700">
                <div class="text-xs text-orange-100 font-semibold">قيد التعيين</div>
                <div class="mt-1 text-2xl font-black text-white">{{ number_format($metrics['pending_today'] ?? 0) }}</div>
            </div>
        </div>
    </x-dashboard.collapsible>
</div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4 mb-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 xl:col-span-4">
        <h3 class="text-base font-black text-gray-800 mb-3">مؤشرات الأداء</h3>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">نسبة التسليم في الوقت</span>
                <span class="text-sm font-semibold text-indigo-600">{{ number_format($metrics['on_time_delivery_rate'] ?? 0, 1) }}%</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">متوسط زمن التسليم</span>
                <span class="text-sm font-semibold">{{ number_format($metrics['avg_delivery_time'] ?? 0) }} دقيقة</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">كفاءة السائقين</span>
                <span class="text-sm font-semibold">{{ number_format($metrics['driver_efficiency'] ?? 0, 1) }}%</span>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('dashboard.supervisor.route-optimization') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                تحسين المسارات
            </a>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 xl:col-span-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-black text-gray-800">قائمة السائقين</h3>
            <a href="{{ route('dashboard.supervisor.drivers') }}" class="text-sm text-indigo-600 hover:text-indigo-800">عرض الكل</a>
        </div>
        <div class="overflow-auto max-h-[380px]">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">السائق</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الحالة</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">موقع حالي</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">توصيلات نشطة</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">اتصال</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(($metrics['drivers_sample'] ?? []) as $driver)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $driver->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($driver->availability==='available') bg-green-100 text-green-800
                                    @elseif($driver->availability==='busy') bg-blue-100 text-blue-800
                                    @elseif($driver->availability==='on_break') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ str_replace('_',' ',$driver->availability) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if($driver->current_latitude && $driver->current_longitude)
                                    {{ number_format($driver->current_latitude,4) }}, {{ number_format($driver->current_longitude,4) }}
                                @else
                                    <span class="text-gray-400">غير متوفر</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $driver->active_assignments_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="tel:{{ $driver->phone }}" class="text-indigo-600 hover:text-indigo-900">اتصال</a>
                            </td>
                        </tr>
                    @endforeach
                    @if(empty($metrics['drivers_sample']) || count($metrics['drivers_sample'])===0)
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد بيانات</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 xl:col-span-7">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-black text-gray-800">طلبات غير معينة</h3>
            <a href="{{ route('dashboard.supervisor.order-assignment') }}" class="text-sm text-indigo-600 hover:text-indigo-800">تعيين</a>
        </div>
        <div class="overflow-auto max-h-[360px]">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">رقم الطلب</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">العميل</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">العنوان</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(($metrics['unassigned_orders_sample'] ?? []) as $order)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $order->recipient_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $order->address_note ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('dashboard.supervisor.order-assignment') }}" class="text-indigo-600 hover:text-indigo-900">تعيين</a>
                            </td>
                        </tr>
                    @endforeach
                    @if(empty($metrics['unassigned_orders_sample']) || count($metrics['unassigned_orders_sample'])===0)
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد طلبات</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 xl:col-span-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-black text-gray-800">مسارات نشطة</h3>
            <a href="{{ route('dashboard.supervisor.route-optimization') }}" class="text-sm text-indigo-600 hover:text-indigo-800">عرض</a>
        </div>
        <ul class="divide-y divide-gray-200 max-h-[360px] overflow-auto">
            @foreach(($metrics['active_routes'] ?? []) as $route)
                <li class="py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">{{ $route->driver->name ?? '—' }}</div>
                            <div class="text-sm text-gray-500">تاريخ المسار: {{ optional($route->route_date)->format('Y-m-d') ?? '—' }}</div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $route->status }}
                        </span>
                    </div>
                </li>
            @endforeach
            @if(empty($metrics['active_routes']) || count($metrics['active_routes'])===0)
                <li class="py-3 text-center text-sm text-gray-500">لا توجد بيانات</li>
            @endif
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const overviewMapEl = document.getElementById('overview-map');
    if (overviewMapEl) {
        if (typeof window.L === 'undefined') {
            overviewMapEl.innerHTML = '<div class="h-full w-full flex items-center justify-center text-sm text-gray-500">فشل تحميل الخريطة (Leaflet). تحقق من الاتصال بالإنترنت.</div>';
        } else {
            const map = L.map('overview-map').setView([33.5138, 36.2765], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '' }).addTo(map);
            setTimeout(() => map.invalidateSize(), 200);
            fetch('{{ route('dashboard.supervisor.api.driver-locations') }}').then(r => r.json()).then(data => {
                const markers = [];
                data.forEach(d => {
                    if (d.lat !== null && d.lat !== '' && d.lng !== null && d.lng !== '') {
                        const marker = L.circleMarker([d.lat, d.lng], { radius: 6, color: '#4f46e5', fillColor: '#6366f1', fillOpacity: 0.8 }).addTo(map);
                        marker.bindPopup('<div class="text-sm font-semibold">'+d.name+'</div>');
                        markers.push(marker);
                    }
                });
                if (markers.length) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.2));
                }
                setTimeout(() => map.invalidateSize(), 0);
            });
        }
    }
</script>
@endpush
