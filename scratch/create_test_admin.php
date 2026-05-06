<?php

use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'admin@smartlookbd.com';
$password = '12345678';

$user = User::updateOrCreate(
    ['email' => $email],
    [
        'password' => $password,
        'name' => 'Admin Test',
        'role' => 'admin'
    ]
);

echo "New test admin created:\n";
echo "Email: " . $user->email . "\n";
echo "Pass: " . $password . "\n";
