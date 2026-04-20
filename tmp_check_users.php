<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check delivery user
$user = App\Models\User::find(84);
if ($user) {
    echo 'User ID 84: ' . $user->name . ' - role: ' . $user->role . "\n";
} else {
    echo "User ID 84 not found\n";
}

// Check all shipper users
$shippers = App\Models\User::where('role', 'shipper')->get();
echo 'Shipper users: ' . $shippers->count() . "\n";
foreach($shippers as $shipper) {
    echo 'ID: ' . $shipper->id . ' - ' . $shipper->name . ' - ' . $shipper->email . "\n";
}
