@extends('dashboards.layouts.app', ['title' => 'Purchase Orders'])

@section('content')
<div class="bg-white rounded-xl shadow border border-gray-100">
    <div class="p-6 flex items-center justify-between border-b border-gray-100">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">طلبات الشراء</h3>
            <p class="text-sm text-gray-500">إنشاء y تتبع طلبات إعادة المخزون</p>
        </div>
        <button type="button" onclick="document.getElementById('createPOModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            إنشاء PO
        </button>
    </div>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 border border-green-100">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->has('purchase_order'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-100">
                {{ $errors->first('purchase_order') }}
            </div>
        @endif

        <form method="GET" action="{{ route('dashboard.vendor.purchase-orders') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="md:col-span-3">
                <select name="status" class="border rounded-lg px-3 py-2 w-full">
                    <option value="">جميع الحالات</option>
                    @foreach($statusOptions as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <button class="px-4 py-2 bg-gray-800 text-white rounded-lg">تصفية</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">طلب الشراء</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المورد</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">تاريخ التسليم المتوقع</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">مجموع التكلفة</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">تاريخ الإنشاء</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $po)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">#{{ $po->id }}</div>
                                <div class="text-xs text-gray-500">{{ $po->items?->count() ?? 0 }} items</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="font-medium">{{ $po->supplier_name ?: '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $po->supplier_contact ?: '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">
                                    {{ $po->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700" min="1000-01-01" max="9999-12-31" oninput="if(this.value.length > 10) this.value=this.value.slice(0,10)"
                            >{{ optional($po->expected_delivery_date)->format('Y-m-d') ?: '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ number_format((float) $po->total_cost, 2) }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ optional($po->created_at)->format('Y-m-d') ?: '-' }}</td>
                        </tr>
                        @if(($po->items?->count() ?? 0) > 0)
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($po->items as $item)
                                            <div class="flex items-center justify-between bg-white border border-gray-100 rounded-lg px-3 py-2">
                                                <div class="text-sm text-gray-800">
                                                    {{ $item->product->name ?? ('Product #'.$item->product_id) }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $item->received_quantity }}/{{ $item->quantity }} @ {{ number_format((float) $item->unit_cost, 2) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                لا توجد طلبات شراء بعد
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<div id="createPOModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-800">إنشاء طلب شراء</h4>
            <button type="button" onclick="document.getElementById('createPOModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="{{ route('dashboard.vendor.purchase-orders.create') }}" method="POST">
            @csrf
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">اسم المورد</label>
                    <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">رقم الاتصال للمورد</label>
                    <input type="text" name="supplier_contact" value="{{ old('supplier_contact') }}" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">تاريخ التسليم المتوقع</label>
                    <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" class="border rounded-lg px-3 py-2 w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">ملاحظات</label>
                    <textarea name="notes" class="border rounded-lg px-3 py-2 w-full" rows="2">{{ old('notes') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm font-medium text-gray-700">عناصر الطلب</div>
                        <button type="button" class="px-3 py-1 text-sm bg-gray-100 rounded hover:bg-gray-200" onclick="addPOItemRow()">إضافة عنصر</button>
                    </div>
                    <div id="poItems" class="space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <select name="items[0][product_id]" class="border rounded-lg px-3 py-2 w-full" required>
                                <option value="">اختر المنتج</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[0][quantity]" min="1" class="border rounded-lg px-3 py-2 w-full" placeholder="Quantity" required>
                            <input type="number" step="0.01" name="items[0][unit_cost]" min="0" class="border rounded-lg px-3 py-2 w-full"
                             placeholder="Unit cost" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('createPOModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">إلغاء</button>
                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">إنشاء</button>
            </div>
        </form>
    </div>
</div>

<script>
    let poItemIndex = 1;
    function addPOItemRow() {
        const container = document.getElementById('poItems');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 md:grid-cols-3 gap-2';
        row.innerHTML = `
            <select name="items[${poItemIndex}][product_id]" class="border rounded-lg px-3 py-2 w-full" required>
                <option value="">Select product</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <input type="number" name="items[${poItemIndex}][quantity]" min="1" class="border rounded-lg px-3 py-2 w-full" placeholder="Quantity" required>
            <input type="number" step="0.01" name="items[${poItemIndex}][unit_cost]" min="0" class="border rounded-lg px-3 py-2 w-full" placeholder="Unit cost" required>
        `;
        container.appendChild(row);
        poItemIndex += 1;
    }
</script>
@endsection

