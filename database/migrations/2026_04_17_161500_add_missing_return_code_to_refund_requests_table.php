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
        // Safe check to avoid duplicate column errors if a user runs migrate:fresh in the future
        if (Schema::hasTable('refund_requests') && !Schema::hasColumn('refund_requests', 'return_code')) {
            Schema::table('refund_requests', function (Blueprint $table) {
                $table->string('return_code')->nullable()->unique()->after('type')->comment('Mã trả hàng');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('refund_requests') && Schema::hasColumn('refund_requests', 'return_code')) {
            Schema::table('refund_requests', function (Blueprint $table) {
                $table->dropColumn('return_code');
            });
        }
    }
};
