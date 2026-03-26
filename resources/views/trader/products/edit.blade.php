@extends('trader.layout')
@php $title = 'تعديل منتج'; @endphp
@section('content')

@php
    $status = $product->status ?? 'pending';
    if ($status === 'draft') {
        $status = 'pending';
    }
    $isApproved = in_array($status, ['approved', 'active'], true);
@endphp

<div class="card">
    <form method="POST" action="{{ route('trader.products.update', $product) }}" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')

        @if($isApproved)
            <div class="alert error">هذا المنتج مقبول. تعديل بياناته سيُعاد لإعادة المراجعة من خدمة العملاء.</div>
        @endif

        <div class="grid grid-2">
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">اسم المنتج</label>
                <input class="input" name="name" value="{{ old('name', $product->name) }}" required>
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">SKU (اختياري)</label>
                <input class="input" name="sku" value="{{ old('sku', $product->sku) }}">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">السعر</label>
                <input class="input" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">سعر الخصم (اختياري)</label>
                <input class="input" type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">المخزون</label>
                <input class="input" type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" min="0">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">حد المخزون المنخفض</label>
                <input class="input" type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 0) }}" min="0">
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">الفئة (اختياري)</label>
                <select class="select" name="category_id">
                    <option value="">—</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected((string)old
                        ('category_id', $product->category_id)===(string)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:800;display:block;margin-bottom:.35rem">تتبع المخزون</label>
                <select class="select" name="track_inventory">
                    <option value="1" @selected((string)old('track_inventory', (string)(int)($product->track_inventory ?? 1))==='1')>نعم</option>
                    <option value="0" @selected((string)old('track_inventory', (string)(int)($product->track_inventory ?? 1))==='0')>لا</option>
                </select>
            </div>
            <div class="grid" style="grid-column:1 / -1">
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">وصف (اختياري)</label>
                    <textarea class="textarea" rows="4" name="description">{{ old('description', $product->description) }}</textarea>
                </div>
                <div>
                    <label style="font-weight:800;display:block;margin-bottom:.35rem">تفاصيل (اختياري)</label>
                    <textarea class="textarea" rows="4" name="details">{{ old('details', $product->details) }}</textarea>
                </div>
                <div class="grid grid-2">
                    <div>
                        <label style="font-weight:800;display:block;margin-bottom:.35rem">تحديث الصورة الرئيسية (اختياري)</label>
                        <input class="input" type="file" name="image" accept="image/*">
                    </div>
                    <div>
                        <label style="font-weight:800;display:block;margin-bottom:.35rem">تحديث الصور الإضافية (اختياري)</label>
                        <input class="input" type="file" name="images[]" accept="image/*" multiple>
                    </div>
                </div>
                <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                    @php
                        $statusText = $status;
                        if ($status === 'active') $statusText = 'نشط';
                        elseif ($status === 'pending') $statusText = 'قيد المراجعة';
                        elseif ($status === 'rejected') $statusText = 'مرفوض';
                        elseif ($status === 'approved') $statusText = 'مقبول';
                    @endphp
                    <span class="badge {{ $status==='approved' || $status==='active' ? 'green' : ($status==='rejected' ? 'red' : 'orange') }}">الحالة: {{ $statusText }}</span>
                </div>
            </div>
        </div>

        @php
            $customAttributes = old('custom_attributes');
            if (! is_array($customAttributes)) {
                $customAttributes = [];
                if (\Illuminate\Support\Facades\Schema::hasTable('product_attributes') &&
                 \Illuminate\Support\Facades\Schema::hasColumn('product_attributes', 'is_custom')) {
                    $customAttributes = $product->attributes()->where('is_custom', true)
                    ->orderBy('sort_order')->orderBy('id')->limit(200)->get()->map(function ($a) {
                        $opts = is_array($a->options ?? null) ? $a->options : [];
                        return [
                            'id' => $a->id,
                            'name' => $a->name,
                            'key' => $a->attribute_key ?? null,
                            'type' => $a->type ?? 'text',
                            'value' => $a->value_json ?? $a->value,
                            'options' => $opts ? implode("\n", $opts) : '',
                            'is_required' => (bool) ($a->is_required ?? false),
                            'rules' => $a->rules ?? null,
                            'uid' => $a->attribute_key ? ($a->attribute_key.'-'.$a->id) : ('attr-'.$a->id),
                        ];
                    })->all();
                }
            }
        @endphp
        <div class="card" style="margin-top:1rem">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap; margin-bottom:.75rem">
                <div style="font-weight:900">Custom Attributes</div>
                <button type="button" class="btn gray" data-action="add"><i class="fas fa-plus"></i> إضافة</button>
            </div>
            <div style="color:#6b7280; font-size:.9rem; margin-bottom:.75rem">يدعم: dropdown, textbox, multi-line, number, date,
                 checkbox group, radio group, file upload</div>
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

        <div style="display:flex;justify-content:space-between;gap:.5rem;margin-top:1rem;flex-wrap:wrap">
            <a class="btn gray" href="{{ route('trader.products.index') }}">رجوع</a>
            <button class="btn primary" type="submit"><i class="fas fa-save"></i> حفظ</button>
        </div>
    </form>
</div>

<script src="/js/trader-attribute-builder.js"></script>
<script>
    window.initTraderAttributeBuilder?.(document.getElementById('attrBuilder'), @json(array_values($customAttributes)));
</script>

@endsection
