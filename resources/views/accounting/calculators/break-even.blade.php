@extends('layouts.accounting')

@section('title', 'حاسبة نقطة التعادل')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> حاسبة نقطة التعادل (Break-Even Point)</h1>
    <p>احتساب نقطة التعادل بالوحدات والإيرادات</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Input Form -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-edit"></i>
            <span>بيانات الحساب</span>
        </div>
        <form id="breakEvenForm">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">التكاليف الثابتة (Fixed Costs)</label>
                <input type="number" id="fixedCosts" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                <small style="color: #6b7280;">الإيجار، الرواتب، التأمين، إلخ</small>
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">سعر البيع للوحدة (Price per Unit)</label>
                <input type="number" id="pricePerUnit" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">التكلفة المتغيرة للوحدة (Variable Cost per Unit)</label>
                <input type="number" id="variableCost" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                <small style="color: #6b7280;">المواد الخام، العمالة المباشرة، إلخ</small>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-calculator"></i> احتساب نقطة التعادل</button>
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
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">نقطة التعادل (بالوحدات)</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="breakEvenUnits">0</div>
                <small style="color: #6b7280;">وحدة</small>
            </div>
            <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border-right: 4px solid #047857;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">نقطة التعادل (بالإيرادات)</div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;" id="breakEvenRevenue">$0</div>
            </div>
            <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; border-right: 4px solid #d97706;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">هامش المساهمة للوحدة</div>
                <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;" id="contributionMargin">$0</div>
            </div>
        </div>
    </div>
</div>

<!-- Analysis -->
<div class="card" id="analysisCard" style="display: none;">
    <div class="card-header">
        <i class="fas fa-chart-area"></i>
        <span>التحليل</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #1e3a8a; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="cmRatio">0%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">نسبة هامش المساهمة</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Contribution Margin Ratio</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #047857; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;" id="safetyMargin">0%</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">هامش الأمان</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Margin of Safety</small>
        </div>
        <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;" id="targetUnits">0</div>
            <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">الوحدات للربح المستهدف</div>
            <small style="color: #9ca3af; font-size: 0.75rem;">Target Profit Units</small>
        </div>
    </div>
    
    <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; border: 2px solid #e5e7eb;">
        <h4 style="color: #1e3a8a; margin-bottom: 1rem;"><i class="fas fa-info-circle"></i> ماذا يعني هذا؟</h4>
        <ul style="list-style: none; padding: 0; display: grid; gap: 0.8rem;">
            <li style="display: flex; align-items: start; gap: 0.8rem;">
                <i class="fas fa-check-circle" style="color: #047857; margin-top: 0.2rem;"></i>
                <span>يجب بيع <strong id="unitsNeeded">0</strong> وحدة لتغطية جميع التكاليف</span>
            </li>
            <li style="display: flex; align-items: start; gap: 0.8rem;">
                <i class="fas fa-check-circle" style="color: #047857; margin-top: 0.2rem;"></i>
                <span>يجب تحقيق إيرادات بقيمة <strong id="revenueNeeded">$0</strong> للوصول لنقطة التعادل</span>
            </li>
            <li style="display: flex; align-items: start; gap: 0.8rem;">
                <i class="fas fa-check-circle" style="color: #047857; margin-top: 0.2rem;"></i>
                <span>كل وحدة إضافية بعد نقطة التعادل تحقق ربح قدره <strong id="profitPerUnit">$0</strong></span>
            </li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('breakEvenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fixedCosts = parseFloat(document.getElementById('fixedCosts').value);
    const pricePerUnit = parseFloat(document.getElementById('pricePerUnit').value);
    const variableCost = parseFloat(document.getElementById('variableCost').value);
    
    const contributionMargin = pricePerUnit - variableCost;
    
    if (contributionMargin <= 0) {
        alert('خطأ: هامش المساهمة يجب أن يكون موجباً (سعر البيع > التكلفة المتغيرة)');
        return;
    }
    
    const breakEvenUnits = Math.ceil(fixedCosts / contributionMargin);
    const breakEvenRevenue = breakEvenUnits * pricePerUnit;
    const cmRatio = (contributionMargin / pricePerUnit) * 100;
    
    // Display results
    document.getElementById('breakEvenUnits').textContent = breakEvenUnits.toLocaleString();
    document.getElementById('breakEvenRevenue').textContent = '$' + breakEvenRevenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('contributionMargin').textContent = '$' + contributionMargin.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('results').style.display = 'block';
    
    // Display analysis
    document.getElementById('cmRatio').textContent = cmRatio.toFixed(1) + '%';
    document.getElementById('safetyMargin').textContent = '0%'; // Would need actual sales to calculate
    document.getElementById('targetUnits').textContent = breakEvenUnits.toLocaleString();
    
    document.getElementById('unitsNeeded').textContent = breakEvenUnits.toLocaleString();
    document.getElementById('revenueNeeded').textContent = '$' + breakEvenRevenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('profitPerUnit').textContent = '$' + contributionMargin.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    document.getElementById('analysisCard').style.display = 'block';
});
</script>
@endpush
