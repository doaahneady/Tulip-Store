@extends('layouts.accounting')

@section('title', 'الصفحة الرئيسية')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-home"></i> الصفحة الرئيسية</h1>
    <p>نظرة شاملة على الوضع المالي للشركة</p>
</div>

<!-- Accounting Equation -->
<div style="background: linear-gradient(135deg, #eff6ff, #dbeafe); padding: 2rem; text-align: center; margin-bottom: 2rem; border: 3px solid #1e3a8a; border-top: 5px solid #d97706; box-shadow: 0 4px 15px rgba(30,58,138,0.1);">
    <div style="font-size: 1.4rem; font-weight: 800; color: #1e3a8a; margin-bottom: 1.2rem;">
        <i class="fas fa-balance-scale"></i> المعادلة المحاسبية الأساسية
    </div>
    <div style="display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap; align-items: center;">
        <div style="text-align: center; background: #fff; padding: 1.2rem 1.8rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 3px solid #d97706;">
            <div style="font-size: 0.9rem; color: #6b7280; font-weight: 700; margin-bottom: 0.5rem;">الأصول</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">${{ number_format($totalAssets, 0) }}</div>
        </div>
        <div style="font-size: 2rem; color: #1e3a8a; font-weight: 800;">=</div>
        <div style="text-align: center; background: #fff; padding: 1.2rem 1.8rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 3px solid #d97706;">
            <div style="font-size: 0.9rem; color: #6b7280; font-weight: 700; margin-bottom: 0.5rem;">الالتزامات</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">${{ number_format($totalLiabilities, 0) }}</div>
        </div>
        <div style="font-size: 2rem; color: #1e3a8a; font-weight: 800;">+</div>
        <div style="text-align: center; background: #fff; padding: 1.2rem 1.8rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 3px solid #d97706;">
            <div style="font-size: 0.9rem; color: #6b7280; font-weight: 700; margin-bottom: 0.5rem;">حقوق الملكية</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">${{ number_format($totalEquity, 0) }}</div>
        </div>
    </div>
    @php $diff = $totalAssets - ($totalLiabilities + $totalEquity); @endphp
    <div style="margin-top: 1rem; font-size: 1rem; font-weight: 700;">
        @if(abs($diff) < 1)
            <span style="color: #047857;"><i class="fas fa-check-circle"></i> المعادلة متوازنة ✓</span>
        @else
            <span style="color: #dc2626;"><i class="fas fa-exclamation-triangle"></i> فرق: ${{ number_format($diff, 0) }}</span>
        @endif
    </div>
</div>

<!-- Financial Summary -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        <div class="stat-label">إجمالي الأصول</div>
        <div class="stat-value positive">${{ number_format($totalAssets, 0) }}</div>
    </div>
    <div class="stat-card" style="border-right-color: #dc2626;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-label">إجمالي الالتزامات</div>
        <div class="stat-value negative">${{ number_format($totalLiabilities, 0) }}</div>
    </div>
    <div class="stat-card" style="border-right-color: #7c3aed;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);"><i class="fas fa-landmark"></i></div>
        <div class="stat-label">حقوق الملكية</div>
        <div class="stat-value">${{ number_format($totalEquity, 0) }}</div>
    </div>
    <div class="stat-card" style="border-right-color: #047857;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #047857, #065f46);"><i class="fas fa-arrow-up"></i></div>
        <div class="stat-label">إجمالي الإيرادات</div>
        <div class="stat-value positive">${{ number_format($totalRevenue, 0) }}</div>
    </div>
    <div class="stat-card" style="border-right-color: #f59e0b;">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);"><i class="fas fa-arrow-down"></i></div>
        <div class="stat-label">إجمالي المصروفات</div>
        <div class="stat-value negative">${{ number_format($totalExpenses, 0) }}</div>
    </div>
    <div class="stat-card" style="border-right-color: {{ $netIncome >= 0 ? '#047857' : '#dc2626' }};">
        <div class="stat-icon" style="background: linear-gradient(135deg, {{ $netIncome >= 0 ? '#047857, #065f46' : '#dc2626, #b91c1c' }});"><i class="fas fa-{{ $netIncome >= 0 ? 'trophy' : 'exclamation-triangle' }}"></i></div>
        <div class="stat-label">صافي الدخل</div>
        <div class="stat-value {{ $netIncome >= 0 ? 'positive' : 'negative' }}">${{ number_format($netIncome, 0) }}</div>
    </div>
