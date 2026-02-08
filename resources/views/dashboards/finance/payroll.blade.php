@extends('dashboards.layouts.app')
@section('content')
@php $title = 'رواتب المالية'; $subtitle = 'موافقة وصرف الرواتب المرسلة من HR'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('dashboard.finance.payroll') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <select name="pay_period" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <option value="">الفترة (الكل)</option>
            @foreach(($payPeriods ?? []) as $p)
                <option value="{{ $p }}" @selected((string) request('pay_period') === (string) $p)>{{ $p }}</option>
            @endforeach
        </select>
        <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            <option value="">الحالة (الكل)</option>
            @foreach(['pending_approval','approved','completed','rejected'] as $s)
                <option value="{{ $s }}" @selected((string) request('status') === (string) $s)>{{ $s }}</option>
            @endforeach
        </select>
        <button class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-filter"></i>
            <span>تصفية</span>
        </button>
        <a href="{{ route('dashboard.finance.payroll') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">طلبات الرواتب</h3>
        <span class="text-sm text-gray-500">{{ number_format($transactions->total()) }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">الموظف</th>
                    <th class="text-right py-2">الفترة</th>
                    <th class="text-right py-2">الصافي</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">تفاصيل</th>
                    <th class="text-right py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    @php
                        $employeeId = (int) (data_get($tx->metadata, 'employee_id') ?? 0);
                        $payPeriod = data_get($tx->metadata, 'pay_period');
                        $prId = (int) (data_get($tx->metadata, 'payroll_record_id') ?? 0);
                        $emp = $employees[$employeeId] ?? null;
                        $pr = $payrollRecords[$prId] ?? null;
                        $empName = $emp?->user?->name ?? trim(($emp?->first_name ?? '').' '.($emp?->last_name ?? '')) ?? ('#'.$employeeId);
                    @endphp
                    <tr class="border-t border-gray-100 align-top">
                        <td class="py-3">
                            <div class="font-semibold text-gray-900">{{ $empName }}</div>
                            <div class="text-xs text-gray-500">{{ $emp?->user?->email ?? '' }}</div>
                        </td>
                        <td class="py-3 text-gray-700">{{ $payPeriod ?? '-' }}</td>
                        <td class="py-3 text-gray-900 font-semibold">{{ number_format((float) ($tx->amount ?? 0), 2) }} {{ $tx->currency ?? 'USD' }}</td>
                        <td class="py-3 text-gray-700">{{ $tx->status }}</td>
                        <td class="py-3 text-xs text-gray-500">
                            <div>TX: {{ $tx->transaction_id }}</div>
                            <div>PR: {{ $prId ?: '-' }}</div>
                            <div>HR Net: {{ $pr ? number_format((float) ($pr->net_pay ?? 0), 2) : '-' }}</div>
                        </td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                @if($tx->status === 'pending_approval')
                                    <form method="POST" action="{{ route('dashboard.finance.approvals.transactions.approve', $tx) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-3 py-2 rounded-xl hover:bg-emerald-700 transition">
                                            <i class="fas fa-check"></i>
                                            <span>موافقة</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.finance.approvals.transactions.reject', $tx) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-3 py-2 rounded-xl hover:bg-red-700 transition">
                                            <i class="fas fa-times"></i>
                                            <span>رفض</span>
                                        </button>
                                    </form>
                                @elseif($tx->status === 'approved')
                                    <a href="{{ route('dashboard.finance.payroll.pay', $tx) }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-3 py-2 rounded-xl hover:bg-indigo-700 transition">
                                        <i class="fas fa-hand-holding-dollar"></i>
                                        <span>صرف + توقيع</span>
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
