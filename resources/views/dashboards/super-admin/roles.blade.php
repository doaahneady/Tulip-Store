@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الأدوار والصلاحيات'; $subtitle = 'إدارة RBAC والمصفوفة'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h3 class="text-lg font-bold text-gray-800">صلاحيات الموظفين (لوحات التحكم)</h3>
        <form method="GET" action="{{ route('dashboard.admin.roles') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو البريد أو كود الموظف" class="form-input w-64">
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-search"></i>
                بحث
            </button>
        </form>
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الموظف</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">البريد</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">اللوحات</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">حفظ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($employees ?? []) as $emp)
                    @php
                        $explicit = method_exists($emp, 'getExplicitDashboardKeys') ? $emp->getExplicitDashboardKeys() : [];
                        $selected = $explicit;
                        if (in_array('__none__', $selected, true)) {
                            $selected = [];
                        }
                        if (empty($selected) && method_exists($emp, 'getAllowedDashboardKeys')) {
                            $selected = $emp->getAllowedDashboardKeys();
                        }
                    @endphp
                    @php $formId = 'emp-rules-'.$emp->id; @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            <div class="font-semibold">{{ $emp->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $emp->employee_code ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $emp->email }}</td>
                        <td class="px-4 py-3">
                            @php
                                $options = [
                                    'admin' => 'Admin',
                                    'it' => 'IT',
                                    'hr' => 'HR',
                                    'cs' => 'CS',
                                    'finance' => 'Finance',
                                    'supervisor' => 'Supervisor',
                                    'vendor' => 'Trader',
                                ];
                            @endphp
                            <div class="flex flex-wrap items-center gap-3">
                                @foreach($options as $key => $label)
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input form="{{ $formId }}" type="checkbox" name="dashboards[]" value="{{ $key }}" class="form-checkbox"
                                            @checked(in_array($key, $selected, true))>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                            <form id="{{ $formId }}" method="POST" action="{{ route('dashboard.admin.roles.employees.update', $emp) }}" class="inline-flex items-center gap-2">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-xs">حفظ</button>
                                <a href="{{ route('dashboard.admin.employees.dashboards.edit', $emp) }}" class="text-indigo-600 hover:underline text-sm">تفاصيل</a>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        @if(method_exists(($employees ?? null), 'links'))
            {{ $employees->links() }}
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">الأدوار</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الاسم</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الوصف</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الصلاحيات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($roles as $role)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $role->display_name ?? $role->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $role->description }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        @foreach(($role->permissions ?? []) as $perm)
                            <span class="inline-block px-2 py-1 text-xs rounded bg-gray-100 text-gray-700 mr-1 mb-1">
                                {{ $perm->display_name ?? $perm->name }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">مصفوفة الصلاحيات حسب الفئات</h3>
    @foreach(($permissions ?? collect())->toArray() as $category => $perms)
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-2">{{ is_string($category) ? $category : 'عام' }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach(($perms ?? []) as $perm)
                    <span class="px-3 py-1 text-xs rounded bg-indigo-50 text-indigo-700">
                        {{ is_array($perm) ? ($perm['display_name'] ?? $perm['name'] ?? 'غير معروف') : ($perm->display_name ?? $perm->name) }}
                    </span>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
