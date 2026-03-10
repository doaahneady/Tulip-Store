@extends('dashboards.layouts.app')
@section('content')
@php $title = 'أداء قاعدة البيانات'; $subtitle = 'مؤشرات صحة وأداء الاستعلامات'; @endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="text-gray-500 text-xs font-semibold">الحالة</div>
            <div class="text-2xl font-black {{ ($databaseHealth['status'] ?? 'ok') === 'ok' ? 'text-emerald-700' : 'text-red-700' }}">
                {{ ($databaseHealth['status'] ?? 'ok') === 'ok' ? 'جيدة' : 'تحذير' }}
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="text-gray-500 text-xs font-semibold">متوسط زمن الاستعلام</div>
            <div class="text-2xl font-black text-gray-900">{{ number_format((float) ($queryStats['avg_ms'] ?? 0), 1) }} ms</div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="text-gray-500 text-xs font-semibold">استعلامات بطيئة</div>
            <div class="text-2xl font-black text-gray-900">{{ number_format((int) ($queryStats['slow_count'] ?? 0)) }}</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">الاستعلامات البطيئة</h3>
        <a href="{{ route('dashboard.it.index') }}" class="btn btn-ghost btn-sm">عودة</a>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الوقت</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المدة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الاستعلام</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($slowQueries ?? []) as $q)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $q->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ number_format((float) ($q->duration_ms ?? 0), 1) }} ms</td>
                        <td class="px-4 py-3 text-gray-900">{{ $q->query ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists(($slowQueries ?? null), 'links'))
            <div class="mt-4">
                {{ $slowQueries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
