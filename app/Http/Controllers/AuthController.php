<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        // Normalize inputs to avoid false unique collisions
        $request->merge([
            'email' => strtolower(trim((string) $request->email)),
            'username' => trim((string) $request->username),
        ]);

        // Debug: log email uniqueness check to diagnose false positives
        try {
            $normalizedEmail = $request->email;
            $existingCount = \DB::table('users')->where('email', $normalizedEmail)->count();
            \Log::info('Register email uniqueness check', ['email' => $normalizedEmail, 'count' => $existingCount]);
        } catch (\Throwable $e) {
            // ignore
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email:rfc|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'user_full_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create user but don't verify yet
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_full_name' => $request->user_full_name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'language' => $request->language ?? 'english',
            'gender' => $request->gender,
            'currency' => $request->currency,
            'verified' => false,
        ]);

        // Generate verification code and token
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(64);

        // Store verification code with token (expires in 15 minutes)
        EmailVerification::create([
            'email' => $user->email,
            'verification_code' => $verificationCode,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(15),
            'used' => false,
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(
                new VerificationCodeMail($user->user_full_name ?? $user->username, $verificationCode)
            );
        } catch (\Exception $e) {
            // Log error but don't fail registration
            \Log::error('Failed to send verification email: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please check your email for verification code.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'verified' => $user->verified,
            ],
        ], 201);
    }

    /**
     * Verify email with code
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'verification_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $verification = EmailVerification::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $verification) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code',
            ], 400);
        }

        // Mark verification as used
        $verification->update(['used' => true]);

        // Verify user
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'verified' => true,
                'email_verified_at' => Carbon::now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'verified' => $user->verified,
            ],
        ], 200);
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->verified) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified',
            ], 400);
        }

        // Generate new verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidate old codes
        EmailVerification::where('email', $request->email)
            ->where('used', false)
            ->update(['used' => true]);

        // Store new verification code with token
        $token = Str::random(64);
        EmailVerification::create([
            'email' => $user->email,
            'verification_code' => $verificationCode,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(15),
            'used' => false,
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(
                new VerificationCodeMail($user->user_full_name ?? $user->username, $verificationCode)
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully',
        ], 200);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $identifier = trim((string) ($request->input('username') ?? $request->input('email') ?? ''));
        if ($identifier === '') {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => [
                    'username' => ['The username field is required when email is not present.'],
                    'email' => ['The email field is required when username is not present.'],
                ],
            ], 422);
        }

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
                    'message' => 'Access denied',
                ], 403);
            }
        }

        $user = User::query()
            ->where(function ($q) use ($identifier) {
                if (str_contains($identifier, '@')) {
                    $q->where('email', $identifier);
                    return;
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'username')) {
                    $q->where('username', $identifier);
                }
                $q->orWhere('email', $identifier);
            })
            ->first();

        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Account temporarily locked',
            ], 423);
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            \App\Services\CrossDepartmentFlowService::recordFailedLoginAttempt(
                $identifier,
                $user?->id,
                $request->ip(),
                $request->userAgent()
            );

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (! $user->verified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email before logging in',
                'requires_verification' => true,
            ], 403);
        }

        if ($user->locked_at || $user->locked_until || $user->login_failures > 0) {
            $user->update([
                'locked_at' => null,
                'locked_until' => null,
                'lock_reason' => null,
                'login_failures' => 0,
            ]);
        }

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'user_full_name' => $user->user_full_name,
                'mobile' => $user->mobile,
                'address' => $user->address,
                'language' => $user->language,
                'verified' => $user->verified,
            ],
            'token' => $token,
        ], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ], 200);
    }

    /**
     * Request password reset code
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Invalidate old codes
        PasswordReset::where('email', $request->email)
            ->where('used', false)
            ->update(['used' => true]);

        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordReset::create([
            'email' => $request->email,
            'verification_code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
            'used' => false,
        ]);

        try {
            Mail::send('emails.password_reset', [
                'name' => $user->user_full_name ?? $user->username,
                'code' => $code,
            ], function ($message) use ($user) {
                $message->to($user->email)->subject('Password Reset Code - Tulip Store');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset email',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset code sent successfully',
        ], 200);
    }

    /**
     * Reset password using code
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $reset = PasswordReset::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $reset->update(['used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully',
        ], 200);
    }
}
