@extends('layouts.accounting')

@section('title', 'الرواتب والأجور')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-money-check-alt"></i> الرواتب والأجور</h1>
    <p>إدارة رواتب الموظفين والمستحقات</p>
</div>

@php
$totalEmployees = \App\Models\User::where('is_admin', true)->count() + 15;
$monthlyPayroll = 85000;
$yearlyPayroll = $monthlyPayroll * 12;
$pendingPayments = 3;
@endphp

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: #eff6ff; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #1e3a8a;">
        <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">${{ number_format($monthlyPayroll, 0) }}</div>
        <div style="color: #1e40af; font-weight: 700; margin-top: 0.5rem;">إجمالي الرواتب الشهرية</div>
    </div>
    <div style="background: #d1fae5; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #047857;">
        <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: monospace;">{{ $totalEmployees }}</div>
        <div style="color: #065f46; font-weight: 700; margin-top: 0.5rem;">عدد الموظفين</div>
    </div>
    <div style="background: #e0e7ff; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #4f46e5;">
        <div style="font-size: 2rem; font-weight: 800; color: #4f46e5; font-family: monospace;">${{ number_format($yearlyPayroll, 0) }}</div>
        <div style="color: #3730a3; font-weight: 700; margin-top: 0.5rem;">الرواتب السنوية</div>
    </div>
    <div style="background: #fef3c7; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #d97706;">
        <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: monospace;">{{ $pendingPayments }}</div>
        <div style="color: #92400e; font-weight: 700; margin-top: 0.5rem;">مدفوعات معلقة</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-users"></i>
        <span>كشف رواتب الموظفين - ديسمبر 2025</span>
        <div style="margin-right: auto; display: flex; gap: 0.5rem;">
            <button class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-calendar"></i> تغيير الشهر
            </button>
            <button class="btn btn-primary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-plus"></i> إضافة موظف
            </button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>رقم الموظف</th>
                <th>الاسم</th>
                <th>القسم</th>
                <th>الراتب الأساسي</th>
                <th>البدلات</th>
                <th>الخصومات</th>
                <th>صافي الراتب</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php
            $employees = [
                ['id' => 'EMP-001', 'name' => 'أحمد محمد العلي', 'dept' => 'الإدارة', 'basic' => 15000, 'allowances' => 3000, 'deductions' => 900, 'status' => 'مدفوع'],
                ['id' => 'EMP-002', 'name' => 'فاطمة أحمد السالم', 'dept' => 'المحاسبة', 'basic' => 12000, 'allowances' => 2400, 'deductions' => 720, 'status' => 'مدفوع'],
                ['id' => 'EMP-003', 'name' => 'محمد عبدالله الحربي', 'dept' => 'المبيعات', 'basic' => 10000, 'allowances' => 2000, 'deductions' => 600, 'status' => 'معلق'],
                ['id' => 'EMP-004', 'name' => 'نورة سعد القحطاني', 'dept' => 'الموارد البشرية', 'basic' => 11000, 'allowances' => 2200, 'deductions' => 660, 'status' => 'مدفوع'],
                ['id' => 'EMP-005', 'name' => 'خالد عمر المطيري', 'dept' => 'تقنية المعلومات', 'basic' => 13000, 'allowances' => 2600, 'deductions' => 780, 'status' => 'معلق'],
                ['id' => 'EMP-006', 'name' => 'سارة فهد الدوسري', 'dept' => 'خدمة العملاء', 'basic' => 9000, 'allowances' => 1800, 'deductions' => 540, 'status' => 'مدفوع'],
                ['id' => 'EMP-007', 'name' => 'عبدالرحمن ناصر الشمري', 'dept' => 'المشتريات', 'basic' => 10500, 'allowances' => 2100, 'deductions' => 630, 'status' => 'معلق'],
                ['id' => 'EMP-008', 'name' => 'مريم علي الزهراني', 'dept' => 'التسويق', 'basic' => 11500, 'allowances' => 2300, 'deductions' => 690, 'status' => 'مدفوع'],
            ];
            @endphp
            @foreach($employees as $emp)
            @php $netSalary = $emp['basic'] + $emp['allowances'] - $emp['deductions']; @endphp
            <tr>
                <td style="font-family: monospace; font-weight: 700; color: #4f46e5;">{{ $emp['id'] }}</td>
                <td>
                    <div style="font-weight: 700; color: #1e3a8a;">{{ $emp['name'] }}</div>
                </td>
                <td>
                    <span style="background: #e0e7ff; color: #4f46e5; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                        {{ $emp['dept'] }}
                    </span>
                </td>
                <td class="positive" style="font-family: monospace; font-weight: 700;">${{ number_format($emp['basic'], 2) }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($emp['allowances'], 2) }}</td>
                <td class="negative" style="font-family: monospace;">${{ number_format($emp['deductions'], 2) }}</td>
                <td class="positive" style="font-family: monospace; font-weight: 800; font-size: 1.1rem;">${{ number_format($netSalary, 2) }}</td>
                <td>
                    @if($emp['status'] === 'مدفوع')
                        <span style="background: #d1fae5; color: #047857; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">مدفوع</span>
                    @else
                        <span style="background: #fef3c7; color: #d97706; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">معلق</span>
                    @endif
                </td>
                <td>
                    @if($emp['status'] === 'معلق')
                        <button class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                            <i class="fas fa-check"></i> دفع
                        </button>
                    @else
                        <button class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                            <i class="fas fa-file-pdf"></i> كشف
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f3f4f6; font-weight: 800;">
                <td colspan="3" style="text-align: left; padding: 1rem;">الإجمالي</td>
                <td class="positive" style="font-family: monospace;">${{ number_format(array_sum(array_column($employees, 'basic')), 2) }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format(array_sum(array_column($employees, 'allowances')), 2) }}</td>
                <td class="negative" style="font-family: monospace;">${{ number_format(array_sum(array_column($employees, 'deductions')), 2) }}</td>
                <td class="positive" style="font-family: monospace; font-size: 1.2rem;">
                    ${{ number_format(array_sum(array_map(function($e) { return $e['basic'] + $e['allowances'] - $e['deductions']; }, $employees)), 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Payroll History -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-history"></i>
        <span>سجل الرواتب الشهرية</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>الشهر</th>
                <th>عدد الموظفين</th>
                <th>إجمالي الرواتب</th>
                <th>إجمالي البدلات</th>
                <th>إجمالي الخصومات</th>
                <th>صافي المدفوعات</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @php
            $months = [
                ['month' => 'ديسمبر 2025', 'employees' => 18, 'basic' => 92000, 'allowances' => 18400, 'deductions' => 5520, 'status' => 'جاري'],
                ['month' => 'نوفمبر 2025', 'employees' => 18, 'basic' => 92000, 'allowances' => 18400, 'deductions' => 5520, 'status' => 'مكتمل'],
                ['month' => 'أكتوبر 2025', 'employees' => 17, 'basic' => 87000, 'allowances' => 17400, 'deductions' => 5220, 'status' => 'مكتمل'],
                ['month' => 'سبتمبر 2025', 'employees' => 17, 'basic' => 87000, 'allowances' => 17400, 'deductions' => 5220, 'status' => 'مكتمل'],
                ['month' => 'أغسطس 2025', 'employees' => 16, 'basic' => 82000, 'allowances' => 16400, 'deductions' => 4920, 'status' => 'مكتمل'],
            ];
            @endphp
            @foreach($months as $month)
            @php $netPayroll = $month['basic'] + $month['allowances'] - $month['deductions']; @endphp
            <tr>
                <td style="font-weight: 700; color: #1e3a8a;">{{ $month['month'] }}</td>
                <td style="font-family: monospace; font-weight: 600;">{{ $month['employees'] }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($month['basic'], 2) }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($month['allowances'], 2) }}</td>
                <td class="negative" style="font-family: monospace;">${{ number_format($month['deductions'], 2) }}</td>
                <td class="positive" style="font-family: monospace; font-weight: 800;">${{ number_format($netPayroll, 2) }}</td>
                <td>
                    @if($month['status'] === 'مكتمل')
                        <span style="background: #d1fae5; color: #047857; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">مكتمل</span>
                    @else
                        <span style="background: #fef3c7; color: #d97706; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">جاري</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Department Summary -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-pie"></i>
        <span>ملخص الرواتب حسب القسم</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <div style="background: #eff6ff; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">$18,000</div>
            <div style="color: #1e40af; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">الإدارة</div>
            <div style="color: #6b7280; font-size: 0.8rem;">1 موظف</div>
        </div>
        <div style="background: #e0e7ff; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #4f46e5; font-family: monospace;">$27,680</div>
            <div style="color: #3730a3; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">المحاسبة + IT</div>
            <div style="color: #6b7280; font-size: 0.8rem;">2 موظف</div>
        </div>
        <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #047857; font-family: monospace;">$24,970</div>
            <div style="color: #065f46; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">المبيعات + التسويق</div>
            <div style="color: #6b7280; font-size: 0.8rem;">2 موظف</div>
        </div>
        <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #d97706; font-family: monospace;">$34,230</div>
            <div style="color: #92400e; font-weight: 700; font-size: 0.9rem; margin-top: 0.5rem;">أقسام أخرى</div>
            <div style="color: #6b7280; font-size: 0.8rem;">3 موظفين</div>
        </div>
    </div>
</div>
@endsection
