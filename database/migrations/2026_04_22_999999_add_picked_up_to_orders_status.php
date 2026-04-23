<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'waiting_payment',
            'confirmed',
            'picked_up',
            'shipping',
            'failed_delivery',
            'completed',
            'cancelled',
            'refunded'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
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
};
