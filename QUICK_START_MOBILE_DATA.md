# 🚀 Quick Start: Mobile Data GPS Tracking

## ✅ 3 Simple Steps to Get Started

### Step 1: Get SIM Cards (15 minutes)
Go to Syriatel or MTN store and say:
```
"أريد 6 بطاقات SIM مع باقة إنترنت صغيرة"
(I want 6 SIM cards with small internet package)
```

Cost: ~$5-10/month per SIM

---

### Step 2: Set Up Server (5 minutes)

**Option A: Use ngrok (Easiest)**
```bash
# Download from: https://ngrok.com/download

# Start Laravel
php artisan serve

# In another terminal, start ngrok
ngrok http 8000

# Copy the HTTPS URL (e.g., https://abc123.ngrok.io)
```

**Option B: Use Your Public IP**
```bash
# Find your IP
curl ifconfig.me

# Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# URL: http://YOUR_IP:8000
```

---

### Step 3: Give URL to Drivers (2 minutes)

**Your URL:**
```
https://abc123.ngrok.io/driver-gps.html
(or http://YOUR_IP:8000/driver-gps.html)
```

**Driver Instructions:**
1. Turn ON mobile data
2. Turn ON GPS
3. Open browser
4. Go to the URL
5. Select name and start tracking

---

## 📱 For Drivers (Arabic)

```
تعليمات بسيطة:

1. شغّل بيانات الجوال (Mobile Data)
2. شغّل GPS
3. افتح المتصفح
4. اذهب إلى: [YOUR_URL]
5. اختر اسمك واضغط "بدء التتبع"
6. اسمح بالوصول للموقع
7. لا تغلق المتصفح!
```

---

## 🧪 Test It Now!

```bash
# 1. Start ngrok
ngrok http 8000

# 2. On your phone (using mobile data):
# Open: https://abc123.ngrok.io/driver-gps.html

# 3. Start tracking

# 4. On computer, open dashboard:
http://localhost:8000/delivery/supervisor/dashboard

# 5. See yourself on the map!
```

---

## 💰 Cost

- **SIM cards**: $5-10/month each
- **6 drivers**: $30-60/month total
- **Data usage**: ~5MB/day per driver
- **1GB plan**: Lasts 6+ months!

---

## ✅ What You Get

- ✅ Real-time tracking (5-second updates)
- ✅ Works anywhere in city
- ✅ Professional solution
- ✅ Very affordable
- ✅ Reliable and proven

---

## 📞 Need Help?

See full guide: **MOBILE_DATA_SETUP_GUIDE.md**

---

**That's it! Real-time GPS tracking in 3 steps!** 🚀
