@extends('dashboards.layouts.app')
@section('content')
@php $title = 'لوحة السائق'; $subtitle = 'طلباتي المعيّنة وحالة الاستلام'; @endphp

@if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-black text-gray-900">مرحباً {{ auth('employee')->user()->full_name ?? 'سائق' }}</h3>
            <p class="text-sm text-gray-500">سجّل الاستلام ثم أكمِل التسليم مع توقيعك وتوقيع العميل — تُحفظ على الفاتورة.</p>
        </div>
        <div class="text-sm text-gray-600">
            @if($driver)
                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">سائق #{{ $driver->id }}</span>
            @else
                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700">لا يوجد ملف سائق مرتبط بحسابك</span>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
    <h3 class="text-base font-black text-gray-800 mb-3">الطلبات المعيّنة</h3>
    <div class="overflow-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-3 py-2 text-right">رقم الطلب</th>
                    <th class="px-3 py-2 text-right">العميل</th>
                    <th class="px-3 py-2 text-right">الحالة</th>
                    <th class="px-3 py-2 text-right">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr class="border-t border-gray-100 align-top">
                        <td class="px-3 py-2">#{{ $o->order_number ?? $o->id }}</td>
                        <td class="px-3 py-2">{{ $o->recipient_name ?? $o->user->name ?? '—' }}</td>
                        <td class="px-3 py-2">{{ $o->status ?? '—' }}</td>
                        <td class="px-3 py-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('dashboard.driver.orders.show', $o) }}" class="inline-flex items-center gap-1 bg-sky-600 text-white px-3 py-1.5 rounded-lg hover:bg-sky-700">
                                    <i class="fas fa-info-circle"></i>
                                    معلومات
                                </a>
                                @if(! in_array($o->status ?? '', ['out_for_delivery', 'delivered', 'done']))
                                    <form method="POST" action="{{ route('dashboard.driver.orders.receive', $o) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700">
                                            <i class="fas fa-check"></i>
                                            استلام الطلب
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">لا توجد طلبات معيّنة حالياً</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
