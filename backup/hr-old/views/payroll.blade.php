@extends('layouts.dashboard')

@section('title', 'إدارة الرواتب')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-money-bill-wave text-purple-600"></i>
            <span class="text-sm text-gray-700">فلاتر الرواتب</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.payroll') }}" class="flex flex-wrap items-center gap-2">
            <select name="pay_period" class="form-select w-40">
                <option value="">الفترة</option>
                @foreach($payPeriods as $period)
                    <option value="{{ $period }}" @selected(request('pay_period')==$period)>{{ $period }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select w-40">
                <option value="">الحالة</option>
                <option value="draft" @selected(request('status')=='draft')>مسودة</option>
                <option value="approved" @selected(request('status')=='approved')>معتمدة</option>
                <option value="paid" @selected(request('status')=='paid')>مدفوعة</option>
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                تطبيق
            </button>
        </form>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">سجلات الرواتب</h3>
            <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة للوحة الموارد البشرية</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">الفترة</th>
                    <th class="px-4 py-2 text-left">ساعات عادية</th>
                    <th class="px-4 py-2 text-left">ساعات إضافية</th>
                    <th class="px-4 py-2 text-left">إجمالي</th>
                    <th class="px-4 py-2 text-left">الصافي</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">إجراءات</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($payrollRecords as $pr)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $pr->employee->user->name ?? ($pr->employee->first_name.' '.$pr->employee->last_name) }}</td>
                            <td class="px-4 py-2">{{ $pr->pay_period }}</td>
                            <td class="px-4 py-2">{{ number_format($pr->regular_hours, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($pr->overtime_hours, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($pr->gross_pay, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($pr->net_pay, 2) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $pr->status==='approved' ? 'bg-emerald-100 text-emerald-700' : ($pr->status==='paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $pr->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.hr.payroll.reports') }}?employee_id={{ $pr->employee_id }}&pay_period={{ $pr->pay_period }}" 
                                       class="btn btn-primary btn-sm" title="عرض تقرير مفصل">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    <a href="{{ route('dashboard.hr.payroll.reports') }}?employee_id={{ $pr->employee_id }}" 
                                       class="btn btn-secondary btn-sm" title="عرض سجل الرواتب">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            @if(method_exists(($payrollRecords ?? null),'links'))
                {{ $payrollRecords->links() }}
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">إجراءات</h3>
        <form method="POST" action="{{ route('dashboard.hr.payroll.calculate') }}" class="space-y-3">
            @csrf
            <input type="text" name="pay_period" class="form-input w-full" placeholder="YYYY-MM">
            <input type="date" name="period_start" class="form-input w-full">
            <input type="date" name="period_end" class="form-input w-full">
            <button class="w-full btn btn-primary">حساب الرواتب</button>
        </form>
        <hr class="my-4">
        <form method="POST" action="{{ route('dashboard.hr.payroll.submit') }}" class="space-y-3">
            @csrf
            <input type="text" name="pay_period" class="form-input w-full" placeholder="YYYY-MM">
            <button class="w-full btn btn-success">إرسال إلى المالية</button>
        </form>
    </div>
</div>
@endsection
