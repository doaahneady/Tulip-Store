@extends('dashboards.layouts.app')
@section('content')
@php $title = 'حضور الموظفين'; $subtitle = 'متابعة الحضور والانصراف لليوم'; @endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.admin.index') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-black transition">
            <i class="fas fa-chart-pie"></i>
            <span>لوحة الإدارة</span>
        </a>
        <a href="{{ route('dashboard.admin.gifts') }}" class="inline-flex items-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-xl hover:bg-pink-700 transition">
            <i class="fas fa-gift"></i>
            <span>Tulip Gifts</span>
        </a>
        <a href="{{ route('dashboard.admin.mart.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.manage') }}" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.admin.roles') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-user-shield"></i>
            <span>Rules</span>
        </a>
    </div>
    <div class="text-sm text-gray-600 bg-white rounded-xl border border-gray-200 px-4 py-2">
        متواجدون الآن: <span class="font-bold text-gray-900">{{ number_format($checkedIn ?? 0) }}</span>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" action="{{ route('dashboard.admin.attendance') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                <input type="date" name="date" value="{{ request('date', $date ?? now()->toDateString()) }}" class="form-input w-44">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم / البريد / كود الموظف" class="form-input w-64">
                <button type="submit" class="btn btn-ghost btn-sm">
                    <i class="fas fa-filter"></i>
                    تصفية
                </button>
            </div>
            <a href="{{ route('dashboard.admin.attendance', array_diff_key(request()->query(), ['search' => true])) }}" class="text-sm text-indigo-600 hover:underline">
                إعادة ضبط البحث
            </a>
        </form>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>التاريخ</th>
                    <th>الحضور</th>
                    <th>الانصراف</th>
                    <th>الحالة</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td class="font-semibold text-gray-900">
                            <div>{{ $row->employee?->full_name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $row->employee?->employee_code ?? '-' }} • {{ $row->employee?->email ?? '' }}</div>
                        </td>
                        <td class="text-gray-700">{{ optional($row->date)->format('Y-m-d') }}</td>
                        <td class="text-gray-700">{{ $row->check_in ? optional($row->check_in)->format('H:i') : '-' }}</td>
                        <td class="text-gray-700">{{ $row->check_out ? optional($row->check_out)->format('H:i') : '-' }}</td>
                        <td>
                            @php
                                $status = $row->status ?? 'absent';
                                $badge = method_exists($row, 'getStatusColorAttribute') ? ($row->status_color ?? 'text-gray-600 bg-gray-100') : 'text-gray-600 bg-gray-100';
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $badge }}">{{ $status }}</span>
                        </td>
                        <td class="text-gray-600">{{ $row->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $rows->links() }}
    </div>
</div>
@endsection

