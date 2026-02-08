@extends('dashboards.layouts.app')
@section('content')
@php $title = 'النسخ الاحتياطية'; $subtitle = 'إدارة نسخ قاعدة البيانات'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">ملخص</h3>
        <form method="POST" action="{{ route('dashboard.it.backups.create') }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">إنشاء نسخة</button>
        </form>
    </div>
    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-50 rounded-xl p-4">
            <div class="text-gray-500 text-xs">إجمالي النسخ</div>
            <div class="text-2xl font-bold mt-1 text-gray-900">{{ number_format((int) ($backupStats['total'] ?? 0)) }}</div>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <div class="text-gray-500 text-xs">آخر نسخة</div>
            <div class="text-2xl font-bold mt-1 text-gray-900">{{ $backupStats['last_backup_at'] ?? '-' }}</div>
        </div>
        <div class="bg-gray-50 rounded-xl p-4">
            <div class="text-gray-500 text-xs">الحجم الكلي</div>
            <div class="text-2xl font-bold mt-1 text-gray-900">{{ $backupStats['total_size'] ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">السجل</h3>
        <a href="{{ route('dashboard.it.index') }}" class="btn btn-ghost btn-sm">عودة</a>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">التاريخ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">النوع</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحجم</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($backups ?? []) as $b)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $b->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $b->type ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ ($b->status ?? '') === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $b->status ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-900">{{ $b->size ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists(($backups ?? null), 'links'))
            <div class="mt-4">
                {{ $backups->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

