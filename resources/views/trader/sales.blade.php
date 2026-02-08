@extends('trader.layout')
@php $title = 'المبيعات'; @endphp
@section('content')

<div class="card" style="margin-bottom:1rem">
    <form method="GET" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:flex-end">
        <div>
            <div style="font-weight:800;margin-bottom:.25rem">من</div>
            <input class="input" type="date" name="from" value="{{ request('from') }}">
        </div>
        <div>
            <div style="font-weight:800;margin-bottom:.25rem">إلى</div>
            <input class="input" type="date" name="to" value="{{ request('to') }}">
        </div>
        <button class="btn gray" type="submit"><i class="fas fa-filter"></i> تصفية</button>
        <a class="btn gray" href="{{ route('trader.sales') }}"><i class="fas fa-rotate"></i> مسح</a>
    </form>
</div>

<div class="grid grid-4" style="margin-bottom:1rem">
    <div class="card kpi">
        <div class="icon green"><i class="fas fa-coins"></i></div>
        <div class="meta">
            <div class="label">إجمالي الإيراد</div>
            <div class="value">{{ number_format($summary['revenue'] ?? 0, 2) }}</div>
        </div>
    </div>
    <div class="card kpi">
        <div class="icon blue"><i class="fas fa-box"></i></div>
        <div class="meta">
            <div class="label">الكمية المباعة</div>
            <div class="value">{{ number_format($summary['units_sold'] ?? 0) }}</div>
        </div>
    </div>
    <div class="card kpi">
        <div class="icon indigo"><i class="fas fa-receipt"></i></div>
        <div class="meta">
            <div class="label">عدد الطلبات</div>
            <div class="value">{{ number_format($summary['orders'] ?? 0) }}</div>
        </div>
    </div>
    <div class="card kpi">
        <div class="icon orange"><i class="fas fa-percent"></i></div>
        <div class="meta">
            <div class="label">نسبة العمولة</div>
            <div class="value">{{ number_format((float)($trader->commission_rate ?? 0), 2) }}%</div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="section-title">أفضل المنتجات</div>
        <table class="table">
            <thead>
            <tr>
                <th>المنتج</th>
                <th>SKU</th>
                <th>الكمية</th>
                <th>الإيراد</th>
            </tr>
            </thead>
            <tbody>
            @forelse($topProducts as $p)
                <tr>
                    <td style="font-weight:800">{{ $p['name'] ?? ('#'.$p['id']) }}</td>
                    <td>{{ $p['sku'] ?? '—' }}</td>
                    <td>{{ number_format($p['units_sold']) }}</td>
                    <td>{{ number_format($p['revenue'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#9ca3af">لا توجد بيانات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card">
        <div class="section-title">آخر الطلبات</div>
        <table class="table">
            <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>العميل</th>
                <th>الحالة</th>
                <th>التاريخ</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentOrders as $o)
                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->user?->name ?? $o->user?->user_full_name ?? '-' }}</td>
                    <td><span class="badge {{ in_array($o->status, ['delivered','completed']) ? 'green' : 'orange' }}">{{ $o->status }}</span></td>
                    <td>{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#9ca3af">لا توجد بيانات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
