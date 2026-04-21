<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\Subscriber::count();
$emails = \App\Models\Subscriber::pluck('email')->toArray();

echo "Total subscribers: " . $count . "\n";
echo "Emails: " . implode(', ', $emails) . "\n";
