<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إدارة الصفحة الرئيسية - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="background:#f5f5f5;margin:0;padding:0;font-family:'El Messiri',sans-serif;">
@include('components.navbar')

<section style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);padding:2.5rem 1.5rem;text-align:center;margin-top:80px;position:relative;overflow:hidden;">
<div style="position:absolute;inset:0;background:url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.03\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
<div style="position:relative;z-index:1;">
<h1 style="font-size:2.2rem;font-weight:800;color:#fff;margin:0;text-shadow:0 2px 10px rgba(0,0,0,0.3);"><i class="fas fa-palette"></i> مركز تخصيص الصفحة الرئيسية</h1>
<p style="color:#e94560;margin:0.5rem 0 0 0;font-size:1.1rem;">تحكم كامل في مظهر ومحتوى متجرك</p>
<a href="{{ route('admin.dashboard') }}" style="display:inline-block;margin-top:1.2rem;color:#fff;text-decoration:none;background:rgba(233,69,96,0.3);padding:0.6rem 1.5rem;border-radius:25px;font-size:0.9rem;border:1px solid rgba(233,69,96,0.5);transition:all 0.3s;" onmouseover="this.style.background='rgba(233,69,96,0.5)'" onmouseout="this.style.background='rgba(233,69,96,0.3)'">
<i class="fas fa-arrow-right"></i> العودة للوحة الإدارة
</a>
</div>
</section>

<div style="max-width:1400px;margin:0 auto;padding:2rem;">

<!-- Stats Overview -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem;">
<div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:1.5rem;border-radius:16px;color:#fff;position:relative;overflow:hidden;">
<div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
<i class="fas fa-eye" style="font-size:2rem;opacity:0.8;"></i>
<div style="font-size:2rem;font-weight:800;margin:0.5rem 0;">4</div>
<div style="opacity:0.9;">أقسام نشطة</div>
</div>
<div style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);padding:1.5rem;border-radius:16px;color:#fff;position:relative;overflow:hidden;">
<div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
<i class="fas fa-bolt" style="font-size:2rem;opacity:0.8;"></i>
<div style="font-size:2rem;font-weight:800;margin:0.5rem 0;" id="activeDealsCount">0</div>
<div style="opacity:0.9;">عروض برق نشطة</div>
</div>
<div style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);padding:1.5rem;border-radius:16px;color:#fff;position:relative;overflow:hidden;">
<div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
<i class="fas fa-clock" style="font-size:2rem;opacity:0.8;"></i>
<div style="font-size:2rem;font-weight:800;margin:0.5rem 0;" id="timerDisplay">--:--:--</div>
<div style="opacity:0.9;">الوقت المتبقي</div>
</div>
<div style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);padding:1.5rem;border-radius:16px;color:#fff;position:relative;overflow:hidden;">
<div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
<i class="fas fa-mouse-pointer" style="font-size:2rem;opacity:0.8;"></i>
<div style="font-size:2rem;font-weight:800;margin:0.5rem 0;">1,234</div>
<div style="opacity:0.9;">زيارات اليوم</div>
</div>
</div>

<!-- Main Grid -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">

<!-- Left Column -->
<div>
<!-- Sections Management -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:2rem;">
<div style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);padding:1.2rem 1.5rem;display:flex;align-items:center;justify-content:space-between;">
<h3 style="margin:0;color:#fff;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-layer-group"></i> ترتيب أقسام الصفحة</h3>
<span style="background:rgba(233,69,96,0.3);color:#e94560;padding:0.3rem 0.8rem;border-radius:20px;font-size:0.8rem;">اسحب للترتيب</span>
</div>
<div style="padding:1.5rem;">
<div id="sectionsContainer" style="display:flex;flex-direction:column;gap:0.8rem;">
<div style="text-align:center;padding:2rem;color:#999;"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>
</div>
<div style="display:flex;gap:1rem;margin-top:1.5rem;">
<button onclick="saveSectionsOrder()" style="flex:1;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;padding:0.9rem;border-radius:10px;font-weight:700;cursor:pointer;font-family:inherit;font-size:1rem;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 15px rgba(102,126,234,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
<i class="fas fa-save"></i> حفظ الترتيب
</button>
<button onclick="resetSections()" style="background:#f8f9fa;color:#666;border:1px solid #e0e0e0;padding:0.9rem 1.5rem;border-radius:10px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.3s;" onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">
<i class="fas fa-undo"></i> إعادة تعيين
</button>
</div>
</div>
</div>

<!-- Packages Management -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:2rem;">
<div style="background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);padding:1.2rem 1.5rem;display:flex;align-items:center;justify-content:space-between;">
<h3 style="margin:0;color:#fff;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-boxes"></i> إدارة الباقات (الصفحة الرئيسية)</h3>
<button onclick="openAddPackageModal()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;padding:0.5rem 1rem;border-radius:20px;cursor:pointer;font-family:inherit;font-weight:600;transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
<i class="fas fa-plus"></i> إضافة باقة
</button>
</div>
<div style="padding:1.5rem;">
<div id="packagesContainer" style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
<div style="text-align:center;padding:2rem;color:#999;grid-column:1/-1;"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>
</div>
</div>
</div>

