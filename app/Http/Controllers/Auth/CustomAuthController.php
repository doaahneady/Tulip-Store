<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\ActivityFeed;
use App\Models\Notification;
use App\Models\SecurityAuditLog;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class CustomAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:ذكر,أنثى',
            ]);

            // Generate username from email
            $username = explode('@', $validated['email'])[0].'_'.rand(1000, 9999);

            $user = User::create([
                'name' => $validated['name'],
                'username' => $username,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'verified' => false, // Set to false to require OTP
            ]);

            // Generate 6-digit verification code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $method = $request->input('verification_method', 'email');

            // Store code and user info in session
            $request->session()->put('verification_code', $code);
            $request->session()->put('verification_email', $user->email);
            $request->session()->put('verification_phone', $user->phone);
            $request->session()->put('verification_user_id', $user->id);
            $request->session()->put('verification_method', $method);
            $request->session()->put('code_expires_at', now()->addMinutes(10));

            // Send verification code
            if ($method === 'sms' && $user->phone) {
                // Mock SMS sending - in real app use Twilio/Infobip etc.
                \Log::info("SMS OTP for {$user->phone}: {$code}");
                // Here you would call your SMS provider API
            } else {
                // Default to Email
                try {
                    Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));
                } catch (\Exception $e) {
                    \Log::error('Failed to send verification email: '.$e->getMessage());
                }
            }

            // Admin activity feed: new registration
            if (Schema::hasTable('activity_feeds')) {
                ActivityFeed::create([
                    'dashboard_type' => 'admin',
                    'activity_type' => 'user',
                    'action' => 'created',
                    'title' => 'New User Registration',
                    'description' => $user->name.' signed up (Method: '.$method.')',
                    'actor_type' => User::class,
                    'actor_id' => $user->id,
                    'target_type' => User::class,
                    'target_id' => $user->id,
                    'severity' => 'info',
                    'metadata' => ['email' => $user->email, 'method' => $method],
                ]);
            }
            // IT: system log + security audit (account_created)
            if (Schema::hasTable('system_logs')) {
                SystemLog::create([
                    'level' => 'info',
                    'action' => 'user_registered',
                    'message' => 'User registered via '.$method,
                    'user' => $user->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => ['method' => $method],
                ]);
            }
            if (Schema::hasTable('security_audit_logs')) {
                SecurityAuditLog::create([
                    'event_type' => 'account_created',
                    'user_type' => User::class,
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'success',
                    'description' => 'Account created, OTP sent via '.$method,
                    'metadata' => ['email' => $user->email, 'method' => $method],
                    'risk_level' => 'low',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $method === 'sms' ? 'تم إرسال رمز التحقق إلى رقم جوالك' : 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
                'redirect' => '/ar-verify-registration',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: '.$e->getMessage(),
            ], 500);
        }
    }

    public function verifyRegistration(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $storedCode = $request->session()->get('verification_code');
        $expiresAt = $request->session()->get('code_expires_at');
        $userId = $request->session()->get('verification_user_id');

        if (! $storedCode || now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الرمز',
            ], 400);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'الرمز غير صحيح',
            ], 400);
        }

        // Mark user as verified
        $user = User::find($userId);
        if ($user) {
            $user->verified = true;
            $user->save();

            // Log the user in
            Auth::login($user);

            // Create welcome notification
            if (Schema::hasTable('notifications')) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'system',
                    'title' => 'Welcome to Tulip Store',
                    'message' => 'Your email has been verified. Welcome aboard!',
                    'icon' => 'fa-smile',
                    'color' => 'green',
                    'link' => '/profile',
                ]);
            }
            // IT: successful login audit + system log
            if (Schema::hasTable('system_logs')) {
                SystemLog::create([
                    'level' => 'info',
                    'action' => 'login_success',
                    'message' => 'User logged in after verification',
                    'user' => $user->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => ['method' => 'email_password'],
                ]);
            }
            if (Schema::hasTable('security_audit_logs')) {
                SecurityAuditLog::create([
                    'event_type' => 'login_attempt',
                    'user_type' => User::class,
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'success',
                    'description' => 'User logged in after verification',
                    'metadata' => ['email' => $user->email],
                    'risk_level' => 'low',
                ]);
            }
        }

        // Clear session
        $request->session()->forget(['verification_code', 'verification_email', 'verification_user_id', 'code_expires_at']);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح',
            'user_name' => $user->name,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (\Illuminate\Support\Facades\Schema::hasTable('ip_blacklists')) {
            $blocked = \App\Models\IPBlacklist::where('ip_address', $request->ip())
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                })
                ->exists();
            if ($blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم رفض الوصول',
                ], 403);
            }
        }

        $credentials = [
            'password' => $request->password,
        ];

        // Check if email or phone
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->email;
        } else {
            $credentials['phone'] = $request->email;
        }

        $user = \App\Models\User::where('email', $request->email)->orWhere('phone', $request->email)->first();
        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب مقفل مؤقتًا',
            ], 423);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($user && ($user->locked_at || $user->locked_until || $user->login_failures > 0)) {
                $user->update([
                    'locked_at' => null,
                    'locked_until' => null,
                    'lock_reason' => null,
                    'login_failures' => 0,
                ]);
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('system_logs')) {
                \App\Models\SystemLog::create([
                    'level' => 'info',
                    'action' => 'login_success',
                    'message' => 'User logged in successfully',
                    'user' => $user?->email ?? (string) $request->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => ['method' => filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone'],
                ]);
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('security_audit_logs')) {
                \App\Models\SecurityAuditLog::create([
                    'event_type' => 'login_attempt',
                    'user_type' => $user ? \App\Models\User::class : null,
                    'user_id' => $user?->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'success',
                    'description' => 'User logged in successfully',
                    'metadata' => ['identifier' => (string) $request->email],
                    'risk_level' => 'low',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'redirect' => '/',
            ]);
        }

        \App\Services\CrossDepartmentFlowService::recordFailedLoginAttempt(
            (string) $request->email,
            $user?->id,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'success' => false,
            'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ], 401);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store code in session
        $request->session()->put('verification_code', $code);
        $request->session()->put('verification_email', $request->email);
        $request->session()->put('code_expires_at', now()->addMinutes(10));

        // Send email
        Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $storedCode = $request->session()->get('verification_code');
        $expiresAt = $request->session()->get('code_expires_at');

        if (! $storedCode || now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الرمز',
            ], 400);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'الرمز غير صحيح',
            ], 400);
        }

        $request->session()->put('code_verified', true);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! $request->session()->get('code_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'يجب التحقق من الرمز أولاً',
            ], 400);
        }

        $email = $request->session()->get('verification_email');
        $user = User::where('email', $email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        $request->session()->forget(['verification_code', 'verification_email', 'code_verified', 'code_expires_at']);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح',
        ]);
    }

    public function getVerificationInfo(Request $request)
    {
        $method = $request->session()->get('verification_method');
        $target = $method === 'sms' 
            ? $request->session()->get('verification_phone') 
            : $request->session()->get('verification_email');

        if (!$target) {
            return response()->json(['success' => false], 404);
        }

        return response()->json([
            'success' => true,
            'method' => $method,
            'target' => $target
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/ar-login');
    }
}
