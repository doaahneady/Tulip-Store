@extends('layouts.dashboard')

@section('title', 'إدارة الموظفين')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-users text-indigo-600"></i>
            <span class="text-sm text-gray-700">فلاتر سريعة</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.employees') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-input w-56" placeholder="الاسم أو البريد أو كود الموظف">
            <select name="department" class="form-select w-40">
                <option value="">القسم</option>
                @foreach($departments as $dep)
                    <option value="{{ $dep }}" @selected(request('department')==$dep)>{{ $dep }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select w-36">
                <option value="">الحالة</option>
                <option value="active" @selected(request('status')=='active')>نشط</option>
                <option value="inactive" @selected(request('status')=='inactive')>غير نشط</option>
            </select>
            <select name="employment_type" class="form-select w-40">
                <option value="">نوع التوظيف</option>
                <option value="full_time" @selected(request('employment_type')=='full_time')>دوام كامل</option>
                <option value="part_time" @selected(request('employment_type')=='part_time')>دوام جزئي</option>
                <option value="contract" @selected(request('employment_type')=='contract')>عقد</option>
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                تطبيق
            </button>
        </form>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">قائمة الموظفين</h3>
        <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة للوحة الموارد البشرية</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الاسم</th>
                    <th class="px-4 py-2 text-left">البريد</th>
                    <th class="px-4 py-2 text-left">القسم</th>
                    <th class="px-4 py-2 text-left">الوظيفة</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">تاريخ التعيين</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $emp->user->name ?? ($emp->first_name.' '.$emp->last_name) }}</td>
                        <td class="px-4 py-2">{{ $emp->user->email ?? $emp->email }}</td>
                        <td class="px-4 py-2">{{ $emp->department ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $emp->position ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $emp->status==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $emp->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ optional($emp->hire_date)->format('Y-m-d') ?? ($emp->hire_date ?? '-') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
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
