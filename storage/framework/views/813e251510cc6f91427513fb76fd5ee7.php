<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قريباً - Tulip Store</title>
    <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700;800&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'El Messiri', sans-serif;
            position: relative;
            height: 100vh;
        }

        /* Blurred background with enhanced effect */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .background iframe {
            width: 100%;
            height: 100%;
            border: none;
            filter: blur(12px) brightness(0.7);
            -webkit-filter: blur(12px) brightness(0.7);
            transform: scale(1.15);
            pointer-events: none;
        }

        /* Gradient overlay for depth */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at center, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.65) 100%);
            z-index: 2;
        }

        /* Animated particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
            opacity: 0.15;
        }

        .particle {
            position: absolute;
            background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, transparent 70%);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }

        /* Content container */
        .content {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
            gap: 30px;
        }

        /* Main content wrapper */
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 35px;
            animation: fadeInUp 1.2s ease 0.3s both;
        }

        /* Image container with glow effect */
        .image-container {
            position: relative;
            animation: floatImage 3s ease-in-out infinite;
        }

        .coming-soon-image {
            max-width: 600px;
            max-height: 65vh;
            width: auto;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));
            transition: transform 0.3s ease;
        }

        .image-container:hover .coming-soon-image {
            transform: scale(1.05);
        }

        @keyframes floatImage {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        /* Button with enhanced design */
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .shop-button {
            position: relative;
            background: linear-gradient(135deg, #ff6f35 0%, #ff8c5a 50%, #ffa07a 100%);
            color: white;
            padding: 18px 55px;
            font-size: 1.4rem;
            font-weight: 700;
            border: none;
            border-radius: 60px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 15px 35px rgba(255, 111, 53, 0.5), 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-family: 'El Messiri', sans-serif;
            white-space: nowrap;
            overflow: hidden;
            animation: buttonGlow 2s ease-in-out infinite;
        }

        .shop-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .shop-button:hover::before {
            left: 100%;
        }

        .shop-button:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 45px rgba(255, 111, 53, 0.7), 0 10px 25px rgba(0,0,0,0.4);
        }

        .shop-button:active {
            transform: translateY(-2px) scale(1.02);
        }

        .shop-button i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .shop-button:hover i {
            transform: translateX(-5px);
        }

        @keyframes buttonGlow {
            0%, 100% {
                box-shadow: 0 15px 35px rgba(255, 111, 53, 0.5), 0 5px 15px rgba(0,0,0,0.3);
            }
            50% {
                box-shadow: 0 15px 45px rgba(255, 111, 53, 0.7), 0 5px 20px rgba(0,0,0,0.4), 0 0 30px rgba(255, 111, 53, 0.4);
            }
        }

        /* Subtitle text */
        .subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            font-weight: 500;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
            animation: fadeIn 1s ease 0.8s both;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                gap: 25px;
            }
            
            .coming-soon-image {
                max-width: 420px;
                max-height: 58vh;
            }
            
            .shop-button {
                padding: 16px 45px;
                font-size: 1.2rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                gap: 20px;
            }
            
            .coming-soon-image {
                max-width: 340px;
                max-height: 52vh;
            }
            
            .shop-button {
                padding: 14px 35px;
                font-size: 1rem;
                gap: 8px;
            }
            
            .subtitle {
                font-size: 0.9rem;
            }
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Blurred background -->
    <div class="background">
        <iframe src="<?php echo e(route('home.old')); ?>" scrolling="no"></iframe>
    </div>
    
    <!-- Gradient overlay -->
    <div class="overlay"></div>

    <!-- Animated particles -->
    <div class="particles" id="particles"></div>

    <!-- Content -->
    <div class="content">
        <!-- Main content -->
        <div class="main-content">
            <!-- Image without glow -->
            <div class="image-container">
                <img src="<?php echo e(asset('images/coming soon.png')); ?>" alt="Coming Soon" class="coming-soon-image">
            </div>
            
            <!-- Button with icon -->
            <div class="button-container">
                <a href="/mart" class="shop-button">
                    <span>تسوق معنا من توليب مارت</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
                <p class="subtitle">تجربة تسوق فريدة بانتظارك</p>
            </div>
        </div>
    </div>

    <!-- Font Awesome for icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        const particleCount = 20;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            const size = Math.random() * 4 + 2;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particle.style.animationDelay = Math.random() * 5 + 's';
            
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/pages/coming-soon.blade.php ENDPATH**/ ?>