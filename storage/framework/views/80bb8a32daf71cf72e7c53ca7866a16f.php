<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نسيت كلمة المرور - Tulip Store</title>
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
            font-family:'El Messiri',sans-serif;
            font-weight: 400;
            height: 100%;
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
        label{display:none;}
        .control{position:relative;}
        input{
            width:100%;
            border:none;
            border-bottom:2px solid #d3e7e2;
            background:transparent;
            color:#fff;
            padding:0.5rem 0 0.5rem 2rem;
            font-size:1.05rem;
            text-align:center;
            outline:none;
            transition: border-bottom 0.3s ease, box-shadow 0.3s ease;
            font-family: 'El Messiri',sans-serif;
            font-weight: 400;
        }
        input::placeholder{
            color:#d3e7e2;
            font-family: 'El Messiri',sans-serif;
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
        .back-link{
            text-align:center;
            margin-top:1rem;
            font-size:0.95rem;
            font-family:'El Messiri',sans-serif;
            font-weight: 300;
            color:#d3e7e2;
            cursor:pointer;
            transition: color 0.3s ease;
        }
        .back-link:hover{
            color:#ffb48a;
        }
        @media (max-width:900px){
            .auth-card-wrap{max-width:99vw;width:99vw;}
            .auth-illustration{width:75%;left:-10%;opacity:0.7;}
            .auth-card{border-radius:40px;}
        }
        @media (max-width:700px){
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
                <img src="/images/logo-girl.jpg" alt="Tulip illustration">
            </div>
            <div class="auth-card">
                <h1>نسيت كلمة المرور</h1>
                <form onsubmit="event.preventDefault();handleForgotPassword();">
                    <div>
                        <label for="forgotEmail">البريد الإلكتروني</label>
                        <div class="control">
                            <input id="forgotEmail" type="email" placeholder="أدخل بريدك الإلكتروني" required>
                        </div>
                    </div>
                    <button class="action-btn" type="submit">متابعة</button>
                    <div class="back-link" onclick="window.location.href='/ar-login'">
                        العودة لتسجيل الدخول
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Email validation
        const emailInput = document.getElementById('forgotEmail');
        emailInput.addEventListener('input', function() {
            const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
            if (this.value.length > 0) {
                if (!emailPattern.test(this.value)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            } else {
                this.classList.remove('error');
            }
        });

        async function handleForgotPassword() {
            const email = document.getElementById('forgotEmail').value;
            const submitBtn = document.querySelector('.action-btn');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري الإرسال...';

            try {
                const response = await fetch('/api/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '/ar-verify-code';
                } else {
                    alert(data.message || 'حدث خطأ');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'متابعة';
                }
            } catch (error) {
                alert('حدث خطأ، يرجى المحاولة مرة أخرى');
                submitBtn.disabled = false;
                submitBtn.textContent = 'متابعة';
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/pages/ar-forgot-password.blade.php ENDPATH**/ ?>