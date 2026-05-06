<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'adminfaysalvai@vai';
$password = '1999610577';

$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found by email.\n";
} else {
    echo "User found: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    if (Hash::check($password, $user->password)) {
        echo "Password check: SUCCESS\n";
    } else {
        echo "Password check: FAILED\n";
    }
    
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        echo "Auth::attempt: SUCCESS\n";
    } else {
        echo "Auth::attempt: FAILED\n";
    }
}
