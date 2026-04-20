<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('email', 'shipper@gmail.com')->first();
if($user){
    echo 'Shipper user exists: ' . $user->name . ' (' . $user->email . ') - ID: ' . $user->id . "\n";
    echo 'Password hash exists: ' . (!empty($user->password) ? 'yes' : 'no') . "\n";
} else {
    echo 'Shipper user not found' . "\n";
}
