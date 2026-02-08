@extends('layouts.dashboard')

@section('title', 'الساعات الإضافية')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-clock text-orange-600"></i>
            <span class="text-sm text-gray-700">سجل الإضافي</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.overtime') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ request('date') }}" class="form-input w-44">
            <select name="employee_id" class="form-select w-44">
                <option value="">كل الموظفين</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->user->name ?? $emp->first_name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                تصفية
            </button>
            <a href="{{ route('dashboard.hr.index') }}" class="btn btn-secondary btn-sm">عودة</a>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">سجلات العمل الإضافي</h3>
        <div class="text-sm text-gray-500">ساعات العمل الإضافية للموظفين</div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">التاريخ</th>
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">الدخول</th>
                    <th class="px-4 py-2 text-left">الخروج</th>
                    <th class="px-4 py-2 text-left">ساعات العمل</th>
                    <th class="px-4 py-2 text-left">ساعات الإضافي</th>
                    <th class="px-4 py-2 text-left">إجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($overtimeRecords as $row)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $row->date->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ $row->employee->user->name ?? 'غير معروف' }}</td>
                    <td class="px-4 py-2">{{ optional($row->check_in)->format('H:i') ?? '-' }}</td>
                    <td class="px-4 py-2">{{ optional($row->check_out)->format('H:i') ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $row->work_hours !== null ? number_format($row->work_hours, 2) : '-' }}</td>
                    <td class="px-4 py-2">
                        @if($row->overtime_hours > 0)
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700 font-bold">
                                {{ number_format($row->overtime_hours, 2) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard.hr.attendance') }}?date={{ $row->date->format('Y-m-d') }}&employee_id={{ $row->employee_id }}" 
                               class="btn btn-primary btn-sm" title="عرض تفاصيل الحضور">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('dashboard.hr.payroll') }}?employee_id={{ $row->employee_id }}" 
                               class="btn btn-secondary btn-sm" title="عرض كشف الراتب">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات إضافي</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists($overtimeRecords, 'links'))
            {{ $overtimeRecords->links() }}
        @endif
    </div>
</div>
@endsection
