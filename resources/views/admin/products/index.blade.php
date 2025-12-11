@extends('layouts.admin')

@push('styles')
<style>
    body { font-family: 'El Messiri', sans-serif; }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 2rem;
        color: white;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
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
    .filter-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .filter-input {
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #f8fafc;
    }
    .filter-input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .toolbar {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bulk-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .table-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
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
    }
    td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    tr:hover {
        background: #f8fafc;
    }
    .product-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s;
    }
    .product-image:hover {
        transform: scale(1.1);
    }
    .badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .badge-active { background: #d4edda; color: #155724; }
    .badge-inactive { background: #f8d7da; color: #721c24; }
    .badge-featured { background: #fff3cd; color: #856404; }
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
    .action-btn.edit { background: #e3f2fd; color: #1976d2; }
    .action-btn.edit:hover { background: #1976d2; color: white; }
    .action-btn.delete { background: #ffebee; color: #c62828; }
    .action-btn.delete:hover { background: #c62828; color: white; }
    .action-btn.quick-edit { background: #fff3e0; color: #f57c00; }
    .action-btn.quick-edit:hover { background: #f57c00; color: white; }
    .btn-add {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .btn-filter, .btn-export, .btn-bulk {
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .btn-export {
        background: #38a169;
        color: white;
    }
    .btn-bulk {
        background: #e2e8f0;
        color: #4a5568;
    }
    .btn-bulk:hover {
        background: #cbd5e0;
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
    .stock-indicator {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
    }
    .stock-high { background: #d4edda; color: #155724; }
    .stock-low { background: #fff3cd; color: #856404; }
    .stock-out { background: #f8d7da; color: #721c24; }
    .editable {
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.3s;
    }
    .editable:hover {
        background: #f0f4f8;
    }
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .image-preview-modal {
        max-width: 800px;
    }
    .image-preview-modal img {
        width: 100%;
        border-radius: 15px;
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
                        <i class="fas fa-box-open ml-2"></i>
                        إدارة المنتجات
                    </h1>
                    <p class="opacity-90 text-lg">إدارة مخزون المنتجات والأسعار</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('admin.products.export') }}?{{ http_build_query(request()->all()) }}" class="btn-export">
                        <i class="fas fa-download"></i>
                        تصدير CSV
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn-add">
                        <i class="fas fa-plus-circle"></i>
                        إضافة منتج
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-box">
                <div class="stat-icon" style="background: #e3f2fd; color: #1976d2;">
                    <i class="fas fa-box"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $products->total() }}</div>
                <div style="color: #718096; font-size: 0.95rem;">إجمالي المنتجات</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon" style="background: #e8f5e9; color: #388e3c;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $products->where('is_active', true)->count() }}</div>
                <div style="color: #718096; font-size: 0.95rem;">منتجات نشطة</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon" style="background: #fff3e0; color: #f57c00;">
                    <i class="fas fa-star"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $products->where('is_featured', true)->count() }}</div>
                <div style="color: #718096; font-size: 0.95rem;">منتجات مميزة</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon" style="background: #ffebee; color: #c62828;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #2d3748;">{{ $products->where('stock', '<=', 10)->count() }}</div>
                <div style="color: #718096; font-size: 0.95rem;">مخزون منخفض</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-search ml-1"></i>
                        البحث
                    </label>
                    <input type="text" name="search" placeholder="ابحث عن منتج..." value="{{ request('search') }}" class="filter-input" style="width: 100%;">
                </div>
                
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-tags ml-1"></i>
                        الفئة
                    </label>
                    <select name="category" class="filter-input" style="width: 100%;">
                        <option value="">الكل</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-toggle-on ml-1"></i>
                        الحالة
                    </label>
                    <select name="status" class="filter-input" style="width: 100%;">
                        <option value="">الكل</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-warehouse ml-1"></i>
                        المخزون
                    </label>
                    <select name="stock_status" class="filter-input" style="width: 100%;">
                        <option value="">الكل</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>منخفض</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>نفذ</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-sort ml-1"></i>
                        الترتيب
                    </label>
                    <select name="sort" class="filter-input" style="width: 100%;">
                        <option value="">الأحدث</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: منخفض-عالي</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: عالي-منخفض</option>
                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>المخزون: منخفض-عالي</option>
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i>
                    تصفية
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle ml-2" style="font-size: 1.25rem;"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Bulk Actions Toolbar -->
        <div class="toolbar">
            <div class="bulk-actions">
                <input type="checkbox" id="selectAll" style="width: 20px; height: 20px; cursor: pointer;">
                <span style="font-weight: 600; color: #4a5568;">تحديد الكل</span>
                <span id="selectedCount" style="color: #718096; display: none;"></span>
            </div>
            <div class="bulk-actions" id="bulkActionsButtons" style="display: none;">
                <button onclick="bulkAction('activate')" class="btn-bulk">
                    <i class="fas fa-check"></i>
                    تفعيل
                </button>
                <button onclick="bulkAction('deactivate')" class="btn-bulk">
                    <i class="fas fa-times"></i>
                    إلغاء التفعيل
                </button>
                <button onclick="bulkAction('feature')" class="btn-bulk">
                    <i class="fas fa-star"></i>
                    تمييز
                </button>
                <button onclick="bulkAction('delete')" class="btn-bulk" style="background: #ffebee; color: #c62828;">
                    <i class="fas fa-trash"></i>
                    حذف
                </button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-card">
            <form id="bulkForm" method="POST" action="{{ route('admin.products.bulk-action') }}">
                @csrf
                <input type="hidden" name="action" id="bulkAction">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;"></th>
                            <th>الصورة</th>
                            <th>المنتج</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>المخزون</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $product->id }}" class="product-checkbox" style="width: 20px; height: 20px; cursor: pointer;">
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" onclick="showImagePreview('{{ asset('storage/' . $product->image) }}', '{{ $product->name }}')">
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #2d3748; font-size: 1.05rem; margin-bottom: 0.25rem;">
                                        {{ $product->name }}
                                    </div>
                                    @if($product->is_featured)
                                        <span class="badge badge-featured">
                                            <i class="fas fa-star ml-1"></i>
                                            مميز
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span style="background: #f0f4f8; padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; color: #2d3748;">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="editable" onclick="quickEdit({{ $product->id }}, 'price', {{ $product->price }})" title="اضغط للتعديل">
                                        <div style="font-weight: 700; color: #2d3748; font-size: 1.1rem;">
                                            ${{ number_format($product->price, 2) }}
                                        </div>
                                    </div>
                                    @if($product->discount_price)
                                        <div class="editable" onclick="quickEdit({{ $product->id }}, 'discount_price', {{ $product->discount_price }})" style="color: #38a169; font-size: 0.9rem; font-weight: 600;">
                                            <i class="fas fa-tag ml-1"></i>
                                            ${{ number_format($product->discount_price, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="stock-indicator editable {{ $product->stock > 10 ? 'stock-high' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}" onclick="quickEdit({{ $product->id }}, 'stock', {{ $product->stock }})" title="اضغط للتعديل">
                                        <i class="fas fa-box ml-1"></i>
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        <i class="fas fa-{{ $product->is_active ? 'check' : 'times' }}-circle ml-1"></i>
                                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="action-btn edit" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
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
                                <td colspan="8" style="text-align: center; padding: 3rem; color: #a0aec0;">
                                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    <div style="font-size: 1.1rem; font-weight: 600;">لا توجد منتجات حالياً</div>
                                    <div style="margin-top: 0.5rem;">ابدأ بإضافة منتج جديد</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content image-preview-modal">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 id="imageTitle" style="font-size: 1.5rem; font-weight: 700; color: #2d3748;"></h3>
            <button onclick="closeModal('imageModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #718096;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <img id="imagePreview" src="" alt="">
    </div>
</div>

<!-- Quick Edit Modal -->
<div id="quickEditModal" class="modal">
    <div class="modal-content">
        <h3 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1.5rem;">
            <i class="fas fa-edit ml-2"></i>
            تعديل سريع
        </h3>
        <form id="quickEditForm">
            <input type="hidden" id="editProductId">
            <input type="hidden" id="editField">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;" id="editLabel"></label>
                <input type="number" id="editValue" step="0.01" min="0" class="filter-input" style="width: 100%;">
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-filter" style="flex: 1;">
                    <i class="fas fa-save"></i>
                    حفظ
                </button>
                <button type="button" onclick="closeModal('quickEditModal')" class="btn-bulk" style="flex: 1;">
                    <i class="fas fa-times"></i>
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Select All Functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActions();
});

document.querySelectorAll('.product-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.product-checkbox:checked').length;
    const buttons = document.getElementById('bulkActionsButtons');
    const counter = document.getElementById('selectedCount');
    
    if (checked > 0) {
        buttons.style.display = 'flex';
        counter.style.display = 'inline';
        counter.textContent = `(${checked} محدد)`;
    } else {
        buttons.style.display = 'none';
        counter.style.display = 'none';
    }
}

function bulkAction(action) {
    if (!confirm('هل أنت متأكد من تنفيذ هذا الإجراء؟')) return;
    
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkForm').submit();
}

// Image Preview
function showImagePreview(src, title) {
    document.getElementById('imagePreview').src = src;
    document.getElementById('imageTitle').textContent = title;
    document.getElementById('imageModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Quick Edit
function quickEdit(productId, field, currentValue) {
    document.getElementById('editProductId').value = productId;
    document.getElementById('editField').value = field;
    document.getElementById('editValue').value = currentValue;
    
    const labels = {
        'price': 'السعر',
        'discount_price': 'سعر الخصم',
        'stock': 'الكمية في المخزون'
    };
    document.getElementById('editLabel').textContent = labels[field];
    
    document.getElementById('quickEditModal').classList.add('active');
}

document.getElementById('quickEditForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const productId = document.getElementById('editProductId').value;
    const field = document.getElementById('editField').value;
    const value = document.getElementById('editValue').value;
    
    try {
        const response = await fetch(`/admin/products/${productId}/quick-update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field, value })
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeModal('quickEditModal');
            location.reload();
        }
    } catch (error) {
        alert('حدث خطأ أثناء التحديث');
    }
});

// Close modals on outside click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});
</script>
@endpush
@endsection
