@extends('layouts.accounting')

@section('title', 'الأصول الثابتة')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-building"></i> الأصول الثابتة</h1>
    <p>إدارة ومتابعة الأصول الثابتة والاستهلاك</p>
</div>

@php
$fixedAssetsAccount = \App\Models\ChartOfAccount::where('account_code', '1300')->first();
$totalFixedAssets = $fixedAssetsAccount ? $fixedAssetsAccount->current_balance : 150000;
$accumulatedDepreciation = $totalFixedAssets * 0.25;
$netBookValue = $totalFixedAssets - $accumulatedDepreciation;
$currentYearDepreciation = $totalFixedAssets * 0.10;
@endphp

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: #eff6ff; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #1e3a8a;">
        <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">${{ number_format($totalFixedAssets, 0) }}</div>
        <div style="color: #1e40af; font-weight: 700; margin-top: 0.5rem;">التكلفة الأصلية</div>
    </div>
    <div style="background: #fee2e2; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #dc2626;">
        <div style="font-size: 2rem; font-weight: 800; color: #dc2626; font-family: monospace;">${{ number_format($accumulatedDepreciation, 0) }}</div>
        <div style="color: #991b1b; font-weight: 700; margin-top: 0.5rem;">الاستهلاك المتراكم</div>
    </div>
    <div style="background: #d1fae5; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #047857;">
        <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: monospace;">${{ number_format($netBookValue, 0) }}</div>
        <div style="color: #065f46; font-weight: 700; margin-top: 0.5rem;">صافي القيمة الدفترية</div>
    </div>
    <div style="background: #fef3c7; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #d97706;">
        <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: monospace;">${{ number_format($currentYearDepreciation, 0) }}</div>
        <div style="color: #92400e; font-weight: 700; margin-top: 0.5rem;">استهلاك السنة الحالية</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list"></i>
        <span>سجل الأصول الثابتة</span>
        <div style="margin-right: auto;">
            <button class="btn btn-primary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-plus"></i> إضافة أصل جديد
            </button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>كود الأصل</th>
                <th>اسم الأصل</th>
                <th>الفئة</th>
                <th>تاريخ الشراء</th>
                <th>التكلفة</th>
                <th>الاستهلاك المتراكم</th>
                <th>القيمة الدفترية</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @php
            $assets = [
                ['code' => 'FA-001', 'name' => 'مبنى المكتب الرئيسي', 'category' => 'مباني', 'date' => '2020-01-15', 'cost' => 500000, 'depreciation' => 50000, 'status' => 'نشط'],
                ['code' => 'FA-002', 'name' => 'سيارة نقل - تويوتا', 'category' => 'مركبات', 'date' => '2022-06-10', 'cost' => 80000, 'depreciation' => 24000, 'status' => 'نشط'],
                ['code' => 'FA-003', 'name' => 'أجهزة كمبيوتر (10 وحدات)', 'category' => 'معدات', 'date' => '2023-03-20', 'cost' => 25000, 'depreciation' => 8333, 'status' => 'نشط'],
                ['code' => 'FA-004', 'name' => 'أثاث المكتب', 'category' => 'أثاث', 'date' => '2021-09-05', 'cost' => 35000, 'depreciation' => 11667, 'status' => 'نشط'],
                ['code' => 'FA-005', 'name' => 'نظام الأمن والمراقبة', 'category' => 'معدات', 'date' => '2022-11-12', 'cost' => 18000, 'depreciation' => 4500, 'status' => 'نشط'],
                ['code' => 'FA-006', 'name' => 'مولد كهربائي', 'category' => 'معدات', 'date' => '2021-04-18', 'cost' => 22000, 'depreciation' => 8800, 'status' => 'نشط'],
                ['code' => 'FA-007', 'name' => 'نظام تكييف مركزي', 'category' => 'معدات', 'date' => '2020-08-25', 'cost' => 45000, 'depreciation' => 18000, 'status' => 'نشط'],
                ['code' => 'FA-008', 'name' => 'سيارة إدارية - لكزس', 'category' => 'مركبات', 'date' => '2023-01-10', 'cost' => 120000, 'depreciation' => 24000, 'status' => 'نشط'],
            ];
            @endphp
            @foreach($assets as $asset)
            @php $bookValue = $asset['cost'] - $asset['depreciation']; @endphp
            <tr>
                <td style="font-family: monospace; font-weight: 700; color: #4f46e5;">{{ $asset['code'] }}</td>
                <td>
                    <div style="font-weight: 700; color: #1e3a8a;">{{ $asset['name'] }}</div>
                </td>
                <td>
                    <span style="background: #e0e7ff; color: #4f46e5; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                        {{ $asset['category'] }}
                    </span>
                </td>
                <td>{{ $asset['date'] }}</td>
                <td class="positive" style="font-family: monospace; font-weight: 700;">${{ number_format($asset['cost'], 2) }}</td>
                <td class="negative" style="font-family: monospace;">${{ number_format($asset['depreciation'], 2) }}</td>
                <td class="positive" style="font-family: monospace; font-weight: 800;">${{ number_format($bookValue, 2) }}</td>
                <td>
                    <span style="background: #d1fae5; color: #047857; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                        {{ $asset['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Depreciation Schedule -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-line"></i>
        <span>جدول الاستهلاك السنوي</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>السنة</th>
                <th>الاستهلاك السنوي</th>
                <th>الاستهلاك المتراكم</th>
                <th>القيمة الدفترية</th>
            </tr>
        </thead>
        <tbody>
            @php
            $years = [
                ['year' => '2025', 'annual' => 15000, 'accumulated' => 37500, 'book_value' => 112500],
                ['year' => '2024', 'annual' => 15000, 'accumulated' => 22500, 'book_value' => 127500],
                ['year' => '2023', 'annual' => 15000, 'accumulated' => 7500, 'book_value' => 142500],
                ['year' => '2022', 'annual' => 7500, 'accumulated' => 0, 'book_value' => 150000],
            ];
            @endphp
            @foreach($years as $year)
            <tr>
                <td style="font-weight: 700; color: #1e3a8a;">{{ $year['year'] }}</td>
                <td class="negative" style="font-family: monospace; font-weight: 700;">${{ number_format($year['annual'], 2) }}</td>
                <td class="negative" style="font-family: monospace;">${{ number_format($year['accumulated'], 2) }}</td>
                <td class="positive" style="font-family: monospace; font-weight: 800;">${{ number_format($year['book_value'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Asset Categories Summary -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-pie"></i>
        <span>ملخص الأصول حسب الفئة</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <div style="background: #eff6ff; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">$500,000</div>
            <div style="color: #1e40af; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">مباني</div>
            <div style="color: #6b7280; font-size: 0.8rem;">1 أصل</div>
        </div>
        <div style="background: #e0e7ff; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #4f46e5; font-family: monospace;">$200,000</div>
            <div style="color: #3730a3; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">مركبات</div>
            <div style="color: #6b7280; font-size: 0.8rem;">2 أصل</div>
        </div>
        <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #047857; font-family: monospace;">$110,000</div>
            <div style="color: #065f46; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">معدات</div>
            <div style="color: #6b7280; font-size: 0.8rem;">4 أصول</div>
        </div>
        <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #d97706; font-family: monospace;">$35,000</div>
            <div style="color: #92400e; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">أثاث</div>
            <div style="color: #6b7280; font-size: 0.8rem;">1 أصل</div>
        </div>
    </div>
</div>
@endsection
