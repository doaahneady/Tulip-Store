<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التأكيد مسبقاً</title>
    <!-- fav icon -->
     <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'El Messiri', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .container {
            background: #fff;
            border-radius: 24px;
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
        }
        
        .icon i {
            font-size: 3rem;
            color: #fff;
        }
        
        h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #ff9800;
            margin-bottom: 1rem;
        }
        
        p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .info-box {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.7rem 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .info-row:last-child { border-bottom: none; }
        
        .info-label {
            color: #666;
            font-weight: 600;
        }
        
        .info-value {
            color: #1a1a1a;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-check-double"></i>
        </div>
        <h1>تم التأكيد مسبقاً</h1>
        <p>هذا الطلب تم تأكيد استلامه بالفعل</p>
        
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">رقم الطلب:</span>
                <span class="info-value">#{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">تاريخ التأكيد:</span>
                <span class="info-value">{{ $order->confirmed_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">الحالة:</span>
                <span class="info-value" style="color: #28a745;">تم التوصيل</span>
            </div>
        </div>
        
        <p style="margin-top: 2rem; font-size: 1rem; color: #999;">يمكنك إغلاق هذه الصفحة الآن</p>
    </div>
</body>
</html>
