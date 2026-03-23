<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($gift->name); ?> - هدايا توليب</title>
    <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family:  'El Messiri', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .gift-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .breadcrumb {
            background: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .breadcrumb a {
            color: #2a7080;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .gift-detail {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        .gift-detail-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            padding: 3rem;
        }

        .gift-images {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gift-info h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .gift-category {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* .gift-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        } */

        .stars {
            color: #ffa500;
        }
        
        .stars .star-off {
            color: rgba(0, 0, 0, 0.18);
        }

        .gift-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2a7080;
            margin-bottom: 1.5rem;
        }

        .gift-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 2rem;
        }

        .gift-features {
            margin-bottom: 2rem;
        }

        .gift-features h3 {
            color: #2a7080;
            margin-bottom: 1rem;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feature-list li i {
            color: #2a7080;
        }

        .customization-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .customization-section h3 {
            color: #2a7080;
            margin-bottom: 1rem;
        }

        .custom-options {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .custom-option {
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .custom-option:hover, .custom-option.selected {
            border-color: #2a7080;
            background: #2a7080;
            color: white;
        }

        .quantity-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #f8f9fa;
            border: none;
            padding: 0.75rem 1rem;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .quantity-btn:hover {
            background: #2a7080;
            color: white;
        }

        .quantity-input {
            border: none;
            padding: 0.75rem;
            text-align: center;
            width: 60px;
            font-size: 1.1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a7080, #1a5060);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(42, 112, 128, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #2a7080;
            border: 2px solid #2a7080;
            padding: 1rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #2a7080;
            color: white;
        }

        .related-gifts {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .related-gifts h2 {
            font-size: 2rem;
            color: #2a7080;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .related-card {
            border: 1px solid #eee;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .related-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .related-content {
            padding: 1rem;
        }

        .related-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .related-price {
            color: #2a7080;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .gift-detail-container {
                padding: 0;
            }

            .breadcrumb {
                margin: 1rem;
            }

            .gift-detail, .related-gifts {
                border-radius: 0;
                box-shadow: none;
                margin-bottom: 1.5rem;
            }

            .gift-detail-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .main-image {
                height: auto;
                aspect-ratio: 1 / 1;
                border-radius: 0;
            }

            .gift-info h1 {
                font-size: 1.8rem;
            }

            .gift-price {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            /* Related Gifts Mobile */
            .related-gifts {
                padding: 1.5rem 1rem;
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.8rem;
            }

            .related-card {
                border-radius: 12px;
                transform: none !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
            }

            .related-image {
                height: auto;
                aspect-ratio: 1 / 1;
            }

            .related-content {
                padding: 0.8rem;
            }

            .related-name {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

    <div class="gift-detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo e(route('gifts.index')); ?>"><i class="fas fa-gift"></i> الهدايا</a>
            <i class="fas fa-chevron-left"></i>
            <a href="<?php echo e(route('gifts.category', $gift->category)); ?>"><?php echo e($gift->category_name); ?></a>
            <i class="fas fa-chevron-left"></i>
            <span><?php echo e($gift->name); ?></span>
        </div>

        <!-- Gift Detail -->
        <div class="gift-detail">
            <div class="gift-detail-content">
                <!-- Images -->
                <div class="gift-images">
                    <img src="<?php echo e($gift->main_image); ?>" alt="<?php echo e($gift->name); ?>" class="main-image" loading="eager" width="900" height="500" onerror="this.src='/images/birthday_card.jpeg'">
                </div>

                <!-- Info -->
                <div class="gift-info">
                    <h1><?php echo e($gift->name); ?></h1>
                    
                    <span class="gift-category <?php echo e($gift->category_color); ?>">
                        <?php echo e($gift->category_name); ?>

                    </span>

                    <!-- <div class="gift-rating">
                        <div class="stars">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo e($i <= $gift->rating ? '' : 'star-off'); ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span>(<?php echo e($gift->reviews_count); ?> تقييم)</span>
                    </div> -->

                    <div class="gift-price"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($gift->price)); ?></div>

                    <div class="gift-description">
                        <?php echo e($gift->description); ?>

                    </div>

                    <div class="gift-features">
                        <h3><i class="fas fa-check-circle"></i> مميزات الهدية</h3>
                        <ul class="feature-list">
                            <li><i class="fas fa-shipping-fast"></i> توصيل سريع</li>
                            <li><i class="fas fa-gift-card"></i> تغليف مجاني</li>
                            <li><i class="fas fa-heart"></i> مصنوعة بعناية</li>
                            <?php if($gift->is_customizable): ?>
                                <li><i class="fas fa-palette"></i> قابلة للتخصيص</li>
                            <?php endif; ?>
                            <?php if($gift->delivery_time): ?>
                                <li><i class="fas fa-clock"></i> <?php echo e($gift->delivery_time); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if($gift->is_customizable && $gift->customization_options): ?>
                        <div class="customization-section">
                            <h3><i class="fas fa-palette"></i> خيارات التخصيص</h3>
                            <div class="custom-options">
                                <?php $__currentLoopData = $gift->customization_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="custom-option" onclick="selectOption(this)">
                                        <?php echo e($option); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="quantity-section">
                        <label>الكمية:</label>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="<?php echo e($gift->stock_quantity); ?>" id="quantity">
                            <button class="quantity-btn" onclick="changeQuantity(1)">+</button>
                        </div>
                        <span class="stock-info">
                            <?php if($gift->is_in_stock): ?>
                                <i class="fas fa-check text-green-500"></i> متوفر (<?php echo e($gift->stock_quantity); ?> قطعة)
                            <?php else: ?>
                                <i class="fas fa-times text-red-500"></i> غير متوفر
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="action-buttons">
                        <?php if($gift->is_in_stock): ?>
                            <button class="btn-primary" onclick="addToCart()">
                                <i class="fas fa-shopping-cart"></i> إضافة للسلة
                            </button>
                        <?php else: ?>
                            <button class="btn-primary" disabled>
                                <i class="fas fa-times"></i> غير متوفر
                            </button>
                        <?php endif; ?>
                        <button class="btn-secondary" onclick="addToWishlist()">
                            <i class="fas fa-heart"></i> المفضلة
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Gifts -->
        <?php if($relatedGifts->count() > 0): ?>
            <div class="related-gifts">
                <h2><i class="fas fa-heart"></i> هدايا مشابهة</h2>
                <div class="related-grid">
                    <?php $__currentLoopData = $relatedGifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedGift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="related-card" onclick="window.location.href='<?php echo e(route('gifts.show', $relatedGift)); ?>'">
                            <img src="<?php echo e($relatedGift->main_image); ?>" alt="<?php echo e($relatedGift->name); ?>" class="related-image" loading="lazy" width="320" height="200" onerror="this.src='/images/birthday_card.jpeg'">
                            <div class="related-content">
                                <div class="related-name"><?php echo e($relatedGift->name); ?></div>
                                <div class="related-price"><?php echo app(App\Services\CurrencyService::class)->formatUsd((float)($relatedGift->price)); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        function changeQuantity(change) {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value);
            const newValue = currentValue + change;
            const max = parseInt(input.max);
            
            if (newValue >= 1 && newValue <= max) {
                input.value = newValue;
            }
        }

        function selectOption(element) {
            // Remove selected class from all options
            document.querySelectorAll('.custom-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
        }

        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            const selectedOptions = Array.from(document.querySelectorAll('.custom-option.selected')).map(opt => opt.textContent);
            
            // Show success message
            if (window.showToast) {
                window.showToast('تم إضافة الهدية إلى السلة بنجاح!');
            }
            
            // Animate cart icon
            if (window.animateCartIcon) {
                window.animateCartIcon();
            }
        }

        function addToWishlist() {
            const giftId = 'gift-<?php echo e($gift->id); ?>';
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            const list = Array.isArray(items) ? items : [];
            const idx = list.findIndex((x) => String(x?.id) === giftId);

            if (idx >= 0) {
                list.splice(idx, 1);
                localStorage.setItem('favorites', JSON.stringify(list.slice(0, 200)));
                if (window.showToast) window.showToast('تمت إزالة الهدية من المفضلة');
                return;
            }

            list.unshift({
                id: giftId,
                type: 'gift',
                name: <?php echo json_encode($gift->name, 15, 512) ?>,
                price: Number(<?php echo json_encode((float) $gift->price, 15, 512) ?>),
                image: <?php echo json_encode($gift->main_image, 15, 512) ?>,
                url: <?php echo json_encode(route('gifts.show', $gift), 512) ?>,
            });
            localStorage.setItem('favorites', JSON.stringify(list.slice(0, 200)));
            if (window.showToast) window.showToast('تم إضافة الهدية إلى المفضلة!');
        }
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/gifts/show.blade.php ENDPATH**/ ?>