<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Trader;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoTraderSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'trader@demo.com';
        $password = 'password123';
        $baseUsername = 'demo_trader';

        $user = User::query()->where('email', $email)->first();
        if ($user) {
            $user->fill([
                'name' => 'Demo Trader',
                'user_full_name' => 'Demo Trader',
                'phone' => '+10000000001',
                'mobile' => '+10000000001',
                'password' => Hash::make($password),
                'verified' => true,
                'email_verified_at' => now(),
                'is_trader' => true,
            ])->save();
        } else {
            $username = $baseUsername;
            while (User::query()->where('username', $username)->exists()) {
                $username = $baseUsername.'_'.random_int(1000, 9999);
            }

            $user = User::create([
                'email' => $email,
                'username' => $username,
                'name' => 'Demo Trader',
                'user_full_name' => 'Demo Trader',
                'phone' => '+10000000001',
                'mobile' => '+10000000001',
                'password' => Hash::make($password),
                'verified' => true,
                'email_verified_at' => now(),
                'is_trader' => true,
            ]);
        }

        Trader::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name ?? 'Demo Trader',
                'company_name' => 'Demo Store',
                'contact_email' => $user->email,
                'contact_phone' => $user->phone ?? $user->mobile,
                'status' => Trader::STATUS_APPROVED,
                'commission_rate' => 0.10,
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
        if (Schema::hasColumn('stores', 'owner_id')) {
            $storeKey['owner_id'] = $user->id;
        } else {
            $storeKey['user_id'] = $user->id;
        }

        $orgId = null;
        if (Schema::hasColumn('stores', 'organization_id')) {
            $orgId = DB::table('organizations')->value('id');
            if (! $orgId) {
                $orgData = [
                    'name' => 'Demo Organization',
                    'slug' => 'demo-org-'.Str::lower(Str::random(6)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('organizations', 'status')) {
                    $orgData['status'] = 'active';
                }
                $orgId = DB::table('organizations')->insertGetId($orgData);
            }
        }

        $storeData = [
            'name' => 'Demo Store',
            'slug' => 'demo-store-'.Str::random(6),
            'description' => 'Demo store for local testing',
        ];
        if (Schema::hasColumn('stores', 'phone')) {
            $storeData['phone'] = $user->phone ?? $user->mobile;
        }
        if (Schema::hasColumn('stores', 'email')) {
            $storeData['email'] = $user->email;
        }
        if (Schema::hasColumn('stores', 'address')) {
            $storeData['address'] = 'Demo Address';
        }
        if (Schema::hasColumn('stores', 'commission_rate')) {
            $storeData['commission_rate'] = 0.10;
        }
        if (Schema::hasColumn('stores', 'is_featured')) {
            $storeData['is_featured'] = true;
        }
        if (Schema::hasColumn('stores', 'organization_id')) {
            $storeData['organization_id'] = $orgId;
        }
        if (Schema::hasColumn('stores', 'status')) {
            $hasOwnerId = Schema::hasColumn('stores', 'owner_id');
            $hasUserId = Schema::hasColumn('stores', 'user_id');
            if (Schema::hasColumn('stores', 'organization_id') && $hasOwnerId && ! $hasUserId) {
                $storeData['status'] = 'active';
            } else {
                $storeData['status'] = 'approved';
            }
        }

        if (Schema::hasColumn('stores', 'owner_id')) {
            $storeData['owner_id'] = $user->id;
        }
        if (Schema::hasColumn('stores', 'user_id')) {
            $storeData['user_id'] = $user->id;
        }
        Store::updateOrCreate($storeKey, $storeData);
    }
}
