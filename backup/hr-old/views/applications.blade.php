@extends('layouts.dashboard')

@section('title', 'إدارة طلبات التوظيف')

@section('content')

<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-bold text-gray-800">طلبات التوظيف</h3>
        </div>
        <div class="flex items-center gap-2">
            <!-- Filter options could go here -->
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">اسم المتقدم</th>
                    <th class="px-4 py-2 text-left">الوظيفة</th>
                    <th class="px-4 py-2 text-left">البريد الإلكتروني</th>
                    <th class="px-4 py-2 text-left">تاريخ التقديم</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr class="border-t hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2 font-medium">{{ $application->applicant_name }}</td>
                        <td class="px-4 py-2">{{ $application->position->title ?? 'غير محدد' }}</td>
                        <td class="px-4 py-2">{{ $application->applicant_email }}</td>
                        <td class="px-4 py-2">{{ $application->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">
                            @php
                                $statusColors = [
                                    'applied' => 'bg-gray-100 text-gray-700',
                                    'screening' => 'bg-blue-100 text-blue-700',
                                    'interview_scheduled' => 'bg-purple-100 text-purple-700',
                                    'interviewed' => 'bg-yellow-100 text-yellow-700',
                                    'offer_made' => 'bg-indigo-100 text-indigo-700',
                                    'hired' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];
                                $statusLabels = [
                                    'applied' => 'تم التقديم',
                                    'screening' => 'فرز',
                                    'interview_scheduled' => 'مقابلة مجدولة',
                                    'interviewed' => 'تمت المقابلة',
                                    'offer_made' => 'عرض وظيفي',
                                    'hired' => 'تم التعيين',
                                    'rejected' => 'مرفوض',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$application->status] ?? $application->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <button onclick="openStatusModal('{{ $application->id }}', '{{ $application->status }}')" 
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i> تحديث الحالة
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-file-alt text-4xl text-gray-300"></i>
                                <p>لا توجد طلبات توظيف حالياً</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $applications->links() }}
    </div>
</div>

<!-- Update Status Modal -->
<dialog id="updateStatusModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">تحديث حالة الطلب</h3>
        <form id="statusForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="label">الحالة الجديدة</label>
                    <select name="status" id="statusSelect" class="select select-bordered w-full" required>
                        <option value="applied">تم التقديم</option>
                        <option value="screening">فرز</option>
                        <option value="interview_scheduled">مقابلة مجدولة</option>
                        <option value="interviewed">تمت المقابلة</option>
                        <option value="offer_made">عرض وظيفي</option>
                        <option value="hired">تم التعيين</option>
                        <option value="rejected">مرفوض</option>
                    </select>
                </div>
                <div>
                    <label class="label">ملاحظات (اختياري)</label>
                    <textarea name="notes" class="textarea textarea-bordered w-full h-24" placeholder="ملاحظات حول تغيير الحالة..."></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('updateStatusModal').close()">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openStatusModal(applicationId, currentStatus) {
        const modal = document.getElementById('updateStatusModal');
        const form = document.getElementById('statusForm');
        const select = document.getElementById('statusSelect');
        
        // Update form action URL - using a placeholder that we replace
        form.action = "{{ route('dashboard.hr.applications.update-status', ':id') }}".replace(':id', applicationId);
        
        // Set current status
        select.value = currentStatus;
        
        modal.showModal();
    }
</script>
@endsection
