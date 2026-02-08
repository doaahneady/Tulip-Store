@extends('dashboards.layouts.app')
@section('content')
@php $title = 'التقارير المالية'; $subtitle = 'قائمة تقارير الأرباح والخسائر والتدفقات'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('dashboard.finance.reports') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <input type="date" name="date_from" value="{{ $dateFrom ?? request('date_from') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
        <input type="date" name="date_to" value="{{ $dateTo ?? request('date_to') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">

        <select name="group_by" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <option value="none" @selected(($groupBy ?? 'none') === 'none')>بدون تجميع</option>
            <option value="store" @selected(($groupBy ?? '') === 'store')>حسب المتجر</option>
            <option value="employee" @selected(($groupBy ?? '') === 'employee')>حسب الموظف</option>
        </select>

        <select name="store_id" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <option value="">تصفية بالمتجر</option>
            @foreach(($stores ?? []) as $s)
                @php $storeName = data_get($s, 'name'); @endphp
                <option value="{{ $s->id }}" @selected((string) ($storeId ?? request('store_id')) === (string) $s->id)>{{ is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName }}</option>
            @endforeach
        </select>

        <select name="employee_id" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <option value="">تصفية بالموظف</option>
            @foreach(($employees ?? []) as $e)
                <option value="{{ $e->id }}" @selected((string) ($employeeId ?? request('employee_id')) === (string) $e->id)>{{ ($e->first_name ?? '') . ' ' . ($e->last_name ?? '') }}</option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button class="flex-1 inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تطبيق</span>
            </button>
            <a href="{{ route('dashboard.finance.reports') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="text-sm text-gray-500">الإيرادات</div>
        <div class="text-2xl font-bold text-gray-900 mt-1">${{ number_format((float) ($summary['revenue'] ?? 0), 2) }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="text-sm text-gray-500">المصروفات</div>
        <div class="text-2xl font-bold text-gray-900 mt-1">${{ number_format((float) ($summary['expenses'] ?? 0), 2) }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="text-sm text-gray-500">الربح التقريبي</div>
        <div class="text-2xl font-bold text-gray-900 mt-1">${{ number_format((float) ($summary['profit'] ?? 0), 2) }}</div>
    </div>
</div>

@if(($groupBy ?? 'none') === 'store')
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">حسب المتجر</h3>
            <span class="text-sm text-gray-500">{{ number_format(count($tables['by_store'] ?? [])) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">المتجر</th>
                        <th class="text-right py-2">الإيرادات</th>
                        <th class="text-right py-2">المصروفات</th>
                        <th class="text-right py-2">الربح</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($tables['by_store'] ?? []) as $r)
                        @php
                            $storeName = data_get($r, 'name');
                            $rev = (float) (data_get($r, 'revenue_total') ?? 0);
                            $exp = (float) (data_get($r, 'expense_total') ?? 0);
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="py-3 text-gray-900 font-semibold">{{ is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName }}</td>
                            <td class="py-3 text-gray-700">${{ number_format($rev, 2) }}</td>
                            <td class="py-3 text-gray-700">${{ number_format($exp, 2) }}</td>
                            <td class="py-3 text-gray-900 font-semibold">${{ number_format($rev - $exp, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@elseif(($groupBy ?? 'none') === 'employee')
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">حسب الموظف (مصروفات)</h3>
            <span class="text-sm text-gray-500">{{ number_format(count($tables['by_employee'] ?? [])) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">الموظف</th>
                        <th class="text-right py-2">المصروفات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($tables['by_employee'] ?? []) as $r)
                        <tr class="border-t border-gray-100">
                            <td class="py-3 text-gray-900 font-semibold">{{ ($r->first_name ?? '') . ' ' . ($r->last_name ?? '') }}</td>
                            <td class="py-3 text-gray-900 font-semibold">${{ number_format((float) ($r->expense_total ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">رواتب الموظفين (شهرياً)</h3>
            <span class="text-sm text-gray-500">{{ number_format(count($salaryRows ?? [])) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">الموظف</th>
                        <th class="text-right py-2">الشهر</th>
                        <th class="text-right py-2">الصافي</th>
                        <th class="text-right py-2">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($salaryRows ?? []) as $r)
                        <tr class="border-t border-gray-100">
                            <td class="py-3 text-gray-900 font-semibold">{{ ($r->first_name ?? '') . ' ' . ($r->last_name ?? '') }}</td>
                            <td class="py-3 text-gray-700">{{ $r->month ?? '-' }}</td>
                            <td class="py-3 text-gray-900 font-semibold">${{ number_format((float) ($r->net_salary ?? 0), 2) }}</td>
                            <td class="py-3 text-gray-700">{{ $r->status ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
