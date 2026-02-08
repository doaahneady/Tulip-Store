@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إدارة الموافقات الإدارية'; $subtitle = 'اعتماد/رفض الطلبات (HR أو Admin)'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900">الطلبات المعلقة</h3>
            <p class="text-sm text-gray-500">{{ number_format($requests->total()) }} طلب</p>
        </div>
        <form method="GET" action="{{ route('dashboard.administrative-approvals.manage') }}" class="flex flex-wrap items-center gap-2">
            <select name="category" class="px-4 py-2 rounded-xl border border-gray-200">
                <option value="">كل الأنواع</option>
                <option value="money" @selected(request('category') === 'money')>مبلغ</option>
                <option value="day_off" @selected(request('category') === 'day_off')>إجازة</option>
                <option value="other" @selected(request('category') === 'other')>أخرى</option>
            </select>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تصفية</span>
            </button>
            <a href="{{ route('dashboard.administrative-approvals.manage') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
            <a href="{{ route('dashboard.administrative-approvals.index') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
                <i class="fas fa-file-signature"></i>
                <span>طلباتي</span>
            </a>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">الموظف</th>
                    <th class="text-right py-2">النوع</th>
                    <th class="text-right py-2">المبلغ</th>
                    <th class="text-right py-2">الفترة</th>
                    <th class="text-right py-2">التفاصيل</th>
                    <th class="text-right py-2">التاريخ</th>
                    <th class="text-right py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $r)
                    @php
                        $categoryName = match ($r->category) {
                            'money' => 'مبلغ',
                            'day_off' => 'إجازة',
                            default => 'أخرى',
                        };
                    @endphp
                    <tr class="border-t border-gray-100 align-top">
                        <td class="py-3">
                            <div class="font-semibold text-gray-900">{{ $r->requester?->full_name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $r->requester?->email ?? '' }}</div>
                        </td>
                        <td class="py-3 text-gray-700">{{ $categoryName }}</td>
                        <td class="py-3 text-gray-700">{{ $r->amount !== null ? number_format((float) $r->amount, 2) : '-' }}</td>
                        <td class="py-3 text-gray-700">
                            @if($r->start_date || $r->end_date)
                                {{ optional($r->start_date)->format('Y-m-d') ?? '-' }} → {{ optional($r->end_date)->format('Y-m-d') ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-3 text-gray-700">{{ \Illuminate\Support\Str::limit($r->details, 80) }}</td>
                        <td class="py-3 text-gray-700">{{ optional($r->created_at)->format('Y-m-d') }}</td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('dashboard.administrative-approvals.approve', $r) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-3 py-2 rounded-xl hover:bg-emerald-700 transition">
                                        <i class="fas fa-check"></i>
                                        <span>موافقة</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.administrative-approvals.reject', $r) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-3 py-2 rounded-xl hover:bg-red-700 transition">
                                        <i class="fas fa-times"></i>
                                        <span>رفض</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-gray-500">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>
</div>
@endsection
