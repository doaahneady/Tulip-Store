@extends('dashboards.layouts.app')
@section('content')
@php
    $title = 'Dashboard V4 Mockups';
    $subtitle = 'High-fidelity components and interaction patterns';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-3xl border border-gray-200 bg-white/90 backdrop-blur shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold text-gray-500">Overview</div>
                    <div class="text-2xl font-black text-gray-900">Admin Dashboard</div>
                    <div class="text-sm text-gray-600 mt-1">Clear hierarchy, fewer competing colors, consistent spacing.</div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="#" class="inline-flex items-center gap-2 rounded-2xl px-4 h-11 border border-gray-200 bg-white hover:bg-gray-50 font-semibold text-gray-800">
                        <i class="fas fa-filter"></i><span>Filters</span>
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 rounded-2xl px-4 h-11 border border-transparent bg-[#0D464C] text-white hover:bg-[#0b3a3f] font-semibold">
                        <i class="fas fa-plus"></i><span>New</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <div class="text-xs font-bold text-gray-500">Orders</div>
                    <div class="mt-2 text-2xl font-black text-gray-900">1,284</div>
                    <div class="mt-2 text-sm font-semibold text-emerald-700 bg-emerald-50 inline-flex px-2 py-1 rounded-xl">+8.3%</div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <div class="text-xs font-bold text-gray-500">Revenue</div>
                    <div class="mt-2 text-2xl font-black text-gray-900">14.2M</div>
                    <div class="mt-2 text-sm font-semibold text-amber-700 bg-amber-50 inline-flex px-2 py-1 rounded-xl">Today</div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <div class="text-xs font-bold text-gray-500">Tickets</div>
                    <div class="mt-2 text-2xl font-black text-gray-900">42</div>
                    <div class="mt-2 text-sm font-semibold text-rose-700 bg-rose-50 inline-flex px-2 py-1 rounded-xl">Needs review</div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <div class="text-xs font-bold text-gray-500">Inventory</div>
                    <div class="mt-2 text-2xl font-black text-gray-900">9</div>
                    <div class="mt-2 text-sm font-semibold text-gray-700 bg-gray-100 inline-flex px-2 py-1 rounded-xl">Low stock</div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white/90 backdrop-blur shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <div class="text-lg font-black text-gray-900">Latest Orders</div>
                    <div class="text-sm text-gray-600">Actions grouped, status is scannable, table is responsive.</div>
                </div>
                <div class="flex gap-2">
                    <button class="inline-flex items-center gap-2 rounded-2xl px-4 h-11 border border-gray-200 bg-white hover:bg-gray-50 font-semibold text-gray-800">
                        <i class="fas fa-download"></i><span>Export</span>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-right px-5 py-3 font-bold">Order</th>
                            <th class="text-right px-5 py-3 font-bold">Customer</th>
                            <th class="text-right px-5 py-3 font-bold">Total</th>
                            <th class="text-right px-5 py-3 font-bold">Status</th>
                            <th class="text-right px-5 py-3 font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach([['#1024','Ahmad','$54.20','Paid','emerald'],['#1025','Sara','$18.00','Pending','amber'],['#1026','Khaled','$92.10','Issue','rose']] as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $row[0] }}</td>
                                <td class="px-5 py-4 text-gray-800">{{ $row[1] }}</td>
                                <td class="px-5 py-4 font-semibold text-gray-900">{{ $row[2] }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-xl text-xs font-black bg-{{ $row[4] }}-50 text-{{ $row[4] }}-700">{{ $row[3] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-2">
                                        <button class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50" aria-label="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50" aria-label="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-3xl border border-gray-200 bg-white/90 backdrop-blur shadow-sm p-6">
            <div class="text-lg font-black text-gray-900">Pattern Library</div>
            <div class="text-sm text-gray-600 mt-1">Same buttons, inputs, and cards across roles.</div>
            <div class="mt-4 space-y-3">
                <div class="db4-search flex">
                    <i class="fas fa-magnifying-glass" aria-hidden="true" style="color: rgba(15, 23, 42, 0.45);"></i>
                    <input type="search" placeholder="Search pattern..." aria-label="Search pattern">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button class="db4-action-btn"><i class="fas fa-bolt"></i><span>Quick</span></button>
                    <button class="db4-action-btn"><i class="fas fa-gear"></i><span>Settings</span></button>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white/90 backdrop-blur shadow-sm p-6">
            <div class="text-lg font-black text-gray-900">Usability Checklist</div>
            <div class="mt-3 space-y-2 text-sm text-gray-700">
                <div class="flex items-start gap-2"><i class="fas fa-check text-emerald-600 mt-0.5"></i><span>Primary actions visible above the fold.</span></div>
                <div class="flex items-start gap-2"><i class="fas fa-check text-emerald-600 mt-0.5"></i><span>Tables support keyboard navigation and clear focus.</span></div>
                <div class="flex items-start gap-2"><i class="fas fa-check text-emerald-600 mt-0.5"></i><span>Statuses use color + text (not color only).</span></div>
                <div class="flex items-start gap-2"><i class="fas fa-check text-emerald-600 mt-0.5"></i><span>Touch targets >= 40px for mobile.</span></div>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 rounded-3xl border border-gray-200 bg-white/90 backdrop-blur shadow-sm p-6" role="region" aria-labelledby="growthMockHeading">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <div id="growthMockHeading" class="text-lg font-black text-gray-900">نمو النظام (Mockup)</div>
            <div class="text-sm text-gray-600 mt-1">تسلسل بصري واضح، ألوان عالية التباين، وأرقام باتجاه LTR.</div>
        </div>
        <div class="text-sm text-gray-500">WCAG-friendly</div>
    </div>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="p-4 rounded-2xl text-center border border-blue-100 bg-gradient-to-b from-blue-50 to-white" aria-label="نمو المستخدمين">
            <div class="text-xs font-bold text-gray-600">نمو المستخدمين</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 leading-none"><span dir="ltr">100.0%</span></div>
        </div>
        <div class="p-4 rounded-2xl text-center border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white" aria-label="نمو الإيرادات">
            <div class="text-xs font-bold text-gray-600">نمو الإيرادات</div>
            <div class="mt-2 text-3xl font-extrabold text-emerald-700 leading-none"><span dir="ltr">100.0%</span></div>
        </div>
        <div class="p-4 rounded-2xl text-center border border-orange-100 bg-gradient-to-b from-orange-50 to-white" aria-label="نمو الطلبات">
            <div class="text-xs font-bold text-gray-600">نمو الطلبات</div>
            <div class="mt-2 text-3xl font-extrabold text-orange-700 leading-none"><span dir="ltr">100.0%</span></div>
        </div>
    </div>
</div>
@endsection
