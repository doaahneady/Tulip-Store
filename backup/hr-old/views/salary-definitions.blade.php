@extends('layouts.dashboard')

@section('title', 'تعريفات الرواتب')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">قائمة الموظفين</h3>
        <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة للوحة الموارد البشرية</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">الراتب الشهري</th>
                    <th class="px-4 py-2 text-left">الأجر بالساعة</th>
                    <th class="px-4 py-2 text-left">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $emp->user->name ?? ($emp->first_name.' '.$emp->last_name) }}</td>
                    <td class="px-4 py-2">{{ number_format($emp->monthly_salary ?? 0, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($emp->hourly_rate ?? 0, 2) }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('dashboard.hr.salary-definitions.update', $emp) }}" class="flex items-center gap-2">
                            @csrf
                            <input type="number" step="0.01" name="monthly_salary" class="form-input w-32" placeholder="شهري" value="{{ $emp->monthly_salary }}">
                            <input type="number" step="0.01" name="hourly_rate" class="form-input w-28" placeholder="ساعة" value="{{ $emp->hourly_rate }}">
                            <button class="btn btn-primary btn-sm">تحديث</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($employees ?? null),'links'))
            {{ $employees->links() }}
        @endif
    </div>
</div>
@endsection

