@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إدارة الطلبات'; $subtitle = 'نظرة عامة على جميع الطلبات مع فلاتر وإجراءات'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm text-gray-600">بحث</label>
            <input name="search" value="{{ request('search') }}" class="w-full px-3 py-2 border rounded-lg" placeholder="رقم الطلب أو اسم العميل">
        </div>
        <div>
            <label class="text-sm text-gray-600">الحالة</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg">
                <option value="">الكل</option>
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">حالة الدفع</label>
            <select name="payment_status" class="w-full px-3 py-2 border rounded-lg">
                <option value="">الكل</option>
                @foreach($paymentOptions as $ps)
                    <option value="{{ $ps }}" @selected(request('payment_status')===$ps)>{{ $ps }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">تطبيق</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">قائمة الطلبات</h3>
        <span class="text-xs text-gray-500">عدد: {{ $orders->total() }}</span>
    </div>
    <div class="p-6 overflow-x-auto">
        <table class="table-auto w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="px-3 py-2">رقم الطلب</th>
                    <th class="px-3 py-2">العميل</th>
                    <th class="px-3 py-2">المتجر</th>
                    <th class="px-3 py-2">الحالة</th>
                    <th class="px-3 py-2">الدفع</th>
                    <th class="px-3 py-2">السائق</th>
                    <th class="px-3 py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $order->order_number ?? ('#'.$order->id) }}</td>
                        <td class="px-3 py-2">{{ $order->user->name ?? 'ضيف' }}</td>
                        <td class="px-3 py-2">{{ $order->store->name ?? '-' }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('dashboard.admin.orders.update-status', $order) }}" class="flex gap-2">
                                @csrf
                                <select name="status" class="px-2 py-1 border rounded">
                                    @foreach($statusOptions as $st)
                                        <option value="{{ $st }}" @selected($order->status === $st)>{{ $st }}</option>
                                    @endforeach
                                </select>
                                <select name="payment_status" class="px-2 py-1 border rounded">
                                    <option value="">بدون تغيير</option>
                                    @foreach($paymentOptions as $ps)
                                        <option value="{{ $ps }}" @selected(($order->payment_status ?? '') === $ps)>{{ $ps }}</option>
                                    @endforeach
                                </select>
                                <button class="bg-blue-600 text-white px-3 py-1 rounded">تحديث</button>
                            </form>
                        </td>
                        <td class="px-3 py-2">{{ $order->payment_status ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $order->assigned_driver_id ? ('#'.$order->assigned_driver_id) : '-' }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('dashboard.admin.orders.override-assignment', $order) }}" class="flex gap-2">
                                @csrf
                                <input type="number" name="driver_id" class="px-2 py-1 border rounded w-24" placeholder="ID سائق" value="{{ $order->assigned_driver_id }}">
                                <button class="bg-orange-600 text-white px-3 py-1 rounded">تعيين يدوي</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-6 text-center text-gray-500">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
