<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            .header-title {
                font-size: 28px !important;
            }
            .header-subtitle {
                font-size: 14px !important;
            }
            .content-padding {
                padding: 30px 20px !important;
            }
            .main-title {
                font-size: 24px !important;
            }
            .code-box {
                padding: 20px !important;
            }
            .verification-code {
                font-size: 36px !important;
                letter-spacing: 8px !important;
            }
            .text-content {
                font-size: 14px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: 'El Messiri', sans-serif; background: linear-gradient(135deg, #0f4f55 0%, #1a7a7f 100%); direction: rtl;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #0f4f55 0%, #1a7a7f 100%); padding: 20px 10px;">
        <tr>
            <td align="center">
                <table class="email-container" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #ffffff; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                    <!-- Header with gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f4f55 0%, #1a7a7f 100%); padding: 30px 20px; text-align: center;">
                            <h1 class="header-title" style="font-family: 'El Messiri', Arial, sans-serif; color: #ffffff; font-size: 36px; margin: 0; font-weight: 600;">
                                Tulip Store
                            </h1>
                            <p class="header-subtitle" style="color: #d3e7e2; font-size: 16px; margin: 10px 0 0 0; font-weight: 300;">
                                Send smile anywhere
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content-padding" style="padding: 40px 30px;">
                            <h2 class="main-title" style="font-family: 'El Messiri', Arial, sans-serif; color: #0f4f55; font-size: 28px; margin: 0 0 20px 0; text-align: center; font-weight: 600;">
                                رمز التحقق الخاص بك
                            </h2>
                            
                            <p class="text-content" style="color: #555; font-size: 16px; line-height: 1.6; text-align: center; margin: 0 0 25px 0;">
                                مرحباً <strong style="color: #0f4f55;">{{ $name }}</strong>،
                            </p>
                            
                            <p class="text-content" style="color: #666; font-size: 15px; line-height: 1.6; text-align: center; margin: 0 0 30px 0;">
                                لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بك. استخدم الرمز التالي لإكمال العملية:
                            </p>
                            
                            <!-- Verification Code Box -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div class="code-box" style="background: linear-gradient(135deg, #ff6f35 0%, #ff8c5a 100%); border-radius: 20px; padding: 25px 20px; display: inline-block; box-shadow: 0 10px 30px rgba(255, 111, 53, 0.3); max-width: 90%;">
                                            <p class="verification-code" style="color: #ffffff; font-size: 42px; font-weight: 600; letter-spacing: 10px; margin: 0; font-family: 'El Messiri', sans-serif; word-break: break-all;">
                                                {{ $code }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <p class="text-content" style="color: #666; font-size: 14px; line-height: 1.6; text-align: center; margin: 30px 0 0 0;">
                                هذا الرمز صالح لمدة <strong style="color: #ff6f35;">10 دقائق</strong> فقط
                            </p>
                            
                            <div style="background: #f8f9fa; border-right: 4px solid #ff6f35; padding: 15px; margin: 25px 0; border-radius: 10px;">
                                <p class="text-content" style="color: #555; font-size: 13px; line-height: 1.6; margin: 0;">
                                    <strong style="color: #0f4f55;">ملاحظة هامة:</strong> إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة. حسابك آمن ولن يتم إجراء أي تغييرات.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #f8f9fa; padding: 25px 20px; text-align: center; border-top: 1px solid #e9ecef;">
                            <p style="color: #999; font-size: 13px; line-height: 1.6; margin: 0 0 10px 0;">
                                شكراً لاختيارك Tulip Store
                            </p>
                            <p style="color: #999; font-size: 11px; line-height: 1.6; margin: 0;">
                                © 2026 Tulip Store. جميع الحقوق محفوظة.
                            </p>
                            <div style="margin-top: 15px;">
                                <a href="#" style="color: #0f4f55; text-decoration: none; margin: 0 8px; font-size: 11px;">الموقع الإلكتروني</a>
                                <span style="color: #ddd;">|</span>
                                <a href="#" style="color: #0f4f55; text-decoration: none; margin: 0 8px; font-size: 11px;">الدعم الفني</a>
                                <span style="color: #ddd;">|</span>
                                <a href="#" style="color: #0f4f55; text-decoration: none; margin: 0 8px; font-size: 11px;">سياسة الخصوصية</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
