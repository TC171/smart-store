<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$orders = App\Models\Order::whereNotNull('delivery_user_id')->get();
echo 'Total orders with delivery_user_id: ' . $orders->count() . "\n";
foreach($orders as $order) {
    echo 'Order #' . $order->id . ' - delivery_user_id: ' . $order->delivery_user_id . ' - delivery_status: ' . ($order->delivery_status ?? 'null') . ' - status: ' . $order->status . "\n";
}
