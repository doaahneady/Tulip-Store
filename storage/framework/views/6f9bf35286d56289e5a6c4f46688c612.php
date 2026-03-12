<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Tulip Store</title>
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url('/images/background-pattern.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            direction: rtl;
            font-family: 'El Messiri',sans-serif;
            font-weight: 400;
            height: 100%;
        }
        .home-logo {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            z-index: 1000;
            background: white;
        }
        .home-logo:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(255,111,53,0.5);
        }
        .home-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: auto;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }
        .auth-shell {
            height: 100vh;
            width: 100vw;
            max-width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        .auth-card-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0;
            max-width: 1040px;
            width: 1040px;
            background: none;
            margin: 0 auto;
            padding-left: 50px;
        }
        .auth-illustration {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            max-width: 820px;
            pointer-events: none;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0.9;
            z-index: 0;
        }
        .auth-illustration img {
            width: 100%;
            height: auto;
            max-width:none;
            object-fit:contain;
            filter: none;
            background: transparent;
            display: block;
        }
        .auth-card {
            position: relative;
            z-index: 1;
            background: #0f4f55;
            border-radius: 90px;
            padding: 2.4rem 3rem;
            width: 520px;
            color: #fff;
            box-shadow: 0 18px 40px rgba(0,0,0,0.10);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-card h1 {
            font-family: 'El Messiri', sans-serif;
            font-weight: 600;
            font-size: 2.4rem;
            text-align: center;
            margin-bottom: 1.6rem;
        }
        form{display:flex;flex-direction:column;gap:1.1rem;}
        label{
            display:none;
        }
        .control{
            position:relative;
        }
        input{
            width:100%;
            border:none;
            border-bottom:2px solid #d3e7e2;
            background:transparent;
            color:#fff;
            padding:0.5rem 0 0.5rem 2rem;
            font-size:0.95rem;
            text-align:center;
            outline:none;
            transition: border-bottom 0.3s ease, box-shadow 0.3s ease, color 0.3s ease;
            font-family: 'El Messiri',sans-serif;
            font-weight: 400;
        }
        input::placeholder{
            color:#d3e7e2;
            font-family:'El Messiri',sans-serif;
            font-weight: 300;
        }
        input:focus{
            border-bottom:2px solid #ffb48a;
            box-shadow: 0 2px 8px rgba(255, 180, 138, 0.4);
        }
        input.error{
            border-bottom:2px solid #ef4444 !important;
            box-shadow: 0 2px 12px rgba(239, 68, 68, 0.6) !important;
            animation: shake 0.5s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .toggle{
            position:absolute;
            left:0;
            top:50%;
            transform:translateY(-50%);
            color:#d3e7e2;
            cursor:pointer;
            transition: all 0.3s ease;
        }
        .toggle:hover{
            color:#ffb48a;
            transform: translateY(-50%) scale(1.1);
        }
        .error-message{
            background:rgba(248,113,113,0.2);
            border:1px solid #f87171;
            color:#fff;
            padding:0.7rem;
            border-radius:10px;
            font-size:0.9rem;
            text-align:center;
            margin-bottom:0.5rem;
            display:none;
        }
        .error-message.show{
            display:block;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity:0;
                transform:translateY(-10px);
            }
            to {
                opacity:1;
                transform:translateY(0);
            }
        }
        .hint-row, .sign-row{
            display:flex;
            justify-content:flex-end;
            gap:0.4rem;
            font-size:0.95rem;
            font-family: 'El Messiri',sans-serif;
            font-weight: 300;
        }
        .hint-row{
            cursor: pointer;
        }
        .hint-row span, .sign-row span{
            cursor:pointer;
            transition: color 0.3s ease;
        }
        .hint-row:hover, .sign-row span:hover{
            color:#ffb48a;
        }
        .action-btn{
            background:#ff6f35;
            border:none;
            color:#fff;
            padding:0.65rem 0;
            border-radius:18px;
            font-size:1.15rem;
            font-weight:600;
            box-shadow:0 12px 25px rgba(255,111,53,0.25);
            cursor:pointer;
            transition: transform 0.2s ease;
        }
        .action-btn:hover{
            transform: translateY(-2px);
        }
        .google-btn{
            margin-top:0.5rem;
            background:#fff;
            border:none;
            border-radius:20px;
            padding:0.55rem 0.9rem;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:0.55rem;
            color:#1d2b3a;
            font-weight:600;
            cursor:pointer;
            transition: transform 0.2s ease;
        }
        .google-btn:hover{
            transform: translateY(-2px);
        }
        .google-btn img{
            width:24px;
            height:24px;
            display:block;
        }
        @media (max-width:900px){
            .auth-card-wrap{max-width:99vw;width:99vw;}
            .auth-illustration{width:75%;left:-10%;opacity:0.7;}
            .auth-card{border-radius:40px;}
        }
        @media (max-width:700px){
            .home-logo {
                width: 65px;
                height: 65px;
                top: 15px;
                left: 15px;
            }
            .auth-shell{padding:1rem;}
            .auth-card-wrap{
                flex-direction:column;
                align-items:center;
                gap:1.5rem;
                width:100%;
                padding:0;
            }
            .auth-illustration{
                position:static;
                transform:none;
                width:200px;
                opacity:0.7;
                justify-content:center;
            }
            .auth-illustration img{width:200px;}
            .auth-card{
                width:95%;
                max-width:95%;
                border-radius:30px;
                padding:1.8rem 1.2rem;
            }
            .auth-card h1{font-size:1.8rem;margin-bottom:1.2rem;}
            form{gap:0.9rem;}
            input,button{font-size:0.9rem;padding:0.7rem 1rem;}
        }
        @media (max-width:480px){
            .home-logo {
                width: 55px;
                height: 55px;
                top: 10px;
                left: 10px;
            }
            .auth-card{
                width:90%;
                max-width:90%;
                border-radius:25px;
                padding:1.5rem 1rem;
            }
            .auth-card h1{font-size:1.5rem;margin-bottom:1rem;}
            .auth-illustration{width:180px;}
            .auth-illustration img{width:180px;}
            input,button{font-size:0.85rem;padding:0.6rem 0.9rem;}
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card-wrap">
            <div class="auth-illustration">
                <img src="/images/photo_2025-11-17_11-18-40.jpg" alt="Tulip illustration">
            </div>
            <div class="auth-card">
                <h1>تسجيل الدخول</h1>
                <div class="error-message" id="errorMsg"></div>
                <form onsubmit="handleLogin(event)" id="loginForm">
                    <div>
                        <label for="loginEmail">رقم الجوال أو البريد الإلكتروني</label>
                        <div class="control">
                            <input id="loginEmail" type="text" placeholder="اكتب الايميل أو الرقم" dir="ltr" title="اكتب الايميل">
                        </div>
                    </div>
                    <div>
                        <label for="loginPass">كلمة السر</label>
                        <div class="control">
                            <input id="loginPass" type="password" placeholder="أدخل كلمة المرور الخاصة بك هنا">
                            <span class="toggle" data-target="loginPass"><i class="fa fa-eye"></i></span>
                        </div>
                        <div class="hint-row" onclick="window.location.href='/ar-forgot-password'">هل نسيت كلمة المرور؟</div>
                    </div>
                    <div class="sign-row">
                        <span>ليس لديك حساب ؟</span>
                        <span style="color:#ffb48a;" onclick="window.location.href='/register'">أنشئه هنا</span>
                    </div>
                    <button class="action-btn" type="submit">متابعة</button>
                    <button class="google-btn" type="button" onclick="window.open('/auth/google/redirect', '_blank')">
                        <img src="https://www.google.com/favicon.ico" alt="Google logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg'">
                        Login with Google
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Smooth password toggle
        document.querySelectorAll('.toggle').forEach(t => {
            const input = document.getElementById(t.dataset.target);
            if(!input) return;
            t.addEventListener('click', () => {
                const hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';
                t.style.transform = 'translateY(-50%) rotate(360deg)';
                setTimeout(() => {
                    t.innerHTML = `<i class="fa ${hidden ? 'fa-eye-slash' : 'fa-eye'}"></i>`;
                    t.style.transform = 'translateY(-50%) rotate(0deg)';
                }, 150);
            });
        });

        // Handle login
        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPass').value;
            const errorMsg = document.getElementById('errorMsg');
            const submitBtn = e.target.querySelector('.action-btn');

            // Remove all error classes first
            document.querySelectorAll('input').forEach(inp => inp.classList.remove('error'));
            errorMsg.classList.remove('show');

            // Check if all fields are filled together
            let hasEmptyFields = false;
            if (!email || email.trim() === '') {
                document.getElementById('loginEmail').classList.add('error');
                hasEmptyFields = true;
            }
            if (!password || password.trim() === '') {
                document.getElementById('loginPass').classList.add('error');
                hasEmptyFields = true;
            }

            if (hasEmptyFields) {
                errorMsg.textContent = 'املأ جميع الحقول';
                errorMsg.classList.add('show');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري التحميل...';

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    errorMsg.textContent = data.message;
                    errorMsg.classList.add('show');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'متابعة';
                }
            } catch (error) {
                errorMsg.textContent = 'حدث خطأ، يرجى المحاولة مرة أخرى';
                errorMsg.classList.add('show');
                submitBtn.disabled = false;
                submitBtn.textContent = 'متابعة';
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/pages/ar-login.blade.php ENDPATH**/ ?>