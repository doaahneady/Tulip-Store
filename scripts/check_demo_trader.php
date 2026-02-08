<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::query()->where('email', 'trader@demo.com')->first();
echo 'user_id='.(string) ($u?->id ?? 'null').PHP_EOL;
echo 'is_trader='.(string) ($u?->is_trader ?? 'null').PHP_EOL;

$t = $u ? App\Models\Trader::query()->where('user_id', $u->id)->first() : null;
echo 'trader_status='.(string) ($t?->status ?? 'null').PHP_EOL;

