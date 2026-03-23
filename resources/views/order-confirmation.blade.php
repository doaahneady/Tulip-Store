<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تأكيد استلام الطلب</title>
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
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #2a7080;
            margin-bottom: 0.5rem;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }
        
        .success-icon i {
            font-size: 2.5rem;
            color: #fff;
        }
        
        .order-details {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.7rem 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .detail-row:last-child { border-bottom: none; }
        
        .detail-label {
            color: #666;
            font-weight: 600;
        }
        
        .detail-value {
            color: #1a1a1a;
            font-weight: 700;
        }
        
        .products-section {
            margin-bottom: 2rem;
        }
        
        .products-section h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2a7080;
            margin-bottom: 1rem;
        }
        
        .product-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }
        
        .signature-section {
            margin-bottom: 2rem;
        }
        
        .signature-section h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2a7080;
            margin-bottom: 1rem;
        }
        
        #signatureCanvas {
            border: 3px solid #2a7080;
            border-radius: 12px;
            cursor: crosshair;
            display: block;
            width: 100%;
            touch-action: none;
        }
        
        .signature-controls {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .btn {
            flex: 1;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-clear {
            background: #6c757d;
            color: #fff;
        }
        
        .btn-clear:hover {
            background: #5a6268;
            transform: scale(1.02);
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff;
            font-size: 1.1rem;
        }
        
        .btn-confirm:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }
        
        .btn-confirm:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .driver-info {
            background: linear-gradient(135deg, #2a7080, #1f5a68);
            color: #fff;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .driver-info h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .driver-info p {
            font-size: 1.1rem;
            font-weight: 700;
        }
        @media (max-width: 600px) {
            body {
                padding: 0;
                align-items: flex-start;
                background: #fff; /* Use plain white background for mobile confirmation to simplify */
                overflow-x: hidden;
            }
            .container {
                padding: 1rem;
                border-radius: 0;
                box-shadow: none;
                width: 100%;
                overflow: visible !important;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .order-details, .products-section, .driver-info {
                padding: 1rem;
                margin-bottom: 1.5rem;
            }
            .detail-row {
                font-size: 0.85rem;
                padding: 0.5rem 0;
            }
            .product-item {
                font-size: 0.85rem;
                padding: 0.6rem;
            }
            .signature-section h3 {
                font-size: 1rem;
            }
            #signatureCanvas {
                height: 150px;
            }
            .btn {
                padding: 0.7rem;
                font-size: 0.9rem;
            }
            
            /* Hide non-essential elements for confirmation view if needed */
            .success-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }
            .success-icon i {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h1>تأكيد استلام الطلب</h1>
            <p style="color: #666; font-size: 1.1rem;">الرجاء التوقيع لتأكيد استلام طلبك</p>
        </div>
        
        @if($order->assignedDriver)
        <div class="driver-info">
            <h3><i class="fas fa-user-circle"></i> السائق</h3>
            <p>{{ $order->assignedDriver->name }}</p>
        </div>
        @endif
        
        <div class="order-details">
            <div class="detail-row">
                <span class="detail-label">رقم الطلب:</span>
                <span class="detail-value">#{{ $order->order_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">اسم المستلم:</span>
                <span class="detail-value">{{ $order->recipient_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">المنطقة:</span>
                <span class="detail-value">{{ $order->village }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">المجموع الكلي:</span>
                <span class="detail-value">@money($order->total)</span>
            </div>
            @if($order->payment_method === 'cash')
            <div class="detail-row">
                <span class="detail-label">طريقة الدفع:</span>
                <span class="detail-value" style="color: #ff6b35;">دفع عند الاستلام</span>
            </div>
            @endif
        </div>
        
        <div class="products-section">
            <h3><i class="fas fa-shopping-bag"></i> المنتجات</h3>
            @foreach($order->items as $item)
            <div class="product-item">
                <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                <span style="font-weight: 700;">@money($item->subtotal)</span>
            </div>
            @endforeach
        </div>
        
        <div class="signature-section">
            <h3><i class="fas fa-signature"></i> التوقيع</h3>
            <canvas id="signatureCanvas" width="500" height="200"></canvas>
            <div class="signature-controls">
                <button class="btn btn-clear" onclick="clearSignature()">
                    <i class="fas fa-eraser"></i> مسح
                </button>
            </div>
        </div>
        
        <button class="btn btn-confirm" id="confirmBtn" onclick="confirmOrder()">
            <i class="fas fa-check-circle"></i> تأكيد الاستلام
        </button>
    </div>

    <script>
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let hasSignature = false;
        
        // Set canvas size
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * 2;
            canvas.height = rect.height * 2;
            ctx.scale(2, 2);
            ctx.strokeStyle = '#2a7080';
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
        }
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        
        // Touch events
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);
        
        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }
        
        function draw(e) {
            if (!isDrawing) return;
            hasSignature = true;
            const rect = canvas.getBoundingClientRect();
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
        }
        
        function stopDrawing() {
            isDrawing = false;
        }
        
        function handleTouch(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            
            if (e.type === 'touchstart') {
                isDrawing = true;
                ctx.beginPath();
                ctx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
            } else if (e.type === 'touchmove' && isDrawing) {
                hasSignature = true;
                ctx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
                ctx.stroke();
            }
        }
        
        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSignature = false;
        }
        
        async function confirmOrder() {
            if (!hasSignature) {
                alert('الرجاء التوقيع أولاً');
                return;
            }
            
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التأكيد...';
            
            const signatureData = canvas.toDataURL();
            
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        signature: signatureData
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.body.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div style="background: #fff; border-radius: 24px; padding: 3rem; text-align: center; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);">
                                    <i class="fas fa-check" style="font-size: 3rem; color: #fff;"></i>
                                </div>
                                <h1 style="font-family: 'El Messiri', sans-serif; font-size: 2.5rem; font-weight: 800; color: #28a745; margin-bottom: 1rem;">تم التأكيد بنجاح!</h1>
                                <p style="font-family: 'El Messiri', sans-serif; font-size: 1.3rem; color: #666; margin-bottom: 2rem;">شكراً لك على استلام طلبك</p>
                                <p style="font-family: 'El Messiri', sans-serif; font-size: 1.1rem; color: #999;">يمكنك إغلاق هذه الصفحة الآن</p>
                            </div>
                        </div>
                    `;
                } else {
                    alert(data.message);
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-check-circle"></i> تأكيد الاستلام';
                }
            } catch (error) {
                console.error(error);
                alert('حدث خطأ في تأكيد الطلب');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check-circle"></i> تأكيد الاستلام';
            }
        }
    </script>
</body>
</html>
