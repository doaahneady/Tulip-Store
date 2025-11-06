<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'user_full_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'language' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
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
            'verified' => false,
        ]);

        // Generate verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store verification code (expires in 15 minutes)
        EmailVerification::create([
            'email' => $user->email,
            'verification_code' => $verificationCode,
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
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please check your email for verification code.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'verified' => $user->verified,
            ]
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
                'errors' => $validator->errors()
            ], 422);
        }

        $verification = EmailVerification::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code'
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
            ]
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
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        
        if ($user->verified) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified'
            ], 400);
        }

        // Generate new verification code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Invalidate old codes
        EmailVerification::where('email', $request->email)
            ->where('used', false)
            ->update(['used' => true]);

        // Store new verification code
        EmailVerification::create([
            'email' => $user->email,
            'verification_code' => $verificationCode,
            'expires_at' => Carbon::now()->addMinutes(15),
            'used' => false,
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(
                new VerificationCodeMail($user->user_full_name ?? $user->username, $verificationCode)
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully'
        ], 200);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->verified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email before logging in',
                'requires_verification' => true
            ], 403);
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
            'token' => $token
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
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ], 200);
    }
}

