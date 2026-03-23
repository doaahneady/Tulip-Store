<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityFeed;
use App\Models\Notification;
use App\Models\SecurityAuditLog;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
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
            // Use stateful OAuth handshake (do not force stateless in callback)
            // because redirect() stores the state in the session.
            $googleUser = Socialite::driver('google')->user();

            // Find or create user
            $user = User::where('email', $googleUser->email)->first();

            if (! $user) {
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
                if (! $user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            // Log the user in
            Auth::login($user, true);

            // Welcome notification for the user
            if (Schema::hasTable('notifications')) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'system',
                    'title' => 'Welcome to Tulip Store',
                    'message' => 'Your account has been created via Google. Enjoy shopping!',
                    'icon' => 'fa-smile',
                    'color' => 'green',
                    'link' => '/profile',
                ]);
            }

            // Admin activity feed entry
            if (Schema::hasTable('activity_feeds')) {
                ActivityFeed::create([
                    'dashboard_type' => 'admin',
                    'activity_type' => 'user',
                    'action' => 'created',
                    'title' => 'New User Registration',
                    'description' => $user->name.' joined via Google OAuth',
                    'actor_type' => User::class,
                    'actor_id' => $user->id,
                    'target_type' => User::class,
                    'target_id' => $user->id,
                    'severity' => 'info',
                    'metadata' => [
                        'email' => $user->email,
                        'provider' => 'google',
                    ],
                ]);
            }

            // IT dashboard logs
            if (Schema::hasTable('system_logs')) {
                SystemLog::create([
                    'level' => 'info',
                    'action' => 'user_registered',
                    'message' => 'User registered via Google OAuth',
                    'user' => $user->email,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'metadata' => ['provider' => 'google'],
                ]);
            }
            if (Schema::hasTable('security_audit_logs')) {
                SecurityAuditLog::create([
                    'event_type' => 'account_created',
                    'user_type' => User::class,
                    'user_id' => $user->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'status' => 'success',
                    'description' => 'Account created via Google OAuth',
                    'metadata' => ['email' => $user->email],
                    'risk_level' => 'low',
                ]);
                SecurityAuditLog::create([
                    'event_type' => 'login_attempt',
                    'user_type' => User::class,
                    'user_id' => $user->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'status' => 'success',
                    'description' => 'User logged in via Google OAuth',
                    'metadata' => ['email' => $user->email],
                    'risk_level' => 'low',
                ]);
            }

            // Close popup and redirect parent window
            return view('auth.google-success', ['user' => $user]);

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: '.$e->getMessage());

            return redirect('/ar-login')->withErrors(['error' => 'فشل تسجيل الدخول عبر Google: '.$e->getMessage()]);
        }
    }
}
