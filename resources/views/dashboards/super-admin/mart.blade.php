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
                <div class="space-y-2" id="martCategoriesList">
                    @foreach($categories as $cat)
                        @php $selected = (string) request('category_id') === (string) $cat->id; @endphp
                        <div class="block p-3 rounded-xl border @if($selected) border-indigo-400 bg-indigo-50 @else border-gray-200 hover:bg-gray-50 @endif mart-category-item" draggable="true" data-id="{{ $cat->id }}" @if($selected) style="background: rgba(34, 195, 166, 0.12) !important; border-color: rgba(34, 195, 166, 0.55) !important;" @endif>
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
                            @if(!empty($cat->subcategories) && $cat->subcategories->count())
                                <div class="mt-3 rounded-xl border border-gray-200 bg-white/60 p-2">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <div class="text-xs font-bold text-gray-700">التصنيفات الفرعية</div>
                                        <a class="text-xs text-indigo-600 hover:underline" href="{{ route('dashboard.admin.mart.subcategories.create', ['category_id' => $cat->id]) }}">إضافة</a>
                                    </div>
                                    <div class="space-y-1 mart-subcategories-list" data-category="{{ $cat->id }}">
                                        @foreach($cat->subcategories as $sub)
                                            <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 bg-white mart-subcategory-item" draggable="true" data-id="{{ $sub->id }}" data-category="{{ $cat->id }}">
                                                <a class="flex-1 text-xs font-semibold text-gray-800 truncate" href="{{ route('dashboard.admin.mart.subcategories.edit', $sub) }}">{{ $sub->name }}</a>
                                                <form method="POST" action="{{ route('dashboard.admin.mart.subcategories.delete', $sub) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:underline" onclick="return confirm('حذف التصنيف الفرعي؟')">حذف</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mt-3">
                                    <a class="text-xs text-indigo-600 hover:underline" href="{{ route('dashboard.admin.mart.subcategories.create', ['category_id' => $cat->id]) }}">إضافة تصنيف فرعي</a>
                                </div>
                            @endif
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
                    @if(request()->has('missing_photo'))
                        <input type="hidden" name="missing_photo" value="{{ request('missing_photo') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU" class="form-input text-xs w-48 md:w-64">
                    <select name="category_id" class="form-select text-xs w-40">
                        <option value="">كل التصنيفات</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @php
                        $subs = collect();
                        if (is_iterable($categories ?? null)) {
                            foreach ($categories as $c) {
                                if (!empty($c->subcategories)) {
                                    foreach ($c->subcategories as $s) {
                                        $subs->push($s);
                                    }
                                }
                            }
                        }
                    @endphp
                    @if($subs->count())
                        <select name="subcategory_id" class="form-select text-xs w-40">
                            <option value="">كل التصنيفات الفرعية</option>
                            @foreach($subs as $sub)
                                <option value="{{ $sub->id }}" @selected((string) request('subcategory_id') === (string) $sub->id)>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    <button type="submit" class="btn btn-ghost btn-xs text-[10px]">
                        <i class="fas fa-filter"></i>
                        تصفية
                    </button>
                    @if(($missingPhoto ?? false) === true)
                        <a href="{{ route('dashboard.admin.mart.index', array_diff_key(request()->query(), ['missing_photo' => true])) }}" class="btn btn-ghost btn-xs text-[10px]">
                            <i class="fas fa-list"></i>
                            عرض الكل
                        </a>
                    @else
                        <a href="{{ route('dashboard.admin.mart.index', array_merge(request()->query(), ['missing_photo' => 1])) }}" class="btn btn-warning btn-xs text-[10px]">
                            <i class="fas fa-image"></i>
                            بدون صورة
                        </a>
                    @endif
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

        <form method="POST" action="{{ route('dashboard.admin.mart.products.bulk-move') }}">
            @csrf
            <div class="p-4 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="text-xs font-bold text-gray-800">نقل جماعي</div>
                    <select name="target_subcategory_id" class="form-select text-xs w-60" required>
                        <option value="">اختر تصنيف فرعي</option>
                        @foreach(($categories ?? []) as $cat)
                            @if(!empty($cat->subcategories) && $cat->subcategories->count())
                                <optgroup label="{{ $cat->name }}">
                                    @foreach($cat->subcategories as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-xs text-[10px]">نقل</button>
                </div>
                <div class="text-xs text-gray-500">حدد منتجات من الجدول ثم اختر التصنيف الفرعي</div>
            </div>

            <div class="table-container text-xs">
                <table class="table table-compact" id="martProductsTable">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAllProducts">
                        </th>
                        <th>المنتج</th>
                        <th>SKU</th>
                        <th>التصنيف</th>
                        <th>الفرعي</th>
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
                            <td colspan="10" class="py-8 text-center text-gray-500">جدول المنتجات غير موجود</td>
                        </tr>
                    @else
                        @forelse($products as $p)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox" name="product_ids[]" value="{{ $p->id }}">
                                </td>
                                <td class="font-semibold text-gray-900">
                                    @php $hasPhoto = (bool) ($p->has_uploaded_photo ?? false); @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="truncate">{{ $p->name }}</span>
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] @if($hasPhoto) bg-emerald-100 text-emerald-700 @else bg-red-100 text-red-700 @endif" title="{{ $hasPhoto ? 'يوجد صورة' : 'بدون صورة' }}">
                                            <i class="fas fa-image"></i>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-gray-600">{{ $p->sku ?? '-' }}</td>
                                <td class="text-gray-600">{{ $p->category->name ?? '-' }}</td>
                                <td class="text-gray-600">{{ $p->subcategory->name ?? '-' }}</td>
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
                                <td colspan="10" class="py-8 text-center text-gray-500">لا توجد منتجات</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
                </table>
            </div>
        </form>

        <div class="p-4">
            @if(method_exists(($products ?? null), 'links'))
                {{ $products->links() }}
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const categoriesList = document.getElementById('martCategoriesList');
        if (categoriesList) {
            let dragging = null;
            categoriesList.addEventListener('dragstart', (e) => {
                const el = e.target.closest('.mart-category-item');
                if (!el) return;
                dragging = el;
                e.dataTransfer.effectAllowed = 'move';
            });
            categoriesList.addEventListener('dragover', (e) => {
                if (!dragging) return;
                e.preventDefault();
                const over = e.target.closest('.mart-category-item');
                if (!over || over === dragging) return;
                const rect = over.getBoundingClientRect();
                const before = (e.clientY - rect.top) < rect.height / 2;
                categoriesList.insertBefore(dragging, before ? over : over.nextSibling);
            });
            categoriesList.addEventListener('drop', async () => {
                if (!dragging) return;
                dragging = null;
                const order = Array.from(categoriesList.querySelectorAll('.mart-category-item')).map((x) => Number(x.getAttribute('data-id')));
                await fetch(@json(route('dashboard.admin.mart.categories.reorder')), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ order })
                });
            });
        }

        document.querySelectorAll('.mart-subcategories-list').forEach((list) => {
            let dragging = null;
            list.addEventListener('dragstart', (e) => {
                const el = e.target.closest('.mart-subcategory-item');
                if (!el) return;
                dragging = el;
                e.dataTransfer.effectAllowed = 'move';
            });
            list.addEventListener('dragover', (e) => {
                if (!dragging) return;
                e.preventDefault();
                const over = e.target.closest('.mart-subcategory-item');
                if (!over || over === dragging) return;
                const rect = over.getBoundingClientRect();
                const before = (e.clientY - rect.top) < rect.height / 2;
                list.insertBefore(dragging, before ? over : over.nextSibling);
            });
            list.addEventListener('drop', async () => {
                if (!dragging) return;
                const categoryId = list.getAttribute('data-category');
                dragging = null;
                const order = Array.from(list.querySelectorAll('.mart-subcategory-item')).map((x) => Number(x.getAttribute('data-id')));
                await fetch(@json(url('/dashboard/admin/mart/categories')).replace(/\/categories$/, '') + '/categories/' + categoryId + '/subcategories/reorder', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ order })
                });
            });
        });

        const selectAll = document.getElementById('selectAllProducts');
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                const checked = selectAll.checked;
                document.querySelectorAll('.product-checkbox').forEach((cb) => { cb.checked = checked; });
            });
        }
    })();
</script>
@endsection
