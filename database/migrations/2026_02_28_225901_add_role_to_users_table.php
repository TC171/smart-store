<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Kiểm tra nếu cột 'role' chưa tồn tại
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('customer')->after('password');
        }

        // Kiểm tra nếu cột 'status' chưa tồn tại
        if (!Schema::hasColumn('users', 'status')) {
            $table->boolean('status')->default(1)->after('role');
        }
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status']);
        });
    }
};