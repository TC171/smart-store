<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('email', 'shipper@test.com')->first();

if ($user) {
    echo "User found: " . $user->name . " role: " . $user->role . "\n";
} else {
    echo "User not found\n";
}