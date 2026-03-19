<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'لوحة التاجر' }}</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'El Messiri',sans-serif; background: #f6f7fb; color: #1f2937; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; gap: 1rem; flex-wrap: wrap; }
        .header h1 { font-family: 'El Messiri', sans-serif; font-size: 1.8rem; color: #4a148c; }
        .nav { display:flex; gap:.5rem; flex-wrap: wrap; }
        .nav a { text-decoration:none; border:1px solid #e5e7eb; background:#fff; color:#374151; padding:.5rem .9rem; border-radius:999px; font-weight:700; font-size:.9rem; display:inline-flex; align-items:center; gap:.45rem; }
        .nav a.active { background:#7b1fa2; border-color:#7b1fa2; color:#fff; }
        .grid { display: grid; gap: 1rem; }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 1.25rem; }
        .kpi { display: flex; align-items: center; gap: 1rem; }
        .kpi .icon { width: 44px; height: 44px; display: grid; place-items: center; border-radius: 12px; color: #fff; }
        .kpi .meta { display: flex; flex-direction: column; gap: .25rem; }
        .kpi .label { font-size: .9rem; color: #6b7280; }
        .kpi .value { font-weight: 800; font-size: 1.3rem; }
        .indigo { background: #7b1fa2; }
        .green { background: #10b981; }
        .orange { background: #f59e0b; }
        .blue { background: #3b82f6; }
        .red { background: #ef4444; }
        .section-title { font-weight: 800; margin-bottom: .75rem; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { text-align: right; padding: .75rem; border-bottom: 1px solid #f0f0f0; font-size: .95rem; }
        .table th { color: #6b7280; font-weight: 600; background: #fafafa; }
        .badge { padding: .25rem .5rem; border-radius: 999px; font-size: .75rem; font-weight: 700; }
        .badge.green { background: #ecfdf5; color: #065f46; }
        .badge.red { background: #fef2f2; color: #991b1b; }
        .badge.orange { background: #fff7ed; color: #9a3412; }
        .badge.gray { background: #f3f4f6; color: #374151; }
        .btn { border:none; cursor:pointer; border-radius:12px; padding:.6rem 1rem; font-weight:800; display:inline-flex; align-items:center; gap:.5rem; }
        .btn.primary { background:#7b1fa2; color:#fff; }
        .btn.gray { background:#e5e7eb; color:#111827; }
        .btn.danger { background:#fee2e2; color:#b91c1c; }
        .input, .select, .textarea { width:100%; border:2px solid #e5e7eb; border-radius:12px; padding:.75rem .9rem; font-family:'El Messiri',sans-serif; background:#fff; }
        .input:focus, .select:focus, .textarea:focus { outline:none; border-color:#7b1fa2; box-shadow: 0 0 0 4px rgba(123,31,162,.08); }
        .alert { padding:.9rem 1rem; border-radius:12px; font-weight:700; margin-bottom: 1rem; }
        .alert.success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .alert.error { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        @media (max-width: 1024px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .grid-4, .grid-2 { grid-template-columns: 1fr; } .container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>{{ $title ?? 'لوحة التاجر' }}</h1>
                <div style="color:#6b7280;font-weight:700;margin-top:.15rem">{{ $trader->name ?? '' }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
                <div class="nav">
                    <a href="{{ route('trader.dashboard') }}" class="{{ request()->routeIs('trader.dashboard') ? 'active' : '' }}"><i class="fas fa-gauge"></i> لوحة التحكم</a>
                    <a href="{{ route('trader.products.index') }}" class="{{ request()->routeIs('trader.products.*') ? 'active' : '' }}"><i class="fas fa-box"></i> المنتجات</a>
                    <a href="{{ route('trader.inventory') }}" class="{{ request()->routeIs('trader.inventory') ? 'active' : '' }}"><i class="fas fa-warehouse"></i> المخزون</a>
                    <a href="{{ route('trader.sales') }}" class="{{ request()->routeIs('trader.sales') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> المبيعات</a>
                </div>
                <form action="{{ route('trader.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn danger"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
