# إعداد Google Maps API

## المشكلة الحالية
الخريطة لا تعمل لأن مفتاح Google Maps API غير صحيح.

## الحل المؤقت
تم استبدال Google Maps بـ Leaflet (خرائط مفتوحة المصدر ومجانية).

## إذا أردت استخدام Google Maps:

### 1. الحصول على مفتاح API
1. اذهب إلى: https://console.cloud.google.com/
2. أنشئ مشروع جديد
3. فعّل Google Maps JavaScript API
4. أنشئ مفتاح API (API Key)

### 2. تحديث الكود
في ملف `resources/views/checkout.blade.php`، استبدل:
```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

بـ:
```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY_HERE&libraries=places,geometry&language=ar"></script>
```

### 3. تحديث JavaScript
ستحتاج لتحديث كود الخريطة في `public/js/checkout.js` لاستخدام Google Maps بدلاً من Leaflet.

## مميزات Leaflet (الحل الحالي)
- ✅ مجاني تماماً
- ✅ لا يحتاج مفتاح API
- ✅ يدعم الطرق والمسارات
- ✅ خفيف وسريع
