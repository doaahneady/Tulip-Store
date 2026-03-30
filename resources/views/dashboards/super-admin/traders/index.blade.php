@extends('dashboards.layouts.app', ['title' => $title ?? 'إدارة التجار', 'subtitle' => $subtitle ?? 'عرض ومراجعة ملفات التجار'])
@php($indexRoute = $indexRoute ?? 'dashboard.admin.traders')
@php($showRoute = $showRoute ?? 'dashboard.admin.traders.show')
@php($heading = $heading ?? 'التجار')
@php($searchPlaceholder = $searchPlaceholder ?? 'ابحث بالاسم أو البريد')
@php($emptyState = $emptyState ?? 'لا يوجد تجار')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-900">{{ $heading }}</h3>
            <form method="GET" action="{{ route($indexRoute) }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}" class="form-input w-56">
                <select name="status" class="form-select w-44">
                    <option value="">كل الحالات</option>
                    @foreach(($statusOptions ?? []) as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-ghost btn-sm">
                    <i class="fas fa-filter"></i>
                    تصفية
                </button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>التاجر</th>
                    <th>البريد الإلكتروني</th>
                    <th>الهاتف</th>
                    <th>الحالة</th>
                    <th>تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody>
                @forelse($traders ?? [] as $trader)
                    <tr>
                        <td>
                            <a href="{{ route($showRoute, $trader) }}" class="font-semibold text-indigo-700 hover:underline">
                                {{ $trader->name ?? 'Trader #'.$trader->id }}
                            </a>
                            @if(!empty($trader->company_name))
                                <div class="text-xs text-gray-500">{{ $trader->company_name }}</div>
                            @endif
                        </td>
                        <td>{{ $trader->email ?? $trader->contact_email ?? '-' }}</td>
                        <td>{{ $trader->phone ?? $trader->contact_phone ?? '-' }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $trader->status ?? '-' }}</span>
                        </td>
                        <td class="text-xs text-gray-500">{{ $trader->created_at?->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-8">{{ $emptyState }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ ($traders ?? collect())->links() }}
    </div>
</div>
@endsection