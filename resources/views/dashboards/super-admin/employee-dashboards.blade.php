@extends('dashboards.layouts.app')
@section('content')
@php $title = 'صلاحيات لوحات التحكم'; $subtitle = 'تحديد اللوحات المسموح بها للموظف'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">صلاحيات لوحات التحكم</h3>
            <div class="text-sm text-gray-600">{{ $employee->full_name }} · {{ $employee->email }}</div>
        </div>
        <a href="{{ route('dashboard.admin.employees') }}" class="btn btn-ghost btn-sm">رجوع</a>
    </div>

    <form method="POST" action="{{ route('dashboard.admin.employees.dashboards.update', $employee) }}" class="p-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($definitions as $key => $label)
                <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <input type="checkbox" name="dashboards[]" value="{{ $key }}" class="form-checkbox" @checked(in_array($key, $selected ?? [], true))>
                    <div class="text-sm font-semibold text-gray-900">{{ $label }}</div>
                </label>
            @endforeach
        </div>

        <div class="mt-4 flex items-center justify-end gap-2">
            <button type="submit" class="btn btn-secondary btn-sm">حفظ</button>
        </div>
    </form>
</div>
@endsection

