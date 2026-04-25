@extends('dashboards.layouts.app', ['title' => 'إدارة المارت', 'subtitle' => 'إدارة قسم Mart (التصنيفات والمنتجات)'])

@section('content')
@php
    $categoriesCount = is_countable($categories ?? null) ? count($categories) : 0;
    $productsTotal = method_exists(($products ?? null), 'total') ? ($products->total() ?? 0) : 0;
@endphp

<style>
    /* Full-width layout for this page */
    #mainContent.db4-container {
        max-width: none !important;
        width: 100% !important;
        margin-inline: 0 !important;
    }
    /* Make caret visible and stable in dark theme */
    #martSearchInput { caret-color: #ffffff; }
</style>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
       
       
        <a href="{{ route('dashboard.admin.mart.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="{{ route('dashboard.admin.mart.sell-prices.index') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-tag"></i>
            <span>اسعار المبيع</span>
        </a>
        <a href="{{ route('dashboard.admin.mart.orders.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-receipt"></i>
            <span>طلبات المارت</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-gray-800">سعر الصرف (USD -> SYP)</h3>
            <p class="text-xs text-gray-500">تعديل السعر المستخدم في الواجهة لتحويل العملات</p>
        </div>
        <form method="POST" action="{{ route('dashboard.admin.exchange-rate.update') }}" class="flex items-center gap-2">
            @csrf
            <input
                type="number"
                name="usd_to_syp_rate"
                step="0.01"
                min="1"
                value="{{ \App\Models\SystemSetting::get('usd_to_syp_rate', 117) }}"
                class="form-input w-40"
                required
            >
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i>
                تحديث
            </button>
        </form>
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 cursor-pointer hover:shadow-md transition" onclick="window.location.href='{{ route('dashboard.admin.mart.low-stock') }}'">
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

<div class="flex flex-col gap-6 w-full">
    <!-- Products should appear first, categories under them -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 w-full" style="order:2;">
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
                                <a href="{{ route('dashboard.admin.mart.index', array_merge(array_diff_key(request()->query(), ['subcategory_id' => true]), ['category_id' => $cat->id])) }}" class="font-bold text-gray-900" @if($selected) style="color: rgba(255, 255, 255, 0.95) !important;" @endif>
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
                    <a href="{{ route('dashboard.admin.mart.index', array_diff_key(request()->query(), ['category_id' => true, 'subcategory_id' => true])) }}" class="block text-center text-sm text-indigo-600 mt-3">عرض الكل</a>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 text-sm w-full" id="martProductsPanel" style="order:1;">
        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-bold text-gray-900">المنتجات</h3>
                    <button 
                        type="button" 
                        id="bulkToggleInventoryBtn"
                        class="btn btn-sm btn-outline"
                        onclick="bulkToggleInventory()"
                        title="تفعيل/تعطيل تتبع المخزون لجميع المنتجات"
                    >
                        <i class="fas fa-toggle-on me-2"></i>
                        <span id="bulkToggleText">تفعيل الكل</span>
                    </button>
                </div>
                <form method="GET" action="{{ route('dashboard.admin.mart.index') }}" class="flex flex-wrap items-center gap-2">
                    @if(request()->has('missing_photo'))
                        <input type="hidden" name="missing_photo" value="{{ request('missing_photo') }}">
                    @endif
                    <input type="text" name="search" id="martSearchInput" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU" class="form-input text-xs w-48 md:w-64" autocomplete="off" dir="ltr" style="text-align:left;">
                    <select name="category_id" class="form-select text-xs w-40" id="martCategoryFilter">
                        <option value="">كل التصنيفات</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @php
                        $selectedCategoryId = request('category_id');
                        $subs = collect();
                        if ($selectedCategoryId && is_iterable($categories ?? null)) {
                            $cat = collect($categories)->firstWhere('id', (int) $selectedCategoryId) ?? collect($categories)->firstWhere('id', (string) $selectedCategoryId);
                            if ($cat && !empty($cat->subcategories)) {
                                $subs = collect($cat->subcategories);
                            }
                        }
                    @endphp
                    @if($selectedCategoryId && $subs->count())
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

            <div class="table-container text-xs" id="martProductsTableContainer">
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
                        <th>تتبع المخزون</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products === null)
                        <tr>
                            <td colspan="11" class="py-8 text-center text-gray-500">جدول المنتجات غير موجود</td>
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
                                    @php $trackInventory = (bool) ($p->track_inventory ?? false); @endphp
                                    <button 
                                        type="button" 
                                        class="toggle-inventory-btn px-2 py-0.5 rounded text-[10px] transition @if($trackInventory) bg-emerald-100 text-emerald-700 hover:bg-emerald-200 @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif"
                                        data-product-id="{{ $p->id }}"
                                        data-track-inventory="{{ $trackInventory ? '1' : '0' }}"
                                        onclick="toggleInventoryTracking(this)"
                                    >
                                        <i class="fas fa-{{ $trackInventory ? 'toggle-on' : 'toggle-off' }} me-1"></i>
                                        {{ $trackInventory ? 'مفعل' : 'معطل' }}
                                    </button>
                                </td>
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
                                        <button 
                                            type="button" 
                                            class="btn btn-ghost btn-xs text-[10px] px-1 h-6 min-h-0 text-red-600"
                                            onclick="deleteProduct({{ $p->id }}, event)"
                                        >
                                            حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-8 text-center text-gray-500">لا توجد منتجات</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
                </table>
            </div>
        </form>

        <div class="p-4" id="martProductsPagination">
            @if(method_exists(($products ?? null), 'links'))
                {{ $products->links() }}
            @endif
        </div>
    </div>
</div>

<script>
    // When changing category, clear subcategory_id to avoid mismatched filter
    document.addEventListener('DOMContentLoaded', function () {
        // Force full width even if theme CSS overrides it
        const main = document.getElementById('mainContent');
        if (main) {
            main.style.maxWidth = 'none';
            main.style.width = '100%';
            main.style.marginInline = '0';
        }

        function bindMartProductsUI() {
            const cat = document.getElementById('martCategoryFilter');
            if (cat) {
                if (!cat.dataset.bound) {
                    cat.dataset.bound = '1';
                    cat.addEventListener('change', function () {
                        const url = new URL(window.location.href);
                        url.searchParams.set('category_id', this.value);
                        url.searchParams.delete('subcategory_id');
                        // Category change affects subcategory dropdown; do full refresh
                        window.location.href = url.toString();
                    });
                }
            }

            const searchInput = document.getElementById('martSearchInput');
            if (searchInput) {
                if (!searchInput.dataset.bound) {
                    searchInput.dataset.bound = '1';
                    let t = null;
                    searchInput.addEventListener('input', function () {
                        window.clearTimeout(t);
                        const q = this.value || '';
                        t = window.setTimeout(() => {
                            const url = new URL(window.location.href);
                            if (q.trim().length) url.searchParams.set('search', q.trim());
                            else url.searchParams.delete('search');
                            url.searchParams.delete('page');
                            loadProductsPanel(url.toString());
                        }, 350);
                    });
                }
            }

            const selectAll = document.getElementById('selectAllProducts');
            if (selectAll) {
                if (!selectAll.dataset.bound) {
                    selectAll.dataset.bound = '1';
                    selectAll.addEventListener('change', () => {
                        const checked = selectAll.checked;
                        document.querySelectorAll('.product-checkbox').forEach((cb) => { cb.checked = checked; });
                    });
                }
            }
        }

        async function loadProductsPanel(url) {
            // Update only results (table + pagination) so the search input keeps focus/caret naturally
            const tableContainer = document.getElementById('martProductsTableContainer');
            const pagination = document.getElementById('martProductsPagination');
            if (!tableContainer || !pagination) {
                window.location.href = url;
                return;
            }

            try {
                const panel = document.getElementById('martProductsPanel');
                if (panel) panel.classList.add('opacity-80');

                const r = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                const html = await r.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');

                const newTable = doc.getElementById('martProductsTableContainer');
                const newPagination = doc.getElementById('martProductsPagination');

                if (!newTable || !newPagination) {
                    window.location.href = url;
                    return;
                }

                tableContainer.replaceWith(newTable);
                pagination.replaceWith(newPagination);
                window.history.replaceState({}, '', url);
                bindMartProductsUI();

                // Keep caret at end (RTL UIs sometimes move it)
                const search = document.getElementById('martSearchInput');
                if (search && document.activeElement === search) {
                    const len = (search.value || '').length;
                    try { search.setSelectionRange(len, len); } catch (e) {}
                }
            } catch (e) {
                window.location.href = url;
            } finally {
                const panel = document.getElementById('martProductsPanel');
                if (panel) panel.classList.remove('opacity-80');
            }
        }

        // initial bind
        bindMartProductsUI();
    });
</script>

<script>
    (function () {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? (csrfMeta.getAttribute('content') || '') : '';

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

    // Toggle inventory tracking
    async function toggleInventoryTracking(button) {
        const productId = button.getAttribute('data-product-id');
        const currentState = button.getAttribute('data-track-inventory') === '1';
        const newState = !currentState;
        
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
        
        try {
            button.disabled = true;
            button.style.opacity = '0.5';
            
            const response = await fetch(`/dashboard/admin/mart/products/${productId}/toggle-inventory`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ track_inventory: newState })
            });
            
            if (response.ok) {
                const data = await response.json();
                
                // Update button state
                button.setAttribute('data-track-inventory', newState ? '1' : '0');
                button.className = `toggle-inventory-btn px-2 py-0.5 rounded text-[10px] transition ${newState ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`;
                button.innerHTML = `<i class="fas fa-${newState ? 'toggle-on' : 'toggle-off'} me-1"></i> ${newState ? 'مفعل' : 'معطل'}`;
                
                // Show success message
                if (window.showToast) {
                    window.showToast(data.message || 'تم التحديث بنجاح');
                }
            } else {
                throw new Error('Failed to update');
            }
        } catch (error) {
            console.error('Error toggling inventory:', error);
            alert('حدث خطأ أثناء التحديث');
        } finally {
            button.disabled = false;
            button.style.opacity = '1';
        }
    }

    // Bulk toggle inventory tracking for all products
    async function bulkToggleInventory() {
        const bulkBtn = document.getElementById('bulkToggleInventoryBtn');
        const bulkText = document.getElementById('bulkToggleText');
        
        // Ask user what action to take
        const action = confirm('اختر الإجراء:\n\nموافق = تفعيل تتبع المخزون لجميع المنتجات\nإلغاء = تعطيل تتبع المخزون لجميع المنتجات');
        const enableTracking = action; // true = enable, false = disable
        
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
        
        try {
            bulkBtn.disabled = true;
            bulkBtn.style.opacity = '0.5';
            bulkText.textContent = 'جاري التحديث...';
            
            const response = await fetch('/dashboard/admin/mart/products/bulk-toggle-inventory', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ track_inventory: enableTracking })
            });
            
            if (response.ok) {
                const data = await response.json();
                
                // Show success message
                if (window.showToast) {
                    window.showToast(data.message || 'تم تحديث جميع المنتجات بنجاح');
                } else {
                    alert(data.message || 'تم تحديث جميع المنتجات بنجاح');
                }
                
                // Reload page to show updated states
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error('Failed to bulk update');
            }
        } catch (error) {
            console.error('Error bulk toggling inventory:', error);
            alert('حدث خطأ أثناء التحديث الجماعي');
            bulkText.textContent = 'تفعيل الكل';
        } finally {
            bulkBtn.disabled = false;
            bulkBtn.style.opacity = '1';
        }
    }

    // Delete product function
    function deleteProduct(productId, event) {
        // Prevent parent form submission
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Confirm deletion
        if (!confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
            return;
        }
        
        // Create a temporary form to submit the delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/dashboard/admin/mart/products/${productId}`;
        
        // Add CSRF token
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrf;
        form.appendChild(csrfInput);
        
        // Add DELETE method
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Append to body and submit
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
