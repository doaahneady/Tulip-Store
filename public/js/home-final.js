// ============================================
// TULIP STORE - FINAL VERSION
// ============================================

// (moved to top)

// Activity Tracker with Database Integration
class DatabaseActivityTracker {
  constructor() {
    this.csrfToken =
      document.querySelector('meta[name="csrf-token"]')?.content || "";
  }

  async track(activityType, data = {}) {
    try {
      await fetch("/api/activity/track", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": this.csrfToken,
        },
        body: JSON.stringify({
          activity_type: activityType,
          ...data,
        }),
      });
    } catch (error) {
      console.error("Error tracking activity:", error);
    }
  }

  async getRecommendations() {
    try {
      const response = await fetch("/api/activity/recommendations");
      if (!response.ok) throw new Error("Failed to get recommendations");
      return await response.json();
    } catch (error) {
      console.error("Error getting recommendations:", error);
      return {
        personalized_products: [],
        recommended_categories: {},
        search_suggestions: [],
      };
    }
  }

  trackProductView(productId, categoryId) {
    this.track("view", { product_id: productId, category_id: categoryId });
  }

  trackSearch(query) {
    if (query && query.length > 2) {
      this.track("search", { search_query: query });
    }
  }

  trackCartAdd(productId) {
    this.track("cart_add", { product_id: productId });
  }

  trackPurchase(productId) {
    this.track("purchase", { product_id: productId });
  }
}

// Initialize tracker
const activityTracker = new DatabaseActivityTracker();

// ============================================
// MODERN SLIDER
// ============================================

let sliderData = [];

let currentModernSlide = 0;

const FALLBACK_SLIDER_DATA = [
  {
    image: "public\images\banner1.jpg",
    title: "أرسل ابتسامتك أينما كنت",
    subtitle: "تسوق معنا أفضل المنتجات والعروض",
  },
  {
    image: "/images/banner2.jpg",
    title: "عروض وخصومات",
    subtitle: "اكتشف عروضنا المميزة وتوفير أكبر على مشترياتك",
  },
  {
    image: "/images/banner3.jpg",
    title: "و حديثاً",
    subtitle: "اكتشف أحدث المنتجات في متجرنا",
  },
];

async function loadSliderData() {
  try {
    const response = await fetch("/api/homepage/slides", {
      headers: { Accept: "application/json" },
    });
    if (response.ok) {
      const data = await response.json();
      if (
        data &&
        data.success &&
        Array.isArray(data.slides) &&
        data.slides.length
      ) {
        return data.slides;
      }
    }
  } catch (error) {
    console.error("Error loading slider data:", error);
  }

  return FALLBACK_SLIDER_DATA;
}

function initializeModernSlider() {
  const container = document.getElementById("modernSlider");
  const dotsContainer = document.getElementById("modernSliderDots");

  if (!container || !dotsContainer) return;
  if (!Array.isArray(sliderData) || sliderData.length === 0) return;

  container.innerHTML = "";
  dotsContainer.innerHTML = "";
  currentModernSlide = 0;

  // Create slides
  sliderData.forEach((slide, index) => {
    const slideEl = document.createElement("div");
    slideEl.className = "modern-slide";
    slideEl.innerHTML = `
            <a href="${slide.link || "#"}" style="display:block; width:100%; height:100%; text-decoration:none;">
                <img src="${slide.image}" alt="${slide.title}">
                <div class="modern-slide-content">
                    <h2 style="font-family:'El Messiri', sans-serif; font-size:2rem; font-weight:900; margin:0 0 0.8rem 0;">${slide.title}</h2>
                    <p style="font-family:'El Messiri', sans-serif; font-size:1.1rem; margin:0;">${slide.subtitle}</p>
                </div>
            </a>
        `;
    container.appendChild(slideEl);

    // Create dot
    const dot = document.createElement("button");
    dot.onclick = () => goToModernSlide(index);
    dot.style.cssText =
      "width:12px; height:12px; border-radius:50%; border:2px solid #2a7080; background:transparent; cursor:pointer; transition:all 0.3s;";
    dotsContainer.appendChild(dot);
  });

  updateModernSliderPositions();

  // Auto-advance
  setInterval(() => changeModernSlide(1), 6000);
}

