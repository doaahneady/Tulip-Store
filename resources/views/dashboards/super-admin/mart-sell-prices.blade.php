@extends('dashboards.layouts.app', ['title' => 'أسعار المبيع', 'subtitle' => 'تعديل أسعار مبيع منتجات المارت'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.admin.mart.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
            @if(!($zeroOnly ?? false))
                <a href="{{ route('dashboard.admin.mart.sell-prices.index', array_merge(request()->query(), ['zero_only' => 1])) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-filter"></i>
                    أسعار = 0
                </a>
            @else
                <a href="{{ route('dashboard.admin.mart.sell-prices.index', array_diff_key(request()->query(), ['zero_only' => true])) }}" class="btn btn-ghost btn-sm">
                    <i class="fas fa-list"></i>
                    عرض الكل
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('dashboard.admin.mart.sell-prices.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-input w-64" placeholder="بحث بالاسم أو SKU">
            @if($zeroOnly ?? false)
                <input type="hidden" name="zero_only" value="1">
            @endif
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
                بحث
            </button>
        </form>
    </div>

    <div class="table-container text-sm">
        <table class="table table-compact">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>سعر المبيع الحالي</th>
                    <th>آخر سعر إدخال</th>
                    <th>سعر جديد</th>
                    <th>تحديث</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td class="font-semibold">{{ $p->name }}</td>
                        <td>{{ number_format((float) ($p->price ?? 0), 2) }}</td>
                        <td>
                            @if($hasLastEntryPrice)
                                {{ number_format((float) ($p->last_entry_price ?? 0), 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('dashboard.admin.mart.sell-prices.update', $p) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $p->price) }}" class="form-input w-36" required>
                        </td>
                        <td>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i>
                                    تحديث
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">لا توجد منتجات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($products, 'links'))
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
