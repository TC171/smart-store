<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Thêm cột shipper_id vào bảng orders
        if (!Schema::hasColumn('orders', 'shipper_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('shipper_id')->nullable()->after('user_id');
                $table->foreign('shipper_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        // 2. Thêm 'failed_delivery' vào enum status của orders
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'waiting_payment',
            'confirmed',
            'shipping',
            'failed_delivery',
            'completed',
            'cancelled',
            'refunded'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipper_id']);
            $table->dropColumn('shipper_id');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'waiting_payment',
            'confirmed',
            'shipping',
            'completed',
            'cancelled',
            'refunded'
        ) DEFAULT 'pending'");
    }
};
