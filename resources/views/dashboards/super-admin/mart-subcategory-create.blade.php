@extends('dashboards.layouts.app', ['title' => 'إضافة تصنيف فرعي', 'subtitle' => 'إضافة تصنيف فرعي جديد لقسم Mart'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="{{ route('dashboard.admin.mart.subcategories.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">التصنيف الرئيسي</label>
                <select name="category_id" class="form-select w-full" required>
                    <option value="">اختر تصنيف</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string)old('category_id', (string)($prefillCategoryId ?? '')) === (string)$cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                <input name="name" value="{{ old('name') }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                <input name="slug" value="{{ old('slug') }}" class="form-input w-full">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">صورة التصنيف الفرعي</label>
                <input type="file" name="image" accept="image/*" class="form-input w-full" onchange="previewImage(event)">
                <div id="imagePreview" class="mt-3 hidden">
                    <img id="preview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                </div>
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

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('dashboard.admin.mart.index') }}" class="btn btn-secondary">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
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

