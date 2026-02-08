<?php

require __DIR__.'/../vendor/autoload.php';

echo class_exists(Database\Seeders\DemoTraderSeeder::class) ? "exists\n" : "missing\n";