function updateModernSliderPositions() {
  const slides = document.querySelectorAll(".modern-slide");
  const dots = document.querySelectorAll("#modernSliderDots button");
  const total = slides.length;

  slides.forEach((slide, index) => {
    const diff = (index - currentModernSlide + total) % total;

    if (diff === 0) {
      slide.className = "modern-slide active";
    } else if (diff === 1) {
      slide.className = "modern-slide next";
    } else if (diff === total - 1) {
      slide.className = "modern-slide prev";
    } else {
      slide.className = "modern-slide hidden";
    }
  });

  dots.forEach((dot, index) => {
    if (index === currentModernSlide) {
      dot.style.background = "#2a7080";
      dot.style.transform = "scale(1.3)";
    } else {
      dot.style.background = "transparent";
      dot.style.transform = "scale(1)";
    }
  });
}

function changeModernSlide(direction) {
  const total = sliderData.length;
  currentModernSlide = (currentModernSlide + direction + total) % total;
  updateModernSliderPositions();
}

function goToModernSlide(index) {
  currentModernSlide = index;
  updateModernSliderPositions();
}

// ============================================
// CATEGORIES SCROLL FUNCTIONS
// ============================================

function scrollCategoriesLeft() {
  document
    .getElementById("categoriesScroll")
    .scrollBy({ left: -250, behavior: "smooth" });
}

function scrollCategoriesRight() {
  document
    .getElementById("categoriesScroll")
    .scrollBy({ left: 250, behavior: "smooth" });
}

// Category icons mapping - Comprehensive Arabic categories
const categoryIcons = {
  // Flowers & Plants - Using valid Font Awesome icons
  زهور: "fa-seedling",
  زهرة: "fa-seedling",
  الزهور: "fa-seedling",
  "الزهور الطازة": "fa-seedling",
  طازة: "fa-seedling",
  ورد: "fa-heart",
  وردة: "fa-heart",
  "سلل ورد": "fa-heart",
  نباتات: "fa-leaf",
  نبات: "fa-leaf",

  // Gifts & Occasions
  هدايا: "fa-gift",
  هدية: "fa-gift",
  الهدايا: "fa-gift",
  "الهدايا والمفاجآت": "fa-gift",
  مفاجآت: "fa-gift",
  مناسبات: "fa-calendar-days",
  مناسبة: "fa-calendar-days",

  // Children
  أطفال: "fa-baby",
  طفل: "fa-baby",
  اطفال: "fa-baby",
  "هدايا أطفال": "fa-baby",

  // Food & Sweets
  شوكولاتة: "fa-cookie-bite",
  شوكولا: "fa-cookie-bite",
  الشوكولاتة: "fa-cookie-bite",
  "الشوكولاتة والحلويات": "fa-cookie-bite",
  حلويات: "fa-cake-candles",
  حلوى: "fa-cake-candles",
  فواكه: "fa-apple-whole",
  فاكهة: "fa-apple-whole",
  "سلال فواكه": "fa-apple-whole",
  سلال: "fa-basket-shopping",
  سلة: "fa-basket-shopping",

  // Decor & Home
  ديكور: "fa-couch",
  البالونات: "fa-circle",
  "البالونات والديكور": "fa-circle",
  بالونات: "fa-circle",
  تطبيقات: "fa-home",
  منزل: "fa-house",
  بيت: "fa-house",

  // Jewelry & Accessories
  مجوهرات: "fa-gem",
  مجوهر: "fa-gem",
  إكسسوارات: "fa-ring",
  اكسسوار: "fa-ring",
  خواتم: "fa-ring",
  خاتم: "fa-ring",

  // Perfumes
  عطور: "fa-spray-can-sparkles",
  عطر: "fa-spray-can-sparkles",

  // Fashion
  ملابس: "fa-shirt",
  ملبس: "fa-shirt",
  أزياء: "fa-shirt",
  موضة: "fa-shirt",

  // Electronics
  إلكترونيات: "fa-laptop",
  الكترونيات: "fa-laptop",
  تقنية: "fa-microchip",
  كمبيوتر: "fa-computer",

  // Books & Education
  كتب: "fa-book",
  كتاب: "fa-book",
  قراءة: "fa-book-open",

  // Games & Entertainment
  ألعاب: "fa-gamepad",
  لعبة: "fa-gamepad",
  العاب: "fa-gamepad",

  // Sports
  رياضة: "fa-dumbbell",
  رياضي: "fa-dumbbell",

  // Beauty & Cosmetics
  جمال: "fa-spa",
  تجميل: "fa-spa",
  عطور: "fa-spray-can",
  عطر: "fa-spray-can",
  مكياج: "fa-palette",

  // Watches & Time
  ساعات: "fa-clock",
  ساعة: "fa-clock",

  // Bags & Luggage
  حقائب: "fa-bag-shopping",
  حقيبة: "fa-bag-shopping",
  شنط: "fa-bag-shopping",

  // Shoes
  أحذية: "fa-shoe-prints",
  حذاء: "fa-shoe-prints",
  احذية: "fa-shoe-prints",

  // Glasses
  نظارات: "fa-glasses",
  نظارة: "fa-glasses",

  // Default
  default: "fa-box",
};

