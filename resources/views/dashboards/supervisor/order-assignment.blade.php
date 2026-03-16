@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تعيين الطلبات'; $subtitle = 'اضغط على الطلب لفتح التفاصيل وتعيين سائق'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Unassigned Orders - Card Grid --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <h3 class="text-lg font-bold text-gray-800">طلبات غير معينة</h3>
                <form method="GET" action="{{ route('dashboard.supervisor.order-assignment') }}" class="flex items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="بحث بالرقم / الاسم / المتجر"
                        class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm w-64"
                    />
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition text-sm">
                        <i class="fas fa-search"></i>
                        <span>بحث</span>
                    </button>
                    <a href="{{ route('dashboard.supervisor.order-assignment') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm">مسح</a>
                </form>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($pendingOrders as $order)
                    <button type="button"
                            data-order-id="{{ $order->id }}"
                            class="js-open-assign order-card-btn text-right p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-500 hover:bg-indigo-50/50 transition-all duration-200 w-full group">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-gray-900 text-base">#{{ $order->order_number ?? $order->id }}</div>
                                <div class="text-sm text-gray-600 mt-0.5">{{ $order->recipient_name ?? optional($order->customer)->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ optional($order->store)->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="font-semibold">Type:</span> {{ $order->delivery_method ?? '—' }}
                                    <span class="mx-1">•</span>
                                    <span class="font-semibold">Date:</span> {{ $order->estimated_delivery ? \Carbon\Carbon::parse($order->estimated_delivery)->format('Y-m-d') : '—' }}
                                </div>
                                <div class="text-sm font-semibold text-emerald-600 mt-2">${{ number_format($order->total ?? $order->total_amount ?? 0, 2) }}</div>
                            </div>
                            <span class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition">
                                <i class="fas fa-user-plus"></i>
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>
            @if($pendingOrders->count() === 0)
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 opacity-40"></i>
                    <p>لا توجد طلبات جاهزة للتعيين</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar: Available Drivers + Active Assignments --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">سائقون متاحون</h3>
            <ul class="space-y-3">
                @foreach($availableDrivers as $driver)
                    <li class="flex items-center justify-between p-3 rounded-xl bg-green-50 border border-green-100">
                        <div>
                            <div class="font-medium text-gray-900">{{ optional($driver->user)->name ?? optional($driver->user)->user_full_name ?? 'Driver #'.$driver->id }}</div>
                            <div class="text-xs text-gray-500">{{ optional($driver->user)->phone ?? optional($driver->user)->mobile ?? '—' }}</div>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-200 text-green-800">متاح</span>
                    </li>
                @endforeach
                @if($availableDrivers->count() === 0)
                    <li class="py-4 text-center text-sm text-gray-500">لا يوجد سائقون متاحون</li>
                @endif
            </ul>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">تعيينات نشطة</h3>
            <ul class="space-y-3">
                @foreach($activeAssignments as $assign)
                    <li class="flex items-center justify-between p-3 rounded-xl border border-gray-200">
                        <div>
                            <div class="font-medium text-gray-900">#{{ optional($assign->order)->order_number ?? $assign->order_id }}</div>
                            <div class="text-sm text-gray-500">{{ optional(optional($assign->driver)->user)->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">
                                {{ optional($assign->order)->delivery_method ?? '—' }}
                                <span class="mx-1">•</span>
                                {{ optional($assign->order)->estimated_delivery ? \Carbon\Carbon::parse(optional($assign->order)->estimated_delivery)->format('Y-m-d') : '—' }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $assign->status }}</span>
                            @if(optional($assign->order)->status === 'out_for_delivery')
                                <form method="POST" action="{{ route('dashboard.supervisor.orders.change-status', $assign->order_id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="delivered">
                                    <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                                        تسليم
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
                @if($activeAssignments->count() === 0)
                    <li class="py-4 text-center text-sm text-gray-500">لا توجد تعيينات</li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- Assignment Modal --}}
<div id="assignModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog">
    <div class="flex items-center justify-center min-h-screen px-4 py-4">
        <div class="fixed inset-0 bg-gray-900/60 transition-opacity" onclick="closeAssignModal()"></div>
        <div class="relative inline-block w-full max-w-lg p-0 my-4 overflow-hidden text-right align-middle transition-all transform bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-600">
            <div class="px-4 py-3 bg-indigo-600 text-white rounded-t-2xl">
                <h3 class="text-base font-bold" id="modal-title"><i class="fas fa-file-invoice mr-2"></i>تعيين سائق للطلب</h3>
                <button type="button" onclick="closeAssignModal()" class="absolute left-3 top-3 w-7 h-7 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="px-4 py-3 max-h-[calc(100vh-12rem)] overflow-y-auto">
                <div id="orderDetailsContent" class="mb-3 text-sm"></div>
                <div id="orderMap" class="w-full h-40 rounded-lg border border-gray-200 dark:border-gray-600 mb-3 bg-gray-100 dark:bg-gray-700" style="min-height:160px;"></div>
                <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                    <a id="googleMapsLink" href="#" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800">
                    <i class="fab fa-google"></i> فتح في خرائط جوجل
                    </a>
                    <div id="orderCoords" class="text-xs text-gray-600 dark:text-gray-300"></div>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">اختر السائق</label>
                        <select id="driverSelect" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700">
                            <option value="">-- اختر سائق --</option>
                            @foreach($availableDrivers as $driver)
                                @php $phone = optional($driver->user)->phone ?? optional($driver->user)->mobile ?? ''; @endphp
                                <option value="{{ $driver->id }}" data-phone="{{ $phone }}">{{ optional($driver->user)->name ?? optional($driver->user)->user_full_name ?? 'Driver #'.$driver->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">ملاحظات التوصيل (اختياري)</label>
                        <textarea id="deliveryNotes" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700" placeholder="أضف ملاحظات للسائق..."></textarea>
                    </div>
                    <a id="whatsAppBtn" href="#" target="_blank" class="hidden w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition text-sm font-semibold">
                        <i class="fab fa-whatsapp"></i>
                        <span>إرسال الموقع على واتساب للسائق</span>
                    </a>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600 flex gap-2 justify-end">
                <button type="button" onclick="closeAssignModal()" class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm">إلغاء</button>
                <button type="button" id="assignBtn" onclick="submitAssign()" class="px-4 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold text-sm">
                    <i class="fas fa-check-circle mr-2"></i>تعيين
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.leaflet-routing-container { display: none !important; }
#orderMap.leaflet-container { background: #e5e7eb !important; }
.db-next #orderMap.leaflet-container { background: #374151 !important; }
#orderMap .leaflet-tile-pane { z-index: 1; }
#orderMap .leaflet-overlay-pane { z-index: 2; }
#orderMap .leaflet-marker-pane { z-index: 3; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const orderDetailsUrl = '{{ url("/dashboard/supervisor/order-assignment/order") }}';
const assignUrl = '{{ route("dashboard.supervisor.assign-order") }}';
const defaultCoords = [32.7125, 36.5669];

let currentOrderId = null;
let currentOrderMeta = null;
let orderMap = null;
let orderMapMarker = null;

document.querySelectorAll('.js-open-assign').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-order-id');
        if (!id) return;
        openAssignModal(parseInt(id, 10));
    });
});

