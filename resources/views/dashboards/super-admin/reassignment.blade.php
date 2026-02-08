@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إعادة التعيين اليدوية'; $subtitle = 'تعيين الطلبات للسائقين وتعيين التذاكر للوكلاء'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">طلبات بحاجة لتعيين سائق</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($orders as $order)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <p class="text-gray-800 font-semibold">#{{ $order->order_number ?? $order->id }}</p>
                            <p class="text-gray-500">{{ is_array(data_get($order,'store.name')) ? json_encode(data_get($order,'store.name')) : (data_get($order,'store.name') ?? '-') }} - {{ is_array(data_get($order,'user.name')) ? json_encode(data_get($order,'user.name')) : (data_get($order,'user.name') ?? 'ضيف') }}</p>
                        </div>
                        <form method="POST" action="{{ route('dashboard.admin.reassignment.orders', $order) }}" class="flex gap-2">
                            @csrf
                            <select name="driver_id" class="px-2 py-1 border rounded">
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ is_array(data_get($driver,'user.name')) ? json_encode(data_get($driver,'user.name')) : (data_get($driver,'user.name') ?? (is_array($driver->name ?? null) ? json_encode($driver->name) : ($driver->name ?? ('Driver #'.$driver->id)))) }}</option>
                                @endforeach
                            </select>
                            <button class="bg-orange-600 text-white px-3 py-1 rounded">تعيين</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500">لا توجد طلبات بانتظار التعيين</p>
                @endforelse
            </div>
        </div>
        <div class="p-6">{{ $orders->links() }}</div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">تذاكر الدعم</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($tickets as $ticket)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <p class="text-gray-800 font-semibold">#{{ $ticket->id }} - {{ $ticket->subject ?? 'Ticket' }}</p>
                            <p class="text-gray-500">الحالة: {{ $ticket->status }}</p>
                        </div>
                        <form method="POST" action="{{ route('dashboard.admin.reassignment.tickets', $ticket) }}" class="flex gap-2">
                            @csrf
                            <select name="agent_id" class="px-2 py-1 border rounded">
                                @foreach($csAgents as $agent)
                                    <option value="{{ $agent->id }}">{{ is_array(data_get($agent,'name')) ? json_encode(data_get($agent,'name')) : (data_get($agent,'name') ?? ('Agent #'.$agent->id)) }}</option>
                                @endforeach
                            </select>
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded">تعيين</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500">لا توجد تذاكر</p>
                @endforelse
            </div>
        </div>
        <div class="p-6">{{ $tickets->links() }}</div>
    </div>
</div>
@endsection
