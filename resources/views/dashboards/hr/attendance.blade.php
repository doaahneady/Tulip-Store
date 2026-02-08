@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الحضور اليومي'; $subtitle = 'عرض الحضور وتسجيل الدخول والخروج'; @endphp
@php
    $formatTime = function ($t) {
        if (! $t) {
            return '-';
        }
        try {
            return \Carbon\Carbon::parse($t)->format('H:i');
        } catch (\Throwable $e) {
            return (string) $t;
        }
    };
    $formatDuration = function ($date, $in, $out) {
        if (! $in || ! $out) {
            return '-';
        }
        try {
            $start = \Carbon\Carbon::parse(\Carbon\Carbon::parse($date)->toDateString().' '.$in);
            $end = \Carbon\Carbon::parse(\Carbon\Carbon::parse($date)->toDateString().' '.$out);
            $mins = $start->diffInMinutes($end);
            $h = intdiv($mins, 60);
            $m = $mins % 60;
            return $h.':'.str_pad((string) $m, 2, '0', STR_PAD_LEFT);
        } catch (\Throwable $e) {
            return '-';
        }
    };
@endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <form method="GET" action="{{ route('dashboard.hr.attendance') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-sm text-gray-700 mb-1">التاريخ</label>
                <input type="date" name="date" value="{{ $date ?? now()->format('Y-m-d') }}" class="w-44 px-4 py-2 rounded-xl border border-gray-200">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-search"></i>
                <span>عرض</span>
            </button>
        </form>

        <div class="flex flex-wrap items-end gap-2">
            <form method="POST" action="{{ route('dashboard.hr.attendance.clock-in') }}" class="flex items-end gap-2">
                @csrf
                <input type="hidden" name="date" value="{{ $date ?? now()->format('Y-m-d') }}">
                <select name="employee_id" class="w-64 px-4 py-2 rounded-xl border border-gray-200">
                    @foreach(($employees ?? []) as $e)
                        <option value="{{ $e->id }}">{{ optional($e->user)->name ?? (($e->first_name ?? '').' '.($e->last_name ?? '')) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>تسجيل دخول</span>
                </button>
            </form>
            <form method="POST" action="{{ route('dashboard.hr.attendance.clock-out') }}" class="flex items-end gap-2">
                @csrf
                <input type="hidden" name="date" value="{{ $date ?? now()->format('Y-m-d') }}">
                <select name="employee_id" class="w-64 px-4 py-2 rounded-xl border border-gray-200">
                    @foreach(($employees ?? []) as $e)
                        <option value="{{ $e->id }}">{{ optional($e->user)->name ?? (($e->first_name ?? '').' '.($e->last_name ?? '')) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل خروج</span>
                </button>
            </form>
        </div>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجل الحضور</h3>
        <span class="text-xs text-gray-500">{{ $date }}</span>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الدخول</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الخروج</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المدة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($attendance as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($row->employee->user)->name ?? ('#'.$row->employee_id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $row->status }}</td>
                        <td class="px-4 py-3 text-sm">{{ $formatTime($row->check_in) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $formatTime($row->check_out) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $formatDuration($row->date, $row->check_in, $row->check_out) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $attendance->links() }}</div>
    </div>
@endsection
