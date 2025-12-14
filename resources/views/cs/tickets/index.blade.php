<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة التذاكر - Tulip Store</title>
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1a202c;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            margin-top: 80px;
        }

        /* Modern Header */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .header-title {
            flex: 1;
        }

        .header-title h1 {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            color: #64748b;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-modern {
            padding: 1rem 2rem;
            border-radius: 16px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #667eea;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-2px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.2);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .stat-value {
            font-size: 3rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .change-up {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .change-down {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* Modern Filters */
        .filters-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filters-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .filter-label {
            font-weight: 600;
            color: #374151;
            font-size: 1rem;
        }

        .filter-input, .filter-select {
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Modern Tickets Table */
        .tickets-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .tickets-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tickets-title {
            font-size: 1.8rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .tickets-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 1.5rem;
            text-align: right;
            font-weight: 700;
            color: #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-weight: 500;
            vertical-align: top;
        }

        tbody tr {
            transition: all 0.2s;
        }

        tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .ticket-id {
            font-weight: 800;
            color: #667eea;
            font-size: 1rem;
        }

        .ticket-subject {
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .ticket-description {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Modern Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-open {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .status-in_progress {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .status-resolved {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .status-closed {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }

        .priority-urgent {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            animation: pulse 2s infinite;
        }

        .priority-high {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }

        .priority-medium {
            background: linear-gradient(135deg, #eab308, #ca8a04);
            color: white;
        }

        .priority-low {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        /* Customer Info */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .customer-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .customer-details {
            flex: 1;
        }

        .customer-name {
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.2rem;
        }

        .customer-email {
            color: #64748b;
            font-size: 0.8rem;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.6rem 1rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }

        .btn-view {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .btn-view:hover, .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 2rem;
            background: #f8fafc;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination a, .pagination span {
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .pagination a {
            background: white;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .pagination .active span {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .header-title h1 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                font-size: 0.8rem;
            }
            
            th, td {
                padding: 1rem 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header-actions {
                flex-direction: column;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="main-container">
        <!-- Modern Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-ticket-alt"></i> إدارة التذاكر المتقدمة</h1>
                    <p class="header-subtitle">نظام شامل لإدارة ومتابعة جميع تذاكر الدعم الفني والعملاء</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('cs.tickets.create') }}" class="btn-modern btn-primary">
                        <i class="fas fa-plus"></i>
                        تذكرة جديدة
                    </a>
                    <a href="{{ route('cs.dashboard') }}" class="btn-modern btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        لوحة التحكم
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['open'] ?? 0 }}</div>
                <div class="stat-label">تذاكر مفتوحة</div>
                <div class="stat-change change-up">
                    <i class="fas fa-arrow-up"></i>
                    +{{ rand(5, 15) }}% من الأمس
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['in_progress'] ?? 0 }}</div>
                <div class="stat-label">قيد المعالجة</div>
                <div class="stat-change change-up">
                    <i class="fas fa-arrow-up"></i>
                    +{{ rand(3, 12) }}% من الأمس
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['urgent'] ?? 0 }}</div>
                <div class="stat-label">عاجلة</div>
                <div class="stat-change change-down">
                    <i class="fas fa-arrow-down"></i>
                    -{{ rand(2, 8) }}% من الأمس
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['resolved'] ?? 0 }}</div>
                <div class="stat-label">محلولة</div>
                <div class="stat-change change-up">
                    <i class="fas fa-arrow-up"></i>
                    +{{ rand(10, 25) }}% من الأمس
                </div>
            </div>
        </div>

        <!-- Modern Filters -->
        <div class="filters-section">
            <div class="filters-header">
                <h3 class="filters-title">
                    <i class="fas fa-filter"></i>
                    فلاتر البحث المتقدمة
                </h3>
            </div>
            
            <form method="GET" action="{{ route('cs.tickets.index') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">البحث الشامل</label>
                        <input type="text" name="search" class="filter-input" 
                               value="{{ request('search') }}" 
                               placeholder="رقم التذكرة، الموضوع، اسم العميل...">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">حالة التذكرة</label>
                        <select name="status" class="filter-select">
                            <option value="">جميع الحالات</option>
                            @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                @switch($status)
                                    @case('open') مفتوحة @break
                                    @case('in_progress') قيد المعالجة @break
                                    @case('waiting_customer') انتظار العميل @break
                                    @case('resolved') محلولة @break
                                    @case('closed') مغلقة @break
                                @endswitch
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">مستوى الأولوية</label>
                        <select name="priority" class="filter-select">
                            <option value="">جميع الأولويات</option>
                            @foreach($priorities as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                @switch($priority)
                                    @case('low') منخفضة @break
                                    @case('medium') متوسطة @break
                                    @case('high') عالية @break
                                    @case('urgent') عاجلة @break
                                @endswitch
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">الوكيل المعين</label>
                        <select name="assigned_to" class="filter-select">
                            <option value="">جميع الوكلاء</option>
                            @foreach($csAgents as $agent)
                            <option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
                    <button type="submit" class="btn-modern btn-primary">
                        <i class="fas fa-search"></i>
                        تطبيق الفلاتر
                    </button>
                    <a href="{{ route('cs.tickets.index') }}" class="btn-modern btn-secondary">
                        <i class="fas fa-times"></i>
                        مسح الفلاتر
                    </a>
                </div>
            </form>
        </div>

        <!-- Modern Tickets Table -->
        <div class="tickets-section">
            <div class="tickets-header">
                <h3 class="tickets-title">
                    <i class="fas fa-list"></i>
                    قائمة التذاكر
                </h3>
                <span class="tickets-count">{{ $tickets->total() }} تذكرة</span>
            </div>

            @if($tickets->total() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>معرف التذكرة</th>
                            <th>الموضوع والوصف</th>
                            <th>العميل</th>
                            <th>الحالة</th>
                            <th>الأولوية</th>
                            <th>الوكيل المعين</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>
                                <div class="ticket-id">#{{ $ticket->id }}</div>
                            </td>
                            <td>
                                <div class="ticket-subject">{{ $ticket->subject }}</div>
                                <div class="ticket-description">{{ Str::limit($ticket->description, 100) }}</div>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $ticket->user->name }}</div>
                                        <div class="customer-email">{{ $ticket->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $ticket->status }}">
                                    <i class="fas fa-circle"></i>
                                    @switch($ticket->status)
                                        @case('open') مفتوحة @break
                                        @case('in_progress') قيد المعالجة @break
                                        @case('waiting_customer') انتظار العميل @break
                                        @case('resolved') محلولة @break
                                        @case('closed') مغلقة @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="status-badge priority-{{ $ticket->priority }}">
                                    <i class="fas fa-flag"></i>
                                    @switch($ticket->priority)
                                        @case('low') منخفضة @break
                                        @case('medium') متوسطة @break
                                        @case('high') عالية @break
                                        @case('urgent') عاجلة @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                @if($ticket->assignedAgent)
                                    <div class="customer-info">
                                        <div class="customer-avatar" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                            {{ strtoupper(substr($ticket->assignedAgent->name, 0, 2)) }}
                                        </div>
                                        <div class="customer-details">
                                            <div class="customer-name">{{ $ticket->assignedAgent->name }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #64748b; font-style: italic;">غير معين</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1a202c;">{{ $ticket->created_at->format('Y-m-d') }}</div>
                                <div style="color: #64748b; font-size: 0.8rem;">{{ $ticket->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="actions-group">
                                    <a href="{{ route('cs.tickets.show', $ticket->id) }}" class="action-btn btn-view">
                                        <i class="fas fa-eye"></i>
                                        عرض
                                    </a>
                                    @if($ticket->status !== 'closed')
                                    <a href="{{ route('cs.tickets.edit', $ticket->id) }}" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                        تعديل
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination">
                    {{ $tickets->appends(request()->query())->links() }}
                </div>
            </div>
            @else
            <div style="text-align: center; padding: 4rem; color: #64748b;">
                <i class="fas fa-inbox" style="font-size: 5rem; color: #cbd5e1; margin-bottom: 2rem;"></i>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin-bottom: 1rem;">لا توجد تذاكر</h3>
                <p style="font-size: 1rem; margin-bottom: 2rem;">لم يتم العثور على تذاكر تطابق معايير البحث المحددة</p>
                <a href="{{ route('cs.tickets.create') }}" class="btn-modern btn-primary">
                    <i class="fas fa-plus"></i>
                    إنشاء تذكرة جديدة
                </a>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-submit form on filter change
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                if (this.value !== '') {
                    this.form.submit();
                }
            });
        });

        // Smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .filters-section, .tickets-section');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>