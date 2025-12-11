@extends('layouts.admin')

@push('styles')
<style>
    body { font-family: 'El Messiri', sans-serif; }
    .gradient-bg { background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%); }
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
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-plus-circle ml-2"></i>
                    إضافة فئة جديدة
                </h1>
                <p class="opacity-90">أضف فئة جديدة لتنظيم منتجاتك</p>
            </div>

            <div class="p-8">

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="input-group">
                    <label>
                        <i class="fas fa-tag ml-1"></i>
                        اسم الفئة *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: إلكترونيات، ملابس، أثاث">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label>
                        <i class="fas fa-align-right ml-1"></i>
                        الوصف
                    </label>
                    <textarea name="description" rows="4" placeholder="وصف مختصر عن الفئة...">{{ old('description') }}</textarea>
                </div>

                <div class="input-group">
                    <label>
                        <i class="fas fa-image ml-1"></i>
                        صورة الفئة
                    </label>
                    <input type="file" name="image" accept="image/*" style="padding: 0.75rem;">
                    <p class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-info-circle ml-1"></i>
                        الحد الأقصى: 2 ميجابايت | الصيغ المدعومة: JPG, PNG, GIF
                    </p>
                </div>

                <div class="input-group">
                    <label>
                        <i class="fas fa-sort-numeric-down ml-1"></i>
                        ترتيب العرض
                    </label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" placeholder="0">
                    <p class="text-sm text-gray-500 mt-2">الرقم الأقل يظهر أولاً</p>
                </div>

                <div class="checkbox-wrapper">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} id="is_active">
                    <label for="is_active" style="margin: 0; cursor: pointer;">
                        <i class="fas fa-check-circle ml-1"></i>
                        فئة نشطة (ستظهر في المتجر)
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الفئة
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                        <i class="fas fa-times ml-2"></i>
                        إلغاء
                    </a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
