@extends('layouts.accounting')

@section('title', 'ميزان المراجعة')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-balance-scale"></i> ميزان المراجعة</h1>
    <p>عرض أرصدة جميع الحسابات والتحقق من التوازن</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-calendar"></i>
        <span>ميزان المراجعة كما في {{ date('Y-m-d') }}</span>
        <button class="btn btn-primary" onclick="window.print()" style="margin-right: auto; font-size: 0.9rem; padding: 0.5rem 1rem;">
            <i class="fas fa-print"></i> طباعة
        </button>
        <button class="btn btn-success" onclick="exportToExcel()" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
            <i class="fas fa-file-excel"></i> تصدير Excel
        </button>
    </div>
    
    @php
    $accounts = \App\Models\ChartOfAccount::where('is_active', true)->orderBy('account_code')->get();
    $trialBalance = $accounts->map(function($account) {
        $balance = $account->current_balance;
        return [
            'code' => $account->account_code,
            'name' => $account->account_name,
            'type' => $account->account_type,
            'debit' => in_array($account->account_type, ['asset', 'expense']) && $balance > 0 ? $balance : 
                      (in_array($account->account_type, ['liability', 'equity', 'revenue']) && $balance < 0 ? abs($balance) : 0),
            'credit' => in_array($account->account_type, ['liability', 'equity', 'revenue']) && $balance > 0 ? $balance : 
                       (in_array($account->account_type, ['asset', 'expense']) && $balance < 0 ? abs($balance) : 0),
        ];
    })->filter(function($item) {
        return $item['debit'] > 0 || $item['credit'] > 0;
    });
    
    $totalDebits = $trialBalance->sum('debit');
    $totalCredits = $trialBalance->sum('credit');
    @endphp
    
    <table id="trialBalanceTable">
        <thead>
            <tr>
                <th style="width: 15%;">رمز الحساب</th>
                <th style="width: 50%;">اسم الحساب</th>
                <th style="width: 17.5%;">مدين</th>
                <th style="width: 17.5%;">دائن</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trialBalance as $account)
            <tr>
                <td><strong style="color: #1e3a8a;">{{ $account['code'] }}</strong></td>
                <td>{{ $account['name'] }}</td>
                <td class="{{ $account['debit'] > 0 ? 'positive' : '' }}" style="font-family: 'Courier New', monospace; text-align: left;">
                    {{ $account['debit'] > 0 ? '$'.number_format($account['debit'], 2) : '-' }}
                </td>
                <td class="{{ $account['credit'] > 0 ? 'negative' : '' }}" style="font-family: 'Courier New', monospace; text-align: left;">
                    {{ $account['credit'] > 0 ? '$'.number_format($account['credit'], 2) : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; font-weight: 800; font-size: 1.1rem;">
            <tr>
                <td colspan="2" style="padding: 1.2rem;"><strong>الإجمالي</strong></td>
                <td style="padding: 1.2rem; font-family: 'Courier New', monospace; text-align: left;"><strong>${{ number_format($totalDebits, 2) }}</strong></td>
                <td style="padding: 1.2rem; font-family: 'Courier New', monospace; text-align: left;"><strong>${{ number_format($totalCredits, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div style="margin-top: 2rem; padding: 1.5rem; border-radius: 8px; text-align: center; font-weight: 700; font-size: 1.1rem; {{ abs($totalDebits - $totalCredits) < 0.01 ? 'background: #d1fae5; color: #065f46; border: 2px solid #047857;' : 'background: #fee2e2; color: #991b1b; border: 2px solid #dc2626;' }}">
        @if(abs($totalDebits - $totalCredits) < 0.01)
            <i class="fas fa-check-circle"></i> ميزان المراجعة متوازن - إجمالي المدين يساوي إجمالي الدائن ✓
        @else
            <i class="fas fa-exclamation-triangle"></i> تحذير: ميزان المراجعة غير متوازن - الفرق: ${{ number_format(abs($totalDebits - $totalCredits), 2) }}
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    const table = document.getElementById('trialBalanceTable');
    let html = table.outerHTML;
    const blob = new Blob(['\ufeff' + html], { type: 'application/vnd.ms-excel' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'trial-balance-' + new Date().toISOString().split('T')[0] + '.xls';
    link.click();
}
</script>
@endpush
