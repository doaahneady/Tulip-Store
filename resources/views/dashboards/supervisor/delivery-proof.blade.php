@extends('dashboards.layouts.app')
@section('content')
@php $title = 'مراجعة إثبات التسليم'; $subtitle = 'التسليمات المكتملة والتحقق'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">تسليمات اليوم</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الطلب</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">السائق</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الوقت</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الحالة</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($completedDeliveries as $delivery)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900">#{{ optional($delivery->order)->order_number ?? $delivery->order_id }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ optional(optional($delivery->driver)->user)->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ optional($delivery->delivered_at)->format('H:i') }}</td>
                    <td class="px-4 py-3">
                        @if($delivery->verified_at)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">متحقق</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">غير متحقق</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 bg-green-600 text-white rounded-md text-xs" onclick="verify({{ $delivery->id }}, true)">تحقق</button>
                            <button class="px-3 py-1 bg-red-600 text-white rounded-md text-xs" onclick="verify({{ $delivery->id }}, false)">رفض</button>
                        </div>
                    </td>
                </tr>
            @endforeach
            @if($completedDeliveries->count()===0)
                <tr><td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد تسليمات</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="px-2 py-4">
        {{ $completedDeliveries->links() }}
    </div>
</div>

@push('scripts')
<script>
    function verify(id, verified) {
        const notes = verified ? '' : prompt('أدخل سبب الرفض');
        fetch('{{ url('/dashboard/supervisor/deliveries') }}/'+id+'/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ verified, notes })
        }).then(r => r.json()).then(() => location.reload());
    }
</script>
@endpush
@endsection
