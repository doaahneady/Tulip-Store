<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام المحاسبة') - Tulip Store</title>
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
    <style>
        * { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f7fa; min-height: 100vh; display: flex; flex-direction: column; }
        
        /* Top Navbar */
        .top-navbar { 
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 3px solid #d97706;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .top-navbar .logo { font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.8rem; }
        .top-navbar .logo i { color: #d97706; }
        .top-navbar .user-info { display: flex; align-items: center; gap: 1.5rem; }
        .top-navbar .user-info .date { font-size: 0.9rem; }
        .top-navbar .user-info .user { display: flex; align-items: center; gap: 0.5rem; }
        
        /* Main Layout */
        .main-layout { display: flex; margin-top: 70px; min-height: calc(100vh - 70px); }
        
        /* Sidebar */
        .sidebar { 
            width: 280px;
            background: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            position: fixed;
            top: 70px;
            bottom: 0;
            right: 0;
        }
        .sidebar-menu { list-style: none; padding: 1rem 0; }
        .sidebar-menu > li { margin-bottom: 0.3rem; }
        .sidebar-menu > li > a { 
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 1.5rem;
            color: #374151;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border-right: 4px solid transparent;
        }
        .sidebar-menu > li > a:hover,
        .sidebar-menu > li > a.active { 
            background: #eff6ff;
            color: #1e3a8a;
            border-right-color: #d97706;
        }
        .sidebar-menu > li > a i { 
            width: 24px;
            text-align: center;
            color: #1e3a8a;
        }
        
        /* Submenu */
        .submenu { 
            list-style: none;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s;
            background: #f9fafb;
        }
        .submenu.open { max-height: 500px; }
        .submenu li a { 
            display: block;
            padding: 0.8rem 1.5rem 0.8rem 3.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .submenu li a:hover,
        .submenu li a.active { 
            background: #e0f2fe;
            color: #1e3a8a;
            padding-right: 3rem;
        }
        
        /* Content Area */
        .content-area { 
            flex: 1;
            margin-right: 280px;
            padding: 2rem;
        }
        
        /* Page Header */
        .page-header { 
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #fff;
            padding: 1.5rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            border-bottom: 4px solid #d97706;
        }
        .page-header h1 { 
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }
        .page-header p { 
            color: #dbeafe;
            font-size: 0.95rem;
        }
        
        /* Cards */
        .card { 
            background: #fff;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-right: 5px solid #1e3a8a;
        }
        .card-header { 
            font-size: 1.3rem;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid #d97706;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .card-header i { color: #d97706; }
        
        /* Buttons */
        .btn { 
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Cairo', sans-serif;
        }
        .btn-primary { background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(30,58,138,0.3); }
        .btn-success { background: #047857; color: #fff; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #6b7280; color: #fff; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        thead { background: linear-gradient(135deg, #1e3a8a, #2563eb); }
        th { padding: 1rem; text-align: right; font-weight: 700; color: #fff; border-left: 1px solid rgba(255,255,255,0.1); }
        td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        tbody tr:hover { background: #f0f9ff; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody tr:nth-child(even):hover { background: #f0f9ff; }
        
        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: #fff; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-right: 5px solid #1e3a8a; }
        .stat-icon { width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 0.8rem; background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; border-radius: 8px; }
        .stat-value { font-size: 2rem; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace; }
        .stat-label { color: #6b7280; font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem; }
        
        /* Colors */
        .positive { color: #047857; font-weight: 700; }
        .negative { color: #dc2626; font-weight: 700; }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="top-navbar">
        <div class="logo">
            <i class="fas fa-landmark"></i>
            <span>نظام المحاسبة المتكامل - Tulip Store</span>
        </div>
        <div class="user-info">
            <div class="date">
                <i class="far fa-calendar"></i>
                {{ date('Y-m-d') }} | {{ date('H:i') }}
            </div>
            <div class="user">
                <i class="fas fa-user-circle"></i>
                <span>{{ is_array(data_get(auth()->user(),'name')) ? json_encode(data_get(auth()->user(),'name')) : data_get(auth()->user(),'name') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="/accounting/dashboard" class="{{ request()->is('accounting/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>الصفحة الرئيسية</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" onclick="toggleSubmenu(event, 'accounts-menu')">
                        <i class="fas fa-book"></i>
                        <span>الحسابات</span>
                        <i class="fas fa-chevron-down" style="margin-right: auto; font-size: 0.8rem;"></i>
                    </a>
                    <ul class="submenu" id="accounts-menu">
                        <li><a href="/accounting/chart-of-accounts">دليل الحسابات</a></li>
                        <li><a href="/accounting/accounts/tree">شجرة الحسابات</a></li>
                        <li><a href="/accounting/accounts/create">إضافة حساب جديد</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#" onclick="toggleSubmenu(event, 'journal-menu')">
                        <i class="fas fa-file-alt"></i>
                        <span>القيود اليومية</span>
                        <i class="fas fa-chevron-down" style="margin-right: auto; font-size: 0.8rem;"></i>
                    </a>
                    <ul class="submenu" id="journal-menu">
                        <li><a href="/accounting/journal-entries/create">قيد يومية جديد</a></li>
                        <li><a href="/accounting/journal-entries">دفتر اليومية</a></li>
                        <li><a href="/accounting/journal-entries/adjustments">قيود التسوية</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#" onclick="toggleSubmenu(event, 'reports-menu')">
                        <i class="fas fa-chart-bar"></i>
                        <span>التقارير المالية</span>
                        <i class="fas fa-chevron-down" style="margin-right: auto; font-size: 0.8rem;"></i>
                    </a>
                    <ul class="submenu" id="reports-menu">
                        <li><a href="/accounting/trial-balance">ميزان المراجعة</a></li>
                        <li><a href="/accounting/balance-sheet">قائمة المركز المالي</a></li>
                        <li><a href="/accounting/income-statement">قائمة الدخل</a></li>
                        <li><a href="/accounting/cash-flow">قائمة التدفقات النقدية</a></li>
                        <li><a href="/accounting/general-ledger">الأستاذ العام</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#" onclick="toggleSubmenu(event, 'calculators-menu')">
                        <i class="fas fa-calculator"></i>
                        <span>الآلات الحاسبة</span>
                        <i class="fas fa-chevron-down" style="margin-right: auto; font-size: 0.8rem;"></i>
                    </a>
                    <ul class="submenu" id="calculators-menu">
                        <li><a href="/accounting/calculators/depreciation">حاسبة الإهلاك</a></li>
                        <li><a href="/accounting/calculators/loan">حاسبة القروض</a></li>
                        <li><a href="/accounting/calculators/vat">حاسبة ضريبة القيمة المضافة</a></li>
                        <li><a href="/accounting/calculators/profit-margin">حاسبة هامش الربح</a></li>
                        <li><a href="/accounting/calculators/break-even">حاسبة نقطة التعادل</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="/accounting/invoices">
                        <i class="fas fa-file-invoice"></i>
                        <span>الفواتير</span>
                    </a>
                </li>
                
                <li>
                    <a href="/accounting/receivables">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>الذمم المدينة</span>
                    </a>
                </li>
                
                <li>
                    <a href="/accounting/payables">
                        <i class="fas fa-receipt"></i>
                        <span>الذمم الدائنة</span>
                    </a>
                </li>
                
                <li>
                    <a href="/accounting/inventory">
                        <i class="fas fa-boxes"></i>
                        <span>المخزون</span>
                    </a>
                </li>
                
                <li>
                    <a href="/accounting/fixed-assets">
                        <i class="fas fa-building"></i>
                        <span>الأصول الثابتة</span>
                    </a>
                </li>
                
                <li>
                    <a href="/accounting/payroll">
                        <i class="fas fa-users"></i>
                        <span>الرواتب والأجور</span>
                    </a>
                </li>
                
                <li>
                    <a href="/accounting/settings">
                        <i class="fas fa-cog"></i>
                        <span>الإعدادات</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSubmenu(event, menuId) {
            event.preventDefault();
            const submenu = document.getElementById(menuId);
            submenu.classList.toggle('open');
        }
    </script>
    <script src="/js/accounting-interactions.js"></script>
    @stack('scripts')
</body>
</html>
