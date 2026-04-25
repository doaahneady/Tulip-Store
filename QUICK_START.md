# Quick Start Guide - WhatsApp Verification

## 🚀 Quick Setup (5 minutes)

### Step 1: Get Green API Credentials
1. Go to https://green-api.com/
2. Sign up and create an instance
3. Copy your Instance ID and API Token

### Step 2: Configure
Open `.env` and replace these lines:
```env
GREEN_API_INSTANCE_ID=your_instance_id_here
GREEN_API_TOKEN=your_api_token_here
```

With your actual credentials:
```env
GREEN_API_INSTANCE_ID=1101234567
GREEN_API_TOKEN=abc123def456...
```

### Step 3: Connect WhatsApp
1. Go to Green API dashboard
2. Find your instance
3. Scan the QR code with your WhatsApp
4. Wait for status to show "authorized"

### Step 4: Test
```bash
php test-whatsapp.php
```

Enter your phone number (e.g., 966501234567) and check if you receive a test message.

## ✅ What's Working Now

### Registration Page Changes
- **Before**: "عبر رقم الهاتف" (via phone/SMS)
- **After**: "عبر الواتساب" (via WhatsApp)

### User Flow
1. User goes to http://127.0.0.1:8000/register
2. Fills registration form
3. Chooses verification method:
   - Email (default) ✅
   - WhatsApp (new) ✅
4. Receives verification code
5. Enters code and gets verified

### Email Verification
✅ Still works perfectly as before
✅ Used as fallback if WhatsApp fails

## 📱 Phone Number Format

Users should enter phone numbers with country code:
- ✅ 966501234567 (Saudi Arabia)
- ✅ 971501234567 (UAE)
- ✅ 201234567890 (Egypt)
- ❌ 0501234567 (missing country code)
- ❌ +966501234567 (system removes + automatically)

## 🔍 Troubleshooting

### WhatsApp not sending?
1. Check `.env` has correct credentials
2. Verify Green API instance is "authorized"
3. Check logs: `storage/logs/laravel.log`
4. Run test script: `php test-whatsapp.php`

### Email not working?
Email verification is separate and should still work. Check your MAIL settings in `.env`.

## 📝 Files Changed

**New Files:**
- `app/Services/WhatsAppService.php` - WhatsApp integration
- `config/services.php` - Configuration
- `test-whatsapp.php` - Test script
- Documentation files

**Modified Files:**
- `.env` - Added Green API credentials
- `app/Http/Controllers/Auth/CustomAuthController.php` - Added WhatsApp support
- `resources/views/pages/ar-signup.blade.php` - Changed UI text
- `resources/views/pages/ar-verify-registration.blade.php` - Updated messages

## 🎯 Testing Checklist

- [ ] Green API credentials configured in `.env`
- [ ] WhatsApp connected in Green API dashboard
- [ ] Test script runs successfully: `php test-whatsapp.php`
- [ ] Can register with WhatsApp verification
- [ ] Receive WhatsApp message with code
- [ ] Can verify code and login
- [ ] Email verification still works

## 💡 Tips

1. **Test with your own number first** - Use your WhatsApp number for initial testing
2. **Check Green API dashboard** - Monitor message delivery status
3. **Review logs** - Check `storage/logs/laravel.log` for any errors
4. **Fallback works** - If WhatsApp fails, email is used automatically

## 📞 Support

- Green API Docs: https://green-api.com/docs/
- Laravel Logs: `storage/logs/laravel.log`
- Test Script: `php test-whatsapp.php`

---

**That's it!** Your registration system now supports WhatsApp verification. 🎉
