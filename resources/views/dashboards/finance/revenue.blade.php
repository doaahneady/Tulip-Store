@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تحليلات الإيرادات'; $subtitle = 'مقاييس واتجاهات الإيراد'; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <x-dashboard.stat-card title="إجمالي الإيرادات" :value="'$'.number_format($revenueStats['total_revenue'] ?? 0, 2)" icon="fas fa-dollar-sign" color="green" />
    <x-dashboard.stat-card title="متوسط يومي" :value="'$'.number_format($revenueStats['avg_daily_revenue'] ?? 0, 2)" icon="fas fa-calendar-day" color="indigo" />
    <x-dashboard.stat-card title="نمو" :value="number_format($revenueStats['growth_rate'] ?? 0, 1).' %'" icon="fas fa-arrow-trend-up" color="blue" />
    <x-dashboard.stat-card title="أفضل يوم" :value="$revenueStats['top_revenue_day'] ?? '-'" icon="fas fa-trophy" color="amber" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-chart-line text-indigo-600 ml-2"></i>الإيراد اليومي</h3>
        <canvas id="dailyRevenueChart" height="170"></canvas>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-store text-emerald-600 ml-2"></i>الإيراد حسب المتجر</h3>
        <canvas id="storeRevenueChart" height="170"></canvas>
    </div>
</div>

@php
    $dailyLabels = collect($revenueData['daily_revenue'] ?? [])->pluck('date')->all();
    $dailyValues = collect($revenueData['daily_revenue'] ?? [])->pluck('revenue')->map(fn ($v) => (float) $v)->all();
    $storeLabels = collect($revenueData['revenue_by_store'] ?? [])->pluck('name')->map(fn ($v) => is_array($v) ? json_encode($v) : $v)->all();
    $storeValues = collect($revenueData['revenue_by_store'] ?? [])->pluck('total_revenue')->map(fn ($v) => (float) $v)->all();
@endphp

<script>
    const dailyLabels = @json($dailyLabels);
    const dailyValues = @json($dailyValues);
    const storeLabels = @json($storeLabels);
    const storeValues = @json($storeValues);

    const dctx = document.getElementById('dailyRevenueChart');
    if (dctx) {
        new Chart(dctx, {
            type: 'line',
            data: { labels: dailyLabels, datasets: [{ data: dailyValues, borderColor: '#4F46E5', backgroundColor: 'rgba(79,70,229,0.15)', tension: 0.35, fill: true, pointRadius: 0 }] },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    }

    const sctx = document.getElementById('storeRevenueChart');
    if (sctx) {
        new Chart(sctx, {
            type: 'bar',
            data: { labels: storeLabels, datasets: [{ data: storeValues, backgroundColor: 'rgba(16,185,129,0.6)', borderRadius: 6 }] },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    }
</script>
@endsection

