@extends('dashboards.layouts.app')
@section('content')
@php $title = 'سجل حركة المخزون'; $subtitle = $product->name ?? 'تفاصيل المنتج'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-bold text-gray-900">{{ $product->name }}</h3>
            <p class="text-sm text-gray-500">المخزون الحالي: {{ (int) ($product->stock_quantity ?? 0) }} • حد التنبيه: {{ (int) ($product->low_stock_threshold ?? 0) }}</p>
        </div>
        <a href="{{ route('dashboard.admin.inventory.alerts') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">رجوع</a>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">الحركات</h3>
        <span class="text-sm text-gray-500">{{ $movements->total() }} حركة</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">النوع</th>
                    <th class="text-right py-2">الكمية</th>
                    <th class="text-right py-2">قبل</th>
                    <th class="text-right py-2">بعد</th>
                    <th class="text-right py-2">السبب</th>
                    <th class="text-right py-2">ملاحظات</th>
                    <th class="text-right py-2">الوقت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                    <tr class="border-t border-gray-100">
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $m->type_color ?? 'text-gray-600 bg-gray-100' }}">{{ $m->type }}</span>
                        </td>
                        <td class="py-3 text-gray-900 font-semibold">{{ (int) $m->quantity }}</td>
                        <td class="py-3 text-gray-700">{{ $m->previous_stock !== null ? (int) $m->previous_stock : '-' }}</td>
                        <td class="py-3 text-gray-700">{{ $m->new_stock !== null ? (int) $m->new_stock : '-' }}</td>
                        <td class="py-3 text-gray-700">{{ $m->reason ?? '-' }}</td>
                        <td class="py-3 text-gray-700">{{ $m->notes ?? '-' }}</td>
                        <td class="py-3 text-gray-500">{{ $m->created_at?->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-500">لا توجد حركات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $movements->links() }}
    </div>
</div>
@endsection

