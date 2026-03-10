@extends('dashboards.layouts.app')
@section('content')
@php
    $title = 'طلبات العملاء';
    $subtitle = 'عرض جميع الطلبات ومعلوماتها';
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.cs.index') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
            <span>لوحة خدمة العملاء</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <form method="GET" action="{{ route('dashboard.cs.orders') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <input name="search" value="{{ request('search') }}" class="border rounded-lg px-3 py-2 w-full md:col-span-2" placeholder="بحث: رقم الطلب / اسم / هاتف / بريد">
            <select name="status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">كل الحالات</option>
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">كل حالات الدفع</option>
                @foreach($paymentOptions as $ps)
                    <option value="{{ $ps }}" @selected(request('payment_status') === $ps)>{{ $ps }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded-lg px-3 py-2 w-full">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded-lg px-3 py-2 w-full">
            <div class="md:col-span-6 flex items-center gap-2">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">تطبيق</button>
                <a href="{{ route('dashboard.cs.orders') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">إعادة ضبط</a>
            </div>
        </form>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الطلب</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المتجر</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">العميل</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الدفع</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الإجمالي</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">فتح</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        @php
                            $customerName = $order->customer->name ?? $order->recipient_name ?? ('User #'.$order->customer_id ?? $order->user_id ?? '');
                            if (is_array($customerName)) {
                                $customerName = $customerName['ar'] ?? ($customerName['en'] ?? '');
                            }
                            $storeName = $order->store->name ?? ($order->store_id ? ('Store #'.$order->store_id) : '-');
                            $total = $order->total_amount ?? $order->total ?? 0;
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->order_number ?? ('#'.$order->id) }}</div>
                                <div class="text-xs text-gray-500">{{ number_format((int) ($order->items_count ?? 0)) }} items</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $storeName }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="font-medium">{{ $customerName }}</div>
                                <div class="text-xs text-gray-500">{{ $order->customer->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">{{ $order->status ?? '-' }}</span>
                                @if(($order->status ?? null) === 'delivered')
                                    <form method="POST" action="{{ route('dashboard.cs.orders.change-status', $order) }}" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="status" value="done">
                                        <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                                            Complete
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700 border border-blue-100">{{ $order->payment_status ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ number_format((float) $total, 2) }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.cs.orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-sm text-gray-700 hover:bg-gray-100">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">لا توجد طلبات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
