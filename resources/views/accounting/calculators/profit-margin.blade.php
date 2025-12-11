@extends('layouts.accounting')

@section('title', 'حاسبة هامش الربح')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-pie"></i> حاسبة هامش الربح والتكلفة</h1>
    <p>احتساب هامش الربح ونسبة الربحية والعلامة التجارية</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Input Form -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-edit"></i>
            <span>بيانات الحساب</span>
        </div>
        <form id="profitForm">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">الإيرادات (Revenue)</label>
                <input type="number" id="revenue" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">تكلفة البضاعة المباعة (COGS)</label>
                <input type="number" id="cost" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">المصروفات التشغيلية (Operating Expenses)</label>
                <input type="number" id="expenses" step="0.01" value="0" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-calculator"></i> احتساب الربحية</button>
        </form>
    </div>
    
    <!-- Results -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chart-bar"></i>
            <span>النتائج</span>
        </div>
        <div id="results" style="display: none;">
            <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-right: 4px solid #047857;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">الربح الإجمالي (Gross Profit)</div>
                <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;" id="grossProfit">$0</div>
            </div>
            <div style="background: #eff6ff; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-right: 4px solid #1e3a8a;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">الربح الصافي (Net Profit)</div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="netProfit">$0</div>
            </div>
        </div>
    </div>
</div>

<!-- Ratios -->
<div class="card" id="ratiosCard" style="display: none;">
    <div class="card-header">
        <i class="fas fa-percentage"></i>
        <span>النسب المالية</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;">
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #047857; border-radius: 8px;">
            <div style="font-size: 2.5rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;" id="grossMargin">0%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">هامش الربح الإجمالي</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Gross Margin</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #1e3a8a; border-radius: 8px;">
            <div style="font-size: 2.5rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="netMargin">0%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">هامش الربح الصافي</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Net Margin</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706; border-radius: 8px;">
            <div style="font-size: 2.5rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;" id="markup">0%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">نسبة العلامة التجارية</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Markup</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #7c3aed; border-radius: 8px;">
            <div style="font-size: 2.5rem; font-weight: 800; color: #7c3aed; font-family: 'Courier New', monospace;" id="expenseRatio">0%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">نسبة المصروفات</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Expense Ratio</small>
        </div>
    </div>
</div>

<!-- Breakdown -->
<div class="card" id="breakdownCard" style="display: none;">
    <div class="card-header">
        <i class="fas fa-list"></i>
        <span>التفصيل</span>
    </div>
    <table>
        <tbody>
            <tr style="background: #d1fae5;">
                <td style="font-weight: 700; width: 60%;">الإيرادات (Revenue)</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; font-weight: 700; color: #047857;" id="revenueDisplay">$0</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">تكلفة البضاعة المباعة (COGS)</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; color: #dc2626;" id="costDisplay">$0</td>
            </tr>
            <tr style="background: #f0fdf4; font-weight: 700;">
                <td>الربح الإجمالي (Gross Profit)</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; color: #047857;" id="grossProfitDisplay">$0</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">المصروفات التشغيلية (Operating Expenses)</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; color: #dc2626;" id="expensesDisplay">$0</td>
            </tr>
            <tr style="background: #eff6ff; font-weight: 800; font-size: 1.1rem;">
                <td>الربح الصافي (Net Profit)</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; color: #1e3a8a;" id="netProfitDisplay">$0</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('profitForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const revenue = parseFloat(document.getElementById('revenue').value);
    const cost = parseFloat(document.getElementById('cost').value);
    const expenses = parseFloat(document.getElementById('expenses').value);
    
    const grossProfit = revenue - cost;
    const netProfit = grossProfit - expenses;
    
    const grossMargin = revenue > 0 ? (grossProfit / revenue) * 100 : 0;
    const netMargin = revenue > 0 ? (netProfit / revenue) * 100 : 0;
    const markup = cost > 0 ? (grossProfit / cost) * 100 : 0;
    const expenseRatio = revenue > 0 ? (expenses / revenue) * 100 : 0;
    
    // Display results
    document.getElementById('grossProfit').textContent = '$' + grossProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('netProfit').textContent = '$' + netProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('results').style.display = 'block';
    
    // Display ratios
    document.getElementById('grossMargin').textContent = grossMargin.toFixed(1) + '%';
    document.getElementById('netMargin').textContent = netMargin.toFixed(1) + '%';
    document.getElementById('markup').textContent = markup.toFixed(1) + '%';
    document.getElementById('expenseRatio').textContent = expenseRatio.toFixed(1) + '%';
    document.getElementById('ratiosCard').style.display = 'block';
    
    // Display breakdown
    document.getElementById('revenueDisplay').textContent = '$' + revenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('costDisplay').textContent = '$' + cost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('grossProfitDisplay').textContent = '$' + grossProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('expensesDisplay').textContent = '$' + expenses.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('netProfitDisplay').textContent = '$' + netProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('breakdownCard').style.display = 'block';
});
</script>
@endpush
