<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>أدخل الكود - Tulip Store</title>
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
            font-family:'El Messiri',sans-serif;
            font-weight: 300;
            color:#d3e7e2;
            cursor:pointer;
            transition: color 0.3s ease;
        }
        .resend-link:hover{
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
            .code-inputs input{width:45px;height:45px;font-size:1.5rem;}
            button{font-size:0.9rem;padding:0.7rem 1rem;}
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
            .code-inputs input{width:40px;height:40px;font-size:1.3rem;}
            button{font-size:0.85rem;padding:0.6rem 0.9rem;}
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
                <h1>أدخل الكود</h1>
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
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll('.code-inputs input');
        
        // Auto-focus on first input when page loads
        window.addEventListener('DOMContentLoaded', () => {
            if (inputs[0]) {
                inputs[0].focus();
            }
        });
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Remove any error/success classes when typing
                inputs.forEach(inp => inp.classList.remove('error', 'success'));
                
                if(e.target.value.length === 1) {
                    if(index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else {
                        // Auto-check when 6th digit is entered
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

        function getQueryParam(name) {
            const params = new URLSearchParams(window.location.search);
            return params.get(name);
        }

        async function handleVerifyCode() {
            const code = Array.from(inputs).map(input => input.value).join('');
            if(code.length !== 6) return;

            const target = getQueryParam('target');
            const isEmailChange = target === 'email-change';

            try {
                let ok = false;
                if (isEmailChange) {
                    const newEmail = sessionStorage.getItem('email_change:new_email');
                    if (!newEmail) throw new Error('لا يوجد بريد جديد محفوظ');
                    const res = await fetch('/profile/email/verify-confirm', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ new_email: newEmail, code })
                    });
                    const data = await res.json();
                    ok = res.ok && data.success;

                    // Apply pending non-email updates if exist
                    if (ok) {
                        const pending = sessionStorage.getItem('profile_pending_update');
                        if (pending) {
                            try {
                                const payload = JSON.parse(pending);
                                await fetch('/profile/update', {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                    },
                                    body: JSON.stringify({
                                        name: payload.name,
                                        phone: payload.phone,
                                        address: payload.address
                                    })
                                });
                            } catch (_) {}
                        }
                    }
                } else {
                    const response = await fetch('/api/verify-code', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ code })
                    });
                    const data = await response.json();
                    ok = !!data.success;
                }

                if (ok) {
                    inputs.forEach(input => {
                        input.classList.remove('error');
                        input.classList.add('success');
                    });
                    
                    setTimeout(() => {
                        if (isEmailChange) {
                            sessionStorage.removeItem('email_change:new_email');
                            sessionStorage.removeItem('profile_pending_update');
                            window.location.href = '/profile';
                        } else {
                            window.location.href = '/ar-reset-password';
                        }
                    }, 800);
                } else {
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
            } catch (error) {
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

        async function resendCode() {
            const target = getQueryParam('target');
            const isEmailChange = target === 'email-change';
            if (isEmailChange) {
                const newEmail = sessionStorage.getItem('email_change:new_email');
                if (!newEmail) { alert('لا يوجد بريد جديد محفوظ'); return; }
                try {
                    await fetch('/profile/email/verify-request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ new_email: newEmail })
                    });
                    alert('تم إرسال الكود مرة أخرى');
                } catch (_) {
                    alert('تعذر إعادة الإرسال');
                }
            } else {
                alert('تم إرسال الكود مرة أخرى');
                // Default flow: call API to resend password reset code
            }
        }
    </script>
</body>
</html>
