<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use App\Models\SystemLog;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TraderAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.trader-login');
    }

    public function showRegisterForm()
    {
        return view('auth.trader-register');
    }

    public function logout(Request $request)
    {
        Auth::guard('trader')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('trader.login.form')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
        if (! $user->is_trader) {
            return response()->json(['success' => false, 'message' => 'Not a trader account'], 403);
        }
        $trader = Trader::where('user_id', $user->id)->first();
        if (! $trader) {
            return response()->json(['success' => false, 'message' => 'Trader profile not found'], 404);
        }
        if ($trader->status === Trader::STATUS_PENDING) {
            return response()->json(['success' => false, 'message' => 'Your account is pending approval'], 403);
        }
        if ($trader->status === Trader::STATUS_REJECTED) {
            return response()->json(['success' => false, 'message' => 'Your account was rejected. Contact support.'], 403);
        }
        if ($trader->status === Trader::STATUS_SUSPENDED) {
            return response()->json(['success' => false, 'message' => 'Your account is suspended. Contact support.'], 403);
        }
        $token = $user->createToken('trader-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'redirect' => '/trader/dashboard',
        ]);
    }

    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'business_name_en' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'business_name_ar' => 'required|string|min:3|max:255|regex:/^[\x{0621}-\x{064A}0-9\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|regex:/^09\d{8}$/',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
            'registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'contact_person' => 'required|string|min:3|max:255',
            'business_address' => 'required|string|min:3|max:500',
            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'iban' => 'nullable|string|max:50',
        ], [
            'business_name_en.min' => 'الاسم التجاري بالإنجليزية يجب أن يكون 3 محارف على الأقل',
            'business_name_en.regex' => 'الاسم التجاري بالإنجليزية يجب أن يحتوي على أحرف إنجليزية وأرقام فقط',
            'business_name_ar.required' => 'الاسم التجاري بالعربية مطلوب',
            'business_name_ar.min' => 'الاسم التجاري بالعربية يجب أن يكون 3 محارف على الأقل',
            'business_name_ar.regex' => 'الاسم التجاري بالعربية يجب أن يحتوي على أحرف عربية وأرقام فقط',
            'phone.regex' => 'رقم الهاتف يجب أن يبدأ بـ 09 ويتكون من 10 أرقام',
            'password.min' => 'كلمة المرور يجب أن تكون 8 محارف على الأقل',
            'contact_person.min' => 'اسم الشخص المسؤول يجب أن يكون 3 محارف على الأقل',
            'business_address.min' => 'عنوان العمل يجب أن يكون 3 محارف على الأقل',
        ]);

        $user = User::create([
            'name' => $validated['business_name_en'],
            'username' => Str::slug($validated['business_name_en']).'_'.Str::random(5),
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'verified' => false,
            'is_trader' => true,
        ]);

        $trader = Trader::create([
            'user_id' => $user->id,
            'name' => $validated['business_name_en'],
            'company_name' => $validated['business_name_ar'] ?? null,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
            'status' => Trader::STATUS_PENDING,
            'payout_settings' => [
                'bank' => [
                    'bank_name' => $validated['bank_name'],
                    'account_holder' => $validated['account_holder'],
                    'account_number' => $validated['account_number'],
                    'iban' => $validated['iban'] ?? null,
                ],
                'business' => [
                    'registration_number' => $validated['registration_number'] ?? null,
                    'tax_id' => $validated['tax_id'] ?? null,
                    'contact_person' => $validated['contact_person'],
                    'business_address' => $validated['business_address'],
                ],
            ],
        ]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(64);
        EmailVerification::create([
            'email' => $user->email,
            'verification_code' => $code,
            'token' => $token,
            'expires_at' => now()->addMinutes(15),
            'used' => false,
        ]);
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name ?? $user->username));
        } catch (\Throwable $e) {
        }

        try {
            SystemLog::create([
                'level' => 'info',
                'action' => 'trader_registration_submitted',
                'message' => 'New trader registration submitted (API)',
                'user' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'trader_id' => $trader->id,
                    'company' => $trader->name,
                ],
            ]);
        } catch (\Throwable $e) {
        }

        return response()->json([
            'success' => true,
            'message' => "Registration submitted. You'll be notified once approved.",
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة'])->withInput($request->only('email'));
        }

        if (! $user->is_trader) {
            return back()->withErrors(['email' => 'الحساب ليس لحساب تاجر'])->withInput($request->only('email'));
        }

        $trader = Trader::where('user_id', $user->id)->first();
        if (! $trader) {
            return back()->withErrors(['email' => 'لم يتم العثور على ملف التاجر لهذا الحساب'])->withInput($request->only('email'));
        }

        if ($trader->status === Trader::STATUS_PENDING) {
            return back()->withErrors(['email' => 'حسابك قيد المراجعة من قبل الإدارة'])->withInput($request->only('email'));
        }
        if ($trader->status === Trader::STATUS_REJECTED) {
            return back()->withErrors(['email' => 'تم رفض حسابك. يرجى التواصل مع الدعم'])->withInput($request->only('email'));
        }
        if ($trader->status === Trader::STATUS_SUSPENDED) {
            return back()->withErrors(['email' => 'تم تعليق حسابك. يرجى التواصل مع الدعم'])->withInput($request->only('email'));
        }

        Auth::guard('web')->logout();
        Auth::guard('trader')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect('/trader/dashboard');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name_en' => 'required|string|min:3|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'business_name_ar' => 'required|string|min:3|max:255|regex:/^[\x{0621}-\x{064A}0-9\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|regex:/^09\d{8}$/',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],

            'registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'contact_person' => 'required|string|min:3|max:255',
            'business_address' => 'required|string|min:3|max:500',
            'business_logo' => 'required|image|max:2048',

            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'iban' => 'nullable|string|max:50',

            'business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tax_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'owner_id_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'business_name_en.min' => 'الاسم التجاري بالإنجليزية يجب أن يكون 3 محارف على الأقل',
            'business_name_en.regex' => 'الاسم التجاري بالإنجليزية يجب أن يحتوي على أحرف إنجليزية وأرقام فقط',
            'business_name_ar.required' => 'الاسم التجاري بالعربية مطلوب',
            'business_name_ar.min' => 'الاسم التجاري بالعربية يجب أن يكون 3 محارف على الأقل',
            'business_name_ar.regex' => 'الاسم التجاري بالعربية يجب أن يحتوي على أحرف عربية وأرقام فقط',
            'phone.regex' => 'رقم الهاتف يجب أن يبدأ بـ 09 ويتكون من 10 أرقام',
            'password.min' => 'كلمة المرور يجب أن تكون 8 محارف على الأقل',
            'contact_person.min' => 'اسم الشخص المسؤول يجب أن يكون 3 محارف على الأقل',
            'business_address.min' => 'عنوان العمل يجب أن يكون 3 محارف على الأقل',
            'business_logo.required' => 'يرجى رفع شعار العمل',
            'owner_id_card.required' => 'يرجى رفع هوية المالك',
        ]);

        $user = User::create([
            'name' => $validated['business_name_en'],
            'username' => Str::slug($validated['business_name_en']).'_'.Str::random(5),
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'verified' => false,
            'is_trader' => true,
        ]);

        $documents = [];
        $dir = 'trader_documents/'.$user->id;
        if ($request->file('business_logo')) {
            $documents['business_logo'] = Storage::disk('local')->putFile($dir, $request->file('business_logo'));
        }
        if ($request->file('business_license')) {
            $documents['business_license'] = Storage::disk('local')->putFile($dir, $request->file('business_license'));
        }
        if ($request->file('tax_certificate')) {
            $documents['tax_certificate'] = Storage::disk('local')->putFile($dir, $request->file('tax_certificate'));
        }
        if ($request->file('owner_id_card')) {
            $documents['owner_id_card'] = Storage::disk('local')->putFile($dir, $request->file('owner_id_card'));
        }

        $trader = Trader::create([
            'user_id' => $user->id,
            'name' => $validated['business_name_en'],
            'company_name' => $validated['business_name_ar'] ?? null,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
            'status' => Trader::STATUS_PENDING,
            'payout_settings' => [
                'bank' => [
                    'bank_name' => $validated['bank_name'],
                    'account_holder' => $validated['account_holder'],
                    'account_number' => $validated['account_number'],
                    'iban' => $validated['iban'] ?? null,
                ],
                'business' => [
                    'registration_number' => $validated['registration_number'] ?? null,
                    'tax_id' => $validated['tax_id'] ?? null,
                    'contact_person' => $validated['contact_person'],
                    'business_address' => $validated['business_address'],
                ],
                'documents' => $documents,
            ],
        ]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(64);
        EmailVerification::create([
            'email' => $user->email,
            'verification_code' => $code,
            'token' => $token,
            'expires_at' => now()->addMinutes(15),
            'used' => false,
        ]);
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name ?? $user->username));
        } catch (\Throwable $e) {
        }

        try {
            SystemLog::create([
                'level' => 'info',
                'action' => 'trader_registration_submitted',
                'message' => 'New trader registration submitted',
                'user' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'trader_id' => $trader->id,
                    'company' => $trader->name,
                ],
            ]);
        } catch (\Throwable $e) {
        }

        return redirect()->route('trader.login.form')->with('success', 'تم استلام طلب التسجيل. سيتم إشعارك بعد المراجعة.');
    }
}
