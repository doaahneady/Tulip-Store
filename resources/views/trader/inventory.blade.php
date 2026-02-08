@extends('trader.layout')
@php $title = 'المخزون'; @endphp
@section('content')

<div class="card" style="margin-bottom:1rem">
    <form method="GET" style="display:flex;gap:.5rem;flex-wrap:wrap">
        <input class="input" style="max-width:420px" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU">
        <button class="btn gray" type="submit"><i class="fas fa-search"></i> بحث</button>
        <a class="btn gray" href="{{ route('trader.inventory') }}"><i class="fas fa-rotate"></i> مسح</a>
    </form>
</div>

<div class="card">
    <table class="table">
        <thead>
        <tr>
            <th>المنتج</th>
            <th>SKU</th>
            <th>المخزون</th>
            <th>حد منخفض</th>
            <th>تتبع</th>
            <th style="text-align:left">تحديث</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $p)
            @php
                $stock = (int)($p->stock_quantity ?? 0);
                $low = (int)($p->low_stock_threshold ?? 0);
                $track = (bool)($p->track_inventory ?? false);
                $badge = 'gray';
                if ($track) {
                    if ($stock <= 0) $badge = 'red';
                    elseif ($stock <= $low) $badge = 'orange';
                    else $badge = 'green';
                }
            @endphp
            <tr>
                <td style="font-weight:800">{{ $p->name }}</td>
                <td>{{ $p->sku ?? '—' }}</td>
                <td><span class="badge {{ $badge }}">{{ number_format($stock) }}</span></td>
                <td>{{ number_format($low) }}</td>
                <td>{{ $track ? 'نعم' : 'لا' }}</td>
                <td style="text-align:left">
                    <form method="POST" action="{{ route('trader.inventory.update', $p) }}" style="display:flex;gap:.5rem;justify-content:flex-end;flex-wrap:wrap">
                        @csrf
                        @method('PUT')
                        <input class="input" style="max-width:140px" type="number" name="stock_quantity" value="{{ $stock }}" min="0" required>
                        <input class="input" style="max-width:140px" type="number" name="low_stock_threshold" value="{{ $low }}" min="0">
                        <select class="select" style="max-width:140px" name="track_inventory">
                            <option value="1" @selected($track)>نعم</option>
                            <option value="0" @selected(! $track)>لا</option>
                        </select>
                        <button class="btn primary" style="padding:.45rem .8rem;border-radius:999px" type="submit"><i class="fas fa-save"></i> حفظ</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#9ca3af">لا توجد منتجات مقبولة</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem">
        {{ $products->links() }}
    </div>
</div>
@endsection
