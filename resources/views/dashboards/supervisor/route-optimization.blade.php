@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تحسين المسارات'; $subtitle = 'مسارات نشطة وتحسينات'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">مسارات نشطة</h3>
        </div>
        <ul class="divide-y divide-gray-200">
            @foreach($activeRoutes as $route)
                <li class="py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">{{ optional($route->driver->user)->name ?? $route->driver->name ?? '—' }}</div>
                            <div class="text-sm text-gray-500">تاريخ: {{ optional($route->route_date)->format('Y-m-d') ?? '—' }}</div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $route->status }}</span>
                    </div>
                </li>
            @endforeach
            @if($activeRoutes->count()===0)
                <li class="py-3 text-center text-sm text-gray-500">لا توجد مسارات</li>
            @endif
        </ul>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">تحسين جماعي</h3>
        <form id="optimize-form" class="space-y-3">
            <select multiple id="driver-ids" class="border rounded-xl px-3 py-2 w-full h-40">
                @foreach(\App\Models\Driver::where('status','active')->get() as $d)
                    <option value="{{ $d->id }}">{{ optional($d->user)->name ?? $d->name }}</option>
                @endforeach
            </select>
            <button type="button" id="optimize-btn" class="px-4 py-2 bg-indigo-600 text-white rounded-md w-full">تحسين المسارات</button>
        </form>
        <div id="optimize-result" class="mt-4 text-sm text-gray-700"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('optimize-btn').addEventListener('click', function() {
        const select = document.getElementById('driver-ids');
        const ids = Array.from(select.options).filter(o => o.selected).map(o => o.value);
        fetch('{{ route('dashboard.supervisor.optimize-routes') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ driver_ids: ids })
        }).then(r => r.json()).then(res => {
            document.getElementById('optimize-result').textContent = res.success ? 'تم التحسين' : 'فشل التحسين';
        });
    });
</script>
@endpush
