@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تحضير الرواتب'; $subtitle = 'إدارة سجلات الرواتب وفلترة الفترات'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <form method="GET" action="{{ route('dashboard.hr.payroll') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-sm text-gray-700 mb-1">الفترة</label>
                <select name="pay_period" class="w-48 px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">الكل</option>
                    @foreach(($payPeriods ?? []) as $period)
                        <option value="{{ $period }}" @selected(request('pay_period') == $period)>{{ $period }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">الحالة</label>
                <select name="status" class="w-48 px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">الكل</option>
                    <option value="draft" @selected(request('status')=='draft')>مسودة</option>
                    <option value="sent" @selected(request('status')=='sent')>مُرسلة للمالية</option>
                    <option value="approved" @selected(request('status')=='approved')>موافقة مالية</option>
                    <option value="paid" @selected(request('status')=='paid')>مدفوعة</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تصفية</span>
            </button>
            <a href="{{ route('dashboard.hr.payroll') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
        </form>

        <a href="{{ route('dashboard.finance.payroll') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-coins"></i>
            <span>رواتب المالية</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900">تقارير الرواتب</h3>
            <div class="text-xs text-gray-500 mt-1">افتح تقرير موظف ثم أرسل راتبه للمالية من داخل التقرير.</div>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.payroll') }}" class="flex flex-wrap items-end gap-3">
            <input type="hidden" name="pay_period" value="{{ request('pay_period') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div>
                <label class="block text-sm text-gray-700 mb-1">السنة</label>
                <select name="report_year" class="w-32 px-4 py-2 rounded-xl border border-gray-200">
                    @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" @selected((int) ($reportYear ?? now()->year) === $y)>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">الشهر</label>
                <select name="report_month" class="w-32 px-4 py-2 rounded-xl border border-gray-200">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @selected((int) ($reportMonth ?? now()->month) === $m)>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-gray-950 transition">
                <i class="fas fa-eye"></i>
                <span>عرض</span>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الراتب الشهري</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأجر بالساعة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">صافي (إن وجد)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تقرير</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach(($employees ?? []) as $employee)
                    @php $rec = ($periodRecords[$employee->id] ?? null); @endphp
                    <tr>
                        <td class="px-6 py-4 text-gray-900 font-medium">
                            {{ optional($employee->user)->name ?? trim(($employee->first_name ?? '').' '.($employee->last_name ?? '')) ?? ('Employee #'.$employee->id) }}
                        </td>
                        @php
                            $monthly = (float) ($employee->monthly_salary ?? 0);
                            $salary = (float) ($employee->salary ?? 0);
                            $hourly = (float) ($employee->hourly_rate ?? 0);
                        @endphp
                        <td class="px-6 py-4 text-gray-700">@if($monthly > 0) @money($monthly) @elseif($salary > 0) @money($salary) @else - @endif</td>
                        <td class="px-6 py-4 text-gray-700">@if($hourly > 0) @money($hourly) @else - @endif</td>
                        <td class="px-6 py-4 text-gray-700">@if($rec?->net_pay) @money($rec->net_pay) @else - @endif</td>
                        <td class="px-6 py-4 text-gray-700">{{ $rec?->status ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('dashboard.hr.payroll.report', [$employee->id, $reportPayPeriod ?? now()->format('Y-m')]) }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-3 py-1 rounded-lg hover:bg-indigo-700 transition text-sm">
                                <i class="fas fa-file-alt"></i>
                                <span>فتح</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجلات الرواتب</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <div class="text-sm text-gray-600 mb-3">بعد الحساب يتم إنشاء سجلات رواتب (بدون إرسال للمالية).</div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الفترة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الصافي</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">معلومات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payrollRecords as $rec)
                    @php
                        $sent = !empty(data_get($rec->breakdown, 'salary_tx_id'));
                        $statusLabel = $rec->status === 'draft' && $sent ? 'sent' : $rec->status;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($rec->employee->user)->name ?? ('#'.$rec->employee_id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $rec->pay_period }}</td>
                        <td class="px-4 py-3 text-sm">@money($rec->net_pay ?? 0)</td>
                        <td class="px-4 py-3 text-sm">{{ $statusLabel }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            @if($sent)
                                Sent: {{ data_get($rec->breakdown, 'sent_to_finance_at') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $payrollRecords->links() }}</div>
    </div>
@endsection