</div>

<!-- Key Ratios -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-percentage"></i>
        <span>النسب المالية الرئيسية</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706;">
            <div style="font-size: 2.2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">{{ number_format($currentRatio, 2) }}</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">نسبة التداول</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Current Ratio</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706;">
            <div style="font-size: 2.2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">{{ number_format($quickRatio, 2) }}</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">النسبة السريعة</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Quick Ratio</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706;">
            <div style="font-size: 2.2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">{{ number_format($debtToEquity, 2) }}</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">الدين/حقوق الملكية</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Debt-to-Equity</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706;">
            <div style="font-size: 2.2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">{{ number_format($returnOnAssets, 1) }}%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">العائد على الأصول</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">ROA</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706;">
            <div style="font-size: 2.2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">{{ number_format($profitMargin, 1) }}%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">هامش الربح</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Profit Margin</small>
        </div>
    </div>
</div>

<!-- Charts -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chart-area"></i>
            <span>الإيرادات مقابل المصروفات - آخر 6 أشهر</span>
        </div>
        <canvas id="revenueExpenseChart" height="200"></canvas>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chart-doughnut"></i>
            <span>توزيع أنواع الحسابات</span>
        </div>
        <canvas id="accountTypesChart" height="200"></canvas>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-exchange-alt"></i>
        <span>آخر القيود المحاسبية</span>
        <a href="/accounting/journal-entries" class="btn btn-primary" style="margin-right: auto; font-size: 0.9rem; padding: 0.5rem 1rem;">
            <i class="fas fa-eye"></i> عرض الكل
        </a>
    </div>
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>رقم القيد</th>
                <th>الوصف</th>
                <th>الحساب</th>
                <th>مدين</th>
                <th>دائن</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTransactions as $t)
            <tr>
                <td>{{ $t['date'] }}</td>
                <td><strong style="color: #1e3a8a;">{{ $t['entry'] }}</strong></td>
                <td>{{ $t['description'] }}</td>
                <td>{{ $t['account'] }}</td>
                <td class="{{ $t['debit'] > 0 ? 'positive' : '' }}" style="font-family: 'Courier New', monospace;">{{ $t['debit'] > 0 ? '$'.number_format($t['debit'], 0) : '-' }}</td>
                <td class="{{ $t['credit'] > 0 ? 'negative' : '' }}" style="font-family: 'Courier New', monospace;">{{ $t['credit'] > 0 ? '$'.number_format($t['credit'], 0) : '-' }}</td>
                <td><span style="background: #d1fae5; color: #065f46; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">مرحّل</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #9ca3af; padding: 2rem;">لا توجد قيود محاسبية</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
// Revenue vs Expense Chart
const ctx1 = document.getElementById('revenueExpenseChart');
if (ctx1) {
    new Chart(ctx1.getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [{
                label: 'الإيرادات',
                data: {!! json_encode(array_column($monthlyData, 'revenue')) !!},
                borderColor: '#047857',
                backgroundColor: 'rgba(4, 120, 87, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }, {
                label: 'المصروفات',
                data: {!! json_encode(array_column($monthlyData, 'expenses')) !!},
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

// Account Types Chart
const ctx2 = document.getElementById('accountTypesChart');
if (ctx2) {
    new Chart(ctx2.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['الأصول', 'الالتزامات', 'حقوق الملكية', 'الإيرادات', 'المصروفات'],
            datasets: [{
                data: [{{ $totalAssets }}, {{ $totalLiabilities }}, {{ $totalEquity }}, {{ $totalRevenue }}, {{ $totalExpenses }}],
                backgroundColor: ['#1e3a8a', '#dc2626', '#7c3aed', '#047857', '#f59e0b'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
}
</script>
@endpush
