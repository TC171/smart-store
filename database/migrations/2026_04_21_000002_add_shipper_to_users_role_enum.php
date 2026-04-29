<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 'shipper' vào enum role của bảng users
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','staff','customer','shipper') DEFAULT 'customer'");
    }

    public function down(): void
    {
        // Đổi lại về enum gốc (chú ý: user nào đang là shipper sẽ bị lỗi nếu rollback)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','staff','customer') DEFAULT 'customer'");
    }
};
