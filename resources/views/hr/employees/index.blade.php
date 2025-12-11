<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container-custom {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #667eea;
        }

        .page-header h1 {
            color: #667eea;
            font-weight: bold;
            margin: 0;
        }

        .btn-custom {
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .search-box {
            margin-bottom: 1.5rem;
        }

        .search-box input {
            border-radius: 10px;
            padding: 0.8rem;
            border: 2px solid #e5e7eb;
        }

        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .employee-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .employee-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .employee-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .employee-info h3 {
            color: #1f2937;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .employee-code {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .employee-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item i {
            color: #667eea;
            width: 20px;
        }

        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm-custom {
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-sm-custom:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> إدارة الموظفين</h1>
            <div>
                <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom me-2">
                    <i class="fas fa-arrow-right"></i> العودة
                </a>
                <a href="{{ route('hr.employees.create') }}" class="btn btn-primary btn-custom">
                    <i class="fas fa-plus"></i> إضافة موظف جديد
                </a>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="البحث عن موظف...">
        </div>

        <!-- Employees List -->
        <div id="employeesList">
            @forelse($employees as $employee)
            <div class="employee-card">
                <div class="employee-header">
                    <div class="employee-info">
                        <h3>{{ $employee->full_name }}</h3>
                        <span class="employee-code">{{ $employee->employee_code }}</span>
                    </div>
                    <div>
                        @if($employee->status === 'active')
                            <span class="badge bg-success">نشط</span>
                        @elseif($employee->status === 'on_leave')
                            <span class="badge bg-warning">في إجازة</span>
                        @elseif($employee->status === 'suspended')
                            <span class="badge bg-danger">موقوف</span>
                        @else
                            <span class="badge bg-secondary">{{ $employee->status }}</span>
                        @endif
                    </div>
                </div>

                <div class="employee-details">
                    <div class="detail-item">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ $employee->position }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $employee->department }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $employee->email }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ $employee->phone }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>تاريخ التعيين: {{ $employee->hire_date->format('Y-m-d') }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-money-bill"></i>
                        <span>الراتب: {{ number_format($employee->salary, 2) }} ريال</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('hr.employees.edit', $employee) }}" class="btn btn-primary btn-sm-custom">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <button class="btn btn-info btn-sm-custom" onclick="viewDetails({{ $employee->id }})">
                        <i class="fas fa-eye"></i> عرض التفاصيل
                    </button>
                    <button class="btn btn-danger btn-sm-custom" onclick="deleteEmployee({{ $employee->id }})">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <p class="text-muted">لا يوجد موظفين حالياً</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.employee-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

        function viewDetails(employeeId) {
            alert('عرض تفاصيل الموظف #' + employeeId);
            // Implement view details functionality
        }

        function deleteEmployee(employeeId) {
            if (confirm('هل أنت متأكد من حذف هذا الموظف؟')) {
                // Implement delete functionality
                alert('تم حذف الموظف');
            }
        }
    </script>
</body>
</html>
