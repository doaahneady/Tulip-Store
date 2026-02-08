@extends('dashboards.layouts.app')
@section('content')
@php $title = 'أخطاء API'; $subtitle = 'عرض ملخص الأخطاء والطلبات المتأثرة'; @endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">إجمالي الأخطاء</div>
        <div class="text-2xl font-bold mt-1 text-gray-900">{{ number_format((int) ($errorStats['total_errors'] ?? 0)) }}</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">أكثر Endpoint</div>
        <div class="text-2xl font-bold mt-1 text-gray-900">{{ $errorStats['top_endpoint'] ?? '-' }}</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">آخر تحديث</div>
        <div class="text-2xl font-bold mt-1 text-gray-900">{{ $errorStats['updated_at'] ?? '-' }}</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجل الأخطاء</h3>
        <a href="{{ route('dashboard.it.index') }}" class="btn btn-ghost btn-sm">عودة</a>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الوقت</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">Endpoint</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الكود</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الرسالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($errors ?? []) as $e)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $e->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $e->endpoint ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $e->status_code ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $e->message ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists(($errors ?? null), 'links'))
            <div class="mt-4">
                {{ $errors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

