@extends('layouts.accounting')

@section('title', 'قائمة التدفقات النقدية')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-money-bill-wave"></i> قائمة التدفقات النقدية</h1>
    <p>عرض التدفقات النقدية من الأنشطة التشغيلية والاستثمارية والتمويلية</p>
</div>

@php
$cashAccount = \App\Models\ChartOfAccount::where('account_code', '1110')->first();
$bankAccount = \App\Models\ChartOfAccount::where('account_code', '1120')->first();
$openingCash = ($cashAccount->opening_balance ?? 0) + ($bankAccount->opening_balance ?? 0);
$closingCash = ($cashAccount->current_balance ?? 0) + ($bankAccount->current_balance ?? 0);
$netIncrease = $closingCash - $openingCash;

// Simplified cash flow calculation
$revenueAccounts = \App\Models\ChartOfAccount::where('account_type', 'revenue')->sum('current_balance');
$expenseAccounts = \App\Models\ChartOfAccount::where('account_type', 'expense')->sum('current_balance');
$operatingCashFlow = $revenueAccounts - $expenseAccounts;
@endphp

<div style="text-align: center; margin-bottom: 2rem; padding: 2rem; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <h2 style="color: #1e3a8a; font-size: 2rem; margin-bottom: 0.5rem;">Tulip Store</h2>
    <h3 style="color: #6b7280; font-size: 1.3rem; margin-bottom: 0.3rem;">قائمة التدفقات النقدية</h3>
    <p style="color: #9ca3af; font-size: 1rem;">للفترة المنتهية في {{ date('Y-m-d') }}</p>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <table>
        <tbody>
            <!-- Operating Activities -->
            <tr style="background: #d1fae5;">
                <td colspan="2" style="font-weight: 800; color: #065f46; font-size: 1.2rem; padding: 1rem;">
                    <i class="fas fa-cogs"></i> التدفقات النقدية من الأنشطة التشغيلية
                </td>
            </tr>
            <tr>
                <td style="padding-right: 2rem; width: 70%;">صافي الدخل</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace; width: 30%;">
                    ${{ number_format($revenueAccounts - $expenseAccounts, 2) }}
                </td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">التغير في الذمم المدينة</td>
                <td style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">التغير في المخزون</td>
                <td style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">التغير في الذمم الدائنة</td>
                <td style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr style="background: #f0fdf4; font-weight: 700;">
                <td>صافي التدفقات النقدية من الأنشطة التشغيلية</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace;">
                    ${{ number_format($operatingCashFlow, 2) }}
                </td>
            </tr>
            
            <!-- Investing Activities -->
            <tr style="background: #dbeafe;">
                <td colspan="2" style="font-weight: 800; color: #1e40af; font-size: 1.2rem; padding: 1rem; padding-top: 2rem;">
                    <i class="fas fa-chart-line"></i> التدفقات النقدية من الأنشطة الاستثمارية
                </td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">شراء أصول ثابتة</td>
                <td class="negative" style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">بيع أصول ثابتة</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr style="background: #eff6ff; font-weight: 700;">
                <td>صافي التدفقات النقدية من الأنشطة الاستثمارية</td>
                <td style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            
            <!-- Financing Activities -->
            <tr style="background: #fef3c7;">
                <td colspan="2" style="font-weight: 800; color: #92400e; font-size: 1.2rem; padding: 1rem; padding-top: 2rem;">
                    <i class="fas fa-hand-holding-usd"></i> التدفقات النقدية من الأنشطة التمويلية
                </td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">قروض جديدة</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">سداد قروض</td>
                <td class="negative" style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr>
                <td style="padding-right: 2rem;">توزيعات أرباح</td>
                <td class="negative" style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            <tr style="background: #fef9e7; font-weight: 700;">
                <td>صافي التدفقات النقدية من الأنشطة التمويلية</td>
                <td style="text-align: left; font-family: 'Courier New', monospace;">$0.00</td>
            </tr>
            
            <!-- Net Change -->
            <tr style="background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; font-weight: 800; font-size: 1.2rem;">
                <td style="padding: 1.5rem;">صافي الزيادة (النقص) في النقدية</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; padding: 1.5rem;">
                    ${{ number_format($netIncrease, 2) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 1rem;">النقدية في بداية الفترة</td>
                <td style="text-align: left; font-family: 'Courier New', monospace; padding: 1rem;">
                    ${{ number_format($openingCash, 2) }}
                </td>
            </tr>
            <tr style="background: #ecfdf5; font-weight: 800; font-size: 1.2rem;">
                <td style="padding: 1.5rem;">النقدية في نهاية الفترة</td>
                <td class="positive" style="text-align: left; font-family: 'Courier New', monospace; padding: 1.5rem;">
                    ${{ number_format($closingCash, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem; text-align: center;">
    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
</div>
@endsection
