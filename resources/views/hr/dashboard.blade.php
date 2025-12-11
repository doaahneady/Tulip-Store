<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموارد البشرية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 2rem;
        }

        .header-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            color: var(--primary-color);
            font-weight: bold;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .stat-card.primary .icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.success .icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-card.warning .icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-card.danger .icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0.5rem 0;
            color: #1f2937;
        }

        .stat-card p {
            color: #6b7280;
            margin: 0;
            font-size: 0.95rem;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--primary-color);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            padding: 1rem;
            border-radius: 10px;
            text-decoration: none;
            color: white;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .action-btn i {
            font-size: 2rem;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .action-btn.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .action-btn.info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .action-btn.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .table-container {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border: none;
        }

        .modern-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .modern-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .modern-table tbody tr {
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: #f3f4f6;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 1rem;
            border: none;
        }

        .modern-table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .modern-table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-action {
            padding: 0.4rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-approve {
            background: var(--success-color);
            color: white;
        }

        .btn-reject {
            background: var(--danger-color);
            color: white;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-users-cog"></i> لوحة تحكم الموارد البشرية</h1>
                    <p class="text-muted mb-0">مرحباً بك في نظام إدارة الموارد البشرية</p>
                </div>
                <div>
                    <a href="/" class="btn btn-outline-primary">
                        <i class="fas fa-home"></i> الرئيسية
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>{{ $stats['total_employees'] }}</h3>
                <p>إجمالي الموظفين</p>
            </div>

            <div class="stat-card success">
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3>{{ $stats['present_today'] }}</h3>
                <p>الحضور اليوم</p>
            </div>

            <div class="stat-card warning">
                <div class="icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <h3>{{ $stats['on_leave'] }}</h3>
                <p>في إجازة</p>
            </div>

            <div class="stat-card danger">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>{{ $stats['pending_leave_requests'] }}</h3>
                <p>طلبات إجازة معلقة</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-section">
            <h2 class="section-title"><i class="fas fa-bolt"></i> إجراءات سريعة</h2>
            <div class="quick-actions">
                <a href="{{ route('hr.employees') }}" class="action-btn primary">
                    <i class="fas fa-users"></i>
                    <span>إدارة الموظفين</span>
                </a>
                <a href="{{ route('hr.attendance') }}" class="action-btn success">
                    <i class="fas fa-calendar-check"></i>
                    <span>الحضور والانصراف</span>
                </a>
                <a href="{{ route('hr.leaves') }}" class="action-btn info">
                    <i class="fas fa-calendar-alt"></i>
                    <span>طلبات الإجازات</span>
                </a>
                <a href="{{ route('hr.payroll') }}" class="action-btn warning">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>الرواتب</span>
                </a>
                <a href="{{ route('hr.performance') }}" class="action-btn primary">
                    <i class="fas fa-chart-line"></i>
                    <span>تقييم الأداء</span>
                </a>
                <a href="{{ route('hr.training') }}" class="action-btn success">
                    <i class="fas fa-graduation-cap"></i>
                    <span>التدريب</span>
                </a>
                <a href="{{ route('hr.reports') }}" class="action-btn info">
                    <i class="fas fa-file-alt"></i>
                    <span>التقارير</span>
                </a>
            </div>
        </div>

        <!-- Recent Employees -->
        <div class="content-section">
            <h2 class="section-title"><i class="fas fa-user-plus"></i> أحدث الموظفين</h2>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>الكود</th>
                            <th>الاسم</th>
                            <th>القسم</th>
                            <th>المنصب</th>
                            <th>تاريخ التعيين</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentEmployees as $employee)
                        <tr>
                            <td><strong>{{ $employee->employee_code }}</strong></td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->hire_date->format('Y-m-d') }}</td>
                            <td>
                                @if($employee->status === 'active')
                                    <span class="badge bg-success">نشط</span>
                                @elseif($employee->status === 'on_leave')
                                    <span class="badge bg-warning">في إجازة</span>
                                @else
                                    <span class="badge bg-secondary">{{ $employee->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد بيانات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Leave Requests -->
        <div class="content-section">
            <h2 class="section-title"><i class="fas fa-hourglass-half"></i> طلبات الإجازات المعلقة</h2>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>نوع الإجازة</th>
                            <th>من</th>
                            <th>إلى</th>
                            <th>الأيام</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingLeaves as $leave)
                        <tr>
                            <td>{{ $leave->employee->full_name }}</td>
                            <td>
                                @switch($leave->leave_type)
                                    @case('annual') إجازة سنوية @break
                                    @case('sick') إجازة مرضية @break
                                    @case('emergency') إجازة طارئة @break
                                    @case('unpaid') إجازة بدون راتب @break
                                    @default {{ $leave->leave_type }}
                                @endswitch
                            </td>
                            <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                            <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                            <td>{{ $leave->days_count }} يوم</td>
                            <td>
                                <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-action btn-approve">
                                        <i class="fas fa-check"></i> موافقة
                                    </button>
                                </form>
                                <button class="btn-action btn-reject" onclick="rejectLeave({{ $leave->id }})">
                                    <i class="fas fa-times"></i> رفض
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد طلبات معلقة</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function rejectLeave(leaveId) {
            const reason = prompt('سبب الرفض:');
            if (reason) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/hr/leaves/${leaveId}/reject`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = reason;
                
                form.appendChild(csrfInput);
                form.appendChild(reasonInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
