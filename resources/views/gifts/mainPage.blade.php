<div class="gifts-container">
        <!-- Gifts Creation Section (dynamic images from DB) -->
        <div class="premium-cards">
            <!-- Custom Box Card -->
            <div class="premium-card box-card" onclick="window.location.href='/gifts/box-arrangement'">
                <div class="card-visual">
                   
                    <img id="boxCardImage" src="/images/box.jpg" alt="Gift Box" class="card-image" loading="lazy" >
                </div>
                <div class="card-content">
                    <span class="card-tag">الأكثر طلباً</span>
                    <h3 class="card-title">تنسيق صندوق هدية</h3>
                    <p class="card-desc">صمم صندوق هدية فريد بإضافة الشوكولاتة والعطور والإكسسوارات وكل ما يحبه قلبك</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-box"></i> 4 أحجام</span>
                        <span class="feature"><i class="fas fa-gift"></i> +50 عنصر</span>
                        <span class="feature"><i class="fas fa-envelope"></i> بطاقة مجانية</span>
                    </div>
                    <button class="card-btn">
                        ابدأ التصميم
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- Custom Bouquet Card -->
            <div class="premium-card flower-card" onclick="window.location.href='/gifts/flower-bouquet'">
                <div class="card-visual">
                   
                    <img id="bouquetCardImage" src="/images/Bouquet.jpg" alt="Rose Bouquet" class="card-image" loading="lazy">
                </div>
                <div class="card-content">
                    <span class="card-tag">  دفعة ورورد جديدة يومياً</span>
                    <h3 class="card-title">تنسيق باقة ورد</h3>
                    <p class="card-desc">اختر من أجمل الزهور ونسق باقتك المثالية بالألوان والتغليف الذي تفضله</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-seedling"></i> +20 نوع زهور</span>
                        <span class="feature"><i class="fas fa-palette"></i> ألوان متعددة</span>
                        <span class="feature"><i class="fas fa-truck"></i> توصيل سريع</span>
                    </div>
                    <button class="card-btn">
                        ابدأ التصميم
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- Ready Made Card -->
            <div class="premium-card ready-card" onclick="document.getElementById('readyGifts').scrollIntoView({behavior: 'smooth'})">
                <div class="card-visual">
                  
                    <img id="readyCardImage" src="/images/ready.jpg" alt="Ready Gifts" class="card-image" loading="lazy">
                </div>
                <div class="card-content">
                    <span class="card-tag">جاهزة للتوصيل</span>
                    <h3 class="card-title">هدايا جاهزة</h3>
                    <p class="card-desc">مجموعة منتقاة بعناية من أفخم الهدايا المنسقة والجاهزة للتوصيل في نفس اليوم</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-check-circle"></i> منسقة باحتراف</span>
                        <span class="feature"><i class="fas fa-tag"></i> أسعار مميزة</span>
                        <span class="feature"><i class="fas fa-shield-alt"></i> ضمان الجودة</span>
                    </div>
                    <button class="card-btn">
                        تصفح الهدايا
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>
        

        <!-- Ready Made Gifts Section -->
        <section id="readyGifts">
            <div class="section-header">
                <span class="section-label"><i class="fas fa-star"></i> من صنعنا </span>
                <p class="section-subtitle">اختر من مجموعتنا المنسقة بعناية واستمتع بتوصيل سريع </p>
            </div>
            
            <div class="gifts-grid" id="giftsGrid"></div>

            <!-- <div class="view-all-container">
                <button class="view-all-btn" onclick="window.location.href='/store?category=gifts'">
                    عرض جميع الهدايا
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div> -->
        </section>
 </div>
    
    <script>
        function resolveMediaUrl(path) {
            const p = String(path || '').trim();
            if (!p) return '/images/birthday_card.jpeg';
            if (p.startsWith('http://') || p.startsWith('https://')) return p;
            if (p.startsWith('/')) return p;
            const cleaned = p.replace(/^storage\//, '');
            return `/storage/${cleaned}`;
        }

        async function hydrateGiftCreationSection() {
            try {
                // Not overriding manual images as requested
            } catch (e) {
                // Leave placeholders if API not available
            }
        }

        async function loadGifts() {
            const grid = document.getElementById('giftsGrid');
            if (!grid) return;

            grid.innerHTML = `
                <div style="text-align:center;color:#999;padding:2rem;grid-column:1/-1;">
                    <i class="fas fa-spinner fa-spin" style="font-size:2rem;margin-bottom:1rem;opacity:0.6;"></i>
                    <p>جاري تحميل الهدايا...</p>
                </div>
            `;

            try {
                const response = await fetch('/api/gifts?sort=featured&per_page=24');
                const data = await response.json();
                const gifts = Array.isArray(data.data) ? data.data : [];

                if (!gifts.length) {
                    grid.innerHTML = `
                        <div style="text-align:center;color:#999;padding:2rem;grid-column:1/-1;">
                            <i class="fas fa-gift" style="font-size:2rem;margin-bottom:1rem;opacity:0.4;"></i>
                            <p>لا توجد هدايا حالياً</p>
                        </div>
                    `;
                    return;
                }

                grid.innerHTML = gifts.map(gift => {
                    const image = resolveMediaUrl(gift.main_image || (gift.images && gift.images[0]) || gift.image || '') || '/images/tulip_gift.jpg';
                    const rating = Number(gift.rating ?? 0);
                    const reviews = Number(gift.reviews_count ?? 0);
                    const badgeText = gift.is_featured ? 'مميز' : '';
                    const badgeClass = gift.is_featured ? 'new' : '';
                    return `
                        <div class="gift-card" onclick="window.location.href='/gifts/${gift.id}'">
                            <div class="gift-image">
                                ${badgeText ? `<span class="gift-badge ${badgeClass}">${badgeText}</span>` : ''}
                                <img src="${image}" alt="${gift.name || ''}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'">
                            </div>
                            
                            <div class="gift-info">
                                <h3 class="gift-name">${gift.name || ''}</h3>
                                <div class="gift-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star${rating < 5 ? '-half-alt' : ''}"></i>
                                    <span>${rating.toFixed(1)} (${reviews})</span>
                                </div>
                                <div class="gift-price">
                                    <span class="price-current">${Number(gift.price || 0)} ل.س</span>
                                </div>
                                <button class="gift-add-btn" onclick="event.stopPropagation(); window.location.href='/gifts/${gift.id}'">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (e) {
                grid.innerHTML = `
                    <div style="text-align:center;color:#999;padding:2rem;grid-column:1/-1;">
                        <i class="fas fa-exclamation-triangle" style="font-size:2rem;margin-bottom:1rem;opacity:0.4;"></i>
                        <p>تعذر تحميل الهدايا</p>
                    </div>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            hydrateGiftCreationSection();
            loadGifts();
        });
    </script>
    