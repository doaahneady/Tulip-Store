<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Tulip Store - أرسل ابتسامتك أينما كنت</title>

    <!-- fav icon -->
    <link rel="icon" type="image/png" href="/images/fav_icon.png">
    
    <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* These will now be handled by fluid-overrides.css but keeping specific colors/shadows if needed */
        .product-card {
            background: #fff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .product-card-btn-cart i {
            font-size: 1.4rem !important;
            color: #ff6b35 !important;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }
            .product-card, .product-card:hover {
                transform: none !important;
                transition: none !important;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.5rem !important;
            }
        }

        /* Intro Animation Responsive Text */
        @media (max-width: 768px) {
            #animatedText {
                font-size: 3.5rem !important;
                min-height: 4.5rem !important;
            }
            .intro-content-wrapper {
                padding: 2rem !important;
            }
        }

        @media (max-width: 480px) {
            #animatedText {
                font-size: 2.5rem !important;
                min-height: 3.5rem !important;
            }
            .intro-content-wrapper {
                padding: 1.5rem !important;
            }
            #mainLogo {
                margin-bottom: 2rem !important;
            }
            #mainLogo img {
                height: 80px !important;
            }
        }
    </style>
</head>
<body class="bg-white">
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<!-- SMOOTH INTRO ANIMATION - SHOW ONLY ONCE -->
<div id="introAnimation" style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; display:none; align-items:center; justify-content:center; overflow:hidden; background:linear-gradient(135deg, #000 0%, #1a1a1a 100%);">
    
    <!-- Girl Logo as Background - Grows Big with Blur (Slower & Smoother) -->
    <div id="girlBackground" style="position:absolute; inset:0; background-image:url('/images/logo-girl.jpg'); background-size:cover; background-position:center; filter:blur(25px); opacity:0; transform:scale(1.2);">
    </div>
    
    <!-- Animated Dark Overlay with Gradient -->
    <div id="darkOverlay" style="position:absolute; inset:0; background:radial-gradient(circle at center, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%); opacity:0; transition:opacity 2s ease;"></div>
    
    <!-- Subtle Particles Effect -->
    <div style="position:absolute; inset:0; background-image:radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size:50px 50px; opacity:0.15; animation:particleFloat 20s linear infinite;"></div>
    
    <!-- Content -->
    <div class="intro-content-wrapper" style="position:relative; z-index:2; text-align:center; max-width:900px; padding:3rem;">
        <!-- Logo - Drops from Top with Bounce -->
        <div id="mainLogo" style="margin-bottom:4rem; opacity:0; transform:translateY(-150px) scale(0.8);">
            <img src="/images/white_orange_logo.png" style="height:120px; filter:drop-shadow(0 10px 30px rgba(255,107,53,0.5));">
        </div>
        
        <!-- Text - Types Out in White -->
        <h1 id="animatedText" style="font-family:'El Messiri',sans-serif; font-size:6rem; font-weight:700; margin:0; line-height:1.2; 
        color:#fff; text-shadow:0 4px 15px rgba(0,0,0,0.5); min-height:7.2rem; letter-spacing:0.02em;">
        </h1>
    </div>
</div>

<style>
@keyframes particleFloat {
    0% { transform:translateY(0); }
    100% { transform:translateY(-50px); }
}

@keyframes gentleFloat {
    0%, 100% { 
        transform:scale(1.03) translateY(0);
    }
    50% { 
        transform:scale(1.05) translateY(-2px);
    }
}
</style>

<script>
// Show intro animation ONLY ONCE
(function() {
    const introEl = document.getElementById('introAnimation');
    const hasSeenIntro = sessionStorage.getItem('tulipIntroShown');
    
    if (introEl && !hasSeenIntro) {
        introEl.style.display = 'flex';
        
        // Fade in dark overlay
        setTimeout(() => {
            const overlay = document.getElementById('darkOverlay');
            if (overlay) overlay.style.opacity = '1';
        }, 100);
        
        // Animate girl background - shrinks smoothly with fade (no borders visible)
        setTimeout(() => {
            const girlBg = document.getElementById('girlBackground');
            girlBg.style.transition = 'all 2.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            girlBg.style.opacity = '0.4';
            girlBg.style.filter = 'blur(16px)';
            girlBg.style.transform = 'scale(1.08)';
        }, 300);
        
        // Animate main logo - drops from top with scale and bounce
        setTimeout(() => {
            const mainLogo = document.getElementById('mainLogo');
            mainLogo.style.transition = 'all 1.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
            mainLogo.style.opacity = '1';
            mainLogo.style.transform = 'translateY(0) scale(1)';
        }, 1700);
        
        // Type out text in white with glow on last part
        setTimeout(() => {
            const text = 'أرسل ابتسامتك أينما كنت';
            const textEl = document.getElementById('animatedText');
            let index = 0;
            
            function typeChar() {
                if (index < text.length) {
                    textEl.textContent += text[index];
                    
                    // Add subtle fade-in for each character
                    textEl.style.opacity = '0.95';
                    setTimeout(() => { textEl.style.opacity = '1'; }, 50);
                    
                    index++;
                    setTimeout(typeChar, 85);
                    
                    // When typing is complete, highlight "أينما كنت" with gradient and scale
                    if (index === text.length) {
                        setTimeout(() => {
                            const fullText = textEl.textContent;
                            const highlightPart = 'أينما كنت';
                            const beforeHighlight = fullText.substring(0, fullText.indexOf(highlightPart));
                            
                            textEl.innerHTML = beforeHighlight + '<span id="highlightText" style="display:inline; background:linear-gradient(135deg, #ff6b35 0%, #ff8c5a 50%, #ffa07a 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; opacity:0; transition:all 2s cubic-bezier(0.25, 0.46, 0.45, 0.94); position:relative; z-index:1;">' + highlightPart + '</span>';
                            
                            // Trigger smooth highlight animation
                            setTimeout(() => {
                                const highlightEl = document.getElementById('highlightText');
                                if (highlightEl) {
                                    highlightEl.style.opacity = '1';
                                    highlightEl.style.textShadow = '0 4px 20px rgba(255,107,53,0.3)';
                                }
                            }, 100);
                        }, 400);
                    }
                }
            }
            
            typeChar();
        }, 2600);
        
        // Smooth fade out with scale effect
        setTimeout(() => {
            const content = introEl.querySelector('div[style*="position:relative"]');
            if (content) {
                content.style.transition = 'all 1.2s cubic-bezier(0.4, 0, 0.2, 1)';
                content.style.transform = 'scale(0.95)';
                content.style.opacity = '0';
            }
            
            setTimeout(() => {
                introEl.style.transition = 'opacity 1.2s ease-out';
                introEl.style.opacity = '0';
                setTimeout(() => {
                    introEl.style.display = 'none';
                    sessionStorage.setItem('tulipIntroShown', 'true');
                }, 1200);
            }, 300);
        }, 7000);
    }
})();
</script>

<!-- BANNER SLIDER - REDUCED HEIGHT, SMALLER BUTTONS -->
<section style="position:relative; height:300px; overflow:hidden; background:white; padding:1.5rem 1rem;">
    <div style="max-width:100%; margin:0 auto; position:relative; height:100%;">
        <div id="modernSlider" style="position:relative; height:100%; display:flex; align-items:center; justify-content:center;">
            <?php
                $slidesData = is_array($slides ?? null) ? array_values($slides) : [];
            ?>
            <?php $__currentLoopData = $slidesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $slideClass = 'hidden';
                    if ($i === 0) {
                        $slideClass = 'active';
                    } elseif ($i === 1) {
                        $slideClass = 'next';
                    } elseif ($i === 2) {
                        $slideClass = 'prev';
                    }
                    $slideImage = (string) ($slide['image'] ?? '');
                    $normalizedSlideImage = str_replace('\\', '/', $slideImage);
                    $slideFile = strtolower(basename($normalizedSlideImage));
                    $slideLink = $slide['link'] ?? '#';
                    if ($slideFile === 'banner3.jpg') {
                        $slideLink = '/store';
                    } elseif ($slideFile === 'banner2.jpg') {
                        $slideLink = '/gifts';
                    } elseif ($slideFile === 'banner1.jpg') {
                        $slideLink = '/mart';
                    }
                ?>
                <div class="modern-slide <?php echo e($slideClass); ?>">
                    <a href="<?php echo e($slideLink); ?>" style="display:block; width:100%; height:100%; text-decoration:none;">
                        <img src="<?php echo e($slide['image'] ?? ''); ?>" alt="<?php echo e($slide['title'] ?? ''); ?>">
                        <div class="modern-slide-content">
                            <h2 style="font-family:'El Messiri', sans-serif; font-size:2rem; font-weight:900; margin:0 0 0.8rem 0;"><?php echo e($slide['title'] ?? ''); ?></h2>
                            <p style="font-family:'El Messiri', sans-serif; font-size:1.1rem; margin:0;"><?php echo e($slide['subtitle'] ?? ''); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div> 
        <!-- Dots -->
        <div id="modernSliderDots"
             style="position:absolute; bottom:1.25rem; left:50%; transform:translateX(-50%);
                    display:flex; gap:0.5rem;"></div>
    </div>
</section>

<style>
.modern-slide {
    position:absolute;
    transition:all 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 20px 50px rgba(0,0,0,0.25);
    background:#fff;
}

.modern-slide.active {
    width:min(90vw, 700px);
    height:280px;
    z-index:5;
    opacity:1;
    transform:translateX(0) translateY(0) scale(1) rotateY(0deg);
}

.modern-slide.next {
    width:min(80vw, 550px);
    height:240px;
    z-index:4;
    opacity:0.6;
    transform:translateX(min(45vw, 420px)) translateY(15px) scale(0.88) rotateY(-12deg);
}

.modern-slide.prev {
    width:min(80vw, 550px);
    height:240px;
    z-index:4;
    opacity:0.6;
    transform:translateX(max(-45vw, -420px)) translateY(15px) scale(0.88) rotateY(12deg);
}

@media (max-width: 768px) {
    .modern-slide.next, .modern-slide.prev {
        display: none; /* Only show active slide on mobile to avoid overlap and cut text */
    }
    .modern-slide.active {
        width: 100%;
        height: 250px;
    }
    .modern-slide-content h2 {
        font-size: 1.5rem !important;
    }
    .modern-slide-content p {
        font-size: 0.9rem !important;
    }
}

.modern-slide.hidden {
    width:450px;
    height:240px;
    z-index:1;
    opacity:0;
    transform:scale(0.7);
}

.modern-slide img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.modern-slide-content {
    position:absolute;
    bottom:0;
    left:0;
    right:0;
    padding:2rem;
    background:linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
    color:#fff;
    transition:opacity 0.3s ease;
}

.modern-slide:not(.active) .modern-slide-content {
    opacity:0;
}
</style>

<!-- CATEGORIES - SMALLER, ICON-BASED, BLUE LINE, SMALLER BUTTONS -->
<section id="categories" style="padding:2rem 1.5rem; background:#fff;">
    <div style="max-width:1400px; margin:0 auto; position:relative;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
            <h2 style="font-family:'El Messiri',sans-serif; font-size:1.6rem; font-weight:800; color:#1a1a1a; margin:0;">
                 تسوق حسب الفئة
            </h2>
            <a href="/categories" style="color:#2a7080; font-size:1rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:0.5rem; transition:all 0.3s;" onmouseover="this.style.gap='0.8rem'" onmouseout="this.style.gap='0.5rem'">
                عرض الكل <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        
        <div class="marquee-container">
            <div class="marquee-track">
                <?php
                    $categoryColors = [
                        '#ff6b35', '#2a7080', '#9b59b6', '#e74c3c', '#3498db',
                        '#f39c12', '#1abc9c', '#e91e63', '#00bcd4', '#ff5722',
                    ];
                    $categoriesData = ($categories && $categories->count() > 0) ? $categories : collect();
                ?>
                
                <?php if($categoriesData->isNotEmpty()): ?>
                    
                    <?php $__currentLoopData = $categoriesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $name = (string) ($category->name ?? '');
                            $slug = (string) ($category->slug ?? '');
                            $color = $categoryColors[$index % count($categoryColors)];
                            $icon = 'fa-box';
                            if (str_contains($name, 'ورد') || str_contains($name, 'زهور')) {
                                $icon = 'fa-seedling';
                            } elseif (str_contains($name, 'شوكولات')) {
                                $icon = 'fa-cookie-bite';
                            } elseif (str_contains($name, 'عطر')) {
                                $icon = 'fa-spray-can-sparkles';
                            } elseif (str_contains($name, 'مجوهر') || str_contains($name, 'اكسسو') || str_contains($name, 'إكسسو')) {
                                $icon = 'fa-gem';
                            } elseif (str_contains($name, 'هدايا') || str_contains($name, 'هدية')) {
                                $icon = 'fa-gift';
                            }
                        ?>
                        <div class="category-card" onclick="window.location.href='/category/<?php echo e($slug); ?>'" style="--cat-color:<?php echo e($color); ?>;">
                            <div class="category-icon">
                                <i class="fas <?php echo e($icon); ?>"></i>
                            </div>
                            <p class="category-name"><?php echo e($name); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php $__currentLoopData = $categoriesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $name = (string) ($category->name ?? '');
                            $slug = (string) ($category->slug ?? '');
                            $color = $categoryColors[$index % count($categoryColors)];
                            $icon = 'fa-box';
                            if (str_contains($name, 'ورد') || str_contains($name, 'زهور')) {
                                $icon = 'fa-seedling';
                            } elseif (str_contains($name, 'شوكولات')) {
                                $icon = 'fa-cookie-bite';
                            } elseif (str_contains($name, 'عطر')) {
                                $icon = 'fa-spray-can-sparkles';
                            } elseif (str_contains($name, 'مجوهر') || str_contains($name, 'اكسسو') || str_contains($name, 'إكسسو')) {
                                $icon = 'fa-gem';
                            } elseif (str_contains($name, 'هدايا') || str_contains($name, 'هدية')) {
                                $icon = 'fa-gift';
                            }
                        ?>
                        <div class="category-card" onclick="window.location.href='/category/<?php echo e($slug); ?>'" style="--cat-color:<?php echo e($color); ?>;">
                            <div class="category-icon">
                                <i class="fas <?php echo e($icon); ?>"></i>
                            </div>
                            <p class="category-name"><?php echo e($name); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p style="text-align:center;width:100%;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">لا توجد تصنيفات متاحة حالياً.</p>
                <?php endif; ?>
            </div>
        </div>
            
            <!-- Gradient Overlays -->
            <div style="position:absolute; left:0; top:0; bottom:0; width:120px; background:linear-gradient(to right, #fff, transparent); pointer-events:none; z-index:2;"></div>
            <div style="position:absolute; right:0; top:0; bottom:0; width:120px; background:linear-gradient(to left, #fff, transparent); pointer-events:none; z-index:2;"></div>
        </div>
    </div>
</section>

<style>
.marquee-container {
    position: relative;
    width: 100%;
    overflow: hidden;
    padding: 0.1rem 0;
}

.marquee-track {
    display: flex;
    gap: 1.2rem;
    width: max-content;
    flex-wrap: nowrap;
    animation: marquee 50s linear infinite;
}

.marquee-track:hover {
    animation-play-state: paused;
}

@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(50%); }
}

