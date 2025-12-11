<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الإجازات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
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
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #06b6d4;
        }

        .page-header h1 {
            color: #06b6d4;
            font-weight: bold;
        }

        .leave-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 5px solid #06b6d4;
            transition: all 0.3s ease;
        }

        .leave-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .leave-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .leave-info h3 {
            color: #1f2937;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .leave-details {
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
            color: #06b6d4;
            width: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-custom {
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-calendar-alt"></i> طلبات الإجازات</h1>
                <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom">
                    <i class="fas fa-arrow-right"></i> العودة
                </a>
            </div>
        </div>

        @forelse($leaves as $leave)
        <div class="leave-card">
            <div class="leave-header">
                <div class="leave-info">
                    <h3>{{ $leave->employee->full_name }}</h3>
                    <span class="text-muted">{{ $leave->employee->employee_code }}</span>
                </div>
                <div>
                    @switch($leave->status)
                        @case('pending')
                            <span class="badge bg-warning">معلق</span>
                            @break
                        @case('approved')
                            <span class="badge bg-success">موافق عليه</span>
                            @break
                        @case('rejected')
                            <span class="badge bg-danger">مرفوض</span>
                            @break
                    @endswitch
                </div>
            </div>

            <div class="leave-details">
                <div class="detail-item">
                    <i class="fas fa-tag"></i>
                    <span>
                        @switch($leave->leave_type)
                            @case('annual') إجازة سنوية @break
                            @case('sick') إجازة مرضية @break
                            @case('emergency') إجازة طارئة @break
                            @case('unpaid') إجازة بدون راتب @break
                            @case('maternity') إجازة أمومة @break
                            @case('paternity') إجازة أبوة @break
                        @endswitch
                    </span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar-day"></i>
                    <span>من: {{ $leave->start_date->format('Y-m-d') }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar-day"></i>
                    <span>إلى: {{ $leave->end_date->format('Y-m-d') }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $leave->days_count }} يوم</span>
                </div>
            </div>

            <div class="mb-3">
                <strong>السبب:</strong>
                <p class="mb-0 text-muted">{{ $leave->reason }}</p>
            </div>

            @if($leave->status === 'pending')
            <div class="action-buttons">
                <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-custom">
                        <i class="fas fa-check"></i> موافقة
                    </button>
                </form>
                <button class="btn btn-danger btn-custom" onclick="rejectLeave({{ $leave->id }})">
                    <i class="fas fa-times"></i> رفض
                </button>
            </div>
            @endif

            @if($leave->status === 'rejected' && $leave->rejection_reason)
            <div class="mt-3 alert alert-danger">
                <strong>سبب الرفض:</strong> {{ $leave->rejection_reason }}
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <p class="text-muted">لا توجد طلبات إجازات</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $leaves->links() }}
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
