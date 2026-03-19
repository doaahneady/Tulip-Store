@extends('dashboards.layouts.app', ['title' => 'إضافة تصنيف', 'subtitle' => 'إضافة تصنيف جديد لقسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.categories.store') }}" enctype="multipart/form-data" class="space-y-5">
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
                <label class="block text-sm text-gray-600 mb-1">ترتيب العرض</label>
                <input type="number" min="0" name="display_order" value="{{ old('display_order', 0) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" @selected(old('is_active','1')==='1')>نشط</option>
                    <option value="0" @selected(old('is_active')==='0')>غير نشط</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
            <textarea name="description" rows="4" class="form-input w-full">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">صورة (اختياري)</label>
            <input type="file" name="image" class="form-input w-full" accept="image/*">
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('dashboard.admin.mart.index') }}" class="btn btn-secondary">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>
</div>
@endsection

