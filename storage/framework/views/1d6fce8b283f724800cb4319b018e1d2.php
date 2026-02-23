<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($category->name); ?> - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .category-header {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            padding: 2rem;
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }
        .category-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .category-description {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .products-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 260px;
            gap: 2rem;
        }
        .filters-sidebar {
            background: #ffffff;
            padding: 0;
            border-radius: 12px;
            height: fit-content;
            width: 260px;
            box-shadow: 0 2px 8px rgba(15, 79, 85, 0.08);
            overflow: hidden;
        }
        .filter-section {
            border-bottom: 1px solid #e8f4f5;
            padding: 1.2rem 0;
        }
        .filter-section:first-child {
            padding-top: 1.2rem;
        }
        .filter-section:last-child {
            border-bottom: none;
        }
        .filter-section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f4f55;
            margin-bottom: 0.9rem;
            padding: 0 1.2rem;
        }
        .filter-option {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.5rem 1.2rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .filter-option:hover {
            background: #f0f9fa;
        }
        .filter-option input[type="checkbox"] {
            width: 17px;
            height: 17px;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
            border: 2px solid #0f4f55;
            border-radius: 3px;
            accent-color: #0f4f55;
        }
        .filter-option input[type="checkbox"]:checked {
            background: #0f4f55;
        }
        .filter-option label {
            cursor: pointer;
            font-family: 'Changa', sans-serif;
            font-size: 0.9rem;
            color: #2c3e50;
            line-height: 1.5;
            flex: 1;
            font-weight: 400;
        }
        .filter-option label:hover {
            color: #0f4f55;
        }
        .filter-see-more {
            color: #0f4f55;
            font-family: 'Changa', sans-serif;
            font-size: 0.875rem;
            cursor: pointer;
            padding: 0.5rem 1.2rem;
            display: inline-block;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .filter-see-more:hover {
            color: #1a6b73;
            text-decoration: underline;
        }
        .price-inputs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0 1.2rem;
            margin-bottom: 0.8rem;
        }
        .price-input-wrapper {
            flex: 1;
            position: relative;
        }
        .price-input {
            width: 100%;
            padding: 0.5rem 0.4rem;
            border: 2px solid #d1e7e9;
            border-radius: 8px;
            font-family: 'Changa', sans-serif;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            background: #fafafa;
            text-align: center;
        }
        .price-input::placeholder {
            font-size: 0.75rem;
        }
        .price-input:focus {
            outline: none;
            border-color: #0f4f55;
            background: white;
            box-shadow: 0 0 0 3px rgba(15, 79, 85, 0.1);
        }
        .price-separator {
            color: #7f8c8d;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .avg-rating-filter {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 0 0.5rem;
        }
        .avg-rating-filter:hover {
            background: #f0f9fa;
        }
        .rating-stars {
            display: flex;
            gap: 2px;
        }
        .rating-stars i {
            font-size: 0.9rem;
            color: #ffa500;
        }
        .rating-text {
            font-family: 'Changa', sans-serif;
            font-size: 0.875rem;
            color: #0f4f55;
            text-decoration: none;
            font-weight: 500;
        }
        .rating-text:hover {
            color: #1a6b73;
            text-decoration: underline;
        }
        .products-content {
            min-width: 0;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }
        
        @media (max-width: 1400px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem 1rem;
            }
        }
        .no-products {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        .no-products i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        @media (max-width: 768px) {
            .category-title {
                font-size: 1.5rem;
            }
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="products-container">
        <!-- Products Content -->
        <div class="products-content">
            <h2 style="font-family: 'El Messiri', sans-serif; font-size: 2rem; color: #0f4f55; margin: 0 0 2rem 0;"><?php echo e($category->name); ?></h2>
            <?php if($products->count() > 0): ?>
                <div class="products-grid">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="product-card" data-product-id="<?php echo e($product->id); ?>">
                            <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, <?php echo e($product->id); ?>, <?php echo e(json_encode($product)); ?>)">
                                <i class="far fa-heart"></i>
                            </button>
                            <div class="product-image-wrapper" onclick='openFloatingView(<?php echo json_encode($product, 15, 512) ?>)'>
                                <img src="<?php echo e($product->image ?? 'https://via.placeholder.com/250'); ?>" alt="<?php echo e($product->name); ?>" class="product-img">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo e($product->name); ?></h3>
                                <div class="product-price-rating-wrapper">
                                    <div class="product-price-wrapper">
                                        <span class="product-price">$<?php echo e(number_format($product->discount_price ?? $product->price, 2)); ?></span>
                                        <?php if($product->discount_price): ?>
                                            <span class="product-old-price">$<?php echo e(number_format($product->price, 2)); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-rating">
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="product-card-actions">
                                <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(event, <?php echo e($product->id); ?>)">
                                    إضافة للسلة
                                </button>
                                <button class="product-card-btn product-card-btn-share" onclick="event.stopPropagation(); shareProduct(event, '<?php echo e($product->name); ?>')">
                                    شاركه الآن
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 3rem; text-align: center;">
                    <?php echo e($products->links()); ?>

                </div>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <h2>لا توجد منتجات في هذا القسم حالياً</h2>
                    <p>نعمل على إضافة منتجات جديدة قريباً</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Filters Sidebar (Amazon Style) -->
        <div class="filters-sidebar">
            <?php
                $brands = $products->pluck('brand')->unique()->filter()->sort()->values();
            ?>
            
            <!-- Customer Reviews -->
            <div class="filter-section">
                <div class="filter-section-title">تقييمات العملاء</div>
                <div class="avg-rating-filter" onclick="filterByRating(4)">
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="rating-text">وأعلى</span>
                </div>
                <div class="avg-rating-filter" onclick="filterByRating(3)">
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="rating-text">وأعلى</span>
                </div>
                <div class="avg-rating-filter" onclick="filterByRating(2)">
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="rating-text">وأعلى</span>
                </div>
                <div class="avg-rating-filter" onclick="filterByRating(1)">
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="rating-text">وأعلى</span>
                </div>
            </div>

            <!-- Brand -->
            <?php if($brands->count() > 0): ?>
            <div class="filter-section">
                <div class="filter-section-title">العلامة التجارية</div>
                <?php $__currentLoopData = $brands->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="filter-option">
                    <input type="checkbox" id="brand-<?php echo e($loop->index); ?>" value="<?php echo e($brand); ?>" onchange="applyFilters()">
                    <label for="brand-<?php echo e($loop->index); ?>"><?php echo e($brand); ?></label>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($brands->count() > 8): ?>
                <span class="filter-see-more" onclick="toggleBrandExpand()">عرض المزيد</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Price -->
            <div class="filter-section">
                <div class="filter-section-title">السعر</div>
                <div class="price-inputs">
                    <div class="price-input-wrapper">
                        <input type="number" class="price-input" id="minPrice" placeholder="من" min="0">
                    </div>
                    <span class="price-separator">-</span>
                    <div class="price-input-wrapper">
                        <input type="number" class="price-input" id="maxPrice" placeholder="إلى" min="0">
                    </div>
                </div>
                
                <div class="filter-option">
                    <input type="checkbox" id="price-under-25" value="0-25" onchange="applyPriceRange(0, 25)">
                    <label for="price-under-25">أقل من $25</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="price-25-50" value="25-50" onchange="applyPriceRange(25, 50)">
                    <label for="price-25-50">$25 إلى $50</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="price-50-100" value="50-100" onchange="applyPriceRange(50, 100)">
                    <label for="price-50-100">$50 إلى $100</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="price-100-200" value="100-200" onchange="applyPriceRange(100, 200)">
                    <label for="price-100-200">$100 إلى $200</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="price-over-200" value="200-999999" onchange="applyPriceRange(200, 999999)">
                    <label for="price-over-200">$200 وأكثر</label>
                </div>
            </div>

            <!-- Availability -->
            <div class="filter-section">
                <div class="filter-section-title">التوفر</div>
                <div class="filter-option">
                    <input type="checkbox" id="inStock" onchange="toggleAvailabilityFilter('in-stock')">
                    <label for="inStock">متوفر</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="includeOutOfStock" onchange="toggleAvailabilityFilter('include-out')">
                    <label for="includeOutOfStock">تضمين غير المتوفر</label>
                </div>
            </div>

            <!-- Condition -->
            <?php
                $conditions = $products->pluck('condition')->unique()->filter()->sort()->values();
            ?>
            <?php if($conditions->count() > 0): ?>
            <div class="filter-section">
                <div class="filter-section-title">الحالة</div>
                <?php $__currentLoopData = $conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="filter-option">
                    <input type="checkbox" id="condition-<?php echo e($loop->index); ?>" value="<?php echo e($condition); ?>" onchange="applyFilters()">
                    <label for="condition-<?php echo e($loop->index); ?>">
                        <?php if($condition == 'new'): ?> جديد
                        <?php elseif($condition == 'used'): ?> مستعمل
                        <?php elseif($condition == 'refurbished'): ?> مجدد
                        <?php else: ?> <?php echo e($condition); ?>

                        <?php endif; ?>
                    </label>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <?php
                $slug = strtolower($category->slug ?? '');
                $name = strtolower($category->name ?? '');
                
                // Detect category type
                $isClothing = str_contains($slug, 'cloth') || str_contains($name, 'ملابس') || str_contains($slug, 'fashion');
                $isShoes = str_contains($slug, 'shoe') || str_contains($name, 'أحذية') || str_contains($slug, 'footwear');
                $isElectronics = str_contains($slug, 'electron') || str_contains($name, 'إلكترونيات') || str_contains($slug, 'tech');
                $isBooks = str_contains($slug, 'book') || str_contains($name, 'كتب');
                $isToys = str_contains($slug, 'toy') || str_contains($name, 'ألعاب') || str_contains($slug, 'kids');
                $isHome = str_contains($slug, 'home') || str_contains($name, 'منزل') || str_contains($slug, 'kitchen');
                $isSports = str_contains($slug, 'sport') || str_contains($name, 'رياضة');
            ?>

            <!-- Clothing Filters -->
            <?php if($isClothing): ?>
                <?php
                    $colors = $products->pluck('color')->unique()->filter()->sort()->values();
                    $sizes = $products->pluck('size')->unique()->filter()->sort()->values();
                    $materials = $products->pluck('material')->unique()->filter()->sort()->values();
                    $fits = $products->pluck('fit')->unique()->filter()->sort()->values();
                    $patterns = $products->pluck('pattern')->unique()->filter()->sort()->values();
                ?>
                
                <?php if($colors->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">اللون</div>
                    <?php $__currentLoopData = $colors->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="color-<?php echo e($loop->index); ?>" value="<?php echo e($color); ?>" onchange="applyFilters()">
                        <label for="color-<?php echo e($loop->index); ?>"><?php echo e($color); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($sizes->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">المقاس</div>
                    <?php $__currentLoopData = $sizes->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="size-<?php echo e($loop->index); ?>" value="<?php echo e($size); ?>" onchange="applyFilters()">
                        <label for="size-<?php echo e($loop->index); ?>"><?php echo e($size); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($materials->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">المادة</div>
                    <?php $__currentLoopData = $materials->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="material-<?php echo e($loop->index); ?>" value="<?php echo e($material); ?>" onchange="applyFilters()">
                        <label for="material-<?php echo e($loop->index); ?>"><?php echo e($material); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($fits->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">القصة</div>
                    <?php $__currentLoopData = $fits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="fit-<?php echo e($loop->index); ?>" value="<?php echo e($fit); ?>" onchange="applyFilters()">
                        <label for="fit-<?php echo e($loop->index); ?>"><?php echo e($fit); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($patterns->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">النقشة</div>
                    <?php $__currentLoopData = $patterns->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pattern): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="pattern-<?php echo e($loop->index); ?>" value="<?php echo e($pattern); ?>" onchange="applyFilters()">
                        <label for="pattern-<?php echo e($loop->index); ?>"><?php echo e($pattern); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Shoes Filters -->
            <?php if($isShoes): ?>
                <?php
                    $shoeSizes = $products->pluck('shoe_size')->unique()->filter()->sort()->values();
                    $shoeTypes = $products->pluck('shoe_type')->unique()->filter()->sort()->values();
                    $colors = $products->pluck('color')->unique()->filter()->sort()->values();
                ?>
                
                <?php if($shoeSizes->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">مقاس الحذاء</div>
                    <?php $__currentLoopData = $shoeSizes->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shoeSize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="shoe-size-<?php echo e($loop->index); ?>" value="<?php echo e($shoeSize); ?>" onchange="applyFilters()">
                        <label for="shoe-size-<?php echo e($loop->index); ?>"><?php echo e($shoeSize); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($shoeTypes->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">نوع الحذاء</div>
                    <?php $__currentLoopData = $shoeTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shoeType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="shoe-type-<?php echo e($loop->index); ?>" value="<?php echo e($shoeType); ?>" onchange="applyFilters()">
                        <label for="shoe-type-<?php echo e($loop->index); ?>"><?php echo e($shoeType); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($colors->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">اللون</div>
                    <?php $__currentLoopData = $colors->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="color-<?php echo e($loop->index); ?>" value="<?php echo e($color); ?>" onchange="applyFilters()">
                        <label for="color-<?php echo e($loop->index); ?>"><?php echo e($color); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Electronics Filters -->
            <?php if($isElectronics): ?>
                <?php
                    $screenSizes = $products->pluck('screen_size')->unique()->filter()->sort()->values();
                    $storages = $products->pluck('storage')->unique()->filter()->sort()->values();
                    $rams = $products->pluck('ram')->unique()->filter()->sort()->values();
                    $processors = $products->pluck('processor')->unique()->filter()->sort()->values();
                ?>
                
                <?php if($screenSizes->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">حجم الشاشة</div>
                    <?php $__currentLoopData = $screenSizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $screenSize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="screen-<?php echo e($loop->index); ?>" value="<?php echo e($screenSize); ?>" onchange="applyFilters()">
                        <label for="screen-<?php echo e($loop->index); ?>"><?php echo e($screenSize); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($storages->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">التخزين</div>
                    <?php $__currentLoopData = $storages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="storage-<?php echo e($loop->index); ?>" value="<?php echo e($storage); ?>" onchange="applyFilters()">
                        <label for="storage-<?php echo e($loop->index); ?>"><?php echo e($storage); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($rams->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">الذاكرة العشوائية</div>
                    <?php $__currentLoopData = $rams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="ram-<?php echo e($loop->index); ?>" value="<?php echo e($ram); ?>" onchange="applyFilters()">
                        <label for="ram-<?php echo e($loop->index); ?>"><?php echo e($ram); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($processors->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">المعالج</div>
                    <?php $__currentLoopData = $processors->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $processor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="processor-<?php echo e($loop->index); ?>" value="<?php echo e($processor); ?>" onchange="applyFilters()">
                        <label for="processor-<?php echo e($loop->index); ?>"><?php echo e($processor); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Books Filters -->
            <?php if($isBooks): ?>
                <?php
                    $authors = $products->pluck('author')->unique()->filter()->sort()->values();
                    $publishers = $products->pluck('publisher')->unique()->filter()->sort()->values();
                    $languages = $products->pluck('language')->unique()->filter()->sort()->values();
                    $formats = $products->pluck('format')->unique()->filter()->sort()->values();
                    $genres = $products->pluck('genre')->unique()->filter()->sort()->values();
                ?>
                
                <?php if($authors->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">المؤلف</div>
                    <?php $__currentLoopData = $authors->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="author-<?php echo e($loop->index); ?>" value="<?php echo e($author); ?>" onchange="applyFilters()">
                        <label for="author-<?php echo e($loop->index); ?>"><?php echo e($author); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($publishers->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">الناشر</div>
                    <?php $__currentLoopData = $publishers->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publisher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="publisher-<?php echo e($loop->index); ?>" value="<?php echo e($publisher); ?>" onchange="applyFilters()">
                        <label for="publisher-<?php echo e($loop->index); ?>"><?php echo e($publisher); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($languages->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">اللغة</div>
                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="language-<?php echo e($loop->index); ?>" value="<?php echo e($language); ?>" onchange="applyFilters()">
                        <label for="language-<?php echo e($loop->index); ?>"><?php echo e($language); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($formats->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">التنسيق</div>
                    <?php $__currentLoopData = $formats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="format-<?php echo e($loop->index); ?>" value="<?php echo e($format); ?>" onchange="applyFilters()">
                        <label for="format-<?php echo e($loop->index); ?>"><?php echo e($format); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($genres->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">النوع</div>
                    <?php $__currentLoopData = $genres->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="genre-<?php echo e($loop->index); ?>" value="<?php echo e($genre); ?>" onchange="applyFilters()">
                        <label for="genre-<?php echo e($loop->index); ?>"><?php echo e($genre); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Toys Filters -->
            <?php if($isToys): ?>
                <?php
                    $toyTypes = $products->pluck('toy_type')->unique()->filter()->sort()->values();
                ?>
                
                <div class="filter-section">
                    <div class="filter-section-title">الفئة العمرية</div>
                    <div class="filter-option">
                        <input type="checkbox" id="age-0-2" value="0-2" onchange="applyFilters()">
                        <label for="age-0-2">0-2 سنة</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="age-3-5" value="3-5" onchange="applyFilters()">
                        <label for="age-3-5">3-5 سنوات</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="age-6-8" value="6-8" onchange="applyFilters()">
                        <label for="age-6-8">6-8 سنوات</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="age-9-12" value="9-12" onchange="applyFilters()">
                        <label for="age-9-12">9-12 سنة</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="age-13plus" value="13+" onchange="applyFilters()">
                        <label for="age-13plus">13+ سنة</label>
                    </div>
                </div>

                <?php if($toyTypes->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">نوع اللعبة</div>
                    <?php $__currentLoopData = $toyTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toyType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="toy-type-<?php echo e($loop->index); ?>" value="<?php echo e($toyType); ?>" onchange="applyFilters()">
                        <label for="toy-type-<?php echo e($loop->index); ?>"><?php echo e($toyType); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Home & Kitchen Filters -->
            <?php if($isHome): ?>
                <?php
                    $rooms = $products->pluck('room')->unique()->filter()->sort()->values();
                    $capacities = $products->pluck('capacity')->unique()->filter()->sort()->values();
                ?>
                
                <?php if($rooms->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">الغرفة</div>
                    <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="room-<?php echo e($loop->index); ?>" value="<?php echo e($room); ?>" onchange="applyFilters()">
                        <label for="room-<?php echo e($loop->index); ?>"><?php echo e($room); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($capacities->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">السعة</div>
                    <?php $__currentLoopData = $capacities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $capacity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="capacity-<?php echo e($loop->index); ?>" value="<?php echo e($capacity); ?>" onchange="applyFilters()">
                        <label for="capacity-<?php echo e($loop->index); ?>"><?php echo e($capacity); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Sports Filters -->
            <?php if($isSports): ?>
                <?php
                    $sportTypes = $products->pluck('sport_type')->unique()->filter()->sort()->values();
                    $skillLevels = $products->pluck('skill_level')->unique()->filter()->sort()->values();
                ?>
                
                <?php if($sportTypes->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">نوع الرياضة</div>
                    <?php $__currentLoopData = $sportTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sportType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="sport-<?php echo e($loop->index); ?>" value="<?php echo e($sportType); ?>" onchange="applyFilters()">
                        <label for="sport-<?php echo e($loop->index); ?>"><?php echo e($sportType); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <?php if($skillLevels->count() > 0): ?>
                <div class="filter-section">
                    <div class="filter-section-title">مستوى المهارة</div>
                    <?php $__currentLoopData = $skillLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skillLevel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="filter-option">
                        <input type="checkbox" id="skill-<?php echo e($loop->index); ?>" value="<?php echo e($skillLevel); ?>" onchange="applyFilters()">
                        <label for="skill-<?php echo e($loop->index); ?>"><?php echo e($skillLevel); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Free Shipping & On Sale (All Categories) -->
            <div class="filter-section">
                <div class="filter-section-title">خيارات إضافية</div>
                <div class="filter-option">
                    <input type="checkbox" id="freeShipping" onchange="applyFilters()">
                    <label for="freeShipping">شحن مجاني</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="onSale" onchange="applyFilters()">
                    <label for="onSale">عروض خاصة</label>
                </div>
            </div>
        </div>



    <!-- Floating Product View -->
    <div class="product-modal-backdrop" id="productModalBackdrop" onclick="closeFloatingView()"></div>
    <div class="product-floating-view" id="productFloatingView">
        <button class="floating-close" onclick="closeFloatingView()">×</button>
        <div class="floating-image-container">
            <img id="floatingImage" class="floating-image" src="" alt="">
        </div>
        <div class="floating-details">
            <h2 id="floatingName" class="floating-name"></h2>
            <p id="floatingDescription" class="floating-description"></p>
            <div class="floating-price-wrapper">
                <span id="floatingOldPrice" class="floating-old-price" style="display: none;"></span>
                <span id="floatingPrice" class="floating-price"></span>
            </div>
            <div id="floatingRating" class="floating-rating" style="display: none;"></div>
            <div class="floating-actions">
                <button id="floatingCartBtn" class="floating-btn floating-btn-cart">
                    <i class="fas fa-shopping-cart"></i> أضف للسلة
                </button>
                <button id="floatingShareBtn" class="floating-btn floating-btn-share">
                    <i class="fas fa-share-alt"></i> مشاركة
                </button>
            </div>
            <a href="#" class="floating-view-details" onclick="viewAllDetails(event)">عرض جميع التفاصيل</a>
        </div>
    </div>

    <script>
        // Load saved theme and cart count
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if(savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
            }
            loadCartCount();
        });

        // Open floating view
        let currentProductId = null;
        
        function openFloatingView(product) {
            currentProductId = product.id;
            
            document.getElementById('floatingImage').src = product.image || 'https://via.placeholder.com/400';
            document.getElementById('floatingName').textContent = product.name;
            document.getElementById('floatingDescription').textContent = product.description || 'منتج رائع من Tulip Store';
            
            const priceEl = document.getElementById('floatingPrice');
            const oldPriceEl = document.getElementById('floatingOldPrice');
            
            if (product.discount_price) {
                oldPriceEl.textContent = '$' + parseFloat(product.price).toFixed(2);
                oldPriceEl.style.display = 'inline';
                priceEl.textContent = '$' + parseFloat(product.discount_price).toFixed(2);
            } else {
                oldPriceEl.style.display = 'none';
                priceEl.textContent = '$' + parseFloat(product.price).toFixed(2);
            }
            
            const ratingEl = document.getElementById('floatingRating');
            if (product.rating > 0) {
                ratingEl.innerHTML = '<i class="fas fa-star"></i>'.repeat(product.rating) + `<span>(${product.reviews_count})</span>`;
                ratingEl.style.display = 'flex';
            } else {
                ratingEl.style.display = 'none';
            }
            
            document.getElementById('productModalBackdrop').classList.add('active');
            document.getElementById('productFloatingView').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeFloatingView() {
            document.getElementById('productModalBackdrop').classList.remove('active');
            document.getElementById('productFloatingView').classList.remove('active');
            document.body.style.overflow = '';
            currentProductId = null;
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeFloatingView();
            }
        });
        
        document.getElementById('floatingCartBtn').addEventListener('click', () => {
            if (currentProductId) {
                addToCartFromFloating(currentProductId);
            }
        });
        
        document.getElementById('floatingShareBtn').addEventListener('click', () => {
            const productName = document.getElementById('floatingName').textContent;
            shareProductFromFloating(productName);
        });

        // Add to cart from floating view
        async function addToCartFromFloating(productId) {
            const btn = document.getElementById('floatingCartBtn');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            btn.disabled = true;
            
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    setTimeout(() => {
                        closeFloatingView();
                        if (typeof openCart === 'function') {
                            openCart();
                        }
                    }, 800);
                } else {
                    throw new Error(data.message || 'فشلت الإضافة');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> فشلت الإضافة';
                btn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        function shareProductFromFloating(productName) {
            if (navigator.share) {
                navigator.share({
                    title: productName,
                    text: `تحقق من هذا المنتج الرائع: ${productName}`,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = document.getElementById('floatingShareBtn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                });
            }
        }
        
        // View all details function
        function viewAllDetails(event) {
            event.preventDefault();
            if (currentProductId) {
                window.location.href = `/products/${currentProductId}`;
            }
        }

        // Add to cart function
        async function addToCart(event, productId) {
            event.stopPropagation();
            
            const btn = event.target.closest('.product-btn-cart');
            const originalText = btn.innerHTML;
            
            // Show loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            btn.disabled = true;
            
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = '#27ae60';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    // Trigger cart update event
                    localStorage.setItem('cart-updated', Date.now().toString());
                    window.dispatchEvent(new Event('cart-updated'));
                    
                    // Open cart sidebar
                    setTimeout(() => {
                        if (typeof openCart === 'function') {
                            openCart();
                        }
                    }, 500);
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.style.background = '';
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'فشلت الإضافة');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> فشلت الإضافة';
                btn.style.background = '#e74c3c';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        // Load cart count on page load
        async function loadCartCount() {
            try {
                const response = await fetch('/api/cart');
                const data = await response.json();
                updateCartCount(data.count || 0);
            } catch (error) {
                console.error('Error loading cart count:', error);
            }
        }

        // Share product function
        function shareProduct(event, productName) {
            event.stopPropagation();
            
            if (navigator.share) {
                navigator.share({
                    title: productName,
                    text: `تحقق من هذا المنتج الرائع: ${productName}`,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // Fallback: copy link to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = event.target.closest('.product-btn-share');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                });
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllProducts();
            }
        });

        // Amazon-style Filter Functions
        let activeFilters = {
            brands: [],
            conditions: [],
            rating: null,
            availability: [],
            minPrice: null,
            maxPrice: null
        };

        function filterByRating(rating) {
            activeFilters.rating = rating;
            applyFilters();
        }

        function applyPriceRange(min, max) {
            // Uncheck other price ranges
            document.querySelectorAll('[id^="price-"]').forEach(cb => {
                if (cb.id !== event.target.id) cb.checked = false;
            });
            
            if (event.target.checked) {
                activeFilters.minPrice = min;
                activeFilters.maxPrice = max;
                document.getElementById('minPrice').value = min;
                document.getElementById('maxPrice').value = max;
            } else {
                activeFilters.minPrice = null;
                activeFilters.maxPrice = null;
                document.getElementById('minPrice').value = '';
                document.getElementById('maxPrice').value = '';
            }
            applyFilters();
        }

        function toggleAvailabilityFilter(type) {
            const checkbox = document.getElementById(type === 'in-stock' ? 'inStock' : 'includeOutOfStock');
            
            const index = activeFilters.availability.indexOf(type);
            if (checkbox.checked && index === -1) {
                activeFilters.availability.push(type);
            } else if (!checkbox.checked && index > -1) {
                activeFilters.availability.splice(index, 1);
            }
            applyFilters();
        }

        function toggleBrandExpand() {
            // TODO: Implement expand/collapse for brands
            alert('عرض جميع العلامات التجارية');
        }

        async function applyFilters() {
            // Get price values
            const minPriceInput = document.getElementById('minPrice');
            const maxPriceInput = document.getElementById('maxPrice');
            
            if (minPriceInput && minPriceInput.value) activeFilters.minPrice = minPriceInput.value;
            if (maxPriceInput && maxPriceInput.value) activeFilters.maxPrice = maxPriceInput.value;
            
            // Collect all checked filters
            activeFilters.brands = Array.from(document.querySelectorAll('[id^="brand-"]:checked')).map(el => el.value);
            activeFilters.conditions = Array.from(document.querySelectorAll('[id^="condition-"]:checked')).map(el => el.value);
            
            // Category-specific filters
            const colors = Array.from(document.querySelectorAll('[id^="color-"]:checked')).map(el => el.value);
            const sizes = Array.from(document.querySelectorAll('[id^="size-"]:checked')).map(el => el.value);
            const materials = Array.from(document.querySelectorAll('[id^="material-"]:checked')).map(el => el.value);
            const fits = Array.from(document.querySelectorAll('[id^="fit-"]:checked')).map(el => el.value);
            const patterns = Array.from(document.querySelectorAll('[id^="pattern-"]:checked')).map(el => el.value);
            const shoeSizes = Array.from(document.querySelectorAll('[id^="shoe-size-"]:checked')).map(el => el.value);
            const shoeTypes = Array.from(document.querySelectorAll('[id^="shoe-type-"]:checked')).map(el => el.value);
            const screenSizes = Array.from(document.querySelectorAll('[id^="screen-"]:checked')).map(el => el.value);
            const storages = Array.from(document.querySelectorAll('[id^="storage-"]:checked')).map(el => el.value);
            const rams = Array.from(document.querySelectorAll('[id^="ram-"]:checked')).map(el => el.value);
            const processors = Array.from(document.querySelectorAll('[id^="processor-"]:checked')).map(el => el.value);
            const authors = Array.from(document.querySelectorAll('[id^="author-"]:checked')).map(el => el.value);
            const publishers = Array.from(document.querySelectorAll('[id^="publisher-"]:checked')).map(el => el.value);
            const languages = Array.from(document.querySelectorAll('[id^="language-"]:checked')).map(el => el.value);
            const formats = Array.from(document.querySelectorAll('[id^="format-"]:checked')).map(el => el.value);
            const genres = Array.from(document.querySelectorAll('[id^="genre-"]:checked')).map(el => el.value);
            const ageRanges = Array.from(document.querySelectorAll('[id^="age-"]:checked')).map(el => el.value);
            const toyTypes = Array.from(document.querySelectorAll('[id^="toy-type-"]:checked')).map(el => el.value);
            const rooms = Array.from(document.querySelectorAll('[id^="room-"]:checked')).map(el => el.value);
            const capacities = Array.from(document.querySelectorAll('[id^="capacity-"]:checked')).map(el => el.value);
            const sportTypes = Array.from(document.querySelectorAll('[id^="sport-"]:checked')).map(el => el.value);
            const skillLevels = Array.from(document.querySelectorAll('[id^="skill-"]:checked')).map(el => el.value);
            
            // Additional options
            const freeShipping = document.getElementById('freeShipping')?.checked;
            const onSale = document.getElementById('onSale')?.checked;
            
            // Build query string
            const params = new URLSearchParams();
            
            if (activeFilters.minPrice) params.append('min_price', activeFilters.minPrice);
            if (activeFilters.maxPrice) params.append('max_price', activeFilters.maxPrice);
            if (activeFilters.brands.length) params.append('brands', activeFilters.brands.join(','));
            if (activeFilters.conditions.length) params.append('conditions', activeFilters.conditions.join(','));
            if (activeFilters.rating) params.append('rating', activeFilters.rating);
            if (activeFilters.availability.length) params.append('availability', activeFilters.availability.join(','));
            
            // Add category-specific params
            if (colors.length) params.append('colors', colors.join(','));
            if (sizes.length) params.append('sizes', sizes.join(','));
            if (materials.length) params.append('materials', materials.join(','));
            if (fits.length) params.append('fits', fits.join(','));
            if (patterns.length) params.append('patterns', patterns.join(','));
            if (shoeSizes.length) params.append('shoe_sizes', shoeSizes.join(','));
            if (shoeTypes.length) params.append('shoe_types', shoeTypes.join(','));
            if (screenSizes.length) params.append('screen_sizes', screenSizes.join(','));
            if (storages.length) params.append('storages', storages.join(','));
            if (rams.length) params.append('rams', rams.join(','));
            if (processors.length) params.append('processors', processors.join(','));
            if (authors.length) params.append('authors', authors.join(','));
            if (publishers.length) params.append('publishers', publishers.join(','));
            if (languages.length) params.append('languages', languages.join(','));
            if (formats.length) params.append('formats', formats.join(','));
            if (genres.length) params.append('genres', genres.join(','));
            if (ageRanges.length) params.append('age_ranges', ageRanges.join(','));
            if (toyTypes.length) params.append('toy_types', toyTypes.join(','));
            if (rooms.length) params.append('rooms', rooms.join(','));
            if (capacities.length) params.append('capacities', capacities.join(','));
            if (sportTypes.length) params.append('sport_types', sportTypes.join(','));
            if (skillLevels.length) params.append('skill_levels', skillLevels.join(','));
            if (freeShipping) params.append('free_shipping', '1');
            if (onSale) params.append('on_sale', '1');
            
            // Show loading state
            const productsGrid = document.querySelector('.products-grid');
            if (productsGrid) {
                productsGrid.style.opacity = '0.5';
                productsGrid.style.pointerEvents = 'none';
            }
            
            try {
                // Fetch filtered products via AJAX
                const currentUrl = window.location.pathname;
                const response = await fetch(`${currentUrl}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                // Update products grid
                if (data.products && data.products.length > 0) {
                    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                    productsGrid.innerHTML = data.products.map(product => {
                        const isFavorite = favorites.some(p => p.id === product.id);
                        return `
                        <div class="product-card" data-product-id="${product.id}">
                            <button class="product-favorite-btn ${isFavorite ? 'active' : ''}" onclick="event.stopPropagation(); toggleProductFavorite(event, ${product.id}, ${JSON.stringify(product).replace(/"/g, '&quot;')})">
                                <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                            </button>
                            <div class="product-image-wrapper" onclick='openFloatingView(${JSON.stringify(product)})'>
                                <img src="${product.image || 'https://via.placeholder.com/250'}" alt="${product.name}" class="product-img">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">${product.name}</h3>
                                <div class="product-price-rating-wrapper">
                                    <div class="product-price-wrapper">
                                        <span class="product-price">$${parseFloat(product.discount_price || product.price).toFixed(2)}</span>
                                        ${product.discount_price ? 
                                            `<span class="product-old-price">$${parseFloat(product.price).toFixed(2)}</span>` : ''
                                        }
                                    </div>
                                    <div class="product-rating">
                                        ${'<i class="fas fa-star"></i>'.repeat(5)}
                                    </div>
                                </div>
                            </div>
                            <div class="product-card-actions">
                                <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(event, ${product.id})">
                                    إضافة للسلة
                                </button>
                                <button class="product-card-btn product-card-btn-share" onclick="event.stopPropagation(); shareProduct(event, '${product.name}')">
                                    شاركه الآن
                                </button>
                            </div>
                        </div>
                        `;
                    }).join('');
                } else {
                    productsGrid.innerHTML = `
                        <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #999;">
                            <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>لا توجد منتجات تطابق الفلاتر المحددة</p>
                        </div>
                    `;
                }
                
                // Update URL without reload
                const newUrl = params.toString() ? `${currentUrl}?${params.toString()}` : currentUrl;
                window.history.pushState({}, '', newUrl);
                
            } catch (error) {
                console.error('Error filtering products:', error);
            } finally {
                // Remove loading state
                if (productsGrid) {
                    productsGrid.style.opacity = '1';
                    productsGrid.style.pointerEvents = 'auto';
                }
            }
        }

        // Price input listeners with debounce
        let priceTimeout;
        function debouncedApplyFilters() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(applyFilters, 500);
        }
        
        document.getElementById('minPrice').addEventListener('input', debouncedApplyFilters);
        document.getElementById('maxPrice').addEventListener('input', debouncedApplyFilters);

        // Toggle product favorite
        function toggleProductFavorite(event, productId, product) {
            event.stopPropagation();
            const btn = event.currentTarget;
            const icon = btn.querySelector('i');
            
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const isFavorite = favorites.some(p => p.id === productId);
            
            // Add animation
            btn.classList.add('animating');
            setTimeout(() => btn.classList.remove('animating'), 600);
            
            if (isFavorite) {
                // Remove from favorites
                favorites = favorites.filter(p => p.id !== productId);
                btn.classList.remove('active');
                icon.classList.remove('fas');
                icon.classList.add('far');
            } else {
                // Add to favorites
                favorites.push({
                    id: product.id,
                    name: product.name,
                    price: product.discount_price || product.price,
                    image: product.image
                });
                btn.classList.add('active');
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
            
            // Update navbar count
            const countElement = document.getElementById('favoritesCount');
            if (countElement) {
                countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
            }
        }

        // Check favorites on page load
        window.addEventListener('DOMContentLoaded', function() {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            document.querySelectorAll('.product-card').forEach(card => {
                const productId = parseInt(card.dataset.productId);
                const isFavorite = favorites.some(p => p.id === productId);
                if (isFavorite) {
                    const btn = card.querySelector('.product-favorite-btn');
                    const icon = btn.querySelector('i');
                    btn.classList.add('active');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                }
            });
        });
    </script>
    </div>
</body>
</html>
<?php /**PATH D:\Tulip-Store\resources\views/category.blade.php ENDPATH**/ ?>