<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = new User();
$user->password = '123456';
echo "Plain set: " . $user->password . "\n";

$user2 = new User();
$user2->password = Hash::make('123456');
echo "Hash::make set: " . $user2->password . "\n";
