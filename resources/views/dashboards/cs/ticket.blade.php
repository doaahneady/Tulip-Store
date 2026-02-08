@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تفاصيل التذكرة'; $subtitle = 'عرض المحادثة والإجراءات'; @endphp

@if(! $ticket)
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-700">التذكرة غير موجودة</p>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $ticket->ticket_number }} — {{ $ticket->subject }}</h3>
                    <p class="text-sm text-gray-500">تم الإنشاء: {{ $ticket->created_at?->format('Y-m-d H:i') }} • الحالة: {{ $ticket->status }} • الأولوية: {{ $ticket->priority }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if(empty($ticket->assigned_to))
                        <form method="POST" action="{{ route('dashboard.cs.tickets.assign-to-me', $ticket->id) }}">
                            @csrf
                            <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">تخصيص لي</button>
                        </form>
                    @endif

                    @if(in_array($ticket->status, ['open','pending','in_progress','waiting_customer'], true))
                        <form method="POST" action="{{ route('dashboard.cs.tickets.resolve', $ticket->id) }}">
                            @csrf
                            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">حل</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.cs.tickets.close', $ticket->id) }}">
                            @csrf
                            <button class="px-4 py-2 rounded-xl bg-gray-900 text-white hover:bg-black">إغلاق</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl mb-6">
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
            </div>

            <h4 class="text-sm font-semibold text-gray-900 mb-3">المحادثة</h4>
            <div class="space-y-3">
                @forelse($ticket->replies->sortBy('created_at') as $reply)
                    <div class="p-4 rounded-2xl border {{ $reply->is_internal ? 'border-amber-200 bg-amber-50' : 'border-gray-100 bg-white' }}">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $reply->author_name ?? 'Unknown' }}
                                <span class="text-xs text-gray-500">({{ $reply->author_type_display ?? '' }})</span>
                                @if($reply->is_internal)
                                    <span class="text-xs px-2 py-0.5 rounded bg-amber-200 text-amber-900">ملاحظة داخلية</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">{{ $reply->created_at?->diffForHumans() }}</div>
                        </div>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $reply->message }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">لا توجد ردود بعد</p>
                @endforelse
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">إضافة رد</h4>
                <form method="POST" action="{{ route('dashboard.cs.tickets.reply', $ticket->id) }}" class="space-y-3">
                    @csrf
                    <textarea name="message" rows="4" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="اكتب الرد..."></textarea>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300">
                        <span>ملاحظة داخلية (لا تظهر للعميل)</span>
                    </label>
                    <div>
                        <button class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition">
                            <i class="fas fa-paper-plane"></i>
                            <span>إرسال</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h4 class="text-sm font-semibold text-gray-900 mb-3">معلومات العميل</h4>
            <div class="space-y-2 text-sm text-gray-700">
                <div><span class="text-gray-500">الاسم:</span> {{ optional($ticket->user)->name ?? ('User #'.$ticket->user_id) }}</div>
                <div><span class="text-gray-500">البريد:</span> {{ optional($ticket->user)->email ?? '-' }}</div>
                <div><span class="text-gray-500">الهاتف:</span> {{ optional($ticket->user)->phone ?? '-' }}</div>
                <div><span class="text-gray-500">المسؤول:</span> {{ optional($ticket->assignedTo)->full_name ?? ($ticket->assigned_to ? ('#'.$ticket->assigned_to) : 'غير مخصص') }}</div>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">مرتجع / استرداد</h4>
                <form method="POST" action="{{ route('dashboard.cs.tickets.initiate-refund', $ticket->id) }}" class="space-y-2">
                    @csrf
                    <input name="amount" type="number" step="0.01" min="0.01" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="المبلغ">
                    <input name="reason" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="السبب">
                    <button class="w-full inline-flex items-center justify-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl hover:bg-amber-700 transition">
                        <i class="fas fa-rotate-left"></i>
                        <span>بدء طلب الاسترداد</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

