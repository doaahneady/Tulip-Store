@extends('dashboards.layouts.app')
@section('content')
@php $title = 'طلبات الإجازة'; $subtitle = 'إدارة طلبات الإجازة والموافقة عليها'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="POST" action="{{ route('dashboard.hr.leave.submit') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        @csrf
        <input type="number" name="employee_id" placeholder="رقم الموظف" class="form-input" required>
        <select name="leave_type" class="form-select" required>
            <option value="">نوع الإجازة</option>
            <option value="annual">سنوية</option>
            <option value="sick">مرضية</option>
            <option value="personal">شخصية</option>
            <option value="emergency">طارئة</option>
            <option value="maternity">أمومة</option>
            <option value="paternity">أبوة</option>
        </select>
        <input type="date" name="start_date" class="form-input" required>
        <input type="date" name="end_date" class="form-input" required>
        <input type="text" name="reason" placeholder="السبب" class="form-input" required>
        <div class="md:col-span-5">
            <button type="submit" class="btn btn-primary btn-sm">إرسال طلب</button>
        </div>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">قائمة الطلبات</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">النوع</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">من</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إلى</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إجراء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leaveRequests as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($req->employee->user)->name ?? ('#'.$req->employee_id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $req->leave_type }}</td>
                        <td class="px-4 py-3 text-sm">{{ optional($req->start_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-sm">{{ optional($req->end_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $req->status }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('dashboard.hr.leave.approve', $req) }}">@csrf
                                    <button class="btn btn-secondary btn-xs">موافقة</button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.hr.leave.reject', $req) }}">@csrf
                                    <button class="btn btn-ghost btn-xs">رفض</button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.hr.leave.cancel', $req) }}">@csrf
                                    <button class="btn btn-ghost btn-xs">إلغاء</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $leaveRequests->links() }}</div>
    </div>
@endsection

