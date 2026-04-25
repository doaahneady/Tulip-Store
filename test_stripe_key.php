<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Stripe Public Key: " . config('services.stripe.public') . "\n";
echo "Stripe Secret Key: " . config('services.stripe.secret') . "\n";