<!-- Banner Management -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:2rem;">
<div style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);padding:1.2rem 1.5rem;">
<h3 style="margin:0;color:#fff;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-images"></i> إدارة البانرات</h3>
</div>
<div style="padding:1.5rem;">
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1rem;">
<div style="aspect-ratio:16/9;background:#f8f9fa;border-radius:10px;border:2px dashed #ddd;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;" onmouseover="this.style.borderColor='#e94560';this.style.background='#fff5f7'" onmouseout="this.style.borderColor='#ddd';this.style.background='#f8f9fa'">
<i class="fas fa-plus" style="font-size:1.5rem;color:#e94560;margin-bottom:0.5rem;"></i>
<span style="color:#666;font-size:0.85rem;">إضافة بانر</span>
</div>
<div style="aspect-ratio:16/9;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:10px;position:relative;overflow:hidden;">
<div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.6);padding:0.5rem;color:#fff;font-size:0.8rem;">بانر 1 - نشط</div>
</div>
<div style="aspect-ratio:16/9;background:linear-gradient(135deg,#f093fb,#f5576c);border-radius:10px;position:relative;overflow:hidden;">
<div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.6);padding:0.5rem;color:#fff;font-size:0.8rem;">بانر 2 - نشط</div>
</div>
</div>
<p style="color:#999;font-size:0.85rem;margin:0;"><i class="fas fa-info-circle"></i> الحجم الموصى به: 1400×400 بكسل</p>
</div>
</div>

<!-- Featured Products -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;">
<div style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);padding:1.2rem 1.5rem;">
<h3 style="margin:0;color:#fff;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star"></i> إدارة المنتجات الخاصة</h3>
</div>
<div style="padding:1.5rem;">
<div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
<div onclick="openProductModal('featured')" style="flex:1;min-width:200px;background:#f8f9fa;padding:1rem;border-radius:10px;text-align:center;cursor:pointer;transition:all 0.3s;border:2px solid transparent;" onmouseover="this.style.borderColor='#4facfe';this.style.background='#e8f7ff'" onmouseout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
<i class="fas fa-star" style="font-size:1.5rem;color:#4facfe;margin-bottom:0.5rem;display:block;"></i>
<div style="font-size:1.8rem;font-weight:800;color:#4facfe;" id="featuredCount">0</div>
<div style="color:#666;font-size:0.9rem;">منتجات مميزة</div>
</div>
<div onclick="openProductModal('flash')" style="flex:1;min-width:200px;background:#f8f9fa;padding:1rem;border-radius:10px;text-align:center;cursor:pointer;transition:all 0.3s;border:2px solid transparent;" onmouseover="this.style.borderColor='#f5576c';this.style.background='#fff5f7'" onmouseout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
<i class="fas fa-bolt" style="font-size:1.5rem;color:#f5576c;margin-bottom:0.5rem;display:block;"></i>
<div style="font-size:1.8rem;font-weight:800;color:#f5576c;" id="flashCount">0</div>
<div style="color:#666;font-size:0.9rem;">عروض البرق</div>
</div>
<div onclick="openProductModal('new')" style="flex:1;min-width:200px;background:#f8f9fa;padding:1rem;border-radius:10px;text-align:center;cursor:pointer;transition:all 0.3s;border:2px solid transparent;" onmouseover="this.style.borderColor='#43e97b';this.style.background='#e8fff0'" onmouseout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
<i class="fas fa-sparkles" style="font-size:1.5rem;color:#43e97b;margin-bottom:0.5rem;display:block;"></i>
<div style="font-size:1.8rem;font-weight:800;color:#43e97b;" id="newCount">0</div>
<div style="color:#666;font-size:0.9rem;">وصل حديثاً</div>
</div>
</div>
<p style="color:#999;font-size:0.85rem;margin:0;text-align:center;"><i class="fas fa-mouse-pointer"></i> انقر على أي قسم لتعديل المنتجات</p>
</div>
</div>
</div>

<!-- Product Selection Modal -->
<div id="productModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
<div style="background:#fff;border-radius:20px;width:90%;max-width:900px;max-height:85vh;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
<div id="modalHeader" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);padding:1.5rem;display:flex;align-items:center;justify-content:space-between;">
<h3 style="margin:0;color:#fff;font-size:1.3rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-star"></i> <span id="modalTitle">تعديل المنتجات المميزة</span></h3>
<button onclick="closeProductModal()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:40px;height:40px;border-radius:50%;cursor:pointer;font-size:1.2rem;transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
<i class="fas fa-times"></i>
</button>
</div>
<div style="padding:1.5rem;">
<div style="display:flex;gap:1rem;margin-bottom:1.5rem;">
<div style="flex:1;position:relative;">
<i class="fas fa-search" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);color:#999;"></i>
<input type="text" id="productSearch" placeholder="ابحث عن منتج..." oninput="searchProducts()" style="width:100%;padding:0.8rem 1rem;padding-right:2.5rem;border:2px solid #e0e0e0;border-radius:10px;font-size:1rem;font-family:inherit;transition:border-color 0.3s;" onfocus="this.style.borderColor='#4facfe'" onblur="this.style.borderColor='#e0e0e0'">
</div>
<select id="categoryFilter" onchange="filterByCategory()" style="padding:0.8rem 1rem;border:2px solid #e0e0e0;border-radius:10px;font-family:inherit;min-width:150px;">
<option value="">كل الفئات</option>
</select>
</div>
<div id="selectedProducts" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;min-height:40px;padding:0.5rem;background:#f8f9fa;border-radius:10px;">
<span style="color:#999;font-size:0.9rem;">المنتجات المختارة ستظهر هنا</span>
</div>
<div id="productsList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1rem;max-height:350px;overflow-y:auto;padding:0.5rem;">
<div style="text-align:center;padding:2rem;color:#999;grid-column:1/-1;"><i class="fas fa-spinner fa-spin"></i> جاري تحميل المنتجات...</div>
</div>
</div>
<div style="padding:1rem 1.5rem;background:#f8f9fa;display:flex;justify-content:space-between;align-items:center;">
<span style="color:#666;font-size:0.9rem;"><span id="selectedCount">0</span> منتج مختار</span>
<div style="display:flex;gap:1rem;">
<button onclick="closeProductModal()" style="background:#fff;border:1px solid #e0e0e0;padding:0.8rem 1.5rem;border-radius:10px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.3s;">إلغاء</button>
<button onclick="saveSelectedProducts()" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);color:#fff;border:none;padding:0.8rem 2rem;border-radius:10px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
<i class="fas fa-save"></i> حفظ التغييرات
</button>
</div>
</div>
</div>
</div>

