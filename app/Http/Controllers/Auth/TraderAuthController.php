<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TraderOtpMail;
use App\Models\EmailVerification;
use App\Models\SystemLog;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TraderAuthController extends Controller
{
    /**
     * Check if email is available for registration
     */
    public function checkEmailAvailability(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $exists = User::where('email', strtolower($request->email))->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'هذا البريد الإلكتروني مستخدم بالفعل' : 'البريد متاح'
        ]);
    }

    /**
     * Send OTP to trader email
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);
        
        // Store in session for verification
        $request->session()->put('trader_registration_otp', [
            'email' => strtolower($request->email),
            'code' => $otp,
            'expires_at' => $expiresAt
        ]);

        try {
            Mail::to($request->email)->send(new TraderOtpMail($otp));
            return response()->json(['success' => true, 'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'فشل إرسال البريد الإلكتروني: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $stored = $request->session()->get('trader_registration_otp');

        if (!$stored || $stored['email'] !== strtolower($request->email)) {
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على رمز لهذا البريد'], 400);
        }

        if (now()->isAfter($stored['expires_at'])) {
            return response()->json(['success' => false, 'message' => 'رمز التحقق منتهي الصلاحية'], 400);
        }

        if ((int)$request->otp !== (int)$stored['code']) {
            return response()->json(['success' => false, 'message' => 'رمز التحقق غير صحيح'], 400);
        }

        // Mark as verified in session
        $request->session()->put('trader_registration_otp_verified', true);

        return response()->json(['success' => true, 'message' => 'تم التحقق بنجاح']);
    }

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
            'business_name_en' => 'required|string|min:2|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'business_name_ar' => 'required|string|min:2|max:255|regex:/^[\x{0600}-\x{06FF}0-9\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|regex:/^09\d{8}$/',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'contact_person' => 'required|string|min:3|max:255',
            'business_address' => 'required|string|min:3|max:500',
            'bank_name' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'iban' => 'nullable|string|max:50',
        ], [
            'business_name_en.min' => 'الاسم التجاري بالإنجليزية يجب أن يكون 3 محارف على الأقل',
            'business_name_en.regex' => 'الاسم التجاري بالإنجليزية يجب أن يحتوي على أحرف إنجليزية وأرقام فقط',
            'business_name_ar.required' => 'الاسم التجاري بالعربية مطلوب',
            'business_name_ar.min' => 'الاسم التجاري بالعربية يجب أن يكون 3 محارف على الأقل',
            'business_name_ar.regex' => 'الاسم التجاري بالعربية يجب أن يحتوي على أحرف عربية وأرقام فقط',
            'phone.regex' => 'رقم الهاتف يجب أن يبدأ بـ 09 ويتكون من 10 أرقام',
            'password.min' => 'كلمة المرور يجب أن تكون 8 محارف على الأقل',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، حرف صغير، رقم، ورمز خاص',
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

        $traderData = [
            'user_id' => $user->id,
            'name' => $validated['business_name_en'],
            'company_name' => $validated['business_name_ar'] ?? null,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
            'status' => Trader::STATUS_PENDING,
            'payout_settings' => [
                'bank' => [
                    'bank_name' => $validated['bank_name'] ?? null,
                    'account_holder' => $validated['account_holder'] ?? null,
                    'account_number' => $validated['account_number'] ?? null,
                    'iban' => $validated['iban'] ?? null,
                ],
                'business' => [
                    'registration_number' => $validated['registration_number'] ?? null,
                    'tax_id' => $validated['tax_id'] ?? null,
                    'contact_person' => $validated['contact_person'],
                    'business_address' => $validated['business_address'],
                ],
            ],
        ];
        if (Schema::hasColumn('traders', 'account_name_en')) {
            $traderData['account_name_en'] = $validated['business_name_en'];
        }
        if (Schema::hasColumn('traders', 'account_name_ar')) {
            $traderData['account_name_ar'] = $validated['business_name_ar'] ?? $validated['business_name_en'];
        }
        if (Schema::hasColumn('traders', 'email')) {
            $traderData['email'] = $user->email;
        }
        if (Schema::hasColumn('traders', 'phone')) {
            $traderData['phone'] = $user->phone;
        }
        if (Schema::hasColumn('traders', 'responsible_name')) {
            $traderData['responsible_name'] = $validated['contact_person'];
        }
        if (Schema::hasColumn('traders', 'work_address')) {
            $traderData['work_address'] = $validated['business_address'];
        }
        if (Schema::hasColumn('traders', 'activity')) {
            $traderData['activity'] = 'mart';
        }
        if (Schema::hasColumn('traders', 'password')) {
            $traderData['password'] = Hash::make($validated['password']);
        }
        $trader = Trader::create($traderData);

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
            'message' => 'تم إرسال طلب إنشاء الحساب. يجب انتظار موافقة خدمة العملاء ثم تسجيل الدخول.',
            'redirect' => route('trader.login.form'),
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
        // Check if OTP was verified in session
        if (!$request->session()->get('trader_registration_otp_verified')) {
            return redirect()->back()->withErrors(['otp' => 'يرجى التحقق من بريدك الإلكتروني أولاً'])->withInput();
        }

        $validated = $request->validate([
            'business_name_en' => 'required|string|min:2|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'business_name_ar' => 'required|string|min:2|max:255|regex:/^[\x{0600}-\x{06FF}0-9\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|regex:/^09\d{8}$/',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'contact_person' => 'required|string|min:3|max:255',
            'business_address' => 'required|string|min:3|max:500',
            'business_logo' => 'required|file|mimes:jpg,jpeg,png,heic,heif|max:2048',
            'owner_id_card' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:5120',
            'business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:5120',
            'tax_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif|max:5120',
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
            $documents['business_logo'] = Storage::disk('public')->putFile($dir, $request->file('business_logo'));
        }
        if ($request->file('business_license')) {
            $documents['business_license'] = Storage::disk('public')->putFile($dir, $request->file('business_license'));
        }
        if ($request->file('tax_certificate')) {
            $documents['tax_certificate'] = Storage::disk('public')->putFile($dir, $request->file('tax_certificate'));
        }
        if ($request->file('owner_id_card')) {
            $documents['owner_id_card'] = Storage::disk('public')->putFile($dir, $request->file('owner_id_card'));
        }

        $traderData = [
            'user_id' => $user->id,
            'name' => $validated['business_name_en'],
            'company_name' => $validated['business_name_ar'] ?? null,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone,
            'status' => Trader::STATUS_PENDING,
            'payout_settings' => [
                'bank' => [
                    'bank_name' => $validated['bank_name'] ?? null,
                    'account_holder' => $validated['account_holder'] ?? null,
                    'account_number' => $validated['account_number'] ?? null,
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
        ];
        if (Schema::hasColumn('traders', 'account_name_en')) {
            $traderData['account_name_en'] = $validated['business_name_en'];
        }
        if (Schema::hasColumn('traders', 'account_name_ar')) {
            $traderData['account_name_ar'] = $validated['business_name_ar'] ?? $validated['business_name_en'];
        }
        if (Schema::hasColumn('traders', 'email')) {
            $traderData['email'] = $user->email;
        }
        if (Schema::hasColumn('traders', 'phone')) {
            $traderData['phone'] = $user->phone;
        }
        if (Schema::hasColumn('traders', 'responsible_name')) {
            $traderData['responsible_name'] = $validated['contact_person'];
        }
        if (Schema::hasColumn('traders', 'work_address')) {
            $traderData['work_address'] = $validated['business_address'];
        }
        if (Schema::hasColumn('traders', 'activity')) {
            $traderData['activity'] = 'mart';
        }
        if (Schema::hasColumn('traders', 'password')) {
            $traderData['password'] = Hash::make($validated['password']);
        }
        $trader = Trader::create($traderData);

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

        $request->session()->forget(['trader_registration_otp', 'trader_registration_otp_verified']);

        return redirect()->route('trader.login.form')->with('success', 'تم إرسال طلب التسجيل بنجاح. يرجى انتظار موافقة الإدارة، وسيتم إعلامك عبر البريد الإلكتروني.');
    }
}
