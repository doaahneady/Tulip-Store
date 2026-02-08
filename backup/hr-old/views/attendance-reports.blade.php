@extends('layouts.dashboard')

@section('title', 'تقارير الحضور')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-user-check text-green-600"></i>
            <span class="text-sm text-gray-700">فلاتر التقرير</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.attendance-reports') }}" class="flex flex-wrap items-center gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-40">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-40">
            <select name="employee_id" class="form-select w-56">
                <option value="">الموظف</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('employee_id')==$emp->id)>{{ $emp->user->name ?? ($emp->first_name.' '.$emp->last_name) }}</option>
                @endforeach
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
        <div class="text-sm text-gray-500">حضور</div>
        <div class="text-2xl font-bold text-gray-800">{{ number_format($summary['present'] ?? 0) }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="text-sm text-gray-500">تأخير</div>
        <div class="text-2xl font-bold text-gray-800">{{ number_format($summary['late'] ?? 0) }}</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="text-sm text-gray-500">خروج مبكر</div>
        <div class="text-2xl font-bold text-gray-800">{{ number_format($summary['early_leave'] ?? 0) }}</div>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">سجلات الحضور</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">التاريخ</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">الدخول</th>
                    <th class="px-4 py-2 text-left">الخروج</th>
                    <th class="px-4 py-2 text-left">ساعات العمل</th>
                    <th class="px-4 py-2 text-left">ساعات إضافية</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $a)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $a->employee->user->name ?? ($a->employee->first_name.' '.$a->employee->last_name) }}</td>
                        <td class="px-4 py-2">{{ $a->date }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs
                                @switch($a->status)
                                    @case('present') bg-green-100 text-green-700 @break
                                    @case('late') bg-yellow-100 text-yellow-700 @break
                                    @case('early_leave') bg-orange-100 text-orange-700 @break
                                    @case('absent') bg-red-100 text-red-700 @break
                                    @default bg-gray-100 text-gray-700
                                @endswitch">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ optional($a->check_in)->format('H:i') ?? ($a->check_in ?? '-') }}</td>
                        <td class="px-4 py-2">{{ optional($a->check_out)->format('H:i') ?? ($a->check_out ?? '-') }}</td>
                        <td class="px-4 py-2">{{ number_format($a->work_hours ?? 0, 2) }}</td>
                        <td class="px-4 py-2">{{ number_format($a->overtime_hours ?? 0, 2) }}</td>
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
        @if(method_exists(($attendance ?? null),'links'))
            {{ $attendance->links() }}
        @endif
    </div>
</div>
@endsection
