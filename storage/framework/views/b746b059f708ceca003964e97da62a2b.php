<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>شحن الرصيد - Tulip Store</title>
    <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v2/"></script>
    
    <style>
        body {
            font-family: 'El Messiri', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .recharge-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .recharge-header {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            padding: 2rem;
            border-radius: 16px 16px 0 0;
            color: white;
            text-align: center;
        }
        
        .recharge-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }
        
        .recharge-header p {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .recharge-content {
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
        
        .payment-options {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .payment-option {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            border: 3px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .payment-option:hover {
            border-color: #0f4f55;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }
        
        .payment-option.selected {
            border-color: #0f4f55;
            background: #e8f4f8;
        }
        
        .payment-option.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: #f5f5f5;
        }
        
        .payment-option.disabled:hover {
            transform: none;
            box-shadow: none;
            border-color: #e0e0e0;
        }
        
        .payment-icon {
            font-size: 2.5rem;
            color: #0f4f55;
            flex-shrink: 0;
        }
        
        .payment-info {
            flex: 1;
        }
        
        .payment-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 0.3rem 0;
        }
        
        .payment-desc {
            font-size: 0.95rem;
            color: #666;
            margin: 0;
        }
        
        .payment-badge {
            background: linear-gradient(135deg, #ff6f35 0%, #ff8c5a 100%);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(255,111,53,0.3);
        }
        
        .payment-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        
        .payment-section.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .amount-input-group {
            margin-bottom: 1.5rem;
        }
        
        .amount-input-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .amount-input-group input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s;
        }
        
        .amount-input-group input:focus {
            outline: none;
            border-color: #0f4f55;
            box-shadow: 0 0 0 3px rgba(15,79,85,0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c5a 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        #map {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            margin-bottom: 1rem;
        }
        
        .storage-info {
            background: #e8f4f8;
            padding: 1.5rem;
            border-radius: 12px;
            border-right: 4px solid #0f4f55;
            margin-bottom: 1.5rem;
        }
        
        .storage-info h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f4f55;
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .storage-info p {
            color: #555;
            line-height: 1.8;
            margin: 0.5rem 0;
        }
        
        @media (max-width: 768px) {
            .recharge-container {
                margin: 1rem auto;
                padding: 0 1rem;
            }
            
            .recharge-header {
                padding: 1.5rem;
            }
            
            .recharge-header h1 {
                font-size: 1.5rem;
            }
            
            .recharge-content {
                padding: 1.5rem;
            }
            
            #map {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="recharge-container">
        <div class="recharge-header">
            <h1><i class="fas fa-wallet"></i> شحن الرصيد</h1>
            <p>اختر طريقة الدفع المناسبة لك لشحن رصيدك</p>
        </div>
        
        <div class="recharge-content">
            <a href="/profile" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                رجوع للملف الشخصي
            </a>
            
            <!-- Payment Options -->
            <div id="paymentOptionsSection">
                <h2 style="font-size:1.3rem; font-weight:700; color:#0f4f55; margin-bottom:1.5rem;">
                    <i class="fas fa-credit-card"></i> اختر طريقة الدفع
                </h2>
                
                <div class="payment-options">
                    <!-- Cash Payment -->
                    <div class="payment-option" onclick="selectPaymentMethod('cash')">
                        <div class="payment-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="payment-info">
                            <h3 class="payment-title">الدفع نقداً</h3>
                            <p class="payment-desc">قم بزيارة المركز لشحن رصيدك نقداً</p>
                        </div>
                        <div>
                            <i class="far fa-circle" style="font-size:1.5rem; color:#ccc;"></i>
                        </div>
                    </div>
                    
                    <!-- Credit Card Payment -->
                    <div class="payment-option disabled" title="قريباً">
                        <div class="payment-icon">
                            <i class="fas fa-credit-card" style="color:#999;"></i>
                        </div>
                        <div class="payment-info">
                            <h3 class="payment-title" style="color:#999;">بطاقة ائتمان</h3>
                            <p class="payment-desc" style="color:#999;">ادفع بأمان باستخدام بطاقتك</p>
                        </div>
                        <div>
                            <span class="payment-badge">قريباً</span>
                        </div>
                    </div>
                    
                    <!-- Sham Cash Payment -->
                    <div class="payment-option" onclick="selectPaymentMethod('shamcash')">
                        <div class="payment-icon">
                            <img src="/images/shamccashlogo.jpg" alt="Sham Cash" style="width:50px; height:50px; object-fit:contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <i class="fas fa-university" style="font-size:2.5rem; color:#0f4f55; display:none;"></i>
                        </div>
                        <div class="payment-info">
                            <h3 class="payment-title">Sham Cash</h3>
                            <p class="payment-desc">حوّل المبلغ إلى الحساب المحدد</p>
                        </div>
                        <div>
                            <i class="far fa-circle" style="font-size:1.5rem; color:#ccc;"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cash Payment Section -->
            <div id="cashPaymentSection" class="payment-section">
                <button onclick="backToPaymentOptions()" class="back-btn" style="margin-bottom:1.5rem;">
                    <i class="fas fa-arrow-right"></i> العودة لطرق الدفع
                </button>
                
                <div class="storage-info">
                    <h3>
                        <i class="fas fa-map-marker-alt"></i>
                        موقع المركز
                    </h3>
                    <p><i class="fas fa-info-circle" style="color:#0f4f55; margin-left:0.5rem;"></i> يرجى زيارة المركز في الموقع المحدد أدناه لشحن رصيدك نقداً</p>
                    <p><i class="fas fa-clock" style="color:#0f4f55; margin-left:0.5rem;"></i> ساعات العمل: من 9 صباحاً حتى 5 مساءً</p>
                    <p><i class="fas fa-phone" style="color:#0f4f55; margin-left:0.5rem;"></i> للاستفسار: 0968355553</p>
                </div>
                
                <div id="map"></div>
                
                <button class="btn-primary" onclick="window.location.href='/profile'">
                    <i class="fas fa-check-circle"></i> فهمت، سأزور المركز
                </button>
            </div>
            
            <!-- Sham Cash Payment Section -->
            <div id="shamcashPaymentSection" class="payment-section">
                <button onclick="backToPaymentOptions()" class="back-btn" style="margin-bottom:1.5rem;">
                    <i class="fas fa-arrow-right"></i> العودة لطرق الدفع
                </button>
                
                <div style="background:#fff; padding:2rem; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <h2 style="font-family:'El Messiri',sans-serif; font-size:1.6rem; font-weight:800; color:#1a1a1a; margin:0 0 2rem 0; text-align:center;">
                        <i class="fas fa-university" style="margin-left:0.5rem; color:#0f4f55;"></i>
                        تحويل Sham Cash
                    </h2>
                    
                    <!-- Instructions -->
                    <div style="background:#fff3cd; padding:1.5rem; border-radius:12px; border-right:4px solid #ffc107; margin-bottom:2rem;">
                        <h3 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#856404; margin:0 0 1rem 0;">
                            <i class="fas fa-info-circle" style="margin-left:0.5rem;"></i>
                            تعليمات الدفع
                        </h3>
                        <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#856404; margin:0; line-height:1.8;">
                            يرجى تحويل المبلغ إلى الحساب المحدد أدناه. سيتم التحقق من الدفع قبل إضافة الرصيد إلى حسابك.
                        </p>
                    </div>
                    
                    <!-- Account Image (QR Code) -->
                    <div style="text-align:center; margin-bottom:2rem;">
                        <img src="/images/shamcash.jpeg" alt="Sham Cash Account" style="max-width:100%; height:auto; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1);" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display:none; background:#f8f9fa; padding:2rem; border-radius:12px; border:2px dashed #ccc;">
                            <i class="fas fa-image" style="font-size:3rem; color:#ccc; margin-bottom:1rem;"></i>
                            <p style="font-family:'El Messiri',sans-serif; color:#999; margin:0;">صورة الحساب غير متوفرة</p>
                        </div>
                    </div>
                    
                    <!-- User Account Details Form -->
                    <div style="background:linear-gradient(135deg, #e8f4f8 0%, #f0f8ff 100%); padding:1.5rem; border-radius:12px; border:2px solid #0f4f55; margin-bottom:2rem;">
                        <h4 style="font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#0f4f55; margin:0 0 1.5rem 0; text-align:center;">
                            <i class="fas fa-edit" style="margin-left:0.5rem;"></i>
                            أدخل معلومات حسابك في Sham Cash
                        </h4>
                        
                        <!-- Account Name Input -->
                        <div style="margin-bottom:1.5rem;">
                            <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.9rem; font-weight:600; color:#0f4f55; margin-bottom:0.5rem;">
                                <i class="fas fa-user" style="margin-left:0.5rem;"></i>
                                اسم حسابك
                            </label>
                            <input type="text" id="userAccountName" placeholder="أدخل اسم حسابك في Sham Cash" style="width:100%; padding:1rem; border:2px solid #e0e0e0; border-radius:8px; font-family:'El Messiri',sans-serif; font-size:1rem; color:#1a1a1a; transition:all 0.3s;" onfocus="this.style.borderColor='#0f4f55'" onblur="this.style.borderColor='#e0e0e0'">
                        </div>
                        
                        <!-- Account Number Input -->
                        <div>
                            <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.9rem; font-weight:600; color:#0f4f55; margin-bottom:0.5rem;">
                                <i class="fas fa-hashtag" style="margin-left:0.5rem;"></i>
                                رقم حسابك
                            </label>
                            <input type="text" id="userAccountNumber" placeholder="أدخل رقم حسابك في Sham Cash" style="width:100%; padding:1rem; border:2px solid #e0e0e0; border-radius:8px; font-family:'Courier New', monospace; font-size:1rem; color:#1a1a1a; direction:ltr; transition:all 0.3s;" onfocus="this.style.borderColor='#0f4f55'" onblur="this.style.borderColor='#e0e0e0'">
                        </div>
                    </div>
                    
                    <!-- Important Note -->
                    <div style="background:#ffe8e8; padding:1.5rem; border-radius:12px; border-right:4px solid #dc3545; margin-bottom:1.5rem;">
                        <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#721c24; margin:0 0 1rem 0; line-height:1.8;">
                            <i class="fas fa-exclamation-triangle" style="margin-left:0.5rem; color:#dc3545;"></i>
                            <strong>مهم:</strong> لن يتم إضافة الرصيد حتى يتم التحقق من استلام المبلغ في الحساب المحدد.
                        </p>
                    </div>
                    
                    <!-- WhatsApp Instructions -->
                    <div style="background:#d4edda; padding:1.5rem; border-radius:12px; border-right:4px solid #28a745; margin-bottom:1.5rem;">
                        <h4 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#155724; margin:0 0 1rem 0;">
                            <i class="fab fa-whatsapp" style="margin-left:0.5rem; color:#25D366;"></i>
                            إرسال معلومات الحساب
                        </h4>
                        <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#155724; margin:0 0 1rem 0; line-height:1.8;">
                            بعد إدخال معلومات حسابك أعلاه، اضغط على الزر أدناه لإرسالها عبر واتساب:
                        </p>
                        <div style="background:#fff; padding:1rem; border-radius:8px; text-align:center; margin-bottom:1rem;">
                            <div style="font-family:'El Messiri',sans-serif; font-size:1.2rem; font-weight:700; color:#155724; direction:ltr;">
                                +963 968355553
                            </div>
                        </div>
                        <button onclick="sendToWhatsApp()" style="display:block; width:100%; padding:1rem; background:#25D366; color:#fff; border:none; border-radius:8px; font-family:'El Messiri',sans-serif; font-weight:700; font-size:1rem; text-align:center; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(37,211,102,0.3);" onmouseover="this.style.background='#20BA5A'" onmouseout="this.style.background='#25D366'">
                            <i class="fab fa-whatsapp" style="margin-left:0.5rem; font-size:1.2rem;"></i>
                            إرسال معلومات حسابي عبر واتساب
                        </button>
                    </div>
                    
                    <button class="btn-primary" onclick="window.location.href='/profile'">
                        <i class="fas fa-check-circle"></i> فهمت، سأرسل إثبات الدفع
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let map;
        let storageMarker;
        
        // Storage location coordinates (from checkout page)
        const STORAGE_LOCATION = {
            lat: 32.749925,
            lng: 36.573006,
            name: 'Tulip mart توليب مارت'
        };
        
        function selectPaymentMethod(method) {
            // Hide payment options
            document.getElementById('paymentOptionsSection').style.display = 'none';
            
            // Show selected payment section
            if (method === 'cash') {
                document.getElementById('cashPaymentSection').classList.add('active');
                initMap();
            } else if (method === 'shamcash') {
                document.getElementById('shamcashPaymentSection').classList.add('active');
            }
        }
        
        function backToPaymentOptions() {
            // Hide all payment sections
            document.querySelectorAll('.payment-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show payment options
            document.getElementById('paymentOptionsSection').style.display = 'block';
        }
        
        function initMap() {
            if (map) return; // Map already initialized
            
            // Initialize Leaflet map with satellite view
            map = L.map('map').setView([STORAGE_LOCATION.lat, STORAGE_LOCATION.lng], 15);
            
            // Use satellite imagery tiles (ArcGIS World Imagery - no API key required)
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri',
                maxNativeZoom: 18,
                maxZoom: 18
            }).addTo(map);
            
            // Add marker for storage location
            storageMarker = L.marker([STORAGE_LOCATION.lat, STORAGE_LOCATION.lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background:#ff6b35; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:1.5rem; box-shadow:0 4px 15px rgba(255,107,53,0.5);"><i class="fas fa-store"></i></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            }).addTo(map);
            
            // Add popup to marker
            storageMarker.bindPopup(`
                <div style="font-family:'El Messiri',sans-serif; text-align:center; padding:0.5rem;">
                    <strong style="color:#0f4f55; font-size:1.1rem;">${STORAGE_LOCATION.name}</strong><br>
                    <span style="color:#666; font-size:0.9rem;">انقر للحصول على الاتجاهات</span>
                </div>
            `).openPopup();
            
            // Open in Google Maps when marker is clicked
            storageMarker.on('click', function() {
                window.open(`https://www.google.com/maps/dir/?api=1&destination=${STORAGE_LOCATION.lat},${STORAGE_LOCATION.lng}`, '_blank');
            });
        }
        
        // Send user account info to WhatsApp
        function sendToWhatsApp() {
            const userAccountName = document.getElementById('userAccountName').value.trim();
            const userAccountNumber = document.getElementById('userAccountNumber').value.trim();
            
            // Validate inputs
            if (!userAccountName || !userAccountNumber) {
                alert('يرجى إدخال اسم الحساب ورقم الحساب أولاً');
                return;
            }
            
            const message = `مرحباً، أود شحن رصيدي عبر Sham Cash\n\nمعلومات حسابي:\nاسم الحساب: ${userAccountName}\nرقم الحساب: ${userAccountNumber}\n\nحساب Tulip Mart:\nاسم الحساب: Tulip Mart\nرقم الحساب: cc8571e4f93387893e15f39cda36f45a`;
            const whatsappUrl = `https://wa.me/963968355553?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
        
        // Copy account information to clipboard
        function copyAccountInfo() {
            const accountName = document.getElementById('accountName').textContent;
            const accountNumber = document.getElementById('accountNumber').textContent;
            const text = `اسم الحساب: ${accountName}\nرقم الحساب: ${accountNumber}`;
            
            navigator.clipboard.writeText(text).then(() => {
                alert('تم نسخ معلومات الحساب بنجاح!');
            }).catch(() => {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('تم نسخ معلومات الحساب بنجاح!');
            });
        }
        
        function copyAccountNumber() {
            const accountNumber = document.getElementById('accountNumber').textContent.trim();
            
            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(accountNumber).then(() => {
                    alert('تم نسخ رقم الحساب بنجاح!');
                }).catch(err => {
                    // Fallback to old method
                    fallbackCopyTextToClipboard(accountNumber);
                });
            } else {
                // Fallback for older browsers
                fallbackCopyTextToClipboard(accountNumber);
            }
        }
        
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.top = '0';
            textArea.style.left = '0';
            textArea.style.width = '2em';
            textArea.style.height = '2em';
            textArea.style.padding = '0';
            textArea.style.border = 'none';
            textArea.style.outline = 'none';
            textArea.style.boxShadow = 'none';
            textArea.style.background = 'transparent';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    alert('تم نسخ رقم الحساب بنجاح!');
                } else {
                    alert('فشل نسخ رقم الحساب. يرجى نسخه يدوياً.');
                }
            } catch (err) {
                alert('فشل نسخ رقم الحساب. يرجى نسخه يدوياً.');
            }
            
            document.body.removeChild(textArea);
        }
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/recharge.blade.php ENDPATH**/ ?>