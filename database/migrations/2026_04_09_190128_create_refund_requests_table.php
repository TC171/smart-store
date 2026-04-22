<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['refund', 'return'])->default('return'); // refund=hoàn tiền, return=hoàn hàng
            $table->string('return_code')->nullable()->unique(); // Mã trả hàng
            $table->text('reason');  // Lý do hoàn hàng
            $table->string('video_path')->nullable(); // Đường dẫn video bóc hàng
            $table->string('video_original_name')->nullable();
            
            // Cập nhật các trạng thái: pending -> chờ gửi hàng -> đã gửi/chờ duyệt -> hoàn thành
            $table->string('status')->default('pending'); 

            $table->text('admin_note')->nullable(); // Ghi chú của admin
            $table->timestamp('reviewed_at')->nullable(); // Thời gian admin xem xét
            $table->unsignedBigInteger('reviewed_by')->nullable(); // ID của admin đã duyệt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
