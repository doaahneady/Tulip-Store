@extends('layouts.dashboard')

@section('title', 'تقارير الرواتب')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-file-invoice-dollar text-purple-600"></i>
            <span class="text-sm text-gray-700">فلاتر التقرير</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.payroll-reports') }}" class="flex flex-wrap items-center gap-2">
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
    <div class="px-4 pb-3">
        <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة للوحة الموارد البشرية</a>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="text-sm text-gray-500">إجمالي الإجمالي</div>
        <div class="text-2xl font-bold text-gray-800">{{ number_format($totals['gross'] ?? 0, 2) }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="text-sm text-gray-500">إجمالي الصافي</div>
        <div class="text-2xl font-bold text-gray-800">{{ number_format($totals['net'] ?? 0, 2) }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="text-sm text-gray-500">عدد السجلات</div>
        <div class="text-2xl font-bold text-gray-800">{{ number_format($totals['count'] ?? 0) }}</div>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">سجلات الرواتب</h3>
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
                </tr>
            </thead>
            <tbody>
                @forelse($records as $pr)
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($records ?? null),'links'))
            {{ $records->links() }}
        @endif
    </div>
</div>
@endsection
