@extends('layouts.accounting')

@section('title', 'الأستاذ العام')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-book-open"></i> الأستاذ العام (General Ledger)</h1>
    <p>عرض حركات الحسابات التفصيلية</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-filter"></i>
        <span>تصفية البيانات</span>
    </div>
    <form method="GET" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
        <div class="form-group" style="margin: 0;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">الحساب</label>
            <select name="account_id" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
                <option value="">اختر الحساب...</option>
                @php
                $accounts = \App\Models\ChartOfAccount::where('is_active', true)->orderBy('account_code')->get();
                @endphp
                @foreach($accounts as $account)
                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                    {{ $account->account_code }} - {{ $account->account_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin: 0;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">من تاريخ</label>
            <input type="date" name="from_date" value="{{ request('from_date', date('Y-m-01')) }}" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
        </div>
        <div class="form-group" style="margin: 0;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #374151;">إلى تاريخ</label>
            <input type="date" name="to_date" value="{{ request('to_date', date('Y-m-d')) }}" style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 6px; font-family: 'Cairo', sans-serif;">
        </div>
        <button type="submit" class="btn btn-primary" style="height: 48px;"><i class="fas fa-search"></i> بحث</button>
    </form>
</div>

@if(request('account_id'))
    @php
    $selectedAccount = \App\Models\ChartOfAccount::find(request('account_id'));
    $fromDate = request('from_date', date('Y-m-01'));
    $toDate = request('to_date', date('Y-m-d'));
    
    $ledgerEntries = \App\Models\JournalEntryLine::with(['journalEntry'])
        ->where('account_id', request('account_id'))
        ->whereHas('journalEntry', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('entry_date', [$fromDate, $toDate])
              ->where('status', 'posted');
        })
        ->get()
        ->sortBy('journalEntry.entry_date');
    
    $openingBalance = $selectedAccount->opening_balance;
    $runningBalance = $openingBalance;
    @endphp

    <div class="card">
        <div class="card-header">
            <i class="fas fa-file-alt"></i>
            <span>حساب: {{ $selectedAccount->account_code }} - {{ $selectedAccount->account_name }}</span>
            <button class="btn btn-primary" onclick="window.print()" style="margin-right: auto; font-size: 0.9rem; padding: 0.5rem 1rem;">
                <i class="fas fa-print"></i> طباعة
            </button>
        </div>
        
        <div style="background: #eff6ff; padding: 1rem; margin-bottom: 1rem; border-right: 4px solid #1e3a8a;">
            <strong>الرصيد الافتتاحي:</strong>
            <span style="font-family: 'Courier New', monospace; font-weight: 800; color: #1e3a8a; margin-right: 1rem;">
                ${{ number_format($openingBalance, 2) }}
            </span>
            <span style="margin-right: 2rem;"><strong>الفترة:</strong> من {{ $fromDate }} إلى {{ $toDate }}</span>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">التاريخ</th>
                    <th style="width: 12%;">رقم القيد</th>
                    <th style="width: 35%;">البيان</th>
                    <th style="width: 13%;">مدين</th>
                    <th style="width: 13%;">دائن</th>
                    <th style="width: 17%;">الرصيد</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: #f0fdf4; font-weight: 700;">
                    <td colspan="5">الرصيد الافتتاحي</td>
                    <td style="font-family: 'Courier New', monospace; color: #1e3a8a;">
                        ${{ number_format($openingBalance, 2) }}
                    </td>
                </tr>
                @foreach($ledgerEntries as $line)
                    @php
                    if ($line->type === 'debit') {
                        if (in_array($selectedAccount->account_type, ['asset', 'expense'])) {
                            $runningBalance += $line->amount;
                        } else {
                            $runningBalance -= $line->amount;
                        }
                    } else {
                        if (in_array($selectedAccount->account_type, ['liability', 'equity', 'revenue'])) {
                            $runningBalance += $line->amount;
                        } else {
                            $runningBalance -= $line->amount;
                        }
                    }
                    @endphp
                    <tr>
                        <td>{{ $line->journalEntry->entry_date->format('Y-m-d') }}</td>
                        <td><strong style="color: #1e3a8a;">{{ $line->journalEntry->entry_number }}</strong></td>
                        <td>{{ $line->description ?: $line->journalEntry->description }}</td>
                        <td class="{{ $line->type === 'debit' ? 'positive' : '' }}" style="font-family: 'Courier New', monospace;">
                            {{ $line->type === 'debit' ? '$'.number_format($line->amount, 2) : '-' }}
                        </td>
                        <td class="{{ $line->type === 'credit' ? 'negative' : '' }}" style="font-family: 'Courier New', monospace;">
                            {{ $line->type === 'credit' ? '$'.number_format($line->amount, 2) : '-' }}
                        </td>
                        <td style="font-family: 'Courier New', monospace; font-weight: 700; color: #1e3a8a;">
                            ${{ number_format($runningBalance, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr style="background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; font-weight: 800; font-size: 1.05rem;">
                    <td colspan="3" style="padding: 1rem;">الرصيد الختامي</td>
                    <td style="padding: 1rem; font-family: 'Courier New', monospace;">
                        ${{ number_format($ledgerEntries->where('type', 'debit')->sum('amount'), 2) }}
                    </td>
                    <td style="padding: 1rem; font-family: 'Courier New', monospace;">
                        ${{ number_format($ledgerEntries->where('type', 'credit')->sum('amount'), 2) }}
                    </td>
                    <td style="padding: 1rem; font-family: 'Courier New', monospace;">
                        ${{ number_format($runningBalance, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        @if($ledgerEntries->isEmpty())
        <div style="text-align: center; padding: 3rem; color: #9ca3af;">
            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>لا توجد حركات على هذا الحساب في الفترة المحددة</p>
        </div>
        @endif
    </div>
@else
    <div class="card">
        <div style="text-align: center; padding: 3rem; color: #6b7280;">
            <i class="fas fa-search" style="font-size: 4rem; margin-bottom: 1rem; color: #1e3a8a;"></i>
            <h3 style="color: #1e3a8a; margin-bottom: 0.5rem;">اختر حساباً لعرض حركاته</h3>
            <p>قم باختيار الحساب والفترة الزمنية من الأعلى</p>
        </div>
    </div>
@endif
@endsection
