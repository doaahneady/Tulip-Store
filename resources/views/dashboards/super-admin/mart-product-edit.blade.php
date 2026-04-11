@extends('dashboards.layouts.app', ['title' => 'تعديل منتج', 'subtitle' => 'تعديل منتج في قسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-4xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="{{ old('name', $product->name) }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="{{ old('slug', $product->slug) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">التصنيف</label>
                <select name="category_id" class="form-select w-full" id="categorySelect">
                    <option value="">بدون</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string)old('category_id', (string)$product->category_id)===(string)$cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(!empty($subcategories) && $subcategories->count())
            <div>
                <label class="block text-sm text-gray-600 mb-1">التصنيف الفرعي</label>
                <select name="subcategory_id" class="form-select w-full" id="subcategorySelect">
                    <option value="">بدون</option>
                    @foreach($subcategories as $sub)
                        <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" @selected((string)old('subcategory_id', (string)($product->subcategory_id ?? ''))===(string)$sub->id)>{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <input type="hidden" name="sku" value="{{ old('sku', $product->sku) }}">
            <div>
                <label class="block text-sm text-gray-600 mb-1">السعر</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">سعر مخفض (اختياري)</label>
                <input type="number" step="0.01" min="0" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المخزون</label>
                <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">حد انخفاض المخزون</label>
                <input type="number" min="0" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تتبع المخزون</label>
                <select name="track_inventory" class="form-select w-full">
                    @php $track = (string) old('track_inventory', (string) (int) ($product->track_inventory ?? 1)); @endphp
                    <option value="1" @selected($track==='1')>مفعل</option>
                    <option value="0" @selected($track==='0')>غير مفعل</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    @php $active = (string) old('is_active', (string) (int) ($product->is_active ?? 1)); @endphp
                    <option value="1" @selected($active==='1')>نشط</option>
                    <option value="0" @selected($active==='0')>غير نشط</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تمييز</label>
                <select name="is_featured" class="form-select w-full">
                    @php $featured = (string) old('is_featured', (string) (int) ($product->is_featured ?? 0)); @endphp
                    <option value="0" @selected($featured==='0')>لا</option>
                    <option value="1" @selected($featured==='1')>نعم</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الوحدة (اختياري)</label>
                <input name="unit" value="{{ old('unit', $attrs['unit'] ?? '') }}" class="form-input w-full" placeholder="كيلو / علبة ...">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المنشأ (اختياري)</label>
                <input name="origin" value="{{ old('origin', $attrs['origin'] ?? '') }}" class="form-input w-full" placeholder="محلي / تركيا ...">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full">{{ old('description', $product->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">تفاصيل (اختياري)</label>
            <textarea name="details" rows="4" class="form-input w-full">{{ old('details', $product->details) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">تحديث الصورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
            @if(!empty($product->image))
                <div class="text-xs text-gray-500 mt-2">{{ $product->image }}</div>
            @endif
        </div>

        <div class="flex items-center justify-between gap-2 flex-wrap">
            <a href="{{ route('dashboard.admin.mart.index') }}" class="btn btn-secondary">رجوع</a>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </form>
</div>

@if(!empty($subcategories) && $subcategories->count())
<script>
    (function () {
        const categorySelect = document.getElementById('categorySelect');
        const subcategorySelect = document.getElementById('subcategorySelect');
        if (!categorySelect || !subcategorySelect) return;

        const allOptions = Array.from(subcategorySelect.querySelectorAll('option'));
        const placeholder = allOptions.shift();

        function syncSubcategories() {
            const categoryId = categorySelect.value;
            const selected = subcategorySelect.value;

            subcategorySelect.innerHTML = '';
            if (placeholder) subcategorySelect.appendChild(placeholder.cloneNode(true));

            const filtered = allOptions.filter((opt) => {
                const c = opt.getAttribute('data-category') || '';
                return categoryId === '' || c === categoryId;
            });
            filtered.forEach((opt) => subcategorySelect.appendChild(opt.cloneNode(true)));

            if (selected) {
                const exists = Array.from(subcategorySelect.options).some((o) => o.value === selected);
                subcategorySelect.value = exists ? selected : '';
            }
        }

        categorySelect.addEventListener('change', syncSubcategories);
        syncSubcategories();
    })();
</script>
@endif
@endsection
