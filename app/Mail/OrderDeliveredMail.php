<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function build()
    {
        $orderNumber = (string) ($this->order->order_number ?? $this->order->id);
        $recipientName = (string) ($this->order->recipient_name ?? 'Customer');
        $total = (float) ($this->order->total ?? $this->order->total_amount ?? 0);
        $paymentMethod = (string) ($this->order->payment_method ?? 'cash');
        $deliveryAddress = (string) ($this->order->village ?? '');
        $createdAt = $this->order->created_at ? $this->order->created_at->format('Y-m-d H:i') : '';
        
        // Get payment method in Arabic
        $paymentMethodArabic = match($paymentMethod) {
            'cash' => 'نقدي عند الاستلام',
            'card' => 'بطاقة ائتمان',
            'balance' => 'الرصيد',
            'syriatel' => 'سيرياتيل كاش',
            'bank' => 'تحويل بنكي',
            default => $paymentMethod
        };

        $html = '
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "El Messiri", Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header .icon {
            font-size: 60px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 30px;
        }
        .success-message {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }
        .success-message h2 {
            color: #155724;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .success-message p {
            color: #155724;
            margin: 0;
            font-size: 16px;
        }
        .order-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-details h3 {
            color: #2a7080;
            margin: 0 0 15px 0;
            font-size: 20px;
            border-bottom: 2px solid #2a7080;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #666;
            font-weight: 600;
        }
        .detail-value {
            color: #333;
            font-weight: 700;
        }
        .total-row {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .total-row .detail-value {
            color: #2a7080;
            font-size: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            background: #2a7080;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">✅</div>
            <h1>تم توصيل طلبك بنجاح!</h1>
        </div>
        
        <div class="email-body">
            <div class="success-message">
                <h2>🎉 مبروك!</h2>
                <p>تم تسليم طلبك بنجاح وتأكيد الاستلام</p>
            </div>
            
            <p style="font-size: 16px; color: #333; line-height: 1.6;">
                عزيزي/عزيزتي <strong>'.htmlspecialchars($recipientName).'</strong>،
            </p>
            
            <p style="font-size: 16px; color: #333; line-height: 1.6;">
                نود إعلامك بأن طلبك قد تم توصيله وتسليمه بنجاح. نشكرك على ثقتك بنا!
            </p>
            
            <div class="order-details">
                <h3>📦 تفاصيل الطلب</h3>
                
                <div class="detail-row">
                    <span class="detail-label">رقم الطلب:</span>
                    <span class="detail-value">#'.htmlspecialchars($orderNumber).'</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">تاريخ الطلب:</span>
                    <span class="detail-value">'.htmlspecialchars($createdAt).'</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">عنوان التوصيل:</span>
                    <span class="detail-value">'.htmlspecialchars($deliveryAddress).'</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">طريقة الدفع:</span>
                    <span class="detail-value">'.htmlspecialchars($paymentMethodArabic).'</span>
                </div>
                
                <div class="total-row">
                    <div class="detail-row" style="border: none;">
                        <span class="detail-label" style="font-size: 18px;">المبلغ الإجمالي:</span>
                        <span class="detail-value">$'.number_format($total, 2).'</span>
                    </div>
                </div>
            </div>
            
            <div style="background: #fff3cd; border-right: 4px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="margin: 0; color: #856404; font-size: 14px;">
                    <strong>💡 ملاحظة:</strong> إذا كان لديك أي استفسار أو ملاحظة على الطلب، يرجى التواصل معنا في أقرب وقت.
                </p>
            </div>
            
            <div style="text-align: center;">
                <p style="font-size: 16px; color: #333;">
                    نتمنى أن تكون راضياً عن خدماتنا ونتطلع لخدمتك مرة أخرى!
                </p>
                <a href="https://tulip-os.com" class="button">تصفح المزيد من المنتجات</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>🌷 Tulip Store</strong></p>
            <p>متجركم الموثوق للتسوق الإلكتروني</p>
            <p style="margin-top: 10px;">
                <a href="https://tulip-os.com" style="color: #2a7080; text-decoration: none;">زيارة الموقع</a>
            </p>
            <p style="font-size: 12px; color: #999;">
                هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه
            </p>
        </div>
    </div>
</body>
</html>';

        return $this->subject('تم توصيل طلبك بنجاح - Tulip Store')->html($html);
    }
}