/* Ensure consistent spacing on mobile */
@media (max-width: 768px) {
    .marquee-track {
        gap: 0.3rem;
        animation-duration: 40s;
    }
}

#categoriesScroll::-webkit-scrollbar { display: none; }

.category-card {
    min-width:140px;
    background:#fff;
    border-radius:16px;
    padding:1.2rem;
    text-align:center;
    cursor:pointer;
    transition:all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow:0 4px 15px rgba(0,0,0,0.06);
    position:relative;
    overflow:hidden;
}

@media (max-width: 768px) {
    .category-card {
        min-width: calc(25vw - 1.5rem); /* Roughly 4 cards in viewport */
        padding: 0.8rem;
    }
    .category-icon {
        width: 50px !important;
        height: 50px !important;
        font-size: 1.5rem !important;
    }
    .category-name {
        font-size: 0.8rem !important;
    }
}

.category-scroll-btn {
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:45px;
    height:45px;
    background:#fff;
    border:2px solid #e0e0e0;
    border-radius:50%;
    cursor:pointer;
    font-size:1.1rem;
    color:#2a7080;
    transition:all 0.3s;
    z-index:10;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.category-scroll-left { left: -15px; }
.category-scroll-right { right: -15px; }

@media (max-width: 768px) {
    .category-scroll-btn {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
    .category-scroll-left { left: -5px; }
    .category-scroll-right { right: -5px; }
}

.category-card::after {
    content:'';
    position:absolute;
    bottom:0;
    left:0;
    right:0;
    height:4px;
    background:var(--cat-color, #2a7080);
    transform:scaleX(0);
    transition:transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.category-card:hover::after {
    transform:scaleX(1);
}

.category-card:hover {
    transform:translateY(-8px) scale(1.03);
    box-shadow:0 12px 25px rgba(0,0,0,0.15);
}

.category-icon {
    width:80px;
    height:80px;
    margin:0 auto 1rem;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
    transition:all 0.4s ease;
    font-size:2.5rem;
    color:var(--cat-color, #2a7080);
}

.category-card:hover .category-icon {
    transform:scale(1.15) rotate(8deg);
    background:#fff;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

.category-name {
    font-family:'El Messiri',sans-serif;
    font-size:1rem;
    font-weight:700;
    color:#1a1a1a;
    margin:0;
    transition:color 0.3s ease;
}

.category-card:hover .category-name {
    color:#2a7080;
}
</style>

<style>
/* Store section: exact mart-style cards (no mart-only labels: category, origin, unit) */
.store-section-cards .product-card {
    border-radius: 24px;
    border: 1px solid #e8e8e8;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
    background: #fff;
}
.store-section-cards .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
    border-color: #2a7080;
}
.store-section-cards .product-image,
.store-section-cards .product-image-wrapper {
    aspect-ratio: 1 / 1;
    width: 100%;
    height: auto;
    background: linear-gradient(135deg, #eaf7f8, #f8f9fa);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
.store-section-cards .product-image img,
.store-section-cards .product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.store-section-cards .product-body,
.store-section-cards .product-info {
    padding: 0.8rem;
    display: flex;
    flex-direction: column;
    min-height: auto;
}
.store-section-cards .product-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.2rem;
    font-family: 'El Messiri', sans-serif;
}
.store-section-cards .product-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.5rem;
    border-top: 1px solid #e8e8e8;
    margin-top: 0.5rem;
}
.store-section-cards .price-wrapper {
    display: flex;
    flex-direction: column;
}
.store-section-cards .price-current,
.store-section-cards .product-price {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f4f55;
    font-family: 'El Messiri', sans-serif;
}
.store-section-cards .price-old,
.store-section-cards .product-old-price {
    font-size: 0.75rem;
    color: #94a3b8;
    text-decoration: line-through;
}
.store-section-cards .add-cart-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    padding: 0.4rem 0.8rem;
    background: #0f4f55;
    color: #fff;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-family: 'El Messiri', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(15,79,85,0.15);
}
.store-section-cards .add-cart-btn:hover:not(:disabled) {
    background: #1a6b73;
    transform: scale(1.05);
}
.store-section-cards .product-favorite-btn {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 5;
    width: 28px;
    height: 28px;
    background: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.store-section-cards .product-favorite-btn.active,
.store-section-cards .product-favorite-btn .fas {
    color: #ef4444;
}
</style>
<!-- PERSONALIZED FOR YOU - NO CIRCLE, SIMPLE DESIGN -->
<section id="personalizedSection" class="store-section-cards" style="padding:2.5rem 1.5rem; background:#fff;">
    <div style="max-width:1400px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:2.5rem;">
            <h2 style="font-family:'El Messiri',sans-serif; font-size:2rem; font-weight:900; color:#1a1a1a; margin:0 0 0.8rem 0;">
                مختارة خصيصاً لك
            </h2>
            <p style="font-family:'El Messiri',sans-serif; font-size:1.1rem; color:#666; margin:0;">بناءً على اهتماماتك وتصفحك السابق</p>
        </div>
        <div id="personalizedProducts" class="products-grid">
            <?php
                $productsData = $products ?? collect();
                $personalized = $productsData->take(5);
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $personalized; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $stock = (int) ($product->stock_quantity ?? 0);
                    $trackInv = (bool) ($product->track_inventory ?? false);
                    $isOutOfStock = $trackInv && $stock <= 0;
                    $price = (float) ($product->discount_price ?? $product->price ?? 0);
                    $oldPrice = (float) ($product->price ?? 0);
                    $img = $product->primary_image_url ?? '';
                    $img = $img ?: '/images/banner1.jpg';
                    $imgUrl = (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) ? $img : url($img);
                ?>
                <div class="product-card" data-product-id="<?php echo e($product->id); ?>" onclick="window.location.href='/products/<?php echo e($product->id); ?>'">
                    <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, <?php echo e($product->id); ?>)">
                        <i class="far fa-heart"></i>
                    </button>
                    <div class="product-image">
                        <img src="<?php echo e($imgUrl); ?>"
                             alt="<?php echo e($product->name); ?>"
                             loading="lazy"
                             width="320"
                             height="320"
                             onerror="this.onerror=null; this.src='<?php echo e(url('/images/banner1.jpg')); ?>';">
                    </div>
                    <div class="product-body">
                        <h3 class="product-name"><?php echo e($product->name); ?></h3>
                        <div class="product-footer">
                            <div class="price-wrapper">
                                <span class="price-current"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($price)); ?></span>
                                <?php if(!empty($product->discount_price)): ?>
                                    <span class="price-old"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($oldPrice)); ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="add-cart-btn" onclick="event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this)" data-product-id="<?php echo e($product->id); ?>" <?php echo e($isOutOfStock ? 'disabled' : ''); ?> style="<?php echo e($isOutOfStock ? 'opacity: 0.5; cursor: not-allowed;' : ''); ?>">
                                <i class="fas fa-plus"></i> أضف
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="grid-column:1/-1; text-align:center; padding:2.5rem; color:#999;">لا توجد منتجات متاحة حالياً.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- TRENDING NOW - WHITE BACKGROUND -->
<section class="store-section-cards" style="padding:2.5rem 1.5rem; background:#fff;">
    <div style="max-width:1400px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:2.5rem;">
            <h2 style="font-family:'El Messiri',sans-serif; font-size:2rem; font-weight:900; color:#1a1a1a; margin:0 0 0.8rem 0;">
                الأكثر رواجاً الآن
            </h2>
            <p style="font-family:'El Messiri',sans-serif; font-size:1.1rem; color:#666; margin:0;">المنتجات الأكثر طلباً هذا الأسبوع</p>
        </div>
        <div id="trendingProducts" class="products-grid">
            <?php
                $productsData = $products ?? collect();
                $trending = $productsData->slice(5, 5);
                if ($trending->isEmpty()) {
                    $trending = $productsData->take(5);
                }
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $trending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $stock = (int) ($product->stock_quantity ?? 0);
                    $trackInv = (bool) ($product->track_inventory ?? false);
                    $isOutOfStock = $trackInv && $stock <= 0;
                    $price = (float) ($product->discount_price ?? $product->price ?? 0);
                    $oldPrice = (float) ($product->price ?? 0);
                    $img = $product->primary_image_url ?? '';
                    $img = $img ?: '/images/banner1.jpg';
                    $imgUrl = (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) ? $img : url($img);
                ?>
                <div class="product-card" data-product-id="<?php echo e($product->id); ?>" onclick="window.location.href='/products/<?php echo e($product->id); ?>'">
                    <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, <?php echo e($product->id); ?>)">
                        <i class="far fa-heart"></i>
                    </button>
                    <div class="product-image">
                        <img src="<?php echo e($imgUrl); ?>"
                             alt="<?php echo e($product->name); ?>"
                             loading="lazy"
                             width="320"
                             height="320"
                             onerror="this.onerror=null; this.src='<?php echo e(url('/images/banner1.jpg')); ?>';">
                    </div>
                    <div class="product-body">
                        <h3 class="product-name"><?php echo e($product->name); ?></h3>
                        <div class="product-footer">
                            <div class="price-wrapper">
                                <span class="price-current"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($price)); ?></span>
                                <?php if(!empty($product->discount_price)): ?>
                                    <span class="price-old"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($oldPrice)); ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="add-cart-btn" onclick="event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this)" data-product-id="<?php echo e($product->id); ?>" <?php echo e($isOutOfStock ? 'disabled' : ''); ?> style="<?php echo e($isOutOfStock ? 'opacity: 0.5; cursor: not-allowed;' : ''); ?>">
                                <i class="fas fa-plus"></i> أضف
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="grid-column:1/-1; text-align:center; padding:2.5rem; color:#999;">لا توجد منتجات متاحة حالياً.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- MERCHANT SECTION - PHOTO ONLY BACKGROUND -->
<section style="position:relative; height:230px; display:flex; align-items:center; justify-content:center; margin:2rem 1rem; border-radius:16px; overflow:hidden;">

    <!-- Background -->
    <div style="position:absolute; inset:0; background: url('/images/banner_ask.jpg') center/cover no-repeat;"></div>

    <!-- Content -->
    <div style="position:relative; text-align:center; padding:2rem; max-width:650px;">
        <h2 style="font-size:2rem; font-weight:700; color:#fff; margin:0 0 1rem 0; text-shadow:0 4px 15px rgba(0,0,0,0.6);">
            كن تاجر معنا
        </h2>

        <p style="font-size:1.1rem; color:#fff; text-shadow:0 3px 12px rgba(0,0,0,0.6); margin:0 0 1.5rem 0;">
            إبدأ بعرض منتجاتك لدينا لربح أكثر.. نظام الكتروني سهل الاستخدام.. متابعة للأرباح و المبيعات من خلال حسابك
        </p>

        <a href="/trader/login" target="_blank" style="display:inline-flex; align-items:center; gap:0.6rem; background:#ff6b35; color:#fff; padding:0.7rem 1.9rem; border-radius:999px; text-decoration:none; font-size:1rem; font-weight:700; box-shadow:0 8px 20px rgba(0,0,0,0.25); transition:all 0.3s;">
            بوابة التجار
            <i class="fas fa-store"></i>
        </a>
    </div>

</section>

<!-- FOOTER -->
<div style="position:relative; z-index:1001;">
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<script>window.isAuthenticated = <?php echo auth()->check() ? 'true' : 'false'; ?>;</script>
<script src="/js/home-final.js?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/home-new.blade.php ENDPATH**/ ?>