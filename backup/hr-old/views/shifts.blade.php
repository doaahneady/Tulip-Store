@extends('layouts.dashboard')

@section('title', 'الورديات والجدولة')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-check text-blue-600"></i>
            <span class="text-sm text-gray-700">جدولة ورديات</span>
        </div>
        <form method="POST" action="{{ route('dashboard.hr.shifts.schedule') }}" class="flex flex-wrap items-center gap-2">
            @csrf
            <select name="employee_id" class="form-select w-48">
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->user->name ?? ($emp->first_name.' '.$emp->last_name) }}</option>
                @endforeach
            </select>
            <input type="date" name="shift_date" class="form-input w-40">
            <input type="time" name="start_time" class="form-input w-32">
            <input type="time" name="end_time" class="form-input w-32">
            <input type="text" name="notes" class="form-input w-48" placeholder="ملاحظات">
            <button class="btn btn-primary btn-sm">جدولة</button>
        </form>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">السجلات</h3>
        <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">التاريخ</th>
                    <th class="px-4 py-2 text-left">البداية</th>
                    <th class="px-4 py-2 text-left">النهاية</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                </tr>
            </thead>
            <tbody>
            @forelse($shifts as $shift)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $shift->employee->user->name ?? 'غير معروف' }}</td>
                    <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($shift->shift_date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ $shift->start_time }}</td>
                    <td class="px-4 py-2">{{ $shift->end_time }}</td>
                    <td class="px-4 py-2">{{ $shift->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($shifts ?? null),'links'))
            {{ $shifts->links() }}
        @endif
    </div>
</div>
@endsection

