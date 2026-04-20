<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm cột delivery_status vào bảng orders.
 *
 * Lý do tách migration riêng: delivery_user_id đã được thêm ở migration trước
 * (2026_04_19_172122). Cột này cần thiết để shipper cập nhật tiến trình giao hàng
 * độc lập với order.status (dùng cho admin & customer).
 *
 * Các giá trị:
 *  assigned   → Admin vừa gán shipper
 *  picked_up  → Shipper đã nhận hàng từ kho
 *  delivering → Đang trên đường giao
 *  delivered  → Giao thành công
 *  failed     → Giao thất bại
 *  returned   → Đã trả về kho
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Chỉ thêm nếu chưa có (an toàn khi chạy lại)
            if (!Schema::hasColumn('orders', 'delivery_status')) {
                $table->string('delivery_status')->nullable()->after('delivery_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'delivery_status')) {
                $table->dropColumn('delivery_status');
            }
        });
    }
};