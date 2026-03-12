<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>من نحن - متجر توليب</title>
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'El Messiri',sans-serif; background: #f8f9fa; min-height: 100vh; }
        
        .hero-section {
            background: linear-gradient(135deg, #0D464C 0%, #1a6b75 50%, #2d8a8a 100%);
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
        }
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 3.5rem;
            color: #fff;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            line-height: 2;
        }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; }
        
        .section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.2rem;
            color: #0D464C;
            text-align: center;
            margin-bottom: 3rem;
            font-weight: 700;
        }
        
        .about-content {
            background: #fff;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 3rem;
        }
        .about-text {
            font-size: 1.15rem;
            color: #555;
            line-height: 2.2;
            text-align: center;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }
        .feature-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0D464C, #1a6b75);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #fff;
        }
        .feature-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem;
            color: #0D464C;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .feature-desc {
            color: #666;
            line-height: 1.8;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #0D464C, #1a6b75);
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 4rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        .stat-item {
            color: #fff;
        }
        .stat-number {
            font-family: 'El Messiri', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
<?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">من نحن</h1>
        <p class="hero-subtitle">متجر توليب - وجهتك المثالية للهدايا الفاخرة والمنتجات المميزة في المملكة العربية السعودية</p>
    </div>
</section>

<div class="container">
    <div class="about-content">
        <p class="about-text">
            تأسس متجر توليب بهدف تقديم تجربة تسوق استثنائية لعملائنا الكرام. نحن نؤمن بأن كل هدية تحمل معها قصة ومشاعر، ولذلك نحرص على اختيار أجود المنتجات وتقديمها بأفضل طريقة ممكنة. من الهدايا الفاخرة إلى المنتجات اليومية، نسعى دائماً لتلبية احتياجاتكم وتجاوز توقعاتكم.
        </p>
    </div>

    <h2 class="section-title">لماذا تختار توليب؟</h2>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-gem"></i>
            </div>
            <h3 class="feature-title">جودة عالية</h3>
            <p class="feature-desc">نختار منتجاتنا بعناية فائقة لضمان أعلى معايير الجودة والتميز</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-truck"></i>
            </div>
            <h3 class="feature-title">توصيل سريع</h3>
            <p class="feature-desc">خدمة توصيل سريعة وموثوقة لجميع مناطق المملكة</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h3 class="feature-title">دعم متواصل</h3>
            <p class="feature-desc">فريق خدمة عملاء متميز جاهز لمساعدتك على مدار الساعة</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-gift"></i>
            </div>
            <h3 class="feature-title">تغليف مميز</h3>
            <p class="feature-desc">تغليف أنيق وفاخر يجعل هديتك أكثر تميزاً وجمالاً</p>
        </div>
    </div>

    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">+10,000</div>
                <div class="stat-label">عميل سعيد</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">+5,000</div>
                <div class="stat-label">منتج متنوع</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">+50</div>
                <div class="stat-label">مدينة نخدمها</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">دعم متواصل</div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
@include('components.footer')
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/pages/about.blade.php ENDPATH**/ ?>