function getCategoryIcon(categoryName) {
  const name = categoryName.trim();
  const nameLower = name.toLowerCase();

  // Try exact match first (case insensitive)
  for (const [key, icon] of Object.entries(categoryIcons)) {
    if (key.toLowerCase() === nameLower) {
      return icon;
    }
  }

  // Try partial match - check if category name contains any keyword
  for (const [key, icon] of Object.entries(categoryIcons)) {
    const keyLower = key.toLowerCase();
    if (nameLower.includes(keyLower) || keyLower.includes(nameLower)) {
      return icon;
    }
  }

  // Specific keyword matching for common categories
  if (
    nameLower.includes("زهور") ||
    nameLower.includes("زهر") ||
    nameLower.includes("طازة")
  ) {
    return "fa-seedling";
  }
  if (nameLower.includes("ورد") || nameLower.includes("سلل")) {
    return "fa-heart";
  }
  if (
    nameLower.includes("هدايا") ||
    nameLower.includes("هدية") ||
    nameLower.includes("مفاج")
  ) {
    return "fa-gift";
  }
  if (
    nameLower.includes("أطفال") ||
    nameLower.includes("اطفال") ||
    nameLower.includes("طفل")
  ) {
    return "fa-baby";
  }
  if (nameLower.includes("شوكولا") || nameLower.includes("حلو")) {
    return "fa-cookie-bite";
  }
  if (nameLower.includes("فواكه") || nameLower.includes("فاكه")) {
    return "fa-apple-whole";
  }
  if (nameLower.includes("بالون") || nameLower.includes("ديكور")) {
    return "fa-circle";
  }
  if (nameLower.includes("عطر") || nameLower.includes("عطور")) {
    return "fa-spray-can-sparkles";
  }

  return categoryIcons.default;
}

// ============================================
// PRODUCT CARD FUNCTIONS - SMALLER BUTTON, USD, CART ICON
// ============================================

