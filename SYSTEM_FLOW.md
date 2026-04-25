# System Flow Diagram

## Registration & Verification Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    User Registration Flow                        │
└─────────────────────────────────────────────────────────────────┘

1. User visits: http://127.0.0.1:8000/register
   │
   ├─> Fills form:
   │   ├─ Name
   │   ├─ Email
   │   ├─ Phone (with country code: 966501234567)
   │   ├─ Password
   │   ├─ Birth Date
   │   └─ Gender
   │
   └─> Selects Verification Method:
       ├─ [✓] عبر الإيميل (Email) - Default
       └─ [ ] عبر الواتساب (WhatsApp) - NEW

2. Form submits to: POST /api/register
   │
   └─> CustomAuthController::register()
       │
       ├─> Validates input
       ├─> Creates user account (verified=false)
       ├─> Generates 6-digit code
       └─> Stores in session (expires 10 min)

3. Send Verification Code
   │
   ├─> IF method = "whatsapp":
   │   │
   │   ├─> WhatsAppService::sendVerificationCode()
   │   │   │
   │   │   ├─> Format phone: 966501234567 → 966501234567@c.us
   │   │   ├─> POST to Green API
   │   │   └─> Send Arabic message with code
   │   │
   │   └─> IF fails:
   │       └─> Fallback to Email
   │
   └─> ELSE (method = "email"):
       └─> Mail::send(VerificationCodeMail)

4. User redirected to: /ar-verify-registration
   │
   ├─> Shows message based on method:
   │   ├─ WhatsApp: "تم إرسال رمز التحقق عبر الواتساب"
   │   └─ Email: "تم إرسال رمز التحقق إلى بريدك الإلكتروني"
   │
   └─> User enters 6-digit code

5. Code verification: POST /api/verify-registration
   │
   └─> CustomAuthController::verifyRegistration()
       │
       ├─> Check code matches session
       ├─> Check not expired (< 10 min)
       │
       ├─> IF valid:
       │   ├─> Set user.verified = true
       │   ├─> Login user
       │   ├─> Create welcome notification
       │   └─> Clear session
       │
       └─> IF invalid:
           └─> Return error

6. Success!
   │
   └─> Redirect to: / (home page)
```

## WhatsApp Service Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    WhatsApp Service Layer                        │
└─────────────────────────────────────────────────────────────────┘

CustomAuthController
        │
        ├─> new WhatsAppService()
        │       │
        │       ├─> Load config from .env:
        │       │   ├─ GREEN_API_INSTANCE_ID
        │       │   └─ GREEN_API_TOKEN
        │       │
        │       └─> isConfigured() ?
        │           ├─ YES → Continue
        │           └─ NO → Fallback to Email
        │
        └─> sendVerificationCode($phone, $code, $name)
                │
                ├─> Format phone number
                │   └─ Remove +, spaces → 966501234567
                │
                ├─> Build message (Arabic):
                │   ┌────────────────────────────────┐
                │   │ مرحباً [name]،                 │
                │   │                                │
                │   │ رمز التحقق الخاص بك:           │
                │   │ *[code]*                       │
                │   │                                │
                │   │ صالح لمدة 10 دقائق             │
                │   │ شكراً لاستخدامك Tulip Store 🌷 │
                │   └────────────────────────────────┘
                │
                └─> POST to Green API:
                    https://api.green-api.com/waInstance{ID}/sendMessage/{TOKEN}
                    │
                    ├─> Success → Log & Return
                    └─> Fail → Log Error & Fallback to Email
```

## Configuration Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    Configuration Chain                           │
└─────────────────────────────────────────────────────────────────┘

.env file
    │
    ├─ GREEN_API_INSTANCE_ID=1101234567
    └─ GREEN_API_TOKEN=abc123...
        │
        ↓
config/services.php
    │
    └─ 'green_api' => [
           'instance_id' => env('GREEN_API_INSTANCE_ID'),
           'token' => env('GREEN_API_TOKEN'),
       ]
        │
        ↓
WhatsAppService::__construct()
    │
    ├─ $this->instanceId = config('services.green_api.instance_id')
    ├─ $this->token = config('services.green_api.token')
    └─ $this->baseUrl = "https://api.green-api.com/waInstance{ID}"
        │
        ↓
WhatsAppService::sendMessage()
    │
    └─ HTTP POST to: {baseUrl}/sendMessage/{token}
```

## Error Handling & Fallback

```
┌─────────────────────────────────────────────────────────────────┐
│                    Error Handling Flow                           │
└─────────────────────────────────────────────────────────────────┘

User selects WhatsApp verification
        │
        ↓
    Is Green API configured?
        │
        ├─ NO → [Fallback to Email]
        │
        └─ YES → Send WhatsApp message
                    │
                    ├─ Success → User receives WhatsApp
                    │
                    └─ Fail (Network/API error)
                        │
                        ├─ Log error to Laravel logs
                        └─ [Fallback to Email]

[Fallback to Email]
    │
    └─> Mail::send(VerificationCodeMail)
        │
        └─> User receives email instead
            (Registration still completes successfully)
```

## Data Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    Session Data Storage                          │
└─────────────────────────────────────────────────────────────────┘

Registration Form Submit
        │
        ↓
Session Storage (10 min expiry):
    ├─ verification_code: "123456"
    ├─ verification_email: "user@example.com"
    ├─ verification_phone: "966501234567"
    ├─ verification_user_id: 123
    ├─ verification_method: "whatsapp" or "email"
    └─ code_expires_at: "2024-01-01 12:10:00"
        │
        ↓
User enters code
        │
        ↓
Verification Check:
    ├─ Code matches? ✓
    ├─ Not expired? ✓
    └─ User ID valid? ✓
        │
        ↓
Success:
    ├─ Set user.verified = true
    ├─ Login user
    └─ Clear session data
```

## Testing Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    Testing Workflow                              │
└─────────────────────────────────────────────────────────────────┘

1. Configure Green API
   ├─ Sign up at green-api.com
   ├─ Create instance
   ├─ Get credentials
   └─ Update .env

2. Connect WhatsApp
   ├─ Open Green API dashboard
   ├─ Scan QR code
   └─ Wait for "authorized" status

3. Run Test Script
   └─> php test-whatsapp.php
       ├─ Enter test phone number
       └─ Check WhatsApp for message

4. Test Full Flow
   ├─ Visit /register
   ├─ Fill form
   ├─ Select WhatsApp
   ├─ Submit
   ├─ Check WhatsApp
   ├─ Enter code
   └─ Verify login

5. Test Email Fallback
   ├─ Visit /register
   ├─ Fill form
   ├─ Select Email
   ├─ Submit
   ├─ Check email
   ├─ Enter code
   └─ Verify login
```

## Security & Logging

```
┌─────────────────────────────────────────────────────────────────┐
│                    Security & Audit Trail                        │
└─────────────────────────────────────────────────────────────────┘

Every registration creates:
    │
    ├─> ActivityFeed (Admin Dashboard)
    │   └─ "New User Registration (Method: whatsapp)"
    │
    ├─> SystemLog (IT Dashboard)
    │   └─ "User registered via whatsapp"
    │
    └─> SecurityAuditLog
        └─ "Account created, OTP sent via whatsapp"

Every verification creates:
    │
    ├─> SystemLog
    │   └─ "User logged in after verification"
    │
    └─> SecurityAuditLog
        └─ "User logged in after verification"

All WhatsApp operations logged:
    │
    ├─> Success: Log::info("WhatsApp message sent to {phone}")
    └─> Failure: Log::error("Failed to send WhatsApp: {error}")
```
