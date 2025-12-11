# تعليمات إصلاح الخريطة

## المشكلة
الكود الحالي يستخدم Google Maps API ولكن تم تغيير المكتبة إلى Leaflet.

## الحل السريع
استخدم Google Maps API بمفتاح صحيح:

### 1. احصل على مفتاح Google Maps API مجاني
1. اذهب إلى: https://console.cloud.google.com/
2. أنشئ مشروع جديد
3. فعّل "Maps JavaScript API"
4. أنشئ مفتاح API

### 2. استبدل في checkout.blade.php
استبدل السطر:
```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
```

بـ:
```html
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places,geometry&language=ar"></script>
```

ضع مفتاحك بدلاً من `YOUR_API_KEY`

### 3. الخريطة ستعمل فوراً
الكود الموجود في checkout.js مكتوب لـ Google Maps وسيعمل مباشرة.

## ملاحظة
Google Maps يعطيك 200$ رصيد مجاني شهرياً (كافي لآلاف الطلبات).