function escapeHtml(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function getProductImageUrl(p) {
  const img =
    p?.primary_image_url ||
    p?.image ||
    (Array.isArray(p?.images) ? p.images[0] : null) ||
    "";
  const s = String(img || "").trim();
  if (!s) return "/images/gift-placeholder.jpg";
  if (s.startsWith("http://") || s.startsWith("https://")) return s;
  if (s.startsWith("/")) return s;
  return `/storage/${s.replace(/^storage\//, "")}`;
}

function createProductCard(p) {
  const favorites = JSON.parse(localStorage.getItem("favorites") || "[]");
  const isFavorite = favorites.some((x) => x.id === p.id);
  const stockQty = parseInt(p?.stock_quantity ?? 0);
  const trackInv = !!p?.track_inventory;
  const isOutOfStock = trackInv && stockQty <= 0;
  const stockLabel = trackInv
    ? isOutOfStock
      ? "غير متوفر"
      : `متوفر: ${stockQty}`
    : "متوفر";

  const price = parseFloat(p.discount_price || p.price || 0);
  const oldPrice = parseFloat(p.price || 0);
  const safeName = escapeHtml(p.name || "");
  const imgUrl = getProductImageUrl(p);

  return `
        <div class="product-card" data-product-id="${p.id}" onclick="window.location.href='/products/${p.id}'">
            <button class="product-favorite-btn ${isFavorite ? "active" : ""}" onclick="event.stopPropagation(); toggleProductFavorite(event, ${p.id})">
                <i class="${isFavorite ? "fas" : "far"} fa-heart"></i>
            </button>
            <div style="position:absolute; top: 14px; left: 14px; z-index: 3; background: ${isOutOfStock ? "#fee2e2" : "#dcfce7"}; color: ${isOutOfStock ? "#b91c1c" : "#166534"}; padding: 6px 10px; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
                ${stockLabel}
            </div>
            <div class="product-image-wrapper">
                <img src="${imgUrl}" alt="${safeName}" class="product-img" loading="lazy" onerror="this.src='/images/gift-placeholder.jpg'">
            </div>
            <div class="product-info">
                <h3 class="product-name">${safeName}</h3>
                <div class="product-price-rating-wrapper">
                    <div class="product-price-wrapper">
                        <span class="product-price">$${price.toFixed(2)}</span>
                        ${p.discount_price ? `<span class="product-old-price">$${oldPrice.toFixed(2)}</span>` : ""}
                    </div>
                </div>
            </div>
            <div class="product-card-actions">
                <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(${p.id}, this)" data-product-id="${p.id}" ${isOutOfStock ? 'disabled data-tooltip="لا يتوفر هذا المنتج في المخزن"' : ""} style="background: transparent; border: none; box-shadow: none; width: auto; height: auto; padding: 0; display: inline-flex; align-items: center; justify-content: center; ${isOutOfStock ? "opacity: 0.5; cursor: not-allowed;" : "cursor: pointer;"}">
                    <i class="fas fa-shopping-cart" style="font-size: 1.4rem; color: #ff6b35;"></i>
                </button>
            </div>
        </div>
    `;
}

function createDiscountCard(p) {
  return createProductCard(p);
}

// ============================================
// LOAD CATEGORIES - ICON BASED
// ============================================

async function loadCategories() {
  try {
    const response = await fetch("/api/categories", {
      headers: { Accept: "application/json" },
    });
    if (!response.ok) throw new Error("Failed to load categories");
    const json = await response.json();
    const categories = Array.isArray(json) ? json : json.data || [];
    const grid = document.getElementById("categoriesGrid");
    if (!grid) return;

    if (!categories.length) {
      grid.innerHTML = `
                <p style="text-align:center;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                    لا توجد تصنيفات متاحة حالياً.
                </p>
            `;
      return;
    }

    const categoryColors = [
      "#ff6b35",
      "#2a7080",
      "#9b59b6",
      "#e74c3c",
      "#3498db",
      "#f39c12",
      "#1abc9c",
      "#e91e63",
      "#00bcd4",
      "#ff5722",
    ];

    grid.innerHTML = categories
      .map((c, index) => {
        let icon = getCategoryIcon(c.name);
        const fallbackIcons = [
          "fa-gift",
          "fa-gem",
          "fa-tshirt",
          "fa-laptop",
          "fa-book",
          "fa-gamepad",
          "fa-home",
          "fa-spa",
          "fa-bag-shopping",
          "fa-clock",
        ];
        if (icon === "fa-box") {
          icon = fallbackIcons[index % fallbackIcons.length];
        }

        const iconColor = categoryColors[index % categoryColors.length];

        return `
                <div class="category-card" onclick="window.location.href='/category/${c.slug}'" style="--cat-color:${iconColor};">
                    <div class="category-icon">
                        <i class="fas ${icon}"></i>
                    </div>
                    <p class="category-name">${c.name}</p>
                </div>
            `;
      })
      .join("");
  } catch (error) {
    console.error("Error loading categories:", error);
    const grid = document.getElementById("categoriesGrid");
    if (!grid) return;
    grid.innerHTML = `
            <p style="text-align:center;color:#c00;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                تعذر تحميل التصنيفات حالياً. يرجى المحاولة لاحقاً.
            </p>
        `;
  }
}

// ============================================
// LOAD PRODUCTS WITH PERSONALIZATION
// ============================================

async function loadPersonalizedProducts() {
  try {
    const container = document.getElementById("personalizedProducts");
    if (!container) return;
    // Keep server-rendered new cards (mart-style); do not replace with old card markup
    if (container.querySelector(".product-card .product-body")) return;

    const recommendations = await activityTracker.getRecommendations();
    const personalizedProducts = recommendations.personalized_products || [];
    window.__productsById = window.__productsById || {};

    let products = personalizedProducts;

    if (products.length === 0) {
      const response = await fetch(`${API_BASE}/products`, {
        headers: { Accept: "application/json" },
      });
      if (!response.ok) throw new Error("Failed to load products");
      const data = await response.json();
      const productsRaw = Array.isArray(data) ? data : data.data || [];
      products = productsRaw.slice(0, 5);
    }

    if (!products.length) {
      container.innerHTML = `
                <p style="text-align:center;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                    لا توجد منتجات متاحة حالياً.
                </p>
            `;
      return;
    }

    const list = products.slice(0, 5);
    list.forEach((p) => {
      window.__productsById[p.id] = p;
    });
    container.innerHTML = list
      .map((p) => createProductCard(p, "#fff"))
      .join("");
  } catch (error) {
    console.error("Error loading personalized products:", error);
    const container = document.getElementById("personalizedProducts");
    if (!container) return;
    container.innerHTML = `
            <p style="text-align:center;color:#c00;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                تعذر تحميل المنتجات المخصصة حالياً. يرجى المحاولة لاحقاً.
            </p>
        `;
  }
}

async function loadTrendingProducts() {
  try {
    const container = document.getElementById("trendingProducts");
    if (!container) return;
    // Keep server-rendered new cards (mart-style); do not replace with old card markup
    if (container.querySelector(".product-card .product-body")) return;

    const response = await fetch(`${API_BASE}/products`, {
      headers: { Accept: "application/json" },
    });
    if (!response.ok) throw new Error("Failed to load products");
    const data = await response.json();
    const productsRaw = Array.isArray(data) ? data : data.data || [];
    const products = productsRaw.slice(0, 5);

    if (!products.length) {
      container.innerHTML = `
                <p style="text-align:center;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                    لا توجد منتجات متاحة حالياً.
                </p>
            `;
      return;
    }

    window.__productsById = window.__productsById || {};
    products.forEach((p) => {
      window.__productsById[p.id] = p;
    });
    container.innerHTML = products
      .map((p) => createProductCard(p, "#fff"))
      .join("");
  } catch (error) {
    console.error("Error loading trending products:", error);
    const container = document.getElementById("trendingProducts");
    if (!container) return;
    container.innerHTML = `
            <p style="text-align:center;color:#c00;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                تعذر تحميل المنتجات الأكثر رواجاً حالياً. يرجى المحاولة لاحقاً.
            </p>
        `;
  }
}

async function loadFlashDeals() {
  try {
    const response = await fetch(`${API_BASE}/products`, {
      headers: { Accept: "application/json" },
    });
    if (!response.ok) throw new Error("Failed to load products");
    const data = await response.json();
    const productsRaw = Array.isArray(data) ? data : data.data || [];
    const products = productsRaw.slice(5, 10);
    const container = document.getElementById("flashDeals");
    if (!container) return;

    if (!products.length) {
      container.innerHTML = `
                <p style="text-align:center;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                    لا توجد عروض فلاش متاحة حالياً.
                </p>
            `;
      return;
    }

    window.__productsById = window.__productsById || {};
    products.forEach((p) => {
      window.__productsById[p.id] = p;
    });
    container.innerHTML = products.map((p) => createDiscountCard(p)).join("");
    startFlashTimer();
  } catch (error) {
    console.error("Error loading flash deals:", error);
    const container = document.getElementById("flashDeals");
    if (!container) return;
    container.innerHTML = `
            <p style="text-align:center;color:#c00;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                تعذر تحميل عروض الفلاش حالياً. يرجى المحاولة لاحقاً.
            </p>
        `;
  }
}

async function loadDiscountItems() {
  try {
    const response = await fetch(`${API_BASE}/products`, {
      headers: { Accept: "application/json" },
    });
    if (!response.ok) throw new Error("Failed to load products");
    const data = await response.json();
    const productsRaw = Array.isArray(data) ? data : data.data || [];
    const products = productsRaw.slice(10, 15);
    const container = document.getElementById("discountItems");
    if (!container) return;

    if (!products.length) {
      container.innerHTML = `
                <p style="text-align:center;color:#666;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                    لا توجد عروض خصم متاحة حالياً.
                </p>
            `;
      return;
    }

    window.__productsById = window.__productsById || {};
    products.forEach((p) => {
      window.__productsById[p.id] = p;
    });
    container.innerHTML = products.map(createDiscountCard).join("");
  } catch (error) {
    console.error("Error loading discount items:", error);
    const container = document.getElementById("discountItems");
    if (!container) return;
    container.innerHTML = `
            <p style="text-align:center;color:#c00;font-family:'El Messiri',sans-serif;font-size:0.95rem;margin:1.5rem 0;">
                تعذر تحميل عروض الخصم حالياً. يرجى المحاولة لاحقاً.
            </p>
        `;
  }
}

// ============================================
// FLASH TIMER - PERSISTENT ACROSS PAGE REFRESHES
// ============================================

const FLASH_DEAL_DURATION = 24 * 60 * 60 * 1000; // 24 hours in milliseconds

function startFlashTimer() {
  const timerEl = document.getElementById("flashTimer");
  if (!timerEl) return;

  // Get or set persistent end time
  let endTime = localStorage.getItem("flashDealEndTime");

  // If no end time or expired, set new one
  if (!endTime || parseInt(endTime) < Date.now()) {
    endTime = Date.now() + FLASH_DEAL_DURATION;
    localStorage.setItem("flashDealEndTime", endTime.toString());
  } else {
    endTime = parseInt(endTime);
  }

  function updateTimer() {
    const now = Date.now();
    let remaining = endTime - now;

    // If timer expired, reset for next 24 hours
    if (remaining <= 0) {
      endTime = Date.now() + FLASH_DEAL_DURATION;
      localStorage.setItem("flashDealEndTime", endTime.toString());
      remaining = FLASH_DEAL_DURATION;
    }

    const hours = Math.floor(remaining / (1000 * 60 * 60));
    const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

    const timeStr = `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

    const display = timerEl.querySelector("div");
    if (display) {
      display.textContent = timeStr;
    }

    setTimeout(updateTimer, 1000);
  }

  updateTimer();
}

// ============================================
// CART ICON ANIMATION
// ============================================

function animateCartIcon() {
  const cartIcon =
    document.querySelector(".cart-icon") ||
    document.querySelector('[href="/cart"]') ||
    document.querySelector(".navbar-cart");

  if (cartIcon) {
    // Add bounce animation
    cartIcon.style.transition = "transform 0.1s ease";
    cartIcon.style.transform = "scale(1.3) rotate(-10deg)";

    setTimeout(() => {
      cartIcon.style.transform = "scale(1.2) rotate(10deg)";
    }, 100);

    setTimeout(() => {
      cartIcon.style.transform = "scale(1.15) rotate(-5deg)";
    }, 200);

    setTimeout(() => {
      cartIcon.style.transform = "scale(1.1) rotate(5deg)";
    }, 300);

    setTimeout(() => {
      cartIcon.style.transform = "scale(1) rotate(0deg)";
    }, 400);
  }

  // Also animate the cart badge
  const cartBadge =
    document.querySelector(".cart-badge") ||
    document.querySelector(".cart-count");
  if (cartBadge) {
    cartBadge.style.transition = "transform 0.3s ease";
    cartBadge.style.transform = "scale(1.5)";
    setTimeout(() => {
      cartBadge.style.transform = "scale(1)";
    }, 300);
  }
}

// Show toast notification
function showToast(message, duration = 2500) {
  // Remove existing toast
  const existingToast = document.querySelector(".toast-notification");
  if (existingToast) {
    existingToast.remove();
  }

  const toast = document.createElement("div");
  toast.className = "toast-notification";
  toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: linear-gradient(135deg, #2a7080, #1a5060);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        font-weight: 500;
        font-family: 'El Messiri', sans-serif;
    `;
  toast.innerHTML = `
        <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #4CAF50;"></i>
        <span>${message}</span>
    `;
  document.body.appendChild(toast);

  // Trigger animation
  setTimeout(() => {
    toast.style.transform = "translateX(-50%) translateY(0)";
    toast.style.opacity = "1";
  }, 10);

  // Remove after duration
  setTimeout(() => {
    toast.style.transform = "translateX(-50%) translateY(100px)";
    toast.style.opacity = "0";
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

// Make functions globally available
window.animateCartIcon = animateCartIcon;
window.showToast = showToast;

// ============================================
// ADD TO CART
// ============================================

async function addToCart(productId, buttonElement) {
  try {
    const p = (window.__productsById || {})[productId];
    const stockQty = parseInt(p?.stock_quantity ?? 0);
    const trackInv = !!p?.track_inventory;
    if (p && trackInv && stockQty <= 0) {
      if (window.showToast) {
        window.showToast("هذا المنتج غير متوفر في المخزون");
      }
      if (buttonElement) {
        buttonElement.style.background = "#e74c3c";
        buttonElement.innerHTML = '<i class="fas fa-times"></i>';
        setTimeout(() => {
          buttonElement.style.background = "#ff6b35";
          buttonElement.innerHTML = '<i class="fas fa-shopping-cart"></i>';
        }, 1500);
      }
      return;
    }
    // Track activity
    activityTracker.trackCartAdd(productId);

    // Change button to green with tick - smooth transition
    if (buttonElement) {
      buttonElement.classList.add("added");
      buttonElement.style.transition = "all 0.3s ease";
      buttonElement.style.background = "#28a745";
      buttonElement.style.transform = "scale(1.1)";
      buttonElement.innerHTML = '<i class="fas fa-check"></i>';
      buttonElement.disabled = true;
    }

    const response = await fetch("/api/cart/add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN":
          document.querySelector('meta[name="csrf-token"]')?.content || "",
      },
      body: JSON.stringify({ product_id: productId, quantity: 1 }),
    });
    const data = await response.json();
    if (data.success && window.updateCartCount) {
      window.updateCartCount(data.cart_count || data.count || 0);

      // Mart delivery warning if applicable
      const isMart =
        p &&
        (p.store_id === 1 ||
          (p.store &&
            p.store.name &&
            p.store.name.toLowerCase().includes("mart")));
      if (isMart && window.showToast) {
        setTimeout(() => {
          window.showToast(
            "تنبيه: منتجات Mart تتوفر للتوصيل فقط إلى (السويداء، عتيل، قنوات)",
            4000,
          );
        }, 1500);
      }
    }

    // Reset button after 2 seconds with smooth transition
    if (buttonElement) {
      setTimeout(() => {
        buttonElement.classList.remove("added");
        buttonElement.style.background = "#ff6b35";
        buttonElement.style.transform = "scale(1)";
        buttonElement.innerHTML = '<i class="fas fa-shopping-cart"></i>';
        buttonElement.disabled = false;
      }, 2000);
    }
  } catch (error) {
    console.error("Error adding to cart:", error);
    if (buttonElement) {
      buttonElement.style.background = "#dc3545";
      buttonElement.innerHTML = '<i class="fas fa-times"></i>';
      setTimeout(() => {
        buttonElement.classList.remove("added");
        buttonElement.style.background = "#ff6b35";
        buttonElement.innerHTML = '<i class="fas fa-shopping-cart"></i>';
        buttonElement.disabled = false;
      }, 2000);
    }
  }
}

// ============================================
// INITIALIZE ON PAGE LOAD
// ============================================

window.addEventListener("DOMContentLoaded", async () => {
  sliderData = await loadSliderData();
  if (Array.isArray(sliderData) && sliderData.length < 3) {
    const filled = sliderData.slice();
    for (let i = filled.length; i < 3; i++) {
      filled.push(FALLBACK_SLIDER_DATA[i] || FALLBACK_SLIDER_DATA[0]);
    }
    sliderData = filled;
  }
  initializeModernSlider();

  // Load all sections
  loadCategories();
  loadPersonalizedProducts();
  loadTrendingProducts();
  loadFlashDeals();
  loadDiscountItems();
});

// Track product clicks
document.addEventListener("click", (e) => {
  const productCard = e.target.closest('[onclick*="/products/"]');
  if (productCard) {
    const match = productCard
      .getAttribute("onclick")
      .match(/\/products\/(\d+)/);
    if (match) {
      activityTracker.trackProductView(parseInt(match[1]));
    }
  }
});

// ============================================
// CART FUNCTIONALITY WITH GREEN CHECK ICON
// ============================================

const API_BASE = window.location.origin + "/api";

// Add to cart with green check icon - RESETS AFTER 2 SECONDS
async function addToCart(productId, button) {
  // Show loading
  const p = (window.__productsById || {})[productId];
  const stockQty = parseInt(p?.stock_quantity ?? 0);
  const trackInv = !!p?.track_inventory;
  if (p && trackInv && stockQty <= 0) {
    if (window.showToast) {
      window.showToast("هذا المنتج غير متوفر في المخزون");
    } else {
      alert("هذا المنتج غير متوفر في المخزون");
    }
    return;
  }
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
  button.disabled = true;

  try {
    const response = await fetch(`${API_BASE}/cart/add`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN":
          document.querySelector('meta[name="csrf-token"]')?.content || "",
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: 1,
      }),
    });

    const data = await response.json();

    if (data.success) {
      // Update localStorage
      const cartItems = JSON.parse(localStorage.getItem("cart_items") || "[]");
      if (!cartItems.includes(productId)) {
        cartItems.push(productId);
        localStorage.setItem("cart_items", JSON.stringify(cartItems));
      }

      // Change button to green with checkmark - prominent visual feedback
      button.style.transition = "all 0.3s ease";
      button.style.background = "#10b981"; // Bright green
      button.style.boxShadow = "0 4px 12px rgba(16, 185, 129, 0.4)";
      button.style.transform = "scale(1.15)";
      button.innerHTML =
        '<i class="fas fa-check" style="font-size:1.4rem;"></i>';
      button.classList.add("added");

      // Animate cart icon in navbar
      animateCartIcon();

      // Show toast notification
      showToast("تم إضافة المنتج إلى السلة ✓");

      // Update cart count in navbar
      if (window.updateCartCount) {
        window.updateCartCount(data.cart_count || data.count || 0);
      }

      // Track activity
      if (activityTracker) {
        activityTracker.trackCartAdd(productId);
      }

      // Reset button back to cart icon after 2 seconds (new cards use "أضف")
      setTimeout(() => {
        button.classList.remove("added");
        button.style.background = "#ff6b35";
        button.style.boxShadow = "none";
        button.style.transform = "scale(1)";
        button.innerHTML = button.classList.contains("add-cart-btn")
          ? '<i class="fas fa-plus"></i> أضف'
          : '<i class="fas fa-shopping-cart"></i>';
        button.disabled = false;
      }, 2000);
    } else {
      throw new Error(data.message || "فشلت الإضافة");
    }
  } catch (error) {
    console.error("Error adding to cart:", error);
    button.innerHTML = '<i class="fas fa-times"></i>';
    button.style.background = "#e74c3c";

    setTimeout(() => {
      button.innerHTML = button.classList.contains("add-cart-btn")
        ? '<i class="fas fa-plus"></i> أضف'
        : '<i class="fas fa-shopping-cart"></i>';
      button.style.background = "#ff6b35";
      button.disabled = false;
    }, 2000);
  }
}

