@extends('dashboards.layouts.app', ['title' => 'تعديل تصنيف فرعي', 'subtitle' => 'تعديل تصنيف فرعي لقسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.subcategories.update', $subcategory) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">التصنيف الرئيسي</label>
                <select name="category_id" class="form-select w-full" required>
                    <option value="">اختر تصنيف</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string)old('category_id', (string)$subcategory->category_id) === (string)$cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="{{ old('name', $subcategory->name) }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="{{ old('slug', $subcategory->slug) }}" class="form-input w-full">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">صورة التصنيف الفرعي</label>
                @if($subcategory->image)
                    <div class="mb-3">
                        <img src="{{ $subcategory->image_url ?? $subcategory->image }}" alt="Current Image" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                        <label class="block mt-2">
                            <input type="checkbox" name="remove_image" value="1" class="mr-2">
                            <span class="text-sm text-red-600">حذف الصورة الحالية</span>
                        </label>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="form-input w-full" onchange="previewImage(event)">
                <div id="imagePreview" class="mt-3 hidden">
                    <img id="preview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">ترتيب العرض</label>
                <input type="number" min="0" name="display_order" value="{{ old('display_order', $subcategory->display_order ?? 0) }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                <select name="is_active" class="form-select w-full">
                    <option value="1" @selected((string)old('is_active', (string)(int)($subcategory->is_active ?? 1))==='1')>نشط</option>
                    <option value="0" @selected((string)old('is_active', (string)(int)($subcategory->is_active ?? 1))==='0')>غير نشط</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between gap-2 flex-wrap">
            <button form="delete-subcategory-form" type="submit" class="btn btn-ghost text-red-600" onclick="return confirm('حذف التصنيف الفرعي؟')">حذف</button>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.admin.mart.index', ['category_id' => $subcategory->category_id]) }}" class="btn btn-secondary">رجوع</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </form>
    <form id="delete-subcategory-form" method="POST" action="{{ route('dashboard.admin.mart.subcategories.delete', $subcategory) }}">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection

