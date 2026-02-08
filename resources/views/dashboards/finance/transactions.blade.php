@extends('dashboards.layouts.app')
@section('content')
@php $title = 'المعاملات المالية'; $subtitle = 'بحث وتصفية وتصدير جميع المعاملات'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" id="new-transaction">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">إنشاء معاملة</h3>
    </div>
    <form method="POST" action="{{ route('dashboard.finance.transactions.create') }}" class="grid grid-cols-1 md:grid-cols-8 gap-3">
        @csrf
        <select name="type" required class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @foreach(['expense','adjustment','fee','commission','refund','order_payment','payout','payroll'] as $t)
                <option value="{{ $t }}" @selected(old('type') === $t)>{{ $t }}</option>
            @endforeach
        </select>
        <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" required placeholder="المبلغ" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="currency" value="{{ old('currency','USD') }}" placeholder="العملة" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="description" value="{{ old('description') }}" required placeholder="الوصف" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @foreach(['completed','pending','pending_approval','processing','failed','cancelled'] as $s)
                <option value="{{ $s }}" @selected(old('status','completed') === $s)>{{ $s }}</option>
            @endforeach
        </select>
        <button class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-plus"></i>
            <span>إنشاء</span>
        </button>

        <select name="store_id" class="w-full md:col-span-3 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">المتجر (اختياري)</option>
            @foreach(($stores ?? []) as $s)
                @php $storeName = data_get($s, 'name'); @endphp
                <option value="{{ $s->id }}" @selected(old('store_id') == $s->id)>{{ is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName }}</option>
            @endforeach
        </select>
        <input name="user_id" value="{{ old('user_id') }}" placeholder="User ID (اختياري)" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="order_id" value="{{ old('order_id') }}" placeholder="Order ID (اختياري)" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="category" value="{{ old('category') }}" placeholder="Category (اختياري)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">

        <select name="employee_id" class="w-full md:col-span-3 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الموظف (اختياري)</option>
            @foreach(($employees ?? []) as $e)
                <option value="{{ $e->id }}" @selected(old('employee_id') == $e->id)>{{ ($e->first_name ?? '') . ' ' . ($e->last_name ?? '') }}</option>
            @endforeach
        </select>
        <div class="md:col-span-5"></div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" action="{{ route('dashboard.finance.transactions') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="بحث: رقم المعاملة / الوصف" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <select name="type" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">النوع</option>
            @foreach(($transactionTypes ?? []) as $t)
                <option value="{{ $t }}" @selected(request('type') == $t)>{{ $t }}</option>
            @endforeach
        </select>
        <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الحالة</option>
            @foreach(($transactionStatuses ?? []) as $s)
                <option value="{{ $s }}" @selected(request('status') == $s)>{{ $s }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <div class="flex gap-2">
            <button class="flex-1 inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تصفية</span>
            </button>
            <a href="{{ route('dashboard.finance.transactions') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
        </div>
    </form>

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('dashboard.finance.transactions.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white hover:bg-black">
            <i class="fas fa-file-csv"></i>
            <span>CSV</span>
        </a>
        <a href="{{ route('dashboard.finance.transactions.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700">
            <i class="fas fa-file-pdf"></i>
            <span>PDF</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة المعاملات</h3>
        <span class="text-sm text-gray-500">{{ $transactions->total() }} عنصر</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">المعرف</th>
                    <th class="text-right py-2">النوع</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">المبلغ</th>
                    <th class="text-right py-2">العميل</th>
                    <th class="text-right py-2">الطلب</th>
                    <th class="text-right py-2">المتجر</th>
                    <th class="text-right py-2">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tr)
                    <tr class="border-t border-gray-100">
                        <td class="py-3 text-gray-900 font-semibold">{{ $tr->transaction_id }}</td>
                        <td class="py-3 text-gray-700">
                            <span class="px-2 py-1 rounded text-xs {{ $tr->type_color ?? 'text-gray-600 bg-gray-100' }}">{{ $tr->type }}</span>
                        </td>
                        <td class="py-3 text-gray-700">
                            <span class="px-2 py-1 rounded text-xs {{ $tr->status_color ?? 'text-gray-600 bg-gray-100' }}">{{ $tr->status }}</span>
                        </td>
                        <td class="py-3 text-gray-900 font-semibold">{{ number_format((float) ($tr->amount ?? 0), 2) }} {{ $tr->currency ?? 'USD' }}</td>
                        <td class="py-3 text-gray-700">{{ optional($tr->user)->email ?? '-' }}</td>
                        <td class="py-3 text-gray-700">{{ optional($tr->order)->order_number ?? '-' }}</td>
                        <td class="py-3 text-gray-700">{{ optional($tr->store)->name ?? '-' }}</td>
                        <td class="py-3 text-gray-500">{{ $tr->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-10 text-center text-gray-500">لا توجد نتائج</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>
@endsection
