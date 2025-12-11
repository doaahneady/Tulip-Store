@extends('layouts.accounting')

@section('title', 'قائمة الدخل')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> قائمة الدخل</h1>
    <p>عرض الإيرادات والمصروفات وصافي الدخل</p>
</div>

@php
$revenueAccounts = \App\Models\ChartOfAccount::where('account_type', 'revenue')->where('current_balance', '!=', 0)->orderBy('account_code')->get();
$expenseAccounts = \App\Models\ChartOfAccount::where('account_type', 'expense')->where('current_balance', '!=', 0)->orderBy('account_code')->get();
$totalRevenue = $revenueAccounts->sum('current_balance');
$totalExpenses = $expenseAccounts->sum('current_balance');
$netIncome = $totalRevenue - $totalExpenses;
@endphp

<div style="text-align: center; margin-bottom: 2rem; padding: 2rem; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="color: #1e3a8a; font-size: 2rem; margin-bottom: 0.5rem;">Tulip Store</h2>
    <h3 style="color: #6b7280; font-size: 1.3rem; margin-bottom: 0.3rem;">قائمة الدخل</h3>
    <p style="color: #9ca3af; font-size: 1rem;">للفترة المنتهية في {{ date('Y-m-d') }}</p>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <table>
        <tbody>
            <!-- Revenue Section -->
            <tr style="background: #d1fae5;">
                <td colspan="2" style="font-weight: 800; color: #065f46; font-size: 1.2rem; padding: 1rem;">
                    <i class="fas fa-arrow-up"></i> الإيرادات
                </td>
            </tr>
            @foreach($revenueAccounts as $account)
            <tr>
                <td style="padding-right: 2rem; width: 70%;">{{ $account->account_name }}</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace; width: 30%;">
                    ${{ number_format($account->current_balance, 2) }}
                </td>
            </tr>
            @endforeach
            <tr style="background: #f0fdf4; font-weight: 700; font-size: 1.1rem;">
                <td style="padding: 1rem;">إجمالي الإيرادات</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace; padding: 1rem;">
                    ${{ number_format($totalRevenue, 2) }}
                </td>
            </tr>
            
            <!-- Expenses Section -->
            <tr style="background: #fee2e2;">
                <td colspan="2" style="font-weight: 800; color: #991b1b; font-size: 1.2rem; padding: 1rem; padding-top: 2rem;">
                    <i class="fas fa-arrow-down"></i> المصروفات
                </td>
            </tr>
            @foreach($expenseAccounts as $account)
            <tr>
                <td style="padding-right: 2rem; width: 70%;">{{ $account->account_name }}</td>
                <td class="negative" style="text-align: left; font-family: 'Courier New', monospace; width: 30%;">
                    ${{ number_format($account->current_balance, 2) }}
                </td>
            </tr>
            @endforeach
            <tr style="background: #fef2f2; font-weight: 700; font-size: 1.1rem;">
                <td style="padding: 1rem;">إجمالي المصروفات</td>
                <td class="negative" style="text-align: left; font-family: 'Courier New', monospace; padding: 1rem;">
                    ${{ number_format($totalExpenses, 2) }}
                </td>
            </tr>
            
            <!-- Net Income -->
            <tr style="background: linear-gradient(135deg, {{ $netIncome >= 0 ? '#d1fae5, #a7f3d0' : '#fee2e2, #fecaca' }}); font-weight: 800; font-size: 1.3rem;">
                <td style="padding: 1.5rem;">
                    <i class="fas fa-{{ $netIncome >= 0 ? 'trophy' : 'exclamation-triangle' }}"></i> صافي الدخل
                </td>
                <td class="{{ $netIncome >= 0 ? 'positive' : 'negative' }}" style="text-align: left; font-family: 'Courier New', monospace; padding: 1.5rem;">
                    ${{ number_format($netIncome, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Financial Analysis -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 2rem; max-width: 900px; margin-left: auto; margin-right: auto;">
    <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #047857;">
        <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: 'Courier New', monospace;">
            {{ $totalRevenue > 0 ? number_format(($netIncome / $totalRevenue) * 100, 1) : 0 }}%
        </div>
        <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">هامش الربح الصافي</div>
        <small style="color: #9ca3af; font-size: 0.75rem;">Net Profit Margin</small>
    </div>
    <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #1e3a8a;">
        <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">
            {{ $totalRevenue > 0 ? number_format(($totalExpenses / $totalRevenue) * 100, 1) : 0 }}%
        </div>
        <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">نسبة المصروفات</div>
        <small style="color: #9ca3af; font-size: 0.75rem;">Expense Ratio</small>
    </div>
    <div style="background: #fff; padding: 1.5rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-top: 4px solid #d97706;">
        <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: 'Courier New', monospace;">
            ${{ number_format($totalRevenue - $totalExpenses, 2) }}
        </div>
        <div style="color: #6b7280; font-size: 0.9rem; font-weight: 700; margin-top: 0.5rem;">الربح التشغيلي</div>
        <small style="color: #9ca3af; font-size: 0.75rem;">Operating Profit</small>
    </div>
</div>

<div style="margin-top: 2rem; text-align: center;">
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
    <button class="btn btn-success" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> تصدير PDF</button>
</div>
@endsection
