<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('email', 'shipper@gmail.com')->first();
if($user){
    $user->password = Illuminate\Support\Facades\Hash::make('123456');
    $user->save();
    echo 'Password updated for shipper user' . "\n";
}
