<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام المحاسبة - Tulip Store</title>
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f7fa; min-height: 100vh; }
        .container { max-width: 1800px; margin: 0 auto; padding: 2rem; margin-top: 100px; }
        
        /* Professional Header */
        .hero { 
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(30,58,138,0.3);
            border-bottom: 4px solid #d97706;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d97706, #f59e0b, #d97706);
        }
        .hero h1 { 
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.3rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .hero p { 
            color: #dbeafe;
            font-size: 1rem;
            font-weight: 600;
        }
        
        /* Section Titles */
        .section-title { 
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e3a8a;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #d97706;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .section-title i { color: #d97706; }
        
        /* Stats Grid */
        .stats-grid { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card { 
            background: #fff;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-right: 5px solid #1e3a8a;
            border-top: 1px solid #e5e7eb;
            transition: all 0.3s;
        }
        .stat-card:hover { 
            box-shadow: 0 6px 20px rgba(30,58,138,0.15);
            transform: translateY(-3px);
        }
        .stat-icon { 
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 0.8rem;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #fff;
            border-radius: 8px;
        }
        .stat-value { 
            font-size: 2rem;
            font-weight: 800;
            color: #1e3a8a;
            font-family: 'Courier New', monospace;
            direction: ltr;
            text-align: right;
        }
        .stat-label { 
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        /* Cards */
        .card { 
            background: #fff;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-right: 5px solid #1e3a8a;
            border-top: 1px solid #e5e7eb;
        }
        .card h3 { 
            color: #1e3a8a;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 800;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid #d97706;
        }
        .card h3 i { color: #d97706; }
        
        /* Tables */
        table { 
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        thead { 
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }
        th { 
            padding: 1rem;
            text-align: right;
            font-weight: 700;
            color: #fff;
            border-left: 1px solid rgba(255,255,255,0.1);
        }
        td { 
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        tbody tr:hover { 
            background: #f0f9ff;
        }
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        tbody tr:nth-child(even):hover {
            background: #f0f9ff;
        }
        
        /* Colors */
        .positive { color: #047857; font-weight: 700; font-family: 'Courier New', monospace; }
        .negative { color: #dc2626; font-weight: 700; font-family: 'Courier New', monospace; }
        .zero { color: #9ca3af; font-family: 'Courier New', monospace; }
        
        /* Accounting Equation */
        .accounting-equation { 
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            border: 3px solid #1e3a8a;
            border-top: 5px solid #d97706;
            box-shadow: 0 4px 15px rgba(30,58,138,0.1);
        }
        .equation { 
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 1.2rem;
        }
        .equation-values { 
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .equation-item { 
            text-align: center;
            background: #fff;
            padding: 1.2rem 1.8rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-top: 3px solid #d97706;
        }
        .equation-label { 
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .equation-value { 
            font-size: 1.6rem;
            font-weight: 800;
            color: #1e3a8a;
            font-family: 'Courier New', monospace;
            direction: ltr;
        }
        
        /* Ratios */
        .ratio-grid { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .ratio-box { 
            background: #fff;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #d97706;
            border-right: 1px solid #e5e7eb;
        }
        .ratio-value { 
            font-size: 2.2rem;
            font-weight: 800;
            color: #1e3a8a;
            font-family: 'Courier New', monospace;
            direction: ltr;
        }
        .ratio-label { 
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-landmark"></i> نظام المحاسبة المتكامل - Tulip Store</h1>
            <p>برنامج محاسبي احترافي متكامل وفق المعايير المحاسبية الدولية</p>
        </div>

        <!-- Accounting Equation -->
        <div class="accounting-equation">
            <div class="equation"><i class="fas fa-balance-scale"></i> المعادلة المحاسبية الأساسية</div>
            <div class="equation-values">
                <div class="equation-item">
                    <div class="equation-label">الأصول</div>
                    <div class="equation-value">${{ number_format($totalAssets, 0) }}</div>
                </div>
                <div style="font-size: 2rem; color: #065f46; font-weight: 800;">=</div>
                <div class="equation-item">
                    <div class="equation-label">الالتزامات</div>
                    <div class="equation-value">${{ number_format($totalLiabilities, 0) }}</div>
                </div>
                <div style="font-size: 2rem; color: #065f46; font-weight: 800;">+</div>
                <div class="equation-item">
                    <div class="equation-label">حقوق الملكية</div>
                    <div class="equation-value">${{ number_format($totalEquity, 0) }}</div>
                </div>
            </div>
            @php $diff = $totalAssets - ($totalLiabilities + $totalEquity); @endphp
            <div style="margin-top: 1rem; font-size: 1rem; font-weight: 700;">
                @if(abs($diff) < 1)
                    <span style="color: #059669;"><i class="fas fa-check-circle"></i> المعادلة متوازنة ✓</span>
                @else
                    <span style="color: #dc2626;"><i class="fas fa-exclamation-triangle"></i> فرق: ${{ number_format($diff, 0) }}</span>
                @endif
            </div>
        </div>

        <!-- Financial Summary -->
        <h2 class="section-title"><i class="fas fa-chart-pie"></i> الملخص المالي</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                <div class="stat-label">إجمالي الأصول</div>
                <div class="stat-value positive">${{ number_format($totalAssets, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #dc2626;"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-label">إجمالي الالتزامات</div>
                <div class="stat-value negative">${{ number_format($totalLiabilities, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #7c3aed;"><i class="fas fa-landmark"></i></div>
                <div class="stat-label">حقوق الملكية</div>
                <div class="stat-value">${{ number_format($totalEquity, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #0ea5e9;"><i class="fas fa-arrow-up"></i></div>
                <div class="stat-label">إجمالي الإيرادات</div>
                <div class="stat-value positive">${{ number_format($totalRevenue, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f59e0b;"><i class="fas fa-arrow-down"></i></div>
                <div class="stat-label">إجمالي المصروفات</div>
                <div class="stat-value negative">${{ number_format($totalExpenses, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: {{ $netIncome >= 0 ? '#059669' : '#dc2626' }};"><i class="fas fa-{{ $netIncome >= 0 ? 'trophy' : 'exclamation-triangle' }}"></i></div>
                <div class="stat-label">صافي الدخل</div>
                <div class="stat-value {{ $netIncome >= 0 ? 'positive' : 'negative' }}">${{ number_format($netIncome, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-label">النقدية</div>
                <div class="stat-value">${{ number_format($cashBalance, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #3b82f6;"><i class="fas fa-university"></i></div>
                <div class="stat-label">البنوك</div>
                <div class="stat-value">${{ number_format($bankBalance ?? 0, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #8b5cf6;"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="stat-label">الذمم المدينة</div>
                <div class="stat-value">${{ number_format($accountsReceivable, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #ec4899;"><i class="fas fa-boxes"></i></div>
                <div class="stat-label">المخزون</div>
                <div class="stat-value">${{ number_format($inventory, 0) }}</div>
            </div>
        </div>

        <!-- Key Ratios -->
        <h2 class="section-title"><i class="fas fa-percentage"></i> النسب المالية الرئيسية</h2>
        <div class="ratio-grid">
            <div class="ratio-box">
                <div class="ratio-value">{{ number_format($currentRatio, 2) }}</div>
                <div class="ratio-label">نسبة التداول</div>
            </div>
            <div class="ratio-box">
                <div class="ratio-value">{{ number_format($quickRatio, 2) }}</div>
                <div class="ratio-label">النسبة السريعة</div>
            </div>
            <div class="ratio-box">
                <div class="ratio-value">{{ number_format($debtToEquity, 2) }}</div>
                <div class="ratio-label">الدين/حقوق الملكية</div>
            </div>
            <div class="ratio-box">
                <div class="ratio-value">{{ number_format($returnOnAssets, 1) }}%</div>
                <div class="ratio-label">العائد على الأصول</div>
            </div>
            <div class="ratio-box">
                <div class="ratio-value">{{ number_format($profitMargin, 1) }}%</div>
                <div class="ratio-label">هامش الربح</div>
            </div>
        </div>

        <!-- Charts -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div class="card">
                <h3><i class="fas fa-chart-area"></i> الإيرادات مقابل المصروفات - آخر 6 أشهر</h3>
                <canvas id="revenueExpenseChart" height="200"></canvas>
            </div>
            <div class="card">
                <h3><i class="fas fa-chart-doughnut"></i> توزيع أنواع الحسابات</h3>
                <canvas id="accountTypesChart" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <h3><i class="fas fa-exchange-alt"></i> آخر القيود المحاسبية</h3>
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>رقم القيد</th>
                        <th>الوصف</th>
                        <th>الحساب</th>
                        <th>مدين</th>
                        <th>دائن</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $t)
                    <tr>
                        <td>{{ $t['date'] }}</td>
                        <td><strong style="color: #059669;">{{ $t['entry'] }}</strong></td>
                        <td>{{ $t['description'] }}</td>
                        <td>{{ $t['account'] }}</td>
                        <td class="{{ $t['debit'] > 0 ? 'positive' : 'zero' }}">{{ $t['debit'] > 0 ? '$'.number_format($t['debit'], 0) : '-' }}</td>
                        <td class="{{ $t['credit'] > 0 ? 'negative' : 'zero' }}">{{ $t['credit'] > 0 ? '$'.number_format($t['credit'], 0) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #9ca3af; padding: 2rem;">لا توجد قيود محاسبية</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Revenue vs Expense Chart
        const ctx1 = document.getElementById('revenueExpenseChart');
        if (ctx1) {
            new Chart(ctx1.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
                    datasets: [{
                        label: 'الإيرادات',
                        data: {!! json_encode(array_column($monthlyData, 'revenue')) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    }, {
                        label: 'المصروفات',
                        data: {!! json_encode(array_column($monthlyData, 'expenses')) !!},
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Account Types Chart
        const ctx2 = document.getElementById('accountTypesChart');
        if (ctx2) {
            new Chart(ctx2.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['الأصول', 'الالتزامات', 'حقوق الملكية', 'الإيرادات', 'المصروفات'],
                    datasets: [{
                        data: [{{ $totalAssets }}, {{ $totalLiabilities }}, {{ $totalEquity }}, {{ $totalRevenue }}, {{ $totalExpenses }}],
                        backgroundColor: ['#059669', '#dc2626', '#7c3aed', '#0ea5e9', '#f59e0b'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    </script>
</body>
</html>