function openAssignModal(orderId) {
    currentOrderId = orderId;
    currentOrderMeta = null;
    document.getElementById('assignModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('driverSelect').value = '';
    document.getElementById('deliveryNotes').value = '';
    document.getElementById('orderCoords').textContent = '';
    document.getElementById('whatsAppBtn').classList.add('hidden');
    document.getElementById('orderDetailsContent').innerHTML = '<div class="animate-pulse py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">جاري تحميل التفاصيل...</p></div>';

    fetch(`${orderDetailsUrl}/${orderId}`)
        .then(r => r.json())
        .then(order => {
            const rawLat = parseFloat(order.latitude);
            const rawLng = parseFloat(order.longitude);
            const hasCoords = Number.isFinite(rawLat) && Number.isFinite(rawLng);
            const lat = hasCoords ? rawLat : defaultCoords[0];
            const lng = hasCoords ? rawLng : defaultCoords[1];
            const items = order.items || [];
            const itemsHtml = items.length
                ? items.map(i => `<div class="flex justify-between py-1 text-sm"><span>${(i.product?.name || i.product_name || 'منتج')} × ${i.quantity}</span><span class="font-semibold">$${parseFloat(i.subtotal || i.total_price || 0).toFixed(2)}</span></div>`).join('')
                : '<div class="text-sm text-gray-500">لا توجد منتجات</div>';

            currentOrderMeta = {
                id: order.id,
                orderNumber: order.order_number || order.id,
                customer: order.recipient_name || (order.customer?.name) || '—',
                phone: order.phone || (order.customer?.phone) || '—',
                village: order.village || '—',
                addressNote: order.address_note || '',
                deliveryMethod: order.delivery_method || '—',
                estimatedDelivery: order.estimated_delivery || '',
                hasCoords,
                lat: hasCoords ? rawLat : null,
                lng: hasCoords ? rawLng : null,
                mapLat: lat,
                mapLng: lng,
            };

            document.getElementById('orderDetailsContent').innerHTML = `
                <div class="grid grid-cols-2 gap-2 mb-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-800 dark:text-gray-200">
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">رقم الطلب</span><br><strong>${order.order_number || order.id}</strong></div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">العميل</span><br><strong>${order.recipient_name || (order.customer?.name) || '—'}</strong></div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">الهاتف</span><br>${order.phone || (order.customer?.phone) || '—'}</div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">المنطقة</span><br>${order.village || '—'}</div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">المجموع</span><br><strong class="text-emerald-600 dark:text-emerald-400">$${parseFloat(order.total || order.total_amount || 0).toFixed(2)}</strong></div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">الدفع</span><br>${order.payment_method === 'cash' ? 'نقداً' : 'مدفوع'}</div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">نوع التوصيل</span><br>${order.delivery_method || '—'}</div>
                    <div class="text-xs"><span class="text-gray-500 dark:text-gray-400">تاريخ التوصيل</span><br>${order.estimated_delivery ? new Date(order.estimated_delivery).toLocaleDateString('ar-SA') : '—'}</div>
                </div>
                ${order.address_note ? `<div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg text-xs mb-2 text-gray-800 dark:text-gray-200"><strong>ملاحظات:</strong> ${order.address_note}</div>` : ''}
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden mb-2">
                    <div class="px-3 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 font-semibold text-indigo-800 dark:text-indigo-200 text-xs">المنتجات</div>
                    <div class="px-3 py-2 divide-y divide-gray-200 dark:divide-gray-600 text-gray-800 dark:text-gray-200">${itemsHtml}</div>
                </div>
            `;

            if (hasCoords) {
                const coordsText = `${rawLat.toFixed(6)}, ${rawLng.toFixed(6)}`;
                document.getElementById('orderCoords').textContent = `الإحداثيات: ${coordsText}`;
                document.getElementById('googleMapsLink').href = `https://www.google.com/maps/dir/?api=1&destination=${rawLat},${rawLng}`;
                document.getElementById('googleMapsLink').classList.remove('hidden');
            } else {
                document.getElementById('orderCoords').textContent = 'الإحداثيات: —';
                document.getElementById('googleMapsLink').classList.add('hidden');
            }

            initMap(lat, lng, order.recipient_name || order.village || 'التوصيل');
            updateWhatsAppLink();
        })
        .catch(err => {
            console.error(err);
            document.getElementById('orderDetailsContent').innerHTML = '<div class="py-8 text-center text-red-600"><i class="fas fa-exclamation-triangle text-2xl"></i><p class="mt-2">فشل تحميل بيانات الطلب</p></div>';
        });
}

