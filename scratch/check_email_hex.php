<?php

use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::where('email', 'like', '%adminfaysalvai%')->first();
if ($user) {
    echo "Email: " . bin2hex($user->email) . "\n";
    echo "Email raw: " . $user->email . "\n";
} else {
    echo "User not found.\n";
}
