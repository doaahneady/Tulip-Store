@extends('layouts.accounting')

@section('title', 'حاسبة القروض')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-hand-holding-usd"></i> حاسبة القروض والفوائد</h1>
    <p>احتساب الأقساط الشهرية وجدول السداد للقروض</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Input Form -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-edit"></i>
            <span>بيانات القرض</span>
        </div>
        <form id="loanForm">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">مبلغ القرض (Principal)</label>
                <input type="number" id="principal" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">معدل الفائدة السنوي (%)</label>
                <input type="number" id="rate" step="0.01" value="5" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">مدة القرض (بالأشهر)</label>
                <input type="number" id="months" min="1" value="12" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-calculator"></i> احتساب القسط</button>
        </form>
    </div>
    
    <!-- Results -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chart-bar"></i>
            <span>النتائج</span>
        </div>
        <div id="results" style="display: none;">
            <div style="background: #eff6ff; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-right: 4px solid #1e3a8a;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">القسط الشهري</div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="monthlyPayment">$0</div>
            </div>
            <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-right: 4px solid #d97706;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">إجمالي المدفوعات</div>
                <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;" id="totalPayment">$0</div>
            </div>
            <div style="background: #fee2e2; padding: 1.5rem; border-radius: 8px; border-right: 4px solid #dc2626;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">إجمالي الفوائد</div>
                <div style="font-size: 2rem; font-weight: 800; color: #dc2626; font-family: 'Courier New', monospace;" id="totalInterest">$0</div>
            </div>
        </div>
    </div>
</div>

<!-- Amortization Schedule -->
<div class="card" id="scheduleCard" style="display: none;">
    <div class="card-header">
        <i class="fas fa-table"></i>
        <span>جدول السداد (Amortization Schedule)</span>
    </div>
    <div style="max-height: 500px; overflow-y: auto;">
        <table id="scheduleTable">
            <thead>
                <tr>
                    <th>الشهر</th>
                    <th>القسط</th>
                    <th>الفائدة</th>
                    <th>الأصل</th>
                    <th>الرصيد المتبقي</th>
                </tr>
            </thead>
            <tbody id="scheduleBody"></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('loanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const principal = parseFloat(document.getElementById('principal').value);
    const annualRate = parseFloat(document.getElementById('rate').value);
    const months = parseInt(document.getElementById('months').value);
    
    const monthlyRate = annualRate / 100 / 12;
    let monthlyPayment;
    
    if (monthlyRate > 0) {
        monthlyPayment = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);
    } else {
        monthlyPayment = principal / months;
    }
    
    const totalPayment = monthlyPayment * months;
    const totalInterest = totalPayment - principal;
    
    // Display results
    document.getElementById('monthlyPayment').textContent = '$' + monthlyPayment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalPayment').textContent = '$' + totalPayment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalInterest').textContent = '$' + totalInterest.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('results').style.display = 'block';
    
    // Generate amortization schedule
    const tbody = document.getElementById('scheduleBody');
    tbody.innerHTML = '';
    let balance = principal;
    
    for (let month = 1; month <= months; month++) {
        const interest = balance * monthlyRate;
        const principalPaid = monthlyPayment - interest;
        balance -= principalPaid;
        
        tbody.innerHTML += `
            <tr>
                <td><strong>${month}</strong></td>
                <td style="font-family: 'Courier New', monospace;">$${monthlyPayment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td style="font-family: 'Courier New', monospace; color: #dc2626;">$${interest.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td style="font-family: 'Courier New', monospace; color: #047857;">$${principalPaid.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td style="font-family: 'Courier New', monospace; font-weight: 700;">$${Math.max(balance, 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
            </tr>
        `;
    }
    document.getElementById('scheduleCard').style.display = 'block';
});
</script>
@endpush
