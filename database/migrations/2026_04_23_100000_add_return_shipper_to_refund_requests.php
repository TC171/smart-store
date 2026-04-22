<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            // Shipper được admin chỉ định để lấy hàng hoàn
            $table->unsignedBigInteger('return_shipper_id')->nullable()->after('reviewed_by');
            // Thời gian shipper lấy hàng về
            $table->timestamp('picked_up_at')->nullable()->after('return_shipper_id');
            // Thời gian shipper mang hàng về shop
            $table->timestamp('returned_at')->nullable()->after('picked_up_at');
        });
    }

    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn(['return_shipper_id', 'picked_up_at', 'returned_at']);
        });
    }
};