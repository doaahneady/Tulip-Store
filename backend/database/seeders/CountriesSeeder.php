<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $list = [];
        $local = database_path('seeders/data/countries.json');
        if (File::exists($local)) {
            $list = json_decode(File::get($local), true) ?: [];
        }
        if (!$list) {
            try {
                $resp = file_get_contents('https://cdn.jsdelivr.net/npm/country-telephone-data@0.6.22/data/countries.json');
                $list = json_decode($resp, true) ?: [];
            } catch (\Throwable $e) {
                $list = [];
            }
        }
        if (!$list) return;
        foreach ($list as $c) {
            $name = $c['name'] ?? null;
            $iso2 = $c['iso2'] ?? null;
            $dial = isset($c['dial_code']) ? ltrim((string)$c['dial_code'], '+') : (isset($c['dialCode']) ? (string)$c['dialCode'] : null);
            if (!$name || !$iso2 || !$dial) continue;
            Country::updateOrCreate(
                ['iso2' => strtolower($iso2)],
                ['name' => $name, 'dial_code' => $dial]
            );
        }
    }
}
