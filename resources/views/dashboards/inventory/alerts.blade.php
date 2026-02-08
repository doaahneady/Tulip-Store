@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تنبيهات نقص المخزون'; $subtitle = 'المنتجات التي وصلت لحد التنبيه وإعادة التوريد'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة النقص</h3>
        <span class="text-sm text-gray-500">{{ $lowStockProducts->count() }} منتج</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">#</th>
                    <th class="text-right py-2">المنتج</th>
                    <th class="text-right py-2">المخزون</th>
                    <th class="text-right py-2">حد التنبيه</th>
                    <th class="text-right py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockProducts as $p)
                    <tr class="border-t border-gray-100">
                        <td class="py-3 text-gray-700">{{ $p->id }}</td>
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
                                    @if(!empty($p->image))
                                        <img src="{{ $p->image }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        <i class="fas fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 truncate">{{ $p->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $p->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs bg-amber-100 text-amber-800">{{ (int) $p->stock_quantity }}</span>
                        </td>
                        <td class="py-3 text-gray-700">{{ (int) $p->low_stock_threshold }}</td>
                        <td class="py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('dashboard.admin.inventory.history', $p->id) }}" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-50">السجل</a>
                                <form method="POST" action="{{ route('dashboard.admin.inventory.restock', $p->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input name="quantity" type="number" min="1" value="1" class="w-20 px-3 py-1.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                    <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">توريد</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500">لا يوجد منتجات ناقصة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

