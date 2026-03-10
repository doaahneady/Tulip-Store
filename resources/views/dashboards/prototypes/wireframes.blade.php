@extends('dashboards.layouts.app')
@section('content')
@php
    $title = 'Dashboard V4 Wireframes';
    $subtitle = 'Low-fidelity layout and information architecture';
@endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold text-gray-500">Top Bar</div>
                    <div class="text-lg font-black text-gray-900">Page Title + Quick Search + Actions</div>
                    <div class="text-sm text-gray-600 mt-1">Sticky header, consistent actions placement, keyboard-friendly.</div>
                </div>
                <div class="grid gap-2 text-xs text-gray-600">
                    <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2">Notifications</div>
                    <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2">User Menu</div>
                </div>
            </div>
            <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="rounded-2xl border border-dashed border-gray-300 p-4">
                    <div class="text-xs font-semibold text-gray-500">KPI Card</div>
                    <div class="h-10 rounded-xl bg-gray-100 mt-2"></div>
                    <div class="h-6 rounded-lg bg-gray-100 mt-2 w-2/3"></div>
                </div>
                <div class="rounded-2xl border border-dashed border-gray-300 p-4">
                    <div class="text-xs font-semibold text-gray-500">KPI Card</div>
                    <div class="h-10 rounded-xl bg-gray-100 mt-2"></div>
                    <div class="h-6 rounded-lg bg-gray-100 mt-2 w-1/2"></div>
                </div>
                <div class="rounded-2xl border border-dashed border-gray-300 p-4">
                    <div class="text-xs font-semibold text-gray-500">KPI Card</div>
                    <div class="h-10 rounded-xl bg-gray-100 mt-2"></div>
                    <div class="h-6 rounded-lg bg-gray-100 mt-2 w-3/4"></div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold text-gray-500">Data Table</div>
                    <div class="text-lg font-black text-gray-900">Primary Work Area</div>
                    <div class="text-sm text-gray-600 mt-1">Filters above table, pagination consistent, row actions grouped.</div>
                </div>
                <div class="flex gap-2">
                    <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2 text-xs text-gray-600">Filters</div>
                    <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2 text-xs text-gray-600">Export</div>
                </div>
            </div>
            <div class="mt-4 rounded-2xl border border-dashed border-gray-300 overflow-hidden">
                <div class="h-10 bg-gray-100"></div>
                <div class="divide-y divide-gray-200">
                    <div class="h-12 bg-white"></div>
                    <div class="h-12 bg-white"></div>
                    <div class="h-12 bg-white"></div>
                    <div class="h-12 bg-white"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-6">
            <div class="text-sm font-semibold text-gray-500">Sidebar</div>
            <div class="text-lg font-black text-gray-900">Role-based Navigation</div>
            <div class="text-sm text-gray-600 mt-1">Grouped by jobs-to-be-done, consistent labels and icons.</div>
            <div class="mt-4 space-y-2">
                <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2 text-sm text-gray-600">Overview</div>
                <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2 text-sm text-gray-600">Orders</div>
                <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2 text-sm text-gray-600">Inventory</div>
                <div class="rounded-xl border border-dashed border-gray-300 px-3 py-2 text-sm text-gray-600">Users</div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white/80 backdrop-blur p-6">
            <div class="text-sm font-semibold text-gray-500">Accessibility</div>
            <div class="mt-2 space-y-2 text-sm text-gray-700">
                <div class="flex items-start gap-2"><span class="font-black">1.</span><span>Skip link + focus-visible rings.</span></div>
                <div class="flex items-start gap-2"><span class="font-black">2.</span><span>Clear hit targets (>= 40px).</span></div>
                <div class="flex items-start gap-2"><span class="font-black">3.</span><span>Reduced motion support.</span></div>
                <div class="flex items-start gap-2"><span class="font-black">4.</span><span>Contrast-aware tokens.</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

