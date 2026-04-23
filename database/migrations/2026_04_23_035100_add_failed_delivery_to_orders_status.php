<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm 'failed_delivery' vào danh sách ENUM
        // Vẫn giữ 'waiting_payment' để tránh lỗi dữ liệu cũ nếu có, nhưng sẽ không hiển thị trên UI
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'waiting_payment', 'confirmed', 'shipping', 'failed_delivery', 'completed', 'cancelled', 'refunded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'waiting_payment', 'confirmed', 'shipping', 'completed', 'cancelled', 'refunded') DEFAULT 'pending'");
    }
};