<!-- Add Package Modal -->
<div id="addPackageModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
<div style="background:#fff;border-radius:20px;width:90%;max-width:500px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
<div style="background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);padding:1.5rem;display:flex;align-items:center;justify-content:space-between;">
<h3 style="margin:0;color:#fff;font-size:1.3rem;"><i class="fas fa-plus-circle"></i> إضافة باقة جديدة</h3>
<button onclick="closeAddPackageModal()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:40px;height:40px;border-radius:50%;cursor:pointer;font-size:1.2rem;">
<i class="fas fa-times"></i>
</button>
</div>
<div style="padding:1.5rem;">
<div style="margin-bottom:1.5rem;">
<label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;">اسم الباقة</label>
<input type="text" id="newPackageName" placeholder="مثال: هدايا العيد" style="width:100%;padding:0.8rem 1rem;border:2px solid #e0e0e0;border-radius:10px;font-size:1rem;font-family:inherit;">
</div>
<div style="display:flex;gap:1rem;">
<button onclick="closeAddPackageModal()" style="flex:1;background:#f8f9fa;border:1px solid #e0e0e0;padding:0.9rem;border-radius:10px;font-weight:600;cursor:pointer;font-family:inherit;">إلغاء</button>
<button onclick="addNewPackage()" style="flex:1;background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);color:#fff;border:none;padding:0.9rem;border-radius:10px;font-weight:700;cursor:pointer;font-family:inherit;">
<i class="fas fa-plus"></i> إضافة
</button>
</div>
</div>
</div>
</div>

<!-- Edit Package Modal -->
<div id="editPackageModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
<div style="background:#fff;border-radius:20px;width:95%;max-width:1000px;max-height:90vh;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);display:flex;flex-direction:column;">
<div id="editPackageHeader" style="background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);padding:1.2rem 1.5rem;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
<h3 style="margin:0;color:#fff;font-size:1.2rem;"><i class="fas fa-edit"></i> <span id="editPackageTitle">تعديل الباقة</span></h3>
<button onclick="closeEditPackageModal()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:1.1rem;">
<i class="fas fa-times"></i>
</button>
</div>
<div style="padding:1rem 1.5rem;flex:1;overflow-y:auto;">
<div style="margin-bottom:1rem;">
<label style="display:block;margin-bottom:0.4rem;font-weight:600;color:#333;font-size:0.9rem;">اسم الباقة</label>
<input type="text" id="editPackageNameInput" style="width:100%;padding:0.6rem 1rem;border:2px solid #e0e0e0;border-radius:8px;font-size:0.95rem;font-family:inherit;">
</div>
<div style="margin-bottom:1rem;">
<div style="position:relative;">
<i class="fas fa-search" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);color:#999;"></i>
<input type="text" id="packageProductSearch" placeholder="ابحث عن منتج..." oninput="searchPackageProducts()" style="width:100%;padding:0.6rem 1rem;padding-right:2.5rem;border:2px solid #e0e0e0;border-radius:8px;font-size:0.95rem;font-family:inherit;">
</div>
</div>
<div id="packageSelectedProducts" style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:1rem;min-height:36px;padding:0.5rem;background:#fff5f0;border:2px solid #ff6b35;border-radius:8px;">
<span style="color:#999;font-size:0.85rem;">اختر 4 منتجات للباقة</span>
</div>
<div id="packageProductsList" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;max-height:400px;overflow-y:auto;padding:0.5rem;">
<div style="text-align:center;padding:2rem;color:#999;grid-column:1/-1;"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>
</div>
</div>
<div style="padding:1rem 1.5rem;background:#f8f9fa;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;border-top:1px solid #e0e0e0;">
<span style="color:#666;font-size:0.9rem;font-weight:600;"><span id="packageSelectedCount">0</span>/4 منتجات مختارة</span>
<div style="display:flex;gap:1rem;">
<button onclick="closeEditPackageModal()" style="background:#fff;border:1px solid #e0e0e0;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;font-family:inherit;font-size:0.9rem;">إلغاء</button>
<button onclick="savePackageProducts()" style="background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);color:#fff;border:none;padding:0.7rem 2rem;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.9rem;">
<i class="fas fa-save"></i> حفظ الباقة
</button>
</div>
</div>
</div>
</div>

<!-- Right Column -->
<div>
<!-- Lightning Deals Timer -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:2rem;">
<div style="background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);padding:1.2rem 1.5rem;">
<h3 style="margin:0;color:#fff;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-bolt"></i> مؤقت عروض البرق</h3>
</div>
<div style="padding:1.5rem;">
<div style="text-align:center;margin-bottom:1.5rem;">
<div style="font-size:3rem;font-weight:900;color:#1a1a2e;font-family:monospace;" id="bigTimerDisplay">--:--:--</div>
<div style="color:#666;font-size:0.9rem;">الوقت المتبقي للعرض الحالي</div>
</div>
<div style="margin-bottom:1.5rem;">
<label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;">مدة العرض</label>
<div style="display:flex;gap:0.5rem;">
<button onclick="setTimerDuration(6)" class="duration-btn" data-hours="6" style="flex:1;padding:0.7rem;border:2px solid #e0e0e0;background:#fff;border-radius:8px;cursor:pointer;font-weight:600;transition:all 0.3s;font-family:inherit;">6 ساعات</button>
<button onclick="setTimerDuration(12)" class="duration-btn" data-hours="12" style="flex:1;padding:0.7rem;border:2px solid #e0e0e0;background:#fff;border-radius:8px;cursor:pointer;font-weight:600;transition:all 0.3s;font-family:inherit;">12 ساعة</button>
<button onclick="setTimerDuration(24)" class="duration-btn active" data-hours="24" style="flex:1;padding:0.7rem;border:2px solid #ff6b35;background:#fff5f0;border-radius:8px;cursor:pointer;font-weight:600;color:#ff6b35;transition:all 0.3s;font-family:inherit;">24 ساعة</button>
</div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
<button onclick="resetTimer()" style="background:linear-gradient(135deg,#ff6b35 0%,#f7931e 100%);color:#fff;border:none;padding:0.9rem;border-radius:10px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
<i class="fas fa-redo"></i> إعادة تشغيل
</button>
<button onclick="pauseTimer()" id="pauseBtn" style="background:#f8f9fa;color:#666;border:1px solid #e0e0e0;padding:0.9rem;border-radius:10px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.3s;">
<i class="fas fa-pause"></i> إيقاف مؤقت
</button>
</div>
</div>
</div>

