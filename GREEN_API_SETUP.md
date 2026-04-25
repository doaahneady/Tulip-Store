# Green API WhatsApp Integration Setup Guide

## Overview
This system now supports WhatsApp verification using Green API. Users can choose to receive their verification code via Email or WhatsApp during registration.

## Setup Instructions

### 1. Get Green API Credentials

1. Visit [Green API](https://green-api.com/)
2. Sign up for an account
3. Create a new instance
4. Get your credentials:
   - Instance ID (e.g., `1101234567`)
   - API Token (e.g., `abc123def456...`)

### 2. Configure Your Instance

1. Log into your Green API dashboard
2. Go to your instance settings
3. Scan the QR code with your WhatsApp account
4. Wait for the instance to connect (status should show "authorized")

### 3. Update Environment Variables

Open your `.env` file and update these values:

```env
GREEN_API_INSTANCE_ID=your_actual_instance_id
GREEN_API_TOKEN=your_actual_api_token
```

Replace `your_actual_instance_id` and `your_actual_api_token` with your real credentials from Green API.

### 4. Test the Integration

1. Go to `http://127.0.0.1:8000/register`
2. Fill in the registration form
3. Select "عبر الواتساب" (via WhatsApp) as verification method
4. Make sure the phone number includes the country code (e.g., 966501234567 for Saudi Arabia)
5. Submit the form
6. You should receive a WhatsApp message with the verification code

## Features

### WhatsApp Service (`app/Services/WhatsAppService.php`)

The WhatsApp service provides:
- `sendMessage($phoneNumber, $message)` - Send any WhatsApp message
- `sendVerificationCode($phoneNumber, $code, $userName)` - Send formatted verification code
- `isConfigured()` - Check if Green API credentials are set

### Fallback Mechanism

If WhatsApp sending fails for any reason, the system automatically falls back to email verification to ensure users can always complete registration.

### Phone Number Format

The system automatically formats phone numbers:
- Removes all non-numeric characters
- Adds the required `@c.us` suffix for Green API
- Example: `966501234567` becomes `966501234567@c.us`

## Verification Message Format

The WhatsApp verification message includes:
- Personalized greeting with user's name
- The 6-digit verification code (bold)
- Expiration time (10 minutes)
- Security notice
- Tulip Store branding

Example:
```
مرحباً أحمد،

رمز التحقق الخاص بك في Tulip Store هو:

*123456*

الرمز صالح لمدة 10 دقائق.
إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.

شكراً لاستخدامك Tulip Store 🌷
```

## Troubleshooting

### WhatsApp messages not sending?

1. Check that your Green API instance is "authorized" in the dashboard
2. Verify your credentials in `.env` are correct
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ensure the phone number includes country code without + or spaces
5. Verify your Green API account has sufficient credits

### Users not receiving codes?

1. Check if the phone number is valid and active on WhatsApp
2. Verify the user's WhatsApp is not blocking unknown numbers
3. Check Green API dashboard for delivery status
4. Review Laravel logs for any errors

### Email verification still works?

Yes! Email verification is the default and fallback method. If:
- User selects "عبر الإيميل" (via Email)
- WhatsApp service is not configured
- WhatsApp sending fails

The system will use email verification.

## API Endpoints

The system uses these endpoints:

- `POST /api/register` - Register new user and send verification code
- `POST /api/verify-registration` - Verify the code entered by user
- `GET /api/get-verification-info` - Get verification method and target (email/phone)

## Security Notes

- Verification codes expire after 10 minutes
- Codes are stored in session, not database
- Failed attempts are logged for security monitoring
- Phone numbers are validated and sanitized before sending

## Support

For Green API support:
- Documentation: https://green-api.com/docs/
- Support: https://green-api.com/support/

For Tulip Store issues:
- Check Laravel logs
- Review the CustomAuthController.php
- Test with email verification first
