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
    .btn-danger {
        background: #f56565;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-danger:hover {
        background: #e53e3e;
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
    .current-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        margin-bottom: 1rem;
    }
    .image-preview-container {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 15px;
        border: 2px dashed #e2e8f0;
        text-align: center;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 15px;
    }
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 12px;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #2d3748;
    }
    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        margin-top: 0.25rem;
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
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل المنتج
                        </h1>
                        <p class="opacity-90">تحديث معلومات المنتج</p>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 0.9rem; opacity: 0.9;">رقم المنتج</div>
                        <div style="font-size: 1.5rem; font-weight: 800;">#{{ $product->id }}</div>
                    </div>
                </div>
            </div>

            <div class="form-body">
                <!-- Product Stats -->
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ $product->created_at->diffForHumans() }}</div>
                        <div class="stat-label">
                            <i class="fas fa-calendar ml-1"></i>
                            تاريخ الإضافة
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $product->updated_at->diffForHumans() }}</div>
                        <div class="stat-label">
                            <i class="fas fa-clock ml-1"></i>
                            آخر تحديث
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $product->views ?? 0 }}</div>
                        <div class="stat-label">
                            <i class="fas fa-eye ml-1"></i>
                            المشاهدات
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="input-group">
                        <label>
                            <i class="fas fa-box ml-1"></i>
                            اسم المنتج *
                        </label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <textarea name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-list-ul ml-1"></i>
                            التفاصيل الإضافية
                        </label>
                        <textarea name="details" rows="3">{{ old('details', $product->details) }}</textarea>
                    </div>

                    <div class="grid-2">
                        <div class="input-group">
                            <label>
                                <i class="fas fa-dollar-sign ml-1"></i>
                                السعر *
                            </label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="input-group">
                            <label>
                                <i class="fas fa-tag ml-1"></i>
                                سعر الخصم
                            </label>
                            <input type="number" name="discount_price" step="0.01" min="0" value="{{ old('discount_price', $product->discount_price) }}">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-warehouse ml-1"></i>
                            الكمية في المخزون *
                        </label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-image ml-1"></i>
                            صورة المنتج
                        </label>
                        <div class="image-preview-container">
                            @if($product->image)
                                <div style="margin-bottom: 1rem;">
                                    <div style="font-weight: 600; color: #2d3748; margin-bottom: 1rem;">الصورة الحالية:</div>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="current-image">
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.75rem;">
                            <p style="color: #718096; font-size: 0.9rem; margin-top: 0.5rem;">
                                <i class="fas fa-info-circle ml-1"></i>
                                اترك الحقل فارغاً للاحتفاظ بالصورة الحالية | الحد الأقصى: 2MB
                            </p>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} id="is_featured">
                        <label for="is_featured" style="margin: 0; cursor: pointer;">
                            <i class="fas fa-star ml-1"></i>
                            منتج مميز (سيظهر في الصفحة الرئيسية)
                        </label>
                    </div>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} id="is_active">
                        <label for="is_active" style="margin: 0; cursor: pointer;">
                            <i class="fas fa-check-circle ml-1"></i>
                            منتج نشط (متاح للبيع)
                        </label>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: space-between;">
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                                <i class="fas fa-times ml-2"></i>
                                إلغاء
                            </a>
                        </div>
                        <button type="button" onclick="if(confirm('هل أنت متأكد من حذف هذا المنتج؟')) document.getElementById('deleteForm').submit();" class="btn-danger">
                            <i class="fas fa-trash ml-2"></i>
                            حذف المنتج
                        </button>
                    </div>
                </form>

                <!-- Delete Form -->
                <form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
