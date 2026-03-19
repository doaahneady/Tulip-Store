@extends('dashboards.layouts.app', ['title' => 'تعديل تصنيف', 'subtitle' => 'تعديل تصنيف لقسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="{{ old('name', $category->name) }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="{{ old('slug', $category->slug) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">ترتيب العرض</label>
                <input type="number" min="0" name="display_order" value="{{ old('display_order', $category->display_order ?? 0) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" @selected((string)old('is_active', (string)(int)($category->is_active ?? 1))==='1')>نشط</option>
                    <option value="0" @selected((string)old('is_active', (string)(int)($category->is_active ?? 1))==='0')>غير نشط</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full">{{ old('description', $category->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">تحديث الصورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
            @if(!empty($category->image))
                <div class="text-xs text-gray-500 mt-2">{{ $category->image }}</div>
            @endif
        </div>

        <div class="flex items-center justify-between gap-2 flex-wrap">
            <button form="delete-category-form" type="submit" class="btn btn-ghost text-red-600" onclick="return confirm('حذف التصنيف؟')">حذف</button>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.admin.mart.index') }}" class="btn btn-secondary">رجوع</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </form>
    <form id="delete-category-form" method="POST" action="{{ route('dashboard.admin.mart.categories.delete', $category) }}">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
