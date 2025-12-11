<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الموظف</title>
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
            max-width: 900px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #667eea;
        }

        .page-header h1 {
            color: #667eea;
            font-weight: bold;
        }

        .form-section {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-section h3 {
            color: #1f2937;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            padding: 0.6rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .required {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-user-edit"></i> تعديل بيانات الموظف</h1>
                <a href="{{ route('hr.employees') }}" class="btn btn-outline-secondary btn-custom">
                    <i class="fas fa-arrow-right"></i> العودة
                </a>
            </div>
        </div>

        <form action="{{ route('hr.employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="form-section">
                <h3><i class="fas fa-user"></i> المعلومات الشخصية</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الاسم الأول <span class="required">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ $employee->first_name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم العائلة <span class="required">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ $employee->last_name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">البريد الإلكتروني <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">رقم الجوال <span class="required">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}" required>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="form-section">
                <h3><i class="fas fa-briefcase"></i> معلومات التوظيف</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">القسم <span class="required">*</span></label>
                        <input type="text" name="department" class="form-control" value="{{ $employee->department }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">المنصب <span class="required">*</span></label>
                        <input type="text" name="position" class="form-control" value="{{ $employee->position }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الراتب (ريال) <span class="required">*</span></label>
                        <input type="number" name="salary" class="form-control" step="0.01" value="{{ $employee->salary }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="on_leave" {{ $employee->status === 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                            <option value="suspended" {{ $employee->status === 'suspended' ? 'selected' : '' }}>موقوف</option>
                            <option value="terminated" {{ $employee->status === 'terminated' ? 'selected' : '' }}>منتهي</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-custom">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
                <a href="{{ route('hr.employees') }}" class="btn btn-secondary btn-custom">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
