@extends('dashboards.layouts.app')
@section('content')
@php $title = 'طلبات التحويل'; $subtitle = 'متابعة طلبات تحويل الأرباح للمتاجر'; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <x-dashboard.stat-card title="طلبات معلقة" :value="number_format($payoutStats['pending_amount'] ?? 0, 2)" icon="fas fa-hourglass-half" color="amber" />
    <x-dashboard.stat-card title="موافق عليها" :value="number_format($payoutStats['approved_amount'] ?? 0, 2)" icon="fas fa-check" color="green" />
    <x-dashboard.stat-card title="مكتملة" :value="number_format($payoutStats['processed_amount'] ?? 0, 2)" icon="fas fa-check-double" color="indigo" />
    <x-dashboard.stat-card title="مرفوضة" :value="number_format($payoutStats['rejected_count'] ?? 0)" icon="fas fa-times" color="red" />
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة الطلبات</h3>
        <span class="text-sm text-gray-500">{{ $payouts->total() }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">المتجر</th>
                    <th class="text-right py-2">المبلغ</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">طريقة الدفع</th>
                    <th class="text-right py-2">تاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $p)
                    <tr class="border-t border-gray-100">
                        <td class="py-3 text-gray-800 font-semibold">{{ optional($p->store)->name ?? ('Store #'.$p->store_id) }}</td>
                        <td class="py-3 text-gray-900 font-semibold">{{ number_format((float) ($p->amount ?? 0), 2) }} {{ $p->currency ?? '' }}</td>
                        <td class="py-3 text-gray-700">{{ $p->status }}</td>
                        <td class="py-3 text-gray-700">{{ $p->payment_method ?? '-' }}</td>
                        <td class="py-3 text-gray-500">{{ $p->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $payouts->withQueryString()->links() }}
    </div>
</div>
@endsection

