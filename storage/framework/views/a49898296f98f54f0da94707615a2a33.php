<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد التسجيل - Tulip Store</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
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
            background: #fff;
            min-height: 100vh;
            direction: rtl;
            font-family: 'El Messiri',sans-serif;
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
            justify-content: center;
            gap: 0;
            max-width: 950px;
            width: 950px;
            background: none;
        }
        .auth-illustration {
            position: absolute;
            top: 50%;
            left: -6%;
            transform: translateY(-50%);
            width: 70%;
            max-width: 820px;
            pointer-events: none;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            opacity: 0.9;
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
            width: 460px;
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
            margin-bottom: 1rem;
        }
        .auth-card p {
            text-align: center;
            color: #d3e7e2;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            font-family: 'El Messiri',sans-serif;
            font-weight: 300;
        }
        form{display:flex;flex-direction:column;gap:1.1rem;}
        .code-inputs{
            display:flex;
            gap:0.8rem;
            justify-content:center;
            direction:ltr;
            margin:1rem 0;
        }
        .code-inputs input{
            width:50px;
            height:50px;
            border:2px solid #d3e7e2;
            border-radius:12px;
            background:transparent;
            color:#fff;
            font-size:1.8rem;
            text-align:center;
            outline:none;
            transition: all 0.3s ease;
            font-family: 'El Messiri',sans-serif;
            font-weight: 400;
        }
        .code-inputs input:focus{
            border-color:#ffb48a;
            box-shadow: 0 0 12px rgba(255, 180, 138, 0.4);
            transform: scale(1.05);
        }
        .code-inputs input.success{
            border-color:#4ade80 !important;
            box-shadow: 0 0 15px rgba(74, 222, 128, 0.6) !important;
            animation: successPulse 0.6s ease;
        }
        .code-inputs input.error{
            border-color:#ef4444 !important;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.6) !important;
            animation: shake 0.5s ease;
        }
        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
        .resend-link{
            text-align:center;
            margin-top:1rem;
            font-size:0.95rem;
            font-family: 'El Messiri',sans-serif;
            font-weight: 300;
            color:#d3e7e2;
            cursor:pointer;
            transition: color 0.3s ease;
        }
        .resend-link:hover{
            color:#ffb48a;
        }
        .welcome-message{
            display:none;
            text-align:center;
            animation: fadeIn 0.5s ease;
        }
        .welcome-message.show{
            display:block;
        }
        .welcome-message .icon{
            font-size:4rem;
            color:#4ade80;
            margin-bottom:1rem;
            animation: bounce 0.6s ease;
        }
        .welcome-message h2{
            font-family: 'El Messiri', sans-serif;
            font-size:2rem;
            margin-bottom:0.5rem;
        }
        .welcome-message p{
            font-size:1.1rem;
            color:#d3e7e2;
        }
        @keyframes fadeIn {
            from { opacity:0; transform:translateY(20px); }
            to { opacity:1; transform:translateY(0); }
        }
        @keyframes bounce {
            0%, 100% { transform:translateY(0); }
            50% { transform:translateY(-20px); }
        }
        @media (max-width:900px){
            .auth-card-wrap{max-width:99vw;width:99vw;}
            .auth-illustration{width:75%;left:-10%;opacity:0.7;}
            .auth-card{border-radius:40px;padding:2rem 2rem;}
        }
        @media (max-width:700px){
            .auth-shell{padding:1rem;}
            .auth-card-wrap{flex-direction:column;align-items:center;gap:1rem;width:100vw;}
            .auth-illustration{
                position:static;
                transform:none;
                width:180px;
                opacity:0.7;
                justify-content:center;
            }
            .auth-illustration img{width:180px;}
            .auth-card{
                width:95vw;
                max-width:95vw;
                border-radius:30px;
                padding:1.5rem 1rem;
            }
            .auth-card h1{font-size:1.8rem;margin-bottom:0.8rem;}
            .auth-card p{font-size:0.85rem;margin-bottom:1rem;}
            .code-inputs{gap:0.5rem;margin:0.8rem 0;}
            .code-inputs input{width:38px;height:38px;font-size:1.4rem;}
            .action-btn{font-size:1rem;padding:0.6rem 0;}
            .resend-link{font-size:0.85rem;}
            .welcome-message .icon{font-size:3rem;}
            .welcome-message h2{font-size:1.6rem;}
            .welcome-message p{font-size:0.95rem;}
        }
        @media (max-width:400px){
            .code-inputs input{width:32px;height:32px;font-size:1.2rem;}
            .auth-card{padding:1.2rem 0.8rem;}
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
                <div id="codeForm">
                    <h1>تأكيد التسجيل</h1>
                    <p>تم إرسال رمز التحقق إلى بريدك الإلكتروني</p>
                    <form onsubmit="event.preventDefault();handleVerifyCode();">
                        <div class="code-inputs">
                            <input type="text" maxlength="1" id="code1" required>
                            <input type="text" maxlength="1" id="code2" required>
                            <input type="text" maxlength="1" id="code3" required>
                            <input type="text" maxlength="1" id="code4" required>
                            <input type="text" maxlength="1" id="code5" required>
                            <input type="text" maxlength="1" id="code6" required>
                        </div>
                        <button class="action-btn" type="submit">تحقق</button>
                        <div class="resend-link" onclick="resendCode()">
                            إعادة إرسال الكود
                        </div>
                    </form>
                </div>
                <div class="welcome-message" id="welcomeMsg">
                    <div class="icon">🎉</div>
                    <h2>مرحباً بك!</h2>
                    <p id="userName"></p>
                    <p style="font-size:0.9rem;margin-top:1rem;">جاري التحويل للصفحة الرئيسية...</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll('.code-inputs input');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Remove any error/success classes when typing
                inputs.forEach(inp => inp.classList.remove('error', 'success'));
                
                if(e.target.value.length === 1) {
                    if(index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else {
                        // Auto-check when 6th digit is entered
                        console.log('6th digit entered, auto-checking...');
                        setTimeout(() => handleVerifyCode(), 100);
                    }
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if(e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        async function handleVerifyCode() {
            const code = Array.from(inputs).map(input => input.value).join('');
            if(code.length !== 6) return;

            try {
                const response = await fetch('/api/verify-registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code })
                });

                const data = await response.json();

                if (data.success) {
                    // Show success - green glow
                    inputs.forEach(input => {
                        input.classList.remove('error');
                        input.classList.add('success');
                    });
                    
                    // Show welcome message after animation
                    setTimeout(() => {
                        document.getElementById('codeForm').style.display = 'none';
                        document.getElementById('welcomeMsg').classList.add('show');
                        document.getElementById('userName').textContent = data.user_name;
                        
                        // Redirect after 3 seconds
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 3000);
                    }, 800);
                } else {
                    // Show error - red glow
                    inputs.forEach(input => {
                        input.classList.remove('success');
                        input.classList.add('error');
                    });
                    
                    // Clear after animation
                    setTimeout(() => {
                        inputs.forEach(input => {
                            input.value = '';
                            input.classList.remove('error');
                        });
                        inputs[0].focus();
                    }, 1000);
                }
            } catch (error) {
                // Show error - red glow
                inputs.forEach(input => {
                    input.classList.remove('success');
                    input.classList.add('error');
                });
                
                setTimeout(() => {
                    inputs.forEach(input => {
                        input.value = '';
                        input.classList.remove('error');
                    });
                    inputs[0].focus();
                }, 1000);
            }
        }

        function resendCode() {
            alert('تم إرسال الكود مرة أخرى');
        }

        // Auto-focus first input
        inputs[0].focus();
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/pages/ar-verify-registration.blade.php ENDPATH**/ ?>