// Update cart icons on page load - show which items are in cart but keep buttons clickable
async function updateCartIcons() {
  try {
    const response = await fetch(`${API_BASE}/cart`);
    const data = await response.json();

    if (data.items) {
      const cartItems = data.items.map((item) => item.product_id);
      localStorage.setItem("cart_items", JSON.stringify(cartItems));

      // Just update cart count, don't permanently change buttons
      // Users can add more of the same item
      if (window.updateCartCount) {
        window.updateCartCount(data.count || cartItems.length || 0);
      }
    }
  } catch (error) {
    console.error("Error loading cart items:", error);
  }
}

// Load cart icons when products are loaded
window.addEventListener("DOMContentLoaded", () => {
  setTimeout(updateCartIcons, 1000);
});

// ============================================
// FAVORITES
// ============================================

function updateFavoritesCount() {
  const favorites = JSON.parse(localStorage.getItem("favorites") || "[]");
  const countElement = document.getElementById("favoritesCount");
  if (countElement) {
    const c = favorites.length;
    countElement.textContent = c > 99 ? "+99" : c;
  }
}

async function syncFavoritesFromServer() {
  const isAuth =
    window.isAuthenticated === true || window.isAuthenticated === "true";
  if (!isAuth) {
    updateFavoritesCount();
    return;
  }
  try {
    const res = await fetch("/api/wishlist", {
      headers: { Accept: "application/json" },
    });
    if (!res.ok) return;
    const data = await res.json();
    if (Array.isArray(data.items)) {
      localStorage.setItem("favorites", JSON.stringify(data.items));
      const countElement = document.getElementById("favoritesCount");
      if (countElement) {
        const c = data.count || data.items.length || 0;
        countElement.textContent = c > 99 ? "+99" : c;
      }
    }
  } catch (e) {}
}

