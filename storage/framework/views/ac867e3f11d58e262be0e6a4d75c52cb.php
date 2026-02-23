<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($product->name); ?> - Tulip Store</title>
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: #fafafa; }
        
        .page-wrapper { min-height: 100vh; padding-bottom: 3rem; }
        
        /* Breadcrumb */
        .breadcrumb-section {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem 0;
        }
        .breadcrumb {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s;
        }
        .breadcrumb a:hover { color: #ea580c; }
        .breadcrumb span { color: #333; font-weight: 500; }
        .breadcrumb i { color: #ccc; font-size: 0.7rem; }

        /* Main Product Section */
        .product-section {
            max-width: 1300px;
            margin: 2rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        /* Gallery */
        .gallery {
            position: sticky;
            top: 2rem;
        }
        .main-image-container {
            position: relative;
            background: linear-gradient(145deg, #fff 0%, #f5f5f5 100%);
            border-radius: 30px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 50px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .main-image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ea580c, #f97316, #fb923c);
        }
        .main-image {
            width: 100%;
            height: 450px;
            object-fit: contain;
            transition: transform 0.5s ease;
            border-radius: 20px;
        }
        .main-image-container:hover .main-image {
            transform: scale(1.03);
        }
        .image-actions {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        .img-action-btn {
            width: 48px;
            height: 48px;
            background: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .img-action-btn i { font-size: 1.2rem; color: #666; transition: all 0.3s; }
        .img-action-btn:hover { transform: scale(1.1); }
        .img-action-btn:hover i { color: #ea580c; }
        .img-action-btn.fav-btn.active i { color: #ef4444; }
        
        .discount-tag {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: 0 4px 20px rgba(239,68,68,0.4);
        }

        .thumbnails {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .thumb {
            width: 90px;
            height: 90px;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            background: #fff;
            padding: 0.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .thumb:hover { border-color: #fed7aa; transform: translateY(-3px); }
        .thumb.active { border-color: #ea580c; }
        .thumb img { width: 100%; height: 100%; object-fit: contain; }

        /* Product Info */
        .product-info {
            padding-top: 1rem;
        }
        .product-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .category-badge {
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            color: #ea580c;
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .sku {
            color: #999;
            font-size: 0.85rem;
        }
        
        .product-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.4rem;
            color: #1a1a2e;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 1.2rem;
        }

        .rating-reviews {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .stars { display: flex; gap: 0.15rem; }
        .stars i { color: #fbbf24; font-size: 1rem; }
        .stars i.empty { color: #e5e7eb; }
        .rating-num { font-weight: 700; color: #333; }
        .reviews-count { color: #888; font-size: 0.9rem; }
        .sold-count {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #16a34a;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .sold-count i { font-size: 0.8rem; }

        /* Price */
        .price-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .price-section::before {
            content: '💰';
            position: absolute;
            top: 50%;
            left: 2rem;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.15;
        }
        .price-row {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .current-price {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: #b45309;
        }
        .currency { font-size: 1.4rem; margin-right: 0.3rem; }
        .old-price {
            font-size: 1.3rem;
            color: #9ca3af;
            text-decoration: line-through;
        }
        .save-amount {
            background: #dc2626;
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Options */
        .option-group {
            margin-bottom: 1.8rem;
        }
        .option-label {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .option-label span {
            color: #ea580c;
            font-weight: 400;
            font-size: 0.95rem;
        }
        
        .size-options {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .size-btn {
            min-width: 55px;
            height: 50px;
            padding: 0 1.2rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            font-size: 1rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all 0.3s;
        }
        .size-btn:hover { border-color: #ea580c; color: #ea580c; }
        .size-btn.active {
            background: #ea580c;
            border-color: #ea580c;
            color: #fff;
        }

        .color-options {
            display: flex;
            gap: 1rem;
        }
        .color-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            transition: all 0.3s;
            position: relative;
        }
        .color-btn:hover { transform: scale(1.1); }
        .color-btn.active::after {
            content: '✓';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }
        .color-btn.light.active::after { color: #333; text-shadow: none; }

        /* Quantity & Cart */
        .cart-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .qty-control {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 14px;
            overflow: hidden;
        }
        .qty-btn {
            width: 50px;
            height: 55px;
            border: none;
            background: transparent;
            font-size: 1.4rem;
            color: #555;
            cursor: pointer;
            transition: all 0.2s;
        }
        .qty-btn:hover { background: #eee; color: #ea580c; }
        .qty-input {
            width: 60px;
            height: 55px;
            border: none;
            background: transparent;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .add-cart-btn {
            flex: 1;
            height: 55px;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            box-shadow: 0 8px 30px rgba(234,88,12,0.3);
        }
        .add-cart-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(234,88,12,0.4);
        }
        .add-cart-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        .secondary-btns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .sec-btn {
            height: 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }
        .sec-btn:hover { border-color: #ea580c; color: #ea580c; }
        .sec-btn.primary {
            background: #0D464C;
            border-color: #0D464C;
            color: #fff;
        }
        .sec-btn.primary:hover { background: #1a5a5a; }

        /* Features */
        .features-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 16px;
        }
        .feature-box {
            text-align: center;
            padding: 1rem 0.5rem;
        }
        .feature-box i {
            font-size: 1.8rem;
            color: #ea580c;
            margin-bottom: 0.6rem;
        }
        .feature-box p {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
        }

        /* Description Section */
        .description-section {
            max-width: 1300px;
            margin: 3rem auto;
            padding: 0 2rem;
        }
        .desc-card {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 10px 50px rgba(0,0,0,0.05);
        }
        .desc-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.5rem;
            color: #1a1a2e;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .desc-title i { color: #ea580c; }
        .desc-content {
            color: #555;
            line-height: 2;
            font-size: 1.05rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .product-section {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .gallery { position: static; }
            .main-image { height: 350px; }
        }
        @media (max-width: 600px) {
            .product-section { padding: 0 1rem; }
            .product-title { font-size: 1.8rem; }
            .current-price { font-size: 2.2rem; }
            .features-strip { grid-template-columns: repeat(2, 1fr); }
            .thumbnails { flex-wrap: wrap; }
            .thumb { width: 70px; height: 70px; }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="page-wrapper">
        <div class="breadcrumb-section">
            <div class="breadcrumb">
                <a href="/"><i class="fas fa-home"></i> الرئيسية</a>
                <i class="fas fa-chevron-left"></i>
                <a href="/store">المتجر</a>
                <?php if($product->category): ?>
                    <i class="fas fa-chevron-left"></i>
                    <a href="/store?category=<?php echo e($product->category->slug); ?>"><?php echo e($product->category->name); ?></a>
                <?php endif; ?>
                <i class="fas fa-chevron-left"></i>
                <span><?php echo e(Str::limit($product->name, 30)); ?></span>
            </div>
        </div>

        <div class="product-section">
            <!-- Gallery -->
            <div class="gallery">
                <div class="main-image-container">
                    <?php if($product->discount_price): ?>
                        <?php $discount = round((($product->price - $product->discount_price) / $product->price) * 100); ?>
                        <div class="discount-tag">خصم <?php echo e($discount); ?>%</div>
                    <?php endif; ?>
                    <div class="image-actions">
                        <button class="img-action-btn fav-btn" id="favBtn" onclick="toggleFavorite()">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="img-action-btn" onclick="shareProduct()">
                            <i class="fas fa-share-alt"></i>
                        </button>
                        <button class="img-action-btn" onclick="zoomImage()">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                    <img id="mainImage" src="<?php echo e($product->image ?? '/images/placeholder.png'); ?>" alt="<?php echo e($product->name); ?>" class="main-image">
                </div>
                <?php
                    $galleryImages = [];
                    if (! empty($product->image)) {
                        $galleryImages[] = $product->image;
                    }
                    if (is_array($product->images ?? null)) {
                        $galleryImages = array_merge($galleryImages, $product->images);
                    }
                    $galleryImages = array_values(array_unique(array_filter($galleryImages)));
                    if (empty($galleryImages)) {
                        $galleryImages = ['/images/placeholder.png'];
                    }
                ?>
                <div class="thumbnails">
                    <?php $__currentLoopData = array_slice($galleryImages, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $imgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="thumb <?php echo e($idx === 0 ? 'active' : ''); ?>" onclick="changeImage(this, '<?php echo e($imgUrl); ?>')">
                            <img src="<?php echo e($imgUrl); ?>" alt="صورة <?php echo e($idx + 1); ?>">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-meta">
                    <?php if($product->category): ?>
                        <span class="category-badge"><?php echo e($product->category->name); ?></span>
                    <?php endif; ?>
                    <span class="sku">SKU: TLP-<?php echo e($product->id); ?></span>
                </div>

                <h1 class="product-title"><?php echo e($product->name); ?></h1>

                <div class="rating-reviews">
                    <div class="rating">
                        <div class="stars">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star <?php echo e($i < floor($avgRating) ? '' : 'empty'); ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-num"><?php echo e(number_format($avgRating, 1)); ?></span>
                    </div>
                    <span class="reviews-count">(<?php echo e(number_format($reviewsCount)); ?> تقييم)</span>
                    <span class="sold-count"><i class="fas fa-fire"></i> <?php echo e($unitsSold >= 500 ? '500+' : number_format($unitsSold)); ?> مبيعات</span>
                </div>

                <div class="price-section">
                    <div class="price-row">
                        <span class="current-price">
                            <span class="currency">ر.س</span><?php echo e(number_format($product->discount_price ?? $product->price, 0)); ?>

                        </span>
                        <?php if($product->discount_price): ?>
                            <span class="old-price"><?php echo e(number_format($product->price, 0)); ?> ر.س</span>
                            <span class="save-amount">وفر <?php echo e(number_format($product->price - $product->discount_price, 0)); ?> ر.س</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="option-group">
                    <div class="option-label">المقاس: <span id="selectedSize">M</span></div>
                    <div class="size-options">
                        <button class="size-btn" onclick="selectSize(this, 'XS')">XS</button>
                        <button class="size-btn" onclick="selectSize(this, 'S')">S</button>
                        <button class="size-btn active" onclick="selectSize(this, 'M')">M</button>
                        <button class="size-btn" onclick="selectSize(this, 'L')">L</button>
                        <button class="size-btn" onclick="selectSize(this, 'XL')">XL</button>
                        <button class="size-btn" onclick="selectSize(this, 'XXL')">XXL</button>
                    </div>
                </div>

                <div class="option-group">
                    <div class="option-label">اللون:</div>
                    <div class="color-options">
                        <button class="color-btn active" style="background:#1a1a2e" onclick="selectColor(this)"></button>
                        <button class="color-btn" style="background:#ea580c" onclick="selectColor(this)"></button>
                        <button class="color-btn" style="background:#0D464C" onclick="selectColor(this)"></button>
                        <button class="color-btn light" style="background:#f5f5f5" onclick="selectColor(this)"></button>
                        <button class="color-btn" style="background:#dc2626" onclick="selectColor(this)"></button>
                    </div>
                </div>

                <div class="cart-section">
                    <div class="qty-control">
                        <button class="qty-btn" onclick="changeQty(-1)">−</button>
                        <input type="text" id="quantity" value="1" class="qty-input" readonly>
                        <button class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                    <button id="addCartBtn" class="add-cart-btn" onclick="addToCart()">
                        <i class="fas fa-shopping-cart"></i>
                        أضف للسلة
                    </button>
                </div>

                <div class="secondary-btns">
                    <button class="sec-btn primary" onclick="buyNow()">
                        <i class="fas fa-bolt"></i>
                        اشتري الآن
                    </button>
                    <button class="sec-btn" onclick="addToWishlist()">
                        <i class="far fa-heart"></i>
                        أضف للمفضلة
                    </button>
                </div>

                <div class="features-strip">
                    <div class="feature-box">
                        <i class="fas fa-truck-fast"></i>
                        <p>توصيل سريع<br>خلال 2-3 أيام</p>
                    </div>
                    <div class="feature-box">
                        <i class="fas fa-shield-check"></i>
                        <p>ضمان الجودة<br>100%</p>
                    </div>
                    <div class="feature-box">
                        <i class="fas fa-rotate-left"></i>
                        <p>إرجاع مجاني<br>خلال 14 يوم</p>
                    </div>
                    <div class="feature-box">
                        <i class="fas fa-headset"></i>
                        <p>دعم فني<br>24/7</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if($product->description): ?>
        <div class="description-section">
            <div class="desc-card">
                <h2 class="desc-title"><i class="fas fa-file-alt"></i> وصف المنتج</h2>
                <div class="desc-content"><?php echo e($product->description); ?></div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(($relatedProducts ?? collect())->count() > 0): ?>
        <div class="description-section">
            <div class="desc-card">
                <h2 class="desc-title"><i class="fas fa-layer-group"></i> منتجات مشابهة</h2>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                    <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('/products/'.$rp->id)); ?>" style="text-decoration:none; color:inherit; background:#fff; border:1px solid #eee; border-radius:14px; overflow:hidden; display:block;">
                            <div style="height:160px; background:#f5f5f5; display:flex; align-items:center; justify-content:center;">
                                <img src="<?php echo e($rp->image ?? '/images/placeholder.png'); ?>" alt="<?php echo e($rp->name); ?>" style="width:100%; height:100%; object-fit:cover;">
                            </div>
                            <div style="padding:0.9rem;">
                                <div style="font-family:'El Messiri',sans-serif; font-weight:700; font-size:0.95rem; margin-bottom:0.4rem; line-height:1.4;">
                                    <?php echo e($rp->name); ?>

                                </div>
                                <div style="font-weight:800; color:#ea580c;">
                                    <?php echo e(number_format($rp->discount_price ?? $rp->price, 0)); ?> ر.س
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <footer style="background:#0D464C; padding:1.8rem 2rem; margin-top:2rem;">
        <div style="max-width:1300px; margin:0 auto; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1.5rem;">
            <div style="display:flex; align-items:center; gap:1rem;">
                <img src="/images/white_orange_logo.png" style="height:60px;">
                <p style="color:rgba(255,255,255,0.7); font-size:0.9rem;">© 2025 Tulip Store</p>
            </div>
            <div style="display:flex; gap:2rem;">
                <a href="/store" style="color:rgba(255,255,255,0.7); text-decoration:none; font-size:0.9rem;">المتجر</a>
                <a href="/gifts" style="color:rgba(255,255,255,0.7); text-decoration:none; font-size:0.9rem;">الهدايا</a>
                <a href="/mart" style="color:rgba(255,255,255,0.7); text-decoration:none; font-size:0.9rem;">مارت</a>
                <a href="/contact" style="color:rgba(255,255,255,0.7); text-decoration:none; font-size:0.9rem;">تواصل معنا</a>
            </div>
        </div>
    </footer>

    <script>
        const productId = <?php echo e($product->id); ?>;
        const productData = {
            id: <?php echo e($product->id); ?>,
            name: "<?php echo e(addslashes($product->name)); ?>",
            price: <?php echo e($product->discount_price ?? $product->price); ?>,
            image: "<?php echo e($product->image ?? '/images/placeholder.png'); ?>"
        };
        const isAuthenticated = <?php echo auth()->check() ? 'true' : 'false'; ?>;

        document.addEventListener('DOMContentLoaded', checkFavoriteStatus);

        function checkFavoriteStatus() {
            const btn = document.getElementById('favBtn');
            if (isAuthenticated) {
                fetch('/api/wishlist')
                    .then(r => r.json())
                    .then(d => {
                        const exists = Array.isArray(d.items) && d.items.some(p => p.id === productId);
                        if (exists) {
                            btn.classList.add('active');
                            btn.querySelector('i').classList.replace('far', 'fas');
                        }
                    })
                    .catch(() => {});
            } else {
                const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                if (favorites.some(p => p.id === productId)) {
                    btn.classList.add('active');
                    btn.querySelector('i').classList.replace('far', 'fas');
                }
            }
        }

        function toggleFavorite() {
            const btn = document.getElementById('favBtn');
            const icon = btn.querySelector('i');
            if (isAuthenticated) {
                fetch('/api/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.action === 'added') {
                        btn.classList.add('active');
                        icon.classList.replace('far', 'fas');
                    } else {
                        btn.classList.remove('active');
                        icon.classList.replace('fas', 'far');
                    }
                })
                .catch(() => {});
            } else {
                let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                if (favorites.some(p => p.id === productId)) {
                    favorites = favorites.filter(p => p.id !== productId);
                    btn.classList.remove('active');
                    icon.classList.replace('fas', 'far');
                } else {
                    favorites.push(productData);
                    btn.classList.add('active');
                    icon.classList.replace('far', 'fas');
                }
                localStorage.setItem('favorites', JSON.stringify(favorites));
            }
        }

        function addToWishlist() { toggleFavorite(); }

        function changeImage(thumb, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        function selectSize(btn, size) {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('selectedSize').textContent = size;
        }

        function selectColor(btn) {
            document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        function changeQty(delta) {
            const input = document.getElementById('quantity');
            let val = Math.max(1, Math.min(99, parseInt(input.value) + delta));
            input.value = val;
        }

        async function addToCart() {
            const btn = document.getElementById('addCartBtn');
            const originalHTML = btn.innerHTML;
            const quantity = parseInt(document.getElementById('quantity').value);
            
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
                    body: JSON.stringify({ product_id: productId, quantity })
                });
                const data = await response.json();
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #16a34a, #22c55e)';
                    if (window.updateCartCount) window.updateCartCount(data.cart_count || 0);
                    setTimeout(() => { btn.innerHTML = originalHTML; btn.style.background = ''; btn.disabled = false; }, 2000);
                } else throw new Error();
            } catch (e) {
                btn.innerHTML = '<i class="fas fa-times"></i> فشل';
                btn.style.background = 'linear-gradient(135deg, #dc2626, #ef4444)';
                setTimeout(() => { btn.innerHTML = originalHTML; btn.style.background = ''; btn.disabled = false; }, 2000);
            }
        }

        async function buyNow() {
            await addToCart();
            setTimeout(() => window.location.href = '/checkout', 500);
        }

        function shareProduct() {
            if (navigator.share) {
                navigator.share({ title: productData.name, url: window.location.href });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('تم نسخ الرابط!');
            }
        }

        function zoomImage() {
            const img = document.getElementById('mainImage');
            if (img.requestFullscreen) img.requestFullscreen();
            else if (img.webkitRequestFullscreen) img.webkitRequestFullscreen();
        }
    </script>
</body>
</html>
<?php /**PATH D:\Tulip-Store\resources\views/products/show.blade.php ENDPATH**/ ?>