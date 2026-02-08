@extends('layouts.dashboard')

@section('title', 'سجلات الأداء')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">المراجعات</h3>
        <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">الفترة</th>
                    <th class="px-4 py-2 text-left">التقييم</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $rev)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $rev->employee->user->name ?? 'غير معروف' }}</td>
                    <td class="px-4 py-2">{{ $rev->review_period }}</td>
                    <td class="px-4 py-2">{{ $rev->overall_rating ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $rev->status ?? 'draft' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($reviews ?? null),'links'))
            {{ $reviews->links() }}
        @endif
    </div>
</div>
@endsection

