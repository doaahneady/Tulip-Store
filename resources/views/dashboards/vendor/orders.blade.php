@extends('dashboards.layouts.app', ['title' => 'Orders'])
@section('content')
@php 
    $isTraderSession = auth('trader')->check() && !auth('employee')->check(); 
    $exchangeRate = \App\Models\SystemSetting::get('usd_to_syp_rate', 117);
@endphp

<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $isTraderSession ? 'طلبات منتجاتي' : 'الطلبات' }}</h3>
            <p class="text-sm text-gray-500">سعر الصرف الحالي: 1$ = {{ number_format($exchangeRate, 0) }} ل.س</p>
        </div>
        <a href="{{ route('dashboard.vendor.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">إجمالي الطلبات</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['total'] ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">طلبات في الانتظار</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['pending'] ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">الطلبات في العمل</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['processing'] ?? 0) }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500">الطلبات المُستلمة</div>
                <div class="text-2xl font-bold text-gray-800">{{ number_format($orderStats['delivered'] ?? 0) }}</div>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard.vendor.orders') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order/customer/phone" class="border rounded-lg px-3 py-2 w-full md:col-span-2">
            <select name="status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">جميع الحالات</option>
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
                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">تصفية</button>
                <a href="{{ route('dashboard.vendor.orders') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">إعادة تعيين</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">رقم الطلب</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">العميل</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الدفع</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">مجموع البضاعة ($)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">نسبة المتجر ($)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">صافي الربح ($)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">تاريخ الإنشاء</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        @php
                            $items = $order->items ?? collect();
                            $customerName = $order->customer->name ?? $order->recipient_name ?? 'Customer';
                            $customerEmail = $order->customer->email ?? null;
                            $customerPhone = $order->phone ?? ($order->customer->phone ?? null);
                            
                            // Calculate totals: Goods only, excluding delivery and other fees
                            $goodsTotal = (float) ($order->subtotal ?? $items->sum('total_price') ?? 0);
                            $storeCommission = $goodsTotal * 0.02;
                            $vendorProfit = $goodsTotal - $storeCommission;
                            
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
                                <span class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $order->payment_status ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-700 font-semibold">{{ number_format($goodsTotal, 2) }} $</div>
                                <div class="text-[10px] text-gray-400 font-normal">≈ {{ number_format($goodsTotal * $exchangeRate, 0) }} ل.س</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-red-600 font-medium">{{ number_format($storeCommission, 2) }} $</div>
                                <div class="text-[10px] text-red-400 font-normal">≈ {{ number_format($storeCommission * $exchangeRate, 0) }} ل.س</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-emerald-600 font-bold">{{ number_format($vendorProfit, 2) }} $</div>
                                <div class="text-[10px] text-emerald-400 font-normal">≈ {{ number_format($vendorProfit * $exchangeRate, 0) }} ل.س</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <button type="button" class="px-3 py-1 text-sm bg-emerald-600 text-white rounded hover:bg-emerald-700" onclick="toggleOrderDetails({{ $order->id }})">
                                    عرض التفاصيل
                                </button>
                            </td>
                        </tr>
                        <tr id="order-details-{{ $order->id }}" class="hidden bg-gray-50">
                            <td colspan="9" class="px-6 py-4">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                        <div class="font-semibold text-gray-800 mb-2">العناصر</div>
                                        <div class="space-y-2">
                                            @foreach($items as $item)
                                                <div class="flex items-center justify-between border border-gray-100 rounded-lg px-3 py-2">
                                                    <div class="text-sm text-gray-800">
                                                        {{ $item->product->name ?? ('Product #'.$item->product_id) }}</div>
                                                    <div class="text-sm text-gray-600">x{{ $item->quantity ?? 0 }}</div>
                                                </div>
                                            @endforeach
                                            @if($items->isEmpty())
                                                <div class="text-sm text-gray-500">لا يوجد عناصر</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg border border-gray-100 p-4">
                                        <div class="font-semibold text-gray-800 mb-2">تاريخ الحالة</div>
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
                                                        <div class="text-xs text-gray-500" >
                                                            {{ optional($log->created_at)->format('Y-m-d H:i') }}</div>
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
                                                <div class="text-sm text-gray-500">لا يوجد سجل الحالة</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">لا يوجد طلبات</td>
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

