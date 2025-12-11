# 🚀 How to Access Driver Supervisor Dashboard

## Quick Access Guide

### 📋 Step-by-Step Instructions

#### Step 1: Login
Go to your login page and use these credentials:

```
📧 Email: supervisor@tulipstore.com
🔑 Password: password123
```

Or use your admin account (admin@tulipstore.com) which also has access.

---

#### Step 2: Find the User Menu
After logging in, look at the **top right corner** of the page.

You'll see your user profile icon/name. Click on it to open the dropdown menu.

---

#### Step 3: Click on Driver Supervisor Dashboard
In the dropdown menu, you'll see several options:

- ⚙️ الإعدادات (Settings)
- 🌙 الوضع الليلي (Night Mode)
- 🌐 اللغة (Language)
- 🛍️ طلباتي (My Orders)
- 🔔 الإشعارات (Notifications)
- 🚚 **لوحة مشرف التوصيل** ← Click This!
- 🚪 تسجيل خروج (Logout)

Click on "🚚 لوحة مشرف التوصيل" (Driver Supervisor Dashboard)

---

#### Step 4: Explore the Dashboard
You'll be redirected to the Driver Supervisor Dashboard where you can:

✅ See all drivers on an interactive map
✅ View real-time GPS locations
✅ Check driver statuses (Available, Busy, On Break)
✅ Monitor active deliveries
✅ Track performance metrics
✅ Filter drivers by status

---

## 🎯 What You'll See

### Dashboard Layout:

```
┌─────────────────────────────────────────────────────────┐
│  🚚 لوحة تحكم مشرف التوصيل        [🔄 تحديث البيانات]  │
│  تتبع السائقين والطلبات في الوقت الفعلي                │
└─────────────────────────────────────────────────────────┘

┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐
│ 👥 6 │ │ ✅ 3 │ │ 🚗 2 │ │ 📦 5 │ │ ✨ 8 │
│إجمالي│ │متاح  │ │مشغول│ │نشطة  │ │مكتمل │
└──────┘ └──────┘ └──────┘ └──────┘ └──────┘

┌─────────────────────────────┐ ┌──────────────┐
│  🗺️ خريطة السائقين         │ │ 📋 قائمة     │
│  [الكل] [متاح] [مشغول]     │ │   السائقين   │
│                             │ │              │
│    🟢 🔵 🟡                 │ │ ┌──────────┐ │
│  🗺️ Interactive Map         │ │ │ أحمد محمد │ │
│    with Driver Markers      │ │ │ متاح ✅  │ │
│                             │ │ └──────────┘ │
│                             │ │ ┌──────────┐ │
│                             │ │ │ محمد علي │ │
│                             │ │ │ مشغول 🚗│ │
└─────────────────────────────┘ └──────────────┘
```

---

## 🎨 Visual Features

### Map Markers:
- 🟢 **Green** = Available drivers
- 🔵 **Blue** = Busy drivers
- 🟡 **Yellow** = On break
- ⚪ **Gray** = Offline

### Interactive Elements:
- **Click markers** → See driver details popup
- **Click driver cards** → Focus on driver location
- **Use filters** → Show specific driver types
- **Auto-refresh** → Updates every 30 seconds (watch the green pulse)

---

## 📱 Alternative Access Method

If you prefer, you can also access the dashboard directly via URL:

```
http://localhost:8000/delivery/supervisor/dashboard
```

Or on your production domain:
```
https://your-domain.com/delivery/supervisor/dashboard
```

---

## 🔐 Who Can Access?

Only users with the **Driver Supervisor** role can see and access this dashboard.

### Check Your Access:
If you don't see "لوحة مشرف التوصيل" in your dropdown menu, you need to:

1. **Contact your admin** to grant you access, OR
2. **Grant yourself access** (if you're admin):

```bash
php artisan tinker
```

```php
$user = User::where('email', 'your@email.com')->first();
$user->is_driver_supervisor = true;
$user->save();
```

Then logout and login again to see the menu item.

---

## 🎯 Sample Drivers Available

The system includes 6 test drivers:

1. **أحمد محمد** - Available (145 deliveries, ⭐4.8)
2. **محمد علي** - Busy (203 deliveries, ⭐4.9)
3. **خالد عبدالله** - Available (312 deliveries, ⭐5.0)
4. **عبدالرحمن سعيد** - On Break (178 deliveries, ⭐4.7)
5. **سعد فهد** - Busy (256 deliveries, ⭐4.6)
6. **فيصل ناصر** - Available (89 deliveries, ⭐4.9)

All drivers are located in Riyadh with real GPS coordinates!

---

## 🔄 Real-Time Updates

The dashboard automatically refreshes every 30 seconds to show:
- Latest driver locations
- Status changes
- New deliveries
- Updated statistics

Watch for the **green pulsing dot** next to "خريطة السائقين" - this indicates active auto-refresh.

---

## 💡 Pro Tips

### Tip 1: Focus on Specific Drivers
Click on any driver card in the right panel to zoom the map to their location.

### Tip 2: Filter by Status
Use the filter buttons above the map:
- **الكل** (All) - Show all drivers
- **متاح** (Available) - Show only available drivers
- **مشغول** (Busy) - Show only busy drivers

### Tip 3: View Driver Details
Click on any map marker to see a popup with:
- Driver name
- Current status
- Phone number
- Vehicle information
- Rating
- Last update time

### Tip 4: Manual Refresh
Click the "🔄 تحديث البيانات" button in the top right to manually refresh data.

---

## 🐛 Troubleshooting

### Problem: Menu item not showing
**Solution:** 
- Verify you're logged in
- Check you have `is_driver_supervisor = true` in database
- Clear cache: `php artisan cache:clear`
- Logout and login again

### Problem: Dashboard shows no drivers
**Solution:**
- Run seeder: `php artisan db:seed --class=DriverSeeder`
- Check database: `SELECT * FROM drivers;`
- Refresh the page

### Problem: Map not loading
**Solution:**
- Check internet connection (map tiles load from OpenStreetMap)
- Clear browser cache
- Try a different browser
- Check browser console for errors (F12)

---

## 📞 Need Help?

### Check Documentation:
- **DRIVER_SUPERVISOR_DASHBOARD.md** - Technical guide
- **DRIVER_SUPERVISOR_ROLE_SETUP.md** - Role system guide
- **COMPLETE_SETUP_SUMMARY.md** - Complete overview

### Check Logs:
```bash
tail -f storage/logs/laravel.log
```

### Test Access:
```bash
php artisan tinker
User::where('email', 'supervisor@tulipstore.com')->first();
```

---

## ✅ Quick Checklist

Before accessing the dashboard, make sure:

- [x] You're logged in
- [x] Your account has `is_driver_supervisor = true`
- [x] Migrations have been run
- [x] Sample data has been seeded
- [x] You can see the menu item in dropdown

---

## 🎉 You're Ready!

**Follow these simple steps:**

1. Login → supervisor@tulipstore.com / password123
2. Click user menu (top right)
3. Click "🚚 لوحة مشرف التوصيل"
4. Enjoy tracking your drivers!

---

**Happy Tracking! 🚚📍**

The Driver Supervisor Dashboard gives you complete visibility and control over your delivery operations in real-time.
