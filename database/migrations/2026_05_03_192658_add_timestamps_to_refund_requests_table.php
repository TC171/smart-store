<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            // Bổ sung ID của Shipper đi lấy hàng hoàn nếu chưa có
            if (!Schema::hasColumn('refund_requests', 'return_shipper_id')) {
                $table->bigInteger('return_shipper_id')->unsigned()->nullable()->after('reviewed_by');
                $table->foreign('return_shipper_id')->references('id')->on('users')->onDelete('set null');
            }

            // Bổ sung cột lưu vết thời gian lấy hàng từ khách
            if (!Schema::hasColumn('refund_requests', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('return_shipper_id');
            }

            // Bổ sung cột lưu vết thời gian shipper bàn giao hàng về cho Shop
            if (!Schema::hasColumn('refund_requests', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('picked_up_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropForeign(['return_shipper_id']);
            $table->dropColumn(['return_shipper_id', 'picked_up_at', 'returned_at']);
        });
    }
};