@extends('dashboards.layouts.app')
@section('content')
@php $title = 'توقعات المبيعات'; $subtitle = 'توقعات الفترة القادمة حسب المنتج'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">توقعات المبيعات</h3>
        <a href="{{ route('dashboard.vendor.index') }}" class="text-sm text-indigo-600">عودة</a>
    </div>

    <div class="p-4 overflow-x-auto">
        @if(is_iterable($forecasts) && !method_exists(($forecasts ?? null), 'links'))
            <div class="text-center text-gray-500 py-8">جدول التوقعات غير متوفر</div>
        @else
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الفترة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المنتج</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الكمية</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الإيراد المتوقع</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الثقة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($forecasts as $f)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $f->forecast_period }}</td>
                            <td class="px-4 py-3">{{ $f->product->name ?? ('#'.$f->product_id) }}</td>
                            <td class="px-4 py-3">{{ number_format((int) ($f->forecasted_quantity ?? 0)) }}</td>
                            <td class="px-4 py-3">{{ number_format((float) ($f->forecasted_revenue ?? 0), 2) }} ر.س</td>
                            <td class="px-4 py-3">{{ number_format((float) ($f->confidence_score ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $forecasts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

