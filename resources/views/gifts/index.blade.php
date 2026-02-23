<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>هدايا توليب - Tulip Gifts</title>
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}&fix=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: #f8f9fa; min-height: 100vh; }

        .hero { padding: 1.5rem 0; background: transparent; }
        .hero-card { max-width: 1400px; margin: 0 auto; }
        .hero-card-img { width: 100%; height: auto; display: block; }

        /* Main Container */
        .gifts-container { max-width: 1300px; margin: 2rem auto 0; padding: 0 2rem 4rem; position: relative; z-index: 2; }

        /* Premium Cards Section */
        .premium-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 5rem;
        }

        /* Premium Card */
        .premium-card {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 520px;
        }
        .premium-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 80px rgba(0,0,0,0.15);
        }
        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--card-gradient);
        }
        .premium-card.box-card { --card-gradient: linear-gradient(90deg, #c9956c, #daa87e, #e8c4a8); --card-color: #c9956c; }
        .premium-card.flower-card { --card-gradient: linear-gradient(90deg, #e91e63, #f48fb1, #fce4ec); --card-color: #e91e63; }
        .premium-card.ready-card { --card-gradient: linear-gradient(90deg, #1a5a5a, #3d9a8a, #7dd3c0); --card-color: #1a5a5a; }

        .card-visual {
            height: 220px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .card-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8) 0%, transparent 60%);
        }
        .card-image {
            height: 180px;
            width: auto;
            max-height: 100%;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.2));
        }
        .premium-card:hover .card-image {
            transform: scale(1.05);
        }
        .card-emoji {
            font-size: 7rem;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.2));
        }
        .premium-card:hover .card-emoji {
            transform: scale(1.15) rotate(5deg);
        }
        .card-floating {
            position: absolute;
            font-size: 2rem;
            opacity: 0.3;
            animation: floatAround 6s ease-in-out infinite;
        }
        .card-floating:nth-child(2) { top: 20%; left: 15%; animation-delay: 0s; }
        .card-floating:nth-child(3) { top: 60%; right: 15%; animation-delay: 2s; }
        .card-floating:nth-child(4) { bottom: 20%; left: 25%; animation-delay: 4s; }
        @keyframes floatAround {
            0%, 100% { transform: translate(0, 0) rotate(0deg); opacity: 0.3; }
            50% { transform: translate(10px, -15px) rotate(15deg); opacity: 0.5; }
        }

        .card-content {
            padding: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .card-tag {
            display: inline-block;
            background: var(--card-color);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .card-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.6rem;
            color: #2c3e50;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }
        .card-desc {
            color: #7f8c8d;
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        .card-features {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .feature {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            color: #95a5a6;
        }
        .feature i { color: var(--card-color); font-size: 0.9rem; }
        .card-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 2.5rem;
            background: var(--card-gradient);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            margin-top: auto;
            align-self: center;
        }
        .card-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
        }
        .card-btn i { transition: transform 0.3s; }
        .card-btn:hover i { transform: translateX(-5px); }

        /* Section Title */
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            color: #1a3a3a;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }
        .section-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Ready Gifts Grid */
        .gifts-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        /* Gift Card */
        .gift-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            cursor: pointer;
        }
        .gift-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        .gift-image {
            height: 200px;
            background: linear-gradient(135deg, #f5f7fa, #e4e8eb);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .gift-image img {
            height: 100%;
            max-height: 180px;
            width: auto;
            object-fit: contain;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15));
        }
        .gift-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.6) 0%, transparent 50%);
        }
        .gift-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(231,76,60,0.4);
        }
        .gift-badge.new { background: linear-gradient(135deg, #9b59b6, #8e44ad); box-shadow: 0 4px 15px rgba(155,89,182,0.4); }
        .gift-badge.sale { background: linear-gradient(135deg, #27ae60, #2ecc71); box-shadow: 0 4px 15px rgba(39,174,96,0.4); }
        .gift-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        .gift-add-btn { margin-top: auto; }
        .gift-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.15rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .gift-rating {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 0.8rem;
        }
        .gift-rating i { color: #f1c40f; font-size: 0.85rem; }
        .gift-rating span { color: #95a5a6; font-size: 0.85rem; margin-right: 0.3rem; }
        .gift-price {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }
        .price-current {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a5a5a;
        }
        .price-old {
            font-size: 1rem;
            color: #bdc3c7;
            text-decoration: line-through;
        }
        .gift-add-btn {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #1a5a5a, #2d7a7a);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .gift-add-btn:hover {
            background: linear-gradient(135deg, #2d7a7a, #3d9a8a);
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(26,90,90,0.3);
        }

        /* View All */
        .view-all-container {
            text-align: center;
            margin-top: 3rem;
        }
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 3rem;
            background: #fff;
            color: #1a5a5a;
            border: 2px solid #1a5a5a;
            border-radius: 50px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .view-all-btn:hover {
            background: #1a5a5a;
            color: #fff;
            box-shadow: 0 10px 30px rgba(26,90,90,0.3);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .premium-cards { grid-template-columns: repeat(2, 1fr); }
            .gifts-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 900px) {
            .premium-cards { grid-template-columns: 1fr; max-width: 500px; margin: 0 auto 4rem; }
            .gifts-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { font-size: 1rem; }
            .hero-icon-item { font-size: 2.5rem; }
            .gifts-grid { grid-template-columns: 1fr; }
            .section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <section class="hero">
        <div class="hero-card">
            <img src="/images/banner-flower.jpg" alt="Tulip Banner" class="hero-card-img">
        </div>
    </section>

    <div class="gifts-container">
        <!-- Premium Cards -->
        <div class="premium-cards">
            <!-- Custom Box Card -->
            <div class="premium-card box-card" onclick="window.location.href='/gifts/box-arrangement'">
                <div class="card-visual">
                    <span class="card-floating">✨</span>
                    <span class="card-floating">🎀</span>
                    <span class="card-floating">💝</span>
                        <img src="/images/mistery_box.jpg" alt="Gift Box" class="card-image">
                </div>
                <div class="card-content">
                    <span class="card-tag">الأكثر طلباً</span>
                    <h3 class="card-title">تنسيق صندوق هدية</h3>
                    <p class="card-desc">صمم صندوق هدية فريد بإضافة الشوكولاتة والعطور والإكسسوارات وكل ما يحبه قلبك</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-box"></i> 4 أحجام</span>
                        <span class="feature"><i class="fas fa-gift"></i> +50 عنصر</span>
                        <span class="feature"><i class="fas fa-envelope"></i> بطاقة مجانية</span>
                    </div>
                    <button class="card-btn">
                        ابدأ التصميم
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- Custom Bouquet Card -->
            <div class="premium-card flower-card" onclick="window.location.href='/gifts/flower-bouquet'">
                <div class="card-visual">
                    <span class="card-floating">🌸</span>
                    <span class="card-floating">🌺</span>
                    <span class="card-floating">🌷</span>
                        <img src="/images/banner-flower.jpg" alt="Rose Bouquet" class="card-image">
                </div>
                <div class="card-content">
                    <span class="card-tag">ورد طازج يومياً</span>
                    <h3 class="card-title">تنسيق باقة ورد</h3>
                    <p class="card-desc">اختر من أجمل الزهور الطازجة ونسق باقتك المثالية بالألوان والتغليف الذي تفضله</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-seedling"></i> +20 نوع زهور</span>
                        <span class="feature"><i class="fas fa-palette"></i> ألوان متعددة</span>
                        <span class="feature"><i class="fas fa-truck"></i> توصيل سريع</span>
                    </div>
                    <button class="card-btn">
                        ابدأ التصميم
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- Ready Made Card -->
            <div class="premium-card ready-card" onclick="document.getElementById('readyGifts').scrollIntoView({behavior: 'smooth'})">
                <div class="card-visual">
                    <span class="card-floating">⭐</span>
                    <span class="card-floating">🏆</span>
                    <span class="card-floating">💎</span>
                        <img src="/images/gift-placeholder.jpg" alt="Ready Gifts" class="card-image">
                </div>
                <div class="card-content">
                    <span class="card-tag">جاهزة للتوصيل</span>
                    <h3 class="card-title">هدايا جاهزة</h3>
                    <p class="card-desc">مجموعة منتقاة بعناية من أفخم الهدايا المنسقة والجاهزة للتوصيل في نفس اليوم</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-check-circle"></i> منسقة باحتراف</span>
                        <span class="feature"><i class="fas fa-tag"></i> أسعار مميزة</span>
                        <span class="feature"><i class="fas fa-shield-alt"></i> ضمان الجودة</span>
                    </div>
                    <button class="card-btn">
                        تصفح الهدايا
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Ready Made Gifts Section -->
        <section id="readyGifts">
            <div class="section-header">
                <span class="section-label"><i class="fas fa-star"></i> مختارات توليب</span>
                <h2 class="section-title">هدايا جاهزة للتوصيل</h2>
                <p class="section-subtitle">اختر من مجموعتنا المنسقة بعناية واستمتع بتوصيل سريع في نفس اليوم</p>
            </div>
            
            <div class="gifts-grid" id="giftsGrid"></div>

            <div class="view-all-container">
                <button class="view-all-btn" onclick="window.location.href='/store?category=gifts'">
                    عرض جميع الهدايا
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer style="background:#0D464C; padding:1.8rem 3rem 2rem; position:relative;">
        <style>
            /* Responsive overrides (use !important to override inline styles) */
            footer { padding:1.4rem 1rem 1.6rem !important; box-sizing:border-box; }
            footer > div { max-width:1400px; margin:0 auto; padding:0 1rem; box-sizing:border-box; }
            footer > img { /* background image subtle */ width:100%; height:100%; object-fit:cover; opacity:0.03; pointer-events:none; }

            /* Grid container (first inner div) - center everything */
            footer > div > div:first-of-type {
                display:grid !important;
                grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)) !important;
                gap:2.5rem !important;
                margin-bottom:2rem !important;
                align-items:start;
                justify-items:center !important; /* center columns content */
                text-align:center !important;     /* center text inside columns */
            }

            footer h2 { margin-top:0.6rem !important; margin-bottom:0.8rem !important; font-size:1rem !important; text-align:center !important; }
            footer p { font-size:0.95rem !important; line-height:1.6 !important; text-align:center !important; }

            /* Logo & social icons */
            footer > div > div:first-of-type > div:first-of-type img { height:110px !important; margin-bottom:0.6rem !important; display:block; margin-left:auto; margin-right:auto; }
            footer > div > div:first-of-type > div:first-of-type .social-wrap { display:flex; gap:0.9rem; flex-wrap:wrap; margin-top:0.6rem; justify-content:center; }

            /* Make links inline-block to allow centered spacing and hover padding */
            footer > div > div:first-of-type a { display:inline-block; text-align:center; }

            /* Bottom row centered */
            footer > div > div:last-of-type {
                padding-top:1.4rem !important;
                border-top:1px solid rgba(255,255,255,0.1) !important;
                display:flex !important;
                justify-content:center !important;
                align-items:center !important;
                gap:1rem !important;
                flex-wrap:wrap;
                text-align:center;
            }
            footer > div > div:last-of-type p { margin:0 !important; font-size:0.9rem !important; color:rgba(255,255,255,0.55) !important; text-align:center !important; }

            footer > div > div:last-of-type .payments { display:flex; gap:1.2rem; align-items:center; flex-wrap:wrap; justify-content:center; }

            footer img.payment-icon { height:30px !important; opacity:1 !important; }

            /* Responsive breakpoints */
            @media (max-width:1200px) {
                footer > div > div:first-of-type { grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)) !important; gap:1.4rem !important; }
                footer > div > div:first-of-type > div:first-of-type img { height:95px !important; }
            }

            @media (max-width:800px) {
                footer { padding:1rem 0.8rem 1rem !important; }
                footer > div > div:first-of-type { grid-template-columns:1fr !important; gap:1rem !important; }
                footer > div > div:first-of-type > div { text-align:center !important; }
                footer > div > div:first-of-type > div:not(:first-of-type) a { display:inline-block !important; }
                /* center social icons */
                footer > div > div:first-of-type > div:first-of-type .social-wrap { justify-content:center; margin:0.6rem auto 0; }
                /* bottom row stack */
                footer > div > div:last-of-type { flex-direction:column !important; align-items:center !important; text-align:center !important; gap:0.8rem !important; }
                footer > div > div:last-of-type .payments { justify-content:center; }
            }

            @media (max-width:420px) {
                footer h2 { font-size:0.95rem !important; }
                footer p { font-size:0.9rem !important; }
                footer > div > div:first-of-type > div:first-of-type img { height:78px !important; }
                footer img.payment-icon { height:26px !important; }
            }
        </style>

        <img src="/images/footer.jpg" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.03;">
        
        <div style="max-width:1400px; margin:0 auto; position:relative;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:2.5rem; margin-bottom:2rem; justify-items:center; text-align:center;">
                <div>
                    <img src="/images/white_orange_logo.png" style="height:130px;margin-bottom:0.8rem; display:block; margin-left:auto; margin-right:auto;">
                    <p style="color:rgba(255,255,255,0.7); line-height:1.8; font-size:1rem; margin-bottom:1rem; max-width:480px; margin-left:auto; margin-right:auto;">
                        متجر فاخر للهدايا والمنتجات المميزة. نساعدك في إرسال ابتسامتك لأحبائك أينما كانوا.
                    </p>
                    <div class="social-wrap" style="display:flex; gap:0.9rem; justify-content:center; margin-top:0.6rem;">
                        <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">روابط سريعة</h2>
                    <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                        <a href="/mart" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب مارت</a>
                        <a href="/gifts" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">هدايا توليب</a>
                        <a href="/about" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">من نحن؟</a>
                        <a href="/contact" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">تواصل معنا</a>
                    </div>
                </div>
                
                <div>
                    <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">الدعم التقني</h2>
                    <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                        <a href="/faq" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">الأسئلة الشائعة</a>
                        <a href="/shipping" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الشحن</a>
                        <a href="/returns" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الإرجاع</a>
                        <a href="/privacy" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الخصوصية</a>
                    </div>
                </div>
                
                <div>
                    <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">الأقسام الخاصة</h2>
                    <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                        <a href="/gifts/box-arrangement" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">تنسيق صندوق هدية</a>
                        <a href="/gifts/flower-bouquet" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">تنسيق باقة ورد</a>
                    </div>
                </div>
            </div>
            
            <div style="padding-top:2rem; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:center; align-items:center; gap:1.2rem; flex-wrap:wrap;">
                <p style="color:rgba(255,255,255,0.5); margin:0; font-size:0.95rem;">© 2025 Tulip Store. جميع الحقوق محفوظة</p>
                <div class="payments" style="display:flex; gap:1.2rem; align-items:center; justify-content:center;">
                    <i class="fab fa-cc-visa" style="font-size:28px; color:#fff;"></i>
                    <i class="fab fa-cc-mastercard" style="font-size:28px; color:#fff;"></i>
                    <i class="fas fa-hand-holding-dollar" style="font-size:26px; color:#fff;"></i>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Sample ready-made gifts data
        const readyGifts = [
            { id: 1, name: 'صندوق هدية فاخر', image: '/images/mistery_box.jpg', price: 299, oldPrice: 350, badge: 'الأكثر مبيعاً', rating: 4.9, reviews: 128 },
            { id: 2, name: 'باقة ورد رومانسية', image: '/images/banner-flower.jpg', price: 199, oldPrice: null, badge: null, rating: 4.8, reviews: 95 },
            { id: 3, name: 'صندوق شوكولاتة فاخرة', image: '/images/gift-placeholder.jpg', price: 149, oldPrice: 180, badge: 'sale', badgeText: 'خصم 17%', rating: 4.7, reviews: 67 },
            { id: 4, name: 'طقم عطور مميز', image: '/images/gift-placeholder.jpg', price: 399, oldPrice: null, badge: 'new', badgeText: 'جديد', rating: 5.0, reviews: 23 },
            { id: 5, name: 'سلة فواكه طازجة', image: '/images/grocery.jpg', price: 179, oldPrice: 220, badge: 'sale', badgeText: 'خصم 19%', rating: 4.6, reviews: 54 },
            { id: 6, name: 'صندوق هدية للأطفال', image: '/images/gift-placeholder.jpg', price: 129, oldPrice: null, badge: null, rating: 4.8, reviews: 89 },
            { id: 7, name: 'باقة ورد مع شوكولاتة', image: '/images/banner-flower.jpg', price: 249, oldPrice: 299, badge: 'الأكثر مبيعاً', rating: 4.9, reviews: 156 },
            { id: 8, name: 'طقم إكسسوارات نسائية', image: '/images/gift-placeholder.jpg', price: 349, oldPrice: null, badge: 'new', badgeText: 'جديد', rating: 4.7, reviews: 34 },
        ];

        // Load gifts
        function loadGifts() {
            const grid = document.getElementById('giftsGrid');
            grid.innerHTML = readyGifts.map(gift => {
                const stars = '★'.repeat(Math.floor(gift.rating)) + (gift.rating % 1 >= 0.5 ? '½' : '');
                let badgeClass = '';
                let badgeText = '';
                if (gift.badge === 'الأكثر مبيعاً') { badgeClass = ''; badgeText = gift.badge; }
                else if (gift.badge === 'new') { badgeClass = 'new'; badgeText = gift.badgeText; }
                else if (gift.badge === 'sale') { badgeClass = 'sale'; badgeText = gift.badgeText; }
                
                return `
                    <div class="gift-card" onclick="window.location.href='/product/${gift.id}'">
                        <div class="gift-image">
                            ${badgeText ? `<span class="gift-badge ${badgeClass}">${badgeText}</span>` : ''}
                            <img src="${gift.image}" alt="${gift.name}">
                        </div>
                        <div class="gift-info">
                            <h3 class="gift-name">${gift.name}</h3>
                            <div class="gift-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star${gift.rating < 5 ? '-half-alt' : ''}"></i>
                                <span>${gift.rating} (${gift.reviews})</span>
                            </div>
                            <div class="gift-price">
                                <span class="price-current">${gift.price} ر.س</span>
                                ${gift.oldPrice ? `<span class="price-old">${gift.oldPrice} ر.س</span>` : ''}
                            </div>
                            <button class="gift-add-btn" onclick="event.stopPropagation(); addToCart(${gift.id})">
                                <i class="fas fa-cart-plus"></i>
                                أضف للسلة
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Add to cart
        async function addToCart(productId) {
            const btn = event.target.closest('.gift-add-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
                });
                const data = await response.json();
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #27ae60, #2ecc71)';
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount(data.cart_count);
                    }
                    if (typeof window.showToast === 'function') {
                        window.showToast('تمت إضافة المنتج للسلة');
                    }
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.style.background = '';
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error();
                }
            } catch (error) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', loadGifts);
    </script>
</body>
</html>
