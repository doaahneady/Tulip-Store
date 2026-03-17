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
        body { font-family: 'El Messiri',sans-serif; background: #fafafa; }
        
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
            height: 350px;
            object-fit: contain;
            transition: transform 0.5s ease;
            border-radius: 20px;
            position: relative;
            z-index: 1;
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
            z-index: 5;
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
            background: transparent;
            border-radius: 0;
            padding: 0.5rem 0;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: visible;
        }
        .price-section::before {
            display: none;
        }
        .price-row {
            display: flex;
            align-items: baseline;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .current-price {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #ea580c;
        }
        .currency { font-size: 1rem; margin-right: 0.2rem; }
        .old-price {
            font-size: 1.1rem;
            color: #9ca3af;
            text-decoration: line-through;
        }
        .save-amount {
            background: #dc2626;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* Options */
        .option-group {
            margin-bottom: 1.5rem;
        }
        .option-label {
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .option-label span {
            color: #ea580c;
            font-weight: 400;
            font-size: 0.9rem;
        }
        
        .size-options {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
        }
        .size-btn {
            min-width: 45px;
            height: 40px;
            padding: 0 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            font-size: 0.9rem;
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
            gap: 0.8rem;
        }
        .color-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
            flex-direction: column;
            gap: 1.2rem;
            margin-bottom: 1.5rem;
        }
        .qty-control {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 12px;
            overflow: hidden;
            width: fit-content;
        }
        .qty-btn {
            width: 40px;
            height: 45px;
            border: none;
            background: transparent;
            font-size: 1.2rem;
            color: #555;
            cursor: pointer;
            transition: all 0.2s;
        }
        .qty-btn:hover { background: #eee; color: #ea580c; }
        .qty-input {
            width: 50px;
            height: 45px;
            border: none;
            background: transparent;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .add-cart-btn {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            box-shadow: 0 6px 20px rgba(234,88,12,0.25);
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
            .main-image { height: 300px; }
        }
        @media (max-width: 600px) {
            .product-section { padding: 0 1rem; }
            .product-title { font-size: 1.5rem; }
            .current-price { font-size: 1.6rem; }
            .features-strip { grid-template-columns: repeat(2, 1fr); }
            .thumbnails { flex-wrap: wrap; }
            .thumb { width: 60px; height: 60px; }
            .main-image-container { padding: 1rem; border-radius: 20px; }
            .main-image { height: 250px; }
        }

        /* Products Grid for Related Products */
        .products-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)) !important;
            gap: 1.5rem !important;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.5rem !important;
            }
        }

        .product-image-wrapper {
            aspect-ratio: 1 / 1 !important;
            width: 100% !important;
            overflow: hidden !important;
            background: #f5f5f5;
        }

        .product-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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
                    <img id="mainImage" src="<?php echo e($product->primary_image_url); ?>" srcset="<?php echo e($product->primary_image_srcset); ?>" sizes="(max-width: 768px) 100vw, 50vw" alt="<?php echo e($product->name); ?>" class="main-image" loading="eager" width="900" height="450" data-image-context="product-main">
                </div>
                <?php
                    $galleryImages = [];
                    $galleryImages[] = $product->primary_image_url;
                    if (is_array($product->images ?? null)) {
                        $galleryImages = array_merge($galleryImages, $product->images);
                    }
                    $galleryImages = array_values(array_unique(array_filter($galleryImages)));
                    if (empty($galleryImages)) {
                        $galleryImages = ['/images/gift-placeholder.svg'];
                    }
                    $galleryImages = array_map(function ($p) {
                        $path = trim((string) $p);
                        if ($path === '') {
                            return '/images/gift-placeholder.svg';
                        }
                        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                            return $path;
                        }
                        if (str_starts_with($path, '/storage/')) {
                            return $path;
                        }
                        if (str_starts_with($path, '/images/')) {
                            return $path;
                        }
                        if (str_starts_with($path, '/')) {
                            return $path;
                        }
                        if (str_starts_with($path, 'images/')) {
                            return '/'.$path;
                        }
                        if (file_exists(public_path($path))) {
                            return '/'.$path;
                        }
                        $clean = preg_replace('#^storage/#', '', $path) ?? $path;
                        if ($clean !== '') {
                            return '/storage/'.ltrim($clean, '/');
                        }
                        return '/images/gift-placeholder.svg';
                    }, $galleryImages);
                ?>
                <div class="thumbnails">
                    <?php $__currentLoopData = array_slice($galleryImages, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $imgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="thumb <?php echo e($idx === 0 ? 'active' : ''); ?>" onclick="changeImage(this, '<?php echo e($imgUrl); ?>')">
                            <img src="<?php echo e($imgUrl); ?>" alt="صورة <?php echo e($idx + 1); ?>" loading="lazy" width="70" height="70" data-image-context="product-thumb-<?php echo e($idx + 1); ?>">
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

                <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee;">
                    <span class="sold-count"><i class="fas fa-fire"></i> <?php echo e($unitsSold >= 500 ? '500+' : number_format($unitsSold)); ?> مبيعات</span>
                </div>

                <div class="price-section">
                    <div class="price-row">
                        <span class="current-price" id="currentPrice" data-usd="<?php echo e($product->discount_price ?? $product->price); ?>"></span>
                        <?php if($product->discount_price): ?>
                            <span class="old-price" id="oldPrice" data-usd="<?php echo e($product->price); ?>"></span>
                            <span class="save-amount" id="saveAmount" data-usd="<?php echo e($product->price - $product->discount_price); ?>"></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                    $customAttributes = collect();
                    if (isset($product->attributes) && $product->attributes instanceof \Illuminate\Support\Collection) {
                        $customAttributes = $product->attributes->where('is_custom', true)->sortBy('sort_order')->values();
                    } elseif (\Illuminate\Support\Facades\Schema::hasTable('product_attributes') && \Illuminate\Support\Facades\Schema::hasColumn('product_attributes', 'is_custom')) {
                        $q = $product->attributes()->where('is_custom', true);
                        if (\Illuminate\Support\Facades\Schema::hasColumn('product_attributes', 'sort_order')) {
                            $q->orderBy('sort_order');
                        }
                        $customAttributes = $q->get();
                    }
                    $customAttributes = $customAttributes->take(5);
                ?>

                <?php if($customAttributes->count() > 0): ?>
                    <div class="option-group" id="productAttributes">
                        <div class="option-label"><i class="fas fa-sliders"></i> الخصائص</div>
                        <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                            <?php $__currentLoopData = $customAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $type = (string) ($attr->type ?? 'text');
                                    $options = is_array($attr->options ?? null) ? $attr->options : [];
                                    $fieldId = 'attr_'.$attr->id;
                                    $jsonVal = is_array($attr->value_json ?? null) ? $attr->value_json : null;
                                ?>
                                <div class="attr-field" style="display:flex; flex-direction:column; gap:0.45rem;">
                                    <label for="<?php echo e($fieldId); ?>" style="font-weight:700; color:#333;"><?php echo e($attr->name); ?></label>
                                    <?php if($type === 'select' || $type === 'radio' || $type === 'radio_group'): ?>
                                        <select id="<?php echo e($fieldId); ?>" class="qty-control" style="width:100%; height:55px; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff;" aria-label="<?php echo e($attr->name); ?>">
                                            <?php if(!empty($options)): ?>
                                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($opt); ?>" <?php if((string) $attr->value === (string) $opt): echo 'selected'; endif; ?>><?php echo e($opt); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <option value="<?php echo e($attr->value); ?>"><?php echo e($attr->value); ?></option>
                                            <?php endif; ?>
                                        </select>
                                    <?php elseif($type === 'checkbox_group' || $type === 'multiselect'): ?>
                                        <?php
                                            $selected = [];
                                            if (is_array($jsonVal)) {
                                                $selected = $jsonVal;
                                            } else {
                                                try {
                                                    $decoded = json_decode((string) $attr->value, true);
                                                    if (is_array($decoded)) {
                                                        $selected = $decoded;
                                                    }
                                                } catch (\Throwable $e) {
                                                }
                                            }
                                            $selected = array_values(array_filter(array_map(fn ($v) => (string) $v, $selected), fn ($v) => $v !== ''));
                                        ?>
                                        <select id="<?php echo e($fieldId); ?>" class="qty-control" style="width:100%; min-height:55px; padding:0.5rem 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff;" multiple aria-label="<?php echo e($attr->name); ?>">
                                            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($opt); ?>" <?php if(in_array((string) $opt, $selected, true)): echo 'selected'; endif; ?>><?php echo e($opt); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php elseif($type === 'date'): ?>
                                        <input id="<?php echo e($fieldId); ?>" type="date" value="<?php echo e($attr->value); ?>" style="width:100%; height:55px; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff;" aria-label="<?php echo e($attr->name); ?>">
                                    <?php elseif($type === 'checkbox'): ?>
                                        <div style="display:flex; align-items:center; gap:0.6rem; height:55px; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff;">
                                            <input id="<?php echo e($fieldId); ?>" type="checkbox" aria-label="<?php echo e($attr->name); ?>" <?php if(filter_var($attr->value, FILTER_VALIDATE_BOOLEAN)): echo 'checked'; endif; ?>>
                                            <span style="font-weight:600; color:#444;"><?php echo e($attr->name); ?></span>
                                        </div>
                                    <?php elseif($type === 'textarea'): ?>
                                        <textarea id="<?php echo e($fieldId); ?>" rows="2" style="width:100%; padding:0.8rem 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff; resize:vertical;" aria-label="<?php echo e($attr->name); ?>"><?php echo e($attr->value); ?></textarea>
                                    <?php elseif($type === 'number'): ?>
                                        <input id="<?php echo e($fieldId); ?>" type="number" value="<?php echo e($attr->value); ?>" style="width:100%; height:55px; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff;" aria-label="<?php echo e($attr->name); ?>">
                                    <?php elseif($type === 'file'): ?>
                                        <?php
                                            $path = (string) ($attr->value_text ?? $attr->value ?? '');
                                            $url = $path !== '' ? asset('storage/'.$path) : '';
                                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif'], true);
                                        ?>
                                        <?php if($url): ?>
                                            <?php if($isImage): ?>
                                                <img src="<?php echo e($url); ?>" alt="<?php echo e($attr->name); ?>" style="width:100%; max-height:180px; object-fit:cover; border-radius:14px; border:2px solid #e5e7eb; background:#fff;">
                                            <?php else: ?>
                                                <a href="<?php echo e($url); ?>" style="display:inline-flex; align-items:center; justify-content:center; height:55px; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff; font-weight:800; color:#2a7080; text-decoration:none;" target="_blank" rel="noopener noreferrer">تحميل الملف</a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div style="height:55px; display:flex; align-items:center; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff; color:#6b7280; font-weight:700;">لا يوجد ملف</div>
                                        <?php endif; ?>
                                    <?php elseif($type === 'color'): ?>
                                        <?php
                                            $colors = $options;
                                            if (empty($colors) && (string) $attr->value !== '') {
                                                $colors = [$attr->value];
                                            }
                                        ?>
                                        <div role="radiogroup" aria-label="<?php echo e($attr->name); ?>" style="display:flex; flex-wrap:wrap; gap:0.6rem; padding:0.7rem 0.2rem;">
                                            <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $cid = $fieldId.'_'.$i; ?>
                                                <input type="radio" id="<?php echo e($cid); ?>" name="color_<?php echo e($attr->id); ?>" value="<?php echo e($c); ?>" <?php if((string) $attr->value === (string) $c): echo 'checked'; endif; ?> style="position:absolute; opacity:0; width:1px; height:1px;">
                                                <label for="<?php echo e($cid); ?>" aria-label="<?php echo e($attr->name); ?> <?php echo e($c); ?>" style="width:44px; height:44px; border-radius:999px; background:<?php echo e($c); ?>; border:3px solid #fff; box-shadow:0 2px 10px rgba(0,0,0,0.15); cursor:pointer;"></label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <input id="<?php echo e($fieldId); ?>" type="text" value="<?php echo e($attr->value); ?>" style="width:100%; height:55px; padding:0 1rem; border-radius:14px; border:2px solid #e5e7eb; background:#fff;" aria-label="<?php echo e($attr->name); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

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

            

                <div class="features-strip">
                    <div class="feature-box">
                        <i class="fas fa-truck-fast"></i>
                        <p>توصيل سريع<br>خلال 2-3 أيام</p>
                    </div>
                    <div class="feature-box">
                        <i class="fas fa-shield-halved"></i>
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
                <div class="products-grid">
                    <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('/products/'.$rp->id)); ?>" class="product-card" style="text-decoration:none; color:inherit; background:#fff; border:1px solid #eee; border-radius:14px; overflow:hidden; display:flex; flex-direction:column;">
                            <div class="product-image-wrapper">
                                <img src="<?php echo e($rp->primary_image_url); ?>" srcset="<?php echo e($rp->primary_image_srcset); ?>" sizes="(max-width: 768px) 50vw, 20vw" alt="<?php echo e($rp->name); ?>" class="product-img" loading="lazy" width="320" height="320" onerror="this.src='/images/gift-placeholder.svg'">
                            </div>
                            <div style="padding:0.9rem; flex:1; display:flex; flex-direction:column; justify-content:space-between;">
                                <div style="font-family:'El Messiri',sans-serif; font-weight:700; font-size:0.95rem; margin-bottom:0.4rem; line-height:1.4;">
                                    <?php echo e($rp->name); ?>

                                </div>
                                <div style="font-weight:800; color:#ea580c;">
                                    <?php echo e(number_format($rp->discount_price ?? $rp->price, 0)); ?> ل.س
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

   <!-- Footer -->
 <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <script>
        const productId = <?php echo e($product->id); ?>;
        const productData = {
            id: <?php echo e($product->id); ?>,
            name: "<?php echo e(addslashes($product->name)); ?>",
            price: <?php echo e($product->discount_price ?? $product->price); ?>,
            image: "<?php echo e($product->primary_image_url); ?>"
        };
        const trackInventory = <?php echo (bool) ($product->track_inventory ?? false) ? 'true' : 'false'; ?>;
        const stockQuantity = <?php echo e((int) ($product->stock_quantity ?? 0)); ?>;
        const isAuthenticated = <?php echo auth()->check() ? 'true' : 'false'; ?>;

        document.addEventListener('DOMContentLoaded', function () {
            checkFavoriteStatus();
            const formatter = window.formatDualMoney || function (amountUsd) {
                const n = Number(amountUsd || 0);
                const rate = Number(window.TULIP_USD_TO_SYP || 0);
                const usd = '$' + n.toFixed(2);
                if (!rate) return usd;
                const syp = Math.round(n * rate).toLocaleString() + ' SYP';
                return `${usd} • ${syp}`;
            };

            const currentEl = document.getElementById('currentPrice');
            if (currentEl) {
                currentEl.textContent = formatter(currentEl.dataset.usd);
            }

            const oldEl = document.getElementById('oldPrice');
            if (oldEl) {
                oldEl.textContent = formatter(oldEl.dataset.usd);
            }

            const saveEl = document.getElementById('saveAmount');
            if (saveEl) {
                saveEl.textContent = 'وفر ' + formatter(saveEl.dataset.usd);
            }
        });

        function checkFavoriteStatus() {
            const btn = document.getElementById('favBtn');
            if (isAuthenticated) {
                fetch('/api/wishlist', { credentials: 'same-origin' })
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
                    credentials: 'same-origin',
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
            const img = document.getElementById('mainImage');
            if (img && window.setImageWithFallback) {
                window.setImageWithFallback(img, src, [], '/images/gift-placeholder.svg', 2000);
            } else if (img) {
                img.src = src;
            }
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        function changeQty(delta) {
            const input = document.getElementById('quantity');
            let val = Math.max(1, Math.min(99, parseInt(input.value) + delta));
            input.value = val;
        }

        async function addToCart() {
            if (trackInventory && stockQuantity <= 0) {
                const msg = `لا يوجد مخزون من ${productData.name}`;
                if (window.showToast) window.showToast(msg, 3500);
                else alert(msg);
                return;
            }
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
                    credentials: 'same-origin',
                    body: JSON.stringify({ product_id: productId, quantity })
                });
                const data = await response.json();
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #16a34a, #22c55e)';
                    if (window.updateCartCount) window.updateCartCount(data.cart_count || 0);
                    setTimeout(() => { btn.innerHTML = originalHTML; btn.style.background = ''; btn.disabled = false; }, 2000);
                } else {
                    const msg = data && data.message ? data.message : 'فشل إضافة المنتج للسلة';
                    if (window.showToast) window.showToast(msg, 3500);
                    else alert(msg);
                    throw new Error(msg);
                }
            } catch (e) {
                btn.innerHTML = '<i class="fas fa-times"></i> فشل';
                btn.style.background = 'linear-gradient(135deg, #dc2626, #ef4444)';
                setTimeout(() => { btn.innerHTML = originalHTML; btn.style.background = ''; btn.disabled = false; }, 2000);
            }
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

        window.IMAGE_FALLBACK_LOG_URL = '/api/image-fallback/log';

        document.addEventListener('DOMContentLoaded', function () {
            const placeholder = '/images/gift-placeholder.svg';
            const main = document.getElementById('mainImage');
            if (main && window.setImageWithFallback) {
                window.setImageWithFallback(main, main.getAttribute('src'), [], placeholder, 2000);
            }
            document.querySelectorAll('.thumbnails img').forEach((img) => {
                if (window.setImageWithFallback) {
                    window.setImageWithFallback(img, img.getAttribute('src'), [], placeholder, 2000);
                }
            });
        });
    </script>
    <script src="/js/image-fallback.js"></script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/products/show.blade.php ENDPATH**/ ?>