@extends('dashboards.layouts.app')
@section('content')
@php($title = 'Payrolls')
@php($subtitle = 'عرض كشوف الرواتب')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.cs.index') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-arrow-right"></i>
            <span>رجوع</span>
        </a>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-2">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="بحث بالاسم / الإيميل"
            class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm"
        />
        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm">
            <option value="">كل الحالات</option>
            @foreach($statusOptions as $opt)
                <option value="{{ $opt }}" @selected(request('status')===$opt)>{{ $opt }}</option>
            @endforeach
        </select>
        <button class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition" type="submit">
            <i class="fas fa-filter"></i>
            <span>تصفية</span>
        </button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="text-right px-4 py-3 font-bold">الموظف</th>
                    <th class="text-right px-4 py-3 font-bold">الفترة</th>
                    <th class="text-right px-4 py-3 font-bold">الصافي</th>
                    <th class="text-right px-4 py-3 font-bold">الحالة</th>
                    <th class="text-right px-4 py-3 font-bold">تاريخ المعالجة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payrolls as $p)
                    @php
                        $emp = $p->employee;
                        $name = $emp?->user?->name
                            ?? trim(($emp?->first_name ?? '').' '.($emp?->last_name ?? ''))
                            ?: ($emp?->email ?? ('Employee #'.$p->employee_id));
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">{{ $name }}</div>
                            @if($emp?->email)
                                <div class="text-xs text-gray-500">{{ $emp->email }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-800">{{ $p->formatted_period ?? $p->pay_period }}</td>
                        <td class="px-4 py-3 font-bold text-gray-900">{{ $p->formatted_net_pay ?? ('$'.number_format((float) $p->net_pay, 2)) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold {{ $p->status_color ?? 'text-gray-600 bg-gray-100' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $p->processed_at?->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                            لا توجد كشوف رواتب.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $payrolls->links() }}
</div>

@endsection

