<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>بوابة الموظفين - متجر توليب</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8f9fa;
        }
        
        .split-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Left Side - Branding */
        .brand-side {
            flex: 1;
            background: linear-gradient(135deg, #0D464C 0%, #1a6b75 50%, #2d8a8a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .brand-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }
        
        .brand-logo {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.15);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
        }
        
        .brand-logo i {
            font-size: 4rem;
            color: white;
        }
        
        .brand-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .brand-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            line-height: 1.8;
        }
        
        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            text-align: right;
        }
        
        .brand-feature {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255,255,255,0.1);
            padding: 1rem 1.5rem;
            border-radius: 15px;
            backdrop-filter: blur(5px);
        }
        
        .brand-feature i {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .brand-feature span {
            font-size: 1rem;
        }
        
        /* Right Side - Login Form */
        .form-side {
            flex: 1;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
        }
        
        .form-container {
            width: 100%;
            max-width: 450px;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .form-header h1 {
            font-family: 'El Messiri', sans-serif;
            font-size: 2rem;
            color: #0D464C;
            margin-bottom: 0.5rem;
        }
        
        .form-header p {
            color: #666;
            font-size: 1rem;
        }
        
        .login-form {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: #0D464C;
            background: white;
            box-shadow: 0 0 0 4px rgba(13, 70, 76, 0.1);
        }
        
        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
        }
        
        .input-wrapper .toggle-password {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .input-wrapper .toggle-password:hover {
            color: #0D464C;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .remember-me input {
            width: 18px;
            height: 18px;
            accent-color: #0D464C;
        }
        
        .remember-me label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .forgot-password {
            color: #0D464C;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0D464C 0%, #1a6b75 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(13, 70, 76, 0.3);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            color: #999;
            font-size: 0.9rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }
        
        .divider span {
            padding: 0 1rem;
        }
        
        .alt-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .alt-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .alt-link:hover {
            border-color: #0D464C;
            color: #0D464C;
            background: rgba(13, 70, 76, 0.05);
        }
        
        .alt-link.trader {
            border-color: #7b1fa2;
            color: #7b1fa2;
        }
        
        .alt-link.trader:hover {
            background: rgba(123, 31, 162, 0.05);
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .success-message {
            background: #efe;
            border: 1px solid #cfc;
            color: #060;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .split-container {
                flex-direction: column;
            }
            
            .brand-side {
                padding: 2rem;
                min-height: auto;
            }
            
            .brand-features {
                display: none;
            }
            
            .form-side {
                padding: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .login-form {
                padding: 1.5rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Brand Side -->
        <div class="brand-side">
            <div class="brand-content">
                <div class="brand-logo">
                    <i class="fas fa-id-badge"></i>
                </div>
                <h1 class="brand-title">بوابة الموظفين</h1>
                <p class="brand-subtitle">
                    نظام إدارة متكامل للموظفين<br>
                    الوصول إلى لوحات التحكم المختلفة
                </p>
                
                <div class="brand-features">
                    <div class="brand-feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>نظام آمن ومحمي</span>
                    </div>
                    <div class="brand-feature">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>لوحات تحكم متعددة</span>
                    </div>
                    <div class="brand-feature">
                        <i class="fas fa-users-cog"></i>
                        <span>إدارة شاملة للعمليات</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Side -->
        <div class="form-side">
            <div class="form-container">
                <div class="form-header">
                    <h1>تسجيل دخول الموظفين</h1>
                    <p>أدخل بياناتك للوصول إلى لوحة التحكم</p>
                </div>
                
                <div class="login-form">
                    <?php if(session('info')): ?>
                        <div class="success-message">
                            <i class="fas fa-info-circle"></i>
                            <?php echo e(session('info')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('message')): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <?php echo e(session('message')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($errors->first()); ?>

                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo e(route('employee.login')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني</label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="example@company.com">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password" required placeholder="••••••••">
                                <button type="button" class="toggle-password" onclick="togglePassword()">
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">تذكرني</label>
                            </div>
                            <a href="#" class="forgot-password">نسيت كلمة المرور؟</a>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            تسجيل الدخول
                        </button>
                    </form>
                    
                    <div class="divider">
                        <span>أو</span>
                    </div>
                    
                    <div class="alt-links">
                        <?php if(auth()->guard('employee')->check()): ?>
                            <?php if(auth('employee')->user()->is_admin): ?>
                                <a href="<?php echo e(route('dashboard.admin.downloads')); ?>" class="alt-link" style="border-color: #0D464C; color: #0D464C;">
                                    <i class="fas fa-download"></i>
                                    تحميل البيانات (Admin)
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <a href="<?php echo e(route('trader.login.form')); ?>" class="alt-link trader">
                            <i class="fas fa-store"></i>
                            بوابة التجار
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="alt-link">
                            <i class="fas fa-user"></i>
                            تسجيل دخول العملاء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
<?php /**PATH D:\Tulip-Store\resources\views/auth/employee-login.blade.php ENDPATH**/ ?>