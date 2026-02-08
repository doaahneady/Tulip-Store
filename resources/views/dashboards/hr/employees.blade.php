@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الموظفون'; $subtitle = 'عرض الموظفين وإدارة بياناتهم'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('dashboard.hr.employees') }}" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث" class="form-input w-56">
        <select name="department" class="form-select w-44">
            <option value="">القسم</option>
            @foreach(($departments ?? []) as $d)
                <option value="{{ $d }}" @selected(request('department')==$d)>{{ $d }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select w-40">
            <option value="">الحالة</option>
            <option value="active" @selected(request('status')=='active')>نشط</option>
            <option value="inactive" @selected(request('status')=='inactive')>غير نشط</option>
            <option value="on_leave" @selected(request('status')=='on_leave')>على إجازة</option>
            <option value="terminated" @selected(request('status')=='terminated')>منتهي العقد</option>
        </select>
        <select name="employment_type" class="form-select w-44">
            <option value="">نوع التوظيف</option>
            <option value="full_time" @selected(request('employment_type')=='full_time')>دوام كامل</option>
            <option value="part_time" @selected(request('employment_type')=='part_time')>دوام جزئي</option>
            <option value="contract" @selected(request('employment_type')=='contract')>عقد</option>
            <option value="intern" @selected(request('employment_type')=='intern')>متدرب</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">تصفية</button>
        <a href="{{ route('dashboard.hr.employees.create') }}" class="btn btn-primary btn-sm">إضافة موظف</a>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">قائمة الموظفين</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">القسم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المنصب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($employees as $emp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($emp->user)->name ?? ('#'.$emp->id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $emp->department }}</td>
                        <td class="px-4 py-3 text-sm">{{ $emp->position }}</td>
                        <td class="px-4 py-3 text-sm">{{ $emp->status }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('dashboard.hr.employees.edit', $emp) }}" class="btn btn-secondary btn-xs">تعديل</a>
                                <form method="POST" action="{{ route('dashboard.hr.employees.delete', $emp) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $employees->links() }}</div>
    </div>
@endsection
