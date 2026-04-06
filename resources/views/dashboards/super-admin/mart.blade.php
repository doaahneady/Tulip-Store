@extends('dashboards.layouts.app', ['title' => 'إدارة المارت', 'subtitle' => 'إدارة قسم Mart (التصنيفات والمنتجات)'])

@section('content')
@php
    $categoriesCount = is_countable($categories ?? null) ? count($categories) : 0;
    $productsTotal = method_exists(($products ?? null), 'total') ? ($products->total() ?? 0) : 0;
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
       
       
        <a href="{{ route('dashboard.admin.mart.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="{{ route('dashboard.admin.mart.daily-prices.manage') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">
            <i class="fas fa-tags"></i>
            <span>أسعار يومية</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">التصنيفات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($categoriesCount) }}</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-tags text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">المنتجات</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($productsTotal) }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-boxes text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">منتجات فعالة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format(method_exists(($products ?? null), 'getCollection') ? $products->getCollection()->where('is_active', true)->count() : 0) }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">مخزون منخفض</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format(method_exists(($products ?? null), 'getCollection') ? $products->getCollection()->filter(fn ($p) => (int) ($p->stock_quantity ?? 0) <= (int) ($p->low_stock_threshold ?? 0))->count() : 0) }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 xl:col-span-1">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">التصنيفات</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.admin.mart.categories.create') }}" class="btn btn-ghost btn-xs">
                    <i class="fas fa-plus"></i>
                    إضافة
                </a>
                <span class="text-sm text-gray-500">{{ number_format($categoriesCount) }}</span>
            </div>
        </div>
        <div class="p-4">
            @if($categories === null)
                <div class="text-center text-gray-500 py-8">جدول التصنيفات غير موجود</div>
            @elseif($categoriesCount === 0)
                <div class="text-center text-gray-500 py-8">لا توجد بيانات</div>
            @else
                <div class="space-y-2">
                    @foreach($categories as $cat)
                        @php $selected = (string) request('category_id') === (string) $cat->id; @endphp
                        <div class="block p-3 rounded-xl border @if($selected) border-indigo-400 bg-indigo-50 @else border-gray-200 hover:bg-gray-50 @endif" @if($selected) style="background: rgba(34, 195, 166, 0.12) !important; border-color: rgba(34, 195, 166, 0.55) !important;" @endif>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <a href="{{ route('dashboard.admin.mart.index', array_merge(request()->query(), ['category_id' => $cat->id])) }}" class="font-bold text-gray-900" @if($selected) style="color: rgba(255, 255, 255, 0.95) !important;" @endif>
                                    {{ $cat->name }}
                                    <span class="text-xs text-gray-400 font-normal ms-1" @if($selected) style="color: rgba(255, 255, 255, 0.62) !important;" @endif>({{ $cat->slug }})</span>
                                </a>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('dashboard.admin.mart.categories.edit', $cat) }}" class="btn btn-primary btn-sm flex-1">
                                    <i class="fas fa-edit me-1"></i> تعديل
                                </a>
                                <form method="POST" action="{{ route('dashboard.admin.mart.categories.delete', $cat) }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-error btn-sm w-full" onclick="return confirm('حذف التصنيف؟')">
                                        <i class="fas fa-trash me-1"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('dashboard.admin.mart.index', array_diff_key(request()->query(), ['category_id' => true])) }}" class="block text-center text-sm text-indigo-600 mt-3">عرض الكل</a>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 xl:col-span-3 text-sm">
        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h3 class="text-base font-bold text-gray-900">المنتجات</h3>
                <form method="GET" action="{{ route('dashboard.admin.mart.index') }}" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU" class="form-input text-xs w-48 md:w-64">
                    <select name="category_id" class="form-select text-xs w-40">
                        <option value="">كل التصنيفات</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-ghost btn-xs text-[10px]">
                        <i class="fas fa-filter"></i>
                        تصفية
                    </button>
                    <a class="btn btn-secondary btn-xs text-[10px]" href="{{ route('dashboard.admin.export.products', array_merge(request()->query(), ['format' => 'csv'])) }}">
                        <i class="fas fa-download"></i>
                        تصدير
                    </a>
                    <a href="{{ route('dashboard.admin.mart.products.create') }}" class="btn btn-primary btn-xs text-[10px]">
                        <i class="fas fa-plus"></i>
                        إضافة منتج
                    </a>
                </form>
            </div>
        </div>

        <div class="table-container text-xs">
            <table class="table table-compact">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>SKU</th>
                        <th>التصنيف</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products === null)
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">جدول المنتجات غير موجود</td>
                        </tr>
                    @else
                        @forelse($products as $p)
                            <tr>
                                <td class="font-semibold text-gray-900">{{ $p->name }}</td>
                                <td class="text-gray-600">{{ $p->sku ?? '-' }}</td>
                                <td class="text-gray-600">{{ $p->category->name ?? '-' }}</td>
                                <td>{{ number_format((float) ($p->discount_price ?? $p->price), 2) }}</td>
                                <td>{{ number_format((int) ($p->stock_quantity ?? 0)) }}</td>
                                <td>
                                    @php $active = (bool) ($p->is_active ?? true); @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] @if($active) bg-emerald-100 text-emerald-700 @else bg-gray-100 text-gray-700 @endif">
                                        {{ $active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td class="text-gray-600">{{ optional($p->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('dashboard.admin.mart.products.edit', $p) }}" class="btn btn-ghost btn-xs text-[10px] px-1 h-6 min-h-0">
                                            تعديل
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.admin.mart.products.delete', $p) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs text-[10px] px-1 h-6 min-h-0 text-red-600" onclick="return confirm('حذف المنتج؟')">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500">لا توجد منتجات</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        <div class="p-4">
            @if(method_exists(($products ?? null), 'links'))
                {{ $products->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
