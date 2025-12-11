<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Find or create user
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'username' => $googleUser->email, // Use email as username
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(24)), // Random password
                    'email_verified_at' => now(), // Auto-verify Google users
                    'google_id' => $googleUser->id,
                ]);
            } else {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }
            
            // Log the user in
            Auth::login($user, true);
            
            // Close popup and redirect parent window
            return view('auth.google-success', ['user' => $user]);
            
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/ar-login')->withErrors(['error' => 'فشل تسجيل الدخول عبر Google: ' . $e->getMessage()]);
        }
    }
}
