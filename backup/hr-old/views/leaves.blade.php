@extends('layouts.dashboard')

@section('title', 'طلبات الإجازات')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-calendar-alt text-amber-600"></i>
            <span class="text-sm text-gray-700">فلاتر الطلبات</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.leaves') }}" class="flex flex-wrap items-center gap-2">
            <select name="status" class="form-select w-40">
                <option value="">الحالة</option>
                @foreach(['pending'=>'قيد الانتظار','approved'=>'موافق عليها','rejected'=>'مرفوضة','cancelled'=>'ملغاة'] as $key=>$label)
                    <option value="{{ $key }}" @selected(request('status')===$key)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                تطبيق
            </button>
            <a href="{{ route('dashboard.hr.index') }}" class="btn btn-secondary btn-sm">عودة للوحة الموارد البشرية</a>
        </form>
    </div>
    </div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">جميع طلبات الإجازة</h3>
        <div class="text-sm text-gray-500">الإدارة: الموافقة / الرفض / الإلغاء</div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">الموظف</th>
                    <th class="px-4 py-2 text-left">نوع الإجازة</th>
                    <th class="px-4 py-2 text-left">من</th>
                    <th class="px-4 py-2 text-left">إلى</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">إجراءات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($leaves as $leave)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $leave->employee->user->name ?? 'غير معروف' }}</td>
                    <td class="px-4 py-2">{{ $leave->type }}</td>
                    <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($leave->start_date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($leave->end_date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">
                        @php $color = ['pending'=>'yellow','approved'=>'green','rejected'=>'red','cancelled'=>'gray'][$leave->status] ?? 'gray'; @endphp
                        <span class="px-2 py-1 rounded text-xs bg-{{ $color }}-100 text-{{ $color }}-700">{{ $leave->status }}</span>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            @if($leave->status==='pending')
                                <form method="POST" action="{{ route('dashboard.hr.leaves.approve', $leave) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm"><i class="fas fa-check"></i> موافقة</button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.hr.leaves.reject', $leave) }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i> رفض</button>
                                </form>
                            @endif
                            @if(in_array($leave->status,['approved','pending']))
                                <form method="POST" action="{{ route('dashboard.hr.leaves.cancel', $leave) }}">
                                    @csrf
                                    <button class="btn btn-secondary btn-sm"><i class="fas fa-ban"></i> إلغاء</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد طلبات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($leaves ?? null),'links'))
            {{ $leaves->links() }}
        @endif
    </div>
</div>
@endsection

