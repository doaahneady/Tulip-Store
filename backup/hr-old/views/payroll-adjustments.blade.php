@extends('layouts.dashboard')

@section('title', 'تعديلات الرواتب')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">سجل التعديلات</h3>
            <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="px-4 py-2 text-left">الموظف</th>
                        <th class="px-4 py-2 text-left">الفترة</th>
                        <th class="px-4 py-2 text-left">النوع</th>
                        <th class="px-4 py-2 text-left">القيمة</th>
                        <th class="px-4 py-2 text-left">السبب</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adj)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $adj->employee->user->name ?? 'غير معروف' }}</td>
                        <td class="px-4 py-2">{{ $adj->pay_period }}</td>
                        <td class="px-4 py-2">{{ $adj->type }}</td>
                        <td class="px-4 py-2">{{ number_format($adj->amount, 2) }}</td>
                        <td class="px-4 py-2">{{ $adj->reason ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            @if(method_exists(($adjustments ?? null),'links'))
                {{ $adjustments->links() }}
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">إضافة تعديل</h3>
        <form method="POST" action="{{ route('dashboard.hr.payroll-adjustments.create') }}" class="space-y-3">
            @csrf
            <select name="employee_id" class="form-select w-full">
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->user->name ?? ($emp->first_name.' '.$emp->last_name) }}</option>
                @endforeach
            </select>
            <input type="text" name="pay_period" class="form-input w-full" placeholder="YYYY-MM">
            <select name="type" class="form-select w-full">
                <option value="bonus">مكافأة</option>
                <option value="overtime_bonus">إضافي</option>
                <option value="deduction">خصم</option>
                <option value="penalty">عقوبة</option>
            </select>
            <input type="number" step="0.01" name="amount" class="form-input w-full" placeholder="القيمة">
            <input type="text" name="reason" class="form-input w-full" placeholder="سبب (اختياري)">
            <button class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg">حفظ</button>
        </form>
    </div>
</div>
@endsection

