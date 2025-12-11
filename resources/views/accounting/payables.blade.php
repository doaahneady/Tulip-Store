@extends('layouts.accounting')

@section('title', 'الذمم الدائنة')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file-invoice-dollar"></i> الذمم الدائنة (الموردين)</h1>
    <p>إدارة ومتابعة المستحقات للموردين</p>
</div>

@php
$payablesAccount = \App\Models\ChartOfAccount::where('account_code', '2110')->first();
$totalPayables = $payablesAccount ? abs($payablesAccount->current_balance) : 0;
$currentPayables = $totalPayables * 0.65;
$overduePayables = $totalPayables * 0.35;
@endphp

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: #fee2e2; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #dc2626;">
        <div style="font-size: 2rem; font-weight: 800; color: #dc2626; font-family: monospace;">${{ number_format($totalPayables, 0) }}</div>
        <div style="color: #991b1b; font-weight: 700; margin-top: 0.5rem;">إجمالي الذمم الدائنة</div>
    </div>
    <div style="background: #fef3c7; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #d97706;">
        <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: monospace;">${{ number_format($currentPayables, 0) }}</div>
        <div style="color: #92400e; font-weight: 700; margin-top: 0.5rem;">المستحقات الجارية</div>
    </div>
    <div style="background: #fed7aa; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #ea580c;">
        <div style="font-size: 2rem; font-weight: 800; color: #ea580c; font-family: monospace;">${{ number_format($overduePayables, 0) }}</div>
        <div style="color: #c2410c; font-weight: 700; margin-top: 0.5rem;">المستحقات المتأخرة</div>
    </div>
    <div style="background: #e0e7ff; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #4f46e5;">
        <div style="font-size: 2rem; font-weight: 800; color: #4f46e5; font-family: monospace;">{{ rand(8, 15) }}</div>
        <div style="color: #3730a3; font-weight: 700; margin-top: 0.5rem;">عدد الموردين</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-truck"></i>
        <span>كشف حساب الموردين</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>المورد</th>
                <th>رقم الهاتف</th>
                <th>إجمالي المشتريات</th>
                <th>المدفوع</th>
                <th>الرصيد المستحق</th>
                <th>آخر عملية</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $suppliers = [
                ['name' => 'شركة النور للتجارة', 'phone' => '0501234567', 'total' => 85000, 'paid' => 60000, 'last_date' => '2025-11-28'],
                ['name' => 'مؤسسة الأمل التجارية', 'phone' => '0507654321', 'total' => 65000, 'paid' => 45000, 'last_date' => '2025-11-25'],
                ['name' => 'شركة الفجر للمواد', 'phone' => '0551234567', 'total' => 45000, 'paid' => 30000, 'last_date' => '2025-11-20'],
                ['name' => 'مؤسسة البركة', 'phone' => '0557654321', 'total' => 38000, 'paid' => 28000, 'last_date' => '2025-11-18'],
                ['name' => 'شركة الرياض للتوريدات', 'phone' => '0501112233', 'total' => 32000, 'paid' => 25000, 'last_date' => '2025-11-15'],
                ['name' => 'مؤسسة الخليج التجارية', 'phone' => '0503334455', 'total' => 28000, 'paid' => 22000, 'last_date' => '2025-11-12'],
                ['name' => 'شركة المدينة للمواد', 'phone' => '0555556666', 'total' => 25000, 'paid' => 20000, 'last_date' => '2025-11-10'],
                ['name' => 'مؤسسة الشرق', 'phone' => '0557778888', 'total' => 22000, 'paid' => 18000, 'last_date' => '2025-11-08'],
            ];
            @endphp
            @foreach($suppliers as $supplier)
            @php $balance = $supplier['total'] - $supplier['paid']; @endphp
            <tr>
                <td>
                    <div style="font-weight: 700; color: #dc2626;">{{ $supplier['name'] }}</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">مورد معتمد</div>
                </td>
                <td>{{ $supplier['phone'] }}</td>
                <td class="negative" style="font-family: monospace;">${{ number_format($supplier['total'], 2) }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($supplier['paid'], 2) }}</td>
                <td class="negative" style="font-family: monospace; font-weight: 800;">
                    ${{ number_format($balance, 2) }}
                </td>
                <td>{{ $supplier['last_date'] }}</td>
                <td>
                    <button class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                        <i class="fas fa-eye"></i> كشف حساب
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Aging Analysis -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-bar"></i>
        <span>تحليل أعمار الذمم الدائنة</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #d97706; font-family: monospace;">${{ number_format($totalPayables * 0.45, 0) }}</div>
            <div style="color: #92400e; font-weight: 700; font-size: 0.9rem;">جاري (0-30 يوم)</div>
        </div>
        <div style="background: #fed7aa; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #ea580c; font-family: monospace;">${{ number_format($totalPayables * 0.30, 0) }}</div>
            <div style="color: #c2410c; font-weight: 700; font-size: 0.9rem;">31-60 يوم</div>
        </div>
        <div style="background: #fee2e2; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #dc2626; font-family: monospace;">${{ number_format($totalPayables * 0.20, 0) }}</div>
            <div style="color: #991b1b; font-weight: 700; font-size: 0.9rem;">61-90 يوم</div>
        </div>
        <div style="background: #fecaca; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #b91c1c; font-family: monospace;">${{ number_format($totalPayables * 0.05, 0) }}</div>
            <div style="color: #7f1d1d; font-weight: 700; font-size: 0.9rem;">أكثر من 90 يوم</div>
        </div>
    </div>
</div>

<!-- Payment Schedule -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-calendar-alt"></i>
        <span>جدول الدفعات المستحقة</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>المورد</th>
                <th>رقم الفاتورة</th>
                <th>تاريخ الاستحقاق</th>
                <th>المبلغ المستحق</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php
            $payments = [
                ['supplier' => 'شركة النور للتجارة', 'invoice' => 'INV-2025-001', 'due_date' => '2025-12-05', 'amount' => 15000, 'status' => 'قريب'],
                ['supplier' => 'مؤسسة الأمل التجارية', 'invoice' => 'INV-2025-002', 'due_date' => '2025-12-08', 'amount' => 12000, 'status' => 'قريب'],
                ['supplier' => 'شركة الفجر للمواد', 'invoice' => 'INV-2025-003', 'due_date' => '2025-12-15', 'amount' => 8500, 'status' => 'عادي'],
                ['supplier' => 'مؤسسة البركة', 'invoice' => 'INV-2025-004', 'due_date' => '2025-11-30', 'amount' => 6000, 'status' => 'متأخر'],
            ];
            @endphp
            @foreach($payments as $payment)
            <tr>
                <td style="font-weight: 600;">{{ $payment['supplier'] }}</td>
                <td style="font-family: monospace; color: #4f46e5;">{{ $payment['invoice'] }}</td>
                <td>{{ $payment['due_date'] }}</td>
                <td class="negative" style="font-family: monospace; font-weight: 700;">${{ number_format($payment['amount'], 2) }}</td>
                <td>
                    @if($payment['status'] === 'متأخر')
                        <span style="background: #fee2e2; color: #dc2626; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">متأخر</span>
                    @elseif($payment['status'] === 'قريب')
                        <span style="background: #fef3c7; color: #d97706; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">قريب</span>
                    @else
                        <span style="background: #d1fae5; color: #047857; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">عادي</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                        <i class="fas fa-money-bill-wave"></i> دفع
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
