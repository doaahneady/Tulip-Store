@extends('dashboards.layouts.app')
@section('content')
@php $title = 'التوظيف والانضمام'; $subtitle = 'إدارة الوظائف والطلبات'; @endphp
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">الوظائف المفتوحة</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المسمى الوظيفي</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">القسم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">عدد الطلبات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($positions as $pos)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $pos->title ?? 'وظيفة' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pos->department ?? 'غير محدد' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pos->status }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pos->applications_count ?? 0 }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $positions->links() }}</div>
    </div>
@endsection