function initMap(lat, lng, label) {
    if (orderMap) {
        orderMap.remove();
        orderMap = null;
    }
    const el = document.getElementById('orderMap');
    if (!el) return;
    orderMap = L.map('orderMap', { attributionControl: false }).setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(orderMap);
    const icon = L.divIcon({
        html: '<div style="width:28px;height:28px;border-radius:50%;background:#4f46e5;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;"><i class="fas fa-map-marker-alt"></i></div>',
        iconSize: [28, 28],
        iconAnchor: [14, 14]
    });
    orderMapMarker = L.marker([lat, lng], { icon }).addTo(orderMap).bindPopup(label || 'موقع التوصيل');
    setTimeout(function() { if (orderMap) orderMap.invalidateSize(); }, 150);
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.body.style.overflow = '';
    currentOrderId = null;
    currentOrderMeta = null;
    if (orderMap) {
        orderMap.remove();
        orderMap = null;
    }
}

document.getElementById('driverSelect')?.addEventListener('change', () => {
    updateWhatsAppLink();
});

document.getElementById('deliveryNotes')?.addEventListener('input', () => {
    updateWhatsAppLink();
});

function normalizeWhatsAppPhone(phone) {
    const digits = String(phone || '').replace(/[^\d]/g, '');
    if (!digits) return '';
    if (digits.startsWith('00')) return digits.slice(2);
    if (digits.startsWith('0')) return digits.replace(/^0+/, '');
    return digits;
}

