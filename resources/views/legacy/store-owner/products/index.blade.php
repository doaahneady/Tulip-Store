@extends('layouts.dashboard')

@section('title', 'منتجاتي')
@section('page-title', 'إدارة المنتجات')
@section('role-gradient', 'from-purple-500 to-purple-700')
@section('role-icon', 'fas fa-store')
@section('role-label', 'صاحب المتجر')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.dashboard'), 'icon' => 'fas fa-tachometer-alt', 'label' => 'لوحة التحكم'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.products'), 'icon' => 'fas fa-box', 'label' => 'منتجاتي', 'active' => true])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'الطلبات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.analytics'), 'icon' => 'fas fa-chart-line', 'label' => 'التحليلات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.reviews'), 'icon' => 'fas fa-star', 'label' => 'التقييمات'])
@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800 dark:text-white">منتجاتي</h2>
    <a href="{{ route('store-owner.products.create') }}" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 flex items-center gap-2">
        <i class="fas fa-plus"></i> إضافة منتج
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">المنتج</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">الفئة</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">السعر</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">المخزون</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($products ?? [] as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : '/images/placeholder.png' }}" class="w-12 h-12 rounded-lg object-cover">
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $product->category->name ?? 'غير محدد' }}</td>
                    <td class="px-6 py-4 font-bold text-emerald-600">${{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $product->stock < 10 ? 'text-red-600' : 'text-gray-600' }}">{{ $product->stock }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @include('components.dashboard.badge', ['type' => $product->is_active ? 'success' : 'danger', 'text' => $product->is_active ? 'نشط' : 'معطل'])
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('store-owner.products.edit', $product->id) }}" class="text-purple-500 hover:text-purple-600"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">لا توجد منتجات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($products) && $products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $products->links() }}</div>
    @endif
</div>
@endsection
