@extends('layouts.admin')

@push('styles')
<style>
    body { font-family: 'El Messiri', sans-serif; }
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .form-header {
        background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
        padding: 2rem;
        color: white;
    }
    .input-group {
        position: relative;
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
        border-color: #2a7080;
        background: white;
        box-shadow: 0 0 0 3px rgba(42, 112, 128, 0.1);
    }
    .btn-primary {
        background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
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
        box-shadow: 0 10px 25px rgba(42, 112, 128, 0.3);
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
        color: #2a7080;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        margin-bottom: 1.5rem;
    }
    .back-link:hover {
        color: #1a5060;
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
        grid-template-columns: repeat(2, 1fr);
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
    <div class="max-w-4xl mx-auto px-4">
        <a href="{{ route('admin.categories.index') }}" class="back-link">
            <i class="fas fa-arrow-left ml-2"></i>
            العودة إلى الفئات
        </a>

        <div class="form-card">
            <div class="form-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل الفئة
                        </h1>
                        <p class="opacity-90">تحديث معلومات الفئة</p>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 0.9rem; opacity: 0.9;">رقم الفئة</div>
                        <div style="font-size: 1.5rem; font-weight: 800;">#{{ $category->id }}</div>
                    </div>
                </div>
            </div>

            <div style="padding: 2.5rem;">
                <!-- Category Stats -->
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ $category->products_count ?? 0 }}</div>
                        <div class="stat-label">
                            <i class="fas fa-box ml-1"></i>
                            عدد المنتجات
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $category->created_at->diffForHumans() }}</div>
                        <div class="stat-label">
                            <i class="fas fa-calendar ml-1"></i>
                            تاريخ الإنشاء
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="input-group">
                        <label>
                            <i class="fas fa-tag ml-1"></i>
                            اسم الفئة *
                        </label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-align-right ml-1"></i>
                            الوصف
                        </label>
                        <textarea name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="input-group">
                        <label>
                            <i class="fas fa-image ml-1"></i>
                            صورة الفئة
                        </label>
                        <div class="image-preview-container">
                            @if($category->image)
                                <div style="margin-bottom: 1rem;">
                                    <div style="font-weight: 600; color: #2d3748; margin-bottom: 1rem;">الصورة الحالية:</div>
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="current-image">
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

                    <div class="input-group">
                        <label>
                            <i class="fas fa-sort-numeric-down ml-1"></i>
                            ترتيب العرض
                        </label>
                        <input type="number" name="display_order" value="{{ old('display_order', $category->display_order) }}">
                        <p class="text-sm text-gray-500 mt-2">الرقم الأقل يظهر أولاً</p>
                    </div>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} id="is_active">
                        <label for="is_active" style="margin: 0; cursor: pointer;">
                            <i class="fas fa-check-circle ml-1"></i>
                            فئة نشطة (ستظهر في المتجر)
                        </label>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: space-between;">
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                                <i class="fas fa-times ml-2"></i>
                                إلغاء
                            </a>
                        </div>
                        @if($category->products_count == 0)
                            <button type="button" onclick="if(confirm('هل أنت متأكد من حذف هذه الفئة؟')) document.getElementById('deleteForm').submit();" class="btn-danger">
                                <i class="fas fa-trash ml-2"></i>
                                حذف الفئة
                            </button>
                        @else
                            <button type="button" disabled class="btn-danger" style="opacity: 0.5; cursor: not-allowed;" title="لا يمكن حذف فئة تحتوي على منتجات">
                                <i class="fas fa-trash ml-2"></i>
                                حذف الفئة
                            </button>
                        @endif
                    </div>
                </form>

                <!-- Delete Form -->
                <form id="deleteForm" action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
