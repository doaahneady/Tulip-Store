@extends('dashboards.layouts.app', ['title' => 'إضافة منتج', 'subtitle' => 'إضافة منتج جديد لقسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-4xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.products.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="{{ old('name') }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="{{ old('slug') }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">التصنيف</label>
                <select name="category_id" class="form-select w-full">
                    <option value="">بدون</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id')==$cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">SKU</label>
                <input name="sku" value="{{ old('sku') }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">السعر</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">سعر مخفض (اختياري)</label>
                <input type="number" step="0.01" min="0" name="discount_price" value="{{ old('discount_price') }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المخزون</label>
                <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">حد انخفاض المخزون</label>
                <input type="number" min="0" name="low_stock_threshold" value="{{ old('low_stock_threshold', 0) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تتبع المخزون</label>
                <select name="track_inventory" class="form-select w-full">
                    <option value="1" @selected(old('track_inventory','1')==='1')>مفعل</option>
                    <option value="0" @selected(old('track_inventory')==='0')>غير مفعل</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" @selected(old('is_active','1')==='1')>نشط</option>
                    <option value="0" @selected(old('is_active')==='0')>غير نشط</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">تمييز</label>
                <select name="is_featured" class="form-select w-full">
                    <option value="0" @selected(old('is_featured','0')==='0')>لا</option>
                    <option value="1" @selected(old('is_featured')==='1')>نعم</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الوحدة (اختياري)</label>
                <input name="unit" value="{{ old('unit') }}" class="form-input w-full" placeholder="كيلو / علبة ...">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">المنشأ (اختياري)</label>
                <input name="origin" value="{{ old('origin') }}" class="form-input w-full" placeholder="محلي / تركيا ...">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">تفاصيل (اختياري)</label>
            <textarea name="details" rows="4" class="form-input w-full">{{ old('details') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">صورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('dashboard.admin.mart') }}" class="btn btn-secondary">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</div>
@endsection
