# 📋 Quick Reference Card - GPS Tracking System

## 🚀 For Drivers

### URL:
```
http://your-domain.com/driver/tracking
```

### Steps:
1. ✅ Turn ON mobile data
2. ✅ Turn ON GPS
3. ✅ Open URL in browser
4. ✅ Select your name
5. ✅ Click "بدء التتبع"
6. ✅ Allow location
7. ✅ Done!

### Important:
- Don't close browser
- If you close, reopen and tracking resumes
- Use car charger
- Keep mobile data ON

---

## 📊 For Supervisors

### URL:
```
http://your-domain.com/delivery/supervisor/dashboard
```

### Login:
```
Email: supervisor@tulipstore.com
Password: password123
```

### Features:
- See all drivers on map
- Live updates every 5 seconds
- Click markers for details
- Filter by status

---

## 🔧 For IT/Admin

### Start Server:
```bash
php artisan serve
```

### With ngrok:
```bash
ngrok http 8000
```

### Check Logs:
```bash
tail -f storage/logs/laravel.log
```

### Test API:
```bash
curl http://localhost:8000/api/driver/location/update \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"driver_id": 1, "latitude": 32.7081, "longitude": 36.5686}'
```

---

## 📱 URLs Summary

| Page | URL |
|------|-----|
| Driver Tracking | `/driver/tracking` |
| Supervisor Dashboard | `/delivery/supervisor/dashboard` |
| API Update | `/api/driver/location/update` |

---

## 💰 Cost

- **Per driver**: $5-10/month (mobile data)
- **6 drivers**: $30-60/month
- **Data usage**: ~5MB/day
- **1GB SIM**: Lasts 6+ months

---

## 🐛 Troubleshooting

### Tracking not working?
1. Check mobile data ON
2. Check GPS enabled
3. Check location permission
4. Reload page

### Not showing on dashboard?
1. Wait 5-10 seconds
2. Check driver selected correct name
3. Check internet connection
4. Refresh dashboard

---

## 📞 Support

**Phone**: [YOUR_NUMBER]  
**Email**: [YOUR_EMAIL]

---

**Tulip Store GPS Tracking System**  
**Version**: 7.0.0  
**Date**: December 3, 2024
