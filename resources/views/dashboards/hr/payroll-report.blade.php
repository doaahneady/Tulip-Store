@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تقرير الراتب'; $subtitle = 'تفاصيل الحضور والراتب وإرسال للمالية'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="text-gray-900 font-semibold text-lg">
                {{ $employee->user?->name ?? trim(($employee->first_name ?? '').' '.($employee->last_name ?? '')) ?? ('Employee #'.$employee->id) }}
            </div>
            <div class="text-gray-500 text-sm">الفترة: {{ $pay_period }}</div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.hr.payroll', ['report_year' => (int) substr($pay_period, 0, 4), 'report_month' => (int) substr($pay_period, 5, 2)]) }}" class="px-4 py-2 rounded-xl border">رجوع</a>
            @php
                $sent = (bool) (data_get($record?->breakdown, 'salary_tx_id') ?? false);
                $status = $record?->status ?? 'draft';
            @endphp
            @if($sent)
                <span class="px-3 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-sm">تم الإرسال للمالية</span>
            @else
                <span class="px-3 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm">غير مرسل</span>
            @endif
            <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-700 text-sm">الحالة: {{ $status }}</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">ملخص الحضور</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-gray-50">
                <div class="text-gray-500 text-sm">أيام العمل</div>
                <div class="text-2xl font-bold text-gray-900">{{ (int) ($summary['days_worked'] ?? 0) }}</div>
            </div>
            <div class="p-4 rounded-xl bg-gray-50">
                <div class="text-gray-500 text-sm">أيام الغياب</div>
                <div class="text-2xl font-bold text-gray-900">{{ (int) ($summary['days_absent'] ?? 0) }}</div>
            </div>
            <div class="p-4 rounded-xl bg-gray-50">
                <div class="text-gray-500 text-sm">أيام التأخير</div>
                <div class="text-2xl font-bold text-gray-900">{{ (int) ($summary['days_late'] ?? 0) }}</div>
            </div>
            <div class="p-4 rounded-xl bg-gray-50">
                <div class="text-gray-500 text-sm">أيام الإجازة (موافق عليها)</div>
                <div class="text-2xl font-bold text-gray-900">{{ (int) ($summary['leave_days'] ?? 0) }}</div>
            </div>
            <div class="p-4 rounded-xl bg-gray-50">
                <div class="text-gray-500 text-sm">ساعات العمل</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format((float) ($summary['regular_hours'] ?? 0), 2) }}</div>
            </div>
            <div class="p-4 rounded-xl bg-gray-50">
                <div class="text-gray-500 text-sm">ساعات العمل الإضافي</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format((float) ($summary['overtime_hours'] ?? 0), 2) }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">ملخص الراتب</h3>
        <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between"><span class="text-gray-600">الأساسي</span><span class="font-semibold text-gray-900">@money((float) ($summary['base_pay'] ?? 0))</span></div>
            <div class="flex items-center justify-between"><span class="text-gray-600">الإضافي</span><span class="font-semibold text-gray-900">@money((float) ($summary['overtime_pay'] ?? 0))</span></div>
            <div class="flex items-center justify-between"><span class="text-gray-600">الإجمالي</span><span class="font-semibold text-gray-900">@money((float) ($summary['gross_pay'] ?? 0))</span></div>
            <div class="flex items-center justify-between"><span class="text-gray-600">الخصومات</span><span class="font-semibold text-gray-900">@money((float) ($summary['deductions'] ?? 0))</span></div>
            <div class="border-t pt-2 flex items-center justify-between"><span class="text-gray-600">الصافي</span><span class="font-bold text-emerald-700 text-lg">@money((float) ($summary['net_pay'] ?? 0))</span></div>
        </div>

        <div class="mt-6">
            @php $sent = (bool) (data_get($record?->breakdown, 'salary_tx_id') ?? false); @endphp
            @if(! $sent)
                <form method="POST" action="{{ route('dashboard.hr.payroll.report.submit', [$employee->id, $pay_period]) }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">إرسال للمالية</button>
                </form>
            @else
                <div class="w-full px-4 py-3 rounded-xl bg-emerald-100 text-emerald-700 text-center">تم الإرسال بالفعل</div>
            @endif
        </div>
    </div>
</div>
@endsection
