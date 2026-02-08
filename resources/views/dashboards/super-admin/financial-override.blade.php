@extends('dashboards.layouts.app')
@section('content')
@php $title = 'Financial Override'; $subtitle = 'Edit transactions with audit logging'; @endphp
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">Transactions</h3>
        <a href="{{ route('dashboard.admin.approvals') }}" class="text-sm text-indigo-600">Back to Approvals</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $tx->transaction_id }}</td>
                        <td class="px-4 py-2">{{ $tx->type }}</td>
                        <td class="px-4 py-2">{{ number_format($tx->amount ?? 0, 2) }}</td>
                        <td class="px-4 py-2">{{ $tx->status }}</td>
                        <td class="px-4 py-2">{{ $tx->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('dashboard.admin.financial-override.update', $tx) }}" class="flex items-center gap-2">
                                @csrf
                                <input type="number" step="0.01" name="amount" value="{{ $tx->amount }}" class="form-input w-28" placeholder="Amount">
                                <select name="status" class="form-select w-36">
                                    <option value="">Status</option>
                                    @foreach(['pending','approved','completed','failed','refunded'] as $st)
                                        <option value="{{ $st }}" @selected($tx->status===$st)>{{ $st }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="description" value="{{ $tx->description }}" class="form-input w-44" placeholder="Description">
                                <button class="btn btn-secondary btn-sm">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($transactions ?? null),'links'))
            {{ $transactions->links() }}
        @endif
    </div>
</div>
@endsection