<!-- Discount Settings -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:2rem;">
<div style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);padding:1.2rem 1.5rem;">
<h3 style="margin:0;color:#fff;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-percent"></i> إعدادات الخصومات</h3>
</div>
<div style="padding:1.5rem;">
<div style="margin-bottom:1.2rem;">
<label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;">نطاق الخصم</label>
<div style="display:flex;align-items:center;gap:1rem;">
<div style="flex:1;">
<input type="number" id="discountMin" value="20" min="5" max="90" style="width:100%;padding:0.7rem;border:1px solid #e0e0e0;border-radius:8px;font-size:1rem;text-align:center;font-family:inherit;">
<div style="text-align:center;color:#999;font-size:0.8rem;margin-top:0.3rem;">الحد الأدنى %</div>
</div>
<span style="color:#999;">—</span>
<div style="flex:1;">
<input type="number" id="discountMax" value="50" min="10" max="95" style="width:100%;padding:0.7rem;border:1px solid #e0e0e0;border-radius:8px;font-size:1rem;text-align:center;font-family:inherit;">
<div style="text-align:center;color:#999;font-size:0.8rem;margin-top:0.3rem;">الحد الأقصى %</div>
</div>
</div>
</div>
<div style="margin-bottom:1.2rem;">
<label style="display:flex;align-items:center;gap:0.8rem;cursor:pointer;">
<input type="checkbox" id="dealsEnabled" checked style="width:20px;height:20px;accent-color:#43e97b;">
<span style="font-weight:600;color:#333;">تفعيل عروض البرق</span>
</label>
</div>
<button onclick="saveDiscountSettings()" style="width:100%;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:#fff;border:none;padding:0.9rem;border-radius:10px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
<i class="fas fa-save"></i> حفظ الإعدادات
</button>
</div>
</div>

<!-- Quick Preview -->
<div style="background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;">
<div style="background:linear-gradient(135deg,#a8edea 0%,#fed6e3 100%);padding:1.2rem 1.5rem;">
<h3 style="margin:0;color:#1a1a2e;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;"><i class="fas fa-eye"></i> معاينة سريعة</h3>
</div>
<div style="padding:1.5rem;text-align:center;">
<a href="/" target="_blank" style="display:inline-flex;align-items:center;gap:0.5rem;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);color:#fff;text-decoration:none;padding:1rem 2rem;border-radius:10px;font-weight:700;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 15px rgba(26,26,46,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
<i class="fas fa-external-link-alt"></i> فتح الصفحة الرئيسية
</a>
<p style="color:#999;font-size:0.85rem;margin:1rem 0 0 0;">سيتم فتح الصفحة في نافذة جديدة</p>
</div>
</div>
</div>

</div>
</div>

<script>
// Section icons mapping
const sectionIcons = {
    'hero': 'fa-image',
    'lightning_deals': 'fa-bolt',
    'categories': 'fa-th-large',
    'products': 'fa-box'
};

const sectionColors = {
    'hero': '#667eea',
    'lightning_deals': '#ff6b35',
    'categories': '#4facfe',
    'products': '#43e97b'
};

// Load sections on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSections();
    loadLightningDealsSettings();
    updateTimerDisplay();
    setInterval(updateTimerDisplay, 1000);
});

async function loadSections() {
    try {
        const response = await fetch('/api/admin/homepage/sections');
        const data = await response.json();
        if (data.success) {
            renderSections(data.sections);
        }
    } catch (error) {
        console.error('Error loading sections:', error);
        document.getElementById('sectionsContainer').innerHTML = '<p style="color:#e94560;text-align:center;">حدث خطأ في تحميل الأقسام</p>';
    }
}

function renderSections(sections) {
    const container = document.getElementById('sectionsContainer');
    container.innerHTML = sections.map((section, index) => `
        <div class="section-item" draggable="true" data-id="${section.id}" data-order="${section.order}" style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.2rem;background:#f8f9fa;border-radius:12px;border:2px solid transparent;cursor:grab;transition:all 0.3s;" onmouseover="this.style.borderColor='${sectionColors[section.id] || '#667eea'}';this.style.background='#fff'" onmouseout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
            <div style="display:flex;align-items:center;gap:1rem;">
                <i class="fas fa-grip-vertical" style="color:#ccc;cursor:grab;"></i>
                <div style="width:45px;height:45px;background:${sectionColors[section.id] || '#667eea'};color:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                    <i class="fas ${sectionIcons[section.id] || 'fa-square'}"></i>
                </div>
                <div>
                    <div style="font-weight:700;color:#1a1a2e;">${section.name}</div>
                    <div style="font-size:0.8rem;color:#999;">${section.name_en}</div>
                </div>
            </div>
            <label style="position:relative;display:inline-block;width:50px;height:26px;">
                <input type="checkbox" ${section.visible ? 'checked' : ''} onchange="toggleSection('${section.id}')" style="opacity:0;width:0;height:0;">
                <span style="position:absolute;cursor:pointer;inset:0;background:${section.visible ? '#43e97b' : '#ccc'};border-radius:26px;transition:0.3s;"></span>
                <span style="position:absolute;content:'';height:22px;width:22px;left:${section.visible ? '26px' : '2px'};bottom:2px;background:#fff;border-radius:50%;transition:0.3s;box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
            </label>
        </div>
    `).join('');
    initDragAndDrop();
}

