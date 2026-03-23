<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إتمام عملية الدفع - Tulip Store</title>
    <!-- fav icon -->
   <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- OPTION 1: Google Maps (Requires API Key) -->
    <!-- Get your FREE API key from: https://console.cloud.google.com/ -->
    <!-- Uncomment the line below and add your API key: -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE&libraries=places,geometry&language=ar"></script> -->
    
    <!-- OPTION 2: Leaflet Maps (Free, No API Key Required) - CURRENTLY ACTIVE -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Routing Machine JS only (CSS removed to hide UI) -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    
    <style>
        /* Smooth Transitions for Payment Sections */
        .payment-section {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }
        
        .payment-section.active {
            opacity: 1;
            transform: translateX(0);
        }
        
        .payment-section.hiding {
            opacity: 0;
            transform: translateX(-30px);
        }
        
        /* Card Type Icons */
        .card-type-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        
        .card-type-icon.visible {
            opacity: 1;
        }
        
        .card-type-icon.visa {
            color: #1434CB;
        }
        
        .card-type-icon.mastercard {
            color: #EB001B;
        }
        
        /* Elegant Card Input */
        .elegant-card-input {
            position: relative;
        }
        
        /* QR Code Container Animation */
        .qr-container {
            animation: fadeInScale 0.5s ease;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Payment Method Cards Hover */
        .payment-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        /* Responsive Mobile Fixes */
        @media (max-width: 768px) {
            #mainContainer {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
                display: flex !important;
                flex-direction: column !important;
            }
            
            #rightPanel, #formContainer {
                height: auto !important;
                overflow: visible !important;
                box-shadow: none !important;
                padding: 1rem !important;
            }

            /* Step 1: Map on top (rightPanel is map in step 1) */
            #rightPanel {
                height: 300px !important;
                order: 1;
            }
            
            #formContainer {
                order: 2;
                overflow-y: visible !important;
            }

            /* Hide order summary on mobile for steps 2-4 */
            body.step-2 #rightPanel,
            body.step-3 #rightPanel,
            body.step-4 #rightPanel {
                display: none !important;
            }

            body.step-2 #formContainer,
            body.step-3 #formContainer,
            body.step-4 #formContainer {
                width: 100% !important;
            }

            /* Disable scrolls */
            .blue-scrollbar, .orange-scrollbar {
                overflow: visible !important;
                max-height: none !important;
            }

            /* Smaller font sizes */
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.2rem !important; }
            label { font-size: 0.8rem !important; }
            input, select, textarea { padding: 0.6rem !important; font-size: 0.85rem !important; }
            .btn, button { padding: 0.5rem !important; font-size: 0.8rem !important; }
        }
    </style>
</head>
<body style="margin:0; font-family:'El Messiri',sans-serif; background:#f5f5f5;" class="step-1">

@if(View::exists('components.navbar'))
    @include('components.navbar')
@endif

@guest
<script>
    // Redirect to login if not authenticated
    window.location.href = '/login?redirect=/checkout';
</script>
@endguest

<!-- Policy Acceptance Modal -->
<div id="policyModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:10000; align-items:center; justify-content:center; overflow-y:auto; padding:2rem 0;">
    <div style="background:#fff; border-radius:20px; max-width:600px; width:90%; margin:auto; box-shadow:0 10px 40px rgba(0,0,0,0.3); animation:modalSlideIn 0.4s ease; display:flex; flex-direction:column;">
        <!-- Header -->
        <div style="background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); padding:2rem; text-align:center; border-radius:20px 20px 0 0;">
            <i class="fas fa-shield-alt" style="font-size:3rem; color:#fff; margin-bottom:0.5rem;"></i>
            <h2 style="font-family:'El Messiri',sans-serif; font-size:1.8rem; font-weight:700; color:#fff; margin:0;">سياسة الخصوصية والشروط</h2>
        </div>
        
        <!-- Content -->
        <div style="padding:2rem; overflow-y:auto; flex:1;">
            <div style="background:#f8f9fa; padding:1.5rem; border-radius:12px; border-right:4px solid #2a7080; margin-bottom:1.5rem;">
                <h3 style="font-family:'El Messiri',sans-serif; font-size:1.2rem; font-weight:700; color:#2a7080; margin:0 0 1rem 0;">
                    <i class="fas fa-info-circle" style="margin-left:0.5rem;"></i>
                    شروط الاستخدام
                </h3>
                <ul style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#555; line-height:1.8; margin:0; padding-right:1.5rem;">
                    <li>جميع المنتجات المعروضة متوفرة حسب المخزون</li>
                    <li>الأسعار قابلة للتغيير دون إشعار مسبق</li>
                    <li>يتم تأكيد الطلب خلال 24 ساعة</li>
                    <li>سياسة الإرجاع متاحة خلال 7 أيام من الاستلام</li>
                    <li>يتم حساب رسوم التوصيل حسب المسافة</li>
                </ul>
            </div>
            
            <div style="background:#fff3cd; padding:1.5rem; border-radius:12px; border-right:4px solid #ffc107; margin-bottom:1.5rem;">
                <h3 style="font-family:'El Messiri',sans-serif; font-size:1.2rem; font-weight:700; color:#856404; margin:0 0 1rem 0;">
                    <i class="fas fa-lock" style="margin-left:0.5rem;"></i>
                    سياسة الخصوصية
                </h3>
                <ul style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#856404; line-height:1.8; margin:0; padding-right:1.5rem;">
                    <li>نحن نحترم خصوصيتك ونحافظ على بياناتك</li>
                    <li>لن يتم مشاركة معلوماتك مع أطراف ثالثة</li>
                    <li>معلومات الدفع محمية بتشفير SSL</li>
                    <li>يمكنك طلب حذف بياناتك في أي وقت</li>
                </ul>
            </div>
            
            <div style="background:#e8f4f8; padding:1.5rem; border-radius:12px; border-right:4px solid #2a7080;">
                <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#2a7080; margin:0; line-height:1.8; font-weight:600;">
                    <i class="fas fa-check-circle" style="margin-left:0.5rem; color:#28a745;"></i>
                    بالمتابعة، أنت توافق على جميع الشروط والأحكام وسياسة الخصوصية الخاصة بنا
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="padding:1.5rem 2rem; background:#f8f9fa; border-top:2px solid #e0e0e0; display:flex; gap:1rem;">
            <button onclick="declinePolicy()" style="flex:1; background:#e0e0e0; color:#666; border:none; padding:1rem; font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s;">
                <i class="fas fa-times" style="margin-left:0.5rem;"></i>
                رفض
            </button>
            <button onclick="acceptPolicy()" style="flex:2; background:linear-gradient(135deg, #28a745 0%, #20c997 100%); color:#fff; border:none; padding:1rem; font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(40,167,69,0.3);">
                <i class="fas fa-check-circle" style="margin-left:0.5rem;"></i>
                أوافق وأتابع
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    #policyModal.show {
        display: flex !important;
    }
