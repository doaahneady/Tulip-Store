@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تعيين الطلبات'; $subtitle = 'قائمة الطلبات غير المعينة والسائقين المتاحين'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">طلبات غير معينة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">رقم الطلب</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">العميل</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">المتجر</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pendingOrders as $order)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ optional($order->customer)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ optional($order->store)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('dashboard.supervisor.assign-order') }}" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <select name="driver_id" class="border rounded-xl px-2 py-1 text-xs">
                                    @foreach($availableDrivers as $driver)
                                        <option value="{{ $driver->id }}">{{ optional($driver->user)->name ?? $driver->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" step="0.01" name="delivery_fee" class="border rounded-xl px-2 py-1 text-xs w-24" placeholder="الرسوم">
                                <input type="text" name="notes" class="border rounded-xl px-2 py-1 text-xs" placeholder="ملاحظة">
                                <button class="px-3 py-1 bg-green-600 text-white rounded-md text-xs">تعيين</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($pendingOrders->count()===0)
                    <tr><td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد طلبات</td></tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">سائقون متاحون</h3>
        <ul class="divide-y divide-gray-200">
            @foreach($availableDrivers as $driver)
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900">{{ optional($driver->user)->name ?? $driver->name }}</div>
                        <div class="text-xs text-gray-500">{{ $driver->availability }}</div>
                    </div>
                    <a href="tel:{{ $driver->phone }}" class="text-indigo-600 text-sm">اتصال</a>
                </li>
            @endforeach
            @if($availableDrivers->count()===0)
                <li class="py-3 text-center text-sm text-gray-500">لا يوجد سائقون متاحون</li>
            @endif
        </ul>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">تعيينات نشطة</h3>
        <ul class="divide-y divide-gray-200">
            @foreach($activeAssignments as $assign)
                <li class="py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">#{{ optional($assign->order)->order_number ?? $assign->order_id }}</div>
                            <div class="text-sm text-gray-500">{{ optional(optional($assign->driver)->user)->name ?? '—' }}</div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $assign->status }}
                        </span>
                    </div>
                </li>
            @endforeach
            @if($activeAssignments->count()===0)
                <li class="py-3 text-center text-sm text-gray-500">لا توجد تعيينات</li>
            @endif
        </ul>
    </div>
</div>
@endsection
