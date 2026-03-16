@extends('dashboards.layouts.app')
@section('content')
@php
    $title = 'الفواتير';
    $subtitle = 'عرض فواتير الطلبات وتحميلها';
@endphp

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
            placeholder="بحث برقم الطلب / اسم العميل / المتجر"
            class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm"
        />
        <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm">
            <option value="">كل الحالات</option>
            @forelse(($statusOptions ?? []) as $opt)
                <option value="{{ $opt }}" @selected(request('status') === $opt)>{{ $opt }}</option>
            @empty
            @endforelse
        </select>
        <select name="payment_status" class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm">
            <option value="">كل حالات الدفع</option>
            @forelse(($paymentOptions ?? []) as $opt)
                <option value="{{ $opt }}" @selected(request('payment_status') === $opt)>{{ $opt }}</option>
            @empty
            @endforelse
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm" />
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm" />
        <button class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition" type="submit">
            <i class="fas fa-filter"></i>
            <span>تصفية</span>
        </button>
        <a href="{{ route('dashboard.cs.payrolls') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm">مسح</a>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="text-right px-4 py-3 font-bold">رقم الطلب</th>
                    <th class="text-right px-4 py-3 font-bold">العميل</th>
                    <th class="text-right px-4 py-3 font-bold">المتجر</th>
                    <th class="text-right px-4 py-3 font-bold">الإجمالي</th>
                    <th class="text-right px-4 py-3 font-bold">الحالة</th>
                    <th class="text-right px-4 py-3 font-bold">الدفع</th>
                    <th class="text-right px-4 py-3 font-bold">التاريخ</th>
                    <th class="text-right px-4 py-3 font-bold">تحميل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($orders ?? []) as $o)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $o->order_number ?? $o->id }}</td>
                        <td class="px-4 py-3 text-gray-900">
                            <div class="font-semibold">
                                {{ $o->recipient_name ?? ($o->user?->name ?? '-') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $o->phone ?? ($o->user?->email ?? '-') }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            @php $storeName = data_get($o->store, 'name'); @endphp
                            {{ is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : ($storeName ?: '-') }}
                        </td>
                        <td class="px-4 py-3 font-bold text-gray-900">
                            ${{ number_format((float) ($o->total ?? $o->total_amount ?? 0), 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $o->status ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $o->payment_status ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $o->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <a
                                href="{{ route('dashboard.cs.payrolls.invoice', $o) }}"
                                class="inline-flex items-center gap-2 bg-emerald-600 text-white px-3 py-2 rounded-xl hover:bg-emerald-700 transition text-xs font-semibold"
                            >
                                <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                            لا توجد فواتير.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>

@endsection