function initDragAndDrop() {
    const items = document.querySelectorAll('.section-item');
    items.forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('drop', handleDrop);
    });
}

let draggedItem = null;

function handleDragStart(e) {
    draggedItem = this;
    this.style.opacity = '0.5';
    this.style.background = '#fff3e0';
}

function handleDragEnd(e) {
    this.style.opacity = '1';
    this.style.background = '#f8f9fa';
    draggedItem = null;
}

function handleDragOver(e) {
    e.preventDefault();
    const container = document.getElementById('sectionsContainer');
    const afterElement = getDragAfterElement(container, e.clientY);
    if (afterElement == null) {
        container.appendChild(draggedItem);
    } else {
        container.insertBefore(draggedItem, afterElement);
    }
}

function handleDrop(e) { e.preventDefault(); }

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.section-item:not([style*="opacity: 0.5"])')];
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

async function toggleSection(sectionId) {
    try {
        const response = await fetch(`/api/admin/homepage/sections/${sectionId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
        const data = await response.json();
        if (data.success) {
            showNotification('تم تحديث حالة القسم', 'success');
            loadSections();
        }
    } catch (error) {
        showNotification('حدث خطأ', 'error');
    }
}

async function saveSectionsOrder() {
    const items = document.querySelectorAll('.section-item');
    const order = Array.from(items).map(item => item.dataset.id);
    try {
        const response = await fetch('/api/admin/homepage/sections/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ order })
        });
        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ الترتيب بنجاح', 'success');
        }
    } catch (error) {
        showNotification('حدث خطأ في حفظ الترتيب', 'error');
    }
}

function resetSections() {
    if (confirm('هل أنت متأكد من إعادة تعيين ترتيب الأقسام؟')) {
        loadSections();
        showNotification('تم إعادة تعيين الترتيب', 'success');
    }
}

// Product Modal Functions
let currentModalType = 'featured';
let allProducts = [];
let selectedProductIds = [];

const modalConfig = {
    featured: {
        title: 'تعديل المنتجات المميزة',
        icon: 'fa-star',
        gradient: 'linear-gradient(135deg,#4facfe 0%,#00f2fe 100%)',
        storageKey: 'homepage_featured_products'
    },
    flash: {
        title: 'تعديل عروض البرق',
        icon: 'fa-bolt',
        gradient: 'linear-gradient(135deg,#f5576c 0%,#f093fb 100%)',
        storageKey: 'homepage_flash_products'
    },
    new: {
        title: 'تعديل المنتجات الجديدة',
        icon: 'fa-sparkles',
        gradient: 'linear-gradient(135deg,#43e97b 0%,#38f9d7 100%)',
        storageKey: 'homepage_new_products'
    }
};

async function openProductModal(type) {
    currentModalType = type;
    const config = modalConfig[type];
    
    // Update modal header
    document.getElementById('modalTitle').textContent = config.title;
    document.getElementById('modalHeader').style.background = config.gradient;
    
    // Load saved products from server
    try {
        const response = await fetch(`/api/admin/homepage/featured/${type}`);
        const data = await response.json();
        selectedProductIds = data.success ? (data.product_ids || []) : [];
    } catch (error) {
        selectedProductIds = [];
    }
    
    // Show modal
    document.getElementById('productModal').style.display = 'flex';
    
    // Load products
    await loadAllProducts();
    loadCategories();
    updateSelectedDisplay();
}

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
    selectedProductIds = [];
}

async function loadAllProducts() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();
        allProducts = data.data || [];
        renderProducts(allProducts);
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsList').innerHTML = '<div style="text-align:center;padding:2rem;color:#e94560;grid-column:1/-1;">حدث خطأ في تحميل المنتجات</div>';
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const categories = await response.json();
        const select = document.getElementById('categoryFilter');
        select.innerHTML = '<option value="">كل الفئات</option>' + 
            categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

function renderProducts(products) {
    const container = document.getElementById('productsList');
    
    if (products.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:2rem;color:#999;grid-column:1/-1;">لا توجد منتجات</div>';
        return;
    }
    
    container.innerHTML = products.map(p => {
        const isSelected = selectedProductIds.includes(p.id);
        return `
            <div onclick="toggleProduct(${p.id})" style="background:${isSelected ? '#e8f7ff' : '#f8f9fa'};border:2px solid ${isSelected ? '#4facfe' : 'transparent'};border-radius:12px;padding:0.8rem;cursor:pointer;transition:all 0.3s;position:relative;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                ${isSelected ? '<div style="position:absolute;top:8px;left:8px;background:#4facfe;color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;"><i class="fas fa-check"></i></div>' : ''}
                <img src="${p.image || 'https://via.placeholder.com/150'}" style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;margin-bottom:0.5rem;">
                <div style="font-weight:600;font-size:0.85rem;color:#1a1a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.name}</div>
                <div style="color:#4facfe;font-weight:700;font-size:0.9rem;">${p.price} USD</div>
            </div>
        `;
    }).join('');
}

function toggleProduct(productId) {
    const index = selectedProductIds.indexOf(productId);
    if (index > -1) {
        selectedProductIds.splice(index, 1);
    } else {
        selectedProductIds.push(productId);
    }
    renderProducts(allProducts.filter(p => {
        const search = document.getElementById('productSearch').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        let match = true;
        if (search) match = p.name.toLowerCase().includes(search);
        if (category) match = match && p.category_id == category;
        return match;
    }));
    updateSelectedDisplay();
}

function updateSelectedDisplay() {
    const container = document.getElementById('selectedProducts');
    const countEl = document.getElementById('selectedCount');
    
    countEl.textContent = selectedProductIds.length;
    
    if (selectedProductIds.length === 0) {
        container.innerHTML = '<span style="color:#999;font-size:0.9rem;">المنتجات المختارة ستظهر هنا</span>';
        return;
    }
    
    const selectedProducts = allProducts.filter(p => selectedProductIds.includes(p.id));
    container.innerHTML = selectedProducts.map(p => `
        <span style="background:#4facfe;color:#fff;padding:0.4rem 0.8rem;border-radius:20px;font-size:0.85rem;display:inline-flex;align-items:center;gap:0.5rem;">
            ${p.name}
            <i class="fas fa-times" onclick="event.stopPropagation();toggleProduct(${p.id})" style="cursor:pointer;opacity:0.8;"></i>
        </span>
    `).join('');
    
    // Update counts on main page
    updateProductCounts();
}

function searchProducts() {
    const search = document.getElementById('productSearch').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    
    let filtered = allProducts;
    if (search) filtered = filtered.filter(p => p.name.toLowerCase().includes(search));
    if (category) filtered = filtered.filter(p => p.category_id == category);
    
    renderProducts(filtered);
}

function filterByCategory() {
    searchProducts();
}

async function saveSelectedProducts() {
    try {
        const response = await fetch(`/api/admin/homepage/featured/${currentModalType}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ product_ids: selectedProductIds })
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ المنتجات بنجاح', 'success');
            updateProductCounts();
            closeProductModal();
        } else {
            showNotification('حدث خطأ في حفظ المنتجات', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
    }
}

