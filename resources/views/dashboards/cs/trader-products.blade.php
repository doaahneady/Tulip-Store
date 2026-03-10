@extends('dashboards.layouts.app')
@section('content')
@php $title = 'مراجعة منتجات التجار'; $subtitle = 'اعتماد أو رفض المنتجات والصور المرفوعة من التجار'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('dashboard.cs.trader-products') }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو SKU أو الخصائص" class="w-72 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <select name="attr_type" class="px-4 py-2 rounded-xl border border-gray-200">
                <option value="">نوع الخاصية (الكل)</option>
                @foreach(['text','textarea','select','multiselect','checkbox','radio','date','number','color'] as $t)
                    <option value="{{ $t }}" @selected((string) request('attr_type') === (string) $t)>{{ $t }}</option>
                @endforeach
            </select>
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">بحث</button>
        </div>
        <a href="{{ route('dashboard.cs.trader-products') }}" class="text-sm text-indigo-600 hover:underline">إعادة ضبط</a>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">منتجات بانتظار الاعتماد</h3>
        <span class="text-sm text-gray-500">{{ number_format($products->total()) }}</span>
    </div>

    <div class="space-y-4">
        @forelse($products as $p)
            @php
                $imgs = $p->images ?? [];
                if (is_string($imgs)) {
                    $decoded = json_decode($imgs, true);
                    $imgs = is_array($decoded) ? $decoded : [];
                }
                $firstImg = is_array($imgs) && count($imgs) > 0 ? $imgs[0] : null;
            @endphp
            <div class="border border-gray-200 rounded-2xl p-4">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center text-gray-400">
                            @php
                                $coverUrl = $p->primary_image_url ?? null;
                            @endphp
                            <img src="{{ $coverUrl ?: '/images/gift-placeholder.svg' }}" srcset="{{ $p->primary_image_srcset ?? '' }}" loading="lazy" class="w-full h-full object-cover" alt="صورة المنتج" onerror="this.onerror=null;this.src='/images/gift-placeholder.svg';">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 truncate">{{ $p->name }}</h4>
                                <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-800">pending</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Trader: {{ $p->trader?->name ?? ('#'.$p->trader_id) }} • Store: {{ $p->store?->name ?? '-' }} • SKU: {{ $p->sku ?? '-' }}
                            </div>
                            @if(is_array($imgs) && count($imgs) > 0)
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    @foreach(array_slice($imgs, 0, 6) as $img)
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden">
                                            @php
                                                $u = $img;
                                                if (!\Illuminate\Support\Str::startsWith($u, ['http://','https://','/'])) {
                                                    $u = \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($u,'/'));
                                                }
                                            @endphp
                                            <img src="{{ $u }}" class="w-full h-full object-cover" alt="صورة" onerror="this.onerror=null;this.src='/images/gift-placeholder.svg'">
                                        </div>
                                    @endforeach
                                    @if(count($imgs) > 6)
                                        <span class="text-xs text-gray-500">+{{ count($imgs) - 6 }}</span>
                                    @endif
                                </div>
                            @endif

                            @php
                                $attrs = collect();
                                if (isset($p->attributes) && $p->attributes instanceof \Illuminate\Support\Collection) {
                                    $attrs = $p->attributes->where('is_custom', true)->values();
                                }
                            @endphp
                            @if($attrs->count())
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full text-xs">
                                        <thead>
                                            <tr class="text-gray-500">
                                                <th class="text-right py-2">الاسم</th>
                                                <th class="text-right py-2">النوع</th>
                                                <th class="text-right py-2">القيمة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attrs as $a)
                                                <tr class="border-t border-gray-100">
                                                    <td class="py-2 text-gray-900 font-semibold">{{ $a->name }}</td>
                                                    <td class="py-2 text-gray-600">{{ $a->type }}</td>
                                                    <td class="py-2 text-gray-900">{{ $a->value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 lg:items-end">
                        <form method="POST" action="{{ route('dashboard.cs.trader-products.approve', $p) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
                                <i class="fas fa-check"></i>
                                <span>اعتماد</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.cs.trader-products.reject', $p) }}" class="w-full lg:w-80">
                            @csrf
                            <div class="flex items-center gap-2">
                                <input name="reason" required placeholder="سبب الرفض" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-200">
                                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition">
                                    <i class="fas fa-times"></i>
                                    <span>رفض</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-10 text-center text-gray-500">لا توجد منتجات بانتظار الاعتماد</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
