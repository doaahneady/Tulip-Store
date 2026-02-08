@extends('dashboards.layouts.app')
@section('content')
@php $title = 'المصروفات'; $subtitle = 'إدارة المصروفات وتتبعها'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6" id="new-expense">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">إضافة مصروف</h3>
    </div>
    <form method="POST" action="{{ route('dashboard.finance.expenses.create') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        @csrf
        <input name="description" value="{{ old('description') }}" required placeholder="الوصف" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="category" value="{{ old('category') }}" placeholder="الفئة (اختياري)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" required placeholder="المبلغ" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input name="currency" value="{{ old('currency','USD') }}" placeholder="العملة" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <button class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
            <i class="fas fa-plus"></i>
            <span>إضافة</span>
        </button>

        <select name="store_id" class="w-full md:col-span-3 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">المتجر (اختياري)</option>
            @foreach(($stores ?? []) as $s)
                @php $storeName = data_get($s, 'name'); @endphp
                <option value="{{ $s->id }}" @selected(old('store_id') == $s->id)>{{ is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName }}</option>
            @endforeach
        </select>
        <select name="employee_id" class="w-full md:col-span-2 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الموظف (اختياري)</option>
            @foreach(($employees ?? []) as $e)
                <option value="{{ $e->id }}" @selected(old('employee_id') == $e->id)>{{ ($e->first_name ?? '') . ' ' . ($e->last_name ?? '') }}</option>
            @endforeach
        </select>
        <div class="md:col-span-1"></div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <x-dashboard.stat-card title="إجمالي المصروفات" :value="'$'.number_format($expenseStats['total_expenses'] ?? 0, 2)" icon="fas fa-wallet" color="red" />
    <x-dashboard.stat-card title="مصروفات الشهر" :value="'$'.number_format($expenseStats['monthly_expenses'] ?? 0, 2)" icon="fas fa-calendar-alt" color="orange" />
    <x-dashboard.stat-card title="متوسط المصروف" :value="'$'.number_format($expenseStats['avg_expense'] ?? 0, 2)" icon="fas fa-chart-line" color="indigo" />
    <x-dashboard.stat-card title="الفئات" :value="number_format(count($expenseStats['expense_categories'] ?? []))" icon="fas fa-tags" color="gray" />
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('dashboard.finance.expenses') }}" class="grid grid-cols-1 md:grid-cols-7 gap-3">
        <input name="category" value="{{ request('category') }}" placeholder="الفئة (اختياري)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <select name="store_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">المتجر</option>
            @foreach(($stores ?? []) as $s)
                @php $storeName = data_get($s, 'name'); @endphp
                <option value="{{ $s->id }}" @selected((string) request('store_id') === (string) $s->id)>{{ is_array($storeName) ? json_encode($storeName, JSON_UNESCAPED_UNICODE) : $storeName }}</option>
            @endforeach
        </select>
        <select name="employee_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الموظف</option>
            @foreach(($employees ?? []) as $e)
                <option value="{{ $e->id }}" @selected((string) request('employee_id') === (string) $e->id)>{{ ($e->first_name ?? '') . ' ' . ($e->last_name ?? '') }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <button class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-filter"></i>
            <span>تصفية</span>
        </button>
        <a href="{{ route('dashboard.finance.expenses') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
    </form>
    </div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة المصروفات</h3>
        <span class="text-sm text-gray-500">{{ $expenses->total() }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">المعرف</th>
                    <th class="text-right py-2">الوصف</th>
                    <th class="text-right py-2">المبلغ</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">تاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                    <tr class="border-t border-gray-100">
                        <td class="py-3 text-gray-900 font-semibold">{{ $e->transaction_id }}</td>
                        <td class="py-3 text-gray-700">{{ $e->description }}</td>
                        <td class="py-3 text-gray-900 font-semibold">{{ number_format((float) ($e->amount ?? 0), 2) }} {{ $e->currency ?? 'USD' }}</td>
                        <td class="py-3 text-gray-700">{{ $e->status }}</td>
                        <td class="py-3 text-gray-500">{{ $e->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-10 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $expenses->withQueryString()->links() }}
    </div>
</div>
@endsection