</style>

<!-- Header with Background Image -->
<section style="background-image:url('/images/footer.jpg'); background-size:cover; background-position:center; padding:3rem 1.5rem; text-align:center; position:relative; overflow:hidden;">
    <h1 style="font-family:'El Messiri',sans-serif; font-size:2.5rem; font-weight:800; color:#fff; margin:0; position:relative; z-index:2; text-shadow:0 2px 10px rgba(0,0,0,0.5);">إتمام عملية الدفع</h1>
</section>

<!-- Progress Steps (Modern Design - Left to Right) -->
<section style="max-width:1100px; margin:2rem auto 1.5rem; padding:0 1.5rem;">
    <div style="background:#fff; padding:1.2rem 2rem; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div style="display:flex; justify-content:space-between; align-items:center; position:relative;">
            <!-- Progress Line Background -->
            <div style="position:absolute; top:22px; left:15%; right:15%; height:3px; background:linear-gradient(to left, #e8f4f8 0%, #e8f4f8 100%); border-radius:3px; z-index:1;"></div>
            <!-- Active Progress Line -->
            <div id="progressLine" style="position:absolute; top:22px; right:85%; height:3px; background:linear-gradient(to left, #ff6b35 0%, #ff8c5a 100%); border-radius:3px; z-index:2; width:0%; transition:all 0.6s cubic-bezier(0.4, 0, 0.2, 1); box-shadow:0 2px 8px rgba(255,107,53,0.3);"></div>
            
            <!-- Step 4: تأكيد الطلب -->
            <div id="step4" class="step-item" style="flex:1; text-align:center; position:relative; z-index:3;">
                <div style="width:45px; height:45px; border-radius:50%; background:#e8f4f8; margin:0 auto 0.6rem; display:flex; align-items:center; justify-content:center; transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border:2px solid #e8f4f8; position:relative;">
                    <i class="fas fa-check-circle" style="font-size:1.1rem; color:#99c2cc; transition:all 0.3s;"></i>
                    <span style="position:absolute; top:-6px; right:-6px; width:20px; height:20px; background:#e8f4f8; border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:'El Messiri',sans-serif; font-size:0.65rem; font-weight:700; color:#99c2cc; border:2px solid #fff;">4</span>
                </div>
                <p style="font-family:'El Messiri',sans-serif; font-size:0.75rem; font-weight:600; color:#99c2cc; margin:0; transition:all 0.3s;">تأكيد الطلب</p>
            </div>
            
            <!-- Step 3: طريقة الدفع -->
            <div id="step3" class="step-item" style="flex:1; text-align:center; position:relative; z-index:3;">
                <div style="width:45px; height:45px; border-radius:50%; background:#e8f4f8; margin:0 auto 0.6rem; display:flex; align-items:center; justify-content:center; transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border:2px solid #e8f4f8; position:relative;">
                    <i class="fas fa-credit-card" style="font-size:1.1rem; color:#99c2cc; transition:all 0.3s;"></i>
                    <span style="position:absolute; top:-6px; right:-6px; width:20px; height:20px; background:#e8f4f8; border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:'El Messiri',sans-serif; font-size:0.65rem; font-weight:700; color:#99c2cc; border:2px solid #fff;">3</span>
                </div>
                <p style="font-family:'El Messiri',sans-serif; font-size:0.75rem; font-weight:600; color:#99c2cc; margin:0; transition:all 0.3s;">طريقة الدفع</p>
            </div>
            
            <!-- Step 2: طريقة التوصيل -->
            <div id="step2" class="step-item" style="flex:1; text-align:center; position:relative; z-index:3;">
                <div style="width:45px; height:45px; border-radius:50%; background:#e8f4f8; margin:0 auto 0.6rem; display:flex; align-items:center; justify-content:center; transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border:2px solid #e8f4f8; position:relative;">
                    <i class="fas fa-truck" style="font-size:1.1rem; color:#99c2cc; transition:all 0.3s;"></i>
                    <span style="position:absolute; top:-6px; right:-6px; width:20px; height:20px; background:#e8f4f8; border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:'El Messiri',sans-serif; font-size:0.65rem; font-weight:700; color:#99c2cc; border:2px solid #fff;">2</span>
                </div>
                <p style="font-family:'El Messiri',sans-serif; font-size:0.75rem; font-weight:600; color:#99c2cc; margin:0; transition:all 0.3s;">طريقة التوصيل</p>
            </div>
            
            <!-- Step 1: معلومات الشحن -->
            <div id="step1" class="step-item active" style="flex:1; text-align:center; position:relative; z-index:3;">
                <div style="width:45px; height:45px; border-radius:50%; background:#fff; margin:0 auto 0.6rem; display:flex; align-items:center; justify-content:center; transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border:2px solid #ff6b35; box-shadow:0 4px 20px rgba(255,107,53,0.25); position:relative; transform:scale(1.05);">
                    <i class="fas fa-map-marker-alt" style="font-size:1.1rem; color:#ff6b35; transition:all 0.3s;"></i>
                    <span style="position:absolute; top:-6px; right:-6px; width:20px; height:20px; background:#ff6b35; border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:'El Messiri',sans-serif; font-size:0.65rem; font-weight:700; color:#fff; border:2px solid #fff; box-shadow:0 2px 8px rgba(255,107,53,0.3);">1</span>
                </div>
                <p style="font-family:'El Messiri',sans-serif; font-size:0.75rem; font-weight:700; color:#2a7080; margin:0; transition:all 0.3s;">معلومات الشحن</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section style="max-width:1100px; margin:2rem auto 4rem; padding:0 1.5rem;">
    <div id="mainContainer" style="display:grid; grid-template-columns:1.2fr 1fr; gap:2rem;">
        
        <!-- Right Side: Map (Step 1) / Cart Summary (Steps 2-4) -->
        <div id="rightPanel" style="background:#fff; border-radius:16px; overflow:hidden; height:600px; position:relative; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <!-- Map Container -->
            <div id="mapContainer" style="width:100%; height:100%; display:block; position:relative;">
                <div id="map" style="width:100%; height:100%; position:relative; z-index:1;"></div>
                
                <!-- Back to cart button -->
                <a href="/cart" style="position:absolute; bottom:1rem; left:1rem; z-index:1000; display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.2rem; background:#fff; color:#2a7080; border:2px solid #2a7080; border-radius:12px; font-family:'El Messiri',sans-serif; font-weight:700; font-size:0.9rem; text-decoration:none; box-shadow:0 4px 15px rgba(0,0,0,0.1); transition:all 0.3s;" onmouseover="this.style.background='#2a7080';this.style.color='#fff';" onmouseout="this.style.background='#fff';this.style.color='#2a7080';">
                    <i class="fas fa-arrow-right"></i>
                    رجوع إلى السلة
                </a>
                <!-- Search Box -->
                <div style="position:absolute; top:1rem; right:1rem; left:1rem; z-index:1000;">
                    <div style="display:flex; gap:0.5rem;">
                        <input type="text" id="mapSearch" placeholder="ابحث عن عنوان أو منطقة..." style="flex:1; padding:0.9rem 1.2rem; border:none; border-radius:12px; font-family:'El Messiri',sans-serif; font-size:0.95rem; box-shadow:0 4px 15px rgba(0,0,0,0.15); background:#fff;">
                        <button onclick="searchMapLocation()" style="padding:0.9rem 1.2rem; background:#2a7080; color:#fff; border:none; border-radius:12px; cursor:pointer; box-shadow:0 4px 15px rgba(0,0,0,0.15); transition:all 0.3s;" onmouseover="this.style.background='#1f5a68'" onmouseout="this.style.background='#2a7080'">
                            <i class="fas fa-search"></i>
                        </button>
                        <button onclick="getCurrentLocation()" title="موقعي الحالي" style="padding:0.9rem 1.2rem; background:#ff6b35; color:#fff; border:none; border-radius:12px; cursor:pointer; box-shadow:0 4px 15px rgba(0,0,0,0.15); transition:all 0.3s;" onmouseover="this.style.background='#e55a2b'" onmouseout="this.style.background='#ff6b35'">
                            <i class="fas fa-crosshairs"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Map Instructions -->
                <div id="mapInstructions" style="position:absolute; top:5rem; left:50%; transform:translateX(-50%); background:rgba(42,112,128,0.95); color:#fff; padding:0.8rem 1.5rem; border-radius:10px; font-family:'El Messiri',sans-serif; font-weight:600; font-size:0.9rem; box-shadow:0 4px 15px rgba(0,0,0,0.2); text-align:center; max-width:90%; z-index:1000; pointer-events:none; transition:all 0.3s;">
                    <i class="fas fa-map-marker-alt" style="margin-left:0.5rem;"></i>
                    انقر على الخريطة لتحديد موقع التوصيل بدقة
                </div>
                
                <!-- Route Info Panel -->
                <div id="routeInfo" style="display:none; position:absolute; bottom:1rem; right:1rem; background:#fff; padding:1rem 1.3rem; border-radius:12px; font-family:'El Messiri',sans-serif; box-shadow:0 4px 20px rgba(0,0,0,0.15); z-index:1000; min-width:200px;">
                    <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom:0.7rem;">
                        <i class="fas fa-route" style="color:#2a7080; font-size:1.2rem;"></i>
                        <div>
                            <div style="font-size:0.75rem; color:#666;">المسافة</div>
                            <div id="routeDistance" style="font-size:1.1rem; font-weight:700; color:#2a7080;">-- كم</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.8rem;">
                        <i class="fas fa-truck" style="color:#ff6b35; font-size:1.2rem;"></i>
                        <div>
                            <div style="font-size:0.75rem; color:#666;">تكلفة التوصيل</div>
                            <div id="routeCost" style="font-size:1.1rem; font-weight:700; color:#ff6b35;">-- SYP</div>
                        </div>
                    </div>
                </div>
                
                <!-- Selected Location Confirmation - HIDDEN (using route info panel instead) -->
                <div id="selectedLocation" style="display:none !important; visibility:hidden !important;"></div>
            </div>
            
            <!-- Cart Summary Container (Hidden initially) -->
            <div id="cartSummaryContainer" class="blue-scrollbar" style="width:100%; height:100%; display:none; padding:1.5rem; overflow-y:auto; flex-direction:column;">
                <h3 style="font-family:'El Messiri',sans-serif; font-size:1.3rem; font-weight:700; color:#1a1a1a; margin:0 0 1rem 0; display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">
                    <i class="fas fa-shopping-cart" style="color:#ff6b35;"></i>
                    ملخص الطلب
                </h3>
                
                <!-- Cart Items with Scroll -->
                <div id="cartItemsList" class="orange-scrollbar" style="margin-bottom:1rem; overflow-y:auto; flex-shrink:1; max-height:180px;">
                    <!-- Will be filled by JavaScript -->
                </div>
                
                <!-- Fixed Bottom Section (No Scroll) -->
                <div style="flex-shrink:0;">
                <!-- Currency Toggle with Flag Icons -->
                <div style="background:#e8f4f8; padding:0.8rem; border-radius:10px; margin-bottom:0.8rem; display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <i class="fas fa-exchange-alt" style="color:#2a7080; font-size:1rem;"></i>
                        <span style="font-family:'El Messiri',sans-serif; font-weight:600; color:#2a7080; font-size:0.9rem;">العملة:</span>
                    </div>
                    <div style="display:flex; gap:0.5rem;">
                        <button id="currencyUSD" onclick="switchCurrency('USD')" style="padding:0.5rem 1rem; border:2px solid #2a7080; background:#2a7080; color:#fff; border-radius:8px; font-family:'El Messiri',sans-serif; font-weight:700; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; gap:0.4rem; font-size:0.85rem;">
                            <svg width="24" height="16" viewBox="0 0 7410 3900" style="border-radius:2px; box-shadow:0 1px 3px rgba(0,0,0,0.2);">
                                <rect width="7410" height="3900" fill="#b22234"/>
                                <path d="M0,450H7410m0,600H0m0,600H7410m0,600H0m0,600H7410m0,600H0" stroke="#fff" stroke-width="300"/>
                                <rect width="2964" height="2100" fill="#3c3b6e"/>
                            </svg>
                            <span>USD</span>
                        </button>
                        <button id="currencySYP" onclick="switchCurrency('SYP')" style="padding:0.5rem 1rem; border:2px solid #2a7080; background:#fff; color:#2a7080; border-radius:8px; font-family:'El Messiri',sans-serif; font-weight:700; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; gap:0.4rem; font-size:0.85rem;">
                            <svg width="24" height="16" viewBox="0 0 900 600" style="border-radius:2px; box-shadow:0 1px 3px rgba(0,0,0,0.2);">
                                <!-- Free Syria Flag: Green-White-Black horizontal stripes with 3 red stars -->
                                <rect width="900" height="200" fill="#007A3D"/>
                                <rect width="900" height="200" y="200" fill="#FFFFFF"/>
                                <rect width="900" height="200" y="400" fill="#000000"/>
                                <!-- Three red 5-pointed stars on white stripe -->
                                <g fill="#CE1126">
                                    <path d="M 200 240 L 215 285 L 262 285 L 224 312 L 239 357 L 200 330 L 161 357 L 176 312 L 138 285 L 185 285 Z"/>
                                    <path d="M 450 240 L 465 285 L 512 285 L 474 312 L 489 357 L 450 330 L 411 357 L 426 312 L 388 285 L 435 285 Z"/>
                                    <path d="M 700 240 L 715 285 L 762 285 L 724 312 L 739 357 L 700 330 L 661 357 L 676 312 L 638 285 L 685 285 Z"/>
                                </g>
                            </svg>
                            <span>SYP</span>
                        </button>
                    </div>
                </div>
                
                <!-- Delivery Cost Calculation -->
                <div style="background:#f8f9fa; padding:1rem; border-radius:10px; margin-bottom:0.8rem;">
                    <h4 style="font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#2a7080; margin:0 0 0.8rem 0;">
                        <i class="fas fa-route" style="margin-left:0.3rem;"></i>
                        حساب تكلفة التوصيل
                    </h4>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem;">
                        <span style="font-family:'El Messiri',sans-serif; color:#666; font-size:0.85rem;">المسافة:</span>
                        <span id="deliveryDistance" style="font-family:'El Messiri',sans-serif; font-weight:700; color:#2a7080; font-size:0.85rem;">-- كم</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem;">
                        <span style="font-family:'El Messiri',sans-serif; color:#666; font-size:0.85rem;">نوع التوصيل:</span>
                        <span id="deliveryTypeName" style="font-family:'El Messiri',sans-serif; font-weight:700; color:#2a7080; font-size:0.85rem;">--</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding-top:0.6rem; border-top:2px solid #e0e0e0;">
                        <span style="font-family:'El Messiri',sans-serif; font-weight:700; color:#333; font-size:0.9rem;">تكلفة التوصيل:</span>
                        <span id="deliveryCost" style="font-family:'El Messiri',sans-serif; font-weight:700; font-size:1rem; color:#ff6b35;">0</span>
                    </div>
                </div>
                
                <!-- Total Summary -->
                <div style="background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); padding:1.2rem; border-radius:10px; color:#fff;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem;">
                        <span style="font-family:'El Messiri',sans-serif; font-size:0.9rem;">المجموع الفرعي:</span>
                        <span id="subtotalAmount" style="font-family:'El Messiri',sans-serif; font-weight:700; font-size:0.9rem;">0</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.6rem;">
                        <span style="font-family:'El Messiri',sans-serif; font-size:0.9rem;">التوصيل:</span>
                        <span id="shippingAmount" style="font-family:'El Messiri',sans-serif; font-weight:700; font-size:0.9rem;">0</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding-top:0.6rem; border-top:2px solid rgba(255,255,255,0.3);">
                        <span style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700;">المجموع الكلي:</span>
                        <span id="totalAmount" style="font-family:'El Messiri',sans-serif; font-size:1.3rem; font-weight:700; color:#ffd700;">0</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
        
        <!-- Left Side: Form -->
        <div id="formContainer" class="blue-scrollbar" style="background:#fff; padding:1.5rem; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.08); position:relative; overflow-y:auto; height:600px;">
            
            <!-- Step 1: Shipping Info -->
            <div id="shippingForm" class="step-content" style="animation:fadeIn 0.4s ease;">
                <h2 style="font-family:'El Messiri',sans-serif; font-size:1.4rem; font-weight:700; color:#1a1a1a; margin:0 0 1rem 0;">معلومات الشحن</h2>
                
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:600; color:#555; margin-bottom:0.3rem;">اسم المستلم</label>
                    <input type="text" id="recipientName" placeholder="اسم المستلم الكامل" style="width:100%; padding:0.75rem; border:2px solid #e0e0e0; border-radius:10px; font-family:'El Messiri',sans-serif; font-size:0.9rem; transition:all 0.3s; background:#f0f0f0;" onfocus="this.style.borderColor='#2a7080'; this.style.background='#fff'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f0f0f0'">
                </div>
                
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:600; color:#555; margin-bottom:0.3rem;">الرقم</label>
                    <input type="tel" id="phoneNumber" placeholder="رقم الهاتف" style="width:100%; padding:0.75rem; border:2px solid #e0e0e0; border-radius:10px; font-family:'El Messiri',sans-serif; font-size:0.9rem; transition:all 0.3s; background:#f0f0f0;" onfocus="this.style.borderColor='#2a7080'; this.style.background='#fff'" onblur="this.style.borderColor='#e0e0e0'; this.style.background='#f0f0f0'">
                </div>
                
                <div style="margin-bottom:0.9rem;">
                    <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:600; color:#555; margin-bottom:0.3rem;">
                        <i class="fas fa-map-marker-alt" style="color:#2a7080; margin-left:0.3rem;"></i>
                        القرية / المدينة - السويداء
                    </label>
                    <input type="text" id="village" readonly placeholder="حدد الموقع على الخريطة" style="width:100%; padding:0.75rem; border:2px solid #e0e0e0; border-radius:10px; font-family:'El Messiri',sans-serif; font-size:0.9rem; transition:all 0.3s; background:#f8f9fa; cursor:not-allowed; color:#333; font-weight:600;">
                    <input type="hidden" id="villageCoords" value="">
                </div>
                
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:600; color:#555; margin-bottom:0.3rem;">ملاحظة إضافية (اختياري)</label>
                    <textarea id="addressNote" placeholder="اكتب تفاصيل إضافية عن موقع التوصيل" style="width:100%; padding:0.75rem; border:2px solid #e0e0e0; border-radius:10px; font-family:'El Messiri',sans-serif; font-size:0.9rem; min-height:70px; resize:vertical; transition:all 0.3s;" onfocus="this.style.borderColor='#2a7080'" onblur="this.style.borderColor='#e0e0e0'"></textarea>
                </div>
                
                <button onclick="goToStep(2)" style="width:100%; background:#ff6b35; color:#fff; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:10px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(255,107,53,0.3);" onmouseover="this.style.background='#e55a2b'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(255,107,53,0.4)'" onmouseout="this.style.background='#ff6b35'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255,107,53,0.3)'">
                    التالي <i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i>
                </button>
            </div>
            
            <style>
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateX(20px); }
                    to { opacity: 1; transform: translateX(0); }
                }
                .step-content {
                    transition: opacity 0.3s ease, transform 0.3s ease;
                    min-height: 500px;
                    width: 100%;
                    position: relative;
                }
                .step-content > * {
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                select:disabled {
                    cursor: not-allowed !important;
                    opacity: 0.6;
                }
                select:not(:disabled) {
                    cursor: pointer !important;
                }
                select option {
                    padding: 0.5rem;
                }
                input:focus, select:focus, textarea:focus {
                    outline: none;
                }
            </style>
            
            <!-- Step 2: Delivery Method -->
            <div id="deliveryForm" class="step-content" style="display:none;">
                <h2 style="font-family:'El Messiri',sans-serif; font-size:1.8rem; font-weight:700; color:#1a1a1a; margin:0 0 1.5rem 0;">معلومات الشحن:</h2>
                
                <div style="background:#e8f4f8; padding:1.5rem; border-radius:12px; margin-bottom:2rem;">
                    
                    <!-- Option 1: توصيل عادي -->
                    <div onclick="selectDelivery('normal')" class="delivery-option" data-type="normal" style="background:#fff; padding:1rem; border-radius:14px; margin-bottom:1rem; cursor:pointer; border:3px solid #ff6b35; transition:all 0.3s; display:flex; align-items:center; gap:1rem;">
                        <div style="flex-shrink:0;">
                            <img src="/images/shipping/truck-tulip.png" alt="توصيل عادي" class="delivery-main-icon" style="width:60px; height:60px; object-fit:contain;" onerror="this.style.display='none';">
                        </div>
                        <div style="flex:1;">
                            <h3 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#1a1a1a; margin:0 0 0.2rem 0;">توصيل عادي</h3>
                            <p style="font-family:'El Messiri',sans-serif; font-size:0.9rem; color:#666; margin:0;">خلال مدة أقصاها أسبوع</p>
                        </div>
                    </div>
                    
                    <!-- Option 2: توصيل مستعجل -->
                    <div onclick="selectDelivery('express')" class="delivery-option" data-type="express" style="background:#fff; padding:1rem; border-radius:14px; margin-bottom:1rem; cursor:pointer; border:3px solid #e0e0e0; transition:all 0.3s; display:flex; align-items:center; gap:1rem;">
                        <div style="flex-shrink:0;">
                            <img src="/images/shipping/scooter-24h.png" alt="توصيل مستعجل" class="delivery-main-icon" style="width:60px; height:60px; object-fit:contain;" onerror="this.style.display='none';">
                        </div>
                        <div style="flex:1;">
                            <h3 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#1a1a1a; margin:0 0 0.2rem 0;">توصيل مستعجل</h3>
                            <p style="font-family:'El Messiri',sans-serif; font-size:0.9rem; color:#666; margin:0;">خلال 24 ساعة</p>
                        </div>
                    </div>
                    
                    <!-- Option 3: توصيل فوري -->
                    <div onclick="selectDelivery('instant')" class="delivery-option" data-type="instant" style="background:#fff; padding:1rem; border-radius:14px; cursor:pointer; border:3px solid #e0e0e0; transition:all 0.3s; display:flex; align-items:center; gap:1rem;">
                        <div style="flex-shrink:0;">
                            <img src="/images/shipping/phone-2h.png" alt="توصيل فوري" class="delivery-main-icon" style="width:60px; height:60px; object-fit:contain;" onerror="this.style.display='none';">
                        </div>
                        <div style="flex:1;">
                            <h3 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#1a1a1a; margin:0 0 0.2rem 0;">توصيل فوري</h3>
                            <p style="font-family:'El Messiri',sans-serif; font-size:0.9rem; color:#666; margin:0;">مسافة الطريق</p>
                        </div>
                    </div>
                </div>
                
                <div style="display:flex; gap:1rem;">
                    <button onclick="goToStep(1)" style="flex:1; background:#e0e0e0; color:#666; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s;">
                        <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i> السابق
                    </button>
                    <button onclick="goToStep(3)" style="flex:2; background:#ff6b35; color:#fff; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(255,107,53,0.3);">
                        التالي <i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i>
                    </button>
                </div>
            </div>
            
            <!-- Step 3: Payment Method Selection -->
            <div id="paymentForm" class="step-content" style="display:none;">
                <!-- Payment Options List -->
                <div id="paymentOptions" style="display:block!important;visibility:visible!important">
                    <h2 style="font-family:'El Messiri',sans-serif; font-size:1.8rem; font-weight:700; color:#1a1a1a; margin:0 0 1.5rem 0;">طريقة الدفع:</h2>
                    
                    <div style="background:#e8f4f8; padding:1.5rem; border-radius:12px; margin-bottom:2rem;">
                        
                        <!-- Option 1: الدفع عند الاستلام -->
                        <div onclick="selectPayment('cash')" class="payment-option" data-type="cash" style="background:#fff; padding:1.5rem; border-radius:12px; margin-bottom:1rem; cursor:pointer; border:3px solid #ff6b35; transition:all 0.3s; display:flex; align-items:center; gap:1rem;">
                            <div style="flex-shrink:0;">
                                <i class="fas fa-money-bill-wave" style="font-size:2.5rem; color:#ff6b35;"></i>
                            </div>
                            <div style="flex:1;">
                                <h3 style="font-family:'El Messiri',sans-serif; font-size:1.2rem; font-weight:700; color:#1a1a1a; margin:0 0 0.3rem 0;">الدفع عند الاستلام</h3>
                                <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#666; margin:0;">ادفع نقداً عند استلام الطلب</p>
                            </div>
                            <div style="flex-shrink:0;">
                                <i class="fas fa-check-circle" style="font-size:1.5rem; color:#ff6b35;"></i>
                            </div>
                        </div>
                        
                        <!-- Option 2: بطاقة ائتمان -->
                        <div onclick="selectPayment('card')" class="payment-option" data-type="card" style="background:#fff; padding:1.5rem; border-radius:12px; margin-bottom:1rem; cursor:pointer; border:3px solid #e0e0e0; transition:all 0.3s; display:flex; align-items:center; gap:1rem;">
                            <div style="flex-shrink:0;">
                                <i class="fas fa-credit-card" style="font-size:2.5rem; color:#2a7080;"></i>
                            </div>
                            <div style="flex:1;">
                                <h3 style="font-family:'El Messiri',sans-serif; font-size:1.2rem; font-weight:700; color:#1a1a1a; margin:0 0 0.3rem 0;">بطاقة ائتمان</h3>
                                <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#666; margin:0;">ادفع بأمان باستخدام بطاقتك</p>
                            </div>
                            <div style="flex-shrink:0;">
                                <i class="far fa-circle" style="font-size:1.5rem; color:#ccc;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display:flex; gap:1rem;">
                        <button onclick="goToStep(2)" style="flex:1; background:#e0e0e0; color:#666; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s;">
                            <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i> السابق
                        </button>
                        <button onclick="proceedWithPayment()" style="flex:2; background:#ff6b35; color:#fff; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(255,107,53,0.3);">
                            متابعة <i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Credit Card Details Form -->
                <div id="creditCardDetails" class="payment-section" style="display:none;">
                    <button onclick="backToPaymentOptions()" style="background:transparent; border:none; color:#2a7080; font-family:'El Messiri',sans-serif; font-size:0.9rem; font-weight:600; cursor:pointer; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem; transition:all 0.3s;">
                        <i class="fas fa-arrow-right"></i> العودة لطرق الدفع
                    </button>
                    
                    <!-- Visual Credit Card Preview -->
                    <div style="perspective:1000px; margin-bottom:2rem;">
                        <div id="cardPreview" style="width:100%; max-width:420px; height:240px; margin:0 auto; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:20px; padding:2rem; box-shadow:0 20px 60px rgba(102,126,234,0.4); position:relative; transform-style:preserve-3d; transition:transform 0.6s;">
                            <div style="position:absolute; top:2rem; right:2rem; width:60px; height:40px; background:rgba(255,255,255,0.3); border-radius:8px;"></div>
                            <div style="position:absolute; top:2rem; left:2rem;">
                                <i id="previewCardType" class="fas fa-credit-card" style="font-size:2.5rem; color:rgba(255,255,255,0.9);"></i>
                            </div>
                            <div style="position:absolute; bottom:5rem; right:2rem; left:2rem;">
                                <div id="previewCardNumber" style="font-family:'El Messiri', sans-serif; font-size:1.5rem; font-weight:700; color:#fff; letter-spacing:3px; direction:ltr; text-align:left;">•••• •••• •••• ••••</div>
                            </div>
                            <div style="position:absolute; bottom:2rem; right:2rem; left:2rem; display:flex; justify-content:space-between; align-items:flex-end;">
                                <div>
                                    <div style="font-size:0.7rem; color:rgba(255,255,255,0.7); margin-bottom:0.3rem;">حامل البطاقة</div>
                                    <div id="previewCardName" style="font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:600; color:#fff;">الاسم الكامل</div>
                                </div>
                                <div style="text-align:left;">
                                    <div style="font-size:0.7rem; color:rgba(255,255,255,0.7); margin-bottom:0.3rem;">ينتهي في</div>
                                    <div id="previewCardExpiry" style="font-family:'El Messiri', sans-serif; font-size:1rem; font-weight:600; color:#fff; direction:ltr;">MM/YY</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Form Container -->
                    <div style="background:#fff; padding:2.5rem; border-radius:20px; box-shadow:0 10px 40px rgba(0,0,0,0.08); margin-bottom:1.5rem;">
                        <h2 style="font-family:'El Messiri',sans-serif; font-size:1.6rem; font-weight:800; color:#1a1a1a; margin:0 0 2rem 0; text-align:center;">
                            معلومات البطاقة
                        </h2>
                        
                        <!-- Saved Cards Section -->
                        <div id="savedCardsSection" style="margin-bottom:1.5rem;">
                            <label style="display:block; font-family:'El Messiri',sans-serif; font-size:0.95rem; font-weight:600; color:#555; margin-bottom:0.8rem;">البطاقات المحفوظة:</label>
                            <div id="savedCardsList" style="display:flex; flex-direction:column; gap:0.8rem; margin-bottom:1rem;">
                                <!-- Saved cards will be loaded here -->
                            </div>
                            <button type="button" onclick="showNewCardForm()" style="width:100%; padding:0.9rem; background:#f8f9fa; border:2px dashed #2a7080; color:#2a7080; border-radius:10px; font-family:'El Messiri',sans-serif; font-weight:600; cursor:pointer; transition:all 0.3s;">
                                <i class="fas fa-plus-circle" style="margin-left:0.5rem;"></i>
                                إضافة بطاقة جديدة
                            </button>
                        </div>
                        
                        <!-- New Card Form -->
                        <div id="newCardForm">
                            <!-- Card Number -->
                            <div style="margin-bottom:2rem;">
                                <label style="display:block; font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#1a1a1a; margin-bottom:0.8rem;">
                                    <i class="fas fa-credit-card" style="margin-left:0.4rem; color:#667eea;"></i>
                                    رقم البطاقة
                                </label>
                                <div style="position:relative;">
                                    <input type="text" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19" style="width:100%; padding:1.3rem 70px 1.3rem 1.3rem; border:2px solid #e0e7ff; border-radius:16px; font-family:'El Messiri', sans-serif; font-size:1.2rem; font-weight:700; transition:all 0.3s; direction:ltr; text-align:left; background:linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%);" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 4px rgba(102,126,234,0.15)'; this.style.background='#fff'" onblur="this.style.borderColor='#e0e7ff'; this.style.boxShadow='none'; this.style.background='linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%)'" oninput="formatCardNumber(this); detectCardType(this); updateCardPreview()">
                                    <div style="position:absolute; right:8px; top:50%; transform:translateY(-50%); width:50px; height:35px; display:flex; align-items:center; justify-content:center; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); pointer-events:none;">
                                        <i id="cardTypeIcon" class="fab fa-cc-visa" style="font-size:2rem; transition:all 0.3s;"></i>
                                    </div>
                                </div>
                                <span id="cardNumberError" style="color: #e74c3c; font-size: 0.85rem; font-family: 'El Messiri', sans-serif; margin-top: 0.5rem; display: none;">الرجاء إدخال رقم بطاقة صحيح</span>
                            </div>
                            
                            <!-- Card Holder Name -->
                            <div style="margin-bottom:2rem;">
                                <label style="display:block; font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#1a1a1a; margin-bottom:0.8rem;">
                                    <i class="fas fa-user" style="margin-left:0.4rem; color:#667eea;"></i>
                                    اسم حامل البطاقة
                                </label>
                                <input type="text" id="cardName" placeholder="الاسم كما هو مكتوب على البطاقة" style="width:100%; padding:1.3rem; border:2px solid #e0e7ff; border-radius:16px; font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:600; transition:all 0.3s; background:linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%);" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 4px rgba(102,126,234,0.15)'; this.style.background='#fff'" onblur="this.style.borderColor='#e0e7ff'; this.style.boxShadow='none'; this.style.background='linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%)'" oninput="updateCardPreview()">
                                <span id="cardNameError" style="color: #e74c3c; font-size: 0.85rem; font-family: 'El Messiri', sans-serif; margin-top: 0.5rem; display: none;">الرجاء إدخال اسم حامل البطاقة</span>
                            </div>
                            
                            <!-- Expiry and CVV -->
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
                                <div>
                                    <label style="display:block; font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#1a1a1a; margin-bottom:0.8rem;">
                                        <i class="fas fa-calendar-alt" style="margin-left:0.4rem; color:#667eea;"></i>
                                        تاريخ الانتهاء
                                    </label>
                                    <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" style="width:100%; padding:1.3rem; border:2px solid #e0e7ff; border-radius:16px; font-family:'El Messiri', sans-serif; font-size:1.2rem; font-weight:700; transition:all 0.3s; direction:ltr; text-align:center; background:linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%);" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 4px rgba(102,126,234,0.15)'; this.style.background='#fff'" onblur="this.style.borderColor='#e0e7ff'; this.style.boxShadow='none'; this.style.background='linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%)'" oninput="formatExpiry(this); updateCardPreview()">
                                    <span id="cardExpiryError" style="color: #e74c3c; font-size: 0.85rem; font-family: 'El Messiri', sans-serif; margin-top: 0.5rem; display: none;">تاريخ غير صحيح</span>
                                </div>
                                <div>
                                    <label style="display:block; font-family:'El Messiri',sans-serif; font-size:1rem; font-weight:700; color:#1a1a1a; margin-bottom:0.8rem;">
                                        <i class="fas fa-lock" style="margin-left:0.4rem; color:#667eea;"></i>
                                        CVV
                                    </label>
                                    <input type="text" id="cardCVV" placeholder="123" maxlength="4" style="width:100%; padding:1.3rem; border:2px solid #e0e7ff; border-radius:16px; font-family:'El Messiri', sans-serif; font-size:1.2rem; font-weight:700; transition:all 0.3s; direction:ltr; text-align:center; background:linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%);" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 4px rgba(102,126,234,0.15)'; this.style.background='#fff'" onblur="this.style.borderColor='#e0e7ff'; this.style.boxShadow='none'; this.style.background='linear-gradient(135deg, #fafbff 0%, #f8f9ff 100%)'" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    <span id="cardCVVError" style="color: #e74c3c; font-size: 0.85rem; font-family: 'El Messiri', sans-serif; margin-top: 0.5rem; display: none;">رمز CVV غير صحيح</span>
                                </div>
                            </div>
                            
                            <!-- Save Card Checkbox -->
                            <div style="background:linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%); padding:1.3rem; border-radius:14px; border:2px solid #c7d2fe;">
                                <label style="display:flex; align-items:center; gap:0.8rem; cursor:pointer;">
                                    <input type="checkbox" id="saveCard" style="width:22px; height:22px; cursor:pointer; accent-color:#667eea;">
                                    <span style="font-family:'El Messiri',sans-serif; font-size:1rem; color:#4338ca; font-weight:600;">
                                        <i class="fas fa-shield-alt" style="margin-left:0.4rem;"></i>
                                        حفظ البطاقة بشكل آمن للمشتريات المستقبلية
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div style="display:flex; gap:1rem;">
                        <button onclick="backToPaymentOptions()" style="flex:1; background:#e0e0e0; color:#666; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s;">
                            <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i> العودة
                        </button>
                        <button onclick="validateCardAndProceed()" style="flex:2; background:#ff6b35; color:#fff; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(255,107,53,0.3);">
                            متابعة <i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Step 4: Order Confirmation -->
            <div id="confirmationForm" class="step-content" style="display:none;">
                <h2 style="font-family:'El Messiri',sans-serif; font-size:1.8rem; font-weight:700; color:#1a1a1a; margin:0 0 1.5rem 0;">تأكيد الطلب</h2>
                
                <div style="background:#f8f9fa; padding:1.5rem; border-radius:12px; margin-bottom:1.5rem;">
                    <h3 style="font-family:'El Messiri',sans-serif; font-size:1.1rem; font-weight:700; color:#2a7080; margin:0 0 1rem 0;">
                        <i class="fas fa-info-circle" style="margin-left:0.5rem;"></i>
                        ملخص الطلب
                    </h3>
                    
                    <div id="orderSummary" style="display:flex; flex-direction:column; gap:0.8rem;">
                        <!-- Will be filled by JavaScript -->
                    </div>
                </div>
                
                <div style="background:#e8f4f8; padding:1.5rem; border-radius:12px; margin-bottom:2rem; border-right:4px solid #2a7080;">
                    <p style="font-family:'El Messiri',sans-serif; font-size:0.95rem; color:#666; margin:0; line-height:1.6;">
                        <i class="fas fa-shield-alt" style="color:#28a745; margin-left:0.5rem;"></i>
                        بالضغط على "تأكيد الطلب"، أنت توافق على شروط وأحكام الخدمة وسياسة الخصوصية الخاصة بنا.
                    </p>
                </div>
                
                <div style="display:flex; gap:1rem;">
                    <button type="button" onclick="event.stopPropagation(); console.log('Back button clicked'); goToStep(3); return false;" style="flex:1; background:#e0e0e0; color:#666; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s;">
                        <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i> السابق
                    </button>
                    <button type="button" onclick="event.stopPropagation(); console.log('Submit button clicked!'); submitOrder(); return false;" style="flex:2; background:linear-gradient(135deg, #28a745 0%, #20c997 100%); color:#fff; border:none; padding:0.6rem; font-family:'El Messiri',sans-serif; font-size:0.85rem; font-weight:700; border-radius:12px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(40,167,69,0.3);">
                        <i class="fas fa-check-circle" style="margin-left:0.5rem;"></i>
                        تأكيد الطلب
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</section>


<style>
    #map {
        cursor: crosshair !important;
        position: relative !important;
        z-index: 1 !important;
        border-radius: 16px;
        overflow: hidden;
    }
    
    #mapContainer {
        position: relative !important;
    }
    
    /* Leaflet popup styling */
    .leaflet-popup-content-wrapper {
        font-family: 'El Messiri', sans-serif;
        border-radius: 12px;
    }
    
    .leaflet-popup-content {
        font-family: 'El Messiri', sans-serif;
    }
    
    /* Hide ALL routing UI elements - distance box, instructions, etc. */
    .leaflet-routing-container,
    .leaflet-routing-container-hidden,
    .leaflet-routing-alternatives-container,
    .leaflet-bar.leaflet-routing-container,
    .leaflet-top.leaflet-right .leaflet-routing-container,
    .leaflet-control-container .leaflet-routing-container,
    .leaflet-routing-geocoders,
    .leaflet-routing-geocoder,
    .leaflet-routing-error,
    .leaflet-routing-collapsible,
    div[class*="leaflet-routing"],
    div[class*="routing-container"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        left: -9999px !important;
    }
    
    /* Specifically target the control in top-right */
    .leaflet-top.leaflet-right > * {
        display: none !important;
    }
    
    /* Hide zoom controls */
    .leaflet-control-zoom,
    .leaflet-top.leaflet-left .leaflet-control-zoom {
        display: none !important;
    }
    
    /* Custom marker animations */
    .delivery-marker {
        animation: markerBounce 0.6s ease-out;
    }
    
    @keyframes markerBounce {
        0% { transform: translateY(-100px); opacity: 0; }
        60% { transform: translateY(10px); opacity: 1; }
        80% { transform: translateY(-5px); }
        100% { transform: translateY(0); }
    }
    
    /* Map search input focus effect */
    #mapSearch:focus {
        outline: none;
        box-shadow: 0 4px 20px rgba(42, 112, 128, 0.3) !important;
    }
    
    /* Ensure map overlays don't block clicks */
    #mapInstructions,
    #selectedLocation {
        pointer-events: none !important;
    }
    
    /* Custom Scrollbar - Blue for whole card */
    .blue-scrollbar::-webkit-scrollbar {
        width: 10px;
    }
    
    .blue-scrollbar::-webkit-scrollbar-track {
        background: #e8f4f8;
        border-radius: 10px;
    }
    
    .blue-scrollbar::-webkit-scrollbar-thumb {
        background: #2a7080;
        border-radius: 10px;
        border: 2px solid #e8f4f8;
    }
    
    .blue-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #1a5060;
    }
    
    /* Custom Scrollbar - Orange for products */
    .orange-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    
    .orange-scrollbar::-webkit-scrollbar-track {
        background: #fff3e6;
        border-radius: 10px;
    }
    
    .orange-scrollbar::-webkit-scrollbar-thumb {
        background: #ff6b35;
        border-radius: 10px;
        border: 2px solid #fff3e6;
    }
    
    .orange-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #e55a2b;
    }
</style>

<!-- Pass User Data to JavaScript -->
<script>
    // User data from Laravel
    window.userData = {
        name: '{{ auth()->user()->name ?? "" }}',
        email: '{{ auth()->user()->email ?? "" }}',
        phone: '{{ auth()->user()->phone ?? "" }}'
    };
</script>

<!-- Checkout JavaScript -->
<script src="/js/checkout.js?v={{ time() }}"></script>

<script>
    // Initialize map when page loads
    window.addEventListener('load', function() {
        if (typeof initMap === 'function') {
            initMap();
        }
    });
</script>
</body>
</html>
