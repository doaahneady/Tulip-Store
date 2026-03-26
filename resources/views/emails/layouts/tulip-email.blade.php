<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
            .header-title { font-size: 28px !important; }
            .header-subtitle { font-size: 14px !important; }
            .content-padding { padding: 30px 20px !important; }
            .main-title { font-size: 24px !important; }
            .verification-code { font-size: 36px !important; letter-spacing: 8px !important; }
            .text-content { font-size: 14px !important; }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; font-family: 'El Messiri', sans-serif; background: linear-gradient(135deg, #0f4f55 0%, #1a7a7f 100%); direction: rtl;">
<table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #0f4f55 0%, #1a7a7f 100%); padding: 20px 10px;">
    <tr>
        <td align="center">
            <table class="email-container" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #ffffff; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                <!-- Header -->
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

                <!-- Main -->
                <tr>
                    <td class="content-padding" style="padding: 40px 30px;">
                        @yield('body')
                        @yield('app_links')
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background: #f8f9fa; padding: 25px 20px; text-align: center; border-top: 1px solid #e9ecef;">
                        <p style="color: #999; font-size: 13px; line-height: 1.6; margin: 0 0 10px 0;">
                            شكراً لاختيارك Tulip Store
                        </p>
                        <p style="color: #999; font-size: 11px; line-height: 1.6; margin: 0;">
                            © {{ date('Y') }} Tulip Store. جميع الحقوق محفوظة.
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

