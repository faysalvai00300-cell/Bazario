<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = \App\Models\Setting::first();
echo "Pathao Client ID: " . ($s->pathao_client_id ?? 'NOT SET') . "\n";
echo "Pathao Secret: " . ($s->pathao_client_secret ?? 'NOT SET') . "\n";
echo "Pathao User: " . ($s->pathao_username ?? 'NOT SET') . "\n";
echo "RedX Token: " . ($s->redx_api_token ?? 'NOT SET') . "\n";
