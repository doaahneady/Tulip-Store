@extends('layouts.admin')

@push('styles')
<style>
    body { font-family: 'El Messiri', sans-serif; }
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2.5rem;
        color: white;
    }
    .form-body {
        padding: 2.5rem;
    }
    .input-group {
        margin-bottom: 1.5rem;
    }
    .input-group label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    .input-group input, .input-group textarea, .input-group select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #f8fafc;
    }
    .input-group input:focus, .input-group textarea:focus, .input-group select:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1.05rem;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }
    .btn-secondary:hover {
        background: #cbd5e0;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        margin-bottom: 1.5rem;
    }
    .back-link:hover {
        color: #764ba2;
        transform: translateX(-5px);
    }
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-left: 0.75rem;
        cursor: pointer;
    }
    .file-upload-area {
        border: 3px dashed #e2e8f0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s;
        cursor: pointer;
    }
    .file-upload-area:hover {
        border-color: #667eea;
        background: white;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-8" style="background: linear-gradient(to bottom, #f8fafc, #e2e8f0);">
    <div class="max-w-5xl mx-auto px-4">
        <a href="{{ route('admin.products.index') }}" class="back-link">
            <i class="fas fa-arrow-left ml-2"></i>
            العودة إلى المنتجات
        </a>

        <div class="form-card">
            <div class="form-header">
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-plus-circle ml-2"></i>
                    إضافة منتج جديد
                </h1>
                <p class="opacity-90">أضف منتج جديد إلى المتجر</p>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="input-group">
                        <label>
                            <i class="fas fa-box ml-1"></i>
                            اسم المنتج *
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: هاتف ذكي، قميص قطني، طاولة خشبية">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-tags ml-1"></i>
                            الفئة *
                        </label>
                        <select name="category_id" required>
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-align-right ml-1"></i>
                            الوصف *
                        </label>
                        <textarea name="description" rows="4" required placeholder="وصف تفصيلي عن المنتج...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-list-ul ml-1"></i>
                            التفاصيل الإضافية
                        </label>
                        <textarea name="details" rows="3" placeholder="تفاصيل إضافية، مواصفات، ملاحظات...">{{ old('details') }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="input-group">
                            <label>
                                <i class="fas fa-dollar-sign ml-1"></i>
                                السعر *
                            </label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required placeholder="0.00">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="input-group">
                            <label>
                                <i class="fas fa-tag ml-1"></i>
                                سعر الخصم
                            </label>
                            <input type="number" name="discount_price" step="0.01" min="0" value="{{ old('discount_price') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-warehouse ml-1"></i>
                            الكمية في المخزون *
                        </label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', 0) }}" required placeholder="0">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-image ml-1"></i>
                            صورة المنتج *
                        </label>
                        <div class="file-upload-area">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #a0aec0; margin-bottom: 1rem;"></i>
                            <div style="font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">اضغط لاختيار صورة أو اسحبها هنا</div>
                            <div style="color: #718096; font-size: 0.9rem;">JPG, PNG, GIF - الحد الأقصى 2MB</div>
                            <input type="file" name="image" accept="image/*" required style="display: none;" id="imageInput">
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} id="is_featured">
                        <label for="is_featured" style="margin: 0; cursor: pointer;">
                            <i class="fas fa-star ml-1"></i>
                            منتج مميز (سيظهر في الصفحة الرئيسية)
                        </label>
                    </div>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} id="is_active">
                        <label for="is_active" style="margin: 0; cursor: pointer;">
                            <i class="fas fa-check-circle ml-1"></i>
                            منتج نشط (متاح للبيع)
                        </label>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save ml-2"></i>
                            حفظ المنتج
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                            <i class="fas fa-times ml-2"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('.file-upload-area').addEventListener('click', function() {
    document.getElementById('imageInput').click();
});

document.getElementById('imageInput').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        document.querySelector('.file-upload-area').innerHTML = `
            <i class="fas fa-check-circle" style="font-size: 3rem; color: #38a169; margin-bottom: 1rem;"></i>
            <div style="font-weight: 600; color: #2d3748;">${e.target.files[0].name}</div>
            <div style="color: #718096; font-size: 0.9rem; margin-top: 0.5rem;">اضغط لتغيير الصورة</div>
        `;
    }
});
</script>
@endsection
