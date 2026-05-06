<?php

use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::where('email', 'adminfaysalvai@vai')->first();
if ($user) {
    $user->password = '1999610577';
    $user->role = 'admin';
    $user->save();
    echo "User updated with plain text password (hashed by cast).\n";
} else {
    $user = User::create([
        'email' => 'adminfaysalvai@vai',
        'password' => '1999610577',
        'name' => 'Admin Faysal',
        'role' => 'admin'
    ]);
    echo "User created with plain text password (hashed by cast).\n";
}