async function toggleProductFavorite(event, productId) {
  event.stopPropagation();
  const btn = event.currentTarget;
  const icon = btn.querySelector("i");
  const isAuth =
    window.isAuthenticated === true || window.isAuthenticated === "true";
  let favorites = JSON.parse(localStorage.getItem("favorites") || "[]");
  const isFavorite = favorites.some((p) => p.id === productId);
  const product = (window.__productsById || {})[productId];

  if (isAuth) {
    try {
      const res = await fetch("/api/wishlist/toggle", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]')?.content || "",
        },
        credentials: "same-origin",
        body: JSON.stringify({ product_id: productId }),
      });
      const data = await res.json();
      if (data.action === "added") {
        btn.classList.add("active");
        icon.classList.remove("far");
        icon.classList.add("fas");
        if (product) {
          favorites.push({
            id: product.id,
            name: product.name,
            price: product.discount_price || product.price,
            image: getProductImageUrl(product),
          });
        }
      } else if (data.action === "removed") {
        btn.classList.remove("active");
        icon.classList.remove("fas");
        icon.classList.add("far");
        favorites = favorites.filter((p) => p.id !== productId);
      }
      localStorage.setItem("favorites", JSON.stringify(favorites));
      updateFavoritesCount();
    } catch (e) {}
  } else {
    if (isFavorite) {
      favorites = favorites.filter((p) => p.id !== productId);
      btn.classList.remove("active");
      icon.classList.remove("fas");
      icon.classList.add("far");
    } else if (product) {
      favorites.push({
        id: product.id,
        name: product.name,
        price: product.discount_price || product.price,
        image: getProductImageUrl(product),
      });
      btn.classList.add("active");
      icon.classList.remove("far");
      icon.classList.add("fas");
    }
    localStorage.setItem("favorites", JSON.stringify(favorites));
    updateFavoritesCount();
  }
}

window.addEventListener("DOMContentLoaded", () => {
  syncFavoritesFromServer();
  updateFavoritesCount();
});
