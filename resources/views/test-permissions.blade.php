<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار الصلاحيات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="padding: 2rem; background: #f5f5f5;">
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3><i class="fas fa-user-shield"></i> اختبار صلاحيات المستخدم</h3>
            </div>
            <div class="card-body">
                @auth
                <h4>معلومات المستخدم:</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>الاسم</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <th>البريد الإلكتروني</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <td>{{ Auth::user()->id }}</td>
                    </tr>
                </table>

                <h4 class="mt-4">الصلاحيات:</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>is_admin</th>
                        <td>
                            @if(Auth::user()->is_admin ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>is_it_super</th>
                        <td>
                            @if(Auth::user()->is_it_super ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>is_it</th>
                        <td>
                            @if(Auth::user()->is_it ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>is_cs_agent</th>
                        <td>
                            @if(Auth::user()->is_cs_agent ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>is_accountant</th>
                        <td>
                            @if(Auth::user()->is_accountant ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>is_driver_supervisor</th>
                        <td>
                            @if(Auth::user()->is_driver_supervisor ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="table-warning">
                        <th>is_hr</th>
                        <td>
                            @if(Auth::user()->is_hr ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="table-warning">
                        <th>is_hr_manager</th>
                        <td>
                            @if(Auth::user()->is_hr_manager ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>is_trader</th>
                        <td>
                            @if(Auth::user()->is_trader ?? false)
                                <span class="badge bg-success"><i class="fas fa-check"></i> نعم</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> لا</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="mt-4">
                    <h4>الروابط المتاحة:</h4>
                    <div class="list-group">
                        @if(Auth::user()->is_admin ?? false)
                        <a href="/admin/dashboard" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line"></i> لوحة الإدارة
                        </a>
                        @endif
                        @if(Auth::user()->is_it_super ?? false)
                        <a href="/it/dashboard" class="list-group-item list-group-item-action">
                            <i class="fas fa-laptop-code"></i> لوحة IT Supervisor
                        </a>
                        @endif
                        @if(Auth::user()->is_accountant ?? false)
                        <a href="/accounting/dashboard" class="list-group-item list-group-item-action">
                            <i class="fas fa-calculator"></i> لوحة المحاسبة
                        </a>
                        @endif
                        @if(Auth::user()->is_driver_supervisor ?? false)
                        <a href="/delivery/supervisor/dashboard" class="list-group-item list-group-item-action">
                            <i class="fas fa-truck"></i> لوحة مشرف التوصيل
                        </a>
                        @endif
                        @if(Auth::user()->is_hr ?? false)
                        <a href="/hr/dashboard" class="list-group-item list-group-item-action list-group-item-warning">
                            <i class="fas fa-users-cog"></i> لوحة الموارد البشرية
                        </a>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <a href="/" class="btn btn-primary"><i class="fas fa-home"></i> العودة للرئيسية</a>
                    <a href="/hr/dashboard" class="btn btn-success"><i class="fas fa-users-cog"></i> لوحة الموارد البشرية</a>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> يجب تسجيل الدخول أولاً
                </div>
                <a href="/ar-login" class="btn btn-primary">تسجيل الدخول</a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
