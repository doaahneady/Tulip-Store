@extends('layouts.accounting')

@section('title', 'قائمة المركز المالي')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file-invoice-dollar"></i> قائمة المركز المالي (الميزانية العمومية)</h1>
    <p>عرض الأصول والالتزامات وحقوق الملكية</p>
</div>

@php
$assetAccounts = \App\Models\ChartOfAccount::where('account_type', 'asset')->where('current_balance', '!=', 0)->orderBy('account_code')->get();
$liabilityAccounts = \App\Models\ChartOfAccount::where('account_type', 'liability')->where('current_balance', '!=', 0)->orderBy('account_code')->get();
$equityAccounts = \App\Models\ChartOfAccount::where('account_type', 'equity')->where('current_balance', '!=', 0)->orderBy('account_code')->get();
$revenueAccounts = \App\Models\ChartOfAccount::where('account_type', 'revenue')->sum('current_balance');
$expenseAccounts = \App\Models\ChartOfAccount::where('account_type', 'expense')->sum('current_balance');
$netIncome = $revenueAccounts - $expenseAccounts;
$totalAssets = $assetAccounts->sum('current_balance');
$totalLiabilities = $liabilityAccounts->sum('current_balance');
$totalEquity = $equityAccounts->sum('current_balance') + $netIncome;
@endphp

<div style="text-align: center; margin-bottom: 2rem; padding: 2rem; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="color: #1e3a8a; font-size: 2rem; margin-bottom: 0.5rem;">Tulip Store</h2>
    <h3 style="color: #6b7280; font-size: 1.3rem; margin-bottom: 0.3rem;">قائمة المركز المالي</h3>
    <p style="color: #9ca3af; font-size: 1rem;">كما في {{ date('Y-m-d') }}</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Assets -->
    <div class="card">
        <div class="card-header" style="background: #d1fae5; color: #065f46; border-bottom: 3px solid #047857;">
            <i class="fas fa-wallet"></i>
            <span>الأصول</span>
        </div>
        <table>
            <tbody>
                @foreach($assetAccounts as $account)
                <tr>
                    <td style="width: 70%;">{{ $account->account_name }}</td>
                    <td class="positive" style="text-align: left; font-family: 'Courier New', monospace; width: 30%;">
                        ${{ number_format($account->current_balance, 2) }}
                    </td>
                </tr>
                @endforeach
                <tr style="background: #f0fdf4; font-weight: 800; font-size: 1.1rem;">
                    <td style="padding: 1.2rem;">إجمالي الأصول</td>
                    <td class="positive" style="text-align: left; font-family: 'Courier New', monospace; padding: 1.2rem;">
                        ${{ number_format($totalAssets, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Liabilities & Equity -->
    <div class="card">
        <div class="card-header" style="background: #fee2e2; color: #991b1b; border-bottom: 3px solid #dc2626;">
            <i class="fas fa-file-invoice"></i>
            <span>الالتزامات وحقوق الملكية</span>
        </div>
        <table>
            <tbody>
                <tr style="background: #fef2f2;">
                    <td colspan="2" style="font-weight: 700; color: #dc2626; padding: 0.8rem;">الالتزامات:</td>
                </tr>
                @foreach($liabilityAccounts as $account)
                <tr>
                    <td style="padding-right: 1.5rem; width: 70%;">{{ $account->account_name }}</td>
                    <td class="negative" style="text-align: left; font-family: 'Courier New', monospace; width: 30%;">
                        ${{ number_format($account->current_balance, 2) }}
                    </td>
                </tr>
                @endforeach
                <tr style="background: #fef2f2; font-weight: 700;">
                    <td style="padding: 0.8rem;">إجمالي الالتزامات</td>
                    <td class="negative" style="text-align: left; font-family: 'Courier New', monospace; padding: 0.8rem;">
                        ${{ number_format($totalLiabilities, 2) }}
                    </td>
                </tr>
                <tr style="background: #f5f3ff;">
                    <td colspan="2" style="font-weight: 700; color: #7c3aed; padding: 0.8rem; padding-top: 1.5rem;">حقوق الملكية:</td>
                </tr>
                @foreach($equityAccounts as $account)
                <tr>
                    <td style="padding-right: 1.5rem; width: 70%;">{{ $account->account_name }}</td>
                    <td style="text-align: left; font-family: 'Courier New', monospace; width: 30%;">
                        ${{ number_format($account->current_balance, 2) }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding-right: 1.5rem;">صافي الدخل للفترة</td>
                    <td class="{{ $netIncome >= 0 ? 'positive' : 'negative' }}" style="text-align: left; font-family: 'Courier New', monospace;">
                        ${{ number_format($netIncome, 2) }}
                    </td>
                </tr>
                <tr style="background: #f5f3ff; font-weight: 700;">
                    <td style="padding: 0.8rem;">إجمالي حقوق الملكية</td>
                    <td style="text-align: left; font-family: 'Courier New', monospace; padding: 0.8rem;">
                        ${{ number_format($totalEquity, 2) }}
                    </td>
                </tr>
                <tr style="background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; font-weight: 800; font-size: 1.1rem;">
                    <td style="padding: 1.2rem;">إجمالي الالتزامات وحقوق الملكية</td>
                    <td style="text-align: left; font-family: 'Courier New', monospace; padding: 1.2rem;">
                        ${{ number_format($totalLiabilities + $totalEquity, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div style="padding: 1.5rem; border-radius: 8px; text-align: center; font-weight: 700; font-size: 1.1rem; {{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'background: #d1fae5; color: #065f46; border: 2px solid #047857;' : 'background: #fee2e2; color: #991b1b; border: 2px solid #dc2626;' }}">
    @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
        <i class="fas fa-check-circle"></i> الميزانية متوازنة - الأصول = الالتزامات + حقوق الملكية ✓
    @else
        <i class="fas fa-exclamation-triangle"></i> تحذير: الميزانية غير متوازنة - الفرق: ${{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity)), 2) }}
    @endif
</div>

<div style="margin-top: 2rem; text-align: center;">
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
    <button class="btn btn-success" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> تصدير PDF</button>
</div>
@endsection
