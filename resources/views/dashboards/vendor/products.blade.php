@extends('dashboards.layouts.app', ['title' => 'Inventory'])

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex items-center justify-between border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Products</h3>
            <p class="text-sm text-gray-500">Manage your store inventory</p>
        </div>
        <button type="button" onclick="document.getElementById('createProductModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            Add Product
        </button>
    </div>

    <div class="p-6">
        <form method="GET" action="{{ route('dashboard.vendor.products') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU" class="border rounded-lg px-3 py-2 w-full">
            <select name="category" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="stock_status" class="border rounded-lg px-3 py-2 w-full">
                <option value="">Any Stock</option>
                <option value="low" @selected(request('stock_status') === 'low')>Low Stock</option>
                <option value="out" @selected(request('stock_status') === 'out')>Out of Stock</option>
            </select>
            <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $product->stock_quantity }}</td>
                        <td class="px-6 py-4">
                            @php $isOut = (bool) ($product->track_inventory ?? true) && (int) ($product->stock_quantity ?? 0) <= 0; @endphp
                            <span class="px-2 py-1 text-xs rounded {{ $isOut ? 'bg-red-100 text-red-700' : ($product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $isOut ? 'out_of_stock' : ($product->status === 'draft' ? 'inactive' : $product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700" onclick="openEditModal('{{ $product->id }}')">Edit</button>
                            <form action="{{ route('dashboard.vendor.products.delete', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<div id="createProductModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-800">Add Product</h4>
            <button type="button" onclick="document.getElementById('createProductModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="{{ route('dashboard.vendor.products.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Category</label>
                    <select name="category_id" class="border rounded-lg px-3 py-2 w-full" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Description</label>
                    <textarea name="description" class="border rounded-lg px-3 py-2 w-full" rows="3" required></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Weight</label>
                    <input type="number" step="0.01" name="weight" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Images</label>
                    <input type="file" name="images[]" multiple class="border rounded-lg px-3 py-2 w-full">
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-lg border" onclick="document.getElementById('createProductModal').classList.add('hidden')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Create</button>
            </div>
        </form>
    </div>
</div>

@foreach($products as $product)
<div id="editProductModal-{{ $product->id }}" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-800">Edit Product</h4>
            <button type="button" onclick="document.getElementById('editProductModal-{{ $product->id }}').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="{{ route('dashboard.vendor.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Category</label>
                    <select name="category_id" class="border rounded-lg px-3 py-2 w-full" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected($product->category_id == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Description</label>
                    <textarea name="description" class="border rounded-lg px-3 py-2 w-full" rows="3" required>{{ $product->description }}</textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" value="{{ $product->cost_price }}" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="{{ $product->low_stock_threshold }}" class="border rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Status</label>
                    <select name="status" class="border rounded-lg px-3 py-2 w-full" required>
                        @php $currentStatus = $product->status === 'draft' ? 'inactive' : $product->status; @endphp
                        @foreach(['active','inactive','out_of_stock'] as $status)
                            <option value="{{ $status }}" @selected($currentStatus === $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-lg border" onclick="document.getElementById('editProductModal-{{ $product->id }}').classList.add('hidden')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
function openEditModal(id) {
    const el = document.getElementById('editProductModal-' + id);
    if (el) el.classList.remove('hidden');
}
</script>
@endsection
