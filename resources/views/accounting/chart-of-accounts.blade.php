@extends('layouts.accounting')

@section('title', 'دليل الحسابات')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-book"></i> دليل الحسابات</h1>
    <p>عرض وإدارة جميع الحسابات المحاسبية</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list"></i>
        <span>قائمة الحسابات</span>
        <button class="btn btn-primary" onclick="openModal('addAccountModal')" style="margin-right: auto; font-size: 0.9rem; padding: 0.5rem 1rem;">
            <i class="fas fa-plus"></i> إضافة حساب جديد
        </button>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">رمز الحساب</th>
                <th style="width: 35%;">اسم الحساب</th>
                <th style="width: 15%;">النوع</th>
                <th style="width: 15%;">الرصيد الافتتاحي</th>
                <th style="width: 15%;">الرصيد الحالي</th>
                <th style="width: 5%;">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php
            $accounts = \App\Models\ChartOfAccount::orderBy('account_code')->get();
            $types = [
                'asset' => ['الأصول', '#1e3a8a'],
                'liability' => ['الالتزامات', '#dc2626'],
                'equity' => ['حقوق الملكية', '#7c3aed'],
                'revenue' => ['الإيرادات', '#047857'],
                'expense' => ['المصروفات', '#f59e0b']
            ];
            @endphp
            @foreach($accounts as $account)
            <tr>
                <td><strong style="color: #1e3a8a;">{{ $account->account_code }}</strong></td>
                <td style="padding-right: {{ $account->parent_account_id ? 2 : 0 }}rem;">
                    {{ $account->parent_account_id ? '↳ ' : '' }}{{ $account->account_name }}
                </td>
                <td>
                    <span style="background: {{ $types[$account->account_type][1] }}15; color: {{ $types[$account->account_type][1] }}; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 700;">
                        {{ $types[$account->account_type][0] }}
                    </span>
                </td>
                <td class="{{ $account->opening_balance >= 0 ? 'positive' : 'negative' }}" style="font-family: 'Courier New', monospace;">
                    ${{ number_format($account->opening_balance, 2) }}
                </td>
                <td class="{{ $account->current_balance >= 0 ? 'positive' : 'negative' }}" style="font-family: 'Courier New', monospace;">
                    ${{ number_format($account->current_balance, 2) }}
                </td>
                <td>
                    <button class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;" onclick="editAccount({{ $account->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Account Modal -->
<div id="addAccountModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6);">
    <div style="background: #fff; margin: 5% auto; width: 90%; max-width: 600px; border-radius: 10px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 1.5rem; color: #fff;">
            <h2 style="margin: 0; font-size: 1.3rem;"><i class="fas fa-plus-circle"></i> إضافة حساب جديد</h2>
        </div>
        <div style="padding: 2rem;">
            <form id="addAccountForm">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">رمز الحساب</label>
                    <input type="text" name="account_code" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">اسم الحساب</label>
                    <input type="text" name="account_name" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">نوع الحساب</label>
                    <select name="account_type" required style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                        <option value="">اختر النوع...</option>
                        <option value="asset">أصول</option>
                        <option value="liability">التزامات</option>
                        <option value="equity">حقوق ملكية</option>
                        <option value="revenue">إيرادات</option>
                        <option value="expense">مصروفات</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">الرصيد الافتتاحي</label>
                    <input type="number" name="opening_balance" step="0.01" value="0" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addAccountModal')"><i class="fas fa-times"></i> إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'block';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

document.getElementById('addAccountForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/accounting/accounts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.success) {
            alert('تم إضافة الحساب بنجاح');
            location.reload();
        } else {
            alert('حدث خطأ: ' + result.message);
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
});
</script>
@endpush
