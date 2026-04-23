<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Đang kiểm tra và cập nhật Database...\n";
    
    // Thêm cột email nếu thiếu
    if (!Schema::hasColumn('orders', 'email')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('email')->nullable()->after('user_id');
        });
        echo "- Đã thêm cột 'email' vào bảng 'orders'.\n";
    }

    // Thêm cột payment_method nếu thiếu
    if (!Schema::hasColumn('orders', 'payment_method')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('payment_status');
        });
        echo "- Đã thêm cột 'payment_method' vào bảng 'orders'.\n";
    }

    echo "Cập nhật hoàn tất! Bạn có thể thử đặt hàng lại ngay bây giờ.\n";
} catch (\Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
