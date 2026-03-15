<!doctype html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tulip</title>
    <link
      href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <style>
      :root {
        --primary-color: #0d464c;
        --secondary-color: #f05928;
        --bg-light: #f9f9f9;
        --text-dark: #333;
        --white: #ffffff;
        --transition-speed: 0.6s;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "El Messiri", sans-serif;
      }

      body {
        background-color: var(--white);
        color: var(--text-dark);
        overflow-x: hidden;
      }

      /* Animations */
      .reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all var(--transition-speed) ease-out;
      }

      .reveal.active {
        opacity: 1;
        transform: translateY(0);
      }

      /* Header */
      header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 5%;
        background: var(--white);
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      }

      .logo img {
        height: 40px;
      }

      .menu-toggle {
        display: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--primary-color);
      }

      nav ul {
        display: flex;
        list-style: none;
        gap: 20px;
      }

      nav ul li a {
        text-decoration: none;
        color: var(--primary-color);
        font-weight: 600;
        transition: color 0.3s;
        font-size: 0.95rem;
      }

      nav ul li a:hover {
        color: var(--secondary-color);
      }

      nav ul li a.active {
        color: var(--secondary-color);
        border-bottom: 2px solid var(--secondary-color);
      }

      /* Hero Section */
      .hero-container {
        padding: 15px;
      }

      .hero {
        position: relative;
        height: 330px;
        width: 100%;
        background: url("../assets/images/banner.jpg") no-repeat center
          center/cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 20px;
        color: var(--white);
        overflow: hidden;
        text-align: center;
      }

      .hero-content {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding-bottom: 40px;
      }

      .btn-shop {
        background: var(--secondary-color);
        color: var(--white);
        padding: 7px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition:
          transform 0.3s,
          background 0.3s,
          box-shadow 0.3s;
        box-shadow: 0 4px 15px rgba(240, 89, 40, 0.3);
      }

      .btn-shop:hover {
        transform: translateY(-3px);
        background: #d4481d;
        box-shadow: 0 6px 20px rgba(240, 89, 40, 0.4);
      }

      /* Branches Section */
      .section-title {
        text-align: right;
        padding: 3rem 5% 1rem;
        color: var(--secondary-color);
        font-size: clamp(1.4rem, 4vw, 2rem);
        display: flex;
        align-items: center;
        gap: 15px;
      }
      .ads-section .section-title {
        color: var(--primary-color);
      }
      .section-title::after {
        content: "";
        height: 2px;
        background: var(--secondary-color);
        flex-grow: 0;
        width: 100px;
      }

      .branches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        padding: 60px 20px 100px;
      }

      .branch-card {
        background: #f0f4f5;
        padding: 20px 15px;
        border-radius: 20px;
        text-align: center;
        transition:
          transform 0.3s,
          box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
      }

      /* Zig-zag pattern for cards */
      @media (min-width: 768px) {
        .branch-card:nth-child(even) {
          transform: translateY(40px);
        }

        .branch-card:nth-child(even):hover {
          transform: translateY(30px);
        }
      }

      .branch-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
      }

      .branch-card img {
        width: 100px;
        height: 100px;
        border-radius: 100px;
        object-fit: contain;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);

        /* margin-bottom: 15px; */
      }

      .branch-card h3 {
        color: var(--primary-color);
        margin-bottom: 10px;
      }

      .branch-card p {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
      }

      .btn-branch {
        background: var(--secondary-color);
        color: var(--white);
        padding: 5px 20px;
        border-radius: 15px;
        text-decoration: none;
        font-size: 0.9rem;
        margin-top: auto;
      }

      /* Safe Journey Section */
      .safe-journey {
        position: relative;
        min-height: 780px;
        background: url("../assets/images/banner2.png") no-repeat center
          center/cover;
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 5%;
        margin-top: 3rem;
        overflow: hidden;
      }

      .safe-journey::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          to left,
          rgba(13, 70, 76, 0.85),
          rgba(13, 70, 76, 0.4)
        );
        z-index: 1;
      }

      .safe-journey-content {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .safe-info {
        width: 100%;
      }

      .safe-info h2 {
        font-size: clamp(2rem, 5vw, 3.5rem);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        font-weight: 700;
      }

      .safe-info h2::before,
      .safe-info h2::after {
        content: "";
        height: 3px;
        background: var(--secondary-color);
        width: 60px;
        display: inline-block;
      }

      .safe-info p {
        margin: 0 auto 3rem;
        line-height: 1.8;
        opacity: 0.95;
        font-size: 1.2rem;
        max-width: 800px;
      }

      .safe-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 3.5rem;
        width: 100%;
      }

      .safe-card-item {
        background: rgba(255, 255, 255, 0.15);
        height: 180px;
        border-radius: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 25px;
        font-size: 1.1rem;
        font-weight: 600;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      }

      .safe-card-item:hover {
        transform: translateY(-15px) scale(1.02);
        background: rgba(255, 255, 255, 0.25);
        border-color: var(--secondary-color);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      }

      .safe-card-item::before {
        content: "\f058";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 2rem;
        margin-bottom: 15px;
        color: var(--secondary-color);
      }

      .safe-actions {
        display: flex;
        gap: 25px;
        justify-content: center;
        flex-wrap: wrap;
      }

      .btn-outline {
        border: 2px solid var(--white);
        color: var(--white);
        padding: 12px 30px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
      }

      .btn-outline:hover {
        background: var(--white);
        color: var(--primary-color);
      }

      /* Ads Section */
      .ads-section {
        padding: 4rem 5%;
      }

      .section-header-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
      }

      .section-header-wrapper .section-title {
        padding: 0;
      }

      .view-all-link {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.3s;
      }

      .view-all-link:hover {
        transform: translateX(-5px);
      }

      .ads-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
      }

      .ad-card {
        border: 1px solid #eee;
        border-radius: 25px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: #fafafa;
        transition:
          box-shadow 0.3s,
          transform 0.3s;
      }

      .ad-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
      }

      .ad-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
      }

      .ad-card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex-grow: 1;
      }

      .ad-card h3 {
        color: var(--primary-color);
        font-size: 1.1rem;
        margin-bottom: 5px;
      }

      .ad-card p {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
      }

      .btn-more {
        color: var(--secondary-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        margin-top: auto;
      }

      /* Contact Section Modernized and Clearer */
      .contact-section {
        margin: 4rem 5%;
        border-radius: 30px;
        display: flex;
        overflow: hidden;
        min-height: 550px;
        background: var(--white);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        position: relative;
      }

      .contact-info-side {
        flex: 1;
        background: var(--primary-color);
        color: var(--white);
        padding: 4rem 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.5s ease;
      }

      .contact-info-side:hover {
        background: #0a3a3f;
      }

      /* Animated Background Elements */
      .contact-info-side::before,
      .contact-info-side::after {
        content: "";
        position: absolute;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        transition: all 0.8s ease;
        z-index: 0;
      }

      .contact-info-side::before {
        width: 300px;
        height: 300px;
        top: -100px;
        left: -100px;
      }

      .contact-info-side::after {
        width: 200px;
        height: 200px;
        bottom: -50px;
        right: -50px;
      }

      .contact-info-side:hover::before {
        transform: scale(1.2) translate(20px, 20px);
        background: rgba(240, 89, 40, 0.1);
      }

      .contact-info-side:hover::after {
        transform: scale(1.5) translate(-30px, -30px);
        background: rgba(240, 89, 40, 0.1);
      }

      .contact-info-side h2 {
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
      }

      .contact-info-side:hover h2 {
        transform: translateY(-5px);
      }

      .contact-details {
        list-style: none;
        margin-top: 2rem;
        position: relative;
        z-index: 1;
      }

      .contact-detail-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        font-size: 1.05rem;
        transition: transform 0.3s ease;
      }

      .contact-info-side:hover .contact-detail-item {
        transform: translateX(-10px);
      }

      .contact-detail-item i {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary-color);
        font-size: 1.2rem;
        transition: all 0.3s ease;
      }

      .contact-detail-item:hover i {
        background: var(--secondary-color);
        color: var(--white);
        transform: rotate(15deg) scale(1.1);
      }

      .contact-form-side {
        flex: 1.4;
        padding: 4rem;
        background: #fdfdfd;
        transition: background 0.3s ease;
      }

      .contact-form-side:hover {
        background: #ffffff;
      }

      .form-title {
        color: var(--primary-color);
        margin-bottom: 2rem;
        font-size: 1.5rem;
        font-weight: 600;
        position: relative;
        display: inline-block;
      }

      .form-title::after {
        content: "";
        position: absolute;
        bottom: -5px;
        right: 0;
        width: 40px;
        height: 3px;
        background: var(--secondary-color);
        transition: width 0.3s ease;
      }

      .contact-form-side:hover .form-title::after {
        width: 100%;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-size: 0.9rem;
      }

      .form-group input,
      .form-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #edf2f7;
        border-radius: 12px;
        background: var(--white);
        font-size: 1rem;
        transition: all 0.3s ease;
        outline: none;
        color: var(--text-dark);
      }

      .form-group input:focus,
      .form-group textarea:focus {
        border-color: var(--secondary-color);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(240, 89, 40, 0.1);
      }

      .btn-submit-modern {
        background: var(--secondary-color);
        color: var(--white);
        border: none;
        padding: 15px 45px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 1.05rem;
        transition: all 0.3s;
        width: 100%;
        box-shadow: 0 10px 20px rgba(240, 89, 40, 0.2);
      }

      .btn-submit-modern:hover {
        background: #d4481d;
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(240, 89, 40, 0.3);
      }

      @media (max-width: 992px) {
        .contact-section {
          flex-direction: column;
        }
        .contact-info-side {
          padding: 3rem 2rem;
        }
        .contact-form-side {
          padding: 3rem 2rem;
        }
      }

      /* Footer */
      footer {
        background: var(--primary-color);
        color: var(--white);
        padding: 3rem 5% 1.5rem; /* Reduced padding */
      }

      .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 2rem;
      }

      .footer-col h4 {
        color: var(--secondary-color);
        margin-bottom: 1.2rem;
        font-size: 1.1rem;
      }

      .footer-col ul {
        list-style: none;
      }

      .footer-col ul li {
        margin-bottom: 10px;
        font-size: 0.9rem;
        opacity: 0.9;
        transition: all 0.3s ease;
      }

      .footer-col ul li a {
        color: var(--white);
        text-decoration: none;
        transition: all 0.3s ease;
        opacity: 0.8;
      }

      .footer-col ul li a:hover {
        color: var(--secondary-color);
        opacity: 1;
        padding-right: 5px;
      }

      /* Footer Signup Button Modernized */
      .footer-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: var(--secondary-color);
        color: var(--white);
        padding: 12px 35px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 5px 15px rgba(240, 89, 40, 0.3);
        border: 2px solid transparent;
      }

      .footer-btn:hover {
        background: #d4481d;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(240, 89, 40, 0.4);
        color: var(--white);
      }

      .footer-btn i {
        font-size: 0.9rem;
        transition: transform 0.3s ease;
      }

      .footer-btn:hover i {
        transform: rotate(-15deg);
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
        color: var(--secondary-color);
      }

      @media (max-width: 992px) {
        nav ul {
          display: none;
          position: absolute;
          top: 100%;
          left: 0;
          width: 100%;
          background: var(--white);
          flex-direction: column;
          padding: 20px;
          gap: 15px;
          box-shadow: 0 10px 10px rgba(0, 0, 0, 0.05);
          text-align: center;
        }

        nav ul.active {
          display: flex;
        }

        .menu-toggle {
          display: block;
        }

        .safe-journey-content {
          flex-direction: column;
        }

        .safe-info h2 {
          justify-content: center;
        }
      }

      @media (max-width: 576px) {
        .hero {
          height: 300px;
        }

        .hero h1 {
          font-size: 1.8rem;
        }

        .safe-cards {
          grid-template-columns: 1fr 1fr;
        }

        .safe-actions {
          flex-direction: column;
          align-items: stretch;
        }

        .safe-actions a {
          text-align: center;
          justify-content: center;
        }

        .footer-bottom {
          flex-direction: column;
          text-align: center;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <div class="menu-toggle" id="mobile-menu">
        <i class="fas fa-bars"></i>
      </div>
      <nav>
        <ul id="nav-list">
          <li><a href="#home" class="active">الرئيسية</a></li>
          <li><a href="#branches">خدماتنا</a></li>
          <li><a href="#create-store">أنشئ متجرك</a></li>
          <li><a href="#ads">الإعلانات</a></li>
          <li><a href="#contact">تواصل معنا</a></li>
        </ul>
      </nav>
      <div class="logo">
        <img src="public\images\logo.png" alt="Tulip Logo" />
      </div>
    </header>

    <div class="hero-container" id="home">
      <section class="hero reveal"></section>
    </div>

    <div class="hero-content">
      <a href="#" class="btn-shop"
        >إذهب للتسوق <i class="fas fa-arrow-left"></i
      ></a>
    </div>

    <section id="branches">
      <h2 class="section-title reveal">أفرع خاصة بتوليب</h2>
      <div class="branches-grid">
        <div class="branch-card reveal">
          <img src="public\images\tulip_store.jpg" alt="Tulip Store" />
          <h3>Tulip Store</h3>
          <p>
            وهو المتجر المتكامل وضعها السوق بين يديك كل ما تحتاجه هنا . و يمكنك
            فتح محلك الخاص ضمن المتجر
          </p>
          <a href="#" class="btn-branch">تسوّق الآن </a>
        </div>

        <div class="branch-card reveal">
          <img src="../assets/images/tulip_mart.jpg" alt="Tulip Mart" />
          <h3>Tulip Mart</h3>
          <p>
            كل ما يحتاجه منزلك في مكان واحد بالإضافة لوجود نسخة اسعار محدثة بشكل
            دائم
          </p>
          <a href="#" class="btn-branch"> إكتشف قائمتنا</a>
        </div>

        <div class="branch-card reveal">
          <img src="../assets/images/tulip_net.jpeg" alt="Tulip Net" />
          <h3>Tulip Net</h3>
          <p>
            متخصص في تركيب النت الفضائي بأسعار منافسة و خدمة ممتازة يمكنك رؤية
            المناطق التي يمكن تغطيتها و حجز موعد
          </p>
          <a href="/bin/tulip_net.html" class="btn-branch"
            >المزيد من التفاصيل</a
          >
        </div>

        <div class="branch-card reveal">
          <img src="../assets/images/tulip_gift.jpg" alt="Tulip Gift" />
          <h3>Tulip Gift</h3>
          <p>
            مكان مخصص لتنسيق ختلف الهدايا بكافة المناسبات بالاضافة لوجود هدايا
            جاهزة يمكنك الاختيار منها
          </p>
          <a href="#" class="btn-branch">إصنع هديتك</a>
        </div>
      </div>
    </section>

    <section class="safe-journey reveal" id="create-store">
      <div class="safe-journey-content">
        <div class="safe-info">
          <h2>رحلتك بأمان مع توليب</h2>
          <p>مكان واحد لتجربة تسوق تحبها و فرصة تجارة تستحقها</p>
          <p>
            إنضم لعائلة توليب و إبدأ طريقك بالتجارة الالكترونية و ارفع نسبة
            الارباح معنا
          </p>

          <div class="safe-cards">
            <div class="safe-card-item">
              أنشئ حسابك و إنضم لعائلة توليب ستور
            </div>
            <div class="safe-card-item">تسوق و إدفع بأمان</div>
            <div class="safe-card-item">إفتح متجرك الخاص معنا</div>
            <div class="safe-card-item">توليب في خدمتكم</div>
          </div>
          <div class="safe-actions">
            <a href="#" class="btn-shop"
              >إذهب للتسوق <i class="fas fa-arrow-left"></i
            ></a>
            <a href="#" class="btn-outline"
              >أنشئ حسابك <i class="fas fa-user-plus"></i
            ></a>
          </div>
        </div>
      </div>
    </section>

    <section class="ads-section" id="ads">
      <div class="section-header-wrapper reveal">
        <h2 class="section-title">أحدث الإعلانات</h2>
        <a href="#" class="view-all-link"
          >عرض جميع الإعلانات <i class="fas fa-arrow-left"></i
        ></a>
      </div>
      <div class="ads-grid">
        <div class="ad-card reveal">
          <img
            src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=400&q=80"
            alt="إعلان توظيف"
          />
          <div class="ad-card-content">
            <h3>إعلان توظيف</h3>
            <p>
              نحن نبحث عن أشخاص طموحين للانضمام لفريقنا، بحاجة لموظف جديد ذو
              خبرة.
            </p>
            <a href="#" class="btn-more"
              >عرض المزيد <i class="fas fa-arrow-left"></i
            ></a>
          </div>
        </div>
        <div class="ad-card reveal">
          <img
            src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=400&q=80"
            alt="فتح فرع جديد"
          />
          <div class="ad-card-content">
            <h3>فتح فرع جديد بتوليب</h3>
            <p>
              يسرنا إعلان افتتاح فرعنا الجديد بتوليب لتقديم أفضل الخدمات لكم.
            </p>
            <a href="#" class="btn-more"
              >عرض المزيد <i class="fas fa-arrow-left"></i
            ></a>
          </div>
        </div>
        <div class="ad-card reveal">
          <img
            src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&w=400&q=80"
            alt="عروض جديدة"
          />
          <div class="ad-card-content">
            <h3>وجود عروض جديدة</h3>
            <p>
              استمتعوا بأحدث العروض والخصومات المميزة المتوفرة الآن في كافة
              أقسامنا.
            </p>
            <a href="#" class="btn-more"
              >عرض المزيد <i class="fas fa-arrow-left"></i
            ></a>
          </div>
        </div>
      </div>
    </section>

    <section class="contact-section reveal" id="contact">
      <div class="contact-info-side">
        <div>
          <h2>تواصل معنا</h2>
          <p>نحن هنا لمساعدتك والإجابة على استفساراتك على مدار الساعة</p>
        </div>
        <ul class="contact-details">
          <li class="contact-detail-item">
            <i class="fas fa-phone-alt"></i>
            <span>+963 968 355 553</span>
          </li>
          <li class="contact-detail-item">
            <i class="fas fa-envelope"></i>
            <span>info@tulip-os.com</span>
          </li>
        
        </ul>
        <div class="social-links" style="margin-top: 2rem">
          <a href="#" style="color: white; font-size: 1.5rem; margin-left: 15px"
            ><i class="fab fa-facebook"></i
          ></a>
          <a href="#" style="color: white; font-size: 1.5rem; margin-left: 15px"
            ><i class="fab fa-instagram"></i
          ></a>
          <a href="#" style="color: white; font-size: 1.5rem"
            ><i class="fab fa-whatsapp"></i
          ></a>
        </div>
      </div>
      <div class="contact-form-side">
        <h3 class="form-title">أرسل لنا رسالة</h3>
        <form action="#">
          <div class="form-group">
            <label for="name">الاسم الكامل</label>
            <input type="text" id="name" placeholder="أدخل اسمك هنا" required />
          </div>
          <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input
              type="email"
              id="email"
              placeholder="example@mail.com"
              required
            />
          </div>
          <div class="form-group">
            <label for="message">رسالتك</label>
            <textarea
              id="message"
              rows="4"
              placeholder="اكتب استفسارك أو رسالتك هنا..."
              required
            ></textarea>
          </div>
          <button type="submit" class="btn-submit-modern">
            إرسال الرسالة الآن
          </button>
        </form>
      </div>
    </section>

    <footer>
      <div class="footer-grid">
        <div class="footer-col">
          <h4>هل أنت جديد هنا؟</h4>
          <a href="signup.html" class="footer-btn"
            >إنشاء حساب <i class="fas fa-user-plus"></i
          ></a>
        </div>
        <div class="footer-col">
          <h4>الأقسام الخاصة بشركتنا</h4>
          <ul>
            <li><a href="/">توليب ستور</a></li>
            <li><a href="/tulip-mart">توليب مارت</a></li>
            <li><a href="/tulip-net">توليب نت</a></li>
            <li><a href="/tulip-gift">توليب لتنسيق الهدايا</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>روابط سريعة الوصول</h4>
          <ul>
            <li><a href="#home">الرئيسية</a></li>
            <li><a href="#branches">خدماتنا</a></li>
            <li><a href="#create-store">أنشئ متجرك</a></li>
            <li><a href="#ads">الإعلانات</a></li>
            <li><a href="#contact">تواصل معنا</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>الدعم و التواصل التقني</h4>
          <ul>
            <li><a href="/faq">الأسئلة الشائعة (FAQ)</a></li>
            <li><a href="/shipping">سياسة الشحن و التوصيل</a></li>
            <li><a href="/return">سياسة الإرجاع و الاستبدال</a></li>
            <li><a href="/warranty">سياسة الضمان</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="payment-methods">
          <i class="fab fa-cc-visa"></i>
          <i class="fab fa-cc-mastercard"></i>

          <i class="fas fa-hand-holding-usd"></i>

          <!-- <i class="fas fa-cash"></i> -->
        </div>
        <div class="social-links">
          <span>تواصل معنا</span>
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </footer>

    <script>
      // Intersection Observer for reveal animations
      const observerOptions = {
        threshold: 0.15,
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("active");
          }
        });
      }, observerOptions);

      document
        .querySelectorAll(".reveal")
        .forEach((el) => observer.observe(el));

      // Mobile Menu Toggle logic
      const mobileMenu = document.getElementById("mobile-menu");
      const navList = document.getElementById("nav-list");
      const navLinks = document.querySelectorAll("#nav-list a");
      const sections = document.querySelectorAll(
        "section[id], div[id], h2[id]",
      );

      if (mobileMenu && navList) {
        mobileMenu.addEventListener("click", () => {
          navList.classList.toggle("active");
          const icon = mobileMenu.querySelector("i");
          if (navList.classList.contains("active")) {
            icon.classList.replace("fa-bars", "fa-times");
          } else {
            icon.classList.replace("fa-times", "fa-bars");
          }
        });

        // Close menu when clicking a link and handle smooth scroll
        navLinks.forEach((link) => {
          link.addEventListener("click", (e) => {
            e.preventDefault();
            const targetId = link.getAttribute("href");
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
              // Smooth scroll to section
              window.scrollTo({
                top: targetSection.offsetTop - 80, // Offset for sticky header
                behavior: "smooth",
              });

              // Close mobile menu
              navList.classList.remove("active");
              mobileMenu
                .querySelector("i")
                .classList.replace("fa-times", "fa-bars");
            }
          });
        });
      }

      // ScrollSpy Logic
      window.addEventListener("scroll", () => {
        let current = "";
        const scrollPos = window.scrollY + 120; // Increased offset for better detection with sticky header

        // Special case for top of page
        if (window.scrollY < 100) {
          current = "home";
        } else {
          sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (
              scrollPos >= sectionTop &&
              scrollPos < sectionTop + sectionHeight
            ) {
              current = section.getAttribute("id");
            }
          });
        }

        navLinks.forEach((link) => {
          link.classList.remove("active");
          const href = link.getAttribute("href");
          if (href === `#${current}` && current !== "") {
            link.classList.add("active");
          }
        });
      });
    </script>
  </body>
</html>
