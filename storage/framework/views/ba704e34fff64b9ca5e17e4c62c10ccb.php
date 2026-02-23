<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Tulip Store - أرسل ابتسامتك أينما كنت</title>
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .products-grid {
            display: grid !important;
            grid-template-columns: repeat(5, 1fr) !important;
            gap: 1.5rem !important;
            margin-top: 2rem !important;
        }

        @media (max-width: 1400px) {
            .products-grid { grid-template-columns: repeat(4, 1fr) !important; }
        }

        @media (max-width: 1100px) {
            .products-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }

        @media (max-width: 768px) {
            .products-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }

        @media (max-width: 480px) {
            .products-grid { grid-template-columns: repeat(1, 1fr) !important; }
        }

        .product-card {
            background: #fff !important;
            border-radius: 15px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease !important;
            position: relative !important;
        }

        .product-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .product-card-actions {
            display: flex !important;
            justify-content: flex-start !important;
            align-items: center !important;
            padding: 0.9rem 1rem 1.1rem !important;
        }

        .product-card-btn-cart {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            width: auto !important;
            height: auto !important;
            padding: 0 !important;
        }

        .product-card-btn-cart:not([disabled]) i {
            font-size: 1.4rem !important;
            color: #ff6b35 !important;
        }

        .product-card-btn-cart[disabled] i {
            font-size: 1.35rem !important;
            color: #b91c1c !important;
        }
    </style>
</head>
<body style="margin:0; font-family:'El Messiri',sans-serif; background:#fff; overflow-x:hidden;">

<?php if(View::exists('components.navbar')): ?>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

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
    <div style="position:relative; z-index:2; text-align:center; max-width:900px; padding:3rem;">
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
<section style="position:relative; height:350px; overflow:hidden; background:white; padding:2rem 1.2rem;">
    <div style="max-width:600px; margin:0 auto; position:relative; height:100%;">
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
                ?>
                <div class="modern-slide <?php echo e($slideClass); ?>">
                    <img src="<?php echo e($slide['image'] ?? ''); ?>" alt="<?php echo e($slide['title'] ?? ''); ?>">
                    <div class="modern-slide-content">
                        <h2 style="font-family:'Changa',sans-serif; font-size:2rem; font-weight:900; margin:0 0 0.8rem 0;"><?php echo e($slide['title'] ?? ''); ?></h2>
                        <p style="font-family:'Changa',sans-serif; font-size:1.1rem; margin:0;"><?php echo e($slide['subtitle'] ?? ''); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Smaller Navigation Buttons -->
        <button onclick="changeModernSlide(-1)" style="position:absolute; left:1.5rem; top:50%; transform:translateY(-50%); width:50px; height:50px; background:rgba(255,255,255,0.95); border:none; border-radius:50%; cursor:pointer; font-size:1.3rem; color:#2a7080; transition:all 0.3s; z-index:10; box-shadow:0 4px 15px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.transform='translateY(-50%) scale(1)'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="changeModernSlide(1)" style="position:absolute; right:1.5rem; top:50%; transform:translateY(-50%); width:50px; height:50px; background:rgba(255,255,255,0.95); border:none; border-radius:50%; cursor:pointer; font-size:1.3rem; color:#2a7080; transition:all 0.3s; z-index:10; box-shadow:0 4px 15px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.transform='translateY(-50%) scale(1)'">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Dots -->
        <div id="modernSliderDots" style="position:absolute; bottom:-1.5rem; left:50%; transform:translateX(-50%); display:flex; gap:0.8rem; z-index:10;"></div>
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
    width:700px;
    height:320px;
    z-index:5;
    opacity:1;
    transform:translateX(0) translateY(0) scale(1) rotateY(0deg);
}

.modern-slide.next {
    width:550px;
    height:280px;
    z-index:4;
    opacity:0.6;
    transform:translateX(420px) translateY(15px) scale(0.88) rotateY(-12deg);
}

