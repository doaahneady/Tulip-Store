@extends('trader.layout')
@php $title = 'لوحة تحكم التاجر'; @endphp
@section('content')

        <div class="grid grid-4" style="margin-bottom:1rem">
            <div class="card kpi">
                <div class="icon indigo"><i class="fas fa-boxes"></i></div>
                <div class="meta">
                    <div class="label">إجمالي المنتجات</div>
                    <div class="value">{{ number_format($metrics['total_products']) }}</div>
                </div>
            </div>
            <div class="card kpi">
                <div class="icon blue"><i class="fas fa-shopping-cart"></i></div>
                <div class="meta">
                    <div class="label">طلبات قيد المعالجة</div>
                    <div class="value">{{ number_format($metrics['pending_orders']) }}</div>
                </div>
            </div>
            <div class="card kpi">
                <div class="icon green"><i class="fas fa-chart-line"></i></div>
                <div class="meta">
                    <div class="label">مبيعات هذا الشهر</div>
                    <div class="value">{{ number_format($metrics['monthly_revenue'], 2) }}</div>
                </div>
            </div>
            <div class="card kpi">
                <div class="icon orange"><i class="fas fa-wallet"></i></div>
                <div class="meta">
                    <div class="label">أرباح هذا الشهر</div>
                    <div class="value">{{ number_format($metrics['monthly_earnings'], 2) }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-4" style="margin-bottom:1rem">
            <div class="card kpi">
                <div class="icon green"><i class="fas fa-sack-dollar"></i></div>
                <div class="meta">
                    <div class="label">إجمالي الأرباح</div>
                    <div class="value">{{ number_format($metrics['total_earnings'], 2) }}</div>
                </div>
            </div>
            <div class="card kpi">
                <div class="icon orange"><i class="fas fa-money-check-alt"></i></div>
                <div class="meta">
                    <div class="label">دفعات معلقة</div>
                    <div class="value">{{ number_format($metrics['pending_payouts'], 2) }}</div>
                </div>
            </div>
            <div class="card kpi">
                <div class="icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="meta">
                    <div class="label">منتجات منخفضة المخزون</div>
                    <div class="value">{{ number_format($metrics['low_stock_products']) }}</div>
                </div>
            </div>
            <div class="card kpi">
                <div class="icon indigo"><i class="fas fa-percent"></i></div>
                <div class="meta">
                    <div class="label">نسبة العمولة</div>
                    <div class="value">{{ number_format($metrics['commission_rate'], 2) }}%</div>
                </div>
            </div>
        </div>

        <div class="grid grid-2" style="margin-bottom:1rem">
            <div class="card">
                <div class="section-title">أفضل المنتجات (هذا الشهر)</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>الإيراد</th>
                            <th>المخزون</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($metrics['top_products'] as $p)
                            <tr>
                                <td>{{ $p['name'] ?? ('#'.$p['id']) }}</td>
                                <td>{{ number_format($p['units_sold']) }}</td>
                                <td>{{ number_format($p['revenue'], 2) }}</td>
                                <td>{{ number_format($p['stock']) }}</td>
                                <td>
                                    @php $status = $p['status'] ?? 'pending'; @endphp
                                    <span class="badge {{ $status === 'approved' ? 'green' : ($status === 'rejected' ? 'red' : 'orange') }}">{{ $status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;color:#9ca3af">لا توجد بيانات</td></tr>
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
                            <th>المجموع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($metrics['recent_orders'] as $o)
                            <tr>
                                <td>#{{ $o->id }}</td>
                                <td>{{ $o->user?->name ?? $o->user?->user_full_name ?? '-' }}</td>
                                <td>{{ number_format($o->total_amount ?? $o->total ?? 0, 2) }}</td>
                                <td><span class="badge {{ in_array($o->status, ['delivered','completed']) ? 'green' : (in_array($o->status, ['pending','confirmed','processing']) ? 'orange' : 'red') }}">{{ $o->status }}</span></td>
                                <td>{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;color:#9ca3af">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection
