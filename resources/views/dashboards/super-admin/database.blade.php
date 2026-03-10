@extends('dashboards.layouts.app')
@section('content')
@php $title = 'ملخص صحة قاعدة البيانات'; $subtitle = 'الحجم، الاستعلامات البطيئة، النسخ الاحتياطية'; @endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-gray-500 text-xs font-semibold">حجم قاعدة البيانات</p>
            <h3 class="text-xl font-black text-gray-800">{{ number_format($summary['database_size_mb'] ?? 0, 2) }} MB</h3>
        </div>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-gray-500 text-xs font-semibold">استعلامات بطيئة</p>
            <h3 class="text-xl font-black text-gray-800">{{ number_format($summary['slow_queries'] ?? 0) }}</h3>
        </div>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-gray-500 text-xs font-semibold">إجمالي النسخ الاحتياطية</p>
            <h3 class="text-xl font-black text-gray-800">{{ number_format($summary['backups_total'] ?? 0) }}</h3>
        </div>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <p class="text-gray-500 text-xs font-semibold">آخر نسخة احتياطية</p>
            <h3 class="text-xl font-black text-gray-800">{{ optional($summary['last_backup'])->completed_at ?? '-' }}</h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">ملاحظات</h3>
    <p class="text-sm text-gray-600">يتم توفير المزيد من التفاصيل في لوحة تقنية المعلومات.</p>
</div>
@endsection
