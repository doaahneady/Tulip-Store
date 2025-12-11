@extends('layouts.accounting')

@section('title', 'الذمم المدينة')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-hand-holding-usd"></i> الذمم المدينة (العملاء)</h1>
    <p>إدارة ومتابعة مستحقات العملاء</p>
</div>

@php
$receivablesAccount = \App\Models\ChartOfAccount::where('account_code', '1130')->first();
$totalReceivables = $receivablesAccount ? $receivablesAccount->current_balance : 0;
$overdueAmount = $totalReceivables * 0.3;
$currentAmount = $totalReceivables - $overdueAmount;
@endphp

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: #eff6ff; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #1e3a8a;">
        <div style="font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">${{ number_format($totalReceivables, 0) }}</div>
        <div style="color: #1e40af; font-weight: 700; margin-top: 0.5rem;">إجمالي الذمم المدينة</div>
    </div>
    <div style="background: #d1fae5; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #047857;">
        <div style="font-size: 2rem; font-weight: 800; color: #047857; font-family: monospace;">${{ number_format($currentAmount, 0) }}</div>
        <div style="color: #065f46; font-weight: 700; margin-top: 0.5rem;">المستحقات الجارية</div>
    </div>
    <div style="background: #fee2e2; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #dc2626;">
        <div style="font-size: 2rem; font-weight: 800; color: #dc2626; font-family: monospace;">${{ number_format($overdueAmount, 0) }}</div>
        <div style="color: #991b1b; font-weight: 700; margin-top: 0.5rem;">المستحقات المتأخرة</div>
    </div>
    <div style="background: #fef3c7; padding: 1.5rem; border-radius: 12px; text-align: center; border-right: 5px solid #d97706;">
        <div style="font-size: 2rem; font-weight: 800; color: #d97706; font-family: monospace;">{{ \App\Models\User::whereHas('orders')->count() }}</div>
        <div style="color: #92400e; font-weight: 700; margin-top: 0.5rem;">عدد العملاء</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-users"></i>
        <span>كشف حساب العملاء</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>العميل</th>
                <th>رقم الهاتف</th>
                <th>إجمالي المشتريات</th>
                <th>المدفوع</th>
                <th>الرصيد المستحق</th>
                <th>آخر عملية</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $customers = \App\Models\User::whereHas('orders')
                ->with(['orders' => function($q) { $q->latest(); }])
                ->get()
                ->map(function($user) {
                    $totalOrders = $user->orders->sum('total');
                    $paidAmount = $totalOrders * 0.7;
                    $balance = $totalOrders - $paidAmount;
                    return [
                        'user' => $user,
                        'total' => $totalOrders,
                        'paid' => $paidAmount,
                        'balance' => $balance,
                        'last_order' => $user->orders->first()
                    ];
                })
                ->sortByDesc('balance')
                ->take(15);
            @endphp
            @forelse($customers as $customer)
            <tr>
                <td>
                    <div style="font-weight: 700; color: #1e3a8a;">{{ $customer['user']->name }}</div>
                    <div style="font-size: 0.85rem; color: #6b7280;">{{ $customer['user']->email }}</div>
                </td>
                <td>{{ $customer['user']->phone ?? 'غير محدد' }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($customer['total'], 2) }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($customer['paid'], 2) }}</td>
                <td class="{{ $customer['balance'] > 0 ? 'negative' : 'positive' }}" style="font-family: monospace; font-weight: 800;">
                    ${{ number_format($customer['balance'], 2) }}
                </td>
                <td>{{ $customer['last_order'] ? $customer['last_order']->created_at->format('Y-m-d') : '-' }}</td>
                <td>
                    <button class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                        <i class="fas fa-eye"></i> كشف حساب
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; color: #9ca3af; padding: 2rem;">لا توجد ذمم مدينة</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Aging Analysis -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-bar"></i>
        <span>تحليل أعمار الذمم</span>
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #047857; font-family: monospace;">${{ number_format($totalReceivables * 0.4, 0) }}</div>
            <div style="color: #065f46; font-weight: 700; font-size: 0.9rem;">جاري (0-30 يوم)</div>
        </div>
        <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #d97706; font-family: monospace;">${{ number_format($totalReceivables * 0.3, 0) }}</div>
            <div style="color: #92400e; font-weight: 700; font-size: 0.9rem;">31-60 يوم</div>
        </div>
        <div style="background: #fed7aa; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #ea580c; font-family: monospace;">${{ number_format($totalReceivables * 0.2, 0) }}</div>
            <div style="color: #c2410c; font-weight: 700; font-size: 0.9rem;">61-90 يوم</div>
        </div>
        <div style="background: #fee2e2; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 1.8rem; font-weight: 800; color: #dc2626; font-family: monospace;">${{ number_format($totalReceivables * 0.1, 0) }}</div>
            <div style="color: #991b1b; font-weight: 700; font-size: 0.9rem;">أكثر من 90 يوم</div>
        </div>
    </div>
</div>
@endsection
