@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تعديل المنتج'; $subtitle = 'تعديل جميع بيانات المنتج بما في ذلك الصورة'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-start justify-between gap-3 mb-6">
        <div>
            <div class="text-sm text-gray-500">Product #{{ $product->id }}</div>
            <div class="text-lg font-black text-gray-900">{{ $product->name }}</div>
        </div>
        <a href="{{ route('dashboard.cs.products') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Back</a>
    </div>

    <form method="POST" action="{{ route('dashboard.cs.products.update', $product) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @csrf

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">الاسم</label>
            <input name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">SKU</label>
            <input name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('sku')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">السوق</label>
            <select name="market" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                @php $m = old('market', $product->market); @endphp
                <option value="" @selected($m === null || $m === '')>—</option>
                <option value="store" @selected((string) $m === 'store')>store</option>
                <option value="mart" @selected((string) $m === 'mart')>mart</option>
            </select>
            @error('market')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
            @if(isset($categories) && $categories->count())
                <select name="category_id" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">—</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected((string) old('category_id', $product->category_id) === (string) $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            @else
                <input name="category_id" value="{{ old('category_id', $product->category_id) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @endif
            @error('category_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Store</label>
            @if(isset($stores) && $stores->count())
                <select name="store_id" class="w-full px-4 py-2 rounded-xl border border-gray-200">
                    <option value="">—</option>
                    @foreach($stores as $s)
                        <option value="{{ $s->id }}" @selected((string) old('store_id', $product->store_id) === (string) $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            @else
                <input name="store_id" value="{{ old('store_id', $product->store_id) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @endif
            @error('store_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">السعر</label>
            <input name="price" type="number" step="0.01" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('price')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">سعر التخفيض</label>
            <input name="discount_price" type="number" step="0.01" value="{{ old('discount_price', $product->discount_price) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('discount_price')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">سعر التكلفة</label>
            <input name="cost_price" type="number" step="0.01" value="{{ old('cost_price', $product->cost_price) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('cost_price')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">الكمية</label>
            <input name="stock_quantity" type="number" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('stock_quantity')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">حد تنبيه المخزون</label>
            <input name="low_stock_threshold" type="number" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('low_stock_threshold')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">خيارات</label>
            <div class="flex flex-wrap items-center gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="track_inventory" value="1" @checked(old('track_inventory', (bool) ($product->track_inventory ?? false)))>
                    <span class="text-sm text-gray-700">Track inventory</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', (bool) ($product->is_active ?? false)))>
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
            <input name="status" value="{{ old('status', $product->status) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200">
            @error('status')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 rounded-xl border border-gray-200">{{ old('description', $product->description) }}</textarea>
            @error('description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">تفاصيل</label>
            <textarea name="details" rows="5" class="w-full px-4 py-2 rounded-xl border border-gray-200">{{ old('details', $product->details) }}</textarea>
            @error('details')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">الصورة</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white">
            @error('photo')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="lg:col-span-2 flex items-center justify-end gap-2 mt-2">
            <a href="{{ route('dashboard.cs.products') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">إلغاء</a>
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">حفظ</button>
        </div>
    </form>
</div>
@endsection

