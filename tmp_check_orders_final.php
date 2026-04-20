<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$orders = App\Models\Order::where('delivery_user_id', 84)->where('delivery_status', 'assigned')->count();
echo 'Orders assigned to shipper ID 84: ' . $orders . "\n";
