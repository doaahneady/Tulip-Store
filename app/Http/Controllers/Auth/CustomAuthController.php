<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\ActivityFeed;
use App\Models\Notification;
use App\Models\SecurityAuditLog;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\WhatsAppService;
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
                'phone' => 'required|string|max:20|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'birth_date' => 'nullable|date',
                'gender' => 'nullable|in:ذكر,أنثى',
                'terms_accepted' => 'required|accepted',
            ]);

            // Generate 6-digit verification code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $method = $request->input('verification_method', 'email');

            // Generate username from email
            $username = explode('@', $validated['email'])[0].'_'.rand(1000, 9999);

            // Store ALL user data in session (don't create user yet!)
            $request->session()->put('pending_user_data', [
                'name' => $validated['name'],
                'username' => $username,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);
            
            // Store verification info in session
            $request->session()->put('verification_code', $code);
            $request->session()->put('verification_email', $validated['email']);
            $request->session()->put('verification_phone', $validated['phone']);
            $request->session()->put('verification_method', $method);
            $request->session()->put('code_expires_at', now()->addMinutes(10));

            // Send verification code
            if ($method === 'whatsapp' && $validated['phone']) {
                // Send via WhatsApp using Green API
                try {
                    $whatsappService = new WhatsAppService();
                    
                    if (!$whatsappService->isConfigured()) {
                        \Log::warning('WhatsApp service not configured, falling back to email');
                        Mail::to($validated['email'])->send(new VerificationCodeMail($code, $validated['name']));
                    } else {
                        $result = $whatsappService->sendVerificationCode($validated['phone'], $code, $validated['name']);
                        
                        if (!$result['success']) {
                            \Log::error('Failed to send WhatsApp message: ' . ($result['error'] ?? 'Unknown error'));
                            // Fallback to email if WhatsApp fails
                            Mail::to($validated['email'])->send(new VerificationCodeMail($code, $validated['name']));
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('WhatsApp service error: ' . $e->getMessage());
                    // Fallback to email
                    Mail::to($validated['email'])->send(new VerificationCodeMail($code, $validated['name']));
                }
            } else {
                // Default to Email
                try {
                    Mail::to($validated['email'])->send(new VerificationCodeMail($code, $validated['name']));
                } catch (\Exception $e) {
                    \Log::error('Failed to send verification email: '.$e->getMessage());
                }
            }

            // Log registration attempt (user not created yet)
            if (Schema::hasTable('system_logs')) {
                SystemLog::create([
                    'level' => 'info',
                    'action' => 'registration_attempt',
                    'message' => 'Registration attempt, OTP sent via '.$method,
                    'user' => $validated['email'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'metadata' => ['method' => $method],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $method === 'whatsapp' ? 'تم إرسال رمز التحقق عبر الواتساب' : 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
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
        $pendingUserData = $request->session()->get('pending_user_data');

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

        if (!$pendingUserData) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات التسجيل غير موجودة',
            ], 400);
        }

        // NOW create the user after successful verification
        $user = User::create([
            'name' => $pendingUserData['name'],
            'username' => $pendingUserData['username'],
            'email' => $pendingUserData['email'],
            'phone' => $pendingUserData['phone'],
            'password' => $pendingUserData['password'],
            'birth_date' => $pendingUserData['birth_date'],
            'gender' => $pendingUserData['gender'],
            'verified' => true, // Already verified!
        ]);

        // Log the user in
        Auth::login($user);

        // Create welcome notification
        if (Schema::hasTable('notifications')) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'system',
                'title' => 'Welcome to Tulip Store',
                'message' => 'Your account has been verified. Welcome aboard!',
                'icon' => 'fa-smile',
                'color' => 'green',
                'link' => '/profile',
            ]);
        }

        // Admin activity feed: new registration
        if (Schema::hasTable('activity_feeds')) {
            ActivityFeed::create([
                'dashboard_type' => 'admin',
                'activity_type' => 'user',
                'action' => 'created',
                'title' => 'New User Registration',
                'description' => $user->name.' completed registration',
                'actor_type' => User::class,
                'actor_id' => $user->id,
                'target_type' => User::class,
                'target_id' => $user->id,
                'severity' => 'info',
                'metadata' => ['email' => $user->email],
            ]);
        }

        // IT: successful registration + login audit
        if (Schema::hasTable('system_logs')) {
            SystemLog::create([
                'level' => 'info',
                'action' => 'registration_completed',
                'message' => 'User completed registration and logged in',
                'user' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => ['method' => 'email_password'],
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
                'description' => 'User account created after verification',
                'metadata' => ['email' => $user->email],
                'risk_level' => 'low',
            ]);
        }

        // Clear session
        $request->session()->forget([
            'verification_code', 
            'verification_email', 
            'verification_phone',
            'verification_method',
            'code_expires_at',
            'pending_user_data'
        ]);

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
            'redirect' => 'nullable|string',
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
                // Prefer explicit redirect from UI (e.g. /login?redirect=/cart),
                // then Laravel intended URL, then fallback to home.
                'redirect' => (function () use ($request) {
                    $explicit = (string) ($request->input('redirect') ?? '');
                    $explicit = trim($explicit);

                    // Prevent open-redirect: allow only local paths starting with "/"
                    if ($explicit !== '' && str_starts_with($explicit, '/')) {
                        return $explicit;
                    }

                    $intended = (string) ($request->session()->pull('url.intended') ?? '');
                    if ($intended !== '' && str_starts_with($intended, '/')) {
                        return $intended;
                    }

                    return '/';
                })(),
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
        $method = $request->session()->get('verification_method', 'email');
        $target = $method === 'whatsapp' || $method === 'sms'
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

        return redirect('/');
    }
}
