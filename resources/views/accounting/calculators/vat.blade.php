@extends('layouts.accounting')

@section('title', 'حاسبة ضريبة القيمة المضافة')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-percent"></i> حاسبة ضريبة القيمة المضافة (VAT)</h1>
    <p>احتساب ضريبة القيمة المضافة وفصل المبلغ الصافي عن الضريبة</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Input Form -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-edit"></i>
            <span>بيانات الحساب</span>
        </div>
        <form id="vatForm">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">المبلغ</label>
                <input type="number" id="amount" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">نسبة الضريبة (%)</label>
                <input type="number" id="vatRate" step="0.01" value="15" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">نوع الحساب</label>
                <select id="calcType" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                    <option value="add">إضافة الضريبة (المبلغ بدون ضريبة)</option>
                    <option value="extract">فصل الضريبة (المبلغ شامل الضريبة)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-calculator"></i> احتساب الضريبة</button>
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
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">المبلغ الصافي (بدون ضريبة)</div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="netAmount">$0</div>
            </div>
            <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-right: 4px solid #d97706;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">قيمة الضريبة</div>
                <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;" id="vatAmount">$0</div>
            </div>
            <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; border-right: 4px solid #047857;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">المبلغ الإجمالي (شامل الضريبة)</div>
                <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;" id="grossAmount">$0</div>
            </div>
            
            <div style="margin-top: 2rem; padding: 1.5rem; background: #f9fafb; border-radius: 8px; border: 2px solid #e5e7eb;">
                <h4 style="color: #1e3a8a; margin-bottom: 1rem; font-size: 1.1rem;"><i class="fas fa-info-circle"></i> التفاصيل</h4>
                <div style="display: grid; gap: 0.5rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>نسبة الضريبة:</span>
                        <strong id="rateDisplay">15%</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>نوع الحساب:</span>
                        <strong id="typeDisplay">-</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Examples -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-lightbulb"></i>
        <span>أمثلة سريعة</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        <button onclick="quickCalc(1000, 15, 'add')" class="btn btn-secondary" style="padding: 1rem;">
            <div style="font-size: 0.85rem; margin-bottom: 0.3rem;">إضافة 15% على 1000</div>
            <div style="font-size: 1.2rem; font-weight: 800;">= $1,150</div>
        </button>
        <button onclick="quickCalc(1150, 15, 'extract')" class="btn btn-secondary" style="padding: 1rem;">
            <div style="font-size: 0.85rem; margin-bottom: 0.3rem;">فصل 15% من 1150</div>
            <div style="font-size: 1.2rem; font-weight: 800;">= $1,000 + $150</div>
        </button>
        <button onclick="quickCalc(5000, 15, 'add')" class="btn btn-secondary" style="padding: 1rem;">
            <div style="font-size: 0.85rem; margin-bottom: 0.3rem;">إضافة 15% على 5000</div>
            <div style="font-size: 1.2rem; font-weight: 800;">= $5,750</div>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('vatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    calculateVAT();
});

function calculateVAT() {
    const amount = parseFloat(document.getElementById('amount').value);
    const vatRate = parseFloat(document.getElementById('vatRate').value);
    const calcType = document.getElementById('calcType').value;
    
    let netAmount, vatAmount, grossAmount;
    
    if (calcType === 'add') {
        netAmount = amount;
        vatAmount = amount * (vatRate / 100);
        grossAmount = amount + vatAmount;
    } else {
        grossAmount = amount;
        netAmount = amount / (1 + vatRate / 100);
        vatAmount = amount - netAmount;
    }
    
    document.getElementById('netAmount').textContent = '$' + netAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('vatAmount').textContent = '$' + vatAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('grossAmount').textContent = '$' + grossAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('rateDisplay').textContent = vatRate + '%';
    document.getElementById('typeDisplay').textContent = calcType === 'add' ? 'إضافة الضريبة' : 'فصل الضريبة';
    document.getElementById('results').style.display = 'block';
}

function quickCalc(amount, rate, type) {
    document.getElementById('amount').value = amount;
    document.getElementById('vatRate').value = rate;
    document.getElementById('calcType').value = type;
    calculateVAT();
}
</script>
@endpush
