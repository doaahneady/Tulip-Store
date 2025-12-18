@extends('layouts.dashboard')

@section('title', 'التحليلات')
@section('page-title', 'تحليلات المتجر')
@section('role-gradient', 'from-purple-500 to-purple-700')
@section('role-icon', 'fas fa-store')
@section('role-label', 'صاحب المتجر')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.dashboard'), 'icon' => 'fas fa-tachometer-alt', 'label' => 'لوحة التحكم'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.products'), 'icon' => 'fas fa-box', 'label' => 'منتجاتي'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'الطلبات'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.analytics'), 'icon' => 'fas fa-chart-line', 'label' => 'التحليلات', 'active' => true])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.reviews'), 'icon' => 'fas fa-star', 'label' => 'التقييمات'])
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @component('components.dashboard.chart-card', ['title' => 'المبيعات الشهرية', 'icon' => 'fas fa-chart-area', 'chartId' => 'monthlySalesChart'])
    @endcomponent
    
    @component('components.dashboard.chart-card', ['title' => 'توزيع المنتجات', 'icon' => 'fas fa-chart-pie', 'chartId' => 'productsChart'])
    @endcomponent
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('monthlySalesChart')?.getContext('2d');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json(array_column($monthlySales ?? [], 'month')),
            datasets: [{
                label: 'المبيعات',
                data: @json(array_column($monthlySales ?? [], 'sales')),
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderRadius: 8
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}
</script>
@endpush
