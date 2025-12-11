<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إنشاء قيد محاسبي - نظام المحاسبة</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root { --primary: #059669; --primary-dark: #047857; --danger: #ef4444; --success: #22c55e; --dark: #1e293b; --gray: #64748b; }
* { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
body { background: #f0fdf4; min-height: 100vh; }
.container { max-width: 1200px; margin: 0 auto; padding: 2rem; margin-top: 80px; }
.page-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 2rem; border-radius: 16px; margin-bottom: 2rem; color: #fff; }
.page-header h1 { font-size: 1.8rem; font-weight: 800; display: flex; align-items: center; gap: 1rem; }
.card { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 1.5rem; }
.card-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
.card-header h3 { font-size: 1.1rem; font-weight: 700; color: var(--dark); }
.card-body { padding: 1.5rem; }
.form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
.form-group { margin-bottom: 1rem; }
.form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark); }
.form-input, .form-select, .form-textarea { width: 100%; padding: 0.9rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; transition: all 0.2s; }
.form-input:focus, .form-select:focus { outline: none; border-color: var(--primary); }
.btn { padding: 0.8rem 1.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem; border: none; }
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-dark); }
.btn-danger { background: var(--danger); color: #fff; }
.btn-success { background: var(--success); color: #fff; }
.btn-outline { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
</style>
</head>
<body>
@include('components.navbar')
<div class="container">
<div class="page-header">
    <h1><i class="fas fa-edit"></i> إنشاء قيد محاسبي جديد</h1>
    <p style="margin-top: 0.5rem; opacity: 0.9;">رقم القيد: {{ $nextNumber }}</p>
</div>

<style>
.lines-table { width: 100%; border-collapse: collapse; }
.lines-table th { background: #f8fafc; padding: 1rem; text-align: right; font-weight: 700; color: var(--dark); border-bottom: 2px solid var(--primary); }
.lines-table td { padding: 0.8rem; border-bottom: 1px solid #e2e8f0; }
.lines-table input, .lines-table select { width: 100%; padding: 0.7rem; border: 1px solid #e2e8f0; border-radius: 8px; }
.totals-row { background: #f0fdf4; font-weight: 700; }
.totals-row td { padding: 1rem; }
.balance-ok { color: var(--success); }
.balance-error { color: var(--danger); }
.remove-line { background: var(--danger); color: #fff; border: none; padding: 0.5rem 0.8rem; border-radius: 6px; cursor: pointer; }
.action-buttons { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }
</style>

<form id="journalEntryForm">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-info-circle" style="color: var(--primary);"></i> معلومات القيد</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">تاريخ القيد</label>
                <input type="date" class="form-input" name="entry_date" id="entryDate" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">نوع القيد</label>
                <select class="form-select" name="entry_type" id="entryType" required>
                    @foreach($entryTypes as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">رقم القيد</label>
                <input type="text" class="form-input" value="{{ $nextNumber }}" readonly style="background: #f8fafc;">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">وصف القيد</label>
            <textarea class="form-input form-textarea" name="description" id="description" rows="2" placeholder="أدخل وصف القيد..." required></textarea>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color: var(--primary);"></i> بنود القيد</h3>
        <button type="button" class="btn btn-primary" onclick="addLine()">
            <i class="fas fa-plus"></i> إضافة بند
        </button>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="lines-table">
            <thead>
                <tr>
                    <th style="width: 40%;">الحساب</th>
                    <th style="width: 15%;">النوع</th>
                    <th style="width: 20%;">المبلغ</th>
                    <th style="width: 20%;">البيان</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody id="linesBody">
                <tr class="line-row">
                    <td>
                        <select name="lines[0][account_id]" class="account-select" required>
                            <option value="">اختر الحساب...</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="lines[0][type]" class="type-select" onchange="updateTotals()" required>
                            <option value="debit">مدين</option>
                            <option value="credit">دائن</option>
                        </select>
                    </td>
                    <td><input type="number" name="lines[0][amount]" class="amount-input" step="0.01" min="0.01" onchange="updateTotals()" required></td>
                    <td><input type="text" name="lines[0][description]" placeholder="بيان اختياري"></td>
                    <td><button type="button" class="remove-line" onclick="removeLine(this)"><i class="fas fa-trash"></i></button></td>
                </tr>
                <tr class="line-row">
                    <td>
                        <select name="lines[1][account_id]" class="account-select" required>
                            <option value="">اختر الحساب...</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="lines[1][type]" class="type-select" onchange="updateTotals()" required>
                            <option value="debit">مدين</option>
                            <option value="credit" selected>دائن</option>
                        </select>
                    </td>
                    <td><input type="number" name="lines[1][amount]" class="amount-input" step="0.01" min="0.01" onchange="updateTotals()" required></td>
                    <td><input type="text" name="lines[1][description]" placeholder="بيان اختياري"></td>
                    <td><button type="button" class="remove-line" onclick="removeLine(this)"><i class="fas fa-trash"></i></button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="totals-row">
                    <td colspan="2" style="text-align: left;">الإجمالي:</td>
                    <td>
                        <div>مدين: <span id="totalDebit">0.00</span></div>
                        <div>دائن: <span id="totalCredit">0.00</span></div>
                    </td>
                    <td colspan="2">
                        <div id="balanceStatus" class="balance-error">غير متوازن</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="action-buttons">
    <a href="{{ route('accounting.journal-entries') }}" class="btn btn-outline">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
    <button type="button" class="btn btn-success" onclick="saveEntry('draft')">
        <i class="fas fa-save"></i> حفظ كمسودة
    </button>
    <button type="button" class="btn btn-primary" onclick="saveEntry('post')">
        <i class="fas fa-check-double"></i> حفظ وترحيل
    </button>
</div>
</form>
</div>

<script>
let lineIndex = 2;
const accountsOptions = `<option value="">اختر الحساب...</option>@foreach($accounts as $account)<option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>@endforeach`;

function addLine() {
    const tbody = document.getElementById('linesBody');
    const row = document.createElement('tr');
    row.className = 'line-row';
    row.innerHTML = `
        <td><select name="lines[${lineIndex}][account_id]" class="account-select" required>${accountsOptions}</select></td>
        <td><select name="lines[${lineIndex}][type]" class="type-select" onchange="updateTotals()" required><option value="debit">مدين</option><option value="credit">دائن</option></select></td>
        <td><input type="number" name="lines[${lineIndex}][amount]" class="amount-input" step="0.01" min="0.01" onchange="updateTotals()" required></td>
        <td><input type="text" name="lines[${lineIndex}][description]" placeholder="بيان اختياري"></td>
        <td><button type="button" class="remove-line" onclick="removeLine(this)"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(row);
    lineIndex++;
}

function removeLine(btn) {
    const rows = document.querySelectorAll('.line-row');
    if (rows.length > 2) {
        btn.closest('tr').remove();
        updateTotals();
    } else {
        alert('يجب أن يحتوي القيد على بندين على الأقل');
    }
}

function updateTotals() {
    let totalDebit = 0, totalCredit = 0;
    document.querySelectorAll('.line-row').forEach(row => {
        const type = row.querySelector('.type-select').value;
        const amount = parseFloat(row.querySelector('.amount-input').value) || 0;
        if (type === 'debit') totalDebit += amount;
        else totalCredit += amount;
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);
    
    const status = document.getElementById('balanceStatus');
    if (Math.abs(totalDebit - totalCredit) < 0.01 && totalDebit > 0) {
        status.textContent = '✓ متوازن';
        status.className = 'balance-ok';
    } else {
        status.textContent = 'غير متوازن (الفرق: ' + Math.abs(totalDebit - totalCredit).toFixed(2) + ')';
        status.className = 'balance-error';
    }
}

async function saveEntry(action) {
    const form = document.getElementById('journalEntryForm');
    const lines = [];
    
    document.querySelectorAll('.line-row').forEach(row => {
        lines.push({
            account_id: row.querySelector('.account-select').value,
            type: row.querySelector('.type-select').value,
            amount: parseFloat(row.querySelector('.amount-input').value) || 0,
            description: row.querySelector('input[type="text"]').value
        });
    });
    
    const data = {
        entry_date: document.getElementById('entryDate').value,
        entry_type: document.getElementById('entryType').value,
        description: document.getElementById('description').value,
        lines: lines
    };
    
    // Validate
    if (!data.entry_date || !data.description) {
        alert('الرجاء ملء جميع الحقول المطلوبة');
        return;
    }
    
    const totalDebit = lines.filter(l => l.type === 'debit').reduce((s, l) => s + l.amount, 0);
    const totalCredit = lines.filter(l => l.type === 'credit').reduce((s, l) => s + l.amount, 0);
    
    if (Math.abs(totalDebit - totalCredit) > 0.01) {
        alert('القيد غير متوازن! الرجاء التأكد من تساوي المدين والدائن');
        return;
    }
    
    try {
        const response = await fetch('{{ route("accounting.journal-entries.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (action === 'post') {
                // Post the entry
                const postResponse = await fetch(`/accounting/journal-entries/${result.entry_id}/post`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
            }
            alert('تم حفظ القيد بنجاح: ' + result.entry_number);
            window.location.href = '{{ route("accounting.journal-entries") }}';
        } else {
            alert(result.message || 'حدث خطأ');
        }
    } catch (e) {
        alert('حدث خطأ في الاتصال');
        console.error(e);
    }
}

// Initialize
updateTotals();
</script>
</body>
</html>
