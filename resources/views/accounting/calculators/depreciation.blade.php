@extends('layouts.accounting')

@section('title', 'حاسبة الإهلاك')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-calculator"></i> حاسبة الإهلاك</h1>
    <p>احتساب إهلاك الأصول الثابتة بطرق مختلفة</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Input Form -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-edit"></i>
            <span>بيانات الأصل</span>
        </div>
        <form id="depreciationForm">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">تكلفة الأصل</label>
                <input type="number" id="cost" step="0.01" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">القيمة المتبقية (Salvage Value)</label>
                <input type="number" id="salvage" step="0.01" value="0" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">العمر الإنتاجي (بالسنوات)</label>
                <input type="number" id="life" min="1" value="5" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
            </div>
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">طريقة الإهلاك</label>
                <select id="method" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                    <option value="straight_line">القسط الثابت (Straight Line)</option>
                    <option value="declining_balance">القسط المتناقص (Declining Balance)</option>
                    <option value="double_declining">القسط المتناقص المضاعف (Double Declining)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-calculator"></i> احتساب الإهلاك</button>
        </form>
    </div>
    
    <!-- Results -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chart-bar"></i>
            <span>النتائج</span>
        </div>
        <div id="results" style="display: none;">
            <div style="background: #eff6ff; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border-right: 4px solid #1e3a8a;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">الإهلاك السنوي</div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;" id="annualDepreciation">$0</div>
            </div>
            <div style="background: #f0fdf4; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border-right: 4px solid #047857;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">الإهلاك الشهري</div>
                <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;" id="monthlyDepreciation">$0</div>
            </div>
            <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; border-right: 4px solid #d97706;">
                <div style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">إجمالي الإهلاك</div>
                <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;" id="totalDepreciation">$0</div>
            </div>
        </div>
    </div>
</div>

<!-- Depreciation Schedule -->
<div class="card" id="scheduleCard" style="display: none;">
    <div class="card-header">
        <i class="fas fa-table"></i>
        <span>جدول الإهلاك</span>
    </div>
    <table id="scheduleTable">
        <thead>
            <tr>
                <th>السنة</th>
                <th>الإهلاك السنوي</th>
                <th>الإهلاك المتراكم</th>
                <th>القيمة الدفترية</th>
            </tr>
        </thead>
        <tbody id="scheduleBody"></tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('depreciationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const cost = parseFloat(document.getElementById('cost').value);
    const salvage = parseFloat(document.getElementById('salvage').value);
    const life = parseInt(document.getElementById('life').value);
    const method = document.getElementById('method').value;
    
    const depreciableAmount = cost - salvage;
    let schedule = [];
    let annualDepreciation = 0;
    
    if (method === 'straight_line') {
        annualDepreciation = depreciableAmount / life;
        let accumulated = 0;
        for (let year = 1; year <= life; year++) {
            accumulated += annualDepreciation;
            schedule.push({
                year: year,
                depreciation: annualDepreciation,
                accumulated: accumulated,
                bookValue: cost - accumulated
            });
        }
    } else if (method === 'declining_balance' || method === 'double_declining') {
        const rate = method === 'double_declining' ? (2 / life) : (1 / life);
        let bookValue = cost;
        let accumulated = 0;
        for (let year = 1; year <= life; year++) {
            const depreciation = Math.min(bookValue * rate, bookValue - salvage);
            accumulated += depreciation;
            bookValue -= depreciation;
            schedule.push({
                year: year,
                depreciation: depreciation,
                accumulated: accumulated,
                bookValue: Math.max(bookValue, salvage)
            });
            if (bookValue <= salvage) break;
        }
        annualDepreciation = schedule[0].depreciation;
    }
    
    // Display results
    document.getElementById('annualDepreciation').textContent = '$' + annualDepreciation.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('monthlyDepreciation').textContent = '$' + (annualDepreciation / 12).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalDepreciation').textContent = '$' + depreciableAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('results').style.display = 'block';
    
    // Display schedule
    const tbody = document.getElementById('scheduleBody');
    tbody.innerHTML = '';
    schedule.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td><strong>${row.year}</strong></td>
                <td style="font-family: 'Courier New', monospace;">$${row.depreciation.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td style="font-family: 'Courier New', monospace;">$${row.accumulated.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td style="font-family: 'Courier New', monospace;">$${row.bookValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
            </tr>
        `;
    });
    document.getElementById('scheduleCard').style.display = 'block';
});
</script>
@endpush
