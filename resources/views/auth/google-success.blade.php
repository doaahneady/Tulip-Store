<!DOCTYPE html>
<html>
<head>
    <title>تسجيل الدخول بنجاح</title>
    <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <style>
        body {
            font-family: "El Messiri", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #2a7080 0%, #0f4f55 100%);
            color: white;
        }
        .success-message {
            text-align: center;
            padding: 2rem;
        }
        .success-message h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .success-message p {
            font-size: 1.2rem;
        }
        .spinner {
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 1rem auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="success-message">
        <h1>✓ تم تسجيل الدخول بنجاح</h1>
        <div class="spinner"></div>
        <p>جاري تحويلك...</p>
    </div>

    <script>
        // Close popup and redirect parent window
        if (window.opener) {
            // If opened in popup, redirect parent and close popup
            window.opener.location.href = '/';
            window.close();
        } else {
            // If not in popup, just redirect
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        }
    </script>
</body>
</html>