.modern-slide.prev {
    width:550px;
    height:280px;
    z-index:4;
    opacity:0.6;
    transform:translateX(-420px) translateY(15px) scale(0.88) rotateY(12deg);
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
        
        <!-- Smaller Left Arrow -->
        <button onclick="scrollCategoriesLeft()" style="position:absolute; left:-15px; top:50%; transform:translateY(-50%); width:45px; height:45px; background:#fff; border:2px solid #e0e0e0; border-radius:50%; cursor:pointer; font-size:1.1rem; color:#2a7080; transition:all 0.3s; z-index:10; box-shadow:0 3px 10px rgba(0,0,0,0.08);" onmouseover="this.style.transform='translateY(-50%) scale(1.1)'; this.style.borderColor='#2a7080'" onmouseout="this.style.transform='translateY(-50%) scale(1)'; this.style.borderColor='#e0e0e0'">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <!-- Smaller Right Arrow -->
        <button onclick="scrollCategoriesRight()" style="position:absolute; right:-15px; top:50%; transform:translateY(-50%); width:45px; height:45px; background:#fff; border:2px solid #e0e0e0; border-radius:50%; cursor:pointer; font-size:1.1rem; color:#2a7080; transition:all 0.3s; z-index:10; box-shadow:0 3px 10px rgba(0,0,0,0.08);" onmouseover="this.style.transform='translateY(-50%) scale(1.1)'; this.style.borderColor='#2a7080'" onmouseout="this.style.transform='translateY(-50%) scale(1)'; this.style.borderColor='#e0e0e0'">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <div style="position:relative; overflow:hidden;">
            <div id="categoriesScroll" style="display:flex; gap:1.5rem; overflow-x:auto; scroll-behavior:smooth; padding:0.8rem 0; scrollbar-width:none; -ms-overflow-style:none;">
                <div id="categoriesGrid" style="display:flex; gap:1.5rem; min-width:max-content;">
                    <?php
                        $categoryColors = [
                            '#ff6b35', '#2a7080', '#9b59b6', '#e74c3c', '#3498db',
                            '#f39c12', '#1abc9c', '#e91e63', '#00bcd4', '#ff5722',
                        ];
                        $categoriesData = $categories ?? collect();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $categoriesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p style="text-align:center;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">لا توجد تصنيفات متاحة حالياً.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Gradient Overlays -->
            <div style="position:absolute; left:0; top:0; bottom:0; width:80px; background:linear-gradient(to left, transparent, #fff); pointer-events:none; z-index:2;"></div>
            <div style="position:absolute; right:0; top:0; bottom:0; width:80px; background:linear-gradient(to right, transparent, #fff); pointer-events:none; z-index:2;"></div>
        </div>
    </div>
</section>

<style>
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

<!-- GIFTS SECTION - TULIP GIFTS -->
<section style="padding:2.5rem 1.5rem; background:linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); position:relative; overflow:hidden;">
    <!-- Decorative Background -->
    <div style="position:absolute; top:-50px; right:-50px; width:200px; height:200px; background:radial-gradient(circle, rgba(42,112,128,0.05) 0%, transparent 70%); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-30px; left:-30px; width:150px; height:150px; background:radial-gradient(circle, rgba(255,107,53,0.05) 0%, transparent 70%); border-radius:50%;"></div>
    
    <div style="max-width:1400px; margin:0 auto; position:relative;">
        <!-- Header -->
        <div style="text-align:center; margin-bottom:3rem;">
            <div style="display:inline-flex; align-items:center; gap:1rem; background:white; padding:1rem 2rem; border-radius:50px; box-shadow:0 4px 20px rgba(0,0,0,0.1); margin-bottom:1.5rem;">
                <i class="fas fa-gift" style="font-size:2rem; color:#ff6b35;"></i>
                <h2 style="margin:0; font-size:2.5rem; font-weight:700; color:#2a7080;">هدايا توليب المميزة</h2>
            </div>
            <p style="font-size:1.2rem; color:#666; max-width:600px; margin:0 auto;">اختر من مجموعتنا الرائعة من الهدايا المصممة خصيصاً لكل مناسبة</p>
        </div>

        <!-- Featured Gifts Grid -->
        <div id="featuredGifts" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:2rem; margin-bottom:2.5rem;">
            <?php
                $featuredGiftsData = $featuredGifts ?? collect();
            ?>
            <?php if($featuredGiftsData->isNotEmpty()): ?>
                <?php $__currentLoopData = $featuredGiftsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background:white; border-radius:20px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.1); transition:all 0.3s; cursor:pointer;" onclick="window.location.href='/gifts/<?php echo e($gift->id); ?>'" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'">
                        <div style="height:200px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); position:relative; overflow:hidden;">
                            <img src="<?php echo e($gift->main_image); ?>" alt="<?php echo e($gift->name); ?>" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center'; this.parentElement.innerHTML='<i class=\\'fas fa-gift fa-3x\\'></i>'">
                            <?php if($gift->is_featured): ?>
                                <div style="position:absolute; top:1rem; right:1rem; background:#ff6b35; color:white; padding:0.5rem 1rem; border-radius:20px; font-size:0.8rem; font-weight:600;"><i class="fas fa-star"></i> مميز</div>
                            <?php endif; ?>
                        </div>
                        <div style="padding:1.5rem;">
                            <div style="display:inline-block; padding:0.25rem 0.75rem; background:#f5f5f5; border-radius:15px; font-size:0.8rem; font-weight:600; margin-bottom:0.75rem;">
                                <?php echo e($gift->occasion ?: ($gift->category ?: '')); ?>

                            </div>
                            <h3 style="margin:0 0 0.5rem 0; font-size:1.2rem; font-weight:700; color:#333; line-height:1.3;"><?php echo e($gift->name); ?></h3>
                            <p style="color:#666; font-size:0.9rem; margin:0 0 1rem 0; line-height:1.5;"><?php echo e(\Illuminate\Support\Str::limit($gift->description ?? '', 80)); ?></p>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:1.3rem; font-weight:700; color:#2a7080;"><?php echo e($gift->formatted_price); ?></span>
                                <div style="display:flex; align-items:center; gap:0.3rem; color:#ffa500;">
                                    <i class="fas fa-star"></i>
                                    <span style="font-size:0.9rem;"><?php echo e($gift->rating ?: '0.0'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div style="grid-column:1/-1; text-align:center; padding:3rem; color:#999;">
                    <i class="fas fa-gift fa-3x" style="margin-bottom:1rem; opacity:0.3;"></i>
                    <p>جاري تحميل الهدايا المميزة...</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- View All Gifts Button -->
        <div style="text-align:center;">
            <a href="/gifts" style="display:inline-flex; align-items:center; gap:1rem; background:linear-gradient(135deg, #2a7080, #1a5060); color:white; padding:1.2rem 3rem; border-radius:50px; text-decoration:none; font-weight:600; font-size:1.1rem; transition:all 0.3s; box-shadow:0 4px 15px rgba(42,112,128,0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 25px rgba(42,112,128,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(42,112,128,0.3)'">
                <i class="fas fa-heart"></i>
                <span>استكشف جميع الهدايا</span>
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</section>

<?php if(($featuredGifts ?? collect())->isEmpty()): ?>
    <script>
    async function loadFeaturedGifts() {
        try {
            let response = await fetch('/api/gifts/featured');
            let data = await response.json();
            if (!data.success || (data.data || []).length === 0) {
                response = await fetch('/api/gifts?sort=featured&per_page=8');
                data = await response.json();
            }
            if (data.success && (data.data || []).length > 0) {
                const giftsGrid = document.getElementById('featuredGifts');
                giftsGrid.innerHTML = data.data.map(gift => `
                    <div style="background:white; border-radius:20px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.1); transition:all 0.3s; cursor:pointer;" onclick="window.location.href='/gifts/${gift.id}'" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'">
                        <div style="height:200px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); position:relative; overflow:hidden;">
                            <img src="${(gift.images && gift.images[0]) || gift.main_image || '/images/gift-placeholder.jpg'}" alt="${gift.name}" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'; this.parentElement.style.display='flex'; this.parentElement.style.alignItems='center'; this.parentElement.style.justifyContent='center'; this.parentElement.innerHTML='<i class=\\'fas fa-gift fa-3x\\'></i>'">
                            ${gift.is_featured ? '<div style="position:absolute; top:1rem; right:1rem; background:#ff6b35; color:white; padding:0.5rem 1rem; border-radius:20px; font-size:0.8rem; font-weight:600;"><i class="fas fa-star"></i> مميز</div>' : ''}
                        </div>
                        <div style="padding:1.5rem;">
                            <div style="display:inline-block; padding:0.25rem 0.75rem; background:#f5f5f5; border-radius:15px; font-size:0.8rem; font-weight:600; margin-bottom:0.75rem;">
                                ${(gift.occasion || gift.category || '')}
                            </div>
                            <h3 style="margin:0 0 0.5rem 0; font-size:1.2rem; font-weight:700; color:#333; line-height:1.3;">${gift.name}</h3>
                            <p style="color:#666; font-size:0.9rem; margin:0 0 1rem 0; line-height:1.5;">${(gift.description || '').substring(0, 80)}...</p>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:1.3rem; font-weight:700; color:#2a7080;">${gift.formatted_price || (gift.price + ' ر.س')}</span>
                                <div style="display:flex; align-items:center; gap:0.3rem; color:#ffa500;">
                                    <i class="fas fa-star"></i>
                                    <span style="font-size:0.9rem;">${gift.rating || '0.0'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                const giftsGrid = document.getElementById('featuredGifts');
                giftsGrid.innerHTML = `
                    <div style="grid-column:1/-1; text-align:center; padding:3rem; color:#999;">
                        <i class="fas fa-box-open fa-3x" style="margin-bottom:1rem; opacity:0.3;"></i>
                        <p>لا توجد هدايا متاحة حالياً.</p>
                    </div>
                `;
            }
        } catch (error) {
            document.getElementById('featuredGifts').innerHTML = `
                <div style="grid-column:1/-1; text-align:center; padding:3rem; color:#e74c3c;">
                    <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom:1rem;"></i>
                    <p>حدث خطأ في تحميل الهدايا</p>
                </div>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', loadFeaturedGifts);
    </script>
<?php endif; ?>

<!-- PERSONALIZED FOR YOU - NO CIRCLE, SIMPLE DESIGN -->
<section id="personalizedSection" style="padding:2.5rem 1.5rem; background:#fff;">
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
                    $stockLabel = $trackInv ? ($isOutOfStock ? 'غير متوفر' : 'متوفر: '.$stock) : 'متوفر';
                    $price = (float) ($product->discount_price ?? $product->price ?? 0);
                    $oldPrice = (float) ($product->price ?? 0);
                ?>
                <div class="product-card" data-product-id="<?php echo e($product->id); ?>" onclick="window.location.href='/products/<?php echo e($product->id); ?>'">
                    <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, <?php echo e($product->id); ?>)">
                        <i class="far fa-heart"></i>
                    </button>
                    <div style="position:absolute; top: 14px; left: 14px; z-index: 3; background: <?php echo e($isOutOfStock ? '#fee2e2' : '#dcfce7'); ?>; color: <?php echo e($isOutOfStock ? '#b91c1c' : '#166534'); ?>; padding: 6px 10px; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
                        <?php echo e($stockLabel); ?>

                    </div>
                    <div class="product-image-wrapper">
                        <img src="<?php echo e($product->image ?? 'https://via.placeholder.com/250'); ?>" alt="<?php echo e($product->name); ?>" class="product-img">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo e($product->name); ?></h3>
                        <div class="product-price-rating-wrapper">
                            <div class="product-price-wrapper">
                                <span class="product-price">$<?php echo e(number_format($price, 2)); ?></span>
                                <?php if(!empty($product->discount_price)): ?>
                                    <span class="product-old-price">$<?php echo e(number_format($oldPrice, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-rating">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="product-card-actions">
                        <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this)" data-product-id="<?php echo e($product->id); ?>" <?php echo e($isOutOfStock ? 'disabled' : ''); ?> style="<?php echo e($isOutOfStock ? 'opacity: 0.55; cursor: not-allowed;' : 'cursor: pointer;'); ?>">
                            <i class="fas <?php echo e($isOutOfStock ? 'fa-ban' : 'fa-shopping-cart'); ?>"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="grid-column:1/-1; text-align:center; padding:2.5rem; color:#999;">لا توجد منتجات متاحة حالياً.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- TRENDING NOW - WHITE BACKGROUND -->
<section style="padding:2.5rem 1.5rem; background:#fff;">
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
                    $stockLabel = $trackInv ? ($isOutOfStock ? 'غير متوفر' : 'متوفر: '.$stock) : 'متوفر';
                    $price = (float) ($product->discount_price ?? $product->price ?? 0);
                    $oldPrice = (float) ($product->price ?? 0);
                ?>
                <div class="product-card" data-product-id="<?php echo e($product->id); ?>" onclick="window.location.href='/products/<?php echo e($product->id); ?>'">
                    <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, <?php echo e($product->id); ?>)">
                        <i class="far fa-heart"></i>
                    </button>
                    <div style="position:absolute; top: 14px; left: 14px; z-index: 3; background: <?php echo e($isOutOfStock ? '#fee2e2' : '#dcfce7'); ?>; color: <?php echo e($isOutOfStock ? '#b91c1c' : '#166534'); ?>; padding: 6px 10px; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
                        <?php echo e($stockLabel); ?>

                    </div>
                    <div class="product-image-wrapper">
                        <img src="<?php echo e($product->image ?? 'https://via.placeholder.com/250'); ?>" alt="<?php echo e($product->name); ?>" class="product-img">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo e($product->name); ?></h3>
                        <div class="product-price-rating-wrapper">
                            <div class="product-price-wrapper">
                                <span class="product-price">$<?php echo e(number_format($price, 2)); ?></span>
                                <?php if(!empty($product->discount_price)): ?>
                                    <span class="product-old-price">$<?php echo e(number_format($oldPrice, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-rating">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="product-card-actions">
                        <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this)" data-product-id="<?php echo e($product->id); ?>" <?php echo e($isOutOfStock ? 'disabled' : ''); ?> style="<?php echo e($isOutOfStock ? 'opacity: 0.55; cursor: not-allowed;' : 'cursor: pointer;'); ?>">
                            <i class="fas <?php echo e($isOutOfStock ? 'fa-ban' : 'fa-shopping-cart'); ?>"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="grid-column:1/-1; text-align:center; padding:2.5rem; color:#999;">لا توجد منتجات متاحة حالياً.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- MERCHANT SECTION - PHOTO ONLY BACKGROUND -->
<section style="position:relative; height:230px; display:flex; align-items:center; justify-content:center;margin:2rem 1rem;  border-radius:16px; overflow:hidden;">
    <div style="position:absolute; inset:0; background-image:url('/images/footer.jpg'); background-size:stretch
    ; background-position:center;"></div>
    <div style="position:relative; text-align:center; padding:2rem; max-width:650px;">
        <h2 style="font-size:2rem; font-weight:700; color:#fff; margin:0 0 1rem 0; text-shadow:0 4px 15px rgba(0,0,0,0.6);">تسوق بثقة معنا</h2>
        <p style="font-size:1.1rem; color:#fff; text-shadow:0 3px 12px rgba(0,0,0,0.6); margin:0 0 1.5rem 0;">جودة عالية • توصيل سريع • خدمة عملاء متميزة</p>
        <a href="/trader/login" style="display:inline-flex; align-items:center; gap:0.6rem; background:#ff6b35; color:#fff; padding:0.7rem 1.9rem; border-radius:999px; text-decoration:none; font-size:1rem; font-weight:700; box-shadow:0 8px 20px rgba(0,0,0,0.25); transition:all 0.3s;">
            بوابة التجار
            <i class="fas fa-store"></i>
        </a>
    </div>
</section>

<!-- FOOTER -->
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
                    <a href="/store" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">المتجر</a>
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
                    <a href="/mart" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب مارت</a>
                    <a href="/gifts" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب للتنسيق العطايا</a>
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

<script>window.isAuthenticated = <?php echo auth()->check() ? 'true' : 'false'; ?>;</script>
<script src="/js/home-final.js?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH D:\Tulip-Store\resources\views/home-new.blade.php ENDPATH**/ ?>