async function updateProductCounts() {
    try {
        const response = await fetch('/api/admin/homepage/featured-counts');
        const data = await response.json();
        if (data.success) {
            document.getElementById('featuredCount').textContent = data.counts.featured || 0;
            document.getElementById('flashCount').textContent = data.counts.flash || 0;
            document.getElementById('newCount').textContent = data.counts.new || 0;
        }
    } catch (error) {
        console.error('Error loading counts:', error);
    }
}

// Load counts on page load
document.addEventListener('DOMContentLoaded', function() {
    updateProductCounts();
    loadPackages();
});

// ==================== PACKAGES MANAGEMENT ====================
let allPackages = [];
let currentEditingPackage = null;
let packageSelectedProductIds = [];

async function loadPackages() {
    try {
        const response = await fetch('/api/admin/homepage/packages');
        const data = await response.json();
        if (data.success) {
            allPackages = data.packages;
            renderPackages(data.packages);
        }
    } catch (error) {
        console.error('Error loading packages:', error);
        document.getElementById('packagesContainer').innerHTML = '<div style="text-align:center;padding:2rem;color:#e94560;grid-column:1/-1;">حدث خطأ في تحميل الباقات</div>';
    }
}

function renderPackages(packages) {
    const container = document.getElementById('packagesContainer');
    
    if (packages.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:2rem;color:#999;grid-column:1/-1;">لا توجد باقات. أضف باقة جديدة!</div>';
        return;
    }
    
    container.innerHTML = packages.map(pkg => `
        <div style="background:#f8f9fa;border-radius:12px;padding:1rem;border:2px solid transparent;transition:all 0.3s;" onmouseover="this.style.borderColor='#ff6b35';this.style.background='#fff'" onmouseout="this.style.borderColor='transparent';this.style.background='#f8f9fa'">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.8rem;">
                <h4 style="margin:0;font-size:1rem;color:#1a1a2e;">${pkg.name}</h4>
                <div style="display:flex;gap:0.5rem;">
                    <button onclick="editPackage('${pkg.id}')" style="background:#4facfe;color:#fff;border:none;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:0.85rem;" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deletePackage('${pkg.id}')" style="background:#f5576c;color:#fff;border:none;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:0.85rem;" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.4rem;margin-bottom:0.8rem;">
                ${renderPackageImages(pkg.product_ids)}
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.85rem;color:#666;">${pkg.product_ids?.length || 0} منتجات</span>
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                    <input type="checkbox" ${pkg.visible ? 'checked' : ''} onchange="togglePackageVisibility('${pkg.id}')" style="width:16px;height:16px;accent-color:#43e97b;">
                    <span style="font-size:0.85rem;color:#666;">مرئي</span>
                </label>
            </div>
        </div>
    `).join('');
}

function renderPackageImages(productIds) {
    if (!productIds || productIds.length === 0) {
        return Array(4).fill('<div style="aspect-ratio:1;background:#e0e0e0;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#999;font-size:0.7rem;"><i class="fas fa-image"></i></div>').join('');
    }
    
    const images = [];
    for (let i = 0; i < 4; i++) {
        if (productIds[i]) {
            const product = allProducts.find(p => p.id === productIds[i]);
            if (product) {
                images.push(`<img src="${product.image || 'https://via.placeholder.com/80'}" style="aspect-ratio:1;object-fit:cover;border-radius:6px;width:100%;">`);
            } else {
                images.push('<div style="aspect-ratio:1;background:#e0e0e0;border-radius:6px;"></div>');
            }
        } else {
            images.push('<div style="aspect-ratio:1;background:#e0e0e0;border-radius:6px;"></div>');
        }
    }
    return images.join('');
}

