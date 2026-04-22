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
        if (Schema::hasTable('orders')) {
            return;
        }

        Schema::create('orders', function (Blueprint $table) {
    $table->id();

    // khách hàng
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

    // shipper
    $table->foreignId('shipper_id')->nullable()->constrained('shippers')->nullOnDelete();

    // thông tin đơn
    $table->string('order_number')->unique();
    $table->decimal('total_amount', 12, 2)->default(0);

    // trạng thái
    $table->enum('status', [
        'pending',
        'confirmed',
        'shipping',
        'completed',
        'cancelled'
    ])->default('pending');

    $table->enum('payment_status', [
        'unpaid',
        'paid',
        'refunded'
    ])->default('unpaid');

    // thông tin giao hàng
    $table->string('shipping_name')->nullable();
    $table->string('shipping_phone')->nullable();
    $table->text('shipping_address')->nullable();

    $table->text('note')->nullable();

    $table->timestamp('completed_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
