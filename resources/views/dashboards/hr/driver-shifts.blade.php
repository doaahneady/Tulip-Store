@extends('dashboards.layouts.app')
@section('content')
@php $title = 'ورديات السائقين'; $subtitle = 'إدارة ومراجعة الورديات الخاصة بالسائقين'; @endphp
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجل الورديات</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">السائق</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">التاريخ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">من</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">إلى</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($driverShifts as $shift)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($shift->employee->user)->name ?? ('#'.$shift->employee_id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ optional($shift->shift_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $shift->start_time }}</td>
                        <td class="px-4 py-3 text-sm">{{ $shift->end_time }}</td>
                        <td class="px-4 py-3 text-sm">{{ $shift->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $driverShifts->links() }}</div>
    </div>
@endsection

