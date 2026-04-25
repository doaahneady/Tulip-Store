@extends('dashboards.layouts.app', ['title' => 'منتجات بمخزون منخفض', 'subtitle' => 'المنتجات التي وصلت إلى حد المخزون المنخفض'])

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('dashboard.admin.mart.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-right me-2"></i>
            العودة إلى المارت
        </a>
    </div>
    <div class="text-sm text-gray-600">
        <i class="fas fa-info-circle me-1"></i>
        المنتجات التي المخزون الحالي أقل من أو يساوي حد المخزون المنخفض
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-800">
            <i class="fas fa-triangle-exclamation text-red-600 me-2"></i>
            منتجات بمخزون منخفض
        </h3>
        <div class="text-2xl font-bold text-red-600">
            {{ number_format($lowStockProducts->count()) }} منتج
        </div>
    </div>

    @if($lowStockProducts->isEmpty())
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-emerald-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">رائع! لا توجد منتجات بمخزون منخفض</h3>
            <p class="text-gray-600">جميع المنتجات لديها مخزون كافٍ</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="table table-compact w-full">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>المنتج</th>
                        <th>SKU</th>
                        <th>التصنيف</th>
                        <th>المخزون الحالي</th>
                        <th>حد المخزون المنخفض</th>
                        <th>الفرق</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $index => $product)
                        @php
                            $stockQty = (int) ($product->stock_quantity ?? 0);
                            $threshold = (int) ($product->low_stock_threshold ?? 0);
                            $difference = $stockQty - $threshold;
                            $isActive = (bool) ($product->is_active ?? true);
                            $trackInventory = (bool) ($product->track_inventory ?? false);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="font-bold text-gray-600">{{ $index + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($product->primary_image_url)
                                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                        @if($product->brand)
                                            <div class="text-xs text-gray-500">{{ $product->brand }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-gray-600 font-mono text-sm">{{ $product->sku ?? '-' }}</td>
                            <td>
                                <div class="text-sm">
                                    <div class="font-semibold text-gray-800">{{ $product->category->name ?? '-' }}</div>
                                    @if($product->subcategory)
                                        <div class="text-xs text-gray-500">{{ $product->subcategory->name }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="px-3 py-1 rounded-lg font-bold text-sm
                                    @if($stockQty == 0) bg-red-100 text-red-700
                                    @elseif($stockQty < $threshold) bg-orange-100 text-orange-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ number_format($stockQty) }}
                                </span>
                            </td>
                            <td>
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-sm">
                                    {{ number_format($threshold) }}
                                </span>
                            </td>
                            <td>
                                @if($difference < 0)
                                    <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-bold text-sm">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        {{ number_format(abs($difference)) }}
                                    </span>
                                @elseif($difference == 0)
                                    <span class="px-3 py-1 rounded-lg bg-orange-100 text-orange-700 font-bold text-sm">
                                        <i class="fas fa-equals me-1"></i>
                                        0
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 font-bold text-sm">
                                        {{ number_format($difference) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-0.5 rounded text-xs @if($isActive) bg-emerald-100 text-emerald-700 @else bg-gray-100 text-gray-700 @endif">
                                        {{ $isActive ? 'نشط' : 'غير نشط' }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-xs @if($trackInventory) bg-blue-100 text-blue-700 @else bg-gray-100 text-gray-700 @endif">
                                        <i class="fas fa-{{ $trackInventory ? 'toggle-on' : 'toggle-off' }} me-1"></i>
                                        {{ $trackInventory ? 'تتبع مفعل' : 'تتبع معطل' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.admin.mart.products.edit', $product) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        تعديل
                                    </a>
                                    <button 
                                        type="button" 
                                        class="btn btn-success btn-sm"
                                        onclick="quickUpdateStock({{ $product->id }}, '{{ $product->name }}')"
                                    >
                                        <i class="fas fa-plus me-1"></i>
                                        إضافة مخزون
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Quick Stock Update Modal -->
<div id="quickStockModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-box me-2"></i>
                تحديث المخزون
            </h3>
            <button onclick="closeQuickStockModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <p class="text-gray-600 mb-2">المنتج:</p>
            <p class="font-bold text-gray-900" id="modalProductName"></p>
        </div>
        
        <form id="quickStockForm" onsubmit="submitQuickStock(event)">
            <input type="hidden" id="modalProductId">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    الكمية المضافة
                </label>
                <input 
                    type="number" 
                    id="stockQuantity" 
                    class="form-input w-full" 
                    min="1" 
                    required
                    placeholder="أدخل الكمية"
                >
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeQuickStockModal()" class="btn btn-ghost flex-1">
                    إلغاء
                </button>
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-save me-2"></i>
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function quickUpdateStock(productId, productName) {
        document.getElementById('modalProductId').value = productId;
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('stockQuantity').value = '';
        document.getElementById('quickStockModal').style.display = 'flex';
    }
    
    function closeQuickStockModal() {
        document.getElementById('quickStockModal').style.display = 'none';
    }
    
    async function submitQuickStock(event) {
        event.preventDefault();
        
        const productId = document.getElementById('modalProductId').value;
        const quantity = parseInt(document.getElementById('stockQuantity').value);
        
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
        
        try {
            const response = await fetch(`/dashboard/admin/mart/products/${productId}/add-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ quantity: quantity })
            });
            
            if (response.ok) {
                const data = await response.json();
                closeQuickStockModal();
                
                if (window.showToast) {
                    window.showToast(data.message || 'تم تحديث المخزون بنجاح');
                } else {
                    alert(data.message || 'تم تحديث المخزون بنجاح');
                }
                
                // Reload page to show updated data
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error('Failed to update stock');
            }
        } catch (error) {
            console.error('Error updating stock:', error);
            alert('حدث خطأ أثناء تحديث المخزون');
        }
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQuickStockModal();
        }
    });
</script>

@endsection
