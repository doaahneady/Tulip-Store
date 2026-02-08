@extends('trader.layout')
@php $title = 'منتجاتي'; @endphp
@section('content')

<div class="card" style="margin-bottom:1rem">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
        <form method="GET" style="display:flex;gap:.5rem;flex-wrap:wrap;flex:1;min-width:260px">
            <input class="input" style="max-width:420px" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU">
            <button class="btn gray" type="submit"><i class="fas fa-search"></i> بحث</button>
            <a class="btn gray" href="{{ route('trader.products.index') }}"><i class="fas fa-rotate"></i> مسح</a>
        </form>
        <a class="btn primary" href="{{ route('trader.products.create') }}"><i class="fas fa-plus"></i> إضافة منتج</a>
    </div>
</div>

<div class="card">
    <table class="table">
        <thead>
        <tr>
            <th>المنتج</th>
            <th>SKU</th>
            <th>السعر</th>
            <th>المخزون</th>
            <th>الحالة</th>
            <th style="text-align:left">إجراء</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $p)
            @php
                $status = $p->status ?? 'pending';
                $badgeClass = $status === 'approved' ? 'green' : ($status === 'rejected' ? 'red' : 'orange');
                $canEdit = in_array($status, ['pending', 'rejected'], true);
            @endphp
            <tr>
                <td style="font-weight:800">{{ $p->name }}</td>
                <td>{{ $p->sku ?? '—' }}</td>
                <td>{{ number_format((float)($p->price ?? 0), 2) }}</td>
                <td>{{ number_format((int)($p->stock_quantity ?? 0)) }}</td>
                <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>
                <td style="text-align:left;white-space:nowrap">
                    <a class="btn gray" style="padding:.45rem .8rem;border-radius:999px" href="{{ route('trader.products.edit', $p) }}"><i class="fas fa-pen"></i> تعديل</a>
                    @if($canEdit)
                        <form method="POST" action="{{ route('trader.products.destroy', $p) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" style="padding:.45rem .8rem;border-radius:999px" onclick="return confirm('حذف المنتج؟');"><i class="fas fa-trash"></i> حذف</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#9ca3af">لا توجد منتجات</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem">
        {{ $products->links() }}
    </div>
</div>

@endsection
