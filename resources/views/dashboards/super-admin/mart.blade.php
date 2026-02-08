@extends('dashboards.layouts.app', ['title' => 'إدارة المارت', 'subtitle' => 'إدارة قسم Mart (التصنيفات والمنتجات)'])

@section('content')
@php
    $categoriesCount = is_countable($categories ?? null) ? count($categories) : 0;
    $productsTotal = method_exists(($products ?? null), 'total') ? ($products->total() ?? 0) : 0;
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.admin.index') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-black transition">
            <i class="fas fa-chart-pie"></i>
            <span>لوحة الإدارة</span>
        </a>
        <a href="{{ route('dashboard.admin.gifts') }}" class="inline-flex items-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-xl hover:bg-pink-700 transition">
            <i class="fas fa-gift"></i>
            <span>Tulip Gifts</span>
        </a>
        <a href="{{ route('dashboard.admin.mart') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="{{ route('dashboard.admin.attendance') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضور الموظفين</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.manage') }}" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.admin.roles') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-user-shield"></i>
            <span>Rules</span>
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
                        <div class="block p-3 rounded-xl border @if($selected) border-indigo-400 bg-indigo-50 @else border-gray-200 hover:bg-gray-50 @endif">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('dashboard.admin.mart', array_merge(request()->query(), ['category_id' => $cat->id])) }}" class="font-semibold text-gray-900">
                                    {{ $cat->name }}
                                </a>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.admin.mart.categories.edit', $cat) }}" class="btn btn-ghost btn-xs">
                                        تعديل
                                    </a>
                                    @if(\Illuminate\Support\Facades\Schema::hasColumn('categories', 'is_active'))
                                        <form method="POST" action="{{ route('dashboard.admin.mart.categories.toggle-active', $cat) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-xs">
                                                {{ ($cat->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('dashboard.admin.mart.categories.delete', $cat) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف التصنيف؟')">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $cat->slug }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('dashboard.admin.mart', array_diff_key(request()->query(), ['category_id' => true])) }}" class="block text-center text-sm text-indigo-600 mt-3">عرض الكل</a>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 xl:col-span-3">
        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h3 class="text-lg font-semibold text-gray-900">المنتجات</h3>
                <form method="GET" action="{{ route('dashboard.admin.mart') }}" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU" class="form-input w-48 md:w-72">
                    <select name="category_id" class="form-select w-44">
                        <option value="">كل التصنيفات</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-ghost btn-sm">
                        <i class="fas fa-filter"></i>
                        تصفية
                    </button>
                    <a class="btn btn-secondary btn-sm" href="{{ route('dashboard.admin.export.products', array_merge(request()->query(), ['format' => 'csv'])) }}">
                        <i class="fas fa-download"></i>
                        تصدير
                    </a>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table class="table">
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
                                    <span class="px-2 py-1 rounded text-xs @if($active) bg-emerald-100 text-emerald-700 @else bg-gray-100 text-gray-700 @endif">
                                        {{ $active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td class="text-gray-600">{{ optional($p->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('dashboard.admin.mart.products.toggle-active', $p) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-xs">
                                                {{ $active ? 'تعطيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                        @if(\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_featured'))
                                            <form method="POST" action="{{ route('dashboard.admin.mart.products.toggle-featured', $p) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-xs">
                                                    {{ ($p->is_featured ?? false) ? 'إلغاء تمييز' : 'تمييز' }}
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('dashboard.admin.mart.products.delete', $p) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف المنتج؟')">
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
