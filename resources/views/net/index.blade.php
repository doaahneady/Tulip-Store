<!doctype html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tulip Net</title>
    <link
      href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Leaflet.js for interactive map -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <style>
      :root {
        --primary: #255690;
        --secondary: #ECCE0D;
        --accent: #0aabba;
        --bg: #f4f8f9;
        --bg-section: #eef4f5;
        --white: #ffffff;
        --text-dark: #1a2e31;
        --text-mid: #4a6b70;
        --text-light: #7a9ea3;
        --shadow: 0 4px 20px rgba(37, 86, 144, 0.08);
        --shadow-hover: 0 12px 35px rgba(37, 86, 144, 0.15);
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "El Messiri", sans-serif;
      }

      body {
        background: var(--bg);
        color: var(--text-dark);
        overflow-x: hidden;
        min-height: 100vh;
      }

      /* ─── HEADER ──────────────────────────────── */
      header {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        padding: 1rem 2%;
        background: transparent; /* خلفية شفافة تماماً */
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 2000;
        transition: all 0.3s ease;
      }
      header.scrolled {
        background: rgba(37, 86, 144, 0.4); /* شفافية عالية جداً عند السكرول للحفاظ على الصورة */
        backdrop-filter: blur(50px); /* تأثير ضبابي خفيف لتحسين القراءة */
      }
      .header-left {
        display: flex;
        justify-content: flex-end;
        align-items: center;
      }
      .logo-left-img {
        height: 70px; /* تكبير بسيط للوغو */
      }
      .logo-center-img {
        height: 50px;
      }
      nav {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 28px;
      }
      nav a {
        color: var(--white);
        text-decoration: none;
        font-size: 1.1rem;
        transition: all 0.3s;
        font-weight: 700;
        position: relative;
        padding-bottom: 8px;
      }
      nav a:hover {
        color: var(--secondary);
      }
      nav a.active {
        color: var(--white);
      }
      nav a::after {
        content: "";
        position: absolute;
        bottom: 0;
        right: 0;
        width: 0;
        height: 3px;
        background: var(--white);
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      nav a.active::after {
        width: 100%;
      }

      /* ─── HERO & HEADER BACKGROUND ────────────── */
      .hero-section-wrapper {
        background: url("../assets/images/panner.png") no-repeat center center;
        background-size: cover;
        position: relative;
        width: 100%;
        height: 650px; /* ارتفاع ثابت للبانر ليظهر بشكل كامل */
        display: flex;
        flex-direction: column;
        justify-content: center; /* توسيط عمودي لمحتوى الهيرو */
      }

      .hero {
        position: relative;
        background: transparent;
        overflow: visible; /* للسماح للصورة الكبيرة بالبروز */
        display: flex;
        align-items: center;
        padding: 0 5%;
        color: var(--white);
        margin-top: -30px; /* رفع المحتوى للأعلى ليتوسط المساحة البصرية */
      }
      /* إزالة الأشكال القديمة */
      .hero::before, .hero::after {
        display: none;
      }

      .hero-container {
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        position: relative;
        z-index: 2;
      }

      .hero-image-side {
        position: relative;
        display: flex;
        justify-content: flex-start;
        align-items: center;
      }
      .satellite-img {
        width: 120%; /* تكبير إضافي للصورة */
        max-width: 800px;
        filter: drop-shadow(0 30px 60px rgba(0,0,0,0.3));
        animation: float 5s infinite ease-in-out;
        transform: scale(0.4); /* زيادة الحجم الأولي */
      }
      @keyframes float {
        0%, 100% { transform: translateY(1px) scale(1.1); }
        50% { transform: translateY(70px) scale(1.15); }
      }

      .hero-text-side {
        text-align: right;
        padding-right: 40px;
      }
      .hero-text-side h1 {
        font-size: clamp(3rem, 4vw, 5.8rem); /* تكبير الخط أكثر */
        font-weight: 900;
        margin-bottom: 15px;
        color: var(--white);
        line-height: 1.1;
        text-shadow: 0 4px 10px rgba(0,0,0,0.2);
      }
      .hero-text-side h1 span {
        color: var(--secondary);
      }
      .hero-subtitle {
        font-size: clamp(1.8rem, 1.2vw, 3rem); /* تكبير العنوان الفرعي */
        font-weight: 700;
        color: var(--white);
        margin-top: 15px;
        text-shadow: 0 2px 5px rgba(0,0,0,0.2);
      }

      .menu-toggle {
        display: none;
        background: none;
        border: none;
        color: var(--white);
        font-size: 1.5rem;
        cursor: pointer;
      }

      .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 3000;
        top: 0;
        right: 0; /* التعديل هنا: الفتح من اليمين */
        background-color: var(--primary);
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
        text-align: center;
      }

      .sidenav a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 1.2rem;
        color: var(--white);
        display: block;
        transition: 0.3s;
      }

      .sidenav a:hover {
        color: var(--secondary);
      }

      .sidenav .close-btn {
        position: absolute;
        top: 15px;
        left: 25px; /* التعديل هنا: الزر في أقصى يسار النافذة */
        font-size: 36px;
      }

      .salam-link {
        margin-top: 20px;
        font-weight: bold;
        border-top: 1px solid var(--accent);
        padding-top: 15px;
      }

      @media (max-width: 992px) {
        header {
          grid-template-columns: auto 1fr; /* عكس ترتيب العناصر */
          background: var(--bg);
          padding: 0.5rem 5%;
        }
        .header-left {
          order: 2;
          justify-content: flex-end;
        }
        .menu-toggle {
          display: block;
          order: 1;
          color: var(--primary);
        }
        nav { display: none; }
        .header-center { display: none; }

        .hero-section-wrapper {
          height: auto;
          min-height: 400px;
          padding-top: 100px;
          background-image: none !important;
          background-color: var(--bg);
        }
        .hero-container { grid-template-columns: 1fr; text-align: center; }
        .hero-image-side { display: none; } /* الصورة تختفي كما طلبت */
        .hero-text-side { padding-right: 0; order: 1;}
        
        /* تحويل الخطوط البيضاء إلى زرقاء */
        .hero-text-side h1, 
        .hero-subtitle, 
        .hero {
          color: var(--primary) !important;
        }
      }

      .hero-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
        margin-bottom: 3rem;
      }
      .stat-item {
        text-align: center;
        background: var(--white);
        border-radius: 18px;
        padding: 18px 28px;
        box-shadow: var(--shadow);
        min-width: 110px;
      }
      .stat-item .num {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        display: block;
      }
      .stat-item .lbl {
        font-size: 0.82rem;
        color: var(--text-light);
      }

      /* ─── PACKAGES SECTION ────────────────────── */
      .section {
        padding: 4rem 5%;
        background: var(--white);
      }
      .section:nth-child(even) {
        background: var(--bg-section);
      }
      .section-title {
        text-align: center;
        font-size: clamp(1.5rem, 3vw, 2.2rem);
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--primary);
      }
      .section-title span {
        color: var(--secondary);
      }
      .section-sub {
        text-align: center;
        color: var(--text-light);
        margin-bottom: 3rem;
        font-size: 1rem;
      }

      /* Packages Realistic Banner */
      .packages-banner {
        width: 100%;
        max-width: 1000px;
        margin: 2rem auto 4rem;
        padding: 4rem 2rem;
        background: linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.9) 0%,
            rgba(240, 248, 250, 0.8) 100%
          ),
          url("https://www.transparenttextures.com/patterns/white-paper.png"); /* Subtle paper texture */
        border-radius: 40px;
        box-shadow: 
          0 20px 40px rgba(13, 70, 76, 0.05),
          0 1px 3px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(13, 70, 76, 0.05);
      }

      /* Human touch - decorative soft shapes */
      .packages-banner::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(240, 89, 40, 0.05) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        z-index: 0;
      }

      .packages-banner::after {
        content: "";
        position: absolute;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(10, 171, 186, 0.05) 0%, transparent 70%);
        bottom: -80px;
        left: -80px;
        z-index: 0;
      }

      .packages-image-box {
        position: relative;
        z-index: 1;
        max-width: 280px;
        width: 100%;
        border-radius: 20px;
        background: var(--white);
        padding: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }

      .packages-image-box:hover {
        transform: translateY(-10px) rotate(1deg);
      }

      .packages-image-box img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        display: block;
      }

      /* Re-styling install costs to be below */
      .install-costs {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 600px;
        margin: 0 auto 3rem;
      }
      .cost-chip {
        background: #fff;
        border: 1px solid rgba(240, 89, 40, 0.1);
        border-radius: 20px;
        padding: 20px 35px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        flex: 1;
        min-width: 180px;
      }
      .cost-chip:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--secondary);
      }
      .cost-chip .cost-val {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--secondary);
        display: block;
        line-height: 1.2;
      }
      .cost-chip .cost-lbl {
        font-size: 0.9rem;
        color: var(--text-mid);
        font-weight: 600;
      }

      /* ─── PRICING CARDS ───────────────────────── */
      .pricing-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        max-width: 1100px;
        margin: 0 auto;
      }
      .price-card {
        background: #f0fbfc;
        border: 2px solid var(--accent);
        border-radius: 24px;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.4s;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
      }
      .price-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent), var(--secondary));
      }
      .price-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
        border-color: var(--secondary);
      }
      .price-icon {
        font-size: 2.2rem;
        margin-bottom: 15px;
        display: block;
        color: var(--primary);
      }
      .price-card h3 {
        font-size: 1.2rem;
        color: var(--primary);
        margin-bottom: 12px;
        font-weight: 700;
      }
      .price-amount {
        font-size: 2.6rem;
        font-weight: 800;
        color: var(--secondary);
        line-height: 1;
      }
      .price-amount small {
        font-size: 0.9rem;
        color: var(--text-mid);
        font-weight: 400;
      }

      /* Responsive for pricing */
      @media (max-width: 992px) {
        .pricing-grid {
          grid-template-columns: repeat(2, 1fr);
        }
      }
      @media (max-width: 500px) {
        .pricing-grid {
          grid-template-columns: 1fr;
        }
      }

      /* Installation costs */
      /* Style moved below packages-banner for better flow */

      /* ─── MAP SECTION ─────────────────────────── */
      .map-section {
        padding: 1rem 5% 4rem;
        background: var(--bg-section);
      }
      .map-wrapper {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1.5px solid rgba(13, 70, 76, 0.1);
        box-shadow: var(--shadow-hover);
      }
      #map {
        height: 520px;
        width: 100%;
        z-index: 1;
      }
      .map-legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 18px;
        justify-content: center;
      }
      .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: var(--text-mid);
        background: var(--white);
        padding: 7px 14px;
        border-radius: 30px;
        box-shadow: var(--shadow);
      }
      .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
      }
      .legend-circle {
        width: 18px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid;
        flex-shrink: 0;
        opacity: 0.8;
      }
      .map-toggle-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 500;
        background: var(--white);
        color: var(--primary);
        border: 1.5px solid rgba(13, 70, 76, 0.15);
        padding: 9px 18px;
        border-radius: 12px;
        cursor: pointer;
        font-family: "El Messiri", sans-serif;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: var(--shadow);
        font-weight: 600;
      }
      .map-toggle-btn:hover {
        background: #f0fbfc;
        border-color: var(--accent);
        color: var(--accent);
      }

      /* ─── BOOKING FORM ────────────────────────── */
      .booking-section {
        padding: 4rem 5% 5rem;
        background: var(--white);
      }
      .booking-card {
        background: var(--white);
        border: 1.5px solid rgba(13, 70, 76, 0.08);
        border-radius: 30px;
        padding: 3rem;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: var(--shadow-hover);
        text-align: center; /* توسيط النصوص والعناوين */
      }
      .booking-card h2 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
      }
      .booking-card h2 span {
        color: var(--secondary);
      }
      .booking-card > p {
        color: var(--text-mid);
        margin-bottom: 2rem;
        font-size: 0.95rem;
      }
      .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        justify-items: center; /* توسيط الحقول في الـ grid */
      }
      .form-group {
        margin-bottom: 18px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center; /* توسيط محتويات الـ group */
      }
      .form-group-full {
        grid-column: 1 / -1;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      .form-group label {
        display: block;
        font-size: 0.88rem;
        color: var(--text-mid);
        margin-bottom: 7px;
        font-weight: 600;
        text-align: center;
      }
      .form-group input,
      .form-group select,
      .form-group textarea {
        width: 100%;
        max-width: 100%; /* الحفاظ على عرض الحقول */
        padding: 13px 16px;
        background: var(--bg);
        border: 1.5px solid rgba(13, 70, 76, 0.1);
        border-radius: 12px;
        color: var(--text-dark);
        font-size: 0.95rem;
        font-family: "El Messiri", sans-serif;
        outline: none;
        transition: all 0.3s;
        text-align: center; /* توسيط النص داخل الحقول */
      }
      .form-group input::placeholder,
      .form-group textarea::placeholder {
        color: var(--text-light);
        text-align: center;
      }
      .form-group input:focus,
      .form-group select:focus,
      .form-group textarea:focus {
        border-color: var(--accent);
        background: #f0fbfc;
        box-shadow: 0 0 0 3px rgba(10, 171, 186, 0.1);
      }
      .form-group select option {
        background: #fff;
        color: #0d464c;
      }
      .btn-submit {
        width: 100%; /* العودة للعرض الكامل ليتطابق مع حقل الملاحظات */
        max-width: 100%;
        background: linear-gradient(35deg, #255690 ,  var(--secondary));
        color: #fff;
        border: none;
        padding: 12px 28px; /* نفس padding زر الواتساب */
        border-radius: 30px; /* نفس border-radius زر الواتساب */
        font-size: 1rem; /* نفس font-size زر الواتساب */
        font-weight: 700;
        font-family: "El Messiri", sans-serif;
        cursor: pointer;
        display: inline-flex; /* مثل زر الواتساب */
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(37, 86, 144, 0.2);
        margin-top: 8px;
        text-decoration: none;
      }
      .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(37, 86, 144, 0.3);
      }
      .form-note {
        text-align: center;
        color: var(--text-light);
        font-size: 0.8rem;
        margin-top: 14px;
      }

      /* ─── SUCCESS MESSAGE ─────────────────────── */
      .success-msg {
        display: none;
        background: #f0fbfc;
        border: 1.5px solid var(--accent);
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        color: var(--primary);
        font-size: 1rem;
        margin-top: 18px;
      }
      .success-msg.show {
        display: block;
      }

      /* ─── FOOTER ──────────────────────────────── */
      footer {
        background: var(--primary);
        color: var(--white);
        padding: 3rem 5% 1.5rem;
      }

      .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 2rem;
      }

      .footer-col h4 {
        color: var(--secondary);
        margin-bottom: 1.2rem;
        font-size: 1.1rem;
        text-align: right;
      }

      .footer-col ul {
        list-style: none;
        padding: 0;
      }

      .footer-col ul li {
        margin-bottom: 10px;
        font-size: 0.9rem;
        opacity: 0.9;
        transition: all 0.3s ease;
        text-align: right;
      }

      .footer-col ul li a {
        color: var(--white);
        text-decoration: none;
        transition: all 0.3s ease;
        opacity: 0.8;
      }

      .footer-col ul li a:hover {
        color: var(--secondary);
        opacity: 1;
        padding-right: 5px;
      }

      .footer-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: var(--secondary);
        color: var(--primary); /* التعديل هنا ليتناسب مع ألوان توليب نت */
        padding: 12px 35px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 5px 15px rgba(236, 206, 13, 0.3);
        border: 2px solid transparent;
      }

      .footer-btn:hover {
        background: #f1db4d;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(236, 206, 13, 0.4);
      }

      .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 2.5rem;
        flex-wrap: wrap;
        gap: 20px;
      }

      .social-links {
        display: flex;
        gap: 20px;
        align-items: center;
      }

      .social-links a {
        color: var(--white);
        font-size: 1.4rem;
        transition: color 0.3s;
      }

      .social-links a:hover {
        color: var(--secondary);
      }

      .payment-methods {
        display: flex;
        gap: 15px;
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.6);
      }

      /* ─── RESPONSIVE ──────────────────────────── */
      @media (max-width: 700px) {
        .form-row {
          grid-template-columns: 1fr;
        }
        .booking-card {
          padding: 2rem 1.5rem;
        }
        #map {
          height: 380px;
        }
        .hero-stats {
          gap: 15px;
        }
      }

      /* Leaflet custom style overrides */
      .leaflet-popup-content-wrapper {
        background: var(--white);
        color: var(--text-dark);
        border-radius: 12px;
        border: 1px solid rgba(13, 70, 76, 0.12);
        box-shadow: var(--shadow-hover);
      }
      .leaflet-popup-tip {
        background: var(--white);
      }
      .leaflet-popup-content {
        font-family: "El Messiri", sans-serif;
      }
    </style>
  </head>
  <body>

    <div class="hero-section-wrapper" id="home">
      <!-- HEADER -->
      <header id="mainHeader">
        <nav>
          <a href="#home" class="nav-link active">الرئيسية</a>
          <a href="#packages" class="nav-link">الباقات</a>
          <a href="#map-section" class="nav-link">الأبراج</a>
          <a href="#booking" class="nav-link">إحجز الآن</a>
        </nav>
        <div class="header-center">
          <img src="public\images\logo_net.jpeg" alt="Tulip Logo Center" class="logo-center-img">
        </div>
        <div class="header-left">
          <img src="public\images\tulip_net.png" alt="Tulip Net Logo" class="logo-left-img">
        </div>
        <button class="menu-toggle" aria-label="Toggle menu">
          <i class="fas fa-bars"></i>
        </button>
      </header>

      <div class="sidenav">
        <a href="javascript:void(0)" class="close-btn">&times;</a>
        <a href="#home">الرئيسية</a>
        <a href="#packages">الباقات</a>
        <a href="#map-section">الأبراج</a>
        <a href="#booking">إحجز الآن</a>
      </div>

      <!-- HERO -->
      <section class="hero">
        <div class="hero-container">
            <div class="hero-text-side">
            <h1> إشتراك <span>بالنت الفضائي</span></h1>
            <div class="hero-subtitle">جودة - سرعة - استقرار</div>
          </div>
          <div class="hero-image-side">
            <img src="public\images\satalitte.png" alt="Satellite" class="satellite-img">
          </div>
        
        </div>
      </section>
    </div>

   
    <!-- PACKAGES -->
    <section class="section" id="packages">
      <h2 class="section-title">باقاتنا <span>المتاحة</span></h2>
      <p class="section-sub">اختر الباقة المناسبة لاحتياجاتك</p>

      <!-- Pricing Cards Grid -->
      <div class="pricing-grid">
        <div class="price-card">
          <span class="price-icon"><i class="fas fa-bolt"></i></span>
          <h3>سرعة 2 ميغا</h3>
          <div class="price-amount">7$ <small>/ شهر</small></div>
        </div>
        <div class="price-card">
          <span class="price-icon"><i class="fas fa-rocket"></i></span>
          <h3>سرعة 4 ميغا</h3>
          <div class="price-amount">10$ <small>/ شهر</small></div>
        </div>
        <div class="price-card">
          <span class="price-icon"><i class="fas fa-tachometer-alt"></i></span>
          <h3>سرعة 8 ميغا</h3>
          <div class="price-amount">20$ <small>/ شهر</small></div>
        </div>
        <div class="price-card">
          <span class="price-icon"><i class="fas fa-crown"></i></span>
          <h3>سرعة 16 ميغا</h3>
          <div class="price-amount">35$ <small>/ شهر</small></div>
        </div>
      </div>
    </section>

    <!-- MAP SECTION -->
    <section class="map-section" id="map-section">
      <h2 class="section-title" style="margin-bottom: 0.5rem">
        خريطة <span>التغطية</span>
      </h2>
      <!-- <p class="section-sub">برجان يغطيان قرية عتيل بنطاق 2 كم لكل برج</p> -->
      <div class="map-wrapper">
        <button class="map-toggle-btn" id="toggleCoverage">
          <i class="fas fa-eye"></i> إخفاء/إظهار التغطية
        </button>
        <div id="map"></div>
      </div>
      <div class="map-legend">
        <div class="legend-item">
          <div class="legend-dot" style="background: #f05928"></div>
          برج التغطية
        </div>
        <div class="legend-item">
          <div
            class="legend-dot"
            style="background: radial-gradient(circle, rgba(240, 89, 40, 1) 0%, rgba(240, 89, 40, 0.4) 40%, rgba(240, 89, 40, 0.05) 100%);"
          ></div>
          نطاق التغطية (2 كم)
        </div>
        <!-- <div class="legend-item">
          <div class="legend-dot" style="background: #f0e028"></div>
          قرية عتيل
        </div> -->
      </div>
    </section>

    <!-- BOOKING FORM -->
    <section class="booking-section" id="booking">
      <div class="booking-card">
        <h2>احجز موعد <span>التركيب</span></h2>
        <p>تواصل معنا أو احجز موعداً لتركيب الإنترنت الفضائي في منزلك</p>
        <form id="bookingForm">
          <div class="form-row">
            <div class="form-group">
              <!-- <label for="fname">الاسم الكامل</label> -->
              <input type="text" id="fname" placeholder="الإسم الكامل" required />
            </div>
            <div class="form-group">
              <!-- <label for="phone"></label> -->
              <input
                type="tel"
                id="phone"
                placeholder="رقم الهاتف"
                required
              />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <!-- <label for="location"></label> -->
              <input
                type="text"
                id="location"
                placeholder="القرية / المنطقة"
                required
              />
            </div>
            <div class="form-group">
              <!-- <label for="package"></label> -->
              <select id="package" required>
                <option value="">الباقة المطلوبة</option>
                <option value="2mb">سرعة 2 ميغا - 7$</option>
                <option value="4mb">سرعة 4 ميغا - 10$</option>
                <option value="8mb">سرعة 8 ميغا - 20$</option>
                <option value="16mb">سرعة 16 ميغا - 35$</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <!-- <label for="tower"></label> -->
              <select id="tower" required>
                <option value="">البرج المطلوب (الأقرب لموقعك)</option>
                <!-- سيمتلىء برمجياً -->
              </select>
            </div>
            <div class="form-group">
              <!-- <label for="date">تاريخ الموعد المفضل</label> -->
              <input type="date" id="date" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <!-- <label for="time"></label> -->
              <select id="time">
                <option value="">الوقت المفضل</option>
                <option>صباحاً (9:00 - 12:00)</option>
                <option>ظهراً (12:00 - 15:00)</option>
                <option>عصراً (15:00 - 18:00)</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group form-group-full">
              <label for="notes">ملاحظات إضافية</label>
              <textarea
                id="notes"
                rows="3"
                placeholder="أي معلومات إضافية تساعدنا في خدمتك..."
              ></textarea>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group form-group-full">
              <button type="submit" class="btn-submit">
                <i class="fas fa-calendar-check"></i>
                تأكيد حجز الموعد
              </button>
            </div>
          </div>
          <p class="form-note">سيتم التواصل معك خلال 24 ساعة لتأكيد الموعد</p>
          <div class="success-msg" id="successMsg">
            <i
              class="fas fa-check-circle"
              style="font-size: 2rem; display: block; margin-bottom: 10px"
            ></i>
            شكراً! تم استلام طلبك بنجاح. سنتواصل معك قريباً لتأكيد الموعد.
          </div>
        </form>

        <!-- WhatsApp shortcut -->
        <div
          style="
            margin-top: 2rem;
            text-align: center;
            border-top: 1px solid rgba(13, 70, 76, 0.08);
            padding-top: 1.5rem;
          "
        >
          <p style="color: #7a9ea3; font-size: 0.9rem; margin-bottom: 12px">
            أو تواصل معنا مباشرة عبر
          </p>
          <a
            href="https://wa.me/963900000000"
            target="_blank"
            style="
              display: inline-flex;
              align-items: center;
              gap: 10px;
              background: #25d366;
              color: #fff;
              padding: 12px 28px;
              border-radius: 30px;
              text-decoration: none;
              font-weight: 700;
              font-size: 1rem;
              transition: all 0.3s;
            "
            onmouseover="this.style.transform = 'translateY(-3px)'"
            onmouseout="this.style.transform = ''"
          >
            <i class="fab fa-whatsapp" style="font-size: 1.3rem"></i>
            تواصل عبر واتساب
          </a>
        </div>
      </div>
    </section>

    <footer>
      <div class="footer-grid">
        <!-- <div class="footer-col">
          <h4>هل أنت جديد هنا؟</h4>
          <a href="signup.html" class="footer-btn">إنشاء حساب <i class="fas fa-user-plus"></i></a>
        </div> -->
        <div class="footer-col">
          <h4>الأقسام الخاصة بشركتنا</h4>
          <ul>
            <li><a href="/store">توليب ستور</a></li>
            <li><a href="/tulip-mart">توليب مارت</a></li>
            <li><a href="/tulip-net">توليب نت</a></li>
            <li><a href="/tulip-gift">توليب لتنسيق الهدايا</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>روابط سريعة الوصول</h4>
          <ul>
            <li><a href="#home">الرئيسية</a></li>
            <li><a href="#packages">خدماتنا</a></li>
            <li><a href="#map-section">الأبراج</a></li>
            <li><a href="#booking">إحجز الآن</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>الدعم و التواصل التقني</h4>
          <ul>
            <li><a href="faq.html">الأسئلة الشائعة (FAQ)</a></li>
            <li><a href="shipping.html">سياسة الشحن و التوصيل</a></li>
            <li><a href="return.html">سياسة الإرجاع و الاستبدال</a></li>
            <li><a href="warranty.html">سياسة الضمان</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="payment-methods">
          <i class="fab fa-cc-visa"></i>
          <i class="fab fa-cc-mastercard"></i>
          <i class="fas fa-hand-holding-usd"></i>
        </div>
        <div class="social-links">
          <span>تواصل معنا</span>
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </footer>

    <!-- MAP SCRIPT -->
    <script>
      // ── البيانات الأساسية للأبراج ──
      const towersData = [
        {
          id: 1,
          name: "منطقة جنوب غرب",
          pos: [32.75018700254111, 36.5726944],
          subscribers: 18, // شارفت على الامتلاء
        },
        {
          id: 2,
          name: "منطقة جنوب شرق",
          pos: [32.75482139004328, 36.58319976441808],
          subscribers: 20, // ممتلئ
        },
        {
          id: 3,
          name: "منطقة شرق شمال",
          pos: [32.76294979890881, 36.58243279325427],
          subscribers: 5, // متاح
        },
        {
          id: 4,
          name: "منطقة شمال غرب",
          pos: [32.76524682433049, 36.57552291223117],
          subscribers: 5, // متاح
        }
      ];

      // Initialize map centered on Sweida governorate
      const map = L.map("map", {
        center: [32.75764854454428, 36.57951608130383], // عتيل / السويداء
        zoom: 14,
        zoomControl: true,
      });

      // Tile layer (OpenStreetMap)
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
        maxZoom: 18,
      }).addTo(map);

      // ── Custom tower icon ──
      const towerIcon = L.divIcon({
        html: `<div style="
          background:#f05928;
          width:36px;height:36px;
          border-radius:50%;
          display:flex;align-items:center;justify-content:center;
          border:3px solid #fff;
          box-shadow:0 4px 15px rgba(240,89,40,0.6);
          font-size:16px;color:#fff;
        "><i class="fas fa-broadcast-tower"></i></div>`,
        iconSize: [36, 36],
        className: "",
      });

      // ── Village marker ──
      const villageIcon = L.divIcon({
        html: `<div style="
          background:#f0e028;
          width:30px;height:30px;
          border-radius:50%;
          display:flex;align-items:center;justify-content:center;
          border:3px solid #fff;
          box-shadow:0 4px 12px rgba(240,224,40,0.5);
          font-size:13px;color:#333;
        "><i class="fas fa-home"></i></div>`,
        iconSize: [20, 20],
        className: "",
      });

      // Village position
      const villagePos = [32.75766658973171, 36.57964482733799]; // مركز عتيل

      // Village marker
      L.marker(villagePos, { icon: villageIcon }).addTo(map)
        .bindPopup(`<div style="text-align:right;padding:5px;">
          <strong style="color:#0d464c;font-size:1rem;">قرية عتيل</strong><br/>
          <span style="color:#4a6b70;font-size:0.85rem;">محافظة السويداء - سوريا</span>
        </div>`);



      // Function to create gradient effect (Natural Spotlight with Dark Core)
      function createGradientCoverage(pos, maxRadius) {
        const group = L.layerGroup();
        const steps = 8; // تقليل عدد التدرجات كما طلب المستخدم
        for (let i = steps; i >= 1; i--) {
          const radius = (maxRadius / steps) * i;
          const opacity = 0.05 + (0.03 * (1 - i/steps)); 
          
          L.circle(pos, {
            radius: radius,
            stroke: false,
            fillColor: "#f05928",
            fillOpacity: opacity,
            interactive: false
          }).addTo(group);
        }
        return group;
      }

      // Reusable function to add a tower with its coverage
      const allCoverages = L.layerGroup().addTo(map);
      
      function addTower(tower) {
        const isFull = tower.subscribers >= 20;
        const statusText = isFull ? '<span style="color:red;font-weight:bold;">ممتلئ (20/20)</span>' : `<span style="color:#0aabba;">نشط (${tower.subscribers}/20)</span>`;
        
        // Create Marker
        const marker = L.marker(tower.pos, { icon: towerIcon }).addTo(map);
        marker.bindPopup(`<div style="text-align:right;padding:5px;">
          <strong style="color:#f05928;font-size:1rem;">برج توليب نت #${tower.id}</strong><br/>
          <span style="color:#0d464c;font-weight:600;">${tower.name}</span><br/>
          <span style="color:#4a6b70;font-size:0.85rem;">المشتركين: ${tower.subscribers} / 20</span><br/>
          <span style="font-size:0.85rem;">الحالة: ${statusText}</span>
        </div>`);

        // Create and Add Coverage
        const coverage = createGradientCoverage(tower.pos, 1500);
        allCoverages.addLayer(coverage);
        
        return { marker, coverage };
      }

      // إضافة الأبراج من البيانات التعريفية
      const towerSelect = document.getElementById("tower");
      towersData.forEach(tower => {
        addTower(tower);
        
        // إضافة الخيارات لقائمة الحجز
        const option = document.createElement("option");
        option.value = tower.id;
        const isFull = tower.subscribers >= 20;
        option.textContent = `${tower.name} (${isFull ? 'ممتلئ' : 'متاح'})`;
        if (isFull) {
          option.disabled = true;
          option.style.color = "#ccc";
        }
        towerSelect.appendChild(option);
      });

      // Toggle coverage visibility
      let coverageVisible = true;
      document
        .getElementById("toggleCoverage")
        .addEventListener("click", function () {
          if (coverageVisible) {
            allCoverages.remove();
            this.innerHTML = '<i class="fas fa-eye"></i> إظهار التغطية';
          } else {
            allCoverages.addTo(map);
            this.innerHTML = '<i class="fas fa-eye-slash"></i> إخفاء التغطية';
          }
          coverageVisible = !coverageVisible;
        });

      // Form submit
      document
        .getElementById("bookingForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();
          
          const selectedTowerId = document.getElementById("tower").value;
          const tower = towersData.find(t => t.id == selectedTowerId);
          
          if (tower && tower.subscribers >= 20) {
            alert("عذراً، هذا البرج ممتلئ حالياً. يرجى اختيار برج آخر.");
            return;
          }

          document.getElementById("successMsg").classList.add("show");
          this.reset();
          this.querySelector(".btn-submit").disabled = true;
          this.querySelector(".btn-submit").innerHTML =
            '<i class="fas fa-check"></i> تم الإرسال';
          setTimeout(() => {
            document.getElementById("successMsg").classList.remove("show");
            this.querySelector(".btn-submit").disabled = false;
            this.querySelector(".btn-submit").innerHTML =
              '<i class="fas fa-calendar-check"></i> تأكيد حجز الموعد';
          }, 5000);
        });

      // Set min date for booking to today
      const today = new Date().toISOString().split("T")[0];
      document.getElementById("date").min = today;

      // ── Navigation Effects (Sticky Header & Active Link) ──
      const header = document.getElementById('mainHeader');
      const navLinks = document.querySelectorAll('.nav-link');
      const sections = document.querySelectorAll('section, .hero-section-wrapper');

      window.addEventListener('scroll', () => {
        // Sticky Header Effect
        if (window.scrollY > 50) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }

        // Active Link on Scroll
        let current = "";
        sections.forEach((section) => {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.clientHeight;
          if (window.scrollY >= sectionTop - 150) {
            current = section.getAttribute("id");
          }
        });

        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href").includes(current)) {
            link.classList.add("active");
          }
        });
      });

      // Smooth Scroll
      navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetSection = document.querySelector(targetId);
          if (targetSection) {
            window.scrollTo({
              top: targetSection.offsetTop - 70,
              behavior: 'smooth'
            });
          }
        });
      });
    </script>
    <script>
      // JavaScript for menu toggle
      const menuToggle = document.querySelector('.menu-toggle');
      const closeBtn = document.querySelector('.close-btn');
      const sidenav = document.querySelector('.sidenav');

      menuToggle.addEventListener('click', () => {
        sidenav.style.width = '250px';
      });

      closeBtn.addEventListener('click', () => {
        sidenav.style.width = '0';
      });

      // Header scroll effect
      window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        header.classList.toggle('scrolled', window.scrollY > 50);
      });
    </script>
  </body>
</html>
