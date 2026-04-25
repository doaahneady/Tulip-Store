@extends('dashboards.layouts.app', ['title' => 'تعديل منتج', 'subtitle' => 'تعديل منتج في قسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-4xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5" id="martProductForm">
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
            @php
                $rawImage = (string) ($product->image ?? '');
                $imageUrl = null;
                if ($rawImage !== '') {
                    $imageUrl = \Illuminate\Support\Str::startsWith($rawImage, ['http://', 'https://', '/'])
                        ? $rawImage
                        : '/storage/'.$rawImage;
                }
            @endphp

            <input type="file" name="image" id="imageInput" class="form-input w-full" accept="image/*,.heic,.heif">

            <div class="mt-3">
                <img
                    id="imagePreview"
                    src="{{ $imageUrl ?: '' }}"
                    alt="معاينة الصورة"
                    style="display: {{ $imageUrl ? 'block' : 'none' }}; width: 100%; max-width: 420px; height: auto; border-radius: 14px; border: 1px solid #e5e7eb;"
                >
                <div id="imagePreviewHint" class="text-xs text-gray-500 mt-2" style="display:none;"></div>
            </div>

            @if(!empty($product->image))
                <div class="text-xs text-gray-500 mt-2">المسار الحالي: {{ $product->image }}</div>
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

<script>
    // Preview uploaded image before saving
    (function () {
        let convertedJpegFile = null; // Used to replace HEIC with JPG on submit
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        const hint = document.getElementById('imagePreviewHint');
        const form = document.getElementById('martProductForm');
        if (!input || !preview) return;

        function showHint(msg) {
            if (!hint) return;
            hint.textContent = msg || '';
            hint.style.display = msg ? 'block' : 'none';
        }

        function loadHeic2Any() {
            if (typeof window.heic2any === 'function') return Promise.resolve(true);
            return new Promise((resolve) => {
                const existing = document.querySelector('script[data-heic2any="1"]');
                if (existing) {
                    existing.addEventListener('load', () => resolve(true), { once: true });
                    existing.addEventListener('error', () => resolve(false), { once: true });
                    return;
                }
                const s = document.createElement('script');
                s.src = 'https://unpkg.com/heic2any/dist/heic2any.min.js';
                s.async = true;
                s.dataset.heic2any = '1';
                s.addEventListener('load', () => resolve(true), { once: true });
                s.addEventListener('error', () => resolve(false), { once: true });
                document.head.appendChild(s);
            });
        }

        input.addEventListener('change', async function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) return;

            showHint('');
            convertedJpegFile = null;

            const isHeic =
                (file.type && (file.type.includes('heic') || file.type.includes('heif'))) ||
                (file.name && (file.name.toLowerCase().endsWith('.heic') || file.name.toLowerCase().endsWith('.heif')));

            try {
                if (isHeic) {
                    // First try direct preview (works on some browsers like iOS Safari)
                    const directUrl = URL.createObjectURL(file);
                    preview.src = directUrl;
                    preview.style.display = 'block';

                    // If browser can't render HEIC, fallback to converting for preview
                    const ok = await new Promise((resolve) => {
                        const done = (v) => resolve(v);
                        const t = setTimeout(() => done(true), 400); // assume ok if no error quickly
                        preview.onload = () => { clearTimeout(t); done(true); };
                        preview.onerror = () => { clearTimeout(t); done(false); };
                    });
                    if (ok) return;

                    const loaded = await loadHeic2Any();
                    if (!loaded || typeof window.heic2any !== 'function') {
                        showHint('ملف HEIC: المتصفح لا يدعم المعاينة. سيتم التحويل على الخادم عند الحفظ.');
                        return;
                    }

                    const converted = await window.heic2any({ blob: file, toType: 'image/jpeg', quality: 0.85 });
                    const blob = Array.isArray(converted) ? converted[0] : converted;
                    convertedJpegFile = new File([blob], (file.name || 'image').replace(/\.(heic|heif)$/i, '') + '.jpg', { type: 'image/jpeg' });
                    const url = URL.createObjectURL(blob);
                    preview.src = url;
                    preview.style.display = 'block';
                    showHint('معاينة HEIC بعد التحويل (سيتم الحفظ كـ JPG).');
                    return;
                }

                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.style.display = 'block';
            } catch (e) {
                showHint('تعذر عرض المعاينة لهذه الصورة قبل الحفظ.');
            }
        });

        // Convert HEIC to JPG before submit so it is saved as JPG even if server can't convert
        if (form) {
            form.addEventListener('submit', async function (e) {
                if (!convertedJpegFile) return;
                try {
                    const dt = new DataTransfer();
                    dt.items.add(convertedJpegFile);
                    input.files = dt.files;
                } catch (err) {
                    // If replacing the file fails, let the request go as-is.
                }
            });
        }
    })();
</script>
@endsection
