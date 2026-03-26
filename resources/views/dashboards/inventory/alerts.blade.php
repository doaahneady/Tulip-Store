@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تنبيهات نقص المخزون'; $subtitle = 'المنتجات التي وصلت لحد التنبيه وإعادة التوريد'; @endphp

<div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-bold text-gray-900">قائمة النقص</h3>
        <span class="text-sm font-medium px-3 py-1 bg-gray-100 text-gray-600 rounded-full">{{ $lowStockProducts->count() }} منتج</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-separate border-spacing-y-3">
            <thead>
                <tr class="text-gray-500 uppercase tracking-wider text-xs">
                    <th class="text-right py-4 px-4 font-semibold">#</th>
                    <th class="text-right py-4 px-4 font-semibold">المنتج</th>
                    <th class="text-right py-4 px-4 font-semibold">المخزون</th>
                    <th class="text-right py-4 px-4 font-semibold">حد التنبيه</th>
                    <th class="text-right py-4 px-4 font-semibold">تواصل</th>
                    <!-- <th class="text-right py-4 px-4 font-semibold">السجل</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockProducts as $p)
                    <tr class="bg-gray-50/50 hover:bg-gray-50 transition-colors rounded-xl overflow-hidden">
                        <td class="py-5 px-4 text-gray-700 font-medium rounded-r-xl">{{ $p->id }}</td>
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                                    @if(!empty($p->image))
                                        <img src="{{ $p->image }}" class="w-full h-full object-cover" alt="{{ $p->name }}">
                                    @else
                                        <i class="fas fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-900 truncate text-base">{{ $p->name }}</div>
                                    <div class="text-xs text-indigo-600 font-medium truncate">
                                        {{ $p->trader->account_name_en ?? $p->store->name ?? $p->trader->company_name ?? 'بدون متجر' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                {{ (int) $p->stock_quantity }}
                            </span>
                        </td>
                        <td class="py-5 px-4 text-gray-700 font-medium">{{ (int) $p->low_stock_threshold }}</td>
                        <td class="py-5 px-4">
                            @php 
                                $phone = $p->trader->phone ?? $p->trader->contact_phone ?? $p->store->phone ?? '';
                                $whatsapp_url = $phone ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) : null;
                            @endphp
                            @if($whatsapp_url)
                                <a href="{{ $whatsapp_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition-all border border-emerald-100">
                                    <i class="fab fa-whatsapp text-sm"></i>
                                    <span>واتساب</span>
                                </a>
                            @else
                                <span class="text-gray-400 text-xs italic">لا يتوفر رقم</span>
                            @endif
                        </td>
                        <!-- <td class="py-5 px-4 rounded-l-xl">
                            <a href="{{ route('dashboard.admin.inventory.history', $p->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                                <i class="fas fa-history text-gray-400"></i>
                                <span>السجل</span>
                            </a>
                        </td> -->
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-check-circle fa-3x"></i>
                                </div>
                                <p class="text-gray-500 font-medium">لا يوجد منتجات تحت حد التنبيه حالياً</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

