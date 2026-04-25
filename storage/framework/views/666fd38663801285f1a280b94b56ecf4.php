<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>طلب عبر واتساب - Tulip Store</title>
    <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'El Messiri', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f4f5 100%);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .header {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            padding: 2rem;
            border-radius: 16px 16px 0 0;
            color: white;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }
        
        .header p {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .content {
            background: white;
            padding: 2rem;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.2rem;
            background: #f8f9fa;
            color: #0f4f55;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #0f4f55;
            color: white;
            border-color: #0f4f55;
        }
        
        .info-box {
            background: #e8f4f8;
            padding: 1.5rem;
            border-radius: 12px;
            border-right: 4px solid #0f4f55;
            margin-bottom: 2rem;
        }
        
        .info-box h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f4f55;
            margin: 0 0 0.5rem 0;
        }
        
        .info-box p {
            color: #555;
            line-height: 1.6;
            margin: 0.3rem 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            transition: all 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #0f4f55;
            box-shadow: 0 0 0 3px rgba(15,79,85,0.1);
        }
        
        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #20BA5A 100%);
            color: white;
            border: none;
            padding: 1.2rem 2rem;
            border-radius: 10px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(37,211,102,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,211,102,0.4);
        }
        
        .btn-whatsapp:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 1rem;
            }
            
            .header {
                padding: 1.5rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="container">
        <div class="header">
            <h1><i class="fab fa-whatsapp"></i> طلب عبر واتساب</h1>
            <p>أكمل معلوماتك لإرسال طلبك عبر واتساب</p>
        </div>
        
        <div class="content">
            <a href="/cart" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                رجوع للسلة
            </a>
            
            <div class="info-box">
                <h3>
                    <i class="fas fa-info-circle"></i> معلومات مهمة
                </h3>
                <p><i class="fas fa-phone" style="color:#0f4f55; margin-left:0.5rem;"></i> يرجى التأكد من أن رقم الهاتف صحيح للتواصل معك عبر المكالمات وواتساب</p>
                <p><i class="fas fa-check-circle" style="color:#0f4f55; margin-left:0.5rem;"></i> سيتم إرسال تفاصيل طلبك مباشرة إلى فريق المبيعات</p>
            </div>
            
            <form id="whatsappOrderForm">
                <div class="form-group">
                    <label for="customerName">
                        <i class="fas fa-user" style="color:#0f4f55; margin-left:0.3rem;"></i>
                        الاسم الكامل
                    </label>
                    <input type="text" id="customerName" name="customerName" placeholder="أدخل اسمك الكامل" required>
                </div>
                
                <div class="form-group">
                    <label for="customerPhone">
                        <i class="fas fa-phone" style="color:#0f4f55; margin-left:0.3rem;"></i>
                        رقم الهاتف
                    </label>
                    <input type="tel" id="customerPhone" name="customerPhone" placeholder="مثال: 0968355553" required>
                </div>
                
                <button type="submit" class="btn-whatsapp">
                    <i class="fab fa-whatsapp" style="font-size:1.5rem;"></i>
                    إرسال الطلب عبر واتساب
                </button>
            </form>
        </div>
    </div>
    
    <script>
        const API_BASE = window.location.origin + '/api';
        const WHATSAPP_NUMBER = '963968355553';
        
        document.getElementById('whatsappOrderForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const customerName = document.getElementById('customerName').value.trim();
            const customerPhone = document.getElementById('customerPhone').value.trim();
            
            if (!customerName || !customerPhone) {
                alert('يرجى إدخال جميع المعلومات المطلوبة');
                return;
            }
            
            // Get cart items
            try {
                const response = await fetch(`${API_BASE}/cart`, { credentials: 'same-origin' });
                const cartData = await response.json();
                
                if (!cartData.items || cartData.items.length === 0) {
                    alert('السلة فارغة');
                    window.location.href = '/cart';
                    return;
                }
                
                // Check if there are mart items
                const hasMartItems = cartData.items.some(item => 
                    item.type === 'mart' || (typeof item.id === 'string' && item.id.startsWith('m'))
                );
                
                if (!hasMartItems) {
                    alert('هذه الخدمة متاحة فقط لمنتجات المارت');
                    window.location.href = '/cart';
                    return;
                }
                
                // Build order message
                let message = `مرحباً، أود تقديم طلب عبر واتساب\n\n`;
                message += `الاسم: ${customerName}\n`;
                message += `رقم الهاتف: ${customerPhone}\n\n`;
                message += `المنتجات:\n`;
                message += `━━━━━━━━━━━━━━━━\n`;
                
                let orderTotal = 0;
                cartData.items.forEach((item, index) => {
                    const isMart = item.type === 'mart' || (typeof item.id === 'string' && item.id.startsWith('m'));
                    if (isMart) {
                        const isWeightBased = item.is_weight_based || false;
                        const weightGrams = item.weight_grams || 0;
                        
                        message += `\n${index + 1}. ${item.product.name}\n`;
                        
                        if (isWeightBased) {
                            const weightDisplay = weightGrams >= 1000 
                                ? `${(weightGrams / 1000).toFixed(2)} كيلو`
                                : `${Math.round(weightGrams)} غرام`;
                            message += `   الوزن: ${weightDisplay}\n`;
                            
                            const amountPaidSyp = parseFloat(item.amount_paid || 0);
                            message += `   السعر: ${amountPaidSyp.toFixed(2)} SYP\n`;
                            orderTotal += amountPaidSyp;
                        } else {
                            message += `   الكمية: ${item.quantity}\n`;
                            const price = parseFloat(item.product.discount_price || item.product.price);
                            const USD_TO_SYP = window.TULIP_USD_TO_SYP || 13100;
                            const priceSyp = price * USD_TO_SYP;
                            const itemTotal = priceSyp * item.quantity;
                            message += `   السعر: ${priceSyp.toFixed(2)} SYP × ${item.quantity} = ${itemTotal.toFixed(2)} SYP\n`;
                            orderTotal += itemTotal;
                        }
                    }
                });
                
                message += `\n━━━━━━━━━━━━━━━━\n`;
                message += `المجموع الإجمالي: ${orderTotal.toFixed(2)} SYP\n\n`;
                message += `ملاحظة: هذا طلب من توليب مارت`;
                
                // Create order in database
                const orderResponse = await fetch('/api/orders/whatsapp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        customer_name: customerName,
                        customer_phone: customerPhone,
                        cart_items: cartData.items.filter(item => 
                            item.type === 'mart' || (typeof item.id === 'string' && item.id.startsWith('m'))
                        )
                    })
                });
                
                if (orderResponse.ok) {
                    const orderData = await orderResponse.json();
                    if (orderData.order_number) {
                        message = `رقم الطلب: #${orderData.order_number}\n\n` + message;
                    }
                }
                
                // Open WhatsApp
                const whatsappUrl = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
                
                // Clear cart after a delay
                setTimeout(() => {
                    if (confirm('تم إرسال طلبك! هل تريد مسح السلة؟')) {
                        fetch(`${API_BASE}/cart/clear`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            credentials: 'same-origin'
                        }).then(() => {
                            window.location.href = '/';
                        });
                    } else {
                        window.location.href = '/cart';
                    }
                }, 1000);
                
            } catch (error) {
                console.error('Error:', error);
                alert('حدث خطأ أثناء معالجة الطلب');
            }
        });
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/whatsapp-order.blade.php ENDPATH**/ ?>