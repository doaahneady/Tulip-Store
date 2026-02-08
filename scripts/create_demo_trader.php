<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'trader@demo.com';
$password = 'password123';
$baseUsername = 'demo_trader';

$user = App\Models\User::query()->where('email', $email)->first();
if ($user) {
    $user->fill([
        'name' => 'Demo Trader',
        'user_full_name' => 'Demo Trader',
        'phone' => '+10000000001',
        'mobile' => '+10000000001',
        'password' => Illuminate\Support\Facades\Hash::make($password),
        'verified' => true,
        'email_verified_at' => now(),
        'is_trader' => true,
    ])->save();
} else {
    $username = $baseUsername;
    while (App\Models\User::query()->where('username', $username)->exists()) {
        $username = $baseUsername.'_'.random_int(1000, 9999);
    }

    $user = App\Models\User::create([
        'email' => $email,
        'username' => $username,
        'name' => 'Demo Trader',
        'user_full_name' => 'Demo Trader',
        'phone' => '+10000000001',
        'mobile' => '+10000000001',
        'password' => Illuminate\Support\Facades\Hash::make($password),
        'verified' => true,
        'email_verified_at' => now(),
        'is_trader' => true,
    ]);
}

App\Models\Trader::updateOrCreate(
    ['user_id' => $user->id],
    [
        'name' => $user->name ?? 'Demo Trader',
        'company_name' => 'Demo Store',
        'contact_email' => $user->email,
        'contact_phone' => $user->phone ?? $user->mobile,
        'status' => App\Models\Trader::STATUS_APPROVED,
        'commission_rate' => 10,
        'payout_settings' => [
            'bank' => [
                'bank_name' => 'Demo Bank',
                'account_holder' => 'Demo Trader',
                'account_number' => '0000000000',
                'iban' => null,
            ],
            'business' => [
                'registration_number' => 'DEMO-REG',
                'tax_id' => 'DEMO-TAX',
                'contact_person' => 'Demo Trader',
                'business_address' => 'Demo Address',
            ],
        ],
    ]
);

$storeKey = [];
if (Illuminate\Support\Facades\Schema::hasColumn('stores', 'owner_id')) {
    $storeKey['owner_id'] = $user->id;
} else {
    $storeKey['user_id'] = $user->id;
}

$orgId = null;
if (Illuminate\Support\Facades\Schema::hasColumn('stores', 'organization_id')) {
    $orgId = Illuminate\Support\Facades\DB::table('organizations')->value('id');
    if (! $orgId) {
        $orgId = Illuminate\Support\Facades\DB::table('organizations')->insertGetId([
            'name' => 'Demo Organization',
            'slug' => 'demo-org-'.Illuminate\Support\Str::lower(Illuminate\Support\Str::random(6)),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

$storeData = [
    'name' => 'Demo Store',
    'slug' => 'demo-store-'.Illuminate\Support\Str::random(6),
    'description' => 'Demo store for local testing',
    'phone' => $user->phone ?? $user->mobile,
    'email' => $user->email,
    'address' => 'Demo Address',
    'status' => Illuminate\Support\Facades\Schema::hasColumn('stores', 'status') ? 'approved' : null,
    'commission_rate' => 10,
    'is_featured' => true,
    'organization_id' => $orgId,
];

if (Illuminate\Support\Facades\Schema::hasColumn('stores', 'owner_id')) {
    $storeData['owner_id'] = $user->id;
}
if (Illuminate\Support\Facades\Schema::hasColumn('stores', 'user_id')) {
    $storeData['user_id'] = $user->id;
}
if (! Illuminate\Support\Facades\Schema::hasColumn('stores', 'commission_rate')) {
    unset($storeData['commission_rate']);
}
if (! Illuminate\Support\Facades\Schema::hasColumn('stores', 'is_featured')) {
    unset($storeData['is_featured']);
}
if (! Illuminate\Support\Facades\Schema::hasColumn('stores', 'status')) {
    unset($storeData['status']);
}
if (! Illuminate\Support\Facades\Schema::hasColumn('stores', 'organization_id')) {
    unset($storeData['organization_id']);
}

App\Models\Store::updateOrCreate($storeKey, $storeData);

echo "created\n";
