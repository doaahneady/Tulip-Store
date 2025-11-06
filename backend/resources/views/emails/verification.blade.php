<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Tulip Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            color: #0D464C;
            margin-bottom: 30px;
        }
        .code-box {
            background-color: #fff;
            border: 2px solid #F05928;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            color: #F05928;
            letter-spacing: 5px;
            font-family: monospace;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tulip Store</h1>
            <h2>Email Verification</h2>
        </div>
        
        <p>Hello {{ $name }},</p>
        
        <p>Thank you for registering with Tulip Store. Please use the verification code below to verify your email address:</p>
        
        <div class="code-box">
            <div class="verification-code">{{ $code }}</div>
        </div>
        
        <p>This code will expire in 15 minutes. If you didn't request this verification code, please ignore this email.</p>
        
        <p>Best regards,<br>Tulip Store Team</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} Tulip Store. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

