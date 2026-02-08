@extends('layouts.dashboard')

@section('title', 'الحضور والانصراف')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-clock text-green-600"></i>
            <span class="text-sm text-gray-700">الحضور حسب التاريخ</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.attendance') }}" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}" class="form-input w-44">
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                عرض
            </button>
            <a href="{{ route('dashboard.hr.index') }}" class="btn btn-secondary btn-sm">عودة للوحة الموارد البشرية</a>
        </form>
    </div>
    </div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">سجل الحضور ليوم {{ $date }}</h3>
        <div class="text-sm text-gray-500">تسجيل دخول/خروج وحساب ساعات العمل</div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">الدخول</th>
                    <th class="px-4 py-2 text-left">الخروج</th>
                    <th class="px-4 py-2 text-left">ساعات العمل</th>
                    <th class="px-4 py-2 text-left">إضافي</th>
                    <th class="px-4 py-2 text-left">إجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($attendance as $row)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $row->employee->user->name ?? 'غير معروف' }}</td>
                    <td class="px-4 py-2">
                        @php $color = ['present'=>'green','absent'=>'red','late'=>'yellow','on_leave'=>'blue','early_leave'=>'orange'][$row->status] ?? 'gray'; @endphp
                        <span class="px-2 py-1 rounded text-xs bg-{{ $color }}-100 text-{{ $color }}-700">{{ $row->status ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-2">{{ optional($row->check_in)->format('H:i') ?? '-' }}</td>
                    <td class="px-4 py-2">{{ optional($row->check_out)->format('H:i') ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $row->work_hours !== null ? number_format($row->work_hours, 2) : '-' }}</td>
                    <td class="px-4 py-2">{{ $row->overtime_hours !== null ? number_format($row->overtime_hours, 2) : '-' }}</td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <!-- Clock In -->
                            <form method="POST" action="{{ route('dashboard.hr.attendance.clock-in') }}">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $row->employee_id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button class="btn btn-success btn-sm"><i class="fas fa-sign-in-alt"></i> دخول</button>
                            </form>
                            <!-- Clock Out -->
                            <form method="POST" action="{{ route('dashboard.hr.attendance.clock-out') }}">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $row->employee_id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> خروج</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات لهذا اليوم</td></tr>
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

