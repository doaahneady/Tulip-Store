@extends('dashboards.layouts.app', ['title' => 'Orders'])
@section('content')
@php $isTraderSession = auth('trader')->check() && !auth('employee')->check(); @endphp

<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $isTraderSession ? 'طلبات منتجاتي' : 'الطلبات' }}</h3>
            <p class="text-sm text-gray-500">Orders history, status, and customer details</p>
        </div>
        <a href="{{ route('dashboard.vendor.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Total</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['total'] ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Pending</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['pending'] ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Processing</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['processing'] ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">Delivered</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['delivered'] ?? 0) }}</div>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard.vendor.orders') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order/customer/phone" class="border rounded-lg px-3 py-2 w-full md:col-span-2">
            <select name="status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Status</option>
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Payments</option>
                @foreach($paymentOptions as $ps)
                    <option value="{{ $ps }}" @selected(request('payment_status') === $ps)>{{ $ps }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded-lg px-3 py-2 w-full">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded-lg px-3 py-2 w-full">
            <div class="md:col-span-6 flex items-center gap-2">
                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">Filter</button>
                <a href="{{ route('dashboard.vendor.orders') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
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
                    @forelse($orders as $order)
                        @php
                            $items = $order->items ?? collect();
                            $customerName = $order->customer->name ?? $order->recipient_name ?? 'Customer';
                            $customerEmail = $order->customer->email ?? null;
                            $customerPhone = $order->phone ?? ($order->customer->phone ?? null);
                            $total = $order->total_amount ?? $order->total ?? 0;
                            $logs = ($orderLogs[$order->id] ?? collect())->take(6);
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->order_number ?? ('#'.$order->id) }}</div>
                                <div class="text-xs text-gray-500">
                                    @foreach($items->take(2) as $item)
                                        <span>{{ $item->product->name ?? ('#'.$item->product_id) }} x{{ $item->quantity ?? 0 }}</span>@if(! $loop->last), @endif
                                    @endforeach
                                    @if($items->count() > 2)
                                        <span>, +{{ $items->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="font-medium">{{ $customerName }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($customerEmail) <span>{{ $customerEmail }}</span> @endif
                                    @if($customerEmail && $customerPhone) <span class="mx-1">•</span> @endif
                                    @if($customerPhone) <span>{{ $customerPhone }}</span> @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">{{ $order->status ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700 border border-blue-100">{{ $order->payment_status ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ number_format((float) $total, 2) }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <button type="button" class="px-3 py-1 text-sm bg-emerald-600 text-white rounded hover:bg-emerald-700" onclick="toggleOrderDetails({{ $order->id }})">
                                    Details
                                </button>
                            </td>
                        </tr>
                        <tr id="order-details-{{ $order->id }}" class="hidden bg-gray-50">
                            <td colspan="7" class="px-6 py-4">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                        <div class="font-semibold text-gray-800 mb-2">Items</div>
                                        <div class="space-y-2">
                                            @foreach($items as $item)
                                                <div class="flex items-center justify-between border border-gray-100 rounded-lg px-3 py-2">
                                                    <div class="text-sm text-gray-800">{{ $item->product->name ?? ('Product #'.$item->product_id) }}</div>
                                                    <div class="text-sm text-gray-600">x{{ $item->quantity ?? 0 }}</div>
                                                </div>
                                            @endforeach
                                            @if($items->isEmpty())
                                                <div class="text-sm text-gray-500">No items</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                        <div class="font-semibold text-gray-800 mb-2">Status History</div>
                                        <div class="space-y-2">
                                            @forelse($logs as $log)
                                                @php
                                                    $oldStatus = $log->old_values['status'] ?? null;
                                                    $newStatus = $log->new_values['status'] ?? null;
                                                    $oldPay = $log->old_values['payment_status'] ?? null;
                                                    $newPay = $log->new_values['payment_status'] ?? null;
                                                @endphp
                                                <div class="border border-gray-100 rounded-lg px-3 py-2">
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-sm text-gray-800">{{ $log->action }}</div>
                                                        <div class="text-xs text-gray-500">{{ optional($log->created_at)->format('Y-m-d H:i') }}</div>
                                                    </div>
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        @if($newStatus)
                                                            <span>Status: {{ $oldStatus ?: '-' }} → {{ $newStatus }}</span>
                                                        @endif
                                                        @if($newPay)
                                                            <span class="mx-1">•</span>
                                                            <span>Payment: {{ $oldPay ?: '-' }} → {{ $newPay }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-sm text-gray-500">No history logs</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">No orders found</td>
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

<script>
    function toggleOrderDetails(orderId) {
        const row = document.getElementById(`order-details-${orderId}`);
        if (!row) return;
        row.classList.toggle('hidden');
    }
</script>
@endsection

