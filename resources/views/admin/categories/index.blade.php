@extends('layouts.admin')

@push('styles')
<style>
    body { font-family: 'El Messiri', sans-serif; }
    .page-header {
        background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
        padding: 3rem 2rem;
        color: white;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(42, 112, 128, 0.2);
    }
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .table-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .table-header {
        background: #f8fafc;
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e2e8f0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        background: #f8fafc;
        padding: 1.25rem 1.5rem;
        text-align: right;
        font-weight: 700;
        color: #2d3748;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    tr:hover {
        background: #f8fafc;
    }
    .category-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .badge-active {
        background: #d4edda;
        color: #155724;
    }
    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        transition: all 0.3s;
        margin: 0 0.25rem;
        cursor: pointer;
        border: none;
    }
    .action-btn.edit {
        background: #e3f2fd;
        color: #1976d2;
    }
    .action-btn.edit:hover {
        background: #1976d2;
        color: white;
    }
    .action-btn.delete {
        background: #ffebee;
        color: #c62828;
    }
    .action-btn.delete:hover {
        background: #c62828;
        color: white;
    }
    .btn-add {
        background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(42, 112, 128, 0.3);
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(42, 112, 128, 0.4);
    }
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        font-weight: 600;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 2px solid #c3e6cb;
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-8" style="background: linear-gradient(to bottom, #f8fafc, #e2e8f0);">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Page Header -->
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-tags ml-2"></i>
                        إدارة الفئات
                    </h1>
                    <p class="opacity-90 text-lg">تنظيم وإدارة فئات المنتجات</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="btn-add">
                    <i class="fas fa-plus-circle ml-2"></i>
                    إضافة فئة جديدة
                </a>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-box">
                <div class="stat-icon" style="background: #e3f2fd; color: #1976d2;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $categories->total() }}</div>
                <div style="color: #718096; font-size: 0.95rem;">إجمالي الفئات</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon" style="background: #e8f5e9; color: #388e3c;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $categories->where('is_active', true)->count() }}</div>
                <div style="color: #718096; font-size: 0.95rem;">فئات نشطة</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon" style="background: #fff3e0; color: #f57c00;">
                    <i class="fas fa-box"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $categories->sum('products_count') }}</div>
                <div style="color: #718096; font-size: 0.95rem;">إجمالي المنتجات</div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle ml-2" style="font-size: 1.25rem;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle ml-2" style="font-size: 1.25rem;"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Categories Table -->
        <div class="table-card">
            <div class="table-header">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin: 0;">
                    <i class="fas fa-list ml-2"></i>
                    قائمة الفئات
                </h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>اسم الفئة</th>
                        <th>عدد المنتجات</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
                                @else
                                    <div class="category-image" style="background: linear-gradient(135deg, #e2e8f0, #cbd5e0); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="color: #a0aec0; font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #2d3748; font-size: 1.05rem; margin-bottom: 0.25rem;">
                                    {{ $category->name }}
                                </div>
                                <div style="color: #718096; font-size: 0.9rem;">
                                    <i class="fas fa-link ml-1"></i>
                                    {{ $category->slug }}
                                </div>
                            </td>
                            <td>
                                <span style="background: #f0f4f8; padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; color: #2d3748;">
                                    <i class="fas fa-box ml-1"></i>
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #4a5568; font-size: 1.1rem;">
                                    {{ $category->display_order ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }}-circle ml-1"></i>
                                    {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #a0aec0;">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                <div style="font-size: 1.1rem; font-weight: 600;">لا توجد فئات حالياً</div>
                                <div style="margin-top: 0.5rem;">ابدأ بإضافة فئة جديدة</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
