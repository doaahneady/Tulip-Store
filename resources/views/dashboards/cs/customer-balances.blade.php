@extends('dashboards.layouts.app')
@section('content')
@php($title = 'أرصدة العملاء')
@php($subtitle = 'عرض أرصدة العملاء وتعديلها')

<div class="bg-white rounded-2xl p-6 shadow-sm mb-6 border border-gray-100">
    <form method="GET" class="flex flex-col md:flex-row gap-3">
        <div class="flex-1">
            <label class="text-sm text-gray-600">بحث</label>
            <input name="search" value="{{ request('search') }}" class="w-full px-3 py-2 border rounded-lg" placeholder="اسم / بريد / اسم مستخدم">
        </div>
        <div class="flex items-end gap-2">
            <button class="px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">بحث</button>
            <a href="{{ route('dashboard.cs.customer-balances') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200">مسح</a>
        </div>
    </form>
</div>

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-right text-gray-600">
                    <th class="py-3 px-4">العميل</th>
                    <th class="py-3 px-4">البريد</th>
                    <th class="py-3 px-4">الرصيد</th>
                    <th class="py-3 px-4">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    <tr class="border-t border-gray-100">
                        <td class="py-3 px-4 font-semibold text-gray-900">{{ $c->name ?? ('User #'.$c->id) }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $c->email ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-900 font-bold">{{ number_format((float) ($c->balance ?? 0), 2) }}</td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('dashboard.cs.customers.balance.adjust', $c) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="action" value="add">
                                    <input name="amount" inputmode="decimal" placeholder="Amount" class="w-28 px-3 py-2 border rounded-lg" required>
                                    <button class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Add</button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.cs.customers.balance.adjust', $c) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="action" value="deduct">
                                    <input name="amount" inputmode="decimal" placeholder="Amount" class="w-28 px-3 py-2 border rounded-lg" required>
                                    <button class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Deduct</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-12 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $customers->withQueryString()->links() }}
    </div>
</div>
@endsection

