@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الورديات والجداول'; $subtitle = 'إدارة الورديات ومتابعتها'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('dashboard.hr.shifts') }}" class="flex flex-wrap items-center gap-3">
        <input type="date" name="date" value="{{ request('date') }}" class="form-input w-44">
        <select name="employee_id" class="form-select w-56">
            <option value="">الموظف</option>
            @foreach(($employees ?? []) as $e)
                <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>{{ optional($e->user)->name ?? ('#'.$e->id) }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select w-40">
            <option value="">الحالة</option>
            <option value="scheduled" @selected(request('status')=='scheduled')>مجدول</option>
            <option value="active" @selected(request('status')=='active')>نشط</option>
            <option value="completed" @selected(request('status')=='completed')>مكتمل</option>
            <option value="missed" @selected(request('status')=='missed')>فائت</option>
            <option value="no_show" @selected(request('status')=='no_show')>لم يحضر</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">تصفية</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجل الورديات</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">التاريخ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">من</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إلى</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($shifts as $shift)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($shift->employee->user)->name ?? ('#'.$shift->employee_id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ optional($shift->shift_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $shift->start_time }}</td>
                        <td class="px-4 py-3 text-sm">{{ $shift->end_time }}</td>
                        <td class="px-4 py-3 text-sm">{{ $shift->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $shifts->links() }}</div>
    </div>
@endsection

