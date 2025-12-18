@extends('layouts.dashboard')

@section('title', 'طلباتي')
@section('page-title', 'إدارة الطلبات')
@section('role-gradient', 'from-purple-500 to-purple-700')
@section('role-icon', 'fas fa-store')
@section('role-label', 'صاحب المتجر')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.dashboard'), 'icon' => 'fas fa-tachometer-alt', 'label' => 'لوحة التحكم'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.products'), 'icon' => 'fas fa-box', 'label' => 'منتجاتي'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'الطلبات', 'active' => true])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.analytics'), 'icon' => 'fas fa-chart-line', 'label' => 'التحليلات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.reviews'), 'icon' => 'fas fa-star', 'label' => 'التقييمات'])
@endsection

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-shopping-bag text-purple-500"></i> الطلبات التي تحتوي على منتجاتي
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">رقم الطلب</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">المنتجات</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($orders ?? [] as $order)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 font-bold text-purple-600">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $order->recipient_name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">
                            @foreach($order->items->take(2) as $item)
                                <div>{{ $item->product->name ?? 'منتج' }} ({{ $item->quantity }})</div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <div class="text-gray-400">+{{ $order->items->count() - 2 }} أخرى</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-emerald-600">${{ number_format($order->items->sum('subtotal'), 2) }}</td>
                    <td class="px-6 py-4">@include('components.dashboard.badge', ['type' => $order->status, 'text' => $order->status])</td>
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($orders) && $orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
