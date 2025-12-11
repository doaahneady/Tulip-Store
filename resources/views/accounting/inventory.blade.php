@extends('layouts.accounting')

@section('title', 'إدارة المخزون')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-boxes"></i> إدارة المخزون</h1>
    <p>متابعة وإدارة المخزون والبضائع</p>
</div>

@php
$inventoryAccount = \App\Models\ChartOfAccount::where('account_code', '1140')->first();
$totalInventoryValue = $inventoryAccount ? $inventoryAccount->current_balance : 0;
$products = \App\Models\Product::with('category')->get();
$totalProducts = $products->count();
$lowStockCount = $products->where('stock_quantity', '<', 10)->count();
$outOfStockCount = $products->where('stock_quantity', 0)->count();
@endphp

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: #eff6ff; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #1e3a8a;">
        <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">${{ number_format($totalInventoryValue, 0) }}</div>
        <div style="color: #1e40af; font-weight: 700; margin-top: 0.5rem;">قيمة المخزون الإجمالية</div>
    </div>
    <div style="background: #d1fae5; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #047857;">
        <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: monospace;">{{ $totalProducts }}</div>
        <div style="color: #065f46; font-weight: 700; margin-top: 0.5rem;">إجمالي المنتجات</div>
    </div>
    <div style="background: #fef3c7; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #d97706;">
        <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: monospace;">{{ $lowStockCount }}</div>
        <div style="color: #92400e; font-weight: 700; margin-top: 0.5rem;">مخزون منخفض</div>
    </div>
    <div style="background: #fee2e2; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #dc2626;">
        <div style="font-size: 2rem; font-weight: 800; color: #dc2626; font-family: monospace;">{{ $outOfStockCount }}</div>
        <div style="color: #991b1b; font-weight: 700; margin-top: 0.5rem;">نفذ من المخزون</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-warehouse"></i>
        <span>تفاصيل المخزون</span>
        <div style="margin-right: auto; display: flex; gap: 0.5rem;">
            <button class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-filter"></i> تصفية
            </button>
            <button class="btn btn-primary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-download"></i> تصدير
            </button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>كود المنتج</th>
                <th>اسم المنتج</th>
                <th>الفئة</th>
                <th>الكمية المتاحة</th>
                <th>سعر الشراء</th>
                <th>سعر البيع</th>
                <th>القيمة الإجمالية</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products->take(20) as $product)
            <tr>
                <td style="font-family: monospace; font-weight: 600; color: #4f46e5;">{{ $product->sku ?? 'PRD-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div style="font-weight: 700; color: #1e3a8a;">{{ $product->name }}</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">{{ Str::limit($product->description, 40) }}</div>
                </td>
                <td>{{ $product->category->name ?? 'غير محدد' }}</td>
                <td style="font-family: monospace; font-weight: 700; text-align: center;">
                    <span style="background: {{ $product->stock_quantity > 10 ? '#d1fae5' : ($product->stock_quantity > 0 ? '#fef3c7' : '#fee2e2') }}; 
                                 color: {{ $product->stock_quantity > 10 ? '#047857' : ($product->stock_quantity > 0 ? '#d97706' : '#dc2626') }}; 
                                 padding: 0.3rem 0.8rem; border-radius: 6px; font-weight: 700;">
                        {{ $product->stock_quantity }}
                    </span>
                </td>
                <td class="positive" style="font-family: monospace;">${{ number_format($product->price * 0.6, 2) }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($product->price, 2) }}</td>
                <td class="positive" style="font-family: monospace; font-weight: 800;">${{ number_format($product->price * 0.6 * $product->stock_quantity, 2) }}</td>
                <td>
                    @if($product->stock_quantity > 10)
                        <span style="background: #d1fae5; color: #047857; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">متوفر</span>
                    @elseif($product->stock_quantity > 0)
                        <span style="background: #fef3c7; color: #d97706; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">منخفض</span>
                    @else
                        <span style="background: #fee2e2; color: #dc2626; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">نفذ</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align: center; color: #9ca3af; padding: 2rem;">لا توجد منتجات في المخزون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Inventory Movement -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-exchange-alt"></i>
        <span>حركة المخزون (آخر 10 عمليات)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>نوع الحركة</th>
                <th>المنتج</th>
                <th>الكمية</th>
                <th>القيمة</th>
                <th>المرجع</th>
            </tr>
        </thead>
        <tbody>
            @php
            $movements = [
                ['date' => '2025-12-01', 'type' => 'بيع', 'product' => 'منتج أ', 'qty' => -5, 'value' => 250, 'ref' => 'ORD-1001'],
                ['date' => '2025-11-30', 'type' => 'شراء', 'product' => 'منتج ب', 'qty' => 20, 'value' => 800, 'ref' => 'PO-2001'],
                ['date' => '2025-11-29', 'type' => 'بيع', 'product' => 'منتج ج', 'qty' => -3, 'value' => 180, 'ref' => 'ORD-1002'],
                ['date' => '2025-11-28', 'type' => 'تسوية', 'product' => 'منتج د', 'qty' => -2, 'value' => -60, 'ref' => 'ADJ-001'],
                ['date' => '2025-11-27', 'type' => 'شراء', 'product' => 'منتج هـ', 'qty' => 15, 'value' => 600, 'ref' => 'PO-2002'],
            ];
            @endphp
            @foreach($movements as $movement)
            <tr>
                <td>{{ $movement['date'] }}</td>
                <td>
                    @if($movement['type'] === 'بيع')
                        <span style="background: #fee2e2; color: #dc2626; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">{{ $movement['type'] }}</span>
                    @elseif($movement['type'] === 'شراء')
                        <span style="background: #d1fae5; color: #047857; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">{{ $movement['type'] }}</span>
                    @else
                        <span style="background: #e0e7ff; color: #4f46e5; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">{{ $movement['type'] }}</span>
                    @endif
                </td>
                <td style="font-weight: 600;">{{ $movement['product'] }}</td>
                <td style="font-family: monospace; font-weight: 700; color: {{ $movement['qty'] > 0 ? '#047857' : '#dc2626' }};">
                    {{ $movement['qty'] > 0 ? '+' : '' }}{{ $movement['qty'] }}
                </td>
                <td class="{{ $movement['value'] > 0 ? 'positive' : 'negative' }}" style="font-family: monospace; font-weight: 700;">
                    ${{ number_format(abs($movement['value']), 2) }}
                </td>
                <td style="font-family: monospace; color: #4f46e5; font-weight: 600;">{{ $movement['ref'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
