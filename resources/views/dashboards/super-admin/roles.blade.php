@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الأدوار والصلاحيات'; $subtitle = 'صلاحيات مختلطة (Role + Employee Override)'; @endphp

<style>
    .roles-permissions-page {
        color: #111827 !important;
        background: transparent !important;
    }
    .roles-permissions-page * {
        color: #111827 !important;
    }
    .roles-permissions-page .rp-card {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
    }
    .roles-permissions-page .bg-white,
    .roles-permissions-page .bg-gray-50,
    .roles-permissions-page .bg-indigo-50 {
        background: #ffffff !important;
    }
    .roles-permissions-page .border,
    .roles-permissions-page .border-gray-100,
    .roles-permissions-page .border-gray-200,
    .roles-permissions-page .border-indigo-100,
    .roles-permissions-page .border-slate-700 {
        border-color: #e5e7eb !important;
    }
    .roles-permissions-page .rp-muted { color: #6b7280 !important; }
    .roles-permissions-page .rp-title { color: #111827 !important; font-weight: 800; }
    .roles-permissions-page .rp-label { color: #374151 !important; font-weight: 600; }
    .roles-permissions-page .rp-section {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px;
    }
    .roles-permissions-page .rp-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        padding: 2px 10px;
        font-size: 12px;
        font-weight: 700;
    }
    .roles-permissions-page .rp-chip-inherited {
        background: #dcfce7;
        color: #166534 !important;
    }
    .roles-permissions-page .rp-chip-custom {
        background: #fef3c7;
        color: #92400e !important;
    }
    .roles-permissions-page input[type="checkbox"] {
        accent-color: #2563eb;
    }
    .roles-permissions-page .rp-grid-headers {
        display: grid;
        grid-template-columns: minmax(260px, 1.2fr) 130px 1fr 1fr 130px 120px 90px;
        gap: 10px;
        font-size: 12px;
        color: #64748b !important;
        font-weight: 800;
        padding: 0 8px;
    }
    .roles-permissions-page .rp-grid-row {
        display: grid;
        grid-template-columns: minmax(260px, 1.2fr) 130px 1fr 1fr 130px 120px 90px;
        gap: 10px;
        align-items: center;
    }
    @media (max-width: 1280px) {
        .roles-permissions-page .rp-grid-headers,
        .roles-permissions-page .rp-grid-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }
</style>

@php
    $roleOrder = ['admin','it','hr','cs','finance','supervisor','driver','vendor'];
    $employeeRows = ($employees ?? null) instanceof \Illuminate\Contracts\Pagination\Paginator
        ? collect(($employees ?? null)->items())
        : collect($employees ?? []);
    $resolveRole = function($emp) {
        if ($emp->is_admin) return 'admin';
        if ($emp->is_it) return 'it';
        if ($emp->is_hr) return 'hr';
        if ($emp->is_cs) return 'cs';
        if ($emp->is_finance) return 'finance';
        if ($emp->is_driver_supervisor) return 'supervisor';
        if ($emp->is_trader) return 'vendor';
        return 'staff';
    };
    $grouped = $employeeRows->groupBy(fn($e) => $resolveRole($e));
@endphp

<div class="roles-permissions-page space-y-5">
<div class="rp-card p-5">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h3 class="text-lg rp-title">Permission Matrix</h3>
            <p class="text-sm rp-muted">واجهة مبسطة: Role Template + Employee Overrides لكل Dashboard</p>
        </div>
        <form method="GET" action="{{ route('dashboard.admin.roles') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم/البريد/الكود" class="form-input w-72">
            <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-search"></i> بحث</button>
        </form>
    </div>
</div>

@foreach(($dashboardCatalog ?? []) as $dashboardKey => $cfg)
    <div class="rp-card p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-base rp-title">{{ $cfg['title'] }}</h3>
                <p class="text-xs rp-muted">الأعمدة: View/Edit | Sections | Actions | Sensitive | Mode</p>
            </div>
    </div>

        <div class="space-y-3">
            <div class="rp-grid-headers">
                <div>Employee (Grouped by Role)</div>
                <div>View/Edit</div>
                <div>Sections Access</div>
                <div>Allowed Actions</div>
                <div>Sensitive</div>
                <div>Mode</div>
                <div>Save</div>
            </div>
            @foreach($roleOrder as $rk)
                @php $rows = $grouped->get($rk, collect()); @endphp
                @if($rows->isNotEmpty())
                    <div class="rp-label text-sm px-2">Role: {{ strtoupper($rk) }}</div>
                @endif
                @foreach($rows as $emp)
                    @php
                        $ov = $employeeOverrideMap[$emp->id][$dashboardKey] ?? null;
                        $isOverride = (bool)($ov->is_override ?? false);
                        $formId = 'ov-'.$dashboardKey.'-'.$emp->id;
                        $resolved = $resolvedPermissionMap[$emp->id][$dashboardKey] ?? ['can_view' => false, 'can_edit' => false, 'sections' => [], 'actions' => [], 'can_view_sensitive' => false];
                        $checkedView = $isOverride ? (bool)($ov->can_view ?? false) : (bool)($resolved['can_view'] ?? false);
                        $checkedEdit = $isOverride ? (bool)($ov->can_edit ?? false) : (bool)($resolved['can_edit'] ?? false);
                        $checkedSensitive = $isOverride ? (bool)($ov->can_view_sensitive ?? false) : (bool)($resolved['can_view_sensitive'] ?? false);
                        $checkedSections = $isOverride ? (array)($ov->sections ?? []) : (array)($resolved['sections'] ?? []);
                        $checkedActions = $isOverride ? (array)($ov->actions ?? []) : (array)($resolved['actions'] ?? []);
                    @endphp
                    <div class="rp-grid-row rp-section p-3">
                        <div>
                            <div class="rp-title text-sm">{{ $emp->full_name }}</div>
                            <div class="text-xs rp-muted">{{ $emp->employee_code ?? '-' }} • {{ $emp->email }}</div>
                        </div>
                        <div class="text-xs space-x-2">
                            <label class="inline-flex items-center gap-1">
                                <input form="{{ $formId }}" type="hidden" name="can_view" value="0">
                                <input form="{{ $formId }}" type="checkbox" name="can_view" value="1" @checked($checkedView)>View
                            </label>
                            <label class="inline-flex items-center gap-1">
                                <input form="{{ $formId }}" type="hidden" name="can_edit" value="0">
                                <input form="{{ $formId }}" type="checkbox" name="can_edit" value="1" @checked($checkedEdit)>Edit
                                    </label>
                        </div>
                        <div class="text-xs">
                            @foreach(($cfg['sections'] ?? []) as $sec)
                                <label class="inline-flex items-center gap-1 mr-2"><input form="{{ $formId }}" type="checkbox" name="sections[]" value="{{ $sec }}" @checked(in_array($sec, $checkedSections, true))>{{ $sec }}</label>
                            @endforeach
                        </div>
                        <div class="text-xs">
                            @foreach(($cfg['actions'] ?? []) as $ac)
                                <label class="inline-flex items-center gap-1 mr-2"><input form="{{ $formId }}" type="checkbox" name="actions[]" value="{{ $ac }}" @checked(in_array($ac, $checkedActions, true))>{{ $ac }}</label>
                                @endforeach
                            </div>
                        <div class="text-xs">
                            <label class="inline-flex items-center gap-1">
                                <input form="{{ $formId }}" type="hidden" name="can_view_sensitive" value="0">
                                <input form="{{ $formId }}" type="checkbox" name="can_view_sensitive" value="1" @checked($checkedSensitive)>Sensitive
                            </label>
                        </div>
                        <div class="text-xs">
                            <span class="rp-label">Employee Override</span>
                            @if($isOverride)
                                <span class="rp-chip rp-chip-custom">Custom</span>
                            @else
                                <span class="rp-chip rp-chip-inherited">Inherited</span>
                            @endif
                        </div>
                        <div>
                            <form id="{{ $formId }}" method="POST" action="{{ route('dashboard.admin.roles.employees.update', $emp) }}">
                                @csrf
                                <input type="hidden" name="dashboard_key" value="{{ $dashboardKey }}">
                                <input type="hidden" name="is_override" value="1">
                            </form>
                            <button form="{{ $formId }}" class="btn btn-secondary btn-xs" type="submit">Save</button>
    </div>
</div>
                @endforeach
                @endforeach
            </div>
        </div>
    @endforeach

<div class="rp-card p-5">
    <h3 class="text-base font-black text-gray-900 mb-3">View As Employee (Preview)</h3>
    <form method="POST" action="{{ route('dashboard.admin.roles.preview') }}" class="flex flex-wrap items-center gap-2">
        @csrf
        <select name="employee_id" class="form-select w-64" required>
            <option value="">Select employee</option>
            @foreach(($employeeRows ?? collect()) as $emp)
                <option value="{{ $emp->id }}" @selected((int)request('preview_employee') === (int)$emp->id)>{{ $emp->full_name }} ({{ $emp->email }})</option>
            @endforeach
        </select>
        <select name="dashboard_key" class="form-select w-48" required>
            @foreach(array_keys($dashboardCatalog ?? []) as $dk)
                <option value="{{ $dk }}" @selected((string)request('preview_dashboard') === (string)$dk)>{{ strtoupper($dk) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary btn-sm" type="submit">Preview</button>
    </form>
    @if(!empty($preview))
        <div class="mt-3 p-3 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-700">
            <div class="font-semibold mb-1">Resolved permissions for {{ $preview['employee']->full_name }} / {{ strtoupper($preview['dashboard_key']) }}</div>
            <pre class="whitespace-pre-wrap">{{ json_encode($preview['resolved'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif
</div>

@if(method_exists(($employees ?? null), 'links'))
    <div class="pt-2">
        {{ $employees->links() }}
    </div>
@endif
</div>
@endsection
