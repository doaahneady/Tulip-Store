@extends('layouts.accounting')

@section('title', 'الفواتير')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file-invoice"></i> إدارة الفواتير</h1>
    <p>فواتير المبيعات والمشتريات</p>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: #d1fae5; padding: 2rem; border-radius: 12px; text-align: center; border-right: 5px solid #047857;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #047857; font-family: monospace;">{{ \App\Models\Order::count() }}</div>
        <div style="color: #065f46; font-weight: 700; margin-top: 0.5rem;">إجمالي الفواتير</div>
    </div>
    <div style="background: #eff6ff; padding: 2rem; border-radius: 12px; text-align: center; border-right: 5px solid #1e3a8a;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">${{ number_format(\App\Models\Order::sum('total'), 0) }}</div>
        <div style="color: #1e40af; font-weight: 700; margin-top: 0.5rem;">إجمالي المبيعات</div>
    </div>
    <div style="background: #fef3c7; padding: 2rem; border-radius: 12px; text-align: center; border-right: 5px solid #d97706;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #d97706; font-family: monospace;">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</div>
        <div style="color: #92400e; font-weight: 700; margin-top: 0.5rem;">فواتير اليوم</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-list"></i>
        <span>آخر الفواتير</span>
        <button class="btn btn-primary" style="margin-right: auto; font-size: 0.9rem; padding: 0.5rem 1rem;">
            <i class="fas fa-plus"></i> فاتورة جديدة
        </button>
    </div>
    <table>
        <thead>
            <tr>
                <th>رقم الفاتورة</th>
                <th>التاريخ</th>
                <th>العميل</th>
                <th>المبلغ</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php $orders = \App\Models\Order::with('user')->latest()->take(20)->get(); @endphp
            @forelse($orders as $order)
            <tr>
                <td><strong style="color: #1e3a8a;">#{{ $order->id }}</strong></td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>{{ $order->user->name }}</td>
                <td class="positive" style="font-family: monospace;">${{ number_format($order->total, 2) }}</td>
                <td>
                    @if($order->status === 'completed')
                    <span style="background: #d1fae5; color: #065f46; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">مكتملة</span>
                    @elseif($order->status === 'pending')
                    <span style="background: #fef3c7; color: #92400e; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">قيد المعالجة</span>
                    @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">{{ $order->status }}</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
                        <i class="fas fa-eye"></i> عرض
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; color: #9ca3af; padding: 2rem;">لا توجد فواتير</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
