@extends('dashboards.layouts.app', ['title' => 'إدارة الهدايا', 'subtitle' => 'إدارة قسم Gifts (الهدايا وخيارات التخصيص)'])

@section('content')
@php
    $giftCollection = method_exists(($gifts ?? null), 'getCollection') ? $gifts->getCollection() : collect();
    $giftsTotal = method_exists(($gifts ?? null), 'total') ? ($gifts->total() ?? 0) : $giftCollection->count();
    $giftsActive = $giftCollection->where('is_active', true)->count();
    $giftsFeatured = $giftCollection->where('is_featured', true)->count();
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.admin.index') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl hover:bg-black transition">
            <i class="fas fa-chart-pie"></i>
            <span>لوحة الإدارة</span>
        </a>
        <a href="{{ route('dashboard.admin.gifts') }}" class="inline-flex items-center gap-2 bg-pink-600 text-white px-4 py-2 rounded-xl hover:bg-pink-700 transition">
            <i class="fas fa-gift"></i>
            <span>Tulip Gifts</span>
        </a>
        <a href="{{ route('dashboard.admin.mart') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
            <i class="fas fa-store"></i>
            <span>Tulip Mart</span>
        </a>
        <a href="{{ route('dashboard.admin.attendance') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضور الموظفين</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.manage') }}" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.admin.roles') }}" class="inline-flex items-center gap-2 bg-slate-700 text-white px-4 py-2 rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-user-shield"></i>
            <span>Rules</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">الهدايا</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($giftsTotal) }}</h3>
            </div>
            <div class="w-12 h-12 bg-pink-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-gift text-pink-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">نشطة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($giftsActive) }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">مميزة</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($giftsFeatured) }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-star text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">خيارات التخصيص</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format((is_countable($boxes ?? null) ? count($boxes) : 0) + (is_countable($wrappings ?? null) ? count($wrappings) : 0) + (is_countable($ribbons ?? null) ? count($ribbons) : 0) + (is_countable($cards ?? null) ? count($cards) : 0) + (is_countable($fillers ?? null) ? count($fillers) : 0)) }}</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-sliders-h text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-900">الهدايا</h3>
            <form method="GET" action="{{ route('dashboard.admin.gifts') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو التصنيف" class="form-input w-48 md:w-72">
                <select name="active" class="form-select w-44">
                    <option value="">كل الحالات</option>
                    <option value="1" @selected(request('active') === '1')>نشط</option>
                    <option value="0" @selected(request('active') === '0')>غير نشط</option>
                </select>
                <button type="submit" class="btn btn-ghost btn-sm">
                    <i class="fas fa-filter"></i>
                    تصفية
                </button>
                <a class="btn btn-secondary btn-sm" href="{{ route('dashboard.admin.export.products', ['format' => 'csv']) }}">
                    <i class="fas fa-download"></i>
                    تصدير
                </a>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>التصنيف</th>
                    <th>السعر</th>
                    <th>المخزون</th>
                    <th>الحالة</th>
                    <th>مميز</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if($gifts === null)
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500">جدول الهدايا غير موجود</td>
                    </tr>
                @else
                    @forelse($gifts as $gift)
                        <tr>
                            <td class="font-semibold text-gray-900">{{ $gift->name }}</td>
                            <td class="text-gray-600">{{ $gift->category ?? '-' }}</td>
                            <td>{{ number_format((float) $gift->price, 2) }}</td>
                            <td>{{ number_format((int) ($gift->stock_quantity ?? 0)) }}</td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs @if($gift->is_active) bg-emerald-100 text-emerald-700 @else bg-gray-100 text-gray-700 @endif">
                                    {{ $gift->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs @if($gift->is_featured) bg-amber-100 text-amber-700 @else bg-gray-100 text-gray-700 @endif">
                                    {{ $gift->is_featured ? 'نعم' : 'لا' }}
                                </span>
                            </td>
                            <td class="text-gray-600">{{ optional($gift->created_at)->format('Y-m-d') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('dashboard.admin.gifts.toggle-active', $gift) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-xs">
                                            {{ $gift->is_active ? 'تعطيل' : 'تفعيل' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.admin.gifts.toggle-featured', $gift) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-xs">
                                            {{ $gift->is_featured ? 'إلغاء تمييز' : 'تمييز' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.admin.gifts.delete', $gift) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف الهدية؟')">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">لا توجد هدايا</td>
                        </tr>
                    @endforelse
                @endif
            </tbody>
        </table>
    </div>

    <div class="p-4">
        @if(method_exists(($gifts ?? null), 'links'))
            {{ $gifts->links() }}
        @endif
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">الصناديق</h3>
            <span class="text-sm text-gray-500">{{ is_countable($boxes ?? null) ? count($boxes) : 0 }}</span>
        </div>
        <div class="p-4">
            @if($boxes === null)
                <div class="text-center text-gray-500 py-8">جدول الصناديق غير موجود</div>
            @elseif(count($boxes) === 0)
                <div class="text-center text-gray-500 py-8">لا توجد بيانات</div>
            @else
                <div class="space-y-3">
                    @foreach($boxes as $box)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">{{ $box->name }}</span>
                                <span class="text-xs text-gray-500">الحجم: {{ $box->size }} | حد العناصر: {{ $box->max_items }}</span>
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-indigo-700">{{ number_format((float) $box->price, 2) }}</div>
                                <div class="text-xs text-gray-500">مخزون: {{ number_format((int) $box->stock) }}</div>
                                <div class="mt-2 flex items-center gap-2 justify-end">
                                    <form method="POST" action="{{ route('dashboard.admin.gifts.boxes.toggle-active', $box) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-xs">
                                            {{ ($box->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.admin.gifts.boxes.delete', $box) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف الصندوق؟')">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">الحشوات والخيارات</h3>
            <span class="text-sm text-gray-500">{{ number_format((is_countable($fillers ?? null) ? count($fillers) : 0) + (is_countable($wrappings ?? null) ? count($wrappings) : 0) + (is_countable($ribbons ?? null) ? count($ribbons) : 0) + (is_countable($cards ?? null) ? count($cards) : 0)) }}</span>
        </div>
        <div class="p-4 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-gray-800">الحشوات</h4>
                    <span class="text-xs text-gray-500">{{ is_countable($fillers ?? null) ? count($fillers) : 0 }}</span>
                </div>
                @if($fillers === null)
                    <div class="text-center text-gray-500 py-4">جدول الحشوات غير موجود</div>
                @elseif(count($fillers) === 0)
                    <div class="text-center text-gray-500 py-4">لا توجد بيانات</div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($fillers as $filler)
                            <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $filler->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $filler->category }}</div>
                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-indigo-700">{{ number_format((float) $filler->price, 2) }}</div>
                                    <div class="text-xs text-gray-500">مخزون: {{ number_format((int) $filler->stock) }}</div>
                                    <div class="mt-2 flex items-center gap-2 justify-end">
                                        <form method="POST" action="{{ route('dashboard.admin.gifts.fillers.toggle-active', $filler) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-xs">
                                                {{ ($filler->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.admin.gifts.fillers.delete', $filler) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف الحشوة؟')">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-bold text-gray-800">التغليف</div>
                        <div class="text-xs text-gray-500">{{ is_countable($wrappings ?? null) ? count($wrappings) : 0 }}</div>
                    </div>
                    @if($wrappings && count($wrappings) > 0)
                        <div class="space-y-2">
                            @foreach($wrappings->take(6) as $w)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $w->name }}</span>
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-indigo-700">{{ number_format((float) $w->price, 2) }}</span>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('dashboard.admin.gifts.wrappings.toggle-active', $w) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-xs">
                                                    {{ ($w->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('dashboard.admin.gifts.wrappings.delete', $w) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف التغليف؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-4 text-sm">لا توجد بيانات</div>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-bold text-gray-800">الشرائط</div>
                        <div class="text-xs text-gray-500">{{ is_countable($ribbons ?? null) ? count($ribbons) : 0 }}</div>
                    </div>
                    @if($ribbons && count($ribbons) > 0)
                        <div class="space-y-2">
                            @foreach($ribbons->take(6) as $r)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $r->name }}</span>
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-indigo-700">{{ number_format((float) $r->price, 2) }}</span>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('dashboard.admin.gifts.ribbons.toggle-active', $r) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-xs">
                                                    {{ ($r->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('dashboard.admin.gifts.ribbons.delete', $r) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف الشريط؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-4 text-sm">لا توجد بيانات</div>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-bold text-gray-800">البطاقات</div>
                        <div class="text-xs text-gray-500">{{ is_countable($cards ?? null) ? count($cards) : 0 }}</div>
                    </div>
                    @if($cards && count($cards) > 0)
                        <div class="space-y-2">
                            @foreach($cards->take(6) as $c)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $c->name }}</span>
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-indigo-700">{{ number_format((float) $c->price, 2) }}</span>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('dashboard.admin.gifts.cards.toggle-active', $c) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-xs">
                                                    {{ ($c->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('dashboard.admin.gifts.cards.delete', $c) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs text-red-600" onclick="return confirm('حذف البطاقة؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-4 text-sm">لا توجد بيانات</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
