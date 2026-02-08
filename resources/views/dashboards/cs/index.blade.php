@extends('dashboards.layouts.app')
@section('content')
@php($title = 'لوحة خدمة العملاء')
@php($subtitle = 'إدارة التذاكر والردود والمرتجعات')
@php($resolvedGrowth = '')
@php($ticketsGrowth = '')
@php($avgFormatted = 'N/A')
@php
    $resolvedGrowth = $kpi['resolved_today']['growth'] ?? '';
    if (is_array($resolvedGrowth)) {
        $resolvedGrowth = $resolvedGrowth['value'] ?? '';
    }
    if ($resolvedGrowth === '0.0%') {
        $resolvedGrowth = '';
    }

    $ticketsGrowth = $kpi['tickets_this_month']['growth'] ?? '';
    if (is_array($ticketsGrowth)) {
        $ticketsGrowth = $ticketsGrowth['value'] ?? '';
    }
    if ($ticketsGrowth === '0.0%') {
        $ticketsGrowth = '';
    }

    $avgFormatted = $kpi['avg_response_time']['formatted'] ?? $avgFormatted;
    if (is_array($avgFormatted)) {
        $avgFormatted = $avgFormatted['value'] ?? 'N/A';
    }
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.cs.orders') }}" class="inline-flex items-center gap-2 bg-green-700 text-white px-4 py-2 rounded-xl hover:bg-green-800 transition">
            <i class="fas fa-receipt"></i>
            <span>الطلبات</span>
        </a>
        <a href="{{ route('dashboard.cs.tickets') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-inbox"></i>
            <span>التذاكر</span>
        </a>
        <a href="{{ route('dashboard.cs.tickets') }}?assigned_to={{ auth('employee')->id() }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-user-check"></i>
            <span>تذاكري</span>
        </a>
        <a href="{{ route('dashboard.cs.trader-products') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>مراجعة المنتجات</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.index') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.my-attendance.index') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs">التذاكر المفتوحة</p>
        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ number_format($kpi['open_tickets']['value'] ?? 0) }}</h3>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs">قيد المعالجة</p>
        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ number_format($kpi['pending_tickets']['value'] ?? 0) }}</h3>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs">تم الحل اليوم</p>
        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ number_format($kpi['resolved_today']['value'] ?? 0) }}</h3>
        <p class="text-xs text-gray-500 mt-1">{{ $resolvedGrowth ?? '' }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs">متوسط وقت الاستجابة</p>
        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $avgFormatted ?? 'N/A' }}</h3>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs">تذاكر هذا الشهر</p>
        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ number_format($kpi['tickets_this_month']['value'] ?? 0) }}</h3>
        <p class="text-xs text-gray-500 mt-1">{{ $ticketsGrowth ?? '' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-bolt text-red-600 ml-2"></i>تذاكر عاجلة</h3>
        <div class="space-y-2 max-h-72 overflow-y-auto">
            @forelse($urgentTickets as $t)
                @php
                    $ticketSubject = $t->subject ?? '';
                    if (is_array($ticketSubject)) {
                        $ticketSubject = $ticketSubject['ar'] ?? ($ticketSubject['en'] ?? '');
                    }
                    $userName = optional($t->user)->name ?? null;
                    if (is_array($userName)) {
                        $userName = $userName['ar'] ?? ($userName['en'] ?? '');
                    }
                    $userLabel = $userName ?: ('User #'.$t->user_id);
                @endphp
                <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $t->ticket_number }} — {{ $ticketSubject }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ $userLabel }} • {{ $t->created_at?->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">{{ $t->priority }}</span>
                        <a href="{{ route('dashboard.cs.tickets.show', $t->id) }}" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-100">فتح</a>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-6">لا توجد تذاكر عاجلة</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-layer-group text-indigo-600 ml-2"></i>حسب الأولوية</h3>
        <canvas id="priorityChart" height="220"></canvas>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-chart-pie text-purple-600 ml-2"></i>حسب الحالة</h3>
        <canvas id="statusChart" height="220"></canvas>
    </div>
</div>

@php
    $priorityLabels = array_keys($priority ?? []);
    $priorityValues = array_values($priority ?? []);
    $statusLabels = array_keys($statusDist ?? []);
    $statusValues = array_values($statusDist ?? []);
@endphp

<script>
    const priorityLabels = @json($priorityLabels);
    const priorityValues = @json($priorityValues);
    const statusLabels = @json($statusLabels);
    const statusValues = @json($statusValues);

    const pctx = document.getElementById('priorityChart');
    if (pctx) {
        new Chart(pctx, {
            type: 'bar',
            data: {
                labels: priorityLabels,
                datasets: [{
                    data: priorityValues,
                    backgroundColor: ['#EF4444','#F59E0B','#6366F1','#22C55E']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    const sctx = document.getElementById('statusChart');
    if (sctx) {
        new Chart(sctx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#6366F1','#22C55E','#F59E0B','#EF4444','#64748B','#8B5CF6']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
</script>
@endsection
