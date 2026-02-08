@extends('dashboards.layouts.app')
@section('content')
@php $title = 'سجلات الأداء'; $subtitle = 'عرض وإنشاء تقييمات الأداء'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="POST" action="{{ route('dashboard.hr.performance.reviews.create') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        @csrf
        <input type="number" name="employee_id" placeholder="رقم الموظف" class="form-input" required>
        <input type="text" name="review_period" placeholder="فترة المراجعة (YYYY-MM)" class="form-input" required>
        <input type="number" name="reviewer_id" placeholder="رقم المراجع" class="form-input" required>
        <input type="number" step="0.1" min="1" max="5" name="overall_rating" placeholder="التقييم (1-5)" class="form-input" required>
        <input type="text" name="comments" placeholder="ملاحظات" class="form-input" required>
        <div class="md:col-span-5">
            <button type="submit" class="btn btn-primary btn-sm">إنشاء تقييم</button>
        </div>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">قائمة التقييمات</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الفترة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">التقييم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ optional($review->employee->user)->name ?? ('#'.$review->employee_id) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $review->review_period }}</td>
                        <td class="px-4 py-3 text-sm">{{ $review->overall_rating }}</td>
                        <td class="px-4 py-3 text-sm">{{ $review->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $reviews->links() }}</div>
    </div>
@endsection

