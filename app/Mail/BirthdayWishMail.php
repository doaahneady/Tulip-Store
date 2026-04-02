<?php

namespace App\Mail;

use App\Models\DiscountCoupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayWishMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public DiscountCoupon $coupon
    ) {}

    public function build()
    {
        $userName = (string) ($this->user->name ?? 'عزيزنا');
        $couponCode = (string) ($this->coupon->code ?? '');
        $discountValue = (int) ($this->coupon->value ?? 0);
        $validUntil = $this->coupon->valid_until ? $this->coupon->valid_until->format('Y-m-d h:i A') : '';

        $html = '
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "El Messiri", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .email-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 50%, #c44569 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        .email-header::before {
            content: "🎉";
            position: absolute;
            font-size: 100px;
            opacity: 0.1;
            top: -20px;
            right: -20px;
            animation: float 3s ease-in-out infinite;
        }
        .email-header::after {
            content: "🎂";
            position: absolute;
            font-size: 80px;
            opacity: 0.1;
            bottom: -10px;
            left: -10px;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .email-header .icon {
            font-size: 80px;
            margin-bottom: 15px;
            animation: bounce 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .email-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 18px;
            opacity: 0.95;
        }
        .email-body {
            padding: 40px 30px;
        }
        .birthday-message {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(253, 203, 110, 0.3);
        }
        .birthday-message h2 {
            color: #d63031;
            margin: 0 0 10px 0;
            font-size: 26px;
        }
        .birthday-message p {
            color: #2d3436;
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
        }
        .coupon-box {
            background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.3);
            position: relative;
        }
        .coupon-box::before {
            content: "🎁";
            position: absolute;
            font-size: 60px;
            opacity: 0.15;
            top: 10px;
            left: 20px;
        }
        .coupon-label {
            color: #ffffff;
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .coupon-code {
            background: #ffffff;
            color: #6c5ce7;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 3px;
            display: inline-block;
            margin: 10px 0;
            border: 3px dashed #6c5ce7;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .discount-badge {
            background: #00b894;
            color: #ffffff;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 22px;
            font-weight: 700;
            display: inline-block;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }
        .how-to-use {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        .how-to-use h3 {
            color: #2a7080;
            margin: 0 0 15px 0;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .step {
            display: flex;
            align-items: start;
            gap: 15px;
            margin-bottom: 15px;
            padding: 12px;
            background: #ffffff;
            border-radius: 8px;
            border-right: 4px solid #6c5ce7;
        }
        .step-number {
            background: #6c5ce7;
            color: #ffffff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }
        .step-text {
            color: #333;
            font-size: 15px;
            line-height: 1.6;
        }
        .validity-notice {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .validity-notice p {
            margin: 0;
            color: #856404;
            font-size: 15px;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: #ffffff;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            margin: 20px 0;
            box-shadow: 0 6px 20px rgba(238, 90, 111, 0.4);
            transition: all 0.3s;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2a7080;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">🎂🎉</div>
            <h1>عيد ميلاد سعيد!</h1>
            <p>يا '.htmlspecialchars($userName).'</p>
        </div>
        
        <div class="email-body">
            <div class="birthday-message">
                <h2>🌟 أجمل التهاني من فريق Tulip 🌟</h2>
                <p>
                    في هذا اليوم المميز، نتمنى لك عاماً مليئاً بالسعادة والنجاح والإنجازات.<br>
                    شكراً لكونك جزءاً من عائلة Tulip Store!
                </p>
            </div>
            
            <p style="font-size: 16px; color: #333; text-align: center; line-height: 1.8;">
                🎁 لأنك مميز بالنسبة لنا، أعددنا لك هدية خاصة!<br>
                كوبون خصم حصري يمكنك استخدامه اليوم فقط
            </p>
            
            <div class="coupon-box">
                <div class="coupon-label">🎫 كود الخصم الخاص بك</div>
                <div class="coupon-code">'.htmlspecialchars($couponCode).'</div>
                <div class="discount-badge">خصم '.htmlspecialchars($discountValue).'٪</div>
            </div>
            
            <div class="validity-notice">
                <p>⏰ صالح حتى: '.htmlspecialchars($validUntil).'</p>
                <p style="margin-top: 5px; font-size: 13px;">يمكن استخدامه مرة واحدة فقط</p>
            </div>
            
            <div class="how-to-use">
                <h3>📝 كيفية استخدام الكوبون</h3>
                
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-text">اختر المنتجات التي تريدها وأضفها إلى سلة التسوق</div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-text">في صفحة السلة، ابحث عن حقل "كود الخصم" أو "كوبون"</div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">أدخل الكود: <strong>'.htmlspecialchars($couponCode).'</strong></div>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-text">اضغط على "تطبيق" وشاهد الخصم يُطبق على طلبك!</div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="https://tulip-os.com" class="cta-button">🛍️ ابدأ التسوق الآن</a>
            </div>
            
            <div style="background: #e8f4f8; border-right: 4px solid #2a7080; padding: 15px; border-radius: 8px; margin-top: 25px;">
                <p style="margin: 0; color: #2a7080; font-size: 14px; text-align: center;">
                    💝 نتمنى لك يوماً رائعاً مليئاً بالفرح والبهجة!
                </p>
            </div>
        </div>
        
        <div class="footer">
            <div class="logo">🌷 Tulip Store</div>
            <p>متجركم الموثوق للتسوق الإلكتروني</p>
            <p style="margin-top: 10px;">
                <a href="https://tulip-os.com" style="color: #2a7080; text-decoration: none;">زيارة الموقع</a>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه
            </p>
        </div>
    </div>
</body>
</html>';

        return $this->subject('🎉 عيد ميلاد سعيد من Tulip Store - هدية خاصة لك!')->html($html);
    }
}
