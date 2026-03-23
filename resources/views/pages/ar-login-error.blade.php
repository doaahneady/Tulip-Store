<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Tulip Store</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #fff8f4;
            direction: rtl;
            font-family: 'El Messiri',sans-serif;
        }
        .ar-auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100vw;
        }
        .auth-illustration {
            height: 400px;
            width: 260px;
            flex-shrink: 0;
            background: none;
            position: relative;
            display: flex;
            align-items: center;
        }
        .auth-illustration img {
            height: 100%;
            width: 100%;
            object-fit: contain;
            mix-blend-mode: multiply;
            opacity: 1;
        }
        .auth-card-box {
            background: #0f4c5c;
            color: #fff;
            min-width: 390px;
            max-width: 440px;
            border-radius: 48px;
            padding: 2.3rem 2.1rem 2rem 2.1rem;
            box-shadow: 0 4px 40px rgba(0,0,0,0.06);
            margin-right: -64px;
            z-index: 2;
            position: relative;
        }
        .auth-logo-title {
            font-family: 'El Messiri',sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.6rem;
        }
        .ar-auth-label {
            color: #c8e6e5;
            font-size: 1.08rem;
            margin-bottom: 0.6rem;
            display: block;
        }
        .ar-auth-input-block {
            margin-bottom: 1.1rem;
        }
        .ar-auth-input {
            width: 100%;
            padding: 0.4rem 0.7rem;
            border: none;
            background: transparent;
            border-bottom: 2px solid #fbe6cf;
            color: #fff;
            font-size: 1.1rem;
            outline: none;
            margin-bottom: 6px;
        }
        .ar-auth-input.error {
            border-bottom: 2px solid #D46A44;
            color: #D46A44;
            background: #fff8f4;
        }
        .ar-auth-input::placeholder {
            color: #fbe6cf;
            font-size: 1.01rem;
        }
        .ar-auth-input.error::placeholder {
            color: #D46A44;
        }
        .ar-auth-hint {
            color: #fbe6cf;
            text-align: left;
            margin-bottom: 0.35rem;
            font-size: 0.94rem;
        }
        .ar-auth-row {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.5rem;
        }
        .ar-auth-link {
            color: #ff8250;
            text-decoration: underline;
            font-weight: 500;
            cursor: pointer;
        }
        .ar-auth-link-btn {
            color: #fff;
            background: #ff8250;
            padding: 0.5rem 0.5rem;
            border: none;
            margin: 0 auto;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 16px;
            transition: background 0.2s;
            width: 100%;
            margin-top: 1.3rem;
            margin-bottom: 0.8rem;
            display: block;
            letter-spacing: 0.01rem;
        }
        .ar-auth-link-btn:active,
        .ar-auth-link-btn:hover {
            background: #f3683b;
        }
        .ar-auth-google {
            background: #fff;
            color: #444;
            border-radius: 24px;
            padding: 0.2rem 0.85rem;
            width: 100%;
            border: none;
            font-size: 1.01rem;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.7rem;
            letter-spacing: -0.01rem;
            gap: 0.5rem;
        }
        .ar-auth-google img {
            height: 25px;
            margin-left: 2px;
        }
        .ar-auth-error {
            color: #d46a44;
            font-size: 1.09rem;
            margin-top: 0.5rem;
            background: #fff8f4;
            border-radius: 8px;
            padding: 0.5rem;
            text-align: right;
            border: 1.5px solid #d46a44;
        }
        @media (max-width:600px) {
            .ar-auth-container {
                flex-direction: column;
            }
            .auth-illustration, .auth-card-box {
                margin: 0 auto;
                max-width: 99vw;
                min-width: 0;
            }
            .auth-card-box { margin: 0 auto; }
        }
    </style>
</head>
<body>
    <div class="ar-auth-container">
        <div class="auth-illustration">
            <img src="/images/signUp.jpg" alt="Illustration" />
        </div>
        <div class="auth-card-box">
            <div class="auth-logo-title">تسجيل الدخول</div>
            <form>
                <label class="ar-auth-label">رقم الجوال أو البريد الإلكتروني</label>
                <div class="ar-auth-input-block">
                    <input class="ar-auth-input error" type="text" placeholder="أدخل اسمك الكامل هنا">
                </div>
                <label class="ar-auth-label">كلمة السر</label>
                <div class="ar-auth-input-block" style="position:relative;">
                    <input class="ar-auth-input" type="password" placeholder="أدخل كلمة المرور الخاصة بك هنا" style="padding-left:2.4rem;">
                    <span style="position:absolute;left:5px;top:5px;color:#c8e6e5;"><i class="fa fa-eye"></i></span>
                </div>
                <div class="ar-auth-hint">هل نسيت كلمة المرور؟</div>
                <div class="ar-auth-row" style="justify-content: flex-end;">
                    <span>ليس لديك حساب ؟</span>
                    <span class="ar-auth-link">أنشئه هنا</span>
                </div>
                <button type="submit" class="ar-auth-link-btn">متابعة</button>
                <div style="color:#abcdd0; text-align:center; font-size:0.92rem; margin-bottom:0.8rem;">- أو سجل دخول بواسطة -</div>
                <button type="button" class="ar-auth-google"><img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google Logo"/>Sign up with Google</button>
            </form>
        </div>
    </div>
</body>
</html>
