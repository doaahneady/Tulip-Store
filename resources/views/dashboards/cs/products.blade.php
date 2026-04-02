@extends('dashboards.layouts.app')
@section('content')
@php $title = 'المنتجات'; $subtitle = 'استعراض منتجات المتجر والبحث بالاسم أو ID'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('dashboard.cs.products') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU أو ID" class="w-72 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">بحث</button>
        </div>
        <a href="{{ route('dashboard.cs.products') }}" class="text-sm text-indigo-600 hover:underline">إعادة ضبط</a>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">المنتج</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">السعر</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">المخزون</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">عرض</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">تعديل</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">حذف</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $p)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $p->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-semibold">{{ $p->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $p->category?->name ?? '—' }} • {{ $p->store?->name ?? '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $p->sku ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->discount_price ?? $p->price ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if(\Illuminate\Support\Facades\Schema::hasColumn('products', 'stock_quantity'))
                                {{ (int) ($p->stock_quantity ?? 0) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $status = $p->status ?? null;
                                $isActive = \Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active') ? (bool) ($p->is_active ?? false) : null;
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                {{ $isActive === true ? 'bg-emerald-100 text-emerald-700' : ($status === 'active' || $status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $status ?? ($isActive === true ? 'active' : '—') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('product.show', ['id' => $p->id]) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">
                                <i class="fas fa-eye"></i>
                                <span>فتح</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('dashboard.cs.products.edit', $p) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                <i class="fas fa-pen"></i>
                                <span>تعديل</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <form method="POST" action="{{ route('dashboard.cs.products.delete', $p) }}" onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟');">
                                @csrf
                                <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                    <i class="fas fa-trash"></i>
                                    <span>حذف</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500">لا توجد منتجات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