function updateWhatsAppLink() {
    const btn = document.getElementById('whatsAppBtn');
    const select = document.getElementById('driverSelect');
    if (!btn || !select || !currentOrderMeta) return;

    const opt = select.options[select.selectedIndex];
    const phone = opt?.getAttribute('data-phone') || '';
    const waPhone = normalizeWhatsAppPhone(phone);

    if (!waPhone || !currentOrderMeta.hasCoords) {
        btn.classList.add('hidden');
        btn.href = '#';
        return;
    }

    const coordsText = `${currentOrderMeta.lat.toFixed(6)}, ${currentOrderMeta.lng.toFixed(6)}`;
    const mapsLink = `https://www.google.com/maps?q=${currentOrderMeta.lat},${currentOrderMeta.lng}`;
    const note = document.getElementById('deliveryNotes')?.value || '';
    const lines = [
        `طلب رقم: ${currentOrderMeta.orderNumber}`,
        `العميل: ${currentOrderMeta.customer}`,
        `هاتف العميل: ${currentOrderMeta.phone}`,
        `المنطقة: ${currentOrderMeta.village}`,
        `الإحداثيات: ${coordsText}`,
        `الخريطة: ${mapsLink}`,
    ];
    if (currentOrderMeta.addressNote) lines.push(`ملاحظة الطلب: ${currentOrderMeta.addressNote}`);
    if (note) lines.push(`ملاحظة المشرف: ${note}`);

    const text = encodeURIComponent(lines.join('\n'));
    btn.href = `https://wa.me/${waPhone}?text=${text}`;
    btn.classList.remove('hidden');
}

async function submitAssign() {
    const driverId = document.getElementById('driverSelect').value;
    const notes = document.getElementById('deliveryNotes').value;
    if (!driverId) {
        alert('الرجاء اختيار سائق');
        return;
    }
    const btn = document.getElementById('assignBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري التعيين...';
    try {
        const res = await fetch(assignUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                order_id: currentOrderId,
                driver_id: driverId,
                delivery_fee: 0,
                notes: notes
            })
        });
        const data = await res.json();
        if (data.success) {
            closeAssignModal();
            window.location.reload();
        } else {
            alert(data.message || 'فشل التعيين');
        }
    } catch (e) {
        console.error(e);
        alert('حدث خطأ في التعيين');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>تعيين';
    }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAssignModal(); });
</script>
@endpush
@endsection
