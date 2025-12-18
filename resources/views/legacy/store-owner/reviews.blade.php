@extends('layouts.dashboard')

@section('title', 'التقييمات')
@section('page-title', 'تقييمات المنتجات')
@section('role-gradient', 'from-purple-500 to-purple-700')
@section('role-icon', 'fas fa-store')
@section('role-label', 'صاحب المتجر')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.dashboard'), 'icon' => 'fas fa-tachometer-alt', 'label' => 'لوحة التحكم'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.products'), 'icon' => 'fas fa-box', 'label' => 'منتجاتي'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'الطلبات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.analytics'), 'icon' => 'fas fa-chart-line', 'label' => 'التحليلات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.reviews'), 'icon' => 'fas fa-star', 'label' => 'التقييمات', 'active' => true])
@endsection

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-star text-amber-500"></i> تقييمات العملاء
        </h3>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($reviews ?? [] as $review)
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <span class="text-purple-600 font-bold">{{ substr($review->user->name ?? 'U', 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="font-semibold text-gray-800 dark:text-white">{{ $review->user->name ?? 'مستخدم' }}</span>
                            <span class="text-gray-400 text-sm mr-2">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }} text-sm"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">{{ $review->comment }}</p>
                    <p class="text-gray-400 text-xs">المنتج: {{ $review->product->name ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <i class="fas fa-star text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">لا توجد تقييمات بعد</p>
        </div>
        @endforelse
    </div>
    @if(isset($reviews) && $reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
