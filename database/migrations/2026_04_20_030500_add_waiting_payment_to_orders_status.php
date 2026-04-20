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
        // Chuyển kiểu ENUM sang STRING tạm thời để tránh lỗi Data Truncated nếu có dữ liệu cũ không khớp
        // Hoặc định nghĩa lại ENUM mới bao gồm 'waiting_payment'
        // Lưu ý: Laravel change() trên ENUM có thể đòi hỏi DB native support hoặc doctrine/dbal.
        // Cách an toàn nhất là dùng DB::statement
        
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'waiting_payment', 'confirmed', 'shipping', 'completed', 'cancelled', 'refunded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'shipping', 'completed', 'cancelled', 'refunded') DEFAULT 'pending'");
    }
};