function openAddPackageModal() {
    document.getElementById('newPackageName').value = '';
    document.getElementById('addPackageModal').style.display = 'flex';
}

function closeAddPackageModal() {
    document.getElementById('addPackageModal').style.display = 'none';
}

async function addNewPackage() {
    const name = document.getElementById('newPackageName').value.trim();
    if (!name) {
        showNotification('يرجى إدخال اسم الباقة', 'error');
        return;
    }
    
    try {
        const response = await fetch('/api/admin/homepage/packages/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ name, product_ids: [] })
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification('تم إضافة الباقة بنجاح', 'success');
            closeAddPackageModal();
            loadPackages();
        } else {
            showNotification('حدث خطأ', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
    }
}

async function editPackage(packageId) {
    currentEditingPackage = allPackages.find(p => p.id === packageId);
    if (!currentEditingPackage) return;
    
    document.getElementById('editPackageTitle').textContent = 'تعديل: ' + currentEditingPackage.name;
    document.getElementById('editPackageNameInput').value = currentEditingPackage.name;
    packageSelectedProductIds = [...(currentEditingPackage.product_ids || [])];
    
    document.getElementById('editPackageModal').style.display = 'flex';
    
    // Load products if not loaded
    if (allProducts.length === 0) {
        await loadAllProducts();
    }
    
    renderPackageProductsList(allProducts);
    updatePackageSelectedDisplay();
}

function closeEditPackageModal() {
    document.getElementById('editPackageModal').style.display = 'none';
    currentEditingPackage = null;
    packageSelectedProductIds = [];
}

function renderPackageProductsList(products) {
    const container = document.getElementById('packageProductsList');
    
    if (products.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:2rem;color:#999;grid-column:1/-1;">لا توجد منتجات</div>';
        return;
    }
    
    container.innerHTML = products.map(p => {
        const isSelected = packageSelectedProductIds.includes(p.id);
        return `
            <div onclick="togglePackageProduct(${p.id})" style="background:${isSelected ? '#fff5f0' : '#fff'};border:3px solid ${isSelected ? '#ff6b35' : '#e0e0e0'};border-radius:12px;padding:0.8rem;cursor:pointer;transition:all 0.3s;position:relative;box-shadow:0 2px 8px rgba(0,0,0,0.06);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'">
                ${isSelected ? '<div style="position:absolute;top:8px;left:8px;background:#ff6b35;color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.9rem;box-shadow:0 2px 8px rgba(255,107,53,0.4);z-index:2;"><i class="fas fa-check"></i></div>' : ''}
                <div style="width:100%;aspect-ratio:1;overflow:hidden;border-radius:8px;margin-bottom:0.6rem;background:#f8f9fa;">
                    <img src="${p.image || 'https://via.placeholder.com/150?text=No+Image'}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                </div>
                <div style="font-size:0.85rem;font-weight:700;color:#1a1a2e;line-height:1.3;height:2.6em;overflow:hidden;margin-bottom:0.4rem;">${p.name}</div>
                <div style="font-size:0.9rem;font-weight:800;color:#ff6b35;">${p.price} USD</div>
                <div style="margin-top:0.6rem;padding-top:0.6rem;border-top:1px solid #eee;text-align:center;">
                    <span style="font-size:0.75rem;color:${isSelected ? '#ff6b35' : '#999'};font-weight:600;">
                        ${isSelected ? '<i class="fas fa-check-circle"></i> تم الاختيار' : '<i class="fas fa-plus-circle"></i> انقر للاختيار'}
                    </span>
                </div>
            </div>
        `;
    }).join('');
}

function togglePackageProduct(productId) {
    const index = packageSelectedProductIds.indexOf(productId);
    if (index > -1) {
        packageSelectedProductIds.splice(index, 1);
    } else {
        if (packageSelectedProductIds.length >= 4) {
            showNotification('يمكنك اختيار 4 منتجات فقط', 'warning');
            return;
        }
        packageSelectedProductIds.push(productId);
    }
    
    const search = document.getElementById('packageProductSearch').value.toLowerCase();
    let filtered = allProducts;
    if (search) filtered = filtered.filter(p => p.name.toLowerCase().includes(search));
    
    renderPackageProductsList(filtered);
    updatePackageSelectedDisplay();
}

function updatePackageSelectedDisplay() {
    const container = document.getElementById('packageSelectedProducts');
    const countEl = document.getElementById('packageSelectedCount');
    
    countEl.textContent = packageSelectedProductIds.length;
    
    if (packageSelectedProductIds.length === 0) {
        container.innerHTML = '<span style="color:#999;font-size:0.9rem;">اختر 4 منتجات للباقة</span>';
        return;
    }
    
    const selectedProducts = allProducts.filter(p => packageSelectedProductIds.includes(p.id));
    container.innerHTML = selectedProducts.map(p => `
        <span style="background:#ff6b35;color:#fff;padding:0.3rem 0.6rem;border-radius:15px;font-size:0.8rem;display:inline-flex;align-items:center;gap:0.4rem;">
            ${p.name}
            <i class="fas fa-times" onclick="event.stopPropagation();togglePackageProduct(${p.id})" style="cursor:pointer;opacity:0.8;"></i>
        </span>
    `).join('');
}

function searchPackageProducts() {
    const search = document.getElementById('packageProductSearch').value.toLowerCase();
    let filtered = allProducts;
    if (search) filtered = filtered.filter(p => p.name.toLowerCase().includes(search));
    renderPackageProductsList(filtered);
}

async function savePackageProducts() {
    if (!currentEditingPackage) return;
    
    const name = document.getElementById('editPackageNameInput').value.trim();
    if (!name) {
        showNotification('يرجى إدخال اسم الباقة', 'error');
        return;
    }
    
    try {
        const response = await fetch(`/api/admin/homepage/packages/${currentEditingPackage.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ 
                name: name,
                product_ids: packageSelectedProductIds 
            })
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ الباقة بنجاح', 'success');
            closeEditPackageModal();
            loadPackages();
        } else {
            showNotification('حدث خطأ', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
    }
}

async function deletePackage(packageId) {
    if (!confirm('هل أنت متأكد من حذف هذه الباقة؟')) return;
    
    try {
        const response = await fetch(`/api/admin/homepage/packages/${packageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification('تم حذف الباقة', 'success');
            loadPackages();
        } else {
            showNotification('حدث خطأ', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
    }
}

async function togglePackageVisibility(packageId) {
    const pkg = allPackages.find(p => p.id === packageId);
    if (!pkg) return;
    
    try {
        const response = await fetch(`/api/admin/homepage/packages/${packageId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ visible: !pkg.visible })
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification(pkg.visible ? 'تم إخفاء الباقة' : 'تم إظهار الباقة', 'success');
            loadPackages();
        }
    } catch (error) {
        showNotification('حدث خطأ', 'error');
    }
}

// Timer functions
function updateTimerDisplay() {
    let endTime = localStorage.getItem('flashDealEndTime');
    if (!endTime) {
        endTime = Date.now() + (24 * 60 * 60 * 1000);
        localStorage.setItem('flashDealEndTime', endTime.toString());
    }
    
    const now = Date.now();
    let remaining = parseInt(endTime) - now;
    
    if (remaining <= 0) {
        remaining = 24 * 60 * 60 * 1000;
        localStorage.setItem('flashDealEndTime', (Date.now() + remaining).toString());
    }
    
    const hours = Math.floor(remaining / (1000 * 60 * 60));
    const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
    
    const timeStr = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    document.getElementById('timerDisplay').textContent = timeStr;
    document.getElementById('bigTimerDisplay').textContent = timeStr;
}

function setTimerDuration(hours) {
    // Update button styles
    document.querySelectorAll('.duration-btn').forEach(btn => {
        btn.style.border = '2px solid #e0e0e0';
        btn.style.background = '#fff';
        btn.style.color = '#333';
    });
    
    const activeBtn = document.querySelector(`.duration-btn[data-hours="${hours}"]`);
    if (activeBtn) {
        activeBtn.style.border = '2px solid #ff6b35';
        activeBtn.style.background = '#fff5f0';
        activeBtn.style.color = '#ff6b35';
    }
    
    // Set new end time
    const newEndTime = Date.now() + (hours * 60 * 60 * 1000);
    localStorage.setItem('flashDealEndTime', newEndTime.toString());
    updateTimerDisplay();
    showNotification(`تم تعيين المؤقت لـ ${hours} ساعة`, 'success');
}

function resetTimer() {
    const currentDuration = 24; // Default 24 hours
    const newEndTime = Date.now() + (currentDuration * 60 * 60 * 1000);
    localStorage.setItem('flashDealEndTime', newEndTime.toString());
    updateTimerDisplay();
    showNotification('تم إعادة تشغيل المؤقت', 'success');
}

let timerPaused = false;
let pausedTime = 0;

function pauseTimer() {
    const btn = document.getElementById('pauseBtn');
    if (!timerPaused) {
        pausedTime = parseInt(localStorage.getItem('flashDealEndTime')) - Date.now();
        timerPaused = true;
        btn.innerHTML = '<i class="fas fa-play"></i> استئناف';
        btn.style.background = '#43e97b';
        btn.style.color = '#fff';
        btn.style.border = 'none';
        showNotification('تم إيقاف المؤقت مؤقتاً', 'warning');
    } else {
        localStorage.setItem('flashDealEndTime', (Date.now() + pausedTime).toString());
        timerPaused = false;
        btn.innerHTML = '<i class="fas fa-pause"></i> إيقاف مؤقت';
        btn.style.background = '#f8f9fa';
        btn.style.color = '#666';
        btn.style.border = '1px solid #e0e0e0';
        showNotification('تم استئناف المؤقت', 'success');
    }
}

// Discount settings
async function loadLightningDealsSettings() {
    try {
        const response = await fetch('/api/admin/homepage/lightning-deals');
        const data = await response.json();
        if (data.success && data.settings) {
            document.getElementById('discountMin').value = data.settings.discount_min || 20;
            document.getElementById('discountMax').value = data.settings.discount_max || 50;
            document.getElementById('dealsEnabled').checked = data.settings.enabled !== false;
        }
    } catch (error) {
        console.error('Error loading settings:', error);
    }
}

async function saveDiscountSettings() {
    const settings = {
        discount_min: parseInt(document.getElementById('discountMin').value),
        discount_max: parseInt(document.getElementById('discountMax').value),
        enabled: document.getElementById('dealsEnabled').checked
    };
    
    try {
        const response = await fetch('/api/admin/homepage/lightning-deals', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(settings)
        });
        const data = await response.json();
        if (data.success) {
            showNotification('تم حفظ إعدادات الخصومات', 'success');
        }
    } catch (error) {
        showNotification('حدث خطأ في حفظ الإعدادات', 'error');
    }
}

// Notification function
function showNotification(message, type) {
    const colors = {
        success: 'linear-gradient(135deg,#43e97b 0%,#38f9d7 100%)',
        error: 'linear-gradient(135deg,#f5576c 0%,#f093fb 100%)',
        warning: 'linear-gradient(135deg,#ff6b35 0%,#f7931e 100%)'
    };
    
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%) translateY(-20px);
        padding: 1rem 2rem;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        background: ${colors[type] || colors.success};
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(-50%) translateY(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(-50%) translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

</body>
</html>
