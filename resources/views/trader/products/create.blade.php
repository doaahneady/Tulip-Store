@extends('trader.layout')
@php $title = 'إضافة منتج'; @endphp
@section('content')

<div class="card">
    <form method="POST" action="{{ route('trader.products.store') }}" enctype="multipart/form-data" id="productForm">
        @csrf
        <div class="grid grid-2">
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">اسم المنتج</label>
                <input class="input" name="name" value="{{ old('name') }}" required>
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">SKU (اختياري)</label>
                <input class="input" name="sku" value="{{ old('sku') }}">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">السعر</label>
                <input class="input" type="number" step="0.01" name="price" value="{{ old('price') }}" required>
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">سعر الخصم (اختياري)</label>
                <input class="input" type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">المخزون</label>
                <input class="input" type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">حد المخزون المنخفض</label>
                <input class="input" type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">الفئة (اختياري)</label>
                <select class="select" name="category_id" id="categorySelect">
                    <option value="">—</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected((string)old('category_id')===(string)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">تتبع المخزون</label>
                <select class="select" name="track_inventory">
                    <option value="1" @selected(old('track_inventory','1')==='1')>نعم</option>
                    <option value="0" @selected(old('track_inventory')==='0')>لا</option>
                </select>
            </div>
            <div class="grid" style="grid-column:1 / -1">
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">وصف (اختياري)</label>
                    <textarea class="textarea" rows="4" name="description">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">تفاصيل (اختياري)</label>
                    <textarea class="textarea" rows="4" name="details">{{ old('details') }}</textarea>
                </div>
                <div class="grid grid-2">
                    <div>
                        <label style="font-weight:800;display:block;margin-bottom:.35rem">صورة رئيسية (اختياري)</label>
                        <input class="input" type="file" name="image" accept="image/*">
                    </div>
                    <div>
                        <label style="font-weight:800;display:block;margin-bottom:.35rem">صور إضافية (اختياري)</label>
                        <input class="input" type="file" name="images[]" accept="image/*" multiple>
                    </div>
                </div>
            </div>
        </div>

        @php
            $customAttributes = old('custom_attributes', []);
            if (! is_array($customAttributes)) {
                $customAttributes = [];
            }
        @endphp
        <div class="card" style="margin-top:1rem">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap; margin-bottom:.75rem">
                <div style="font-weight:900">Custom Attributes</div>
                <button type="button" class="btn gray" data-action="add"><i class="fas fa-plus"></i> إضافة</button>
            </div>
            <div style="color:#6b7280; font-size:.9rem; margin-bottom:.75rem"
            >يدعم: dropdown, textbox, multi-line, number, date, checkbox group, radio group, file upload</div>
            <div style="display:grid; grid-template-columns:1.2fr 0.8fr; gap:1rem;" id="attrBuilder">
                <div>
                    <div style="font-weight:800; margin-bottom:.5rem;">Builder</div>
                    <div data-role="attr-list"></div>
                </div>
                <div>
                    <div style="font-weight:800; margin-bottom:.5rem;">Preview</div>
                    <div class="card" style="padding:1rem;">
                        <div data-role="attr-preview"></div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:.5rem;margin-top:1rem">
            <a class="btn gray" href="{{ route('trader.products.index') }}">إلغاء</a>
            <button class="btn primary" type="submit"><i class="fas fa-paper-plane"></i> إرسال للمراجعة</button>
        </div>
    </form>
</div>

<script src="/js/trader-attribute-builder.js"></script>
<script>
    window.initTraderAttributeBuilder?.(document.getElementById('attrBuilder'), @json(array_values($customAttributes)));
</script>

@endsection
