<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Str;

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
            $username = explode('@', $validated['email'])[0] . '_' . rand(1000, 9999);

            $user = User::create([
                'name' => $validated['name'],
                'username' => $username,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'verified' => false,
            ]);

            // Generate 6-digit verification code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store code and user info in session
            $request->session()->put('verification_code', $code);
            $request->session()->put('verification_email', $user->email);
            $request->session()->put('verification_user_id', $user->id);
            $request->session()->put('code_expires_at', now()->addMinutes(10));

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
                'redirect' => '/ar-verify-registration'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyRegistration(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $storedCode = $request->session()->get('verification_code');
        $expiresAt = $request->session()->get('code_expires_at');
        $userId = $request->session()->get('verification_user_id');

        if (!$storedCode || now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الرمز'
            ], 400);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'الرمز غير صحيح'
            ], 400);
        }

        // Mark user as verified
        $user = User::find($userId);
        if ($user) {
            $user->verified = true;
            $user->save();
            
            // Log the user in
            Auth::login($user);
        }

        // Clear session
        $request->session()->forget(['verification_code', 'verification_email', 'verification_user_id', 'code_expires_at']);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح',
            'user_name' => $user->name
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'password' => $request->password
        ];

        // Check if email or phone
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->email;
        } else {
            $credentials['phone'] = $request->email;
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'redirect' => '/'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
        ], 401);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
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
            'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني'
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $storedCode = $request->session()->get('verification_code');
        $expiresAt = $request->session()->get('code_expires_at');

        if (!$storedCode || now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الرمز'
            ], 400);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'الرمز غير صحيح'
            ], 400);
        }

        $request->session()->put('code_verified', true);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!$request->session()->get('code_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'يجب التحقق من الرمز أولاً'
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
            'message' => 'تم تغيير كلمة المرور بنجاح'
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
