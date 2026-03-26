@extends('dashboards.layouts.app')
@section('content')
@php
    $title = 'تسليمات السائقين';
    $subtitle = 'إدارة طلبات التسليم وإعتماد الدفع للمالية';
    $driverFilterText = $driverUserIdFilter !== null ? (' (Driver #'.$driverUserIdFilter.')') : '';
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.finance.index') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
            <i class="fas fa-arrow-right"></i>
            <span>لوحة المالية</span>
        </a>
    </div>
    <div class="text-sm font-semibold text-gray-800">
        {{ $subtitle }}{{ $driverFilterText }}
    </div>
</div>

@if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm">
        {{ $errors->first() }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-truck"></i>
                التسليمات بانتظار اعتماد المالية
            </h3>
            <p class="text-sm text-gray-600 mt-1">
                تظهر هنا الطلبات التي أصبحت <b>delivered</b> بانتظار تحويلها إلى <b>done</b> وتحديث حالة الدفع.
            </p>
        </div>
        <div class="text-sm text-gray-600">
            {{ $pendingDrivers->count() }} سائق
        </div>
    </div>
</div>

<div class="space-y-6 mb-10">
    @forelse($pendingDrivers as $d)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <div class="text-lg font-black text-gray-900">
                        {{ $d->driverName }}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        عدد الطلبات: <span class="font-semibold">{{ $d->orders->count() }}</span>
                    </div>
                    <div class="text-sm mt-2">
                        <span class="text-gray-600">المبلغ المطلوب (كاش):</span>
                        <span class="text-emerald-700 font-bold">
                            {{ number_format((float) ($d->cashDue ?? 0), 2) }} USD
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('dashboard.finance.driver-deliveries.complete', $d->driverUserId) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
                        <i class="fas fa-check-double"></i>
                        <span>Complete</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto mt-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="px-3 py-2 text-right">رقم الطلب</th>
                            <th class="px-3 py-2 text-right">العميل</th>
                            <th class="px-3 py-2 text-right">الدفع</th>
                            <th class="px-3 py-2 text-right">المبلغ</th>
                            <th class="px-3 py-2 text-right">تاريخ التسليم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($d->orders as $o)
                            <tr class="border-t border-gray-100">
                                <td class="px-3 py-3 text-gray-900 font-semibold">
                                    {{ $o->order_number }}
                                </td>
                                <td class="px-3 py-3 text-gray-700">
                                    {{ $o->recipient_name ?? '-' }}
                                </td>
                                <td class="px-3 py-3 text-gray-700">
                                    {{ $o->payment_method ?? '-' }}<br>
                                    <span class="text-xs text-gray-500">{{ $o->payment_status ?? '-' }}</span>
                                </td>
                                <td class="px-3 py-3 text-gray-900 font-semibold">
                                    {{ number_format((float) ($o->total ?? 0), 2) }} USD
                                </td>
                                <td class="px-3 py-3 text-gray-600">
                                    {{ $o->delivered_at ? $o->delivered_at->format('Y-m-d H:i') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
            <i class="fas fa-inbox text-4xl opacity-40 mb-3"></i>
            <div class="font-semibold">لا توجد تسليمات بانتظار المالية</div>
            <div class="text-sm mt-1">إذا تم استلام طلبات من السائق، ستظهر هنا.</div>
        </div>
    @endforelse
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-history"></i>
                التاريخ
            </h3>
            <p class="text-sm text-gray-600 mt-1">
                آخر الطلبات التي تم اعتمادها من قبل المالية (delivered -> done + payment_status paid).
            </p>
        </div>
        <div class="text-sm text-gray-600">
            {{ $historyOrders->count() }} سجل
        </div>
    </div>

    <form method="GET" action="{{ route('dashboard.finance.driver-deliveries.index') }}" class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        @if($driverUserIdFilter !== null)
            <input type="hidden" name="driver_id" value="{{ $driverUserIdFilter }}">
        @endif

        <input
            type="text"
            name="driver_name"
            value="{{ old('driver_name', $historyDriverNameFilter ?? '') }}"
            class="border rounded-xl px-4 py-2"
            placeholder="اسم السائق أو اليوزر"
        >

        <input
            type="text"
            name="date_from"
            value="{{ old('date_from', $historyDateFrom ?? '') }}"
            class="border rounded-xl px-4 py-2"
            placeholder="من تاريخ (YYYY-MM-DD)"
            inputmode="numeric"
            maxlength="10"
            pattern="\d{4}-\d{2}-\d{2}"
            oninput="this.value=this.value.replace(/[^0-9-]/g,'').slice(0,10)"
        >

        <input
            type="text"
            name="date_to"
            value="{{ old('date_to', $historyDateTo ?? '') }}"
            class="border rounded-xl px-4 py-2"
            placeholder="إلى تاريخ (YYYY-MM-DD)"
            inputmode="numeric"
            maxlength="10"
            pattern="\d{4}-\d{2}-\d{2}"
            oninput="this.value=this.value.replace(/[^0-9-]/g,'').slice(0,10)"
        >

        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">تصفية</button>
            <a href="{{ route('dashboard.finance.driver-deliveries.index', $driverUserIdFilter ? ['driver_id' => $driverUserIdFilter] : []) }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700">إعادة ضبط</a>
        </div>
    </form>

    <div class="overflow-x-auto mt-5">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="px-3 py-2 text-right">السائق</th>
                    <th class="px-3 py-2 text-right">رقم الطلب</th>
                    <th class="px-3 py-2 text-right">العميل</th>
                    <th class="px-3 py-2 text-right">المبلغ</th>
                    <th class="px-3 py-2 text-right">تاريخ التسليم</th>
                    <th class="px-3 py-2 text-right">تاريخ الإعتماد</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historyOrders as $o)
                    <tr class="border-t border-gray-100">
                        <td class="px-3 py-3 text-gray-700">{{ $o->driver_name ?? '-' }}</td>
                        <td class="px-3 py-3 text-gray-900 font-semibold">{{ $o->order_number ?? ('#'.$o->id) }}</td>
                        <td class="px-3 py-3 text-gray-700">{{ $o->recipient_name ?? '-' }}</td>
                        <td class="px-3 py-3 text-gray-900 font-semibold">{{ number_format((float) ($o->total_amount ?? $o->total ?? 0), 2) }} USD</td>
                        <td class="px-3 py-3 text-gray-600">{{ $o->delivered_at ? $o->delivered_at->format('Y-m-d H:i') : '-' }}</td>
                        <td class="px-3 py-3 text-gray-600">
                            {{
                                ($o->completed_at_display ?? null)
                                    ? $o->completed_at_display->format('Y-m-d H:i')
                                    : ($o->updated_at ? $o->updated_at->format('Y-m-d H:i') : '-')
                            }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-10 text-center text-gray-500">لا يوجد سجل